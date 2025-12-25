<?php
/**
 * Template Name: マイページ
 *
 * 会員マイページ
 */

// ログインしていない場合はログインページへ
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}

$current_user = wp_get_current_user();
$info = arigatou_get_subscription_info();
$is_premium = arigatou_is_premium_member();

get_header(); ?>

<main id="main" class="site-main wa-style">

    <!-- ページヘッダー -->
    <section class="page-header">
        <div class="wa-pattern-overlay"></div>
        <div class="container">
            <h1 class="page-title">マイページ</h1>
            <p class="page-subtitle"><?php echo esc_html($current_user->display_name); ?> さん</p>
        </div>
    </section>

    <!-- マイページコンテンツ -->
    <section class="my-account-section section">
        <div class="container">

            <div class="my-account-grid">

                <!-- 会員ステータス -->
                <div class="account-card status-card">
                    <div class="card-header">
                        <h2><i class="fas fa-id-card"></i> 会員ステータス</h2>
                    </div>
                    <div class="card-body">
                        <div class="member-status <?php echo $is_premium ? 'premium' : 'free'; ?>">
                            <span class="status-badge"><?php echo $is_premium ? '有料会員' : '無料会員'; ?></span>
                        </div>

                        <?php if ($is_premium && !empty($info['plan'])) : ?>
                            <div class="subscription-info">
                                <div class="info-row">
                                    <span class="label">プラン</span>
                                    <span class="value"><?php echo esc_html(arigatou_get_plan_name($info['plan'])); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="label">ステータス</span>
                                    <span class="value status-<?php echo esc_attr($info['status']); ?>">
                                        <?php echo esc_html(arigatou_get_subscription_status_label($info['status'])); ?>
                                    </span>
                                </div>
                                <?php if (!empty($info['end_date'])) : ?>
                                <div class="info-row">
                                    <span class="label">
                                        <?php echo $info['status'] === 'canceling' ? '終了予定日' : '次回更新日'; ?>
                                    </span>
                                    <span class="value"><?php echo esc_html(date('Y年n月j日', $info['end_date'])); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="subscription-actions">
                                <?php if ($info['status'] === 'canceling') : ?>
                                    <button type="button" class="btn btn-primary btn-sm" id="reactivate-btn">
                                        解約をキャンセル
                                    </button>
                                <?php else : ?>
                                    <button type="button" class="btn btn-secondary btn-sm" id="portal-btn">
                                        支払い情報を管理
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php else : ?>
                            <p class="upgrade-prompt">
                                有料会員になると、ありがとうカフェへの参加が無制限に！
                            </p>
                            <a href="<?php echo esc_url(home_url('/membership/')); ?>" class="btn btn-primary">
                                有料会員になる
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- アカウント情報 -->
                <div class="account-card">
                    <div class="card-header">
                        <h2><i class="fas fa-user"></i> アカウント情報</h2>
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <span class="label">お名前</span>
                            <span class="value"><?php echo esc_html($current_user->display_name); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">メールアドレス</span>
                            <span class="value"><?php echo esc_html($current_user->user_email); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">登録日</span>
                            <span class="value"><?php echo esc_html(date('Y年n月j日', strtotime($current_user->user_registered))); ?></span>
                        </div>

                        <div class="account-actions">
                            <a href="<?php echo esc_url(get_edit_profile_url()); ?>" class="btn btn-secondary btn-sm">
                                プロフィールを編集
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 会員特典 -->
                <div class="account-card benefits-card">
                    <div class="card-header">
                        <h2><i class="fas fa-gift"></i> 会員特典</h2>
                    </div>
                    <div class="card-body">
                        <?php if ($is_premium) : ?>
                            <ul class="benefits-list premium">
                                <li class="available"><i class="fas fa-check"></i> ありがとうカフェ参加し放題</li>
                                <li class="available"><i class="fas fa-check"></i> セミナー会員価格</li>
                                <li class="available"><i class="fas fa-check"></i> グッズ会員価格</li>
                                <li class="available"><i class="fas fa-check"></i> Facebookグループ参加</li>
                                <li class="available"><i class="fas fa-check"></i> イベント情報の受信</li>
                            </ul>
                        <?php else : ?>
                            <ul class="benefits-list free">
                                <li class="available"><i class="fas fa-check"></i> Facebookグループ参加</li>
                                <li class="available"><i class="fas fa-check"></i> イベント情報の受信</li>
                                <li class="available"><i class="fas fa-check"></i> スポット参加（別途料金）</li>
                                <li class="unavailable"><i class="fas fa-lock"></i> ありがとうカフェ参加し放題</li>
                                <li class="unavailable"><i class="fas fa-lock"></i> セミナー会員価格</li>
                                <li class="unavailable"><i class="fas fa-lock"></i> グッズ会員価格</li>
                            </ul>
                            <a href="<?php echo esc_url(home_url('/membership/')); ?>" class="btn btn-primary btn-sm">
                                有料会員になる
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- クイックリンク -->
                <div class="account-card">
                    <div class="card-header">
                        <h2><i class="fas fa-link"></i> クイックリンク</h2>
                    </div>
                    <div class="card-body">
                        <ul class="quick-links">
                            <li>
                                <a href="<?php echo esc_url(home_url('/events/')); ?>">
                                    <i class="fas fa-calendar-alt"></i> イベント一覧
                                </a>
                            </li>
                            <li>
                                <a href="https://www.facebook.com/groups/arigatoh" target="_blank" rel="noopener">
                                    <i class="fab fa-facebook"></i> Facebookグループ
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url(home_url('/contact/')); ?>">
                                    <i class="fas fa-envelope"></i> お問い合わせ
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>">
                                    <i class="fas fa-sign-out-alt"></i> ログアウト
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

            <?php if ($is_premium && $info['status'] !== 'canceling') : ?>
            <div class="cancel-section">
                <p>
                    <button type="button" class="btn-text" id="cancel-btn">有料会員を解約する</button>
                </p>
            </div>
            <?php endif; ?>

        </div>
    </section>

</main>

<!-- 確認モーダル -->
<div id="cancel-modal" class="modal" style="display: none;">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <h3>解約の確認</h3>
        <p>本当に解約しますか？</p>
        <p class="modal-note">現在の期間終了後に解約されます。それまでは引き続き特典をご利用いただけます。</p>
        <div class="modal-actions">
            <button type="button" class="btn btn-outline" id="cancel-modal-close">キャンセル</button>
            <button type="button" class="btn btn-danger" id="cancel-confirm">解約する</button>
        </div>
    </div>
</div>

<?php get_footer(); ?>
