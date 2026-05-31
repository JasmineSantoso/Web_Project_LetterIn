@extends('layouts.app')

@section('title', 'LetterIn - Book Detail')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/book_details.css') }}">
@endpush

@section('content')
    <main class="detail-container">
        
        <aside class="left-sidebar">
            <div class="cover-wrapper">
                <img src="{{ $book['volumeInfo']['imageLinks']['thumbnail'] ?? asset('images/image10.jpg') }}" alt="{{ $book['volumeInfo']['title'] ?? 'Book Cover' }}" class="book-cover">
            </div>
            
            <div class="sidebar-rating">
                @php
                    $avgRating = $book['volumeInfo']['averageRating'] ?? 0;
                    $ratingsCount = $book['volumeInfo']['ratingsCount'] ?? 0;
                    $fullStars = floor($avgRating);
                    $halfStar = ($avgRating - $fullStars) >= 0.5 ? 1 : 0;
                    $emptyStars = 5 - $fullStars - $halfStar;
                @endphp
                <div class="stars">
                    @for($i=0; $i<$fullStars; $i++)
                        <i class="fa-solid fa-star" style="color: #FFD700;"></i>
                    @endfor
                    @if($halfStar)
                        <i class="fa-solid fa-star-half-stroke" style="color: #FFD700;"></i>
                    @endif
                    @for($i=0; $i<$emptyStars; $i++)
                        <i class="fa-regular fa-star" style="color: #FFD700;"></i>
                    @endfor
                </div>
                <div class="rating-number">{{ $avgRating > 0 ? number_format($avgRating, 1) : 'No Rating' }}</div>
                <div class="rating-text">based on {{ number_format($ratingsCount) }} reviews</div>
            </div>

            @auth
            <a href="{{ route('book.review', ['book_id' => $id]) }}" class="btn-add-review" style="text-decoration: none; color: inherit; display: block;">
                <div class="btn-stars">
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                </div>
                <span>Add Review</span>
            </a>

            <div class="action-menu-box">
                {{-- Dropdown: Reading Status --}}
                <div class="action-item dropdown-item dropdown">
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

                {{-- Toggle: Favorite --}}
                <div class="action-item" id="btn-favorite" data-id="{{ $id }}" style="cursor: pointer;">
                    <span id="fav-text">{{ $isFavorited ? 'Remove Favorite' : 'Add Favorite' }}</span> 
                    <i id="fav-icon" class="{{ $isFavorited ? 'fa-solid' : 'fa-regular' }} fa-heart" style="{{ $isFavorited ? 'color: red;' : '' }}"></i>
                </div>

                {{-- Bookshelf --}}
                <div class="action-item">
                    <span>Add Bookshelf</span>
                    <i class="fa-regular fa-square-check"></i>
                </div>
            </div>
            @endauth


            @guest
            <div class="action-menu-box">
                <a href="{{ route('signin') }}" class="action-item" style="text-decoration:none;">
                    <span>Sign in to Review</span> <i class="fa-regular fa-star"></i>
                </a>
            </div>
            @endguest
        </aside>

        <div class="right-content">
            <h1 class="book-title">{{ $book['volumeInfo']['title'] ?? 'Unknown Title' }}</h1>
            @if(!empty($book['volumeInfo']['subtitle']))
                <h3 class="book-subtitle" style="font-size:1rem; color:#888; margin-top:-8px; margin-bottom:8px;">{{ $book['volumeInfo']['subtitle'] }}</h3>
            @endif
            <h2 class="book-author">{{ implode(', ', $book['volumeInfo']['authors'] ?? ['Unknown Author']) }}</h2>

            <div class="book-metadata">
                @php
                    $isbn = 'Unknown';
                    if (!empty($book['volumeInfo']['industryIdentifiers'])) {
                        foreach ($book['volumeInfo']['industryIdentifiers'] as $identifier) {
                            if (in_array($identifier['type'], ['ISBN_13', 'ISBN_10'])) {
                                $isbn = $identifier['identifier'];
                                break;
                            }
                        }
                    }
                @endphp
                <p><strong>Genre:</strong> {{ implode(', ', $book['volumeInfo']['categories'] ?? ['—']) }}</p>
                <p><strong>ISBN/UID:</strong> {{ $isbn }}</p>
                <p><strong>Format:</strong> {{ $book['volumeInfo']['printType'] ?? 'Unknown' }}</p>
                <p><strong>Language:</strong> {{ strtoupper($book['volumeInfo']['language'] ?? 'Unknown') }}</p>
                <p><strong>Publisher:</strong> {{ $book['volumeInfo']['publisher'] ?? 'Unknown Publisher' }}</p>
                <p><strong>Edition Publish Date:</strong> {{ $book['volumeInfo']['publishedDate'] ?? 'Unknown Date' }}</p>
                <p><strong>Page:</strong> {{ $book['volumeInfo']['pageCount'] ?? 'Unknown' }}</p>
                @if(!empty($book['volumeInfo']['maturityRating']))
                    <p><strong>Maturity:</strong> {{ $book['volumeInfo']['maturityRating'] === 'NOT_MATURE' ? 'All Ages' : $book['volumeInfo']['maturityRating'] }}</p>
                @endif
            </div>

            <section class="content-box synopsis-box">
                <h3 class="box-title">Synopsis</h3>
                <p class="synopsis-text">
                    {!! $book['volumeInfo']['description'] ?? 'No synopsis available for this book.' !!}
                </p>
            </section>

            <section class="content-box review-section">
                <h3 class="box-title">Review</h3>
                
                <div class="review-list">
                    @php
                        $resolvedLocalBookId = null;
                        if (is_numeric($id)) {
                            $resolvedLocalBookId = $id;
                        } else {
                            $localBookByApi = \App\Models\Book::where('title', $book['volumeInfo']['title'] ?? '')
                                ->first();
                            if ($localBookByApi) {
                                $resolvedLocalBookId = $localBookByApi->id;
                            }
                        }

                        $reviews = collect();
                        if ($resolvedLocalBookId) {
                            $reviews = \App\Models\Review::where('book_id', $resolvedLocalBookId)
                                ->with('user')
                                ->latest()
                                ->take(5)
                                ->get();
                        }
                    @endphp

                    @forelse($reviews as $review)
                    <div class="review-item">
                        <div class="user-avatar"><i class="fa-regular fa-circle-user"></i></div>
                        <div class="review-body">
                            <div class="review-meta">
                                <span class="username">Review by <strong>{{ $review->user->fullname ?? ($review->user->username ?? 'Anonymous') }}</strong></span>
                                <span class="date">{{ $review->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="review-stars">
                                @for($s = 1; $s <= 5; $s++)
                                    @if($s <= $review->rating)
                                        <i class="fa-solid fa-star" style="color:#FFD700;"></i>
                                    @else
                                        <i class="fa-regular fa-star" style="color:#FFD700;"></i>
                                    @endif
                                @endfor
                            </div>
                            <p class="review-text">{{ $review->content }}</p>
                            
                            @if(!empty($review->songs) && is_array($review->songs))
                                <div class="review-songs" style="display: flex; gap: 8px; margin-top: 10px; flex-wrap: wrap; margin-bottom: 5px;">
                                    @foreach($review->songs as $song)
                                        @php
                                            $songTitle = $song['title'] ?? 'Unknown Song';
                                            $songArtist = $song['artist'] ?? '';
                                            $songArt = $song['album_art'] ?? '';
                                        @endphp
                                        <div class="review-song-badge" style="display: inline-flex; align-items: center; gap: 6px; background-color: #5D4037; color: #FFF8E7; padding: 4px 10px; border-radius: 12px; font-size: 0.78rem; font-family: var(--font-sans); border: 1px solid rgba(255, 248, 231, 0.2);">
                                            @if($songArt)
                                                <img src="{{ $songArt }}" style="width: 16px; height: 16px; border-radius: 50%; object-fit: cover;">
                                            @else
                                                <i class="fa-solid fa-music" style="font-size: 0.7rem;"></i>
                                            @endif
                                            <span>{{ $songTitle }}{{ $songArtist ? ' - ' . $songArtist : '' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="review-actions">
                                <span><i class="fa-regular fa-thumbs-up"></i> Like</span>
                                <span><i class="fa-regular fa-comment"></i> Comment</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p style="color:#888; padding: 12px 0;">Belum ada review untuk buku ini.
                        @auth
                            <a href="{{ route('book.review', ['book_id' => $id]) }}" style="color: #5D4037; font-weight: bold; text-decoration: underline;">Jadilah yang pertama!</a>
                        @else
                            <a href="{{ route('signin') }}" style="color: #5D4037; font-weight: bold; text-decoration: underline;">Login untuk menulis review.</a>
                        @endauth
                    </p>
                    @endforelse
                </div>

                <div class="more-reviews">
                    <a href="#">More reviews and ratings <i class="fa-solid fa-chevron-right"></i></a>
                </div>
            </section>

        </div>
    </main>
@endsection

@push('scripts')
<script>
    // Handle Reading Status option selection
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('dropdown-option')) {
            const option = e.target;
            const statusText = option.textContent.trim();
            
            // Find parent elements
            const details = option.closest('details');
            if (details) {
                const summarySpan = details.querySelector('summary span');
                if (summarySpan) {
                    summarySpan.textContent = statusText;
                }
                
                // Highlight active option
                const allOptions = details.querySelectorAll('.dropdown-option');
                allOptions.forEach(opt => {
                    opt.classList.toggle('active', opt.textContent.trim() === statusText);
                });
                
                // Close the details dropdown
                details.removeAttribute('open');
            }
        }
    });

    // Close details dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        const openDetails = document.querySelectorAll('.action-menu-box details[open]');
        openDetails.forEach(details => {
            if (!details.contains(e.target)) {
                details.removeAttribute('open');
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const btnFavorite = document.getElementById('btn-favorite');
        if (btnFavorite) {
            btnFavorite.addEventListener('click', function() {
                const bookId = this.getAttribute('data-id');
                const favText = document.getElementById('fav-text');
                const favIcon = document.getElementById('fav-icon');

                fetch(`/book/${bookId}/favorite`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.action === 'added') {
                            favText.textContent = 'Remove Favorite';
                            favIcon.classList.remove('fa-regular');
                            favIcon.classList.add('fa-solid');
                            favIcon.style.color = 'red';
                        } else {
                            favText.textContent = 'Add Favorite';
                            favIcon.classList.remove('fa-solid');
                            favIcon.classList.add('fa-regular');
                            favIcon.style.color = '';
                        }
                    } else {
                        alert(data.message || 'An error occurred.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to toggle favorite.');
                });
            });
        }
    });
</script>
@endpush
