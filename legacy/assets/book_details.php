<?php
$bookId = isset($_GET['id']) ? $_GET['id'] : 'zyTCAlFPjgJC'; // Default fallback ID
$apiKey = 'AIzaSyCrzDO1SZu-fiZMIjxJ9h6um9Ki_-VwA2s'; // API Key from .env
$url = "https://www.googleapis.com/books/v1/volumes/{$bookId}?key={$apiKey}";

// Suppress errors if API quota is exceeded or offline
$response = @file_get_contents($url);
$bookData = $response ? json_decode($response, true) : null;

$title = $bookData['volumeInfo']['title'] ?? 'Hujan (Dummy - API Quota Exceeded)';
$authors = isset($bookData['volumeInfo']['authors']) ? implode(', ', $bookData['volumeInfo']['authors']) : 'Tere Liye';
$publisher = $bookData['volumeInfo']['publisher'] ?? 'Gramedia Pustaka Utama';
$publishedDate = $bookData['volumeInfo']['publishedDate'] ?? '16 April 2018';
$description = $bookData['volumeInfo']['description'] ?? 'Tentang persahabatan... Tentang cinta... Tentang melupakan... Tentang perpisahan... Dan tentang hujan...';
$pageCount = $bookData['volumeInfo']['pageCount'] ?? 318;
$averageRating = $bookData['volumeInfo']['averageRating'] ?? 4.22;
$ratingsCount = $bookData['volumeInfo']['ratingsCount'] ?? 667;
$categories = isset($bookData['volumeInfo']['categories']) ? implode(', ', $bookData['volumeInfo']['categories']) : 'Fiction, Romance';
$language = strtoupper($bookData['volumeInfo']['language'] ?? 'id');
$thumbnail = $bookData['volumeInfo']['imageLinks']['thumbnail'] ?? '../IMG/image10.jpg';

$isbn = '9786020324784';
if (isset($bookData['volumeInfo']['industryIdentifiers'])) {
    foreach ($bookData['volumeInfo']['industryIdentifiers'] as $identifier) {
        if (in_array($identifier['type'], ['ISBN_13', 'ISBN_10'])) {
            $isbn = $identifier['identifier'];
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> | LetterIn</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/book_details.css">
</head>
<body>

    <?php include "layout/header_signed.html" ?>

    <main class="detail-container">
        
        <aside class="left-sidebar">
            <div class="cover-wrapper">
                <img src="<?= htmlspecialchars($thumbnail) ?>" alt="<?= htmlspecialchars($title) ?>" class="book-cover">
            </div>
            
            <div class="sidebar-rating">
                <div class="stars">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star-half-stroke"></i>
                </div>
                <div class="rating-number"><?= number_format($averageRating, 2) ?></div>
                <div class="rating-text">based on <?= number_format($ratingsCount) ?> reviews</div>
            </div>

            <button class="btn-add-review">
                <div class="btn-stars">
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                </div>
                <span>Add Review</span>
            </button>

            <!--<details>
                <summary>
                    <span>To Read</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </summary>

                <div class="dropdown-menu">
                    <div class="dropdown-option">To Read</div>
                    <div class="dropdown-option">Currently Read</div>
                    <div class="dropdown-option">Done Read</div>
                </div>
            </details>
        </div>
            <label class="action-item favorite-btn">
            <input type="checkbox" class="fav-toggle">

            <span class="fav-text add">Add Favorite</span>
            <span class="fav-text remove">Remove Favorite</span>

            <i class="fa-regular fa-heart"></i>
            <i class="fa-solid fa-heart"></i>
        </label>
                        <div class="action-item">
                            <span>Add Bookshelf</span> <i class="fa-regular fa-square-check"></i>
                        </div>
                    </div>
                </div>-->

            <div class="action-menu-box">
                <div class="action-item dropdown-item">
                    <span>To Read</span> <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="action-item">
                    <span>Add Favorite</span> <i class="fa-regular fa-heart"></i>
                </div>
                <div class="action-item">
                    <span>Add Bookshelf</span> <i class="fa-regular fa-square-check"></i>
                </div>
            </div>
        </aside>

        <div class="right-content">
            <h1 class="book-title"><?= htmlspecialchars($title) ?></h1>
            <h2 class="book-author"><?= htmlspecialchars($authors) ?></h2>

            <div class="book-metadata">
                <p><strong>Genre:</strong> <?= htmlspecialchars($categories) ?></p>
                <p><strong>ISBN/UID:</strong> <?= htmlspecialchars($isbn) ?></p>
                <p><strong>Format:</strong> Paperback</p>
                <p><strong>Language:</strong> <?= htmlspecialchars($language) ?></p>
                <p><strong>Publisher:</strong> <?= htmlspecialchars($publisher) ?></p>
                <p><strong>Edition Publish Date:</strong> <?= htmlspecialchars($publishedDate) ?></p>
                <p><strong>Page:</strong> <?= htmlspecialchars($pageCount) ?></p>
            </div>

            <section class="content-box synopsis-box">
                <h3 class="box-title">Synopsis</h3>
                <p class="synopsis-text">
                    <?= $description ?>
                </p>
            </section>

            <section class="content-box review-section">
                <h3 class="box-title">Review</h3>
                
                <div class="review-list">
                    <div class="review-item">
                        <div class="user-avatar"><i class="fa-regular fa-circle-user"></i></div>
                        <div class="review-body">
                            <div class="review-meta">
                                <span class="username">Review by <strong>saskia</strong></span>
                                <span class="date">17 Januari 2024</span>
                            </div>
                            <div class="review-stars">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            </div>
                            <p class="review-text">Just read a few pages and this book... It's an amazing book. Sangat enggak mau berhenti bacanya. Tentang Persahabatan...</p>
                            <div class="review-actions">
                                <span><i class="fa-regular fa-thumbs-up"></i> 102 Likes</span>
                                <span><i class="fa-regular fa-comment"></i> 5 Comments</span>
                            </div>
                        </div>
                    </div>

                    <div class="review-item">
                        <div class="user-avatar"><i class="fa-regular fa-circle-user"></i></div>
                        <div class="review-body">
                            <div class="review-meta">
                                <span class="username">Review by <strong>anindya</strong></span>
                                <span class="date">17 Januari 2024</span>
                            </div>
                            <div class="review-stars">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i>
                            </div>
                            <p class="review-text">Bagus banget bukunya! Recommended.</p>
                            <div class="review-actions">
                                <span><i class="fa-regular fa-thumbs-up"></i> 50 Likes</span>
                                <span><i class="fa-regular fa-comment"></i> 1 Comment</span>
                            </div>
                        </div>
                    </div>

                    <div class="review-item">
                        <div class="user-avatar"><i class="fa-regular fa-circle-user"></i></div>
                        <div class="review-body">
                            <div class="review-meta">
                                <span class="username">Review by <strong>budi</strong></span>
                                <span class="date">16 Januari 2024</span>
                            </div>
                            <div class="review-stars">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            </div>
                            <p class="review-text">Novel Tere Liye tidak pernah mengecewakan.</p>
                            <div class="review-actions">
                                <span><i class="fa-regular fa-thumbs-up"></i> Like</span>
                                <span><i class="fa-regular fa-comment"></i> Comment</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="more-reviews">
                    <a href="#">More reviews and ratings <i class="fa-solid fa-chevron-right"></i></a>
                </div>
            </section>
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
            <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
            <a href="#"><i class="fa-brands fa-threads"></i></a>
            <a href="#"><i class="fa-brands fa-tiktok"></i></a>
            <a href="#"><i class="fa-brands fa-instagram"></i></a>
        </div>
    </footer>
    <script src="../JS/home_signed.js"></script>
</body>
</html>