<?php
/**
 * Template Name: スポット決済完了
 *
 * Stripe Checkout（スポット決済）成功後のリダイレクトページ
 */

// Stripe Checkout Sessionから商品情報を取得
$product_name = 'ありがとうカフェ 参加費';
$product_amount = '¥1,000';

$session_id = isset($_GET['session_id']) ? sanitize_text_field($_GET['session_id']) : '';
if (!empty($session_id)) {
    // Checkout Sessionを取得
    $session = Arigatou_Stripe_API::request('/checkout/sessions/' . $session_id);

    if (!is_wp_error($session)) {
        // メタデータから商品名を取得
        if (!empty($session['metadata']['product_name'])) {
            $product_name = esc_html($session['metadata']['product_name']);
        }
        // 金額を取得
        if (!empty($session['amount_total'])) {
            $product_amount = '¥' . number_format($session['amount_total'] / 100);
        }
    }
}

get_header(); ?>

<main id="main" class="site-main wa-style">

    <!-- ページヘッダー -->
    <section class="page-header">
        <div class="wa-pattern-overlay"></div>
        <div class="container">
            <h1 class="page-title">決済完了</h1>
            <p class="page-subtitle">ありがとうございます！</p>
        </div>
    </section>

    <!-- 完了メッセージ -->
    <section class="membership-section section">
        <div class="container">
            <div class="success-message">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>

                <h2>お支払いが完了しました</h2>

                <p>ご購入いただきありがとうございます。</p>
                <p>確認メールをお送りしましたので、ご確認ください。</p>

                <div class="subscription-summary">
                    <h3>ご購入内容</h3>
                    <div class="summary-item">
                        <span class="label">商品</span>
                        <span class="value"><?php echo $product_name; ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="label">金額</span>
                        <span class="value"><?php echo $product_amount; ?></span>
                    </div>
                </div>

                <p class="spot-success-note">
                    <i class="fas fa-info-circle"></i>
                    当日のご参加をお待ちしております。ご不明な点がございましたらお問い合わせください。
                </p>

                <div class="success-actions">
                    <a href="<?php echo esc_url(home_url()); ?>" class="btn btn-primary">トップページへ</a>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-secondary">お問い合わせ</a>
                </div>
            </div>
        </div>
    </section>

</main>

<style>
.spot-success-note {
    margin-top: 20px;
    padding: 15px;
    background: var(--wa-bg-cream);
    border-radius: 8px;
    color: var(--wa-text-dark);
    display: flex;
    align-items: center;
    gap: 10px;
    text-align: left;
}

.spot-success-note i {
    color: var(--wa-accent);
    flex-shrink: 0;
}
</style>

<?php get_footer(); ?>
