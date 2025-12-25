<?php
/**
 * Template Name: スポット決済
 *
 * カフェ参加費などの単発決済ページ
 */

get_header(); ?>

<main id="main" class="site-main wa-style">

    <!-- ページヘッダー -->
    <section class="page-header">
        <div class="wa-pattern-overlay"></div>
        <div class="container">
            <h1 class="page-title">スポット決済</h1>
            <p class="page-subtitle">イベント参加費のお支払い</p>
        </div>
    </section>

    <!-- スポット決済セクション -->
    <section class="membership-section section">
        <div class="container">

            <div class="membership-intro">
                <p>各種イベントへの参加費をお支払いいただけます。</p>
                <p>決済完了後、確認メールをお送りします。</p>
            </div>

            <div class="spot-payment-cards">

                <!-- ありがとうカフェ 参加費 -->
                <div class="pricing-card spot-card">
                    <div class="pricing-header">
                        <h3>ありがとうカフェ</h3>
                        <p class="spot-description">参加費</p>
                        <div class="price">
                            <span class="currency">¥</span>
                            <span class="amount">1,000</span>
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

            </div>

            <!-- 注意事項 -->
            <div class="spot-payment-notice">
                <h3><i class="fas fa-info-circle"></i> お支払いについて</h3>
                <ul>
                    <li>クレジットカード・デビットカードでお支払いいただけます</li>
                    <li>決済完了後に確認メールをお送りします</li>
                    <li>有料会員の方はカフェ参加費が無料です（<a href="<?php echo esc_url(home_url('/membership/')); ?>">会員登録はこちら</a>）</li>
                </ul>
            </div>

        </div>
    </section>

</main>

<!-- スポット決済用JavaScript -->
<script>
(function($) {
    'use strict';

    $(document).ready(function() {
        $('.spot-checkout-btn').on('click', function(e) {
            e.preventDefault();

            var $btn = $(this);
            var productType = $btn.data('product');
            var $card = $btn.closest('.spot-card');
            var $emailInput = $card.find('input[type="email"]');
            var email = $emailInput.length ? $emailInput.val() : '';
            var originalText = $btn.html();

            // メールアドレス検証（非ログイン時）
            <?php if (!is_user_logged_in()) : ?>
            if (!email || !validateEmail(email)) {
                alert('有効なメールアドレスを入力してください');
                $emailInput.focus();
                return;
            }
            <?php endif; ?>

            // ボタンを無効化
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> 処理中...');

            $.ajax({
                url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
                type: 'POST',
                data: {
                    action: 'arigatou_create_spot_checkout',
                    product_type: productType,
                    email: email,
                    nonce: '<?php echo esc_js(wp_create_nonce('arigatou_spot_nonce')); ?>'
                },
                success: function(response) {
                    if (response.success && response.data.checkout_url) {
                        window.location.href = response.data.checkout_url;
                    } else {
                        alert(response.data && response.data.message ? response.data.message : 'エラーが発生しました');
                        $btn.prop('disabled', false).html(originalText);
                    }
                },
                error: function() {
                    alert('通信エラーが発生しました。もう一度お試しください。');
                    $btn.prop('disabled', false).html(originalText);
                }
            });
        });

        function validateEmail(email) {
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
    });
})(jQuery);
</script>

<style>
/* スポット決済追加スタイル */
.spot-payment-cards {
    display: flex;
    justify-content: center;
    gap: 30px;
    flex-wrap: wrap;
    max-width: 500px;
    margin: 0 auto;
}

.spot-card {
    width: 100%;
    max-width: 400px;
}

.spot-description {
    color: var(--wa-text-muted);
    margin: 0 0 10px;
    font-size: 0.9rem;
}

.spot-email-input {
    margin-top: 20px;
    padding: 15px;
    background: var(--wa-bg-cream);
    border-radius: 8px;
}

.spot-email-input label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: var(--wa-text-dark);
}

.spot-email-input .required {
    color: #D32F2F;
}

.spot-email-input input[type="email"] {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
    box-sizing: border-box;
}

.spot-email-input input[type="email"]:focus {
    outline: none;
    border-color: var(--wa-accent);
    box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.1);
}

.spot-email-input .input-note {
    margin: 8px 0 0;
    font-size: 0.85rem;
    color: var(--wa-text-muted);
}

.spot-checkout-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.spot-payment-notice {
    max-width: 600px;
    margin: 50px auto 0;
    padding: 25px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
}

.spot-payment-notice h3 {
    margin: 0 0 15px;
    color: var(--wa-primary);
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.spot-payment-notice h3 i {
    color: var(--wa-accent);
}

.spot-payment-notice ul {
    margin: 0;
    padding-left: 20px;
}

.spot-payment-notice li {
    margin: 8px 0;
    color: var(--wa-text-dark);
    line-height: 1.6;
}

.spot-payment-notice a {
    color: var(--wa-accent);
    text-decoration: underline;
}
</style>

<?php get_footer(); ?>
