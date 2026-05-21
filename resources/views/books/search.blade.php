@extends('layouts.app')

@section('title', 'LetterIn - Search Result')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/searchbook_signed.css') }}">
    <style>
        .search-section-wrapper {
            background-color: #F7EED3;
            padding: 30px 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .search-bar-inner {
            display: flex;
            align-items: center;
            background: white;
            border-radius: 30px;
            padding: 10px 20px;
            width: 60%;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border: 1px solid #674636;
        }
        .search-bar-inner input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 1rem;
            color: #674636;
        }
        .search-bar-inner button {
            background: none;
            border: none;
            cursor: pointer;
            color: #674636;
            font-size: 1.2rem;
        }
    </style>
@endpush

@section('content')
    <div class="search-section-wrapper">
        <form action="{{ route('search') }}" method="GET" style="width: 100%; display: flex; justify-content: center;">
            <div class="search-bar-inner">
                <input type="text" name="q" value="{{ $query }}" placeholder="Search by title, author, or ISBN" required>
                <button type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </form>
    </div>

    <section class="page-title-section">
        <h1>Book result for '{{ $query }}'</h1>
    </section>

    <section class="filter-bar">
        <div class="filter-buttons">
            <button class="filter-btn">Genre <i class="fa-solid fa-chevron-down"></i></button>
            <button class="filter-btn">Publish <i class="fa-solid fa-chevron-down"></i></button>
            <button class="filter-btn">Rating <i class="fa-solid fa-chevron-down"></i></button>
        </div>
    </section>

    <section class="result-list">
        @forelse ($books as $book)
            @php
                $volumeInfo = $book['volumeInfo'] ?? [];
                $authorsArray = $volumeInfo['authors'] ?? [];
                
                // Skip if no authors (Unknown Author)
                if (empty($authorsArray)) {
                    continue;
                }
                
                $authors = implode(', ', $authorsArray);
                $bookId = $book['id'] ?? null;
                $thumbnail = $volumeInfo['imageLinks']['thumbnail'] ?? 'https://placehold.co/150x220?text=No+Cover';
                $title = $volumeInfo['title'] ?? 'Unknown Title';
                $publishedDate = $volumeInfo['publishedDate'] ?? '';
                $year = $publishedDate ? substr($publishedDate, 0, 4) : 'N/A';
                $rating = $volumeInfo['averageRating'] ?? 0;
                $ratingsCount = $volumeInfo['ratingsCount'] ?? 0;
            @endphp
            <div class="book-card">
                @if($bookId)
                <a href="{{ route('book.details', ['id' => $bookId]) }}" style="display:block;">
                    <img src="{{ $thumbnail }}" alt="{{ $title }}" class="book-cover">
                </a>
                @else
                <img src="{{ $thumbnail }}" alt="{{ $title }}" class="book-cover">
                @endif
                
                <div class="book-info">
                    <div class="info-top">
                        @if($bookId)
                        <a href="{{ route('book.details', ['id' => $bookId]) }}" style="text-decoration:none; color:inherit;">
                            <h2 class="book-title">{{ $title }} <span class="book-year">{{ $year }}</span></h2>
                        </a>
                        @else
                        <h2 class="book-title">{{ $title }} <span class="book-year">{{ $year }}</span></h2>
                        @endif
                        <p class="book-author">{{ $authors }}</p>
                    </div>
                    <div class="book-rating">
                        @php
                            $fullStars = floor($rating);
                            $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
                            $emptyStars = 5 - $fullStars - $halfStar;
                            if ($emptyStars < 0) $emptyStars = 0;
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
                        <span class="rating-text">{{ $rating > 0 ? number_format($rating, 1) : 'No' }} rating ({{ $ratingsCount }} {{ $ratingsCount == 1 ? 'review' : 'reviews' }})</span>
                    </div>
                </div>

                <div class="action-box">
                    @if(Auth::guest() || (isset($forceGuestHeader) && $forceGuestHeader))
                        <div class="action-item" onclick="window.location.href='{{ route('signin') }}'">
                            <span>Sign in to track</span> <i class="fa-regular fa-bookmark"></i>
                        </div>
                    @else
                        <div class="action-item dropdown-item">
                            <span>To Read</span> <i class="fa-regular fa-bookmark"></i>
                        </div>
                        <div class="action-item">
                            <span>Add Favorite</span> <i class="fa-regular fa-heart"></i>
                        </div>
                        <div class="action-item">
                            <span>Add Bookshelf</span> <i class="fa-regular fa-square-check"></i>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 50px; color: #674636;">
                <p>No books found for '{{ $query }}'. Try another keyword.</p>
            </div>
        @endforelse
    </section>
@endsection
