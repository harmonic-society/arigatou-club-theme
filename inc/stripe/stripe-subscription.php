<?php
/**
 * サブスクリプション管理関数
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ユーザーが有料会員かチェック
 *
 * @param int|null $user_id ユーザーID（省略時は現在のユーザー）
 * @return bool
 */
function arigatou_is_premium_member($user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    if (!$user_id) {
        return false;
    }

    $status = get_user_meta($user_id, '_member_status', true);
    return $status === 'premium';
}

/**
 * サブスクリプション情報取得
 *
 * @param int|null $user_id ユーザーID
 * @return array
 */
function arigatou_get_subscription_info($user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    if (!$user_id) {
        return array();
    }

    return array(
        'plan'            => get_user_meta($user_id, '_subscription_plan', true),
        'status'          => get_user_meta($user_id, '_subscription_status', true),
        'start_date'      => get_user_meta($user_id, '_subscription_start_date', true),
        'end_date'        => get_user_meta($user_id, '_subscription_end_date', true),
        'customer_id'     => get_user_meta($user_id, '_stripe_customer_id', true),
        'subscription_id' => get_user_meta($user_id, '_stripe_subscription_id', true),
        'member_status'   => get_user_meta($user_id, '_member_status', true),
    );
}

/**
 * サブスクリプションステータスのラベル取得
 *
 * @param string $status
 * @return string
 */
function arigatou_get_subscription_status_label($status) {
    $labels = array(
        'active'    => '有効',
        'past_due'  => '支払い遅延',
        'canceled'  => 'キャンセル済み',
        'canceling' => 'キャンセル予約中',
        'expired'   => '期限切れ',
        'trialing'  => 'トライアル中',
    );

    return isset($labels[$status]) ? $labels[$status] : $status;
}

/**
 * プラン名取得
 *
 * @param string $plan
 * @return string
 */
function arigatou_get_plan_name($plan) {
    $names = array(
        'monthly' => '月額プラン',
        'annual'  => '年額プラン',
    );

    return isset($names[$plan]) ? $names[$plan] : $plan;
}

/**
 * サブスクリプション解約（期間終了時）
 *
 * @param int $user_id
 * @return bool|WP_Error
 */
function arigatou_cancel_subscription($user_id) {
    $subscription_id = get_user_meta($user_id, '_stripe_subscription_id', true);

    if (empty($subscription_id)) {
        return new WP_Error('no_subscription', 'サブスクリプションが見つかりません');
    }

    $result = Arigatou_Stripe_API::update_subscription($subscription_id, array(
        'cancel_at_period_end' => 'true',
    ));

    if (is_wp_error($result)) {
        return $result;
    }

    update_user_meta($user_id, '_subscription_status', 'canceling');

    return true;
}

/**
 * 解約キャンセル（解約予約の取り消し）
 *
 * @param int $user_id
 * @return bool|WP_Error
 */
function arigatou_reactivate_subscription($user_id) {
    $subscription_id = get_user_meta($user_id, '_stripe_subscription_id', true);

    if (empty($subscription_id)) {
        return new WP_Error('no_subscription', 'サブスクリプションが見つかりません');
    }

    $result = Arigatou_Stripe_API::update_subscription($subscription_id, array(
        'cancel_at_period_end' => 'false',
    ));

    if (is_wp_error($result)) {
        return $result;
    }

    update_user_meta($user_id, '_subscription_status', 'active');

    return true;
}

/**
 * Stripe Customer Portal URLを取得
 *
 * @param int|null $user_id
 * @return string|false
 */
function arigatou_get_portal_url($user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    $customer_id = get_user_meta($user_id, '_stripe_customer_id', true);

    if (empty($customer_id)) {
        return false;
    }

    $result = Arigatou_Stripe_API::create_portal_session(
        $customer_id,
        home_url('/my-account/')
    );

    if (is_wp_error($result)) {
        return false;
    }

    return isset($result['url']) ? $result['url'] : false;
}

/**
 * ユーザーのサブスクリプション情報を更新
 *
 * @param int    $user_id
 * @param array  $subscription Stripeサブスクリプションオブジェクト
 * @param string $plan_type    'monthly' or 'annual'
 */
function arigatou_update_user_subscription($user_id, $subscription, $plan_type = '') {
    update_user_meta($user_id, '_stripe_subscription_id', $subscription['id']);

    if (!empty($plan_type)) {
        update_user_meta($user_id, '_subscription_plan', $plan_type);
    }

    // ステータスマッピング
    $status = $subscription['status'];
    if ($subscription['cancel_at_period_end']) {
        $status = 'canceling';
    }

    update_user_meta($user_id, '_subscription_status', $status);
    update_user_meta($user_id, '_subscription_start_date', $subscription['current_period_start']);
    update_user_meta($user_id, '_subscription_end_date', $subscription['current_period_end']);

    // 有効なサブスクリプションはpremium
    if (in_array($status, array('active', 'trialing', 'canceling'))) {
        update_user_meta($user_id, '_member_status', 'premium');
    } else {
        update_user_meta($user_id, '_member_status', 'free');
    }
}

/**
 * サブスクリプション解除時の処理
 *
 * @param int $user_id
 */
function arigatou_clear_user_subscription($user_id) {
    update_user_meta($user_id, '_subscription_status', 'canceled');
    update_user_meta($user_id, '_member_status', 'free');
}

/**
 * Stripe Customer IDを取得または作成
 *
 * @param int $user_id
 * @return string|WP_Error
 */
function arigatou_get_or_create_stripe_customer($user_id) {
    $customer_id = get_user_meta($user_id, '_stripe_customer_id', true);

    if (!empty($customer_id)) {
        return $customer_id;
    }

    $user = get_user_by('id', $user_id);

    if (!$user) {
        return new WP_Error('user_not_found', 'ユーザーが見つかりません');
    }

    $result = Arigatou_Stripe_API::create_customer(
        $user->user_email,
        $user->display_name,
        array('wp_user_id' => $user_id)
    );

    if (is_wp_error($result)) {
        return $result;
    }

    $customer_id = $result['id'];
    update_user_meta($user_id, '_stripe_customer_id', $customer_id);

    return $customer_id;
}

/**
 * Customer IDからユーザーを検索
 *
 * @param string $customer_id
 * @return int|false
 */
function arigatou_get_user_by_customer_id($customer_id) {
    $users = get_users(array(
        'meta_key'   => '_stripe_customer_id',
        'meta_value' => $customer_id,
        'number'     => 1,
    ));

    return !empty($users) ? $users[0]->ID : false;
}

/**
 * メールアドレスからユーザーを検索または作成
 *
 * @param string $email
 * @param bool   $is_new_user 新規作成されたかどうかを返す参照変数
 * @return int|false ユーザーID
 */
function arigatou_get_or_create_user_by_email($email, &$is_new_user = false) {
    $is_new_user = false;

    // 既存ユーザー検索
    $user = get_user_by('email', $email);
    if ($user) {
        return $user->ID;
    }

    // 新規ユーザー作成
    $password = wp_generate_password(12, true, false);
    $username = sanitize_user(strstr($email, '@', true), true);

    // 空のユーザー名の場合はメール全体を使用
    if (empty($username)) {
        $username = sanitize_user($email, true);
    }

    // ユーザー名重複チェック
    $base_username = $username;
    $counter = 1;
    while (username_exists($username)) {
        $username = $base_username . $counter;
        $counter++;
    }

    $user_id = wp_insert_user(array(
        'user_login'   => $username,
        'user_email'   => $email,
        'user_pass'    => $password,
        'display_name' => $username,
        'role'         => 'subscriber',
    ));

    if (is_wp_error($user_id)) {
        error_log('WPユーザー作成エラー: ' . $user_id->get_error_message());
        return false;
    }

    $is_new_user = true;

    // パスワード付きウェルカムメール送信
    arigatou_send_new_user_welcome_email($user_id, $password);

    return $user_id;
}

/**
 * 新規ユーザー向けウェルカムメール（パスワード付き）
 *
 * @param int    $user_id
 * @param string $password
 */
function arigatou_send_new_user_welcome_email($user_id, $password) {
    $user = get_user_by('id', $user_id);

    if (!$user) {
        return;
    }

    $subject = '[ありがとう倶楽部] アカウントが作成されました';

    $body = sprintf(
        '<html>
<body style="font-family: sans-serif; line-height: 1.8;">
<p>%s 様</p>

<p>ありがとう倶楽部の有料会員にご登録いただき、誠にありがとうございます。</p>
<p>アカウントを自動作成しました。以下の情報でログインできます。</p>

<div style="background: #f5f5f5; padding: 20px; border-radius: 8px; margin: 20px 0;">
<h3 style="margin-top: 0; color: #D32F2F;">ログイン情報</h3>
<p><strong>ユーザー名:</strong> %s</p>
<p><strong>メールアドレス:</strong> %s</p>
<p><strong>パスワード:</strong> %s</p>
<p style="margin-bottom: 0;"><strong>ログインURL:</strong> <a href="%s">%s</a></p>
</div>

<p style="color: #666; font-size: 0.9em;">セキュリティのため、初回ログイン後にパスワードを変更されることをお勧めします。</p>

<p>マイページから会員情報の確認・変更が可能です。</p>
<p><a href="%s">マイページはこちら</a></p>

<p>今後ともよろしくお願いいたします。</p>

<p>ありがとう倶楽部</p>
</body>
</html>',
        esc_html($user->display_name),
        esc_html($user->user_login),
        esc_html($user->user_email),
        esc_html($password),
        esc_url(wp_login_url()),
        esc_url(wp_login_url()),
        esc_url(home_url('/my-account/'))
    );

    $headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail($user->user_email, $subject, $body, $headers);
}
