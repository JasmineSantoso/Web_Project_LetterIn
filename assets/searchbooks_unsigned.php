<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LetterIn - Search Result</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/searchbooks_unsigned.css">
</head>
<body>

    <header>
        <a href="#" class="nav-btn">About Us</a>
        <div class="logo-container">
            <img src="../IMG/logo1.png" alt="LetterIn Logo" class="logo-img">
        </div>
        <a href="signin.html" class="nav-btn">Sign in</a>
    </header>

    <section class="search-header">
        <h1 class="page-title">Every read leaves a letter in</h1>
        <div class="search-container">
            <input type="text" value="Hujan"> <i class="fa-solid fa-magnifying-glass search-icon"></i>
        </div>
    </section>


<section class="filter-bar">
    <div class="filter-container">

        <div class="filter-btn open">
            <div class="btn-header">
                <span class="filter-text">Genre</span>
                <i class="fa-solid fa-chevron-up"></i>
            </div>
            <div class="dropdown-content">
                <label><input type="checkbox"> Romance</label>
                <label><input type="checkbox"> Crime</label>
                <label><input type="checkbox"> Sci-Fi</label>
                <label><input type="checkbox"> Self-help</label>
            </div>
        </div>

        <div class="filter-btn open">
            <div class="btn-header">
                <span class="filter-text">Publish</span>
                <i class="fa-solid fa-chevron-up"></i>
            </div>
            <div class="dropdown-content publish-grid">
                <div class="input-group">
                    <label>From</label>
                    <input type="text">
                </div>
                <div class="input-group">
                    <label>To</label>
                    <input type="text">
                </div>
            </div>
        </div>

        <div class="filter-btn open">
            <div class="btn-header">
                <span class="filter-text">Rating</span>
                <i class="fa-solid fa-chevron-up"></i>
            </div>
            <div class="dropdown-content rating-grid">
                <button class="rate-btn">★ 5</button>
                <button class="rate-btn">≥ ★ 4</button>
                <button class="rate-btn">≥ ★ 3</button>
                <button class="rate-btn active">≥ ★ 2</button>
                <button class="rate-btn">≥ ★ 1</button>
            </div>
        </div>

    </div>
</section>

    <section class="book-list-container">
        
        <div class="book-item">
            <img src="../IMG/hujan1.jpg" alt="Dan Hujan Pun Berhenti" class="book-cover">
            <div class="book-details">
                <h2 class="book-title">Dan Hujan Pun Berhenti</h2>
                <p class="book-author">Farida Susanty</p>
                <p class="book-year">2007</p>
                <div class="book-rating">
                    <div class="stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star-half-stroke"></i>
                    </div>
                    <span class="rating-text">3.11 rating</span>
                </div>
            </div>
        </div>

        <div class="book-item">
            <img src="../IMG/hujan2.jpg" alt="Hujan Kepagian" class="book-cover">
            <div class="book-details">
                <h2 class="book-title">Hujan Kepagian</h2>
                <p class="book-author">Nugroho Notosusanto</p>
                <p class="book-year">1958</p>
                <div class="book-rating">
                    <div class="stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-regular fa-star"></i> </div>
                    <span class="rating-text">3.86 rating</span>
                </div>
            </div>
        </div>

        <div class="book-item">
            <img src="../IMG/hujan3.jpg" alt="Episode Hujan" class="book-cover">
            <div class="book-details">
                <h2 class="book-title">Episode Hujan</h2>
                <p class="book-author">Lucia Priandarini</p>
                <p class="book-year">2016</p>
                <div class="book-rating">
                    <div class="stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star-half-stroke"></i>
                        <i class="fa-regular fa-star"></i>
                    </div>
                    <span class="rating-text">3.55 rating</span>
                </div>
            </div>
        </div>

        <div class="book-item">
            <img src="../IMG/hujan4.jpg" alt="Wait For The Rain" class="book-cover">
            <div class="book-details">
                <h2 class="book-title">Wait For The Rain</h2>
                <p class="book-author">Maria Murnane</p>
                <p class="book-year">2015</p>
                <div class="book-rating">
                    <div class="stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-regular fa-star"></i>
                    </div>
                    <span class="rating-text">3.77 rating</span>
                </div>
            </div>
        </div>

        <div class="book-item">
            <img src="../IMG/image2.jpg" alt="Hujan" class="book-cover">
            <div class="book-details">
                <h2 class="book-title">Hujan</h2>
                <p class="book-author">Rien, Thee&amp;Rien</p>
                <p class="book-year">2008</p>
                <div class="book-rating">
                    <div class="stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <span class="rating-text">5.0 rating</span>
                </div>
            </div>
        </div>

        <div class="book-item">
            <a href="detail_unsigned.html">
                <img src="../IMG/image10.jpg" alt="Hujan Tere Liye" class="book-cover">
            </a>
            <!-- <img src="/IMG/image10.jpg" alt="Hujan Tere Liye" class="book-cover"> -->
            <div class="book-details">
                <h2 class="book-title">Hujan</h2>
                <p class="book-author">Tere Liye</p>
                <p class="book-year">2016</p>
                <div class="book-rating">
                    <div class="stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star-half-stroke"></i>
                    </div>
                    <span class="rating-text">4.22 rating</span>
                </div>
            </div>
        </div>

    </section>

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

</body>
</html>