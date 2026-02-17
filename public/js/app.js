document.addEventListener('DOMContentLoaded', function () {
    // Mobile Menu Toggle
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mainNav = document.getElementById('mainNav');
    const mobileNavOverlay = document.getElementById('mobileNavOverlay');

    if (mobileMenuToggle && mainNav) {
        mobileMenuToggle.addEventListener('click', function() {
            mobileMenuToggle.classList.toggle('active');
            mainNav.classList.toggle('active');
            mobileNavOverlay?.classList.toggle('active');
            document.body.style.overflow = mainNav.classList.contains('active') ? 'hidden' : '';
        });

        // Close menu when clicking overlay
        mobileNavOverlay?.addEventListener('click', function() {
            mobileMenuToggle.classList.remove('active');
            mainNav.classList.remove('active');
            mobileNavOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });

        // Close menu when clicking a link
        mainNav.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 900) {
                    mobileMenuToggle.classList.remove('active');
                    mainNav.classList.remove('active');
                    mobileNavOverlay?.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        });
    }

    // Hero Slider
    document.querySelectorAll('.hero-slider').forEach(function (hero) {
        const track = hero.querySelector('.hero-track');
        const slides = hero.querySelectorAll('.hero-slide');
        const prev = hero.querySelector('.hero-prev');
        const next = hero.querySelector('.hero-next');
        if (!track || slides.length === 0) return;

        let current = 0;
        const goTo = (index) => {
            if (index < 0) index = slides.length - 1;
            if (index >= slides.length) index = 0;
            current = index;
            const left = slides[index].offsetLeft;
            track.scrollTo({ left: left, behavior: 'smooth' });
        };

        next?.addEventListener('click', function () { goTo(current + 1); });
        prev?.addEventListener('click', function () { goTo(current - 1); });

        let autoplay = setInterval(() => goTo(current + 1), 3000);

        over / pointerdown
        hero.addEventListener('pointerenter', () => clearInterval(autoplay));
        hero.addEventListener('pointerleave', () => { autoplay = setInterval(() => goTo(current + 1), 6000); });
        track.addEventListener('pointerdown', () => clearInterval(autoplay));
    });

    document.querySelectorAll('.js-slider').forEach(function (slider) {
        const track = slider.querySelector('.slider-track');
        const prev = slider.querySelector('.slider-prev');
        const next = slider.querySelector('.slider-next');

        const getCardWidth = () => {
            const el = slider.querySelector('.slide');
            if (!el) return 300;
            const style = window.getComputedStyle(el);
            const marginRight = parseFloat(style.marginRight) || 0;
            return Math.round(el.offsetWidth + marginRight);
        };

        if (!track) return;

        next?.addEventListener('click', function () {
            track.scrollBy({ left: getCardWidth(), behavior: 'smooth' });
        });
        prev?.addEventListener('click', function () {
            track.scrollBy({ left: -getCardWidth(), behavior: 'smooth' });
        });

        let isDown = false, startX, scrollLeft;
        track.addEventListener('pointerdown', (e) => {
            isDown = true;
            track.classList.add('active');
            startX = e.pageX - track.offsetLeft;
            scrollLeft = track.scrollLeft;
            track.setPointerCapture(e.pointerId);
        });
        track.addEventListener('pointerup', (e) => {
            isDown = false;
            track.classList.remove('active');
            track.releasePointerCapture(e.pointerId);
        });
        track.addEventListener('pointermove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - track.offsetLeft;
            const walk = (x - startX) * 1;
            track.scrollLeft = scrollLeft - walk;
        });
    });
});
