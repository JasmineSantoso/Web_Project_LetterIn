@extends('layouts.app')

@section('title', 'LetterIn - Welcome')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home_signed.css') }}">
@endpush

@section('content')
    <section class="greeting-section">
        <h1>Welcome to LetterIn!</h1>
        @auth
            <h2>Have a good book, {{ Auth::user()->fullname }}!</h2>
        @endauth
    </section>

    <section class="current-read-section">
        <h2 class="section-title-white">YOUR CURRENT READ</h2>
        
        <div class="current-read-card">
            <img src="{{ $currentReadCover ?? 'https://placehold.co/140x200?text=Cover' }}" alt="Laut Bercerita" class="current-cover">
            
            <div class="read-details">
                <h3 class="read-title">Laut Bercerita</h3>
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
                    @endphp
                    <img src="{{ $thumbnail }}" alt="{{ $title }}">
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
                    @endphp
                    <img src="{{ $thumbnail }}" alt="{{ $title }}">
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
