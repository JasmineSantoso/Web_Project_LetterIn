@extends('layouts.app')

@section('title', 'LetterIn - ' . $user->fullname)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')
    <main class="main-container">
        
        {{-- ── Profile Header ─────────────────────────────── --}}
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

        {{-- ── Stats Bar ───────────────────────────────────── --}}
        <section class="stats-bar">
            <div class="stat-item">
                <span class="stat-title">Total Read Books</span>
                <span class="stat-number" id="total-books-count">{{ $totalBooks }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-title">Total Review</span>
                <span class="stat-number" id="total-reviews-count">{{ $totalReviews }}</span>
            </div>
        </section>

        {{-- ── Favorite Books ──────────────────────────────── --}}
        <section class="bordered-section">
            <h2 class="section-label">FAVORITE BOOKS</h2>
            <div class="books-grid" id="favorite-books-container">
                @if(isset($favoriteBooks) && $favoriteBooks->count() > 0)
                    @foreach($favoriteBooks as $book)
                        <div class="book-card" style="margin: 5px;">
                            <a href="{{ route('book.details', ['id' => $book->google_id]) }}">
                                <img src="{{ $book->cover_image ?? asset('images/cover1.jpg') }}" alt="{{ $book->title }}" style="width: 100px; height: 150px; object-fit: cover; border-radius: 5px;">
                            </a>
                        </div>
                    @endforeach
                @else
                    <p style="color: #777; margin-top: 10px;">No favorite books yet.</p>
                @endif
            </div>
        </section>

        {{-- ── To Read Books ───────────────────────────────── --}}
        <section class="bordered-section">
            <h2 class="section-label">TO READ</h2>
            <div class="books-grid" id="to-read-books-container">
                @if(isset($toReadBooks) && $toReadBooks->count() > 0)
                    @foreach($toReadBooks as $book)
                        <div class="book-card" style="margin: 5px;">
                            <a href="{{ route('book.details', ['id' => $book->google_id ?? $book->id]) }}">
                                <img src="{{ $book->cover_image ?? asset('images/cover1.jpg') }}" alt="{{ $book->title }}" style="width: 100px; height: 150px; object-fit: cover; border-radius: 5px;">
                            </a>
                        </div>
                    @endforeach
                @else
                    <p style="color: #777; margin-top: 10px;">No books to read yet.</p>
                @endif
            </div>
        </section>

        {{-- ── Done Read Books ──────────────────────────────── --}}
        <section class="bordered-section">
            <h2 class="section-label">DONE READ</h2>
            <div class="books-grid" id="done-read-books-container">
                @if(isset($doneReadBooks) && $doneReadBooks->count() > 0)
                    @foreach($doneReadBooks as $book)
                        <div class="book-card" style="margin: 5px;">
                            <a href="{{ route('book.details', ['id' => $book->google_id ?? $book->id]) }}">
                                <img src="{{ $book->cover_image ?? asset('images/cover1.jpg') }}" alt="{{ $book->title }}" style="width: 100px; height: 150px; object-fit: cover; border-radius: 5px;">
                            </a>
                        </div>
                    @endforeach
                @else
                    <p style="color: #777; margin-top: 10px;">No books done reading yet.</p>
                @endif
            </div>
        </section>

        {{-- ── Currently Review ────────────────────────────── --}}
        <section class="section-wrapper">
            <h2 class="plain-title">CURRENTLY REVIEW</h2>
            <div class="review-scroll-container" id="currently-review-container">
                @forelse($userReviews as $review)
                    <div class="review-card-dark">
                        <a href="{{ route('book.details', ['id' => $review->book->google_id ?? $review->book->id]) }}" style="flex-shrink: 0;">
                            <img src="{{ (str_starts_with($review->book->cover_image ?? '', 'http') || empty($review->book->cover_image)) ? ($review->book->cover_image ?: asset('images/cover1.jpg')) : asset('images/' . $review->book->cover_image) }}" 
                                 alt="{{ $review->book->title }}" 
                                 style="width: 70px; height: 100px; object-fit: cover; border-radius: 4px;">
                        </a>
                        <div class="review-content">
                            <h4>{{ $review->book->title }}</h4>
                            <p class="author">{{ $review->book->author }}</p>
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fa-solid fa-star" style="color: #FBC02D;"></i>
                                    @else
                                        <i class="fa-regular fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <p class="desc">{{ Str::limit($review->content, 120) }}</p>
                        </div>
                    </div>
                @empty
                    <p style="color: #777; margin-top: 10px;">No reviews yet.</p>
                @endforelse
            </div>
        </section>

        {{-- ── Bookmates (Following) ───────────────────────── --}}
        @if(isset($bookmates) && $bookmates->count() > 0)
        <div class="friend-list-row" id="friend-list-container">
            @foreach($bookmates as $mate)
                <a href="{{ route('profile.show', ['username' => $mate->username]) }}" class="friend-circle" style="text-decoration: none; color: inherit;">
                    <div class="avatar-placeholder">
                        @if($mate->profile)
                            <img src="{{ asset('images/' . $mate->profile) }}" alt="{{ $mate->username }}" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                        @else
                            <i class="fa-solid fa-user"></i>
                        @endif
                    </div>
                    <span>{{ $mate->username }}</span>
                </a>
            @endforeach
            <div class="next-icon">
                <a href="{{ route('bookmates') }}" style="text-decoration: none; color: inherit;"><i class="fa-regular fa-circle-right"></i></a>
            </div>
        </div>
        @endif

    </main>

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('scripts')
    <script src="{{ asset('js/profile.js') }}?v={{ filemtime(public_path('js/profile.js')) }}"></script>
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
