<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LetterIn - User Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/profile.css">
    
</head>
<body>

    <script src="../JS/profile.js"></script>
    <?php include "layout/header_signed.html" ?>

    <main class="main-container">
        
        <section class="profile-header">
            <div class="profile-avatar">
                <img src="../IMG/IU.webp" alt="User Profile" id="user-avatar">
            </div>
            <div class="profile-details">
                <h1 class="profile-name">
                    <span id="user-name-display">Loading...</span> 
                    
                    <a href="settings.php" title="Edit Profile"><i class="fa-solid fa-pen source-icon"></i></a>
                </h1>
                <p class="profile-handle" id="user-handle">@loading...</p>
                <p class="profile-bio" id="user-bio">"Loading bio..."</p>

                <div class="profile-stats-text">
                    <span>Following <strong id="user-following">0</strong></span>
                    <span>Followers <strong id="user-followers">0</strong></span>
                </div>
            </div>
        </section>

        <!-- <section class="bordered-section">
            <h2 class="section-label">FAVORITE BOOKS</h2>
            <div class="books-grid">
                <img src="../IMG/image1.jpg" alt="Book">
                <img src="../IMG/image2.jpg" alt="Book">
                <img src="../IMG/image3.jpg" alt="Book">
                <img src="../IMG/image4.jpg" alt="Book">
                <img src="../IMG/image5.jpg" alt="Book">
                <img src="../IMG/image6.jpg" alt="Book">
                <img src="../IMG/image7.jpg" alt="Book">
                <img src="../IMG/image8.jpg" alt="Book">
                <img src="../IMG/image7.jpg" alt="Book">
            </div>
        </section> -->

        <section class="bordered-section">
            <h2 class="section-label">FAVORITE BOOKS</h2>
            <div class="books-grid" id="favorite-books-container"></div>
        </section>

        <section class="stats-bar">
            <div class="stat-item">
                <span class="stat-title">Total Book</span>
                <span class="stat-number" id="total-books-count">0</span>
            </div>
            <div class="stat-item">
                <span class="stat-title">Total Review</span>
                <span class="stat-number" id="total-reviews-count">0</span>
            </div>
        </section>

        <section class="bordered-section">
            <h2 class="section-label">READED BOOKS</h2>
            <div class="books-grid" id="readed-books-container">
                </div>
        </section>

        <section class="section-wrapper">
            <h2 class="plain-title">CURRENTLY READING</h2>
            <div id="currently-reading-container">
                </div>
        </section>

        <section class="section-wrapper">
            <h2 class="plain-title">CURRENTLY REVIEW</h2>
            <div class="review-scroll-container" id="currently-review-container">
                </div>
        </section>

        <section class="section-wrapper">
            <h2 class="plain-title">BOOK SHELFS</h2>
            <div class="shelf-list" id="book-shelfs-container">
                </div>
        </section>

        <section class="bordered-section">
            <h2 class="section-label">READING LISTS</h2>
            <div class="books-grid" id="reading-lists-container">
                </div>
        </section>

        <!-- <section class="section-wrapper">
            <h2 class="plain-title">FRIEND LISTS</h2>
            <div class="friend-list-row">
                <div class="friend-circle">
                    <div class="avatar-placeholder" ><i class="fa-solid fa-user"></i></div>
                    <span>azalea</span>
                </div>
                <div class="friend-circle">
                    <div class="avatar-placeholder"><i class="fa-solid fa-user"></i></div>
                    <span>kadiva</span>
                </div>
                <div class="friend-circle">
                    <div class="avatar-placeholder"><i class="fa-solid fa-user"></i></div>
                    <span>samara</span>
                </div>
                <div class="friend-circle">
                    <div class="avatar-placeholder"><i class="fa-solid fa-user"></i></div>
                    <span>jasmine</span>
                </div>
                <div class="friend-circle">
                    <div class="avatar-placeholder"><i class="fa-solid fa-user"></i></div>
                    <span>yayayaa</span>
                </div>
                <div class="friend-circle">
                    <div class="avatar-placeholder"><i class="fa-solid fa-user"></i></div>
                    <span>camellia</span>
                </div>
                <div class="friend-circle">
                    <div class="avatar-placeholder"><i class="fa-solid fa-user"></i></div>
                    <span>bellala</span>
                </div>
                 <div class="next-icon">
                    <i class="fa-regular fa-circle-right"></i>
                </div>
            </div>
        </section> -->

        <div class="friend-list-row" id="friend-list-container"></div>

    </main>

    <footer>
        <div class="footer-links">
            <a href="#">Help</a>
            <a href="#">FAQ</a>
            <a href="#">Contact</a>
        </div>
        <div class="copyright">
            <i class="fa-regular fa-copyright"></i> 2026 LetterIn, Inc.
        </div>
        <div class="social-icons">
            <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
            <a href="#"><i class="fa-brands fa-threads"></i></a>
            <a href="#"><i class="fa-brands fa-tiktok"></i></a>
            <a href="#"><i class="fa-brands fa-instagram"></i></a>
        </div>
    </footer>

</body>
</html>