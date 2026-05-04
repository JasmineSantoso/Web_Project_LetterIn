<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LetterIn - Add Review</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/add_review.css">
</head>
<body>

    <?php include "layout/header_signed.html" ?>

    <main class="review-container">
        
        <div class="left-column">
            <div class="book-cover-wrapper">
                <img src="../IMG/image11.jpg" alt="Laut Bercerita" class="book-img">
            </div>
        </div>

        <div class="right-column">
            <h1 class="book-title">Laut Bercerita</h1>
            <h2 class="book-author">Leila S. Chudori</h2>

            <div class="star-rating-input">
                <i class="fa-regular fa-star"></i>
                <i class="fa-regular fa-star"></i>
                <i class="fa-regular fa-star"></i>
                <i class="fa-regular fa-star"></i>
                <i class="fa-regular fa-star"></i>
            </div>

            <textarea class="review-textarea" placeholder="Write your review here"></textarea>

            <div class="song-section">
                <h3 class="section-label">Add Related Song</h3>
                
                <div class="song-input-box">
    <input type="text" id="songInput" placeholder="Search song...">
                </div>

                <div class="song-tags">

    <div class="song-tag">
        <img src="../IMG/cover2.jpg" alt="Cover">
        <span>Daylight - Harry Style</span>
        <i class="fa-solid fa-xmark remove-song"></i>
    </div>

    <div class="song-tag">
        <img src="../IMG/cover4.jpg" alt="Cover">
        <span>Love Notes - Olivia D.</span>
        <i class="fa-solid fa-xmark remove-song"></i>
    </div>

    <div class="song-tag">
        <img src="../IMG/cover3.jpg" alt="Cover">
        <span>Dear Reader - Taylor S.</span>
        <i class="fa-solid fa-xmark remove-song"></i>
    </div>

</div>

            <div class="action-buttons">
                <div class="bookshelf-wrapper">

    <button class="btn-bookshelf" id="bookshelfBtn">
        Add Bookshelf
        <i class="fa-solid fa-chevron-down"></i>
    </button>

    <div class="bookshelf-dropdown" id="bookshelfDropdown">

        <div class="dropdown-item active">
            <span>To Read</span>
            <i class="fa-solid fa-chevron-down"></i>
        </div>

        <div class="dropdown-item">
            <span>Add Favorite</span>
            <i class="fa-regular fa-heart"></i>
        </div>

        <div class="dropdown-item">
            <span>Add Bookshelf</span>
            <i class="fa-solid fa-chevron-down"></i>
        </div>

    </div>

</div>
                <button class="btn-send">SEND</button>
            </div>

        </div>
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
            <a href="#"><i class="fa-brands fa-square-twitter"></i></a> 
            <!-- <a href="#"><i class="fa-brands fa-square-threads" style="color: #000000;"></i></a>  -->
            <a href="#"><i class="fa-brands fa-tiktok"></i></a>
            <a href="#"><i class="fa-brands fa-instagram"></i></a>
        </div>
    </footer>
    <script src="../JS/home_signed.js"></script>
    <script src="../JS/add_review.js"></script>
</body>
</html>