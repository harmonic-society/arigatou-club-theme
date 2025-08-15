/**
 * ありがとう倶楽部 メインJavaScript
 */

(function($) {
    'use strict';

    // DOM Ready
    $(document).ready(function() {
        
        // モバイルメニュートグル（Android対応版）
        $(document).on('click touchstart', '.menu-toggle', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $toggle = $(this);
            var $nav = $('.main-navigation');
            var isOpen = $nav.hasClass('active');
            
            if (!isOpen) {
                // メニューを開く
                $toggle.addClass('active');
                $nav.addClass('active');
                
                // オーバーレイがまだない場合のみ追加
                if ($('.menu-overlay').length === 0) {
                    $('body').append('<div class="menu-overlay"></div>');
                }
                
                // 少し遅延させてアニメーションを開始
                setTimeout(function() {
                    $('.menu-overlay').addClass('active');
                    $('body').addClass('menu-open');
                }, 10);
                
                // アクセシビリティ
                $toggle.attr('aria-expanded', 'true');
                $nav.attr('aria-hidden', 'false');
                
                // メニューアイテムのアニメーション
                $nav.find('li').each(function(index) {
                    var $item = $(this);
                    setTimeout(function() {
                        $item.addClass('animated');
                    }, index * 50);
                });
            } else {
                // メニューを閉じる
                closeMenu();
            }
            
            return false;
        });
        
        // メニューを閉じる関数
        function closeMenu() {
            $('.menu-toggle').removeClass('active');
            $('.main-navigation').removeClass('active');
            $('.menu-overlay').removeClass('active');
            $('body').removeClass('menu-open');
            
            // アクセシビリティ
            $('.menu-toggle').attr('aria-expanded', 'false');
            $('.main-navigation').attr('aria-hidden', 'true');
            
            // オーバーレイを削除
            setTimeout(function() {
                $('.menu-overlay').remove();
            }, 300);
            
            // アニメーションクラスをリセット
            $('.main-navigation li').removeClass('animated');
        }
        
        // オーバーレイクリックでメニューを閉じる（タッチイベント対応）
        $(document).on('click touchstart', '.menu-overlay', function(e) {
            e.preventDefault();
            closeMenu();
            return false;
        });
        
        // ESCキーでメニューを閉じる
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $('.main-navigation').hasClass('active')) {
                closeMenu();
            }
        });
        
        // メニューリンククリックでメニューを閉じる（モバイル時）
        $(document).on('click touchstart', '.main-navigation a', function() {
            if ($(window).width() < 1024 && !$(this).attr('target')) {
                setTimeout(function() {
                    closeMenu();
                }, 300);
            }
        });
        
        // スムーススクロール
        $('a[href*="#"]').not('[href="#"]').not('[href="#0"]').on('click', function(event) {
            if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && 
                location.hostname === this.hostname) {
                
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                
                if (target.length) {
                    event.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 800, 'swing');
                }
            }
        });
        
        // ヘッダーのスクロール制御
        var lastScrollTop = 0;
        var header = $('.site-header');
        var headerHeight = header.outerHeight();
        
        $(window).on('scroll', function() {
            var scrollTop = $(this).scrollTop();
            
            // スクロール時にヘッダーに影を追加
            if (scrollTop > 10) {
                header.addClass('scrolled');
            } else {
                header.removeClass('scrolled');
            }
            
            // ヘッダーの表示/非表示制御（オプション）
            if (scrollTop > lastScrollTop && scrollTop > headerHeight) {
                // 下にスクロール - ヘッダーを隠す
                header.addClass('header-hidden');
            } else {
                // 上にスクロール - ヘッダーを表示
                header.removeClass('header-hidden');
            }
            
            lastScrollTop = scrollTop;
        });
        
        // アニメーション要素の遅延読み込み
        var animateElements = $('.animate-on-scroll');
        
        function checkAnimation() {
            var windowHeight = $(window).height();
            var windowTop = $(window).scrollTop();
            var windowBottom = windowTop + windowHeight;
            
            animateElements.each(function() {
                var element = $(this);
                var elementTop = element.offset().top;
                var elementBottom = elementTop + element.outerHeight();
                
                if (elementBottom >= windowTop && elementTop <= windowBottom) {
                    element.addClass('animated');
                }
            });
        }
        
        if (animateElements.length) {
            $(window).on('scroll', checkAnimation);
            checkAnimation();
        }
        
        // カードのホバーエフェクト
        $('.card').on('mouseenter', function() {
            $(this).addClass('card-hover');
        }).on('mouseleave', function() {
            $(this).removeClass('card-hover');
        });
        
        // イベント日付のフォーマット
        $('.event-date').each(function() {
            var dateStr = $(this).text();
            if (dateStr) {
                var date = new Date(dateStr);
                var options = { year: 'numeric', month: 'long', day: 'numeric' };
                var formattedDate = date.toLocaleDateString('ja-JP', options);
                $(this).text(formattedDate);
            }
        });
        
        // 画像の遅延読み込み
        if ('IntersectionObserver' in window) {
            var lazyImages = document.querySelectorAll('img[data-lazy]');
            
            var imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        var img = entry.target;
                        img.src = img.dataset.lazy;
                        img.removeAttribute('data-lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            lazyImages.forEach(function(img) {
                imageObserver.observe(img);
            });
        }
        
        // フォームバリデーション
        $('form').on('submit', function(e) {
            var form = $(this);
            var isValid = true;
            
            // 必須フィールドのチェック
            form.find('[required]').each(function() {
                var field = $(this);
                if (!field.val().trim()) {
                    field.addClass('error');
                    isValid = false;
                } else {
                    field.removeClass('error');
                }
            });
            
            // メールアドレスのバリデーション
            form.find('input[type="email"]').each(function() {
                var email = $(this).val();
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (email && !emailRegex.test(email)) {
                    $(this).addClass('error');
                    isValid = false;
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('入力内容をご確認ください。');
            }
        });
        
        // タブ機能
        $('.tab-nav a').on('click', function(e) {
            e.preventDefault();
            var target = $(this).attr('href');
            
            $('.tab-nav a').removeClass('active');
            $(this).addClass('active');
            
            $('.tab-content').removeClass('active');
            $(target).addClass('active');
        });
        
        // アコーディオン
        $('.accordion-header').on('click', function() {
            var accordion = $(this).parent();
            var content = accordion.find('.accordion-content');
            
            accordion.toggleClass('active');
            content.slideToggle(300);
            
            // 他のアコーディオンを閉じる
            accordion.siblings('.accordion-item').removeClass('active')
                .find('.accordion-content').slideUp(300);
        });
        
        // モーダル機能
        $('[data-modal]').on('click', function(e) {
            e.preventDefault();
            var modalId = $(this).data('modal');
            $('#' + modalId).fadeIn(300);
            $('body').addClass('modal-open');
        });
        
        $('.modal-close, .modal-overlay').on('click', function() {
            $(this).closest('.modal').fadeOut(300);
            $('body').removeClass('modal-open');
        });
        
        // カウントアップアニメーション
        $('.counter').each(function() {
            var $this = $(this);
            var countTo = $this.attr('data-count');
            
            $({ countNum: $this.text() }).animate({
                countNum: countTo
            }, {
                duration: 2000,
                easing: 'swing',
                step: function() {
                    $this.text(Math.floor(this.countNum));
                },
                complete: function() {
                    $this.text(this.countNum);
                }
            });
        });
        
        // トースト通知
        function showToast(message, type = 'info') {
            var toast = $('<div class="toast toast-' + type + '">' + message + '</div>');
            $('body').append(toast);
            
            setTimeout(function() {
                toast.addClass('show');
            }, 100);
            
            setTimeout(function() {
                toast.removeClass('show');
                setTimeout(function() {
                    toast.remove();
                }, 300);
            }, 3000);
        }
        
        // AJAX フォーム送信の例
        $('.ajax-form').on('submit', function(e) {
            e.preventDefault();
            
            var form = $(this);
            var formData = form.serialize();
            
            $.ajax({
                url: arigatou_ajax.ajax_url,
                type: 'POST',
                data: formData + '&action=submit_form&nonce=' + arigatou_ajax.nonce,
                success: function(response) {
                    if (response.success) {
                        showToast('送信が完了しました', 'success');
                        form[0].reset();
                    } else {
                        showToast('エラーが発生しました', 'error');
                    }
                },
                error: function() {
                    showToast('通信エラーが発生しました', 'error');
                }
            });
        });
        
    });
    
    // Window Load
    $(window).on('load', function() {
        // ローディング画面を非表示
        $('.loading-screen').fadeOut(500);
        
        // ページ全体のフェードイン
        $('body').addClass('loaded');
    });
    
})(jQuery);