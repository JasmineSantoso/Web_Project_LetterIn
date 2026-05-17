<?php
$bookId = isset($_GET['id']) ? $_GET['id'] : 'zyTCAlFPjgJC'; // Default fallback ID
$url = "https://www.googleapis.com/books/v1/volumes/{$bookId}";

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Details | LetterIn</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="../CSS/detail_unsigned.css">
</head>
<body>

<!-- ================= HEADER ================= -->
<header>
        <a href="#" class="nav-btn">About Us</a>
        <div class="logo-container">
            <img src="../IMG/logo1.png" alt="LetterIn Logo" class="logo-img">
        </div>
        <a href="signin.html" class="nav-btn">Sign in</a>
    </header>

<!-- ================= MAIN CONTENT ================= -->
<main class="book-page">

    <section class="book-container">

        <!-- LEFT : BOOK COVER -->
        <div class="book-cover">
            <img src="<?= htmlspecialchars($thumbnail) ?>" alt="<?= htmlspecialchars($title) ?>">
            <div class="rating">
                ★★★★★ <span><?= number_format($averageRating, 2) ?></span>
                <p>based on <?= number_format($ratingsCount) ?> reviews</p>
            </div>
        </div>

        <!-- RIGHT : BOOK INFO -->
        <div class="book-info">
            <h1><?= htmlspecialchars($title) ?></h1>
            <h3><?= htmlspecialchars($authors) ?></h3>

            <ul class="book-meta">
                <li><b>Genre:</b> <?= htmlspecialchars($categories) ?></li>
                <li><b>ISBN/UID:</b> <?= htmlspecialchars($isbn) ?></li>
                <li><b>Format:</b> Paperback</li>
                <li><b>Language:</b> <?= htmlspecialchars($language) ?></li>
                <li><b>Publisher:</b> <?= htmlspecialchars($publisher) ?></li>
                <li><b>Edition Publish Date:</b> <?= htmlspecialchars($publishedDate) ?></li>
                <li><b>Page:</b> <?= htmlspecialchars($pageCount) ?></li>
            </ul>

            <!-- SYNOPSIS -->
            <div class="card">
                <h4>Synopsis</h4>
                <p>
                    <?= $description ?>
                </p>
            </div>

            <!-- REVIEWS -->
            <div class="card">
                <h4>Review</h4>

                <div class="review-item">
                    <div class="avatar"></div>
                    <div class="review-content">
                        <p class="review-text">
                            Buku ini sangat menyentuh dan penuh makna. Ceritanya
                            sederhana tapi emosinya kuat.
                        </p>
                        <span class="review-date">17 April 2018</span>
                    </div>
                </div>

                <div class="review-item">
                    <div class="avatar"></div>
                    <div class="review-content">
                        <p class="review-text">
                            Alur ceritanya rapi dan membuat pembaca ikut hanyut
                            dalam suasana hujan dan kenangan.
                        </p>
                        <span class="review-date">20 April 2018</span>
                    </div>
                </div>

                <p class="more-review">More reviews and ratings →</p>
            </div>
        </div>

    </section>

</main>

<!-- ================= FOOTER ================= -->
<footer>
    <div class="footer-links">
        <a href="#">Help</a>
        <a href="#">FAQ</a>
        <a href="#">Contact</a>
    </div>

    <div class="copyright">
        © 2026 LetterIn, Inc.
    </div>

    <div class="social-icons">
        <a href="#">X</a>
        <a href="#">IG</a>
    </div>
</footer>

</body>
</html>