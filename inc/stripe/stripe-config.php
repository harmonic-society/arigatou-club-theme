<?php
/**
 * Stripe設定・API通信ヘルパー
 *
 * 設定は WordPress 管理画面「会員管理 > Stripe設定」から入力できます。
 * または wp-config.php に定数を定義することもできます（定数が優先）。
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Stripe設定クラス
 */
class Arigatou_Stripe_Config {

    /**
     * オプション名
     */
    const OPTION_NAME = 'arigatou_stripe_settings';

    /**
     * 設定を取得（wp-config.php定数 > DBオプション の優先順）
     */
    private static function get_setting($key) {
        // wp-config.php の定数が定義されていれば優先
        $constant_map = array(
            'publishable_key' => 'STRIPE_PUBLISHABLE_KEY',
            'secret_key'      => 'STRIPE_SECRET_KEY',
            'webhook_secret'  => 'STRIPE_WEBHOOK_SECRET',
            'price_monthly'   => 'STRIPE_PRICE_MONTHLY',
            'price_annual'    => 'STRIPE_PRICE_ANNUAL',
        );

        if (isset($constant_map[$key]) && defined($constant_map[$key])) {
            return constant($constant_map[$key]);
        }

        // DBから取得
        $options = get_option(self::OPTION_NAME, array());
        return isset($options[$key]) ? $options[$key] : '';
    }

    /**
     * 設定を保存
     */
    public static function save_settings($settings) {
        $sanitized = array(
            'publishable_key' => sanitize_text_field($settings['publishable_key'] ?? ''),
            'secret_key'      => sanitize_text_field($settings['secret_key'] ?? ''),
            'webhook_secret'  => sanitize_text_field($settings['webhook_secret'] ?? ''),
            'price_monthly'   => sanitize_text_field($settings['price_monthly'] ?? ''),
            'price_annual'    => sanitize_text_field($settings['price_annual'] ?? ''),
        );

        return update_option(self::OPTION_NAME, $sanitized);
    }

    /**
     * 全設定を取得（フォーム表示用）
     */
    public static function get_all_settings() {
        $options = get_option(self::OPTION_NAME, array());

        return array(
            'publishable_key' => $options['publishable_key'] ?? '',
            'secret_key'      => $options['secret_key'] ?? '',
            'webhook_secret'  => $options['webhook_secret'] ?? '',
            'price_monthly'   => $options['price_monthly'] ?? '',
            'price_annual'    => $options['price_annual'] ?? '',
        );
    }

    /**
     * Publishable Key取得
     */
    public static function get_publishable_key() {
        return self::get_setting('publishable_key');
    }

    /**
     * Secret Key取得
     */
    public static function get_secret_key() {
        return self::get_setting('secret_key');
    }

    /**
     * Webhook Secret取得
     */
    public static function get_webhook_secret() {
        return self::get_setting('webhook_secret');
    }

    /**
     * Price ID取得
     */
    public static function get_price_ids() {
        return array(
            'monthly' => self::get_setting('price_monthly'),
            'annual'  => self::get_setting('price_annual'),
        );
    }

    /**
     * 設定完了チェック
     */
    public static function is_configured() {
        $price_ids = self::get_price_ids();
        return !empty(self::get_secret_key())
            && !empty(self::get_publishable_key())
            && !empty($price_ids['monthly'])
            && !empty($price_ids['annual']);
    }
}

/**
 * Stripe API通信クラス
 */
class Arigatou_Stripe_API {

    private static $api_base = 'https://api.stripe.com/v1';

    /**
     * APIリクエスト送信
     */
    public static function request($endpoint, $method = 'GET', $data = array()) {
        $secret_key = Arigatou_Stripe_Config::get_secret_key();

        if (empty($secret_key)) {
            return new WP_Error('stripe_not_configured', 'Stripe APIキーが設定されていません');
        }

        $url = self::$api_base . $endpoint;

        $args = array(
            'method'  => $method,
            'headers' => array(
                'Authorization' => 'Bearer ' . $secret_key,
                'Content-Type'  => 'application/x-www-form-urlencoded',
            ),
            'timeout' => 30,
        );

        if (!empty($data)) {
            if ($method === 'GET') {
                $url = add_query_arg($data, $url);
            } else {
                $args['body'] = http_build_query($data, '', '&');
            }
        }

        $response = wp_remote_request($url, $args);

        if (is_wp_error($response)) {
            return $response;
        }

        $body = wp_remote_retrieve_body($response);
        $decoded = json_decode($body, true);

        $status_code = wp_remote_retrieve_response_code($response);

        if ($status_code >= 400) {
            $error_message = isset($decoded['error']['message'])
                ? $decoded['error']['message']
                : 'Stripe APIエラー';
            return new WP_Error('stripe_api_error', $error_message, $decoded);
        }

        return $decoded;
    }

    /**
     * Customer作成
     */
    public static function create_customer($email, $name, $metadata = array()) {
        $data = array(
            'email' => $email,
            'name'  => $name,
        );

        if (!empty($metadata)) {
            foreach ($metadata as $key => $value) {
                $data['metadata[' . $key . ']'] = $value;
            }
        }

        return self::request('/customers', 'POST', $data);
    }

    /**
     * Customer取得
     */
    public static function get_customer($customer_id) {
        return self::request('/customers/' . $customer_id);
    }

    /**
     * Checkout Session作成
     */
    public static function create_checkout_session($params) {
        $data = array(
            'mode' => 'subscription',
            'success_url' => $params['success_url'],
            'cancel_url'  => $params['cancel_url'],
            'locale'      => 'ja',
        );

        if (!empty($params['customer'])) {
            $data['customer'] = $params['customer'];
        }

        if (!empty($params['customer_email'])) {
            $data['customer_email'] = $params['customer_email'];
        }

        // Line items
        $data['line_items[0][price]'] = $params['price_id'];
        $data['line_items[0][quantity]'] = 1;

        // Metadata
        if (!empty($params['metadata'])) {
            foreach ($params['metadata'] as $key => $value) {
                $data['metadata[' . $key . ']'] = $value;
            }
        }

        return self::request('/checkout/sessions', 'POST', $data);
    }

    /**
     * Subscription取得
     */
    public static function get_subscription($subscription_id) {
        return self::request('/subscriptions/' . $subscription_id);
    }

    /**
     * Subscription更新（解約予約など）
     */
    public static function update_subscription($subscription_id, $params) {
        return self::request('/subscriptions/' . $subscription_id, 'POST', $params);
    }

    /**
     * Billing Portal Session作成
     */
    public static function create_portal_session($customer_id, $return_url) {
        return self::request('/billing_portal/sessions', 'POST', array(
            'customer'   => $customer_id,
            'return_url' => $return_url,
        ));
    }

    /**
     * Webhook署名検証
     */
    public static function verify_webhook_signature($payload, $sig_header) {
        $secret = Arigatou_Stripe_Config::get_webhook_secret();

        if (empty($secret)) {
            return new WP_Error('webhook_secret_missing', 'Webhook Secretが設定されていません');
        }

        $elements = explode(',', $sig_header);
        $timestamp = null;
        $signatures = array();

        foreach ($elements as $element) {
            $parts = explode('=', $element, 2);
            if (count($parts) === 2) {
                if ($parts[0] === 't') {
                    $timestamp = $parts[1];
                } elseif ($parts[0] === 'v1') {
                    $signatures[] = $parts[1];
                }
            }
        }

        if (empty($timestamp) || empty($signatures)) {
            return new WP_Error('invalid_signature', '署名形式が無効です');
        }

        // タイムスタンプチェック（5分以内）
        if (abs(time() - intval($timestamp)) > 300) {
            return new WP_Error('timestamp_expired', '署名の有効期限が切れています');
        }

        $signed_payload = $timestamp . '.' . $payload;
        $expected_sig = hash_hmac('sha256', $signed_payload, $secret);

        $valid = false;
        foreach ($signatures as $sig) {
            if (hash_equals($expected_sig, $sig)) {
                $valid = true;
                break;
            }
        }

        if (!$valid) {
            return new WP_Error('signature_mismatch', '署名が一致しません');
        }

        return true;
    }
}
