<?php
/**
 * スポット決済管理
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * スポット決済テーブル作成
 */
function arigatou_spot_payments_install() {
    $db_version = '1.0';
    $installed_version = get_option('arigatou_spot_payments_db_version');

    if ($installed_version === $db_version) {
        return;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'arigatou_spot_payments';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        customer_email varchar(255) NOT NULL,
        customer_name varchar(255) DEFAULT '',
        product_type varchar(50) DEFAULT '',
        product_name varchar(255) DEFAULT '',
        amount int(11) DEFAULT 0,
        stripe_session_id varchar(255) NOT NULL DEFAULT '',
        paid_at datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
        PRIMARY KEY  (id),
        UNIQUE KEY stripe_session_id (stripe_session_id),
        KEY paid_at (paid_at)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);

    update_option('arigatou_spot_payments_db_version', $db_version);
}
add_action('after_switch_theme', 'arigatou_spot_payments_install');
add_action('init', 'arigatou_spot_payments_install');

/**
 * スポット決済を保存
 *
 * @param array $data
 */
function arigatou_save_spot_payment($data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'arigatou_spot_payments';

    $wpdb->query(
        $wpdb->prepare(
            "INSERT IGNORE INTO $table_name
                (customer_email, customer_name, product_type, product_name, amount, stripe_session_id, paid_at)
            VALUES (%s, %s, %s, %s, %d, %s, %s)",
            $data['customer_email'],
            $data['customer_name'],
            $data['product_type'],
            $data['product_name'],
            $data['amount'],
            $data['stripe_session_id'],
            current_time('mysql')
        )
    );
}

/**
 * スポット決済一覧ページ
 */
function arigatou_spot_payments_list_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'arigatou_spot_payments';

    $per_page = 20;
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset = ($current_page - 1) * $per_page;

    $total = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    $payments = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name ORDER BY paid_at DESC LIMIT %d OFFSET %d",
            $per_page,
            $offset
        )
    );

    ?>
    <div class="wrap">
        <h1>スポット決済一覧</h1>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>お名前</th>
                    <th>メールアドレス</th>
                    <th>商品</th>
                    <th>金額</th>
                    <th>決済日時</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($payments)) : ?>
                    <tr>
                        <td colspan="5">スポット決済がまだありません。</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($payments as $payment) : ?>
                        <tr>
                            <td><?php echo esc_html($payment->customer_name ?: '（名前なし）'); ?></td>
                            <td><?php echo esc_html($payment->customer_email); ?></td>
                            <td><?php echo esc_html($payment->product_name ?: $payment->product_type); ?></td>
                            <td>&yen;<?php echo esc_html(number_format($payment->amount)); ?></td>
                            <td><?php echo esc_html($payment->paid_at); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <?php
        if ($total > $per_page) {
            $pagination = paginate_links(array(
                'base'      => add_query_arg('paged', '%#%'),
                'format'    => '',
                'current'   => $current_page,
                'total'     => ceil($total / $per_page),
                'prev_text' => '&laquo;',
                'next_text' => '&raquo;',
            ));

            if ($pagination) {
                echo '<div class="tablenav"><div class="tablenav-pages">' . $pagination . '</div></div>';
            }
        }
        ?>
    </div>
    <?php
}
