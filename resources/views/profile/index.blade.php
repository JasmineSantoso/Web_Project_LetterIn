@extends('layouts.app')

@section('title', 'LetterIn - User Profile')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')
    <main class="main-container">
        
        {{-- ── Profile Header ─────────────────────────────── --}}
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

        {{-- ── Stats Bar ───────────────────────────────────── --}}
        <section class="stats-bar">
            <div class="stat-item">
                <span class="stat-title">Total Book</span>
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

        {{-- ── My Bookshelves ───────────────────────────────── --}}
        <section class="bordered-section" id="bookshelves-section">
            <h2 class="section-label">MY BOOKSHELVES</h2>
            <div style="display: flex; justify-content: flex-end; margin-bottom: 12px;">
                <button id="btn-new-shelf" onclick="openNewShelfModal()" style="background-color: #5D4037; color: white; border: none; padding: 7px 18px; border-radius: 20px; font-size: 0.85rem; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: background 0.2s;">
                    <i class="fa-solid fa-plus"></i> New Shelf
                </button>
            </div>

            <div id="shelves-container" style="display: flex; flex-wrap: wrap; gap: 16px;">
                @forelse($bookshelves as $shelf)
                    <div class="shelf-card" data-shelf-id="{{ $shelf->id }}"
                         onclick="openShelfDetail({{ $shelf->id }}, '{{ addslashes($shelf->name) }}')"
                         style="background: #EDE0D4; border-radius: 12px; padding: 14px 18px; min-width: 160px; max-width: 200px; flex: 1; position: relative; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1.5px solid rgba(93,64,55,0.12); cursor: pointer; transition: transform 0.18s, box-shadow 0.18s;"
                         onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 6px 18px rgba(0,0,0,0.14)'"
                         onmouseout="this.style.transform='';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)'">
                        {{-- Cover thumbnail --}}
                        @if($shelf->books->isNotEmpty())
                            <img src="{{ (str_starts_with($shelf->books->first()->cover_image ?? '', 'http') || empty($shelf->books->first()->cover_image)) ? ($shelf->books->first()->cover_image ?: asset('images/cover1.jpg')) : asset('images/' . $shelf->books->first()->cover_image) }}"
                                 style="width: 100%; height: 110px; object-fit: cover; border-radius: 6px; margin-bottom: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.15); pointer-events:none;">
                        @else
                            <div style="width: 100%; height: 110px; background: linear-gradient(135deg, #A1887F, #6D4C41); border-radius: 6px; margin-bottom: 10px; display: flex; align-items: center; justify-content: center; pointer-events:none;">
                                <i class="fa-solid fa-book-open" style="font-size: 2.2rem; color: rgba(255,255,255,0.7);"></i>
                            </div>
                        @endif
                        <p style="font-family: var(--font-serif); font-weight: bold; font-size: 0.95rem; color: #4E342E; margin: 0 0 4px 0; word-break: break-word; pointer-events:none;">{{ $shelf->name }}</p>
                        <p style="font-size: 0.78rem; color: #8D6E63; margin: 0; pointer-events:none;">{{ $shelf->books->count() }} {{ $shelf->books->count() === 1 ? 'book' : 'books' }}</p>
                        {{-- Delete button --}}
                        <button onclick="event.stopPropagation(); confirmDeleteShelf({{ $shelf->id }}, this)" title="Delete shelf"
                                style="position: absolute; top: 8px; right: 8px; background: none; border: none; color: #B71C1C; cursor: pointer; font-size: 0.8rem; opacity: 0.5; transition: opacity 0.2s;"
                                onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.5'">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                @empty
                    <p id="empty-shelf-msg" style="color: #8D6E63; font-style: italic; margin: 10px 0;">No bookshelves yet. Create your first shelf!</p>
                @endforelse
            </div>
        </section>

        {{-- ── Create New Shelf Modal ───────────────────────── --}}
        <div id="new-shelf-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
            <div style="background: #FFF8EF; border-radius: 16px; padding: 32px 28px; width: 360px; max-width: 90vw; box-shadow: 0 20px 60px rgba(0,0,0,0.25); position: relative;">
                <button onclick="closeNewShelfModal()" style="position: absolute; top: 12px; right: 16px; background: none; border: none; font-size: 1.4rem; color: #8D6E63; cursor: pointer;">&times;</button>
                <h3 style="font-family: var(--font-serif); color: #4E342E; margin-bottom: 18px;">📚 Create New Shelf</h3>
                <input id="new-shelf-name" type="text" placeholder="e.g. Fantasy Reads, Summer 2025..." maxlength="100"
                       style="width: 100%; padding: 10px 14px; border: 1.5px solid #BCAAA4; border-radius: 10px; font-size: 0.95rem; outline: none; background: #fff; color: #4E342E; margin-bottom: 16px; box-sizing: border-box;"
                       onkeydown="if(event.key==='Enter') submitNewShelf()">
                <button onclick="submitNewShelf()" style="width: 100%; background: #5D4037; color: white; border: none; padding: 10px; border-radius: 10px; font-size: 0.95rem; font-weight: bold; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#4E342E'" onmouseout="this.style.background='#5D4037'">
                    <i class="fa-solid fa-plus"></i> Create Shelf
                </button>
                <p id="new-shelf-error" style="color: #B71C1C; font-size: 0.82rem; margin-top: 10px; display: none;"></p>
            </div>
        </div>

        {{-- ── Shelf Detail Modal ──────────────────────────────── --}}
        <div id="shelf-detail-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.55); z-index: 9999; justify-content: center; align-items: center; padding: 20px;">
            <div style="background: #FFF8EF; border-radius: 20px; width: 620px; max-width: 95vw; max-height: 85vh; display: flex; flex-direction: column; box-shadow: 0 24px 70px rgba(0,0,0,0.3); overflow: hidden; position: relative;">
                {{-- Modal Header --}}
                <div style="background: linear-gradient(135deg, #6D4C41, #4E342E); padding: 22px 28px; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fa-solid fa-book-bookmark" style="color: #FFF8EF; font-size: 1.3rem;"></i>
                        <div>
                            <h3 id="shelf-detail-title" style="color: #FFF8EF; font-family: var(--font-serif); margin: 0; font-size: 1.25rem;">Shelf Name</h3>
                            <p id="shelf-detail-count" style="color: rgba(255,248,239,0.7); margin: 2px 0 0 0; font-size: 0.82rem;">0 books</p>
                        </div>
                    </div>
                    <button onclick="closeShelfDetail()" style="background: rgba(255,255,255,0.15); border: none; color: #FFF8EF; width: 34px; height: 34px; border-radius: 50%; font-size: 1.2rem; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">&times;</button>
                </div>
                {{-- Modal Body --}}
                <div id="shelf-detail-body" style="overflow-y: auto; padding: 24px 28px; flex: 1; min-height: 120px;">
                    <div id="shelf-detail-loading" style="text-align: center; padding: 40px; color: #8D6E63;">
                        <i class="fa-solid fa-spinner fa-spin" style="font-size: 2rem; margin-bottom: 12px;"></i>
                        <p>Loading books...</p>
                    </div>
                    <div id="shelf-detail-books" style="display: none;"></div>
                    <div id="shelf-detail-empty" style="display: none; text-align: center; padding: 40px 20px; color: #8D6E63;">
                        <i class="fa-solid fa-book-open" style="font-size: 3rem; margin-bottom: 14px; opacity: 0.3;"></i>
                        <p style="font-size: 1rem; font-family: var(--font-serif);">This shelf is empty.</p>
                        <p style="font-size: 0.85rem; margin-top: 6px;">Add books from any book detail page.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Custom Confirm Dialog ───────────────────────────── --}}
        <div id="confirm-dialog" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 10000; justify-content: center; align-items: center;">
            <div style="background: #FFF8EF; border-radius: 16px; padding: 28px 26px; width: 340px; max-width: 90vw; box-shadow: 0 16px 50px rgba(0,0,0,0.25); text-align: center;">
                <div style="width: 52px; height: 52px; background: #FFEBEE; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
                    <i class="fa-solid fa-triangle-exclamation" style="color: #B71C1C; font-size: 1.4rem;"></i>
                </div>
                <h4 id="confirm-title" style="font-family: var(--font-serif); color: #4E342E; margin: 0 0 8px 0; font-size: 1.1rem;">Are you sure?</h4>
                <p id="confirm-message" style="color: #6D4C41; font-size: 0.88rem; margin: 0 0 24px 0; line-height: 1.5;">This action cannot be undone.</p>
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <button id="confirm-cancel-btn" onclick="closeConfirmDialog()" style="flex: 1; padding: 10px; border: 1.5px solid #BCAAA4; background: transparent; color: #6D4C41; border-radius: 10px; font-size: 0.9rem; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#F3E5D0'" onmouseout="this.style.background=''" >Cancel</button>
                    <button id="confirm-ok-btn" style="flex: 1; padding: 10px; background: #B71C1C; color: white; border: none; border-radius: 10px; font-size: 0.9rem; font-weight: bold; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#7f0000'" onmouseout="this.style.background='#B71C1C'">Delete</button>
                </div>
            </div>
        </div>

        {{-- ── Toast Notification ──────────────────────────────── --}}
        <div id="profile-toast" style="position: fixed; bottom: 28px; right: 28px; padding: 13px 22px; border-radius: 12px; color: #fff; font-weight: bold; font-size: 0.9rem; box-shadow: 0 6px 24px rgba(0,0,0,0.2); z-index: 10001; opacity: 0; transform: translateY(16px); transition: opacity 0.35s ease, transform 0.35s ease; pointer-events: none; max-width: 300px;"></div>

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
                    <p style="color: #777; margin-top: 10px;">No reviews yet. <a href="{{ route('browse') }}" style="color: #5D4037; font-weight: bold;">Browse books</a> to write your first review!</p>
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
@endsection

@push('scripts')
    <script>
        const CSRF = '{{ csrf_token() }}';
        const SHELF_STORE_URL = '{{ route("bookshelf.store") }}';

        /* ─── Toast ─────────────────────────────────────────────── */
        function showToast(msg, color = '#388E3C') {
            const t = document.getElementById('profile-toast');
            t.textContent = msg;
            t.style.background = color;
            t.style.opacity = '1';
            t.style.transform = 'translateY(0)';
            clearTimeout(t._timer);
            t._timer = setTimeout(() => {
                t.style.opacity = '0';
                t.style.transform = 'translateY(16px)';
            }, 3000);
        }

        /* ─── New Shelf Modal ────────────────────────────────────── */
        function openNewShelfModal() {
            document.getElementById('new-shelf-name').value = '';
            document.getElementById('new-shelf-error').style.display = 'none';
            document.getElementById('new-shelf-modal').style.display = 'flex';
            setTimeout(() => document.getElementById('new-shelf-name').focus(), 80);
        }
        function closeNewShelfModal() {
            document.getElementById('new-shelf-modal').style.display = 'none';
        }
        document.getElementById('new-shelf-modal').addEventListener('click', function(e) {
            if (e.target === this) closeNewShelfModal();
        });

        function submitNewShelf() {
            const name   = document.getElementById('new-shelf-name').value.trim();
            const errEl  = document.getElementById('new-shelf-error');
            if (!name) { errEl.textContent = 'Please enter a shelf name.'; errEl.style.display = 'block'; return; }

            fetch(SHELF_STORE_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ name })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    closeNewShelfModal();
                    addShelfCard(data.shelf);
                    const emptyMsg = document.getElementById('empty-shelf-msg');
                    if (emptyMsg) emptyMsg.remove();
                    showToast('✔ Shelf "' + data.shelf.name + '" created!');
                } else {
                    errEl.textContent = data.message || 'Error creating shelf.';
                    errEl.style.display = 'block';
                }
            })
            .catch(() => { errEl.textContent = 'An error occurred.'; errEl.style.display = 'block'; });
        }

        function addShelfCard(shelf) {
            const container = document.getElementById('shelves-container');
            const card = document.createElement('div');
            card.className = 'shelf-card';
            card.dataset.shelfId = shelf.id;
            card.style.cssText = 'background:#EDE0D4;border-radius:12px;padding:14px 18px;min-width:160px;max-width:200px;flex:1;position:relative;box-shadow:0 2px 8px rgba(0,0,0,0.08);border:1.5px solid rgba(93,64,55,0.12);cursor:pointer;opacity:0;transform:scale(0.9);transition:all 0.3s ease;';
            card.onclick = () => openShelfDetail(shelf.id, shelf.name);
            card.onmouseover = () => { card.style.transform = 'translateY(-3px)'; card.style.boxShadow = '0 6px 18px rgba(0,0,0,0.14)'; };
            card.onmouseout  = () => { card.style.transform = ''; card.style.boxShadow = '0 2px 8px rgba(0,0,0,0.08)'; };
            card.innerHTML = `
                <div style="width:100%;height:110px;background:linear-gradient(135deg,#A1887F,#6D4C41);border-radius:6px;margin-bottom:10px;display:flex;align-items:center;justify-content:center;pointer-events:none;">
                    <i class="fa-solid fa-book-open" style="font-size:2.2rem;color:rgba(255,255,255,0.7);"></i>
                </div>
                <p style="font-family:var(--font-serif);font-weight:bold;font-size:0.95rem;color:#4E342E;margin:0 0 4px 0;pointer-events:none;">${shelf.name}</p>
                <p style="font-size:0.78rem;color:#8D6E63;margin:0;pointer-events:none;">0 books</p>
                <button onclick="event.stopPropagation(); confirmDeleteShelf(${shelf.id}, this)" title="Delete shelf"
                        style="position:absolute;top:8px;right:8px;background:none;border:none;color:#B71C1C;cursor:pointer;font-size:0.8rem;opacity:0.5;transition:opacity 0.2s;"
                        onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.5'">
                    <i class="fa-solid fa-trash-can"></i>
                </button>`;
            container.appendChild(card);
            requestAnimationFrame(() => { card.style.opacity = '1'; card.style.transform = 'scale(1)'; });
        }

        /* ─── Shelf Detail Modal ─────────────────────────────────── */
        let _currentShelfId = null;

        function openShelfDetail(shelfId, shelfName) {
            _currentShelfId = shelfId;
            document.getElementById('shelf-detail-title').textContent = shelfName;
            document.getElementById('shelf-detail-count').textContent = 'Loading…';
            document.getElementById('shelf-detail-loading').style.display = 'block';
            document.getElementById('shelf-detail-books').style.display  = 'none';
            document.getElementById('shelf-detail-books').innerHTML      = '';
            document.getElementById('shelf-detail-empty').style.display  = 'none';
            document.getElementById('shelf-detail-modal').style.display  = 'flex';

            fetch(`/bookshelves/${shelfId}/books-list`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF }
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('shelf-detail-loading').style.display = 'none';
                const books = data.books || [];
                document.getElementById('shelf-detail-count').textContent = books.length + (books.length === 1 ? ' book' : ' books');

                if (books.length === 0) {
                    document.getElementById('shelf-detail-empty').style.display = 'block';
                    return;
                }

                const booksEl = document.getElementById('shelf-detail-books');
                booksEl.style.display = 'grid';
                booksEl.style.cssText = 'display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:18px;';

                books.forEach(book => {
                    const coverSrc = book.cover_image
                        ? (book.cover_image.startsWith('http') ? book.cover_image : '/images/' + book.cover_image)
                        : '/images/cover1.jpg';

                    const bookLink = book.google_id
                        ? `/book/${book.google_id}`
                        : `/book/${book.id}`;

                    const item = document.createElement('div');
                    item.style.cssText = 'text-align:center;position:relative;';
                    item.innerHTML = `
                        <a href="${bookLink}" style="text-decoration:none;">
                            <img src="${coverSrc}" alt="${book.title}"
                                 style="width:100%;height:160px;object-fit:cover;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.18);transition:transform 0.2s;"
                                 onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform=''">
                            <p style="font-family:var(--font-serif);font-size:0.82rem;font-weight:bold;color:#4E342E;margin:8px 0 2px 0;line-height:1.3;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">${book.title}</p>
                            <p style="font-size:0.72rem;color:#8D6E63;margin:0;">${book.author}</p>
                        </a>
                        <button onclick="removeBookFromShelf(${shelfId}, ${book.id}, this.closest('div'))"
                                title="Remove from shelf"
                                style="position:absolute;top:6px;right:6px;background:rgba(183,28,28,0.85);border:none;color:#fff;width:24px;height:24px;border-radius:50%;font-size:0.75rem;cursor:pointer;display:flex;align-items:center;justify-content:center;opacity:0;transition:opacity 0.2s;"
                                onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                            <i class="fa-solid fa-xmark"></i>
                        </button>`;
                    item.querySelector('img').parentElement.addEventListener('mouseover', () => item.querySelector('button').style.opacity = '1');
                    item.querySelector('img').parentElement.addEventListener('mouseout', () => item.querySelector('button').style.opacity = '0');
                    booksEl.appendChild(item);
                });
                booksEl.style.display = 'grid';
            })
            .catch(() => {
                document.getElementById('shelf-detail-loading').style.display = 'none';
                document.getElementById('shelf-detail-empty').style.display = 'block';
                document.getElementById('shelf-detail-count').textContent = '—';
            });
        }

        function closeShelfDetail() {
            document.getElementById('shelf-detail-modal').style.display = 'none';
            _currentShelfId = null;
        }
        document.getElementById('shelf-detail-modal').addEventListener('click', function(e) {
            if (e.target === this) closeShelfDetail();
        });

        function removeBookFromShelf(shelfId, bookId, itemEl) {
            fetch(`/bookshelves/${shelfId}/books/${bookId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    itemEl.style.transition = 'all 0.3s ease';
                    itemEl.style.opacity = '0';
                    itemEl.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        itemEl.remove();
                        const remaining = document.getElementById('shelf-detail-books').children.length;
                        document.getElementById('shelf-detail-count').textContent = remaining + (remaining === 1 ? ' book' : ' books');
                        if (remaining === 0) {
                            document.getElementById('shelf-detail-books').style.display = 'none';
                            document.getElementById('shelf-detail-empty').style.display = 'block';
                        }
                        // Update the shelf card book count on the main page
                        const card = document.querySelector(`.shelf-card[data-shelf-id="${shelfId}"]`);
                        if (card) {
                            const countEl = card.querySelectorAll('p')[1];
                            if (countEl) countEl.textContent = remaining + (remaining === 1 ? ' book' : ' books');
                        }
                    }, 300);
                    showToast('Book removed from shelf.', '#5D4037');
                }
            });
        }

        /* ─── Custom Confirm Dialog ──────────────────────────────── */
        let _confirmCallback = null;

        function showConfirmDialog(title, message, okLabel, callback) {
            document.getElementById('confirm-title').textContent   = title;
            document.getElementById('confirm-message').textContent = message;
            document.getElementById('confirm-ok-btn').textContent  = okLabel;
            _confirmCallback = callback;
            document.getElementById('confirm-dialog').style.display = 'flex';
        }
        function closeConfirmDialog() {
            document.getElementById('confirm-dialog').style.display = 'none';
            _confirmCallback = null;
        }
        document.getElementById('confirm-ok-btn').addEventListener('click', () => {
            if (_confirmCallback) _confirmCallback();
            closeConfirmDialog();
        });
        document.getElementById('confirm-dialog').addEventListener('click', function(e) {
            if (e.target === this) closeConfirmDialog();
        });

        /* ─── Delete Shelf ───────────────────────────────────────── */
        function confirmDeleteShelf(shelfId, btn) {
            showConfirmDialog(
                'Delete Shelf?',
                'All books will be removed from this shelf. This action cannot be undone.',
                'Delete',
                () => doDeleteShelf(shelfId, btn)
            );
        }

        function doDeleteShelf(shelfId, btn) {
            fetch(`/bookshelves/${shelfId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const card = btn.closest('.shelf-card');
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        card.remove();
                        if (document.querySelectorAll('.shelf-card').length === 0) {
                            document.getElementById('shelves-container').innerHTML =
                                '<p id="empty-shelf-msg" style="color:#8D6E63;font-style:italic;margin:10px 0;">No bookshelves yet. Create your first shelf!</p>';
                        }
                    }, 300);
                    showToast('Shelf deleted.', '#5D4037');
                }
            });
        }
    </script>
@endpush

