@extends('layouts.app')

@section('title', 'LetterIn - Welcome')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home_signed.css') }}">
@endpush

@section('content')
    <section class="greeting-section">
        @auth
            <h1>Have a good book, {{ Auth::user()->fullname }}!</h1>
        @endauth
    </section>

    <section class="current-read-section">
        <h2 class="section-title-white">YOUR CURRENT READ</h2>
        
        @if($currentReadBook)
        <div class="current-read-card">
            @if(!empty($currentReadGoogleId))
                <a href="{{ route('book.details', ['id' => $currentReadGoogleId]) }}" style="flex-shrink:0;">
                    <img src="{{ $currentReadCover ?? 'https://placehold.co/140x200?text=Cover' }}" alt="{{ $currentReadBook->title }}" class="current-cover">
                </a>
            @else
                <img src="{{ $currentReadCover ?? 'https://placehold.co/140x200?text=Cover' }}" alt="{{ $currentReadBook->title }}" class="current-cover">
            @endif

            <div class="read-details">
                @if(!empty($currentReadGoogleId))
                    <a href="{{ route('book.details', ['id' => $currentReadGoogleId]) }}" style="text-decoration:none; color:inherit;">
                        <h3 class="read-title">{{ $currentReadBook->title }}</h3>
                    </a>
                @else
                    <h3 class="read-title">{{ $currentReadBook->title }}</h3>
                @endif
                <p class="read-author">{{ $currentReadBook->author }}</p>
                
                <div class="rating-container" style="margin-bottom: 20px;">
                    <p class="rating-label" style="font-size: 1rem; margin-bottom: 5px; font-family: var(--font-serif);">Rating</p>
                    <div class="stars" style="color: #FFD700; font-size: 1.1rem; display: flex; align-items: center; gap: 4px;">
                        @php
                            $fullStars = floor($currentReadRating);
                            $halfStar = ($currentReadRating - $fullStars) >= 0.5 ? 1 : 0;
                            $emptyStars = 5 - $fullStars - $halfStar;
                        @endphp
                        @for($i=0; $i<$fullStars; $i++)
                            <i class="fa-solid fa-star"></i>
                        @endfor
                        @if($halfStar)
                            <i class="fa-solid fa-star-half-stroke"></i>
                        @endif
                        @for($i=0; $i<$emptyStars; $i++)
                            <i class="fa-regular fa-star"></i>
                        @endfor
                        <span class="rating-value" style="color: var(--text-cream); font-family: var(--font-serif); font-size: 0.95rem; margin-left: 6px;">({{ number_format($currentReadRating, 1) }})</span>
                    </div>
                </div>
                
                <p class="start-date">Start reading<br>{{ $currentReadStartDate ?? '-' }}</p>
                
                <a href="{{ route('book.review', ['book_id' => $currentReadGoogleId ?? $currentReadBook->id ?? 'unknown']) }}" class="btn-review" style="text-decoration: none;">
                    Add Review
                </a>
            </div>
        </div>
        @else
        <div class="current-read-card empty-read-card" style="display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 40px 20px; width: 100%;">
            <p style="color: #FFF8E7; font-family: 'Playfair Display', serif; font-size: 1.3rem; margin-bottom: 10px; font-weight: 600;">You don't have any book currently being read.</p>
            <p style="color: #FFF8E7; font-family: 'Lato', sans-serif; font-size: 0.95rem; opacity: 0.8; margin-bottom: 20px;">Label a book you are reading as "Currently Reading" to track your progress here.</p>
            <a href="{{ route('browse') }}" class="btn-review" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Browse Books</a>
        </div>
        @endif
    </section>

    <section class="carousel-section light-bg">
        <h2 class="section-title-brown">POPULAR THIS WEEK</h2>
        <div class="carousel-container">
            <button class="prev-arrow brown-arrow">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <div class="books-carousel">
                @foreach ($books as $book)
                    @php
                        $volumeInfo = $book['volumeInfo'] ?? [];
                        $thumbnail = $volumeInfo['imageLinks']['thumbnail'] ?? 'https://placehold.co/150x220?text=No+Cover';
                        $title = $volumeInfo['title'] ?? 'Unknown Title';
                        $bookId = $book['id'] ?? null;
                    @endphp
                    @if($bookId)
                        <a href="{{ route('book.details', ['id' => $bookId]) }}" title="{{ $title }}" style="flex-shrink:0;">
                            <img src="{{ $thumbnail }}" alt="{{ $title }}">
                        </a>
                    @else
                        <img src="{{ $thumbnail }}" alt="{{ $title }}">
                    @endif
                @endforeach
            </div>
            <button class="next-arrow brown-arrow">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </section>

    <section class="carousel-section dark-bg">
        <h2 class="section-title-white">RECOMMENDATIONS</h2>
        <div class="carousel-container">
            <button class="prev-arrow white-arrow">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <div class="books-carousel">
                @foreach ($recommendations as $book)
                    @php
                        $volumeInfo = $book['volumeInfo'] ?? [];
                        $thumbnail = $volumeInfo['imageLinks']['thumbnail'] ?? 'https://placehold.co/150x220?text=No+Cover';
                        $title = $volumeInfo['title'] ?? 'Unknown Title';
                        $bookId = $book['id'] ?? null;
                    @endphp
                    @if($bookId)
                        <a href="{{ route('book.details', ['id' => $bookId]) }}" title="{{ $title }}" style="flex-shrink:0;">
                            <img src="{{ $thumbnail }}" alt="{{ $title }}">
                        </a>
                    @else
                        <img src="{{ $thumbnail }}" alt="{{ $title }}">
                    @endif
                @endforeach
            </div>
            <button class="next-arrow white-arrow">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/home_signed.js') }}"></script>
@endpush
