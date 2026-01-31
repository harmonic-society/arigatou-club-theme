<?php
/**
 * Stripe管理画面
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 管理メニュー追加
 */
function arigatou_stripe_admin_menu() {
    add_menu_page(
        '会員管理',
        '会員管理',
        'manage_options',
        'arigatou-membership',
        'arigatou_membership_dashboard',
        'dashicons-groups',
        30
    );

    add_submenu_page(
        'arigatou-membership',
        '会員一覧',
        '会員一覧',
        'manage_options',
        'arigatou-members-list',
        'arigatou_members_list_page'
    );

    add_submenu_page(
        'arigatou-membership',
        'スポット決済一覧',
        'スポット決済',
        'manage_options',
        'arigatou-spot-payments',
        'arigatou_spot_payments_list_page'
    );

    add_submenu_page(
        'arigatou-membership',
        'Stripe設定',
        'Stripe設定',
        'manage_options',
        'arigatou-stripe-settings',
        'arigatou_stripe_settings_page'
    );
}
add_action('admin_menu', 'arigatou_stripe_admin_menu');

/**
 * 会員ダッシュボード
 */
function arigatou_membership_dashboard() {
    // 統計取得
    $premium_count = count(get_users(array(
        'meta_key'   => '_member_status',
        'meta_value' => 'premium',
    )));

    $monthly_count = count(get_users(array(
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key'   => '_member_status',
                'value' => 'premium',
            ),
            array(
                'key'   => '_subscription_plan',
                'value' => 'monthly',
            ),
        ),
    )));

    $annual_count = count(get_users(array(
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key'   => '_member_status',
                'value' => 'premium',
            ),
            array(
                'key'   => '_subscription_plan',
                'value' => 'annual',
            ),
        ),
    )));

    $canceling_count = count(get_users(array(
        'meta_key'   => '_subscription_status',
        'meta_value' => 'canceling',
    )));

    ?>
    <div class="wrap">
        <h1>会員管理ダッシュボード</h1>

        <div class="arigatou-dashboard-stats" style="display: flex; gap: 20px; margin-top: 20px; flex-wrap: wrap;">
            <div class="stat-card" style="background: #fff; padding: 20px 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); min-width: 150px;">
                <h3 style="margin: 0 0 10px; color: #666; font-size: 14px;">有料会員数</h3>
                <p style="font-size: 36px; margin: 0; color: #D32F2F; font-weight: bold;"><?php echo esc_html($premium_count); ?></p>
            </div>
            <div class="stat-card" style="background: #fff; padding: 20px 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); min-width: 150px;">
                <h3 style="margin: 0 0 10px; color: #666; font-size: 14px;">月額プラン</h3>
                <p style="font-size: 36px; margin: 0; color: #333; font-weight: bold;"><?php echo esc_html($monthly_count); ?></p>
            </div>
            <div class="stat-card" style="background: #fff; padding: 20px 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); min-width: 150px;">
                <h3 style="margin: 0 0 10px; color: #666; font-size: 14px;">年額プラン</h3>
                <p style="font-size: 36px; margin: 0; color: #333; font-weight: bold;"><?php echo esc_html($annual_count); ?></p>
            </div>
            <div class="stat-card" style="background: #fff; padding: 20px 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); min-width: 150px;">
                <h3 style="margin: 0 0 10px; color: #666; font-size: 14px;">解約予約中</h3>
                <p style="font-size: 36px; margin: 0; color: #ff9800; font-weight: bold;"><?php echo esc_html($canceling_count); ?></p>
            </div>
        </div>

        <div style="margin-top: 30px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h2 style="margin-top: 0;">Stripe接続状態</h2>
            <?php if (Arigatou_Stripe_Config::is_configured()) : ?>
                <p style="color: #4caf50;"><span class="dashicons dashicons-yes-alt"></span> Stripeは正しく設定されています。</p>
            <?php else : ?>
                <p style="color: #f44336;"><span class="dashicons dashicons-warning"></span> Stripe APIキーが設定されていません。</p>
                <p>wp-config.php に以下の定数を追加してください：</p>
                <pre style="background: #f5f5f5; padding: 15px; border-radius: 4px; overflow-x: auto;">
define('STRIPE_PUBLISHABLE_KEY', 'pk_live_xxxxx');
define('STRIPE_SECRET_KEY', 'sk_live_xxxxx');
define('STRIPE_WEBHOOK_SECRET', 'whsec_xxxxx');
define('STRIPE_PRICE_MONTHLY', 'price_xxxxx');
define('STRIPE_PRICE_ANNUAL', 'price_xxxxx');
                </pre>
            <?php endif; ?>

            <h3>Webhook URL</h3>
            <p>Stripe Dashboardで以下のURLをWebhookエンドポイントとして設定してください：</p>
            <code style="background: #f5f5f5; padding: 10px 15px; display: block; border-radius: 4px;">
                <?php echo esc_url(home_url('/wp-json/arigatou/v1/stripe-webhook')); ?>
            </code>

            <h4 style="margin-top: 20px;">必要なイベント</h4>
            <ul>
                <li><code>checkout.session.completed</code></li>
                <li><code>customer.subscription.created</code></li>
                <li><code>customer.subscription.updated</code></li>
                <li><code>customer.subscription.deleted</code></li>
                <li><code>invoice.payment_succeeded</code></li>
                <li><code>invoice.payment_failed</code></li>
            </ul>
        </div>

        <div style="margin-top: 30px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h2 style="margin-top: 0;">クイックリンク</h2>
            <p>
                <a href="<?php echo admin_url('admin.php?page=arigatou-members-list'); ?>" class="button button-primary">会員一覧を見る</a>
                <a href="https://dashboard.stripe.com/" target="_blank" class="button">Stripe Dashboard</a>
            </p>
        </div>
    </div>
    <?php
}

/**
 * 会員一覧ページ
 */
function arigatou_members_list_page() {
    // フィルター
    $filter = isset($_GET['filter']) ? sanitize_text_field($_GET['filter']) : 'all';

    $meta_query = array();

    switch ($filter) {
        case 'premium':
            $meta_query[] = array(
                'key'   => '_member_status',
                'value' => 'premium',
            );
            break;
        case 'monthly':
            $meta_query['relation'] = 'AND';
            $meta_query[] = array(
                'key'   => '_member_status',
                'value' => 'premium',
            );
            $meta_query[] = array(
                'key'   => '_subscription_plan',
                'value' => 'monthly',
            );
            break;
        case 'annual':
            $meta_query['relation'] = 'AND';
            $meta_query[] = array(
                'key'   => '_member_status',
                'value' => 'premium',
            );
            $meta_query[] = array(
                'key'   => '_subscription_plan',
                'value' => 'annual',
            );
            break;
        case 'canceling':
            $meta_query[] = array(
                'key'   => '_subscription_status',
                'value' => 'canceling',
            );
            break;
    }

    $args = array(
        'orderby' => 'registered',
        'order'   => 'DESC',
    );

    if (!empty($meta_query)) {
        $args['meta_query'] = $meta_query;
    }

    // 有料会員情報があるユーザーのみ
    if ($filter === 'all') {
        $args['meta_key'] = '_stripe_customer_id';
        $args['meta_compare'] = 'EXISTS';
    }

    $users = get_users($args);

    ?>
    <div class="wrap">
        <h1>会員一覧</h1>

        <div style="margin-bottom: 20px;">
            <a href="<?php echo add_query_arg('filter', 'all'); ?>" class="button <?php echo $filter === 'all' ? 'button-primary' : ''; ?>">すべて</a>
            <a href="<?php echo add_query_arg('filter', 'premium'); ?>" class="button <?php echo $filter === 'premium' ? 'button-primary' : ''; ?>">有料会員</a>
            <a href="<?php echo add_query_arg('filter', 'monthly'); ?>" class="button <?php echo $filter === 'monthly' ? 'button-primary' : ''; ?>">月額</a>
            <a href="<?php echo add_query_arg('filter', 'annual'); ?>" class="button <?php echo $filter === 'annual' ? 'button-primary' : ''; ?>">年額</a>
            <a href="<?php echo add_query_arg('filter', 'canceling'); ?>" class="button <?php echo $filter === 'canceling' ? 'button-primary' : ''; ?>">解約予約中</a>
        </div>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ユーザー</th>
                    <th>メールアドレス</th>
                    <th>プラン</th>
                    <th>ステータス</th>
                    <th>次回更新日</th>
                    <th>登録日</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)) : ?>
                    <tr>
                        <td colspan="6">会員が見つかりません。</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($users as $user) : ?>
                        <?php
                        $info = arigatou_get_subscription_info($user->ID);
                        $status_label = arigatou_get_subscription_status_label($info['status']);
                        $plan_name = arigatou_get_plan_name($info['plan']);
                        $end_date = !empty($info['end_date']) ? date('Y/m/d', $info['end_date']) : '-';
                        ?>
                        <tr>
                            <td>
                                <a href="<?php echo get_edit_user_link($user->ID); ?>">
                                    <?php echo esc_html($user->display_name); ?>
                                </a>
                            </td>
                            <td><?php echo esc_html($user->user_email); ?></td>
                            <td><?php echo esc_html($plan_name ?: '-'); ?></td>
                            <td>
                                <?php
                                $status_class = '';
                                if ($info['status'] === 'active') {
                                    $status_class = 'color: #4caf50;';
                                } elseif ($info['status'] === 'canceling') {
                                    $status_class = 'color: #ff9800;';
                                } elseif (in_array($info['status'], array('canceled', 'past_due'))) {
                                    $status_class = 'color: #f44336;';
                                }
                                ?>
                                <span style="<?php echo $status_class; ?>"><?php echo esc_html($status_label ?: '-'); ?></span>
                            </td>
                            <td><?php echo esc_html($end_date); ?></td>
                            <td><?php echo esc_html(date('Y/m/d', strtotime($user->user_registered))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}

/**
 * Stripe設定ページ
 */
function arigatou_stripe_settings_page() {
    // 保存処理
    $message = '';
    if (isset($_POST['arigatou_stripe_save']) && check_admin_referer('arigatou_stripe_settings')) {
        $settings = array(
            'publishable_key' => $_POST['publishable_key'] ?? '',
            'secret_key'      => $_POST['secret_key'] ?? '',
            'webhook_secret'  => $_POST['webhook_secret'] ?? '',
            'price_monthly'   => $_POST['price_monthly'] ?? '',
            'price_annual'    => $_POST['price_annual'] ?? '',
            'price_cafe'      => $_POST['price_cafe'] ?? '',
        );

        Arigatou_Stripe_Config::save_settings($settings);
        $message = '設定を保存しました。';
    }

    // 現在の設定を取得
    $settings = Arigatou_Stripe_Config::get_all_settings();
    $is_configured = Arigatou_Stripe_Config::is_configured();

    ?>
    <div class="wrap">
        <h1>Stripe設定</h1>

        <?php if ($message) : ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo esc_html($message); ?></p>
            </div>
        <?php endif; ?>

        <?php if ($is_configured) : ?>
            <div class="notice notice-success">
                <p><span class="dashicons dashicons-yes-alt"></span> Stripeは正しく設定されています。</p>
            </div>
        <?php else : ?>
            <div class="notice notice-warning">
                <p><span class="dashicons dashicons-warning"></span> 下記のフォームに必要な情報を入力してください。</p>
            </div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 1fr 400px; gap: 30px; margin-top: 20px;">
            <!-- 設定フォーム -->
            <div style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <h2 style="margin-top: 0;">API設定</h2>

                <form method="post" action="">
                    <?php wp_nonce_field('arigatou_stripe_settings'); ?>

                    <table class="form-table">
                        <tr>
                            <th><label for="publishable_key">Publishable Key</label></th>
                            <td>
                                <input type="text" id="publishable_key" name="publishable_key"
                                       value="<?php echo esc_attr($settings['publishable_key']); ?>"
                                       class="regular-text" placeholder="pk_live_xxxxx または pk_test_xxxxx">
                                <p class="description">公開可能キー（pk_live_ または pk_test_ で始まる）</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="secret_key">Secret Key</label></th>
                            <td>
                                <input type="password" id="secret_key" name="secret_key"
                                       value="<?php echo esc_attr($settings['secret_key']); ?>"
                                       class="regular-text" placeholder="sk_live_xxxxx または sk_test_xxxxx">
                                <p class="description">シークレットキー（sk_live_ または sk_test_ で始まる）</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="webhook_secret">Webhook Secret</label></th>
                            <td>
                                <input type="password" id="webhook_secret" name="webhook_secret"
                                       value="<?php echo esc_attr($settings['webhook_secret']); ?>"
                                       class="regular-text" placeholder="whsec_xxxxx">
                                <p class="description">Webhookの署名シークレット（whsec_ で始まる）</p>
                            </td>
                        </tr>
                    </table>

                    <h2>料金プラン設定</h2>

                    <table class="form-table">
                        <tr>
                            <th><label for="price_monthly">月額プラン Price ID</label></th>
                            <td>
                                <input type="text" id="price_monthly" name="price_monthly"
                                       value="<?php echo esc_attr($settings['price_monthly']); ?>"
                                       class="regular-text" placeholder="price_xxxxx">
                                <p class="description">月額¥1,000のPrice ID（price_ で始まる）</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="price_annual">年額プラン Price ID</label></th>
                            <td>
                                <input type="text" id="price_annual" name="price_annual"
                                       value="<?php echo esc_attr($settings['price_annual']); ?>"
                                       class="regular-text" placeholder="price_xxxxx">
                                <p class="description">年額¥10,000のPrice ID（price_ で始まる）</p>
                            </td>
                        </tr>
                    </table>

                    <h2>スポット決済設定</h2>

                    <table class="form-table">
                        <tr>
                            <th><label for="price_cafe">ありがとうカフェ Price ID</label></th>
                            <td>
                                <input type="text" id="price_cafe" name="price_cafe"
                                       value="<?php echo esc_attr($settings['price_cafe']); ?>"
                                       class="regular-text" placeholder="price_xxxxx">
                                <p class="description">カフェ参加費¥1,000のPrice ID（price_ で始まる・One time）</p>
                            </td>
                        </tr>
                    </table>

                    <p class="submit">
                        <input type="submit" name="arigatou_stripe_save" class="button button-primary" value="設定を保存">
                    </p>
                </form>
            </div>

            <!-- ヘルプサイドバー -->
            <div>
                <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
                    <h3 style="margin-top: 0;">Webhook URL</h3>
                    <p>Stripe Dashboardで以下のURLを設定してください：</p>
                    <code style="background: #f5f5f5; padding: 8px 12px; display: block; border-radius: 4px; word-break: break-all; font-size: 12px;">
                        <?php echo esc_url(home_url('/wp-json/arigatou/v1/stripe-webhook')); ?>
                    </code>
                </div>

                <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
                    <h3 style="margin-top: 0;">必要なWebhookイベント</h3>
                    <ul style="margin: 0; padding-left: 20px;">
                        <li><code>checkout.session.completed</code></li>
                        <li><code>customer.subscription.created</code></li>
                        <li><code>customer.subscription.updated</code></li>
                        <li><code>customer.subscription.deleted</code></li>
                        <li><code>invoice.payment_succeeded</code></li>
                        <li><code>invoice.payment_failed</code></li>
                    </ul>
                </div>

                <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h3 style="margin-top: 0;">設定手順</h3>
                    <ol style="margin: 0; padding-left: 20px;">
                        <li><a href="https://dashboard.stripe.com/apikeys" target="_blank">APIキー</a>を取得</li>
                        <li><a href="https://dashboard.stripe.com/products" target="_blank">Products</a>で商品を作成</li>
                        <li>Priceを2つ作成（月額・年額）</li>
                        <li><a href="https://dashboard.stripe.com/webhooks" target="_blank">Webhooks</a>を設定</li>
                        <li>上のフォームに入力して保存</li>
                    </ol>
                    <p style="margin-bottom: 0; margin-top: 15px;">
                        <a href="https://dashboard.stripe.com/" target="_blank" class="button">
                            Stripe Dashboardを開く
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * ユーザープロフィールにサブスクリプション情報を表示
 */
function arigatou_show_subscription_info_in_profile($user) {
    if (!current_user_can('manage_options')) {
        return;
    }

    $info = arigatou_get_subscription_info($user->ID);

    if (empty($info['customer_id'])) {
        return;
    }

    ?>
    <h2>サブスクリプション情報</h2>
    <table class="form-table">
        <tr>
            <th>会員ステータス</th>
            <td><?php echo esc_html(arigatou_is_premium_member($user->ID) ? '有料会員' : '無料会員'); ?></td>
        </tr>
        <tr>
            <th>プラン</th>
            <td><?php echo esc_html(arigatou_get_plan_name($info['plan']) ?: '-'); ?></td>
        </tr>
        <tr>
            <th>サブスクリプション状態</th>
            <td><?php echo esc_html(arigatou_get_subscription_status_label($info['status']) ?: '-'); ?></td>
        </tr>
        <tr>
            <th>次回更新日</th>
            <td><?php echo !empty($info['end_date']) ? esc_html(date('Y年n月j日', $info['end_date'])) : '-'; ?></td>
        </tr>
        <tr>
            <th>Stripe Customer ID</th>
            <td>
                <code><?php echo esc_html($info['customer_id']); ?></code>
                <a href="https://dashboard.stripe.com/customers/<?php echo esc_attr($info['customer_id']); ?>" target="_blank" style="margin-left: 10px;">Stripeで見る</a>
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'arigatou_show_subscription_info_in_profile');
add_action('edit_user_profile', 'arigatou_show_subscription_info_in_profile');
