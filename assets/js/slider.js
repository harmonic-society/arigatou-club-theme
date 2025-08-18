document.addEventListener('DOMContentLoaded', function() {
    const heroSlider = new Swiper('.hero-slider', {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
        speed: 1000,
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
    
    // パララックス効果
    const slides = document.querySelectorAll('.swiper-slide');
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const parallaxSpeed = 0.5;
        
        slides.forEach(slide => {
            const image = slide.querySelector('.slide-image');
            if (image) {
                image.style.transform = `translateY(${scrolled * parallaxSpeed}px)`;
            }
        });
    });
});