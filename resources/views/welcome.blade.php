@extends('layouts.app')

@section('title', 'LetterIn - Home')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home_unsigned.css') }}">
@endpush

@section('content')
    <section class="hero">
        <h1 class="hero-title">Every read leaves a letter in</h1>
        <div class="search-container">
            <input type="text" placeholder="Search by title, author, or ISBN">
            <span class="search-icon">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
        </div>
    </section>

    <section class="most-read">
        <h2 class="section-title white-text">MOST READ THIS WEEK</h2>
        <div class="carousel-container">
            <button class="prev-arrow">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <div class="books-carousel">
                @foreach ($books as $book)
                    @php
                        $volumeInfo = $book['volumeInfo'] ?? [];
                        $thumbnail = $volumeInfo['imageLinks']['thumbnail'] ?? 'https://placehold.co/150x220?text=No+Cover';
                        $title = $volumeInfo['title'] ?? 'Unknown Title';
                    @endphp
                    <div class="book-card">
                        <img src="{{ $thumbnail }}" alt="{{ $title }}">
                    </div>
                @endforeach
            </div>
            <button class="next-arrow">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </section>

    <section class="features">
        <h2 class="section-title brown-text">FEATURES</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="icon-box">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <p>Track your reading progress and set annual reading goals</p>
            </div>
            <div class="feature-card">
                <div class="icon-box">
                    <i class="fa-solid fa-pen-nib"></i>
                </div>
                <p>Write and share reviews for your favorite books</p>
            </div>
            <div class="feature-card">
                <div class="icon-box">
                    <i class="fa-solid fa-users"></i>
                </div>
                <p>Connect with other book lovers and discover what they're reading</p>
            </div>
            <div class="feature-card">
                <div class="icon-box">
                    <i class="fa-solid fa-star"></i>
                </div>
                <p>Get personalized recommendations based on your taste</p>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/home_signed.js') }}"></script>
@endpush
