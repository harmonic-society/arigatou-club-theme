document.addEventListener('DOMContentLoaded', function() {
    // モバイル判定
    const isMobile = window.innerWidth <= 768;
    const isTouch = 'ontouchstart' in window;
    
    // サムネイルスライダー（モバイル用）
    const thumbnailSlider = document.querySelector('.thumbnails-slider');
    if (thumbnailSlider) {
        // スライドの数をチェック
        const slideCount = thumbnailSlider.querySelectorAll('.swiper-slide').length;
        console.log('Thumbnail slides found:', slideCount);
        
        if (slideCount > 0) {
            const thumbnailSwiper = new Swiper('.thumbnails-slider', {
            loop: slideCount > 1, // スライドが2枚以上の場合のみループ
            autoplay: slideCount > 1 ? {
                delay: 3500,
                disableOnInteraction: false,
            } : false,
            speed: 600,
            
            // タッチ操作の設定
            touchRatio: 1.2,
            touchAngle: 45,
            grabCursor: true,
            touchEventsTarget: 'container',
            touchReleaseOnEdges: true,
            
            // モバイル用の設定
            slidesPerView: 1,
            spaceBetween: 15,
            centeredSlides: true,
            
            pagination: {
                el: '.thumbnails-slider .swiper-pagination',
                clickable: true,
                dynamicBullets: true,
            },
            
            on: {
                init: function() {
                    animateThumbnailContent(this.slides[this.activeIndex]);
                },
                slideChangeTransitionEnd: function() {
                    animateThumbnailContent(this.slides[this.activeIndex]);
                }
            }
        });
        }
    }
    
    function animateThumbnailContent(slide) {
        const content = slide.querySelector('.thumbnail-content');
        if (content) {
            // リセット
            content.style.opacity = '0';
            content.style.transform = 'translateY(20px)';
            
            // アニメーション
            setTimeout(() => {
                content.style.transition = 'all 0.5s ease';
                content.style.opacity = '1';
                content.style.transform = 'translateY(0)';
            }, 100);
        }
    }
    
    // メインヒーロー画像のパララックス効果（デスクトップのみ）
    if (!isMobile) {
        const heroMain = document.querySelector('.hero-main-image');
        let ticking = false;
        
        function updateParallax() {
            const scrolled = window.pageYOffset;
            const parallaxSpeed = 0.3;
            
            if (heroMain) {
                heroMain.style.transform = `translateY(${scrolled * parallaxSpeed}px)`;
            }
            
            ticking = false;
        }
        
        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(updateParallax);
                ticking = true;
            }
        });
    }
    
    // サムネイル画像のホバーエフェクト（デスクトップ）
    if (!isMobile) {
        const thumbnailItems = document.querySelectorAll('.thumbnails-grid .thumbnail-item');
        
        thumbnailItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                const content = this.querySelector('.thumbnail-content');
                if (content) {
                    content.style.opacity = '1';
                    content.style.transform = 'translateY(0)';
                }
            });
            
            item.addEventListener('mouseleave', function() {
                const content = this.querySelector('.thumbnail-content');
                if (content) {
                    content.style.opacity = '0';
                    content.style.transform = 'translateY(20px)';
                }
            });
        });
    }
    
    // モバイルでのビューポート高さ対応
    function setViewportHeight() {
        const vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
    }
    
    setViewportHeight();
    window.addEventListener('resize', setViewportHeight);
    window.addEventListener('orientationchange', setViewportHeight);
    
    // タッチデバイスでのスワイプヒント表示（サムネイルスライダー用）
    if (isTouch && isMobile && thumbnailSlider) {
        let hintShown = localStorage.getItem('thumbnailSwipeHintShown');
        
        if (!hintShown) {
            const showSwipeHint = () => {
                const hint = document.createElement('div');
                hint.className = 'swipe-hint';
                hint.innerHTML = '<span>← スワイプで他の画像を見る →</span>';
                hint.style.cssText = `
                    position: absolute;
                    bottom: 20px;
                    left: 50%;
                    transform: translateX(-50%);
                    background: rgba(0,0,0,0.8);
                    color: white;
                    padding: 10px 25px;
                    border-radius: 25px;
                    font-size: 14px;
                    z-index: 100;
                    animation: fadeInOut 4s ease-in-out;
                    pointer-events: none;
                `;
                
                thumbnailSlider.appendChild(hint);
                setTimeout(() => {
                    hint.remove();
                    localStorage.setItem('thumbnailSwipeHintShown', 'true');
                }, 4000);
            };
            
            // 2秒後にヒント表示
            setTimeout(showSwipeHint, 2000);
        }
    }
    
    // スワイプヒントのアニメーション
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeInOut {
            0% {
                opacity: 0;
                transform: translateX(-50%) translateY(10px);
            }
            20%, 80% {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
            100% {
                opacity: 0;
                transform: translateX(-50%) translateY(10px);
            }
        }
    `;
    document.head.appendChild(style);
    
});