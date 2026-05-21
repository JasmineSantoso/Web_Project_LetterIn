document.addEventListener('DOMContentLoaded', () => {

    // =============================================
    // DRAG-TO-SCROLL for review & friend containers
    // =============================================
    const scrollables = document.querySelectorAll('.review-scroll-container, .friend-list-row');

    scrollables.forEach(el => {
        let isDown = false;
        let startX;
        let scrollLeft;

        el.addEventListener('mousedown', (e) => {
            isDown = true;
            el.classList.add('active');
            startX = e.pageX - el.offsetLeft;
            scrollLeft = el.scrollLeft;
        });

        el.addEventListener('mouseleave', () => {
            isDown = false;
            el.classList.remove('active');
        });

        el.addEventListener('mouseup', () => {
            isDown = false;
            el.classList.remove('active');
        });

        el.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - el.offsetLeft;
            const walk = (x - startX) * 2;
            el.scrollLeft = scrollLeft - walk;
        });
    });

    // =============================================
    // INTERACTIVE STAR RATING (.dynamic-stars)
    // Only activates if the element exists in DOM
    // =============================================
    function activateStarRating() {
        const container = document.querySelector('.dynamic-stars');
        if (!container) return;

        const stars = container.querySelectorAll('i');

        stars.forEach((star, index) => {
            star.addEventListener('click', () => {
                stars.forEach(s => {
                    s.classList.remove('fa-solid');
                    s.classList.add('fa-regular');
                    s.style.color = '';
                });

                for (let i = 0; i <= index; i++) {
                    stars[i].classList.remove('fa-regular');
                    stars[i].classList.add('fa-solid');
                    stars[i].style.color = '#FBC02D';
                }
            });
        });
    }

    activateStarRating();

});

// =============================================
// ADD BOOK TO FAVORITES (kept for compatibility)
// =============================================
function addBookToFavorites(bookUrl) {
    const stored = localStorage.getItem('currentUser');
    if (!stored) return;

    const user = JSON.parse(stored);

    if (!user.favoriteBooks.includes(bookUrl)) {
        user.favoriteBooks.push(bookUrl);
        user.totalBooks = user.favoriteBooks.length;

        localStorage.setItem('currentUser', JSON.stringify(user));

        alert("Buku ditambahkan!");
        location.reload();
    }
}