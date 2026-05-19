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
                <div class="action-item dropdown-item" id="reading-status-btn" onclick="toggleReadingDropdown()">
                    <span id="reading-status-label">To Read</span>
                    <i class="fa-solid fa-chevron-down" id="reading-chevron"></i>
                </div>
                <div class="reading-dropdown" id="reading-dropdown">
                    <div class="reading-option" onclick="setReadingStatus('To Read')">
                        <i class="fa-regular fa-bookmark"></i> To Read
                    </div>
                    <div class="reading-option" onclick="setReadingStatus('Currently Reading')">
                        <i class="fa-solid fa-book-open"></i> Currently Reading
                    </div>
                    <div class="reading-option" onclick="setReadingStatus('Done Reading')">
                        <i class="fa-solid fa-check"></i> Done Reading
                    </div>
                </div>

                {{-- Toggle: Favorite --}}
                <div class="action-item" id="favorite-btn" onclick="toggleFavorite()">
                    <span id="favorite-label">Add Favorite</span>
                    <i class="fa-regular fa-heart" id="favorite-icon"></i>
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
                        $reviews = \App\Models\Review::where('book_id', $id)
                            ->with('user')
                            ->latest()
                            ->take(5)
                            ->get();
                    @endphp

                    @forelse($reviews as $review)
                    <div class="review-item">
                        <div class="user-avatar"><i class="fa-regular fa-circle-user"></i></div>
                        <div class="review-body">
                            <div class="review-meta">
                                <span class="username">Review by <strong>{{ $review->user->name ?? 'Anonymous' }}</strong></span>
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
                            <p class="review-text">{{ $review->body }}</p>
                            <div class="review-actions">
                                <span><i class="fa-regular fa-thumbs-up"></i> Like</span>
                                <span><i class="fa-regular fa-comment"></i> Comment</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p style="color:#888; padding: 12px 0;">Belum ada review untuk buku ini.
                        @auth
                            <a href="{{ route('book.review', ['book_id' => $id]) }}">Jadilah yang pertama!</a>
                        @else
                            <a href="{{ route('signin') }}">Login untuk menulis review.</a>
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
    // ── Reading Status Dropdown ──────────────────────────────
    function toggleReadingDropdown() {
        const dropdown = document.getElementById('reading-dropdown');
        const chevron  = document.getElementById('reading-chevron');
        dropdown.classList.toggle('open');
        chevron.classList.toggle('rotated');
    }

    function setReadingStatus(status) {
        // Update label
        document.getElementById('reading-status-label').textContent = status;

        // Mark active option
        document.querySelectorAll('.reading-option').forEach(opt => {
            opt.classList.toggle('active', opt.textContent.trim() === status);
        });

        // Close dropdown
        document.getElementById('reading-dropdown').classList.remove('open');
        document.getElementById('reading-chevron').classList.remove('rotated');
    }

    // ── Favorite Toggle ──────────────────────────────────────
    let isFavorited = false;

    function toggleFavorite() {
        isFavorited = !isFavorited;
        const btn   = document.getElementById('favorite-btn');
        const icon  = document.getElementById('favorite-icon');
        const label = document.getElementById('favorite-label');

        if (isFavorited) {
            btn.classList.add('favorited');
            icon.className  = 'fa-solid fa-heart';
            icon.style.color = '#c0392b';
            label.textContent = 'Remove Favorite';
        } else {
            btn.classList.remove('favorited');
            icon.className  = 'fa-regular fa-heart';
            icon.style.color = '';
            label.textContent = 'Add Favorite';
        }
    }

    // ── Close dropdown when clicking outside ─────────────────
    document.addEventListener('click', function(e) {
        const btn      = document.getElementById('reading-status-btn');
        const dropdown = document.getElementById('reading-dropdown');
        if (dropdown && btn && !btn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('open');
            document.getElementById('reading-chevron').classList.remove('rotated');
        }
    });
</script>
@endpush
