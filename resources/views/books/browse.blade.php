@extends('layouts.app')

@section('title', 'LetterIn - Browse Books')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/browse.css') }}">
@endpush

@section('content')
    <section class="browse-hero">
        <h1 class="page-title">Every read leaves a letter in</h1>
        <div class="main-search-container">
            <input type="text" value="Hujan">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>
    </section>

    <section class="category-filter">
        <div class="button-row">
            <button class="cat-btn">Popular Books</button>
            <button class="cat-btn active-trending">Trending Now</button>
            <button class="cat-btn">Top Rated Books</button>
        </div>
        <div class="button-row">
            <button class="cat-btn">Most Reviewed Books</button>
            <button class="cat-btn">Editor's Choice</button>
            <button class="cat-btn">New Releases</button>
        </div>
    </section>

    <section class="browse-list">
        @php
            $books = [
                ['title' => 'Dan Hujan Pun Berhenti', 'year' => 2007, 'author' => 'Farida Susanty', 'img' => 'hujan1.jpg', 'rating' => 3.11],
                ['title' => 'Episode Hujan', 'year' => 2016, 'author' => 'Lucia Priandarini', 'img' => 'hujan2.jpg', 'rating' => 3.55],
                ['title' => 'Hujan Kepagian', 'year' => 1958, 'author' => 'Nugroho Notosusanto', 'img' => 'hujan3.jpg', 'rating' => 3.86],
                ['title' => 'Wait For The Rain', 'year' => 2015, 'author' => 'Maria Murnane', 'img' => 'hujan4.jpg', 'rating' => 3.77],
                ['title' => 'Hujan', 'year' => 2016, 'author' => 'Tere Liye', 'img' => 'image10.jpg', 'rating' => 4.22],
            ];
        @endphp

        @foreach ($books as $book)
        <div class="browse-card">
            <img src="{{ asset('images/' . $book['img']) }}" alt="{{ $book['title'] }}" class="browse-cover">
            
            <div class="browse-info">
                <div class="info-header">
                    <h2 class="browse-title">{{ $book['title'] }} <span class="browse-year">{{ $book['year'] }}</span></h2>
                    <p class="browse-author">{{ $book['author'] }}</p>
                </div>
                <div class="browse-rating">
                    @php
                        $fullStars = floor($book['rating']);
                        $halfStar = ($book['rating'] - $fullStars) >= 0.5 ? 1 : 0;
                        $emptyStars = 5 - $fullStars - $halfStar;
                    @endphp
                    @for ($i = 0; $i < $fullStars; $i++)
                        <i class="fa-solid fa-star"></i>
                    @endfor
                    @if ($halfStar)
                        <i class="fa-solid fa-star-half-stroke"></i>
                    @endif
                    @for ($i = 0; $i < $emptyStars; $i++)
                        <i class="fa-regular fa-star"></i>
                    @endfor
                    <span class="rating-text">{{ $book['rating'] }} rating</span>
                </div>
            </div>

            <div class="action-box">
                <div class="action-item dropdown">
                    <details>
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
        </div>
        @endforeach
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/home_signed.js') }}"></script>
@endpush
