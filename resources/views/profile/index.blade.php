@extends('layouts.app')

@section('title', 'LetterIn - User Profile')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')
    <main class="main-container">
        
        <section class="profile-header">
            <div class="profile-avatar">
                @if(Auth::user()->profile)
                    <img src="{{ asset('images/' . Auth::user()->profile) }}" alt="User Profile" id="user-avatar" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">
                @else
                    <div style="width: 150px; height: 150px; border-radius: 50%; background-color: #e0e0e0; display: flex; align-items: center; justify-content: center; font-size: 4rem; color: #757575;">
                        <i class="fa-solid fa-user"></i>
                    </div>
                @endif
            </div>
            <div class="profile-details">
                <h1 class="profile-name">
                    <span id="user-name-display">{{ Auth::user()->fullname }}</span> 
                    <a href="{{ route('settings') }}" title="Edit Profile"><i class="fa-solid fa-pen source-icon"></i></a>
                </h1>
                <p class="profile-handle" id="user-handle">{{ '@' . Auth::user()->username }}</p>
                <p class="profile-bio" id="user-bio">{{ Auth::user()->bio ?: '"No bio yet."' }}</p>

                <div class="profile-stats-text">
                    <span>Following <strong id="user-following">{{ Auth::user()->following()->count() }}</strong></span>
                    <span>Followers <strong id="user-followers">{{ Auth::user()->followers()->count() }}</strong></span>
                </div>
            </div>
        </section>

        <section class="bordered-section">
            <h2 class="section-label">FAVORITE BOOKS</h2>
            <div class="books-grid" id="favorite-books-container">
                @if(isset($favoriteBooks) && $favoriteBooks->count() > 0)
                    @foreach($favoriteBooks as $book)
                        <div class="book-card" style="margin: 5px;">
                            <a href="{{ route('book.details', ['id' => $book->google_id]) }}">
                                <img src="{{ $book->cover_image ?? asset('images/image10.jpg') }}" alt="{{ $book->title }}" style="width: 100px; height: 150px; object-fit: cover; border-radius: 5px;">
                            </a>
                        </div>
                    @endforeach
                @else
                    <p style="color: #777; margin-top: 10px;">No favorite books yet.</p>
                @endif
            </div>
        </section>

        <section class="stats-bar">
            <div class="stat-item">
                <span class="stat-title">Total Book</span>
                <span class="stat-number" id="total-books-count">0</span>
            </div>
            <div class="stat-item">
                <span class="stat-title">Total Review</span>
                <span class="stat-number" id="total-reviews-count">0</span>
            </div>
        </section>

        <section class="bordered-section">
            <h2 class="section-label">READED BOOKS</h2>
            <div class="books-grid" id="readed-books-container"></div>
        </section>

        <section class="section-wrapper">
            <h2 class="plain-title">CURRENTLY READING</h2>
            <div id="currently-reading-container"></div>
        </section>

        <section class="section-wrapper">
            <h2 class="plain-title">CURRENTLY REVIEW</h2>
            <div class="review-scroll-container" id="currently-review-container"></div>
        </section>

        <section class="section-wrapper">
            <h2 class="plain-title">BOOK SHELFS</h2>
            <div class="shelf-list" id="book-shelfs-container"></div>
        </section>

        <section class="bordered-section">
            <h2 class="section-label">READING LISTS</h2>
            <div class="books-grid" id="reading-lists-container"></div>
        </section>

        <div class="friend-list-row" id="friend-list-container"></div>

    </main>
@endsection

@push('scripts')
    <script src="{{ asset('js/profile.js') }}"></script>
@endpush
