<?php
/**
 * Template Name: 会員登録完了
 *
 * Stripe Checkout成功後のリダイレクトページ
 */

get_header(); ?>

<main id="main" class="site-main wa-style">

    <!-- ページヘッダー -->
    <section class="page-header">
        <div class="wa-pattern-overlay"></div>
        <div class="container">
            <h1 class="page-title">登録完了</h1>
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

                <h2>有料会員登録が完了しました</h2>

                <p>ありがとう倶楽部の有料会員へようこそ！</p>
                <p>登録完了のメールをお送りしましたので、ご確認ください。</p>

                <?php if (is_user_logged_in()) : ?>
                    <?php $info = arigatou_get_subscription_info(); ?>
                    <?php if (!empty($info['plan'])) : ?>
                    <div class="subscription-summary">
                        <h3>ご登録内容</h3>
                        <div class="summary-item">
                            <span class="label">プラン</span>
                            <span class="value"><?php echo esc_html(arigatou_get_plan_name($info['plan'])); ?></span>
                        </div>
                        <?php if (!empty($info['end_date'])) : ?>
                        <div class="summary-item">
                            <span class="label">次回更新日</span>
                            <span class="value"><?php echo esc_html(date('Y年n月j日', $info['end_date'])); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="success-actions">
                    <a href="<?php echo esc_url(home_url('/my-account/')); ?>" class="btn btn-primary">マイページへ</a>
                    <a href="<?php echo esc_url(home_url()); ?>" class="btn btn-secondary">トップページへ</a>
                </div>
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>
