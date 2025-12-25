<?php
/**
 * Template Name: 会員登録キャンセル
 *
 * Stripe Checkoutキャンセル時のリダイレクトページ
 */

get_header(); ?>

<main id="main" class="site-main wa-style">

    <!-- ページヘッダー -->
    <section class="page-header">
        <div class="wa-pattern-overlay"></div>
        <div class="container">
            <h1 class="page-title">登録キャンセル</h1>
            <p class="page-subtitle">お手続きが中断されました</p>
        </div>
    </section>

    <!-- キャンセルメッセージ -->
    <section class="membership-section section">
        <div class="container">
            <div class="cancel-message">
                <div class="cancel-icon">
                    <i class="fas fa-times-circle"></i>
                </div>

                <h2>お手続きがキャンセルされました</h2>

                <p>有料会員登録の手続きが完了しませんでした。</p>
                <p>再度お申し込みいただくか、ご不明な点がございましたらお問い合わせください。</p>

                <div class="cancel-actions">
                    <a href="<?php echo esc_url(home_url('/membership/')); ?>" class="btn btn-primary">会員登録ページに戻る</a>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-secondary">お問い合わせ</a>
                </div>
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>
