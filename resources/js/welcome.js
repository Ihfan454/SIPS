/**
 * Welcome Page JavaScript
 * Sistem Informasi Pelanggaran Siswa SMP Negeri 7 Jember
 */

// ========================================
// COUNTER ANIMATION CLASS
// ========================================
class CounterAnimation {
    constructor() {
        this.counters = document.querySelectorAll('.stat-card');
        this.animated = false;
        this.init();
    }

    init() {
        if (!this.counters.length) return;
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !this.animated) {
                    this.startCounters();
                    this.animated = true;
                    observer.disconnect();
                }
            });
        }, { threshold: 0.3 });

        const statsSection = document.querySelector('#statistics');
        if (statsSection) observer.observe(statsSection);
    }

    startCounters() {
        this.counters.forEach(card => {
            const target = parseInt(card.getAttribute('data-target'));
            if (isNaN(target)) return;
            
            const numberElement = card.querySelector('.stat-number');
            if (!numberElement) return;
            
            let current = 0;
            const increment = target / 50;
            const stepTime = 1500 / 50;
            
            const updateCounter = () => {
                current += increment;
                if (current < target) {
                    numberElement.textContent = Math.ceil(current);
                    setTimeout(updateCounter, stepTime);
                } else {
                    numberElement.textContent = target;
                }
            };
            
            updateCounter();
        });
    }
}

// ========================================
// HERO SLIDER CLASS
// ========================================
class HeroSlider {
    constructor() {
        this.slides = document.querySelectorAll('.hero-slide');
        this.dots = document.querySelectorAll('.slider-dot');
        this.prevBtn = document.querySelector('.slider-nav.prev');
        this.nextBtn = document.querySelector('.slider-nav.next');
        this.currentSlide = 0;
        this.totalSlides = this.slides.length;
        this.autoPlayInterval = null;
        this.autoPlayDelay = 5000;
        this.init();
    }

    init() {
        if (!this.slides.length) return;
        
        this.showSlide(this.currentSlide);
        
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => this.prevSlide());
        }
        
        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => this.nextSlide());
        }
        
        this.dots.forEach((dot, index) => {
            dot.addEventListener('click', () => this.goToSlide(index));
        });
        
        this.startAutoPlay();
        
        const sliderContainer = document.querySelector('.hero-slider');
        if (sliderContainer) {
            sliderContainer.addEventListener('mouseenter', () => this.stopAutoPlay());
            sliderContainer.addEventListener('mouseleave', () => this.startAutoPlay());
        }
        
        this.addTouchSupport();
    }

    showSlide(index) {
        this.slides.forEach(slide => slide.classList.remove('active'));
        this.dots.forEach(dot => dot.classList.remove('active'));
        
        this.slides[index].classList.add('active');
        if (this.dots[index]) {
            this.dots[index].classList.add('active');
        }
        
        this.resetProgress();
    }

    nextSlide() {
        this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
        this.showSlide(this.currentSlide);
        this.resetAutoPlay();
    }

    prevSlide() {
        this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
        this.showSlide(this.currentSlide);
        this.resetAutoPlay();
    }

    goToSlide(index) {
        this.currentSlide = index;
        this.showSlide(this.currentSlide);
        this.resetAutoPlay();
    }

    startAutoPlay() {
        if (this.autoPlayInterval) return;
        this.autoPlayInterval = setInterval(() => this.nextSlide(), this.autoPlayDelay);
    }

    stopAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
            this.autoPlayInterval = null;
        }
    }

    resetAutoPlay() {
        this.stopAutoPlay();
        this.startAutoPlay();
    }

    resetProgress() {
        document.querySelectorAll('.slider-progress').forEach(progress => progress.remove());
        
        const activeSlide = document.querySelector('.hero-slide.active');
        if (activeSlide) {
            const progress = document.createElement('div');
            progress.className = 'slider-progress';
            activeSlide.appendChild(progress);
            
            setTimeout(() => {
                if (progress && progress.parentNode) {
                    progress.remove();
                }
            }, this.autoPlayDelay);
        }
    }

    addTouchSupport() {
        const sliderContainer = document.querySelector('.hero-slider');
        if (!sliderContainer) return;
        
        let touchStartX = 0;
        
        sliderContainer.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
            this.stopAutoPlay();
        });
        
        sliderContainer.addEventListener('touchend', (e) => {
            const touchEndX = e.changedTouches[0].screenX;
            const diff = touchEndX - touchStartX;
            
            if (Math.abs(diff) > 50) {
                if (diff > 0) {
                    this.prevSlide();
                } else {
                    this.nextSlide();
                }
            }
            
            this.startAutoPlay();
        });
    }
}

// ========================================
// SCROLL REVEAL CLASS
// ========================================
class ScrollReveal {
    constructor() {
        this.elements = [
            ...document.querySelectorAll('.stat-card'),
            ...document.querySelectorAll('.violation-card'),
            ...document.querySelectorAll('.rules-grid > div:first-child'),
            ...document.querySelectorAll('.rules-quote'),
            ...document.querySelectorAll('.cta-section')
        ];
        this.init();
    }

    init() {
        if (!this.elements.length) return;
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2, rootMargin: '0px 0px -50px 0px' });
        
        this.elements.forEach(el => observer.observe(el));
    }
}

// ========================================
// SMOOTH SCROLL CLASS
// ========================================
class SmoothScroll {
    constructor() {
        this.init();
    }

    init() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                const targetId = anchor.getAttribute('href');
                if (targetId === '#') return;
                
                const target = document.querySelector(targetId);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }
}

// ========================================
// NAVBAR EFFECT CLASS
// ========================================
class NavbarEffect {
    constructor() {
        this.header = document.getElementById('mainHeader');
        this.init();
    }

    init() {
        if (!this.header) return;
        
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                this.header.classList.add('header-scrolled');
            } else {
                this.header.classList.remove('header-scrolled');
            }
        });
    }
}

// ========================================
// DARK MODE DETECTOR (Optional)
// ========================================
class DarkModeDetector {
    constructor() {
        this.init();
    }

    init() {
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        console.log(`🌓 Dark mode: ${prefersDark ? 'active' : 'inactive'}`);
        
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            console.log(`🌓 Dark mode changed: ${e.matches ? 'active' : 'inactive'}`);
        });
    }
}

// ========================================
// INITIALIZE ALL FEATURES
// ========================================
document.addEventListener('DOMContentLoaded', () => {
    new CounterAnimation();
    new HeroSlider();
    new ScrollReveal();
    new SmoothScroll();
    new NavbarEffect();
    new DarkModeDetector();
    
    console.log('✨ Sistem Informasi Pelanggaran Siswa SMP Negeri 7 Jember siap digunakan!');
});

// ========================================
// EXPORT FOR MODULE USE (if needed)
// ========================================
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        CounterAnimation,
        HeroSlider,
        ScrollReveal,
        SmoothScroll,
        NavbarEffect,
        DarkModeDetector
    };
}