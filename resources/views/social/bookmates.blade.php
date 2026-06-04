@extends('layouts.app')

@section('title', 'LetterIn - Bookmates')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/bookmates.css') }}">
@endpush

@section('content')
    <main class="bookmates-container" style="position: relative; z-index: 105;">
        
        <nav class="sub-nav">
            <a href="{{ route('bookmates', ['tab' => 'all']) }}" class="tab-link {{ $tab === 'all' ? 'active-tab' : '' }}">All Updates</a>
            <a href="{{ route('bookmates', ['tab' => 'friends']) }}" class="tab-link {{ $tab === 'friends' ? 'active-tab' : '' }}">Friends</a>
            <a href="{{ route('bookmates', ['tab' => 'similar']) }}" class="tab-link {{ $tab === 'similar' ? 'active-tab' : '' }}">Similar User</a>
        </nav>

        <form action="{{ route('bookmates') }}" method="GET" class="mate-search-bar" style="margin-bottom: 20px;">
            <input type="text" name="q" placeholder="Search users by name or username" value="{{ $search ?? '' }}">
            @if(isset($tab) && $tab !== 'all')
                <input type="hidden" name="tab" value="{{ $tab }}">
            @endif
            <button type="submit">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>

        <div class="feed-list">
            @if(isset($search) && $search !== '')
                <h3 style="color: #674636; margin-bottom: 15px; font-family: var(--font-serif);">Search Results for "{{ $search }}"</h3>
                @forelse($users as $user)
                    <div class="feed-card" style="padding: 15px; display: flex; align-items: center; gap: 15px; border-radius: 12px; margin-bottom: 15px;">
                        @if($user->profile)
                            <img src="{{ asset('images/' . $user->profile) }}" alt="Avatar" style="width: 55px; height: 55px; border-radius: 50%; object-fit: cover; border: 2px solid #fff;">
                        @else
                            <div style="width: 55px; height: 55px; border-radius: 50%; background-color: #e0e0e0; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #757575; border: 2px solid #fff;">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        @endif
                        <div style="flex-grow: 1;">
                            <a href="{{ route('profile.show', ['username' => $user->username]) }}" style="text-decoration: none; color: #674636;">
                                <h4 style="margin: 0; font-family: var(--font-serif); font-size: 1.15rem;">{{ $user->fullname }}</h4>
                                <p style="margin: 0; font-size: 0.9rem; color: #a67c52;">{{ '@' . $user->username }}</p>
                            </a>
                        </div>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            @if(auth()->id() !== $user->user_id)
                                @php
                                    $isFollowing = auth()->user()->following->contains('following_id', $user->user_id);
                                @endphp
                                <button type="button" class="follow-toggle-btn" data-user-id="{{ $user->user_id }}" style="background-color: {{ $isFollowing ? 'transparent' : '#674636' }}; color: {{ $isFollowing ? '#674636' : 'white' }}; border: 1.5px solid #674636; padding: 6px 16px; border-radius: 20px; font-weight: bold; cursor: pointer; font-size: 0.85rem; transition: all 0.2s;">
                                    {{ $isFollowing ? 'Following' : 'Follow' }}
                                </button>
                            @endif
                            <a href="{{ route('profile.show', ['username' => $user->username]) }}" style="background-color: #FFF1C9; border: 1.5px solid rgba(78, 52, 46, 0.2); color: #4E342E; padding: 6px 16px; border-radius: 20px; text-decoration: none; font-weight: bold; font-size: 0.85rem; transition: all 0.2s;">View Profile</a>
                        </div>
                    </div>
                @empty
                    <p style="color: #674636;">No users found matching "{{ $search }}".</p>
                @endforelse
            @elseif($tab === 'similar')
                <h3 style="color: #674636; margin-bottom: 20px; font-family: var(--font-serif);">People You May Know</h3>
                @forelse($similarUsers as $simUser)
                    <div class="feed-card" style="padding: 15px; display: flex; align-items: center; gap: 15px; border-radius: 12px; margin-bottom: 15px;">
                        @if($simUser->profile)
                            <img src="{{ asset('images/' . $simUser->profile) }}" alt="Avatar" style="width: 55px; height: 55px; border-radius: 50%; object-fit: cover; border: 2px solid #fff;">
                        @else
                            <div style="width: 55px; height: 55px; border-radius: 50%; background-color: #e0e0e0; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #757575; border: 2px solid #fff;">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        @endif
                        <div style="flex-grow: 1;">
                            <a href="{{ route('profile.show', ['username' => $simUser->username]) }}" style="text-decoration: none; color: #674636;">
                                <h4 style="margin: 0; font-family: var(--font-serif); font-size: 1.15rem;">{{ $simUser->fullname }}</h4>
                                <p style="margin: 0; font-size: 0.9rem; color: #a67c52;">{{ '@' . $simUser->username }}</p>
                            </a>
                        </div>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <button type="button" class="follow-toggle-btn" data-user-id="{{ $simUser->user_id }}" style="background-color: #674636; color: white; border: 1.5px solid #674636; padding: 6px 16px; border-radius: 20px; font-weight: bold; cursor: pointer; font-size: 0.85rem; transition: all 0.2s;">
                                Follow
                            </button>
                            <a href="{{ route('profile.show', ['username' => $simUser->username]) }}" style="background-color: #FFF1C9; border: 1.5px solid rgba(78, 52, 46, 0.2); color: #4E342E; padding: 6px 16px; border-radius: 20px; text-decoration: none; font-weight: bold; font-size: 0.85rem; transition: all 0.2s;">View Profile</a>
                        </div>
                    </div>
                @empty
                    <p style="color: #674636; text-align: center; padding: 30px;">All caught up! You are following everyone on LetterIn.</p>
                @endforelse
            @else

                @forelse($reviews as $review)
                    <div class="feed-card" style="margin-bottom: 20px;">
                        <div class="card-header">
                            <div class="user-info">
                                @if($review->user->profile)
                                    <img src="{{ asset('images/' . $review->user->profile) }}" alt="Avatar" style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid #fff;">
                                @else
                                    <div class="avatar-circle">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                @endif
                                <span class="username">
                                    <a href="{{ route('profile.show', ['username' => $review->user->username]) }}" style="text-decoration: none; color: inherit; font-weight: bold;">
                                        {{ '@' . $review->user->username }}
                                    </a> 
                                    reviewed:
                                </span>
                            </div>
                            <span class="time-stamp">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('book.details', ['id' => $review->book->google_id ?? $review->book->id]) }}">
                                <img src="{{ (str_starts_with($review->book->cover_image ?? '', 'http') || empty($review->book->cover_image)) ? ($review->book->cover_image ?: asset('images/cover1.jpg')) : asset('images/' . $review->book->cover_image) }}" alt="{{ $review->book->title }}" class="feed-book-cover" style="transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                            </a>
                            <div class="feed-book-info">
                                <h3 class="feed-book-title" style="margin: 0;">
                                    <a href="{{ route('book.details', ['id' => $review->book->google_id ?? $review->book->id]) }}" style="text-decoration: none; color: inherit; font-weight: 600;">
                                        {{ $review->book->title }}
                                    </a>
                                </h3>
                                <p class="feed-book-author" style="margin: 2px 0 8px 0;">{{ $review->book->author }}</p>
                                
                                <div class="feed-rating" style="margin-bottom: 8px;">
                                    <span style="font-weight: 700; margin-right: 5px; font-size: 0.95rem;">{{ number_format($review->rating, 1) }}</span>
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fa-solid fa-star"></i>
                                        @elseif($i - 0.5 <= $review->rating)
                                            <i class="fa-solid fa-star-half-stroke"></i>
                                        @else
                                            <i class="fa-regular fa-star" style="color: #ccc;"></i>
                                        @endif
                                    @endfor
                                </div>

                                @if($review->content)
                                    <p class="feed-review-text" style="font-style: italic; margin: 5px 0 12px 0; color: #5D4037; font-size: 0.92rem; line-height: 1.45; border-left: 2.5px solid #a67c52; padding-left: 10px; background: rgba(255, 255, 255, 0.25); padding-top: 4px; padding-bottom: 4px; border-radius: 0 4px 4px 0;">
                                        "{{ Str::limit($review->content, 140, '...') }}"
                                    </p>
                                @endif

                                @if(!empty($review->songs) && is_array($review->songs))
                                    <div class="review-songs" style="display: flex; gap: 8px; margin-bottom: 12px; flex-wrap: wrap;">
                                        @foreach($review->songs as $song)
                                            @php
                                                $songTitle = $song['title'] ?? 'Unknown Song';
                                                $songArtist = $song['artist'] ?? '';
                                                $songArt = $song['album_art'] ?? '';
                                            @endphp
                                            <div class="review-song-badge" 
                                                 onclick="playSong(this)"
                                                 data-preview="{{ $song['preview_url'] ?? '' }}" 
                                                 data-title="{{ $songTitle }}" 
                                                 data-artist="{{ $songArtist }}"
                                                 style="cursor: pointer; display: inline-flex; align-items: center; gap: 6px; background-color: #5D4037; color: #FFF8E7; padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; border: 1px solid rgba(255, 248, 231, 0.15); transition: transform 0.2s, background-color 0.2s;"
                                                 onmouseover="this.style.transform='scale(1.04)'; this.style.backgroundColor='#6D4C41';"
                                                 onmouseout="this.style.transform='scale(1)'; this.style.backgroundColor='#5D4037';">
                                                <span class="song-icon-container" style="display: inline-flex; align-items: center; justify-content: center; width: 14px; height: 14px;">
                                                    @if($songArt)
                                                        <img src="{{ $songArt }}" style="width: 14px; height: 14px; border-radius: 50%; object-fit: cover;">
                                                    @else
                                                        <i class="fa-solid fa-music" style="font-size: 0.65rem;"></i>
                                                    @endif
                                                </span>
                                                <span>{{ $songTitle }}{{ $songArtist ? ' - ' . $songArtist : '' }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="feed-actions" style="margin-top: auto; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid rgba(78, 52, 46, 0.08); padding-top: 10px;">
                                    <a href="{{ route('book.details', ['id' => $review->book->google_id ?? $review->book->id]) }}" class="see-review" style="text-decoration: none; font-weight: bold; color: #5D4037; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 5px; transition: transform 0.2s;">
                                        See full review <i class="fa-solid fa-arrow-right" style="font-size: 0.75rem;"></i>
                                    </a>
                                    
                                    @php
                                        $hasLiked = auth()->check() && $review->likes->contains('user_id', auth()->id());
                                    @endphp
                                    <div class="action-icons" style="display: flex; gap: 15px; align-items: center; color: #5D4037; font-size: 0.9rem;">
                                        <span style="display: flex; align-items: center; gap: 5px; cursor: default;">
                                            <i class="{{ $hasLiked ? 'fa-solid fa-heart' : 'fa-regular fa-heart' }}" style="{{ $hasLiked ? 'color: #d32f2f;' : '' }}"></i>
                                            <strong style="font-size: 0.85rem;">{{ $review->likes->count() }}</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    @if($tab === 'friends')
                        <div style="text-align: center; padding: 50px 20px; color: #674636;">
                            <i class="fa-solid fa-user-group" style="font-size: 3.5rem; opacity: 0.5; margin-bottom: 15px;"></i>
                            <p style="font-size: 1.2rem; font-family: var(--font-serif); font-weight: bold;">No reviews from your bookmates yet.</p>
                            <a href="{{ route('bookmates', ['tab' => 'similar']) }}" style="display: inline-block; margin-top: 15px; background-color: #674636; color: white; padding: 8px 25px; border-radius: 20px; text-decoration: none; font-weight: bold;">Find People to Follow</a>
                        </div>
                    @else
                        <div style="text-align: center; padding: 50px 20px; color: #674636;">
                            <i class="fa-solid fa-feather-pointed" style="font-size: 3.5rem; opacity: 0.5; margin-bottom: 15px;"></i>
                            <p style="font-size: 1.2rem; font-family: var(--font-serif); font-weight: bold;">No reviews written on the platform yet.</p>
                            <a href="{{ route('browse') }}" style="display: inline-block; margin-top: 15px; background-color: #674636; color: white; padding: 8px 25px; border-radius: 20px; text-decoration: none; font-weight: bold;">Browse & Write a Review</a>
                        </div>
                    @endif
                @endforelse
            @endif
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.follow-toggle-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    const btn = this;
                    
                    btn.disabled = true;
                    btn.style.opacity = '0.6';

                    fetch("{{ route('follow.toggle') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ user_id: userId })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'followed') {
                            btn.textContent = 'Following';
                            btn.style.backgroundColor = 'transparent';
                            btn.style.color = '#674636';
                        } else if (data.status === 'unfollowed') {
                            btn.textContent = 'Follow';
                            btn.style.backgroundColor = '#674636';
                            btn.style.color = 'white';
                            
                            // If we are on the Friends tab, animate and remove the card instantly
                            const tabParams = new URLSearchParams(window.location.search);
                            if (tabParams.get('tab') === 'friends') {
                                const card = btn.closest('.feed-card');
                                if (card) {
                                    card.style.transition = 'all 0.3s ease';
                                    card.style.opacity = '0';
                                    card.style.transform = 'translateY(-10px)';
                                    setTimeout(() => {
                                        card.remove();
                                        if (document.querySelectorAll('.feed-card').length === 0) {
                                            window.location.reload();
                                        }
                                    }, 300);
                                }
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error toggling follow:', error);
                        alert('Oops! Something went wrong. Please try again.');
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.style.opacity = '1';
                    });
                });
            });
        });
    </script>
@endsection
