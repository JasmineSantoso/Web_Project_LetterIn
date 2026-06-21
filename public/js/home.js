// Carousel Scrolling Logic
document.querySelectorAll('.carousel-container').forEach(container => {
    const carousel = container.querySelector('.books-carousel');
    const prevBtn = container.querySelector('.prev-arrow');
    const nextBtn = container.querySelector('.next-arrow');

    if (carousel && prevBtn && nextBtn) {
        prevBtn.addEventListener('click', () => {
            carousel.scrollBy({ left: -300, behavior: 'smooth' });
        });

        nextBtn.addEventListener('click', () => {
            carousel.scrollBy({ left: 300, behavior: 'smooth' });
        });
    }
});