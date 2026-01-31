<?php
/**
 * Template Name: 会員登録
 *
 * 有料会員登録・プラン選択ページ
 */

get_header(); ?>

<main id="main" class="site-main wa-style">

    <!-- ページヘッダー -->
    <section class="page-header">
        <div class="wa-pattern-overlay"></div>
        <div class="container">
            <h1 class="page-title">会員登録</h1>
            <p class="page-subtitle">ありがとう倶楽部に参加しよう</p>
        </div>
    </section>

    <!-- 会員登録セクション -->
    <section class="membership-section section">
        <div class="container">

            <?php if (is_user_logged_in() && arigatou_is_premium_member()) : ?>
                <!-- 既に有料会員の場合 -->
                <?php $info = arigatou_get_subscription_info(); ?>
                <div class="membership-current">
                    <div class="current-status-card">
                        <div class="status-badge premium">有料会員</div>
                        <h2>現在の会員ステータス</h2>

                        <div class="subscription-details">
                            <div class="detail-item">
                                <span class="label">プラン</span>
                                <span class="value"><?php echo esc_html(arigatou_get_plan_name($info['plan'])); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="label">ステータス</span>
                                <span class="value status-<?php echo esc_attr($info['status']); ?>">
                                    <?php echo esc_html(arigatou_get_subscription_status_label($info['status'])); ?>
                                </span>
                            </div>
                            <?php if (!empty($info['end_date'])) : ?>
                            <div class="detail-item">
                                <span class="label">
                                    <?php echo $info['status'] === 'canceling' ? '終了予定日' : '次回更新日'; ?>
                                </span>
                                <span class="value"><?php echo esc_html(date('Y年n月j日', $info['end_date'])); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="membership-actions">
                            <?php if ($info['status'] === 'canceling') : ?>
                                <button type="button" class="btn btn-primary" id="reactivate-btn">解約をキャンセル</button>
                            <?php else : ?>
                                <button type="button" class="btn btn-secondary" id="portal-btn">支払い情報を管理</button>
                                <button type="button" class="btn btn-outline" id="cancel-btn">解約する</button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <p class="my-account-link">
                        <a href="<?php echo esc_url(home_url('/my-account/')); ?>">マイページへ</a>
                    </p>
                </div>

            <?php else : ?>
                <!-- 料金プラン -->
                <div class="membership-intro">
                    <p>ありがとう倶楽部では、無料会員と有料会員をご用意しています。</p>
                    <p>有料会員になると、ありがとうカフェへの参加が無制限になるほか、セミナーやグッズが会員価格でご利用いただけます。</p>
                </div>

                <div class="pricing-cards">

                    <!-- 無料会員 -->
                    <div class="pricing-card">
                        <div class="pricing-header">
                            <h3>無料会員</h3>
                            <div class="price">
                                <span class="amount">0</span>
                                <span class="currency">円</span>
                            </div>
                        </div>
                        <div class="pricing-body">
                            <ul class="feature-list">
                                <li><i class="fas fa-check"></i> Facebookグループ参加</li>
                                <li><i class="fas fa-check"></i> イベント情報の受信</li>
                                <li><i class="fas fa-check"></i> スポット参加（別途料金）</li>
                            </ul>
                        </div>
                        <div class="pricing-footer">
                            <?php if (!is_user_logged_in()) : ?>
                                <a href="<?php echo esc_url(wp_registration_url()); ?>" class="btn btn-secondary">無料会員登録</a>
                            <?php else : ?>
                                <span class="current-plan-badge">現在のプラン</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- 月額プラン -->
                    <div class="pricing-card featured">
                        <div class="pricing-badge">おすすめ</div>
                        <div class="pricing-header">
                            <h3>有料会員（月額）</h3>
                            <div class="price">
                                <span class="amount">1,000</span>
                                <span class="currency">円</span>
                                <span class="period">/月</span>
                            </div>
                        </div>
                        <div class="pricing-body">
                            <ul class="feature-list">
                                <li><i class="fas fa-check"></i> ありがとうカフェ参加し放題</li>
                                <li><i class="fas fa-check"></i> セミナー会員価格</li>
                                <li><i class="fas fa-check"></i> グッズ会員価格</li>
                                <li><i class="fas fa-check"></i> 全ての無料会員特典</li>
                            </ul>
                            <?php if (!is_user_logged_in()) : ?>
                            <div class="guest-checkout-form">
                                <label for="guest-email-monthly">メールアドレス <span class="required">*</span></label>
                                <input type="email" id="guest-email-monthly" class="guest-email-input" placeholder="example@email.com" required>
                                <p class="input-note">決済完了後、アカウントを自動作成します</p>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="pricing-footer">
                            <button type="button" class="btn btn-primary checkout-btn" data-plan="monthly">
                                月額プランに申し込む
                            </button>
                        </div>
                    </div>

                    <!-- 年額プラン -->
                    <div class="pricing-card">
                        <div class="pricing-badge save">2ヶ月分お得</div>
                        <div class="pricing-header">
                            <h3>有料会員（年額）</h3>
                            <div class="price">
                                <span class="amount">10,000</span>
                                <span class="currency">円</span>
                                <span class="period">/年</span>
                            </div>
                        </div>
                        <div class="pricing-body">
                            <ul class="feature-list">
                                <li><i class="fas fa-check"></i> 月額プランと同じ特典</li>
                                <li><i class="fas fa-star"></i> 年間で2,000円お得</li>
                            </ul>
                            <p class="special-note">
                                <i class="fas fa-info-circle"></i>
                                年金生活者・学生は無料
                            </p>
                            <?php if (!is_user_logged_in()) : ?>
                            <div class="guest-checkout-form">
                                <label for="guest-email-annual">メールアドレス <span class="required">*</span></label>
                                <input type="email" id="guest-email-annual" class="guest-email-input" placeholder="example@email.com" required>
                                <p class="input-note">決済完了後、アカウントを自動作成します</p>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="pricing-footer">
                            <button type="button" class="btn btn-primary checkout-btn" data-plan="annual">
                                年額プランに申し込む
                            </button>
                        </div>
                    </div>

                </div>

                <!-- スポット参加決済 -->
                <div class="spot-payment-section">
                    <h2 class="spot-section-title">カフェにスポット参加する</h2>

                    <div class="pricing-card spot-card">
                        <div class="pricing-header">
                            <h3>ありがとうカフェ</h3>
                            <p class="spot-description">参加費</p>
                            <div class="price">
                                <span class="currency">¥</span>
                                <span class="amount">1,000</span>
                                <span class="period">/ 1回</span>
                            </div>
                        </div>
                        <div class="pricing-body">
                            <ul class="feature-list">
                                <li><i class="fas fa-coffee"></i> ありがとうカフェへ1回参加</li>
                                <li><i class="fas fa-users"></i> 会員同士の交流</li>
                                <li><i class="fas fa-heart"></i> ありがとうの輪を広げよう</li>
                            </ul>

                            <?php if (!is_user_logged_in()) : ?>
                            <div class="spot-email-input">
                                <label for="spot-email-cafe">メールアドレス <span class="required">*</span></label>
                                <input type="email" id="spot-email-cafe" placeholder="example@email.com" required>
                                <p class="input-note">確認メールをお送りします</p>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="pricing-footer">
                            <button type="button" class="btn btn-primary spot-checkout-btn" data-product="cafe">
                                <i class="fas fa-credit-card"></i> 決済へ進む
                            </button>
                        </div>
                    </div>

                    <p class="spot-premium-note">
                        <i class="fas fa-info-circle"></i>
                        有料会員になるとカフェ参加費が無料になります
                    </p>
                </div>

                <?php if (!is_user_logged_in()) : ?>
                <div class="guest-checkout-notice">
                    <p>
                        <i class="fas fa-info-circle"></i>
                        決済完了後にアカウントが自動作成され、ログイン情報がメールで届きます。
                        既にアカウントをお持ちの方は<a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>">ログイン</a>してください。
                    </p>
                </div>
                <?php endif; ?>

                <!-- FAQ -->
                <div class="membership-faq">
                    <h2>よくあるご質問</h2>

                    <div class="faq-item">
                        <h3>支払い方法は？</h3>
                        <p>クレジットカード（Visa, Mastercard, American Express, JCB）でお支払いいただけます。</p>
                    </div>

                    <div class="faq-item">
                        <h3>解約はいつでもできますか？</h3>
                        <p>はい、マイページからいつでも解約できます。解約しても、現在の期間終了までは特典をご利用いただけます。</p>
                    </div>

                    <div class="faq-item">
                        <h3>年金生活者・学生の無料会員とは？</h3>
                        <p>年金生活者の方、学生の方は有料会員特典を無料でご利用いただけます。お問い合わせフォームからご連絡ください。</p>
                    </div>
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
