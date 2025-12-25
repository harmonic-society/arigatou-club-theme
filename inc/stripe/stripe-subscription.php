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
