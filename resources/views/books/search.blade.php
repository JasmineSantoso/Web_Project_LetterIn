@extends('layouts.app')

@section('title', 'LetterIn - Search Result')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/searchbook_signed.css') }}">
    <style>
        .search-section-wrapper {
            background-color: #F7EED3;
            padding: 30px 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .search-bar-inner {
            display: flex;
            align-items: center;
            background: white;
            border-radius: 30px;
            padding: 10px 20px;
            width: 60%;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border: 1px solid #674636;
        }
        .search-bar-inner input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 1rem;
            color: #674636;
        }
        .search-bar-inner button {
            background: none;
            border: none;
            cursor: pointer;
            color: #674636;
            font-size: 1.2rem;
        }
    </style>
@endpush

@section('content')
    <div class="search-section-wrapper">
        <form action="{{ route('search') }}" method="GET" style="width: 100%; display: flex; justify-content: center;">
            <div class="search-bar-inner">
                <input type="text" name="q" value="{{ $query }}" placeholder="Search by title, author, or ISBN" required>
                <button type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </form>
    </div>

    <section class="page-title-section">
        <h1>Book result for '{{ $query }}'</h1>
    </section>

    <section class="filter-bar">
        <form action="{{ route('search') }}" method="GET" id="filter-form">
            <input type="hidden" name="q" value="{{ $query }}">
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

    <section class="result-list">
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
                $ratingsCount = $volumeInfo['ratingsCount'] ?? 0;
            @endphp
            <div class="book-card">
                @if($bookId)
                <a href="{{ route('book.details', ['id' => $bookId]) }}" style="display:block;">
                    <img src="{{ $thumbnail }}" alt="{{ $title }}" class="book-cover">
                </a>
                @else
                <img src="{{ $thumbnail }}" alt="{{ $title }}" class="book-cover">
                @endif
                
                <div class="book-info">
                    <div class="info-top">
                        @if($bookId)
                        <a href="{{ route('book.details', ['id' => $bookId]) }}" style="text-decoration:none; color:inherit;">
                            <h2 class="book-title">{{ $title }} <span class="book-year">{{ $year }}</span></h2>
                        </a>
                        @else
                        <h2 class="book-title">{{ $title }} <span class="book-year">{{ $year }}</span></h2>
                        @endif
                        <p class="book-author">{{ $authors }}</p>
                    </div>
                    <div class="book-rating">
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
                        <span class="rating-text">{{ $rating > 0 ? number_format($rating, 1) : 'No' }} rating ({{ $ratingsCount }} {{ $ratingsCount == 1 ? 'review' : 'reviews' }})</span>
                    </div>
                </div>

                <div class="action-box">
                    @if(Auth::guest() || (isset($forceGuestHeader) && $forceGuestHeader))
                        <div class="action-item" onclick="window.location.href='{{ route('signin') }}'">
                            <span>Sign in to track</span> <i class="fa-regular fa-bookmark"></i>
                        </div>
                    @else
                        @php
                            $statusVal = $userBookStatuses[$bookId] ?? 'to_read';
                            $statusText = 'To Read';
                            if ($statusVal === 'currently_reading') {
                                $statusText = 'Currently Read';
                            } elseif ($statusVal === 'done_reading') {
                                $statusText = 'Done Read';
                            }
                            $isFavorited = in_array($bookId, $favoriteBookIds);
                        @endphp
                        <div class="action-item dropdown" data-id="{{ $bookId }}">
                            <details>
                                <summary style="display: flex; justify-content: space-between; align-items: center; width: 100%; cursor: pointer;">
                                    <span>{{ $statusText }}</span>
                                    <i class="fa-solid fa-chevron-down"></i>
                                </summary>
                                <div class="dropdown-menu">
                                    <div class="dropdown-option {{ $statusVal === 'to_read' ? 'active' : '' }}" data-status="to_read">To Read</div>
                                    <div class="dropdown-option {{ $statusVal === 'currently_reading' ? 'active' : '' }}" data-status="currently_reading">Currently Read</div>
                                    <div class="dropdown-option {{ $statusVal === 'done_reading' ? 'active' : '' }}" data-status="done_reading">Done Read</div>
                                </div>
                            </details>
                        </div>
                        <label class="action-item favorite-btn">
                            <input type="checkbox" class="fav-toggle" data-id="{{ $bookId }}" {{ $isFavorited ? 'checked' : '' }}>
                            <span class="fav-text add">Add Favorite</span>
                            <span class="fav-text remove">Remove Favorite</span>
                            <i class="fa-regular fa-heart"></i>
                            <i class="fa-solid fa-heart"></i>
                        </label>
                        <div class="action-item dropdown bookshelf-dropdown-wrapper" data-id="{{ $bookId }}">
                            <details style="width: 100%; position: relative;">
                                <summary style="display: flex; justify-content: space-between; align-items: center; width: 100%; cursor: pointer;">
                                    <span class="bookshelf-label">Add Bookshelf</span>
                                    <i class="fa-regular fa-square-check"></i>
                                </summary>
                                <div class="dropdown-menu bookshelf-dropdown-menu" style="background-color: #FFF8EF; border: 1.5px solid rgba(93,64,55,0.18); border-radius: 10px; box-shadow: 0 8px 25px rgba(0,0,0,0.15); z-index: 200; overflow: hidden; margin-top: 4px; padding: 0; width: 220px; top: 32px; right: 0;">
                                    <div class="bookshelf-list">
                                        @forelse($userBookshelves as $shelf)
                                            <div class="bookshelf-option" data-shelf-id="{{ $shelf->id }}"
                                                 data-shelf-name="{{ $shelf->name }}"
                                                 style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; justify-content: space-between; font-size: 0.88rem; color: #4E342E; transition: background 0.15s; border-bottom: 1px solid rgba(93,64,55,0.07);"
                                                 onmouseover="this.style.background='#F3E5D0'" onmouseout="this.style.background=''">
                                                <span><i class="fa-regular fa-bookmark" style="margin-right: 8px; color: #8D6E63;"></i>{{ $shelf->name }}</span>
                                                <span style="font-size: 0.75rem; color: #BCAAA4;">{{ $shelf->books_count }} bk</span>
                                            </div>
                                        @empty
                                            <div class="bookshelf-empty-hint" style="padding: 10px 16px; font-size: 0.85rem; color: #8D6E63; font-style: italic;">No shelves yet.</div>
                                        @endforelse
                                    </div>
                                    <div class="bookshelf-new-row" style="padding: 8px 10px; border-top: 1px solid rgba(93,64,55,0.1);">
                                        <div class="bookshelf-new-trigger" style="display: flex; align-items: center; gap: 8px; cursor: pointer; color: #5D4037; font-size: 0.87rem; font-weight: bold; padding: 4px 6px; border-radius: 6px; transition: background 0.15s;" onmouseover="this.style.background='#F3E5D0'" onmouseout="this.style.background=''">
                                            <i class="fa-solid fa-plus"></i> Create New Shelf
                                        </div>
                                        <div class="bookshelf-new-input-row" style="display: none; margin-top: 6px;">
                                            <input class="bookshelf-new-name" type="text" placeholder="Shelf name..." maxlength="100"
                                                   style="width: 100%; padding: 7px 10px; border: 1.5px solid #BCAAA4; border-radius: 7px; font-size: 0.85rem; outline: none; color: #4E342E; box-sizing: border-box; margin-bottom: 6px;">
                                            <div style="display: flex; gap: 6px;">
                                                <button class="btn-create-add" style="flex: 1; background: #5D4037; color: white; border: none; padding: 6px; border-radius: 7px; font-size: 0.82rem; font-weight: bold; cursor: pointer;">Create & Add</button>
                                                <button class="btn-cancel-new-shelf" style="background: transparent; color: #8D6E63; border: 1.5px solid #BCAAA4; padding: 6px 10px; border-radius: 7px; font-size: 0.82rem; cursor: pointer;">✕</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </details>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 50px; color: #674636;">
                <p>No books found for '{{ $query }}'. Try another keyword.</p>
            </div>
        @endforelse
    </section>
@endsection

@push('scripts')
    <script>
        function showSearchToast(msg, bg = '#adbda3') {
            let toast = document.getElementById('search-toast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'search-toast';
                toast.style.cssText = 'position:fixed;top:30px;left:50%;transform:translate(-50%, -100px);padding:12px 22px;border-radius:12px;color:#fff;font-weight:bold;font-size:0.9rem;box-shadow:0 10px 25px rgba(0,0,0,0.15);z-index:9999;transition:all 0.5s cubic-bezier(0.68, -0.55, 0.27, 1.55);opacity:0;';
                document.body.appendChild(toast);
            }
            toast.style.background = bg;
            toast.textContent = msg;
            toast.style.opacity = '1';
            toast.style.transform = 'translate(-50%, 0)';
            clearTimeout(toast._timer);
            toast._timer = setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translate(-50%, -100px)';
            }, 3000);
        }

        // Handle Reading Status option selection in search cards
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('dropdown-option')) {
                const option = e.target;
                const statusText = option.textContent.trim();
                const statusVal = option.getAttribute('data-status');
                
                const dropdownEl = option.closest('.action-item');
                if (dropdownEl) {
                    const bookId = dropdownEl.getAttribute('data-id');
                    
                    fetch(`/book/${bookId}/status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ status: statusVal })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            showSearchToast('✔ Status updated to "' + statusText + '"!');
                        } else {
                            alert(data.message || 'Failed to update reading status.');
                        }
                    })
                    .catch(err => {
                        console.error('Error updating reading status:', err);
                        alert('An error occurred.');
                    });
                }
                
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

        // Handle favorite checkbox changes
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('fav-toggle')) {
                const checkbox = e.target;
                const bookId = checkbox.getAttribute('data-id');
                const isChecked = checkbox.checked;
                
                fetch(`/book/${bookId}/favorite`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(r => {
                    if (r.status === 401) {
                        window.location.href = "{{ route('signin') }}";
                        return;
                    }
                    return r.json();
                })
                .then(data => {
                    if (!data) return;
                    if (data.success) {
                        if (data.action === 'added') {
                            showSearchToast('✔ Added to Favorites!');
                            checkbox.checked = true;
                        } else {
                            showSearchToast('✔ Removed from Favorites!');
                            checkbox.checked = false;
                        }
                    } else {
                        checkbox.checked = !isChecked;
                        alert(data.message || 'Failed to update favorites.');
                    }
                })
                .catch(err => {
                    checkbox.checked = !isChecked;
                    console.error('Error updating favorites:', err);
                    alert('An error occurred.');
                });
            }
        });

        // Handle Bookshelf Option click
        document.addEventListener('click', function(e) {
            const option = e.target.closest('.bookshelf-option');
            if (option) {
                const shelfId = option.getAttribute('data-shelf-id');
                const shelfName = option.getAttribute('data-shelf-name');
                const dropdownEl = option.closest('.bookshelf-dropdown-wrapper');
                if (dropdownEl) {
                    const bookId = dropdownEl.getAttribute('data-id');
                    
                    fetch(`/bookshelves/${shelfId}/books`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ book_google_id: bookId })
                    })
                    .then(r => r.json())
                    .then(data => {
                        const details = option.closest('details');
                        if (details) details.removeAttribute('open');
                        
                        if (data.success) {
                            showSearchToast('✔ Added to "' + shelfName + '"!');
                            const label = dropdownEl.querySelector('.bookshelf-label');
                            if (label) label.textContent = shelfName;
                        } else {
                            showSearchToast(data.message || 'Could not add to shelf.', '#d32f2f');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        showSearchToast('An error occurred.', '#d32f2f');
                    });
                }
            }
        });

        // Handle inline new shelf triggers
        document.addEventListener('click', function(e) {
            const trigger = e.target.closest('.bookshelf-new-trigger');
            if (trigger) {
                const row = trigger.nextElementSibling;
                trigger.style.display = 'none';
                if (row) {
                    row.style.display = 'block';
                    const input = row.querySelector('.bookshelf-new-name');
                    if (input) {
                        input.value = '';
                        input.focus();
                    }
                }
            }
        });

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-cancel-new-shelf');
            if (btn) {
                const row = btn.closest('.bookshelf-new-input-row');
                if (row) {
                    row.style.display = 'none';
                    const trigger = row.previousElementSibling;
                    if (trigger) trigger.style.display = 'flex';
                }
            }
        });

        function submitNewShelfFromCard(row) {
            const input = row.querySelector('.bookshelf-new-name');
            const name = input ? input.value.trim() : '';
            if (!name) {
                if (input) input.style.borderColor = '#B71C1C';
                return;
            }
            if (input) input.style.borderColor = '#BCAAA4';
            
            const dropdownEl = row.closest('.bookshelf-dropdown-wrapper');
            if (dropdownEl) {
                const bookId = dropdownEl.getAttribute('data-id');
                
                fetch('/bookshelves', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ name: name, book_google_id: bookId })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        // Dynamically update all bookshelf lists
                        const lists = document.querySelectorAll('.bookshelf-list');
                        lists.forEach(list => {
                            const emptyHint = list.querySelector('.bookshelf-empty-hint');
                            if (emptyHint) emptyHint.remove();
                            
                            const opt = document.createElement('div');
                            opt.className = 'bookshelf-option';
                            opt.dataset.shelfId = data.shelf.id;
                            opt.dataset.shelfName = data.shelf.name;
                            opt.style.cssText = 'padding: 10px 16px; cursor: pointer; display: flex; align-items: center; justify-content: space-between; font-size: 0.88rem; color: #4E342E; border-bottom: 1px solid rgba(93,64,55,0.07);';
                            opt.innerHTML = `<span><i class="fa-regular fa-bookmark" style="margin-right: 8px; color: #8D6E63;"></i>${data.shelf.name}</span><span style="font-size: 0.75rem; color: #BCAAA4;">1 bk</span>`;
                            opt.addEventListener('mouseover', () => opt.style.background = '#F3E5D0');
                            opt.addEventListener('mouseout', () => opt.style.background = '');
                            list.appendChild(opt);
                        });
                        
                        const details = row.closest('details');
                        if (details) details.removeAttribute('open');
                        
                        row.style.display = 'none';
                        const trigger = row.previousElementSibling;
                        if (trigger) trigger.style.display = 'flex';
                        
                        const label = dropdownEl.querySelector('.bookshelf-label');
                        if (label) label.textContent = data.shelf.name;
                        
                        showSearchToast('✔ Shelf created & book added!');
                    } else {
                        showSearchToast(data.message || 'Could not create shelf.', '#d32f2f');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showSearchToast('An error occurred.', '#d32f2f');
                });
            }
        }

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-create-add');
            if (btn) {
                const row = btn.closest('.bookshelf-new-input-row');
                if (row) {
                    submitNewShelfFromCard(row);
                }
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.classList.contains('bookshelf-new-name')) {
                e.preventDefault();
                const row = e.target.closest('.bookshelf-new-input-row');
                if (row) {
                    submitNewShelfFromCard(row);
                }
            }
        });

        function toggleDropdown(id) {
            // Close all other dropdowns
            const dropdowns = document.querySelectorAll('.filter-dropdown');
            dropdowns.forEach(dropdown => {
                if (dropdown.id !== id) {
                    dropdown.classList.remove('active');
                }
            });
            // Toggle the clicked one
            const targetDropdown = document.getElementById(id);
            if (targetDropdown) {
                targetDropdown.classList.toggle('active');
            }
        }

        // Close details dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            const openDetails = document.querySelectorAll('.action-box details[open]');
            openDetails.forEach(details => {
                if (!details.contains(e.target)) {
                    details.removeAttribute('open');
                }
            });

            // Close filter dropdowns when clicking outside
            if (!e.target.matches('.filter-btn') && !e.target.closest('.filter-btn') && !e.target.closest('.dropdown-content')) {
                const dropdowns = document.querySelectorAll('.filter-dropdown');
                dropdowns.forEach(dropdown => {
                    dropdown.classList.remove('active');
                });
            }
        });
    </script>
@endpush
