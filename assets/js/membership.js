/**
 * 会員登録・マイページ用JavaScript
 */
(function($) {
    'use strict';

    $(document).ready(function() {

        // メールアドレス検証
        function validateEmail(email) {
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        // Checkoutボタンクリック
        $('.checkout-btn').on('click', function(e) {
            e.preventDefault();

            var $btn = $(this);
            var planType = $btn.data('plan');
            var originalText = $btn.text();
            var $card = $btn.closest('.pricing-card');
            var $emailInput = $card.find('.guest-email-input');
            var email = $emailInput.length ? $emailInput.val().trim() : '';

            // 非ログイン時はメールアドレス必須
            if ($emailInput.length && (!email || !validateEmail(email))) {
                alert('有効なメールアドレスを入力してください');
                $emailInput.focus();
                return;
            }

            // ボタンを無効化
            $btn.prop('disabled', true).text('処理中...');

            // リクエストデータ
            var requestData = {
                action: 'arigatou_create_checkout',
                plan_type: planType,
                nonce: arigatou_ajax.nonce
            };

            // ゲスト決済の場合はメールアドレスを追加
            if (email) {
                requestData.email = email;
            }

            $.ajax({
                url: arigatou_ajax.ajax_url,
                type: 'POST',
                data: requestData,
                success: function(response) {
                    if (response.success && response.data.checkout_url) {
                        // Stripe Checkoutへリダイレクト
                        window.location.href = response.data.checkout_url;
                    } else {
                        // エラー処理
                        if (response.data && response.data.redirect) {
                            window.location.href = response.data.redirect;
                        } else {
                            alert(response.data && response.data.message ? response.data.message : 'エラーが発生しました');
                            $btn.prop('disabled', false).text(originalText);
                        }
                    }
                },
                error: function() {
                    alert('通信エラーが発生しました。もう一度お試しください。');
                    $btn.prop('disabled', false).text(originalText);
                }
            });
        });

        // Customer Portalボタンクリック
        $('#portal-btn').on('click', function(e) {
            e.preventDefault();

            var $btn = $(this);
            var originalText = $btn.text();

            $btn.prop('disabled', true).text('処理中...');

            $.ajax({
                url: arigatou_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'arigatou_create_portal_session',
                    nonce: arigatou_ajax.nonce
                },
                success: function(response) {
                    if (response.success && response.data.portal_url) {
                        window.location.href = response.data.portal_url;
                    } else {
                        alert(response.data && response.data.message ? response.data.message : 'エラーが発生しました');
                        $btn.prop('disabled', false).text(originalText);
                    }
                },
                error: function() {
                    alert('通信エラーが発生しました。もう一度お試しください。');
                    $btn.prop('disabled', false).text(originalText);
                }
            });
        });

        // 解約ボタンクリック - モーダル表示
        $('#cancel-btn').on('click', function(e) {
            e.preventDefault();
            $('#cancel-modal').fadeIn(200);
        });

        // モーダル閉じる
        $('#cancel-modal-close, .modal-overlay').on('click', function() {
            $('#cancel-modal').fadeOut(200);
        });

        // 解約確定
        $('#cancel-confirm').on('click', function(e) {
            e.preventDefault();

            var $btn = $(this);
            var originalText = $btn.text();

            $btn.prop('disabled', true).text('処理中...');

            $.ajax({
                url: arigatou_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'arigatou_cancel_subscription',
                    nonce: arigatou_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.data.message || '解約を予約しました。');
                        window.location.reload();
                    } else {
                        alert(response.data && response.data.message ? response.data.message : 'エラーが発生しました');
                        $btn.prop('disabled', false).text(originalText);
                    }
                },
                error: function() {
                    alert('通信エラーが発生しました。もう一度お試しください。');
                    $btn.prop('disabled', false).text(originalText);
                }
            });
        });

        // 解約キャンセル（再有効化）
        $('#reactivate-btn').on('click', function(e) {
            e.preventDefault();

            if (!confirm('解約予約をキャンセルしますか？')) {
                return;
            }

            var $btn = $(this);
            var originalText = $btn.text();

            $btn.prop('disabled', true).text('処理中...');

            $.ajax({
                url: arigatou_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'arigatou_reactivate_subscription',
                    nonce: arigatou_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.data.message || '解約予約をキャンセルしました。');
                        window.location.reload();
                    } else {
                        alert(response.data && response.data.message ? response.data.message : 'エラーが発生しました');
                        $btn.prop('disabled', false).text(originalText);
                    }
                },
                error: function() {
                    alert('通信エラーが発生しました。もう一度お試しください。');
                    $btn.prop('disabled', false).text(originalText);
                }
            });
        });

        // ESCキーでモーダルを閉じる
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $('#cancel-modal').is(':visible')) {
                $('#cancel-modal').fadeOut(200);
            }
        });

    });

})(jQuery);
