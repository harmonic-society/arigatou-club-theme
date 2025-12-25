<?php
/**
 * Stripe Checkout処理
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Checkout Session作成
 *
 * @param string $plan_type 'monthly' or 'annual'
 * @param int    $user_id
 * @return array|WP_Error
 */
function arigatou_create_checkout_session($plan_type, $user_id) {
    $price_ids = Arigatou_Stripe_Config::get_price_ids();

    if (empty($price_ids[$plan_type])) {
        return new WP_Error('invalid_plan', '無効なプランです');
    }

    // Stripe Customer IDを取得または作成
    $customer_id = arigatou_get_or_create_stripe_customer($user_id);

    if (is_wp_error($customer_id)) {
        return $customer_id;
    }

    $session = Arigatou_Stripe_API::create_checkout_session(array(
        'customer'    => $customer_id,
        'price_id'    => $price_ids[$plan_type],
        'success_url' => home_url('/membership-success/') . '?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url'  => home_url('/membership-cancel/'),
        'metadata'    => array(
            'wp_user_id' => $user_id,
            'plan_type'  => $plan_type,
        ),
    ));

    return $session;
}

/**
 * ゲスト用Checkout Session作成（非ログインユーザー）
 *
 * @param string $plan_type 'monthly' or 'annual'
 * @param string $email メールアドレス
 * @return array|WP_Error
 */
function arigatou_create_guest_checkout_session($plan_type, $email) {
    $price_ids = Arigatou_Stripe_Config::get_price_ids();

    if (empty($price_ids[$plan_type])) {
        return new WP_Error('invalid_plan', '無効なプランです');
    }

    // 既存ユーザーチェック（メールアドレスで検索）
    $existing_user = get_user_by('email', $email);
    if ($existing_user) {
        // 既に有料会員かチェック
        if (arigatou_is_premium_member($existing_user->ID)) {
            return new WP_Error('already_premium', '既に有料会員です。ログインしてマイページをご確認ください。');
        }
    }

    $session = Arigatou_Stripe_API::create_checkout_session(array(
        'customer_email' => $email,
        'price_id'       => $price_ids[$plan_type],
        'success_url'    => home_url('/membership-success/') . '?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url'     => home_url('/membership-cancel/'),
        'metadata'       => array(
            'guest_email' => $email,
            'plan_type'   => $plan_type,
        ),
    ));

    return $session;
}

/**
 * AJAX: Checkout Session作成
 */
function arigatou_ajax_create_checkout() {
    // Nonce検証
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'arigatou_nonce')) {
        wp_send_json_error(array('message' => 'セキュリティチェックに失敗しました'));
    }

    // プランタイプ検証
    $plan_type = isset($_POST['plan_type']) ? sanitize_text_field($_POST['plan_type']) : '';

    if (!in_array($plan_type, array('monthly', 'annual'), true)) {
        wp_send_json_error(array('message' => '無効なプランです'));
    }

    // ログインユーザーの場合
    if (is_user_logged_in()) {
        // 既に有料会員かチェック
        if (arigatou_is_premium_member()) {
            wp_send_json_error(array(
                'message'  => '既に有料会員です',
                'redirect' => home_url('/my-account/'),
            ));
        }

        // Checkout Session作成（既存処理）
        $session = arigatou_create_checkout_session($plan_type, get_current_user_id());
    } else {
        // 非ログインユーザーの場合（ゲスト決済）
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';

        if (empty($email) || !is_email($email)) {
            wp_send_json_error(array('message' => 'メールアドレスを入力してください'));
        }

        // ゲスト用Checkout Session作成
        $session = arigatou_create_guest_checkout_session($plan_type, $email);
    }

    if (is_wp_error($session)) {
        wp_send_json_error(array('message' => $session->get_error_message()));
    }

    if (!isset($session['url'])) {
        wp_send_json_error(array('message' => 'Checkout URLの取得に失敗しました'));
    }

    wp_send_json_success(array('checkout_url' => $session['url']));
}
add_action('wp_ajax_arigatou_create_checkout', 'arigatou_ajax_create_checkout');
add_action('wp_ajax_nopriv_arigatou_create_checkout', 'arigatou_ajax_create_checkout');

/**
 * AJAX: Customer Portal Session作成
 */
function arigatou_ajax_create_portal_session() {
    // Nonce検証
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'arigatou_nonce')) {
        wp_send_json_error(array('message' => 'セキュリティチェックに失敗しました'));
    }

    // ログインチェック
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'ログインが必要です'));
    }

    $portal_url = arigatou_get_portal_url();

    if (!$portal_url) {
        wp_send_json_error(array('message' => 'Customer Portalの作成に失敗しました'));
    }

    wp_send_json_success(array('portal_url' => $portal_url));
}
add_action('wp_ajax_arigatou_create_portal_session', 'arigatou_ajax_create_portal_session');

/**
 * AJAX: サブスクリプション解約
 */
function arigatou_ajax_cancel_subscription() {
    // Nonce検証
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'arigatou_nonce')) {
        wp_send_json_error(array('message' => 'セキュリティチェックに失敗しました'));
    }

    // ログインチェック
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'ログインが必要です'));
    }

    $result = arigatou_cancel_subscription(get_current_user_id());

    if (is_wp_error($result)) {
        wp_send_json_error(array('message' => $result->get_error_message()));
    }

    wp_send_json_success(array('message' => '解約を予約しました。現在の期間終了後に解約されます。'));
}
add_action('wp_ajax_arigatou_cancel_subscription', 'arigatou_ajax_cancel_subscription');

/**
 * AJAX: 解約キャンセル
 */
function arigatou_ajax_reactivate_subscription() {
    // Nonce検証
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'arigatou_nonce')) {
        wp_send_json_error(array('message' => 'セキュリティチェックに失敗しました'));
    }

    // ログインチェック
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'ログインが必要です'));
    }

    $result = arigatou_reactivate_subscription(get_current_user_id());

    if (is_wp_error($result)) {
        wp_send_json_error(array('message' => $result->get_error_message()));
    }

    wp_send_json_success(array('message' => '解約予約をキャンセルしました。'));
}
add_action('wp_ajax_arigatou_reactivate_subscription', 'arigatou_ajax_reactivate_subscription');

/**
 * スポット決済Checkout Session作成
 *
 * @param string $product_type 'cafe' など
 * @param string $email 購入者メールアドレス（非ログイン時）
 * @return array|WP_Error
 */
function arigatou_create_spot_checkout_session($product_type, $email = '') {
    $spot_prices = Arigatou_Stripe_Config::get_spot_price_ids();

    if (empty($spot_prices[$product_type])) {
        return new WP_Error('invalid_product', '無効な商品です');
    }

    // 商品名マッピング
    $product_names = array(
        'cafe' => 'ありがとうカフェ 参加費',
    );

    $session = Arigatou_Stripe_API::create_spot_checkout_session(array(
        'price_id'       => $spot_prices[$product_type],
        'customer_email' => $email,
        'success_url'    => home_url('/spot-payment-success/') . '?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url'     => home_url('/spot-payment/'),
        'metadata'       => array(
            'product_type' => $product_type,
            'product_name' => $product_names[$product_type] ?? $product_type,
        ),
    ));

    return $session;
}

/**
 * AJAX: スポット決済Checkout Session作成
 */
function arigatou_ajax_create_spot_checkout() {
    // Nonce検証
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'arigatou_spot_nonce')) {
        wp_send_json_error(array('message' => 'セキュリティチェックに失敗しました'));
    }

    // 商品タイプ検証
    $product_type = isset($_POST['product_type']) ? sanitize_text_field($_POST['product_type']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';

    if (empty($product_type)) {
        wp_send_json_error(array('message' => '商品が指定されていません'));
    }

    // ログインユーザーの場合はメールアドレスを自動取得
    if (is_user_logged_in() && empty($email)) {
        $current_user = wp_get_current_user();
        $email = $current_user->user_email;
    }

    // メールアドレス必須
    if (empty($email) || !is_email($email)) {
        wp_send_json_error(array('message' => '有効なメールアドレスを入力してください'));
    }

    // Checkout Session作成
    $session = arigatou_create_spot_checkout_session($product_type, $email);

    if (is_wp_error($session)) {
        wp_send_json_error(array('message' => $session->get_error_message()));
    }

    if (!isset($session['url'])) {
        wp_send_json_error(array('message' => 'Checkout URLの取得に失敗しました'));
    }

    wp_send_json_success(array('checkout_url' => $session['url']));
}
add_action('wp_ajax_arigatou_create_spot_checkout', 'arigatou_ajax_create_spot_checkout');
add_action('wp_ajax_nopriv_arigatou_create_spot_checkout', 'arigatou_ajax_create_spot_checkout');
