@extends('layouts.app')

@section('title', 'LetterIn - Edit Review')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/add_review.css') }}?v={{ filemtime(public_path('css/add_review.css')) }}">
@endpush

@section('content')
    <div class="review-container">
        <form action="{{ route('review.update', $review->id) }}" method="POST" id="reviewForm" style="display: contents;">
            @csrf
            @method('PUT')
            <input type="hidden" name="rating" id="ratingInput" value="{{ $review->rating }}">
            <input type="hidden" name="bookshelf_status" id="bookshelfInput" value="{{ $review->bookshelf_status ?? '' }}">
            <div id="songsHiddenInputs">
                @if(!empty($review->songs) && count($review->songs) > 0)
                    @php $song = $review->songs[0]; @endphp
                    <input type="hidden" name="songs[0][title]" value="{{ $song['title'] }}">
                    <input type="hidden" name="songs[0][artist]" value="{{ $song['artist'] }}">
                    <input type="hidden" name="songs[0][album_art]" value="{{ $song['album_art'] }}">
                    <input type="hidden" name="songs[0][preview_url]" value="{{ $song['preview_url'] ?? '' }}">
                @endif
            </div>

        
            <div class="left-column">
                <div class="book-cover-wrapper">
                    <img src="{{ (str_starts_with($book->cover_image ?? '', 'http') || empty($book->cover_image)) ? ($book->cover_image ?: asset('images/image11.jpg')) : asset('images/' . $book->cover_image) }}" alt="{{ $book->title }}" class="book-img">
                </div>
            </div>
 
            <div class="right-column">
                <h1 class="book-title">{{ $book->title }}</h1>
                <h2 class="book-author">{{ $book->author }}</h2>
 
                @auth
                <div class="reviewer-badge">
                    <div class="reviewer-avatar">
                        @if(Auth::user()->profile)
                            <img src="{{ asset('images/' . Auth::user()->profile) }}" alt="{{ Auth::user()->username }}">
                        @else
                            <i class="fa-regular fa-circle-user"></i>
                        @endif
                    </div>
                    <div class="reviewer-info-text">
                        <span class="reviewer-name">{{ Auth::user()->fullname }}</span>
                        <span class="reviewer-handle">{{ '@' . Auth::user()->username }}</span>
                    </div>
                </div>
                @endauth
 
            <div class="star-rating-input">
                @for($i = 1; $i <= 5; $i++)
                    <i class="{{ $i <= $review->rating ? 'fa-solid active-star' : 'fa-regular' }} fa-star" data-value="{{ $i }}"></i>
                @endfor
            </div>
 
                <textarea name="content" class="review-textarea" placeholder="Write your review here">{{ old('content', $review->content) }}</textarea>
 
            <div class="song-section">
                <h3 class="section-label">Add Related Song</h3>
                
                <div class="song-search-container">
                    <div class="song-input-box" id="songInputBox" style="{{ !empty($review->songs) && count($review->songs) > 0 ? 'display: none;' : '' }}">
                        <input type="text" id="songInput" placeholder="Search song..." autocomplete="off">
                    </div>
                    
                    <!-- Selected Song Box (Exact Screenshot Match) -->
                    <div class="selected-song-container" id="selectedSongContainer" style="{{ !empty($review->songs) && count($review->songs) > 0 ? 'display: flex;' : 'display: none;' }}"
                         @if(!empty($review->songs) && count($review->songs) > 0)
                             @php $song = $review->songs[0]; @endphp
                             data-title="{{ $song['title'] }}"
                             data-artist="{{ $song['artist'] }}"
                             data-art="{{ $song['album_art'] }}"
                             data-preview="{{ $song['preview_url'] ?? '' }}"
                         @endif
                    >
                        <div class="selected-song-info" id="selectedSongInfo" style="cursor: pointer; display: flex; align-items: center; gap: 15px;">
                            <span class="song-icon-container" style="display: inline-flex; align-items: center; justify-content: center; width: 45px; height: 45px; flex-shrink: 0; background-color: #5D4037; border-radius: 4px;">
                                <img src="{{ !empty($review->songs) && count($review->songs) > 0 ? $review->songs[0]['album_art'] : '' }}" id="selectedSongArt" class="selected-song-art" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">
                            </span>
                            <span id="selectedSongText" class="selected-song-text">{{ !empty($review->songs) && count($review->songs) > 0 ? ($review->songs[0]['title'] . ' - ' . $review->songs[0]['artist']) : '' }}</span>
                        </div>
                        <div class="selected-song-action" id="selectedSongAction">
                            <i class="fa-solid fa-xmark"></i>
                        </div>
                    </div>

                    <!-- Search dropdown -->
                    <div id="searchResults" class="search-results-dropdown" style="display: none;"></div>
                </div>

                <!-- Recommendations Pills (Exact Pill Shape/Style in Screenshot) -->
                <div class="recommendations-container" id="recommendationsContainer">
                    @foreach($recommendedSongs as $recSong)
                        <div class="rec-song-pill" data-title="{{ $recSong['title'] }}" data-artist="{{ $recSong['artist'] }}" data-art="{{ $recSong['album_art'] }}" data-preview="{{ $recSong['preview_url'] ?? '' }}">
                            <img src="{{ $recSong['album_art'] }}" class="rec-song-art">
                            <span class="rec-song-text">{{ $recSong['title'] }} - {{ \Illuminate\Support\Str::limit($recSong['artist'], 10) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="action-buttons">
                <div class="bookshelf-wrapper">
                    <button type="button" class="btn-bookshelf" id="bookshelfBtn">
                        {{ $review->bookshelf_status ?? 'Add Bookshelf' }}
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                    <div class="bookshelf-dropdown" id="bookshelfDropdown">
                        @forelse($userBookshelves as $shelf)
                            <div class="dropdown-item {{ ($review->bookshelf_status ?? '') === $shelf->name ? 'active' : '' }}">
                                <span>{{ $shelf->name }}</span>
                            </div>
                        @empty
                            <div class="dropdown-item disabled" style="font-size: 0.95rem; color: #8D6E63; pointer-events: none; cursor: default; background: transparent; padding: 12px 18px;">
                                <span>No bookshelves found</span>
                            </div>
                        @endforelse
                    </div>
                </div>
                    <button type="submit" class="btn-send">SEND</button>
                </div>

            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/add_review.js') }}"></script>
@endpush
