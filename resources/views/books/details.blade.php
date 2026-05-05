@extends('layouts.app')

@section('title', 'LetterIn - Book Detail')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/book_details.css') }}">
@endpush

@section('content')
    <main class="detail-container">
        
        <aside class="left-sidebar">
            <div class="cover-wrapper">
                <img src="{{ asset('images/image10.jpg') }}" alt="Hujan - Tere Liye" class="book-cover">
            </div>
            
            <div class="sidebar-rating">
                <div class="stars">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star-half-stroke"></i>
                </div>
                <div class="rating-number">4.22</div>
                <div class="rating-text">based on 667 reviews</div>
            </div>

            <a href="{{ route('book.review', ['id' => $id]) }}" class="btn-add-review" style="text-decoration: none; color: inherit; display: block;">
                <div class="btn-stars">
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                </div>
                <span>Add Review</span>
            </a>

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
            <h1 class="book-title">Hujan</h1>
            <h2 class="book-author">Tere Liye</h2>

            <div class="book-metadata">
                <p><strong>ISBN/UID:</strong> 9786020324784</p>
                <p><strong>Format:</strong> Paperback</p>
                <p><strong>Language:</strong> Indonesian</p>
                <p><strong>Publisher:</strong> Gramedia Pustaka Utama</p>
                <p><strong>Edition Publish Date:</strong> 16 April 2018</p>
                <p><strong>Page:</strong> 318</p>
            </div>

            <section class="content-box synopsis-box">
                <h3 class="box-title">Synopsis</h3>
                <p class="synopsis-text">
                    Tentang persahabatan... Tentang cinta...<br>
                    Tentang melupakan... Tentang <br>
                    perpisahan... Dan tentang hujan...
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
                </div>

                <div class="more-reviews">
                    <a href="#">More reviews and ratings <i class="fa-solid fa-chevron-right"></i></a>
                </div>
            </section>
        </div>
    </main>
@endsection
