document.addEventListener('DOMContentLoaded', () => {

    // ELEMENT
    const el = {
        avatar: document.getElementById('user-avatar'),
        name: document.getElementById('user-name-display'),
        handle: document.getElementById('user-handle'),
        bio: document.getElementById('user-bio'),
        following: document.getElementById('user-following'),
        followers: document.getElementById('user-followers'),
        totalBooks: document.getElementById('total-books-count'),
        totalReviews: document.getElementById('total-reviews-count'),
        books: document.getElementById('favorite-books-container')
    };

    // 🔵 RENDER STATS (Simulated for now)
    if (el.totalBooks) el.totalBooks.textContent = '0';
    if (el.totalReviews) el.totalReviews.textContent = '0';



// --- 1. DATA SIMULASI DATABASE ---
    const dynamicUserData = {
        readedBooks: [
            "../IMG/image1.jpg", "../IMG/image2.jpg", "../IMG/image3.jpg",
            "../IMG/image4.jpg", "../IMG/image5.jpg", "../IMG/image6.jpg"
        ],
        currentlyReading: {
            cover: "../IMG/image11.jpg",
            title: "Laut Bercerita",
            author: "Leila S. Chudori",
            progress: 35,
            startDate: "08-01-2026",
            rating: 0 // Rating awal
        },
        currentlyReviews: [
            {
                cover: "../IMG/image11.jpg",
                title: "Laut Bercerita",
                author: "Leila S. Chudori",
                rating: 5,
                text: "It's just gives us... the vibes is incredible..."
            },
            {
                cover: "../IMG/image11.jpg",
                title: "Laut Bercerita",
                author: "Leila S. Chudori",
                rating: 4,
                text: "The plot grows on you... amazing book."
            }
        ],
        bookShelfs: [
            { cover: "../IMG/image6.jpg", name: "Fantasy" },
            { cover: "../IMG/image11.jpg", name: "FicHisto" },
            { cover: "../IMG/image2.jpg", name: "Shelf 3" }
        ],
        readingLists: [
            "../IMG/image7.jpg", "../IMG/image8.jpg", "../IMG/image9.jpg",
            "../IMG/image10.jpg", "../IMG/image11.jpg", "../IMG/hujan1.jpg"
        ]
    };

    // --- 2. TANGKAP SEMUA CONTAINER HTML ---
    const readedContainer = document.getElementById('readed-books-container');
    const readingContainer = document.getElementById('currently-reading-container');
    const reviewContainer = document.getElementById('currently-review-container');
    const shelfsContainer = document.getElementById('book-shelfs-container');
    const listsContainer = document.getElementById('reading-lists-container');

    // --- 3. FUNGSI RENDER UNTUK MASING-MASING SECTION ---

    // Render Readed Books & Reading Lists (Logikanya sama dengan Favorite Books)
    function renderBookGrid(container, booksArray) {
        if (!container) return;
        container.innerHTML = ''; 
        booksArray.forEach(url => {
            const img = document.createElement('img');
            img.src = url;
            img.alt = "Cover Buku";
            container.appendChild(img);
        });
    }

    // Render Currently Reading (Lebih kompleks karena ada struktur HTML spesifik)
    function renderCurrentlyReading(data) {
        if (!readingContainer) return;
        
        readingContainer.innerHTML = `
            <div class="current-reading-card">
                <img src="${data.cover}" alt="${data.title}" class="current-cover">
                <div class="current-info">
                    <h3 class="current-title">${data.title}</h3>
                    <p class="current-author">${data.author}</p>
                    
                    <div class="progress-wrapper">
                        <span>Progress</span>
                        <div class="progress-bar"><div class="fill" style="width: 0%; transition: width 1s ease-in-out;"></div></div>
                        <span class="percent">${data.progress}%</span>
                    </div>

                    <p class="start-date">Start reading<br>${data.startDate}</p>

                    <button class="btn-stars-review dynamic-stars">
                        <i class="fa-regular fa-star"></i>
                        <i class="fa-regular fa-star"></i>
                        <i class="fa-regular fa-star"></i>
                        <i class="fa-regular fa-star"></i>
                        <i class="fa-regular fa-star"></i>
                    </button>
                </div>
            </div>
        `;

        // Animasi Progress Bar
        setTimeout(() => {
            const fillBar = readingContainer.querySelector('.fill');
            if (fillBar) fillBar.style.width = `${data.progress}%`;
        }, 300);

        // Aktifkan kembali fungsi klik bintang
        activateStarRating();
    }

    // Helper untuk membuat HTML Bintang Kuning di Review
    function getStarsHTML(rating) {
        let starsHTML = '';
        for (let i = 1; i <= 5; i++) {
            starsHTML += i <= rating ? '<i class="fa-solid fa-star" style="color: #FBC02D;"></i>' : '<i class="fa-regular fa-star"></i>';
        }
        return starsHTML;
    }

    // Render Currently Review
    function renderCurrentlyReview(reviewsArray) {
        if (!reviewContainer) return;
        reviewContainer.innerHTML = '';
        
        reviewsArray.forEach(review => {
            const reviewCard = `
                <div class="review-card-dark">
                    <img src="${review.cover}" alt="Cover">
                    <div class="review-content">
                        <h4>${review.title}</h4>
                        <p class="author">${review.author}</p>
                        <div class="stars">${getStarsHTML(review.rating)}</div>
                        <p class="desc">${review.text}</p>
                    </div>
                </div>
            `;
            reviewContainer.innerHTML += reviewCard;
        });
    }

    // Render Book Shelfs
    function renderBookShelfs(shelfsArray) {
        if (!shelfsContainer) return;
        shelfsContainer.innerHTML = '';
        
        shelfsArray.forEach(shelf => {
            const shelfItem = `
                <div class="shelf-item">
                    <img src="${shelf.cover}" alt="${shelf.name}"> <br>
                    <span>${shelf.name}</span>
                </div>
            `;
            shelfsContainer.innerHTML += shelfItem;
        });
    }

    // Fungsi interaksi bintang (didaur ulang karena HTML-nya dicetak ulang oleh JS)
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

    // --- 4. JALANKAN SEMUA FUNGSI ---
    renderBookGrid(readedContainer, dynamicUserData.readedBooks);
    renderBookGrid(listsContainer, dynamicUserData.readingLists);
    renderCurrentlyReading(dynamicUserData.currentlyReading);
    renderCurrentlyReview(dynamicUserData.currentlyReviews);
    renderBookShelfs(dynamicUserData.bookShelfs);

    const friendContainer = document.getElementById('friend-list-container');

    const friends = [
        "azalea",
        "kadiva",
        "samara",
        "jasmine"
    ];

    function renderFriends(friendArray) {
        if (!friendContainer) return;

        friendContainer.innerHTML = '';

        friendArray.forEach(friend => {
            const div = document.createElement('div');
            div.className = 'friend-circle';

            div.innerHTML = `
                <div class="avatar-placeholder">
                    <i class="fa-solid fa-user"></i>
                </div>
                <span>${friend}</span>
            `;

            friendContainer.appendChild(div);
        });

        const next = document.createElement('div');
        next.className = 'next-icon';
        next.innerHTML = `<i class="fa-regular fa-circle-right"></i>`;
        friendContainer.appendChild(next);
    }

    renderFriends(friends);
});

function addBookToFavorites(bookUrl) {
    const stored = localStorage.getItem('currentUser');
    if (!stored) return;

    const user = JSON.parse(stored);

    if (!user.favoriteBooks.includes(bookUrl)) {
        user.favoriteBooks.push(bookUrl);
        user.totalBooks = user.favoriteBooks.length;

        localStorage.setItem('currentUser', JSON.stringify(user));

        alert("Buku ditambahkan!");
        location.reload(); // refresh biar langsung update

    }
}