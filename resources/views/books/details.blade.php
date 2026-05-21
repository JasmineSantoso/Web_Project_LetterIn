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
                    $avgRating = isset($localReviewsCount) && $localReviewsCount > 0 ? $localAverageRating : ($book['volumeInfo']['averageRating'] ?? 0);
                    $ratingsCount = isset($localReviewsCount) && $localReviewsCount > 0 ? $localReviewsCount : ($book['volumeInfo']['ratingsCount'] ?? 0);
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
                <div class="rating-text">based on {{ number_format($ratingsCount) }} {{ $ratingsCount == 1 ? 'review' : 'reviews' }}</div>
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
                <div class="action-item" id="btn-favorite" data-id="{{ $id }}" style="cursor: pointer;">
                    <span id="fav-text">{{ $isFavorited ? 'Favorited' : 'Add Favorite' }}</span> 
                    <i id="fav-icon" class="{{ $isFavorited ? 'fa-solid' : 'fa-regular' }} fa-heart" style="{{ $isFavorited ? 'color: red;' : '' }}"></i>
                </div>

                {{-- Bookshelf Dropdown --}}
                <div class="action-item dropdown-item" id="bookshelf-btn" onclick="toggleBookshelfDropdown()" style="position: relative; cursor: pointer; user-select: none;">
                    <span id="bookshelf-label">Add Bookshelf</span>
                    <i class="fa-regular fa-square-check"></i>
                </div>
                <div id="bookshelf-dropdown" style="display: none; position: absolute; left: 0; right: 0; background: #FFF8EF; border: 1.5px solid rgba(93,64,55,0.18); border-radius: 10px; box-shadow: 0 8px 25px rgba(0,0,0,0.15); z-index: 200; overflow: hidden; margin-top: 4px; animation: fadeInDown 0.2s ease;">
                    <div id="bookshelf-list">
                        @forelse($userBookshelves as $shelf)
                            <div class="bookshelf-option" data-shelf-id="{{ $shelf->id }}"
                                 onclick="addToShelf({{ $shelf->id }}, '{{ addslashes($shelf->name) }}')"
                                 style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; justify-content: space-between; font-size: 0.88rem; color: #4E342E; transition: background 0.15s; border-bottom: 1px solid rgba(93,64,55,0.07);"
                                 onmouseover="this.style.background='#F3E5D0'" onmouseout="this.style.background=''">
                                <span><i class="fa-regular fa-bookmark" style="margin-right: 8px; color: #8D6E63;"></i>{{ $shelf->name }}</span>
                                <span style="font-size: 0.75rem; color: #BCAAA4;">{{ $shelf->books_count }} bk</span>
                            </div>
                        @empty
                            <div id="bookshelf-empty-hint" style="padding: 10px 16px; font-size: 0.85rem; color: #8D6E63; font-style: italic;">No shelves yet.</div>
                        @endforelse
                    </div>
                    {{-- Create new shelf inline --}}
                    <div id="bookshelf-new-row" style="padding: 8px 10px; border-top: 1px solid rgba(93,64,55,0.1);">
                        <div id="bookshelf-new-trigger" onclick="showNewShelfInput()" style="display: flex; align-items: center; gap: 8px; cursor: pointer; color: #5D4037; font-size: 0.87rem; font-weight: bold; padding: 4px 6px; border-radius: 6px; transition: background 0.15s;" onmouseover="this.style.background='#F3E5D0'" onmouseout="this.style.background=''">
                            <i class="fa-solid fa-plus"></i> Create New Shelf
                        </div>
                        <div id="bookshelf-new-input-row" style="display: none; margin-top: 6px; display: none;">
                            <input id="bookshelf-new-name" type="text" placeholder="Shelf name..." maxlength="100"
                                   style="width: 100%; padding: 7px 10px; border: 1.5px solid #BCAAA4; border-radius: 7px; font-size: 0.85rem; outline: none; color: #4E342E; box-sizing: border-box; margin-bottom: 6px;"
                                   onkeydown="if(event.key==='Enter'){ event.preventDefault(); submitShelfFromDetails(); }">
                            <div style="display: flex; gap: 6px;">
                                <button onclick="submitShelfFromDetails()" style="flex: 1; background: #5D4037; color: white; border: none; padding: 6px; border-radius: 7px; font-size: 0.82rem; font-weight: bold; cursor: pointer;">Create & Add</button>
                                <button onclick="hideNewShelfInput()" style="background: transparent; color: #8D6E63; border: 1.5px solid #BCAAA4; padding: 6px 10px; border-radius: 7px; font-size: 0.82rem; cursor: pointer;">✕</button>
                            </div>
                        </div>
                    </div>
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
                    @forelse($reviews as $review)
                    <div class="review-item" style="display: flex; align-items: flex-start; gap: 15px;">
                        <div class="user-avatar" style="width: 48px; height: 48px; border-radius: 50%; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #E6D5C3; flex-shrink: 0; font-size: 2.2rem; color: #4E342E; margin: 0;">
                            @if(isset($review->user))
                                <a href="{{ route('profile.show', ['username' => $review->user->username]) }}" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                    @if($review->user->profile)
                                        <img src="{{ asset('images/' . $review->user->profile) }}" alt="{{ $review->user->username }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                    @else
                                        <i class="fa-regular fa-circle-user" style="font-size: 2.2rem; color: #4E342E;"></i>
                                    @endif
                                </a>
                            @else
                                <i class="fa-regular fa-circle-user" style="font-size: 2.2rem; color: #4E342E;"></i>
                            @endif
                        </div>
                        <div class="review-body">
                            <div class="review-meta">
                                <span class="username">
                                    @if(isset($review->user))
                                        Review by <a href="{{ route('profile.show', ['username' => $review->user->username]) }}" style="font-weight: bold; color: inherit; text-decoration: none; border-bottom: 1px dashed rgba(78, 52, 46, 0.4);">{{ '@' . $review->user->username }}</a>
                                    @else
                                        Review by <strong>Anonymous</strong>
                                    @endif
                                </span>
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

                            @php
                                $hasLiked = auth()->check() && $review->likes->contains('user_id', auth()->id());
                                $hasReported = auth()->check() && $review->reports->contains('user_id', auth()->id());
                                $likesCount = $review->likes->count();
                                $commentsCount = $review->comments->count();
                            @endphp
                            <div class="review-actions" style="margin-top: 15px; border-top: 1px solid rgba(78, 52, 46, 0.08); padding-top: 12px;">
                                <span class="like-btn {{ $hasLiked ? 'liked' : '' }}" data-id="{{ $review->id }}" style="cursor: pointer; display: inline-flex; align-items: center; gap: 6px; color: {{ $hasLiked ? '#5D4037' : '#666' }}; font-weight: {{ $hasLiked ? 'bold' : 'normal' }}; transition: all 0.2s;">
                                    <i class="{{ $hasLiked ? 'fa-solid' : 'fa-regular' }} fa-thumbs-up" style="{{ $hasLiked ? 'color: #5D4037;' : '' }}"></i>
                                    Like (<span class="likes-count">{{ $likesCount }}</span>)
                                </span>
                                <span class="comment-toggle-btn" data-id="{{ $review->id }}" style="cursor: pointer; display: inline-flex; align-items: center; gap: 6px; color: #666; transition: all 0.2s;">
                                    <i class="fa-regular fa-comment"></i>
                                    Comment (<span class="comments-count">{{ $commentsCount }}</span>)
                                </span>
                                <span class="report-btn {{ $hasReported ? 'reported' : '' }}" data-id="{{ $review->id }}" style="cursor: pointer; display: inline-flex; align-items: center; gap: 6px; color: {{ $hasReported ? '#c0392b' : '#666' }}; font-weight: {{ $hasReported ? 'bold' : 'normal' }}; transition: all 0.2s; margin-left: auto;">
                                    <i class="{{ $hasReported ? 'fa-solid' : 'fa-regular' }} fa-flag" style="{{ $hasReported ? 'color: #c0392b;' : '' }}"></i>
                                    <span class="report-text">{{ $hasReported ? 'Reported' : 'Report' }}</span>
                                </span>
                            </div>

                            {{-- Collapsible Comments Section --}}
                            <div class="comments-section-container" id="comments-section-{{ $review->id }}" style="display: none; margin-top: 15px; background: rgba(78, 52, 46, 0.04); border-radius: 8px; padding: 15px; border: 1px solid rgba(78, 52, 46, 0.08); animation: fadeIn 0.3s ease;">
                                <div class="comments-list" id="comments-list-{{ $review->id }}" style="display: flex; flex-direction: column; gap: 12px; max-height: 250px; overflow-y: auto; margin-bottom: 12px; padding-right: 5px;">
                                    @forelse($review->comments as $comment)
                                        <div class="comment-item" style="display: flex; align-items: flex-start; gap: 10px; font-size: 0.88rem;">
                                            <div class="commenter-avatar" style="width: 32px; height: 32px; border-radius: 50%; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #E6D5C3; flex-shrink: 0; border: 1px solid rgba(78, 52, 46, 0.15);">
                                                @if(isset($comment->user))
                                                    <a href="{{ route('profile.show', ['username' => $comment->user->username]) }}" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                        @if($comment->user->profile)
                                                            <img src="{{ asset('images/' . $comment->user->profile) }}" alt="{{ $comment->user->username }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                        @else
                                                            <i class="fa-regular fa-circle-user" style="font-size: 1.5rem; color: #4E342E;"></i>
                                                        @endif
                                                    </a>
                                                @else
                                                    <i class="fa-regular fa-circle-user" style="font-size: 1.5rem; color: #4E342E;"></i>
                                                @endif
                                            </div>
                                            <div class="comment-bubble" style="background: #FFF8E7; padding: 8px 12px; border-radius: 12px; flex: 1; border: 1px solid rgba(78, 52, 46, 0.06);">
                                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3px;">
                                                    <a href="{{ isset($comment->user) ? route('profile.show', ['username' => $comment->user->username]) : '#' }}" style="font-weight: bold; color: #4E342E; text-decoration: none; font-size: 0.82rem;">
                                                        {{ isset($comment->user) ? '@' . $comment->user->username : 'Anonymous' }}
                                                    </a>
                                                    <span style="font-size: 0.72rem; color: #8D6E63;">{{ $comment->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p style="margin: 0; color: #4a3b32; line-height: 1.3;">{{ $comment->content }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="no-comments-placeholder" style="color: #8D6E63; font-style: italic; font-size: 0.85rem; margin: 0; padding: 5px 0;">Belum ada komentar untuk review ini.</p>
                                    @endforelse
                                </div>

                                @auth
                                    <form class="comment-form" data-review-id="{{ $review->id }}" style="display: flex; align-items: center; gap: 10px; margin-top: 10px; border-top: 1px dashed rgba(78, 52, 46, 0.15); padding-top: 10px;">
                                        <div style="width: 32px; height: 32px; border-radius: 50%; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #E6D5C3; flex-shrink: 0; border: 1px solid rgba(78, 52, 46, 0.15);">
                                            @if(auth()->user()->profile)
                                                <img src="{{ asset('images/' . auth()->user()->profile) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <i class="fa-regular fa-circle-user" style="font-size: 1.5rem; color: #4E342E;"></i>
                                            @endif
                                        </div>
                                        <input type="text" class="comment-input" placeholder="Tulis komentar..." required style="flex: 1; padding: 8px 15px; border-radius: 20px; border: 1px solid #5D4037; font-family: var(--font-sans); font-size: 0.85rem; outline: none; background: #FFF8E7; color: var(--text-brown);">
                                        <button type="submit" style="background: #5D4037; color: white; border: none; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: transform 0.2s;">
                                            <i class="fa-solid fa-paper-plane" style="font-size: 0.85rem;"></i>
                                        </button>
                                    </form>
                                @else
                                    <div style="font-size: 0.82rem; color: #8D6E63; text-align: center; margin-top: 5px;">
                                        <a href="{{ route('signin') }}" style="color: #5D4037; font-weight: bold; text-decoration: underline;">Login</a> untuk menulis komentar.
                                    </div>
                                @endauth
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

    {{-- Sleek Glassmorphic Report Modal Overlay --}}
    <div class="report-modal-overlay" id="reportModalOverlay" style="display: none;">
        <div class="report-modal">
            <div class="report-modal-header">
                <h3>Laporkan Review</h3>
                <button type="button" class="close-modal-btn" id="closeReportModalBtn">&times;</button>
            </div>
            <form id="reportForm">
                <input type="hidden" id="reportReviewId" name="review_id" value="">
                <div class="report-modal-body">
                    <p class="report-instruction">Kenapa Anda ingin melaporkan review ini? Laporan Anda bersifat anonim.</p>
                    
                    <div class="report-options-list">
                        <label class="report-option-item">
                            <input type="radio" name="report_reason" value="Spam atau Promosi" required>
                            <span class="report-option-text">Spam atau Promosi Komersial</span>
                        </label>
                        <label class="report-option-item">
                            <input type="radio" name="report_reason" value="Bahasa Kasar / Pelecehan">
                            <span class="report-option-text">Bahasa Kasar / Pelecehan / Kebencian</span>
                        </label>
                        <label class="report-option-item">
                            <input type="radio" name="report_reason" value="Spoiler Tanpa Peringatan">
                            <span class="report-option-text">Spoiler Tanpa Peringatan</span>
                        </label>
                        <label class="report-option-item">
                            <input type="radio" name="report_reason" value="Konten Tidak Pantas / SARA">
                            <span class="report-option-text">Konten Tidak Pantas / Mengandung SARA</span>
                        </label>
                        <label class="report-option-item">
                            <input type="radio" name="report_reason" value="Lainnya">
                            <span class="report-option-text">Lainnya / Masalah Lain</span>
                        </label>
                    </div>

                    <div class="report-details-container" style="margin-top: 15px;">
                        <label for="reportDetails" style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px; color: #4E342E;">Detail Tambahan (Opsional):</label>
                        <textarea id="reportDetails" class="report-textarea" placeholder="Berikan penjelasan singkat jika ada..." rows="3"></textarea>
                    </div>
                </div>
                <div class="report-modal-footer">
                    <button type="button" class="btn-cancel-report" id="cancelReportBtn">Batal</button>
                    <button type="submit" class="btn-submit-report" id="submitReportBtn">Kirim Laporan</button>
                </div>
            </form>
        </div>
    </div>
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

    // ── Close dropdown when clicking outside ─────────────────
    document.addEventListener('click', function(e) {
        const btn      = document.getElementById('reading-status-btn');
        const dropdown = document.getElementById('reading-dropdown');
        if (dropdown && btn && !btn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('open');
            document.getElementById('reading-chevron').classList.remove('rotated');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

        // ── Favorite Button ──────────────────────────────────────
        const btnFavorite = document.getElementById('btn-favorite');
        if (btnFavorite) {
            btnFavorite.addEventListener('click', function() {
                if (!isAuthenticated) {
                    window.location.href = "{{ route('signin') }}";
                    return;
                }
                
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
                .then(response => {
                    if (response.status === 401) {
                        window.location.href = "{{ route('signin') }}";
                        return;
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data) return;
                    if (data.success) {
                        if (data.action === 'added') {
                            favText.textContent = 'Favorited';
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

        // ── Review Likes Toggling ───────────────────────────────
        document.querySelectorAll('.like-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (!isAuthenticated) {
                    window.location.href = "{{ route('signin') }}";
                    return;
                }

                const reviewId = this.getAttribute('data-id');
                const likeBtn = this;
                const icon = likeBtn.querySelector('i');
                const countSpan = likeBtn.querySelector('.likes-count');

                fetch(`/review/${reviewId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.status === 401) {
                        window.location.href = "{{ route('signin') }}";
                        return;
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data) return;
                    if (data.success) {
                        if (data.action === 'liked') {
                            likeBtn.classList.add('liked');
                            likeBtn.style.color = '#5D4037';
                            likeBtn.style.fontWeight = 'bold';
                            icon.classList.remove('fa-regular');
                            icon.classList.add('fa-solid');
                            icon.style.color = '#5D4037';
                        } else {
                            likeBtn.classList.remove('liked');
                            likeBtn.style.color = '#666';
                            likeBtn.style.fontWeight = 'normal';
                            icon.classList.remove('fa-solid');
                            icon.classList.add('fa-regular');
                            icon.style.color = '';
                        }
                        countSpan.textContent = data.likes_count;
                    } else {
                        alert(data.message || 'An error occurred.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to toggle like.');
                });
            });
        });

        // ── Comment Box Toggling ────────────────────────────────
        document.querySelectorAll('.comment-toggle-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const reviewId = this.getAttribute('data-id');
                const commentBox = document.getElementById(`comments-section-${reviewId}`);
                if (commentBox) {
                    if (commentBox.style.display === 'none') {
                        commentBox.style.display = 'block';
                    } else {
                        commentBox.style.display = 'none';
                    }
                }
            });
        });

        // ── Comment Submission (AJAX) ───────────────────────────
        document.querySelectorAll('.comment-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!isAuthenticated) {
                    window.location.href = "{{ route('signin') }}";
                    return;
                }

                const reviewId = this.getAttribute('data-review-id');
                const input = this.querySelector('.comment-input');
                const content = input.value.trim();
                const submitBtn = this.querySelector('button[type="submit"]');

                if (!content) return;

                // Disable input and button while sending
                input.disabled = true;
                if (submitBtn) submitBtn.disabled = true;

                fetch(`/review/${reviewId}/comment`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ content: content })
                })
                .then(response => {
                    if (response.status === 401) {
                        window.location.href = "{{ route('signin') }}";
                        return;
                    }
                    return response.json();
                })
                .then(data => {
                    // Re-enable elements
                    input.disabled = false;
                    if (submitBtn) submitBtn.disabled = false;

                    if (!data) return;

                    if (data.success) {
                        // Clear input
                        input.value = '';

                        const commentsList = document.getElementById(`comments-list-${reviewId}`);
                        const comment = data.comment;

                        // Create commenter avatar HTML
                        let avatarHtml = `<i class="fa-regular fa-circle-user" style="font-size: 1.5rem; color: #4E342E;"></i>`;
                        if (comment.user.profile) {
                            avatarHtml = `<img src="${comment.user.profile}" alt="${comment.user.username}" style="width: 100%; height: 100%; object-fit: cover;">`;
                        }

                        // Create commenter bubble/item HTML
                        const commentItem = document.createElement('div');
                        commentItem.className = 'comment-item';
                        commentItem.style.display = 'flex';
                        commentItem.style.alignItems = 'flex-start';
                        commentItem.style.gap = '10px';
                        commentItem.style.fontSize = '0.88rem';
                        commentItem.style.animation = 'fadeIn 0.3s ease-out forwards';

                        commentItem.innerHTML = `
                            <div class="commenter-avatar" style="width: 32px; height: 32px; border-radius: 50%; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #E6D5C3; flex-shrink: 0; border: 1px solid rgba(78, 52, 46, 0.15);">
                                <a href="${comment.user.profile_url}" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                    ${avatarHtml}
                                </a>
                            </div>
                            <div class="comment-bubble" style="background: #FFF8E7; padding: 8px 12px; border-radius: 12px; flex: 1; border: 1px solid rgba(78, 52, 46, 0.06);">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3px;">
                                    <a href="${comment.user.profile_url}" style="font-weight: bold; color: #4E342E; text-decoration: none; font-size: 0.82rem;">
                                        @${comment.user.username}
                                    </a>
                                    <span style="font-size: 0.72rem; color: #8D6E63;">${comment.created_at}</span>
                                </div>
                                <p style="margin: 0; color: #4a3b32; line-height: 1.3;">${escapeHtml(comment.content)}</p>
                            </div>
                        `;

                        // Remove placeholder if present
                        const placeholder = commentsList.querySelector('.no-comments-placeholder');
                        if (placeholder) {
                            placeholder.remove();
                        }

                        // Append new comment
                        commentsList.appendChild(commentItem);

                        // Scroll comment list to bottom
                        commentsList.scrollTop = commentsList.scrollHeight;

                        // Increment comment count in review action button
                        const countSpan = document.querySelector(`.comment-toggle-btn[data-id="${reviewId}"] .comments-count`);
                        if (countSpan) {
                            countSpan.textContent = parseInt(countSpan.textContent) + 1;
                        }
                    } else {
                        alert(data.message || 'An error occurred.');
                    }
                })
                .catch(error => {
                    input.disabled = false;
                    if (submitBtn) submitBtn.disabled = false;
                    console.error('Error:', error);
                    alert('Failed to post comment.');
                });
            });
        });

        // ── Review Report Modal & AJAX Toggling ──────────────────
        const reportModalOverlay = document.getElementById('reportModalOverlay');
        const reportForm = document.getElementById('reportForm');
        const reportReviewIdInput = document.getElementById('reportReviewId');
        const closeReportModalBtn = document.getElementById('closeReportModalBtn');
        const cancelReportBtn = document.getElementById('cancelReportBtn');
        const reportDetailsInput = document.getElementById('reportDetails');
        let activeReportBtn = null; // To keep track of which report button opened the modal

        // Event listener for opening the modal
        document.querySelectorAll('.report-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (!isAuthenticated) {
                    window.location.href = "{{ route('signin') }}";
                    return;
                }

                // If already reported, do nothing or show message
                if (this.classList.contains('reported')) {
                    alert('Anda sudah melaporkan review ini.');
                    return;
                }

                activeReportBtn = this;
                const reviewId = this.getAttribute('data-id');
                
                // Set the review ID in the hidden input
                reportReviewIdInput.value = reviewId;
                
                // Clear previous inputs
                reportForm.reset();
                
                // Show the modal overlay
                reportModalOverlay.style.display = 'flex';
            });
        });

        // Function to close the modal
        function closeReportModal() {
            reportModalOverlay.style.display = 'none';
            activeReportBtn = null;
        }

        // Close on clicking X
        if (closeReportModalBtn) {
            closeReportModalBtn.addEventListener('click', closeReportModal);
        }

        // Close on clicking Batal
        if (cancelReportBtn) {
            cancelReportBtn.addEventListener('click', closeReportModal);
        }

        // Close when clicking outside the modal box (on the overlay itself)
        if (reportModalOverlay) {
            reportModalOverlay.addEventListener('click', function(e) {
                if (e.target === reportModalOverlay) {
                    closeReportModal();
                }
            });
        }

        // Handle AJAX form submission
        if (reportForm) {
            reportForm.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!isAuthenticated) {
                    window.location.href = "{{ route('signin') }}";
                    return;
                }

                const reviewId = reportReviewIdInput.value;
                const selectedReasonInput = reportForm.querySelector('input[name="report_reason"]:checked');
                const reason = selectedReasonInput ? selectedReasonInput.value : '';
                const details = reportDetailsInput ? reportDetailsInput.value.trim() : '';

                if (!reviewId || !reason) {
                    alert('Silakan pilih alasan pelaporan.');
                    return;
                }

                const submitBtn = document.getElementById('submitReportBtn');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Mengirim...';
                }

                fetch(`/review/${reviewId}/report`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        reason: reason,
                        details: details
                    })
                })
                .then(response => {
                    if (response.status === 401) {
                        window.location.href = "{{ route('signin') }}";
                        return;
                    }
                    return response.json();
                })
                .then(data => {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Kirim Laporan';
                    }

                    if (!data) return;

                    if (data.success) {
                        // Close modal
                        closeReportModal();

                        // Show success alert
                        alert(data.message || 'Laporan berhasil dikirim!');

                        // Update the active report button state immediately in DOM
                        if (activeReportBtn) {
                            activeReportBtn.classList.add('reported');
                            activeReportBtn.style.color = '#c0392b';
                            activeReportBtn.style.fontWeight = 'bold';
                            
                            const icon = activeReportBtn.querySelector('i');
                            if (icon) {
                                icon.classList.remove('fa-regular');
                                icon.classList.add('fa-solid');
                                icon.style.color = '#c0392b';
                            }
                            
                            const textSpan = activeReportBtn.querySelector('.report-text');
                            if (textSpan) {
                                textSpan.textContent = 'Reported';
                            }
                        }
                    } else {
                        alert(data.message || 'Terjadi kesalahan.');
                    }
                })
                .catch(error => {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Kirim Laporan';
                    }
                    console.error('Error:', error);
                    alert('Gagal mengirim laporan. Silakan coba lagi.');
                });
            });
        }

        // Helper function to escape HTML to prevent XSS
        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }
    });
</script>

<script>
    // ─── Bookshelf Dropdown ───────────────────────────────────────
    const BOOK_GOOGLE_ID = '{{ $id }}';
    const CSRF_TOKEN     = '{{ csrf_token() }}';

    function toggleBookshelfDropdown() {
        const dd = document.getElementById('bookshelf-dropdown');
        if (dd.style.display === 'none' || dd.style.display === '') {
            dd.style.display = 'block';
        } else {
            dd.style.display = 'none';
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const btn = document.getElementById('bookshelf-btn');
        const dd  = document.getElementById('bookshelf-dropdown');
        if (dd && btn && !btn.contains(e.target) && !dd.contains(e.target)) {
            dd.style.display = 'none';
        }
    });

    function showNewShelfInput() {
        document.getElementById('bookshelf-new-trigger').style.display = 'none';
        const row = document.getElementById('bookshelf-new-input-row');
        row.style.display = 'block';
        document.getElementById('bookshelf-new-name').focus();
    }

    function hideNewShelfInput() {
        document.getElementById('bookshelf-new-trigger').style.display = 'flex';
        document.getElementById('bookshelf-new-input-row').style.display = 'none';
        document.getElementById('bookshelf-new-name').value = '';
    }

    function addToShelf(shelfId, shelfName) {
        fetch(`/bookshelves/${shelfId}/books`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: JSON.stringify({ book_google_id: BOOK_GOOGLE_ID })
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('bookshelf-dropdown').style.display = 'none';
            if (data.success) {
                showBookshelfToast('✔ Added to "' + shelfName + '"!', '#388E3C');
                document.getElementById('bookshelf-label').textContent = shelfName;
            } else {
                showBookshelfToast(data.message || 'Could not add to shelf.', data.message && data.message.includes('already') ? '#1565C0' : '#B71C1C');
            }
        })
        .catch(() => showBookshelfToast('An error occurred.', '#B71C1C'));
    }

    function submitShelfFromDetails() {
        const name = document.getElementById('bookshelf-new-name').value.trim();
        if (!name) {
            document.getElementById('bookshelf-new-name').style.borderColor = '#B71C1C';
            return;
        }
        document.getElementById('bookshelf-new-name').style.borderColor = '#BCAAA4';

        fetch('/bookshelves', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: JSON.stringify({ name, book_google_id: BOOK_GOOGLE_ID })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Add the new shelf to the dropdown list
                const list = document.getElementById('bookshelf-list');
                const emptyHint = document.getElementById('bookshelf-empty-hint');
                if (emptyHint) emptyHint.remove();

                const opt = document.createElement('div');
                opt.className = 'bookshelf-option';
                opt.dataset.shelfId = data.shelf.id;
                opt.style.cssText = 'padding:10px 16px;cursor:pointer;display:flex;align-items:center;justify-content:space-between;font-size:0.88rem;color:#4E342E;border-bottom:1px solid rgba(93,64,55,0.07);';
                opt.innerHTML = `<span><i class="fa-regular fa-bookmark" style="margin-right:8px;color:#8D6E63;"></i>${data.shelf.name}</span><span style="font-size:0.75rem;color:#BCAAA4;">1 bk</span>`;
                opt.addEventListener('mouseover', () => opt.style.background = '#F3E5D0');
                opt.addEventListener('mouseout', () => opt.style.background = '');
                opt.onclick = () => addToShelf(data.shelf.id, data.shelf.name);
                list.appendChild(opt);

                hideNewShelfInput();
                document.getElementById('bookshelf-dropdown').style.display = 'none';
                document.getElementById('bookshelf-label').textContent = data.shelf.name;
                showBookshelfToast('✔ Shelf created & book added!', '#388E3C');
            } else {
                showBookshelfToast(data.message || 'Could not create shelf.', '#B71C1C');
            }
        })
        .catch(() => showBookshelfToast('An error occurred.', '#B71C1C'));
    }

    function showBookshelfToast(msg, color) {
        let toast = document.getElementById('bookshelf-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'bookshelf-toast';
            toast.style.cssText = 'position:fixed;bottom:28px;right:28px;padding:12px 22px;border-radius:10px;color:#fff;font-weight:bold;font-size:0.9rem;box-shadow:0 6px 20px rgba(0,0,0,0.2);z-index:9999;transition:all 0.4s ease;opacity:0;transform:translateY(15px);';
            document.body.appendChild(toast);
        }
        toast.textContent = msg;
        toast.style.background = color;
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
        clearTimeout(toast._timer);
        toast._timer = setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(15px)';
        }, 3000);
    }
</script>
@endpush

