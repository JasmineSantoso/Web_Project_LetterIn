@extends('layouts.app')

@section('title', 'LetterIn - Browse Books')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/browse.css') }}">
@endpush

@section('content')
    <section class="browse-hero">
        <h1 class="page-title">Every read leaves a letter in</h1>
        <form action="{{ route('browse') }}" method="GET" class="main-search-container" style="margin-top: 20px;">
            <input type="text" name="category" value="{{ request('category') }}" placeholder="Search category or keyword" style="border: 1px solid #674636;">
            <button type="submit" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #674636; font-size: 1.2rem; padding: 0;">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </section>

    <section class="filter-bar">
        <form action="{{ route('browse') }}" method="GET" id="filter-form">
            <input type="hidden" name="category" value="{{ request('category') }}">
            <div class="filter-buttons">
                <!-- Genre Dropdown -->
                <div class="filter-dropdown" id="dropdown-genre">
                    <button type="button" class="filter-btn" onclick="toggleDropdown('dropdown-genre')">Genre <i class="fa-solid fa-chevron-down"></i></button>
                    <div class="dropdown-content">
                        <label><input type="checkbox" name="genre[]" value="Fiction" onchange="document.getElementById('filter-form').submit()" {{ in_array('Fiction', request('genre', [])) ? 'checked' : '' }}> Fiction</label>
                        <label><input type="checkbox" name="genre[]" value="Romance" onchange="document.getElementById('filter-form').submit()" {{ in_array('Romance', request('genre', [])) ? 'checked' : '' }}> Romance</label>
                        <label><input type="checkbox" name="genre[]" value="Fantasy" onchange="document.getElementById('filter-form').submit()" {{ in_array('Fantasy', request('genre', [])) ? 'checked' : '' }}> Fantasy</label>
                        <label><input type="checkbox" name="genre[]" value="Mystery" onchange="document.getElementById('filter-form').submit()" {{ in_array('Mystery', request('genre', [])) ? 'checked' : '' }}> Mystery</label>
                        <label><input type="checkbox" name="genre[]" value="History" onchange="document.getElementById('filter-form').submit()" {{ in_array('History', request('genre', [])) ? 'checked' : '' }}> History</label>
                        <label><input type="checkbox" name="genre[]" value="Science" onchange="document.getElementById('filter-form').submit()" {{ in_array('Science', request('genre', [])) ? 'checked' : '' }}> Science</label>
                        <label><input type="checkbox" name="genre[]" value="Biography" onchange="document.getElementById('filter-form').submit()" {{ in_array('Biography', request('genre', [])) ? 'checked' : '' }}> Biography</label>
                    </div>
                </div>

                <!-- Publish Dropdown -->
                <div class="filter-dropdown" id="dropdown-publish">
                    <button type="button" class="filter-btn" onclick="toggleDropdown('dropdown-publish')">Publish <i class="fa-solid fa-chevron-down"></i></button>
                    <div class="dropdown-content">
                        <div class="publish-grid">
                            <div>
                                <span>From</span><br>
                                <input type="number" name="publish_from" value="{{ request('publish_from') }}">
                            </div>
                            <div>
                                <span>To</span><br>
                                <input type="number" name="publish_to" value="{{ request('publish_to') }}">
                            </div>
                        </div>
                        <button type="submit" style="margin-top: 10px; width: 100%; padding: 4px; background: #FFF3E0; border: none; cursor: pointer; border-radius: 2px; font-weight: bold; color: var(--card-brown);">Apply</button>
                    </div>
                </div>

                <!-- Rating Dropdown -->
                <div class="filter-dropdown" id="dropdown-rating">
                    <button type="button" class="filter-btn" onclick="toggleDropdown('dropdown-rating')">Rating <i class="fa-solid fa-chevron-down"></i></button>
                    <div class="dropdown-content rating-grid">
                        <label class="rating-label"><input type="radio" name="rating" value="5" onchange="document.getElementById('filter-form').submit()" {{ request('rating') == '5' ? 'checked' : '' }}> <span><i class="fa-solid fa-star"></i> 5</span></label>
                        <label class="rating-label"><input type="radio" name="rating" value="4" onchange="document.getElementById('filter-form').submit()" {{ request('rating') == '4' ? 'checked' : '' }}> <span>&ge;<i class="fa-solid fa-star"></i> 4</span></label>
                        <label class="rating-label"><input type="radio" name="rating" value="3" onchange="document.getElementById('filter-form').submit()" {{ request('rating') == '3' ? 'checked' : '' }}> <span>&ge;<i class="fa-solid fa-star"></i> 3</span></label>
                        <label class="rating-label"><input type="radio" name="rating" value="2" onchange="document.getElementById('filter-form').submit()" {{ request('rating') == '2' ? 'checked' : '' }}> <span>&ge;<i class="fa-solid fa-star"></i> 2</span></label>
                        <label class="rating-label" style="grid-column: span 2; text-align: center;"><input type="radio" name="rating" value="1" onchange="document.getElementById('filter-form').submit()" {{ request('rating') == '1' ? 'checked' : '' }}> <span>&ge;<i class="fa-solid fa-star"></i> 1</span></label>
                    </div>
                </div>
            </div>
        </form>
    </section>

    <section class="browse-list">
        @forelse ($books as $book)
            @php
                $volumeInfo = $book['volumeInfo'] ?? [];
                $authorsArray = $volumeInfo['authors'] ?? [];
                
                // Skip if no authors (Unknown Author)
                if (empty($authorsArray)) {
                    continue;
                }
                
                $authors = implode(', ', $authorsArray);
                $bookId = $book['id'] ?? null;
                $thumbnail = $volumeInfo['imageLinks']['thumbnail'] ?? 'https://placehold.co/150x220?text=No+Cover';
                $title = $volumeInfo['title'] ?? 'Unknown Title';
                $publishedDate = $volumeInfo['publishedDate'] ?? '';
                $year = $publishedDate ? substr($publishedDate, 0, 4) : 'N/A';
                $rating = $volumeInfo['averageRating'] ?? 0;
            @endphp

        <div class="browse-card">
            @if($bookId)
            <a href="{{ route('book.details', ['id' => $bookId]) }}" style="display:block;">
                <img src="{{ $thumbnail }}" alt="{{ $title }}" class="browse-cover">
            </a>
            @else
            <img src="{{ $thumbnail }}" alt="{{ $title }}" class="browse-cover">
            @endif
            
            <div class="browse-info">
                <div class="info-header">
                    @if($bookId)
                    <a href="{{ route('book.details', ['id' => $bookId]) }}" style="text-decoration:none; color:inherit;">
                        <h2 class="browse-title">{{ $title }} <span class="browse-year">{{ $year }}</span></h2>
                    </a>
                    @else
                    <h2 class="browse-title">{{ $title }} <span class="browse-year">{{ $year }}</span></h2>
                    @endif
                    <p class="browse-author">{{ $authors }}</p>
                </div>
                <div class="browse-rating">
                    @php
                        $fullStars = floor($rating);
                        $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
                        $emptyStars = 5 - $fullStars - $halfStar;
                        if ($emptyStars < 0) $emptyStars = 0;
                    @endphp
                    @for ($i = 0; $i < $fullStars; $i++)
                        <i class="fa-solid fa-star"></i>
                    @endfor
                    @if ($halfStar)
                        <i class="fa-solid fa-star-half-stroke"></i>
                    @endif
                    @for ($i = 0; $i < $emptyStars; $i++)
                        <i class="fa-regular fa-star"></i>
                    @endfor
                    <span class="rating-text">{{ $rating > 0 ? $rating : 'No' }} rating</span>
                </div>
            </div>

            <div class="action-box">
                @auth
                    <div class="action-item dropdown">
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
                    <label class="action-item favorite-btn">
                        <input type="checkbox" class="fav-toggle">
                        <span class="fav-text add">Add Favorite</span>
                        <span class="fav-text remove">Remove Favorite</span>
                        <i class="fa-regular fa-heart"></i>
                        <i class="fa-solid fa-heart"></i>
                    </label>
                    <div class="action-item">
                        <span>Add Bookshelf</span> <i class="fa-regular fa-square-check"></i>
                    </div>
                @else
                    <div class="action-item" onclick="window.location.href='{{ route('signin') }}'">
                        <span>Sign in to track</span> <i class="fa-regular fa-bookmark"></i>
                    </div>
                @endauth
            </div>
        </div>
        @empty
            <div style="text-align: center; padding: 50px; color: #674636; width: 100%;">
                <p>No books found for this category.</p>
            </div>
        @endforelse
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/home_signed.js') }}"></script>
    <script>
        function toggleDropdown(id) {
            // Close all other dropdowns
            const dropdowns = document.querySelectorAll('.filter-dropdown');
            dropdowns.forEach(dropdown => {
                if (dropdown.id !== id) {
                    dropdown.classList.remove('active');
                }
            });
            // Toggle the clicked one
            document.getElementById(id).classList.toggle('active');
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.matches('.filter-btn') && !event.target.closest('.filter-btn') && !event.target.closest('.dropdown-content')) {
                const dropdowns = document.querySelectorAll('.filter-dropdown');
                dropdowns.forEach(dropdown => {
                    dropdown.classList.remove('active');
                });
            }
        }

        // Handle Reading Status option selection in browse cards
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
            const openDetails = document.querySelectorAll('.action-box details[open]');
            openDetails.forEach(details => {
                if (!details.contains(e.target)) {
                    details.removeAttribute('open');
                }
            });
        });
    </script>
@endpush
