document.addEventListener('DOMContentLoaded', function() {
    // モバイル判定
    const isMobile = window.innerWidth <= 768;
    const isTouch = 'ontouchstart' in window;
    
    const heroSlider = new Swiper('.hero-slider', {
        loop: true,
        autoplay: {
            delay: isMobile ? 4000 : 5000,
            disableOnInteraction: false,
            pauseOnMouseEnter: !isMobile,
        },
        effect: isMobile ? 'slide' : 'fade',
        fadeEffect: {
            crossFade: true
        },
        speed: isMobile ? 600 : 1000,
        
        // タッチ操作の設定
        touchRatio: 1.2,
        touchAngle: 45,
        grabCursor: true,
        touchEventsTarget: 'container',
        touchReleaseOnEdges: true,
        
        // モバイル用の追加設定
        slidesPerView: 1,
        spaceBetween: 0,
        centeredSlides: true,
        watchSlidesProgress: true,
        
        // レイジーロード設定
        lazy: {
            loadPrevNext: true,
            loadPrevNextAmount: 1,
        },
        
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            renderBullet: function (index, className) {
                return '<span class="' + className + '"><span class="progress-bar"></span></span>';
            }
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
            hideOnClick: isMobile,
        },
        
        // ブレークポイント設定
        breakpoints: {
            320: {
                autoplay: {
                    delay: 3500,
                },
            },
            480: {
                autoplay: {
                    delay: 4000,
                },
            },
            768: {
                autoplay: {
                    delay: 4500,
                },
            },
            1024: {
                autoplay: {
                    delay: 5000,
                },
            }
        },
        on: {
            init: function() {
                animateSlideContent(this.slides[this.activeIndex]);
            },
            slideChangeTransitionStart: function() {
                const activeSlide = this.slides[this.activeIndex];
                const allSlides = this.slides;
                
                allSlides.forEach(slide => {
                    const content = slide.querySelector('.slide-content');
                    if (content) {
                        content.classList.remove('animate');
                    }
                });
            },
            slideChangeTransitionEnd: function() {
                animateSlideContent(this.slides[this.activeIndex]);
            }
        }
    });
    
    function animateSlideContent(slide) {
        const content = slide.querySelector('.slide-content');
        if (content) {
            setTimeout(() => {
                content.classList.add('animate');
            }, 100);
        }
    }
    
    // パララックス効果（デスクトップのみ）
    if (!isMobile) {
        const slides = document.querySelectorAll('.swiper-slide');
        let ticking = false;
        
        function updateParallax() {
            const scrolled = window.pageYOffset;
            const parallaxSpeed = 0.5;
            
            slides.forEach(slide => {
                const image = slide.querySelector('.slide-image');
                if (image) {
                    image.style.transform = `translateY(${scrolled * parallaxSpeed}px)`;
                }
            });
            
            ticking = false;
        }
        
        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(updateParallax);
                ticking = true;
            }
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
    
    // タッチデバイスでのスワイプヒント表示
    if (isTouch && isMobile) {
        let hintShown = false;
        const showSwipeHint = () => {
            if (!hintShown && heroSlider) {
                const hint = document.createElement('div');
                hint.className = 'swipe-hint';
                hint.innerHTML = '<span>スワイプで切り替え</span>';
                hint.style.cssText = `
                    position: absolute;
                    bottom: 60px;
                    left: 50%;
                    transform: translateX(-50%);
                    background: rgba(0,0,0,0.7);
                    color: white;
                    padding: 8px 20px;
                    border-radius: 20px;
                    font-size: 14px;
                    z-index: 100;
                    animation: fadeInOut 3s ease-in-out;
                `;
                
                const sliderEl = document.querySelector('.hero-slider');
                if (sliderEl) {
                    sliderEl.appendChild(hint);
                    setTimeout(() => hint.remove(), 3000);
                    hintShown = true;
                }
            }
        };
        
        // 3秒後にヒント表示
        setTimeout(showSwipeHint, 3000);
    }
    
});