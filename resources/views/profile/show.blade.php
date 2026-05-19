@extends('layouts.app')

@section('title', 'LetterIn - ' . $user->fullname)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')
    <main class="main-container">
        
        <section class="profile-header">
            <div class="profile-avatar">
                @if($user->profile)
                    <img src="{{ asset('images/' . $user->profile) }}" alt="User Profile" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">
                @else
                    <div style="width: 150px; height: 150px; border-radius: 50%; background-color: #e0e0e0; display: flex; align-items: center; justify-content: center; font-size: 4rem; color: #757575;">
                        <i class="fa-solid fa-user"></i>
                    </div>
                @endif
            </div>
            <div class="profile-details">
                <h1 class="profile-name" style="display: flex; align-items: center; gap: 15px;">
                    <span>{{ $user->fullname }}</span> 
                    @if(Auth::check() && Auth::id() !== $user->user_id)
                        <button id="btn-follow" data-id="{{ $user->user_id }}" style="padding: 8px 16px; border-radius: 20px; font-size: 14px; font-weight: bold; cursor: pointer; border: none; {{ $isFollowing ? 'background-color: #e0e0e0; color: #333;' : 'background-color: #674636; color: white;' }}">
                            {{ $isFollowing ? 'Following' : 'Follow' }}
                        </button>
                    @endif
                </h1>
                <p class="profile-handle">{{ '@' . $user->username }}</p>
                <p class="profile-bio">{{ $user->bio ?: '"No bio yet."' }}</p>

                <div class="profile-stats-text">
                    <span>Following <strong id="user-following">{{ $user->following()->count() }}</strong></span>
                    <span>Followers <strong id="follower-count">{{ $user->followers()->count() }}</strong></span>
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

        <!-- Dummy Sections from Profile JS will populate these for demonstration -->
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

    </main>

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('scripts')
    <script src="{{ asset('js/profile.js') }}"></script>
    <script>
        document.getElementById('btn-follow')?.addEventListener('click', function() {
            let btn = this;
            let userId = btn.getAttribute('data-id');
            let countEl = document.getElementById('follower-count');
            
            fetch("{{ route('follow.toggle') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ user_id: userId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'followed') {
                    btn.innerText = 'Following';
                    btn.style.backgroundColor = '#e0e0e0';
                    btn.style.color = '#333';
                    countEl.innerText = parseInt(countEl.innerText) + 1;
                } else if (data.status === 'unfollowed') {
                    btn.innerText = 'Follow';
                    btn.style.backgroundColor = '#674636';
                    btn.style.color = 'white';
                    countEl.innerText = parseInt(countEl.innerText) - 1;
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
@endpush
