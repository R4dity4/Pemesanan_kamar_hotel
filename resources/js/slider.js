document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.js-slider').forEach(function (slider) {
        const track = slider.querySelector('.slider-track');
        const prev = slider.querySelector('.slider-prev');
        const next = slider.querySelector('.slider-next');
        const cardWidth = slider.querySelector('.slide')?.offsetWidth || 300;

        if (!track) return;

        next?.addEventListener('click', function () {
            track.scrollBy({ left: cardWidth, behavior: 'smooth' });
        });
        prev?.addEventListener('click', function () {
            track.scrollBy({ left: -cardWidth, behavior: 'smooth' });
        });
    });
});
