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
        
        <div class="current-read-card">
            @if(!empty($currentReadGoogleId))
                <a href="{{ route('book.details', ['id' => $currentReadGoogleId]) }}" style="flex-shrink:0;">
                    <img src="{{ $currentReadCover ?? 'https://placehold.co/140x200?text=Cover' }}" alt="Laut Bercerita" class="current-cover">
                </a>
            @else
                <img src="{{ $currentReadCover ?? 'https://placehold.co/140x200?text=Cover' }}" alt="Laut Bercerita" class="current-cover">
            @endif

            <div class="read-details">
                @if(!empty($currentReadGoogleId))
                    <a href="{{ route('book.details', ['id' => $currentReadGoogleId]) }}" style="text-decoration:none; color:inherit;">
                        <h3 class="read-title">Laut Bercerita</h3>
                    </a>
                @else
                    <h3 class="read-title">Laut Bercerita</h3>
                @endif
                <p class="read-author">Leila S. Chudori</p>
                
                <div class="progress-container">
                    <p class="progress-label">Progress <span class="percent">35%</span></p>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 35%;"></div>
                    </div>
                </div>
                
                <p class="start-date">Start reading<br>08-01-2026</p>
                
                @if($currentReadBook)
                    <a href="{{ route('book.review', ['book_id' => $currentReadBook->id]) }}" class="btn-review" style="text-decoration: none;">
                        Add Review
                    </a>
                @else
                    <button class="btn-review" onclick="alert('Buku tidak ditemukan di database.')">
                        Add Review
                    </button>
                @endif
            </div>
        </div>
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
