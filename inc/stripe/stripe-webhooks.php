<?php
/**
 * Stripe Webhookハンドラー
 *
 * エンドポイント: https://arigatoh.org/wp-json/arigatou/v1/stripe-webhook
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * REST APIエンドポイント登録
 */
function arigatou_register_stripe_webhook_endpoint() {
    register_rest_route('arigatou/v1', '/stripe-webhook', array(
        'methods'             => 'POST',
        'callback'            => 'arigatou_handle_stripe_webhook',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'arigatou_register_stripe_webhook_endpoint');

/**
 * Webhookハンドラー
 *
 * @param WP_REST_Request $request
 * @return WP_REST_Response
 */
function arigatou_handle_stripe_webhook(WP_REST_Request $request) {
    $payload = $request->get_body();
    $sig_header = isset($_SERVER['HTTP_STRIPE_SIGNATURE']) ? $_SERVER['HTTP_STRIPE_SIGNATURE'] : '';

    // 署名検証
    $verification = Arigatou_Stripe_API::verify_webhook_signature($payload, $sig_header);

    if (is_wp_error($verification)) {
        error_log('Stripe Webhook署名検証エラー: ' . $verification->get_error_message());
        return new WP_REST_Response(array('error' => $verification->get_error_message()), 400);
    }

    $event = json_decode($payload, true);

    if (!isset($event['type']) || !isset($event['data']['object'])) {
        return new WP_REST_Response(array('error' => 'Invalid event format'), 400);
    }

    $event_type = $event['type'];
    $object = $event['data']['object'];

    // イベントタイプに応じて処理
    switch ($event_type) {
        case 'checkout.session.completed':
            arigatou_handle_checkout_completed($object);
            break;

        case 'customer.subscription.created':
        case 'customer.subscription.updated':
            arigatou_handle_subscription_updated($object);
            break;

        case 'customer.subscription.deleted':
            arigatou_handle_subscription_deleted($object);
            break;

        case 'invoice.payment_succeeded':
            arigatou_handle_payment_succeeded($object);
            break;

        case 'invoice.payment_failed':
            arigatou_handle_payment_failed($object);
            break;
    }

    return new WP_REST_Response(array('received' => true), 200);
}

/**
 * Checkout完了時の処理
 *
 * @param array $session
 */
function arigatou_handle_checkout_completed($session) {
    $mode = isset($session['mode']) ? $session['mode'] : 'subscription';

    // スポット決済（単発購入）の場合
    if ($mode === 'payment') {
        arigatou_handle_spot_payment_completed($session);
        return;
    }

    // サブスクリプションの場合
    // メタデータからユーザーID取得
    $user_id = isset($session['metadata']['wp_user_id']) ? intval($session['metadata']['wp_user_id']) : 0;
    $plan_type = isset($session['metadata']['plan_type']) ? $session['metadata']['plan_type'] : '';
    $is_new_user = false;

    // メタデータにない場合はCustomer IDから検索
    if (!$user_id && !empty($session['customer'])) {
        $user_id = arigatou_get_user_by_customer_id($session['customer']);
    }

    // まだユーザーが見つからない場合、ゲスト決済としてメールアドレスからユーザー作成
    if (!$user_id) {
        $email = '';

        // customer_details.email から取得
        if (!empty($session['customer_details']['email'])) {
            $email = $session['customer_details']['email'];
        }
        // metadata.guest_email から取得（フォールバック）
        elseif (!empty($session['metadata']['guest_email'])) {
            $email = $session['metadata']['guest_email'];
        }

        if (!empty($email)) {
            $user_id = arigatou_get_or_create_user_by_email($email, $is_new_user);

            // Stripe Customer IDを紐付け
            if ($user_id && !empty($session['customer'])) {
                update_user_meta($user_id, '_stripe_customer_id', $session['customer']);
            }
        }
    }

    if (!$user_id) {
        error_log('Stripe Webhook: ユーザーが見つかりません - ' . print_r($session, true));
        return;
    }

    // Subscriptionがある場合は情報を取得
    if (!empty($session['subscription'])) {
        $subscription = Arigatou_Stripe_API::get_subscription($session['subscription']);

        if (!is_wp_error($subscription)) {
            arigatou_update_user_subscription($user_id, $subscription, $plan_type);
        }
    }

    // ウェルカムメール送信（新規ユーザーはパスワード付きメールが別途送信されるのでスキップ）
    if (!$is_new_user) {
        arigatou_send_welcome_email($user_id, $plan_type);
    }
}

/**
 * スポット決済完了時の処理
 *
 * @param array $session
 */
function arigatou_handle_spot_payment_completed($session) {
    $customer_email = isset($session['customer_details']['email']) ? $session['customer_details']['email'] : '';
    $customer_name = isset($session['customer_details']['name']) ? $session['customer_details']['name'] : '';
    $product_type = isset($session['metadata']['product_type']) ? $session['metadata']['product_type'] : '';
    $product_name = isset($session['metadata']['product_name']) ? $session['metadata']['product_name'] : '';
    $amount_total = isset($session['amount_total']) ? $session['amount_total'] : 0;

    if (empty($customer_email)) {
        error_log('Stripe Webhook: スポット決済でメールアドレスがありません - ' . print_r($session, true));
        return;
    }

    // DB保存
    arigatou_save_spot_payment(array(
        'customer_email'   => $customer_email,
        'customer_name'    => $customer_name,
        'product_type'     => $product_type,
        'product_name'     => $product_name,
        'amount'           => $amount_total,
        'stripe_session_id' => $session['id'],
    ));

    // 購入者にメール送信
    arigatou_send_spot_purchase_email($customer_email, $customer_name, $product_name, $amount_total);

    // 管理者にメール送信
    arigatou_send_spot_purchase_admin_email($customer_email, $customer_name, $product_name, $amount_total);
}

/**
 * Subscription更新時の処理
 *
 * @param array $subscription
 */
function arigatou_handle_subscription_updated($subscription) {
    $customer_id = $subscription['customer'];
    $user_id = arigatou_get_user_by_customer_id($customer_id);

    if (!$user_id) {
        error_log('Stripe Webhook: Customer IDからユーザーが見つかりません - ' . $customer_id);
        return;
    }

    arigatou_update_user_subscription($user_id, $subscription);
}

/**
 * Subscription削除時の処理
 *
 * @param array $subscription
 */
function arigatou_handle_subscription_deleted($subscription) {
    $customer_id = $subscription['customer'];
    $user_id = arigatou_get_user_by_customer_id($customer_id);

    if (!$user_id) {
        return;
    }

    arigatou_clear_user_subscription($user_id);

    // 解約完了メール送信
    arigatou_send_cancellation_email($user_id);
}

/**
 * 支払い成功時の処理
 *
 * @param array $invoice
 */
function arigatou_handle_payment_succeeded($invoice) {
    // 初回支払いはcheckout.session.completedで処理されるのでスキップ
    if ($invoice['billing_reason'] === 'subscription_create') {
        return;
    }

    $customer_id = $invoice['customer'];
    $user_id = arigatou_get_user_by_customer_id($customer_id);

    if (!$user_id) {
        return;
    }

    // サブスクリプション情報を更新
    if (!empty($invoice['subscription'])) {
        $subscription = Arigatou_Stripe_API::get_subscription($invoice['subscription']);

        if (!is_wp_error($subscription)) {
            arigatou_update_user_subscription($user_id, $subscription);
        }
    }

    // 更新完了メール送信
    arigatou_send_renewal_email($user_id);
}

/**
 * 支払い失敗時の処理
 *
 * @param array $invoice
 */
function arigatou_handle_payment_failed($invoice) {
    $customer_id = $invoice['customer'];
    $user_id = arigatou_get_user_by_customer_id($customer_id);

    if (!$user_id) {
        return;
    }

    // ステータスを更新
    update_user_meta($user_id, '_subscription_status', 'past_due');

    // 支払い失敗メール送信
    arigatou_send_payment_failed_email($user_id);
}

/**
 * ウェルカムメール送信
 *
 * @param int    $user_id
 * @param string $plan_type
 */
function arigatou_send_welcome_email($user_id, $plan_type) {
    $user = get_user_by('id', $user_id);

    if (!$user) {
        return;
    }

    $plan_name = $plan_type === 'monthly' ? '月額プラン' : '年額プラン';
    $price = $plan_type === 'monthly' ? '1,000円/月' : '10,000円/年';

    $subject = '[ありがとう倶楽部] 有料会員へようこそ！';

    $body = sprintf(
        '<html>
<body style="font-family: sans-serif; line-height: 1.8;">
<p>%s 様</p>

<p>この度は、ありがとう倶楽部の有料会員にご登録いただき、誠にありがとうございます。</p>

<h3 style="color: #D32F2F;">ご登録プラン</h3>
<p><strong>%s</strong>（%s）</p>

<h3 style="color: #D32F2F;">会員特典</h3>
<ul>
<li>ありがとうカフェへの参加（無制限）</li>
<li>セミナー会員価格</li>
<li>グッズ会員価格</li>
</ul>

<p>マイページから会員情報の確認・変更が可能です。</p>
<p><a href="%s">マイページはこちら</a></p>

<p>今後ともよろしくお願いいたします。</p>

<p>ありがとう倶楽部</p>
</body>
</html>',
        esc_html($user->display_name),
        esc_html($plan_name),
        esc_html($price),
        esc_url(home_url('/my-account/'))
    );

    $headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail($user->user_email, $subject, $body, $headers);
}

/**
 * 解約完了メール送信
 *
 * @param int $user_id
 */
function arigatou_send_cancellation_email($user_id) {
    $user = get_user_by('id', $user_id);

    if (!$user) {
        return;
    }

    $subject = '[ありがとう倶楽部] 有料会員の解約が完了しました';

    $body = sprintf(
        '<html>
<body style="font-family: sans-serif; line-height: 1.8;">
<p>%s 様</p>

<p>有料会員の解約が完了しました。</p>
<p>ご利用いただきありがとうございました。</p>

<p>またのご参加をお待ちしております。</p>

<p><a href="%s">再度有料会員に登録する</a></p>

<p>ありがとう倶楽部</p>
</body>
</html>',
        esc_html($user->display_name),
        esc_url(home_url('/membership/'))
    );

    $headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail($user->user_email, $subject, $body, $headers);
}

/**
 * 更新完了メール送信
 *
 * @param int $user_id
 */
function arigatou_send_renewal_email($user_id) {
    $user = get_user_by('id', $user_id);

    if (!$user) {
        return;
    }

    $info = arigatou_get_subscription_info($user_id);
    $next_date = !empty($info['end_date']) ? date('Y年n月j日', $info['end_date']) : '';

    $subject = '[ありがとう倶楽部] 会員更新のお知らせ';

    $body = sprintf(
        '<html>
<body style="font-family: sans-serif; line-height: 1.8;">
<p>%s 様</p>

<p>有料会員の更新が完了しました。</p>
<p>引き続きご利用いただけます。</p>

<p>次回更新日: %s</p>

<p>今後ともよろしくお願いいたします。</p>

<p>ありがとう倶楽部</p>
</body>
</html>',
        esc_html($user->display_name),
        esc_html($next_date)
    );

    $headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail($user->user_email, $subject, $body, $headers);
}

/**
 * 支払い失敗メール送信
 *
 * @param int $user_id
 */
function arigatou_send_payment_failed_email($user_id) {
    $user = get_user_by('id', $user_id);

    if (!$user) {
        return;
    }

    $subject = '[ありがとう倶楽部] お支払いに問題が発生しました';

    $body = sprintf(
        '<html>
<body style="font-family: sans-serif; line-height: 1.8;">
<p>%s 様</p>

<p>有料会員の更新に必要なお支払いができませんでした。</p>
<p>お支払い方法をご確認ください。</p>

<p><a href="%s">お支払い情報を更新する</a></p>

<p>ご不明な点がございましたら、お問い合わせください。</p>

<p>ありがとう倶楽部</p>
</body>
</html>',
        esc_html($user->display_name),
        esc_url(home_url('/my-account/'))
    );

    $headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail($user->user_email, $subject, $body, $headers);
}

/**
 * スポット決済購入完了メール送信（購入者向け）
 *
 * @param string $email
 * @param string $name
 * @param string $product_name
 * @param int    $amount
 */
function arigatou_send_spot_purchase_email($email, $name, $product_name, $amount) {
    $display_name = !empty($name) ? $name : 'お客';
    $display_amount = number_format($amount / 100);

    $subject = '[ありがとう倶楽部] ご購入ありがとうございます';

    $body = sprintf(
        '<html>
<body style="font-family: sans-serif; line-height: 1.8;">
<p>%s 様</p>

<p>この度は、ありがとう倶楽部をご利用いただき、誠にありがとうございます。</p>

<h3 style="color: #D32F2F;">ご購入内容</h3>
<table style="border-collapse: collapse; width: 100%%; max-width: 400px;">
<tr>
<td style="padding: 10px; border-bottom: 1px solid #ddd;">商品名</td>
<td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>%s</strong></td>
</tr>
<tr>
<td style="padding: 10px; border-bottom: 1px solid #ddd;">金額</td>
<td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>¥%s</strong></td>
</tr>
</table>

<p style="margin-top: 20px;">当日のご参加をお待ちしております。</p>

<p>ご不明な点がございましたら、お気軽にお問い合わせください。</p>

<p>ありがとう倶楽部</p>
</body>
</html>',
        esc_html($display_name),
        esc_html($product_name),
        esc_html($display_amount)
    );

    $headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail($email, $subject, $body, $headers);
}

/**
 * スポット決済購入通知メール送信（管理者向け）
 *
 * @param string $email
 * @param string $name
 * @param string $product_name
 * @param int    $amount
 */
function arigatou_send_spot_purchase_admin_email($email, $name, $product_name, $amount) {
    $admin_email = get_option('admin_email');
    $display_name = !empty($name) ? $name : '（名前なし）';
    $display_amount = number_format($amount / 100);

    $subject = '[ありがとう倶楽部] 新しい購入がありました';

    $body = sprintf(
        '<html>
<body style="font-family: sans-serif; line-height: 1.8;">
<h2 style="color: #D32F2F;">新しい購入通知</h2>

<table style="border-collapse: collapse; width: 100%%; max-width: 500px;">
<tr>
<td style="padding: 10px; border: 1px solid #ddd; background: #f5f5f5;">購入者名</td>
<td style="padding: 10px; border: 1px solid #ddd;">%s</td>
</tr>
<tr>
<td style="padding: 10px; border: 1px solid #ddd; background: #f5f5f5;">メールアドレス</td>
<td style="padding: 10px; border: 1px solid #ddd;">%s</td>
</tr>
<tr>
<td style="padding: 10px; border: 1px solid #ddd; background: #f5f5f5;">商品名</td>
<td style="padding: 10px; border: 1px solid #ddd;"><strong>%s</strong></td>
</tr>
<tr>
<td style="padding: 10px; border: 1px solid #ddd; background: #f5f5f5;">金額</td>
<td style="padding: 10px; border: 1px solid #ddd;"><strong>¥%s</strong></td>
</tr>
<tr>
<td style="padding: 10px; border: 1px solid #ddd; background: #f5f5f5;">日時</td>
<td style="padding: 10px; border: 1px solid #ddd;">%s</td>
</tr>
</table>

<p style="margin-top: 20px;">
<a href="https://dashboard.stripe.com/payments" target="_blank">Stripe Dashboardで確認</a>
</p>
</body>
</html>',
        esc_html($display_name),
        esc_html($email),
        esc_html($product_name),
        esc_html($display_amount),
        esc_html(date('Y年n月j日 H:i'))
    );

    $headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail($admin_email, $subject, $body, $headers);
}
