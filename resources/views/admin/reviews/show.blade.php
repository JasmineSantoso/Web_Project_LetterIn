@extends('layouts.admin')

@section('title', 'LetterIn - Review Details')

@section('content')
<section class="dashboard-hero">
    <h1 class="page-title">MANAGE REVIEW DETAILS</h1>
</section>

<div class="dashboard-container">
    <div class="detail-card-wrapper">
        <div class="detail-card">
            <!-- Header Section -->
            <div class="detail-card-header">
                <span class="detail-date">
                    <i class="fa-regular fa-calendar-days"></i> 
                    Written on: {{ $review->created_at ? $review->created_at->format('d M Y, H:i') : '-' }}
                </span>
                <span class="status-badge {{ $review->reports_count > 0 ? 'banned' : 'active' }}" style="background-color: {{ $review->reports_count > 0 ? '#E74C3C' : '#27AE60' }};">
                    {{ $review->reports_count }} {{ Str::plural('Report', $review->reports_count) }}
                </span>
            </div>

            <!-- Body Section -->
            <div class="detail-card-body">
                <div class="user-detail-card">
                    <!-- Book Cover & Reviewer Details -->
                    <div class="user-detail-left" style="width: 250px;">
                        <div class="book-cover-large" style="width: 160px; height: 230px; border-radius: 8px; overflow: hidden; border: 2.5px solid var(--btn-brown); margin-bottom: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.12); background-color: var(--box-beige); display: flex; align-items: center; justify-content: center;">
                            @if($review->book && !empty($review->book->cover_image))
                                <img src="{{ Str::startsWith($review->book->cover_image, 'http') ? $review->book->cover_image : asset('images/' . $review->book->cover_image) }}" alt="{{ $review->book->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <i class="fa-solid fa-book" style="font-size: 4rem; color: var(--text-brown);"></i>
                            @endif
                        </div>
                        <h2 style="font-family: var(--font-serif); font-size: 1.4rem; color: var(--text-brown); margin-bottom: 5px; line-height: 1.3;">
                            {{ $review->book ? $review->book->title : 'Unknown Book' }}
                        </h2>
                        <span style="font-weight: 700; color: #8D6E63; font-size: 0.9rem; margin-bottom: 20px; display: block;">
                            by {{ $review->book ? $review->book->author : 'Unknown Author' }}
                        </span>

                        <hr style="border: 0.5px solid rgba(93, 64, 55, 0.15); width: 100%; margin-bottom: 15px;">

                        <div class="user-cell" style="justify-content: center; text-align: left; width: 100%;">
                            <div class="user-avatar-mini" style="width: 35px; height: 35px;">
                                @if($review->user && !empty($review->user->profile))
                                    <img src="{{ asset('images/' . $review->user->profile) }}" alt="{{ $review->user->username }}">
                                @else
                                    <i class="fa-solid fa-user"></i>
                                @endif
                            </div>
                            <div class="user-name-info">
                                <span class="user-username" style="font-size: 0.85rem;">@ {{ $review->user ? $review->user->username : 'unknown' }}</span>
                                <span class="user-fullname" style="font-size: 0.75rem;">{{ $review->user ? $review->user->fullname : 'Unknown User' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Review Content & Stats -->
                    <div class="user-detail-right">
                        <!-- Stats Grid -->
                        <div class="detail-stats-grid">
                            <div class="detail-stat-box">
                                <div class="stat-num">{{ $review->likes_count }}</div>
                                <div class="stat-lbl">Likes</div>
                            </div>
                            <div class="detail-stat-box">
                                <div class="stat-num">{{ $review->comments_count }}</div>
                                <div class="stat-lbl">Comments</div>
                            </div>
                            <div class="detail-stat-box" style="grid-column: span 2; background-color: {{ $review->reports_count > 0 ? '#FDEDEC' : '#FFFDF9' }}; border-color: {{ $review->reports_count > 0 ? '#FADBD8' : 'var(--box-beige)' }};">
                                <div class="stat-num" style="color: {{ $review->reports_count > 0 ? '#E74C3C' : 'var(--btn-brown)' }};">
                                    {{ $review->reports_count }}
                                </div>
                                <div class="stat-lbl" style="color: {{ $review->reports_count > 0 ? '#C0392B' : '#8D6E63' }};">Reports Filed</div>
                            </div>
                        </div>

                        <!-- Rating & Content -->
                        <div class="detail-field">
                            <label>Rating Given :</label>
                            <span class="rating-stars" style="color: var(--star-yellow); font-size: 1.25rem;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-solid fa-star {{ $i <= $review->rating ? 'starred' : 'unstarred' }}" style="color: {{ $i <= $review->rating ? 'var(--star-yellow)' : '#E0E0E0' }};"></i>
                                @endfor
                            </span>
                        </div>

                        <div class="detail-field full-width">
                            <label>Review Content :</label>
                            <div class="field-box review-box" style="font-size: 1.05rem; line-height: 1.6; background-color: #FFFDF9;">
                                {{ $review->content }}
                            </div>
                        </div>

                        @if(!empty($review->songs) && count($review->songs) > 0)
                            <div class="detail-field full-width">
                                <label>Attached Songs Recommendation :</label>
                                <div style="display: flex; flex-direction: column; gap: 10px; width: 100%;">
                                    @foreach($review->songs as $song)
                                        <div style="display: flex; align-items: center; gap: 12px; background-color: #FFFDF9; border: 1.5px solid var(--box-beige); padding: 10px 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.01);">
                                            <div style="width: 45px; height: 45px; border-radius: 6px; overflow: hidden; flex-shrink: 0; background-color: #eee; border: 1px solid rgba(0,0,0,0.05);">
                                                @if(!empty($song['album_art']))
                                                    <img src="{{ $song['album_art'] }}" alt="Album Art" style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <i class="fa-solid fa-music" style="font-size: 1.2rem; color: #888; display: flex; align-items: center; justify-content: center; height: 100%;"></i>
                                                @endif
                                            </div>
                                            <div style="display: flex; flex-direction: column; text-align: left;">
                                                <span style="font-weight: 700; font-size: 0.95rem; color: var(--text-brown);">{{ $song['title'] ?? 'Unknown' }}</span>
                                                <span style="font-size: 0.8rem; color: #8D6E63;">{{ $song['artist'] ?? '-' }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Footer Actions Section -->
            <div class="detail-card-actions">
                <a href="{{ route('admin.reviews') }}" class="btn-action btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </a>

                <div class="decision-buttons">
                    <button type="button" class="btn-action btn-reject" id="openDeleteModal" style="background-color: #C0392B; border-color: #C0392B;">
                        <i class="fa-solid fa-trash-can"></i> Delete Review
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-backdrop" id="deleteModal">
    <div class="modal-box">
        <h3 class="modal-title" style="color: #C0392B;">Delete Review?</h3>
        <p style="font-size: 0.95rem; color: #5D4037; margin-bottom: 25px; line-height: 1.5;">
            Are you sure you want to delete this review permanently?<br>
            This action will also delete the likes, comments, and reports associated with this review.
        </p>
        <div class="modal-actions">
            <button type="button" class="modal-btn btn-cancel" id="closeDeleteModal">Cancel</button>
            <form action="{{ route('admin.reviews.delete', $review->id) }}" method="POST" style="display: inline-block;">
                @csrf
                <button type="submit" class="modal-btn btn-confirm-delete">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('deleteModal');
        const openDeleteBtn = document.getElementById('openDeleteModal');
        const closeDeleteBtn = document.getElementById('closeDeleteModal');

        // Delete Modal Controls
        if (openDeleteBtn && closeDeleteBtn && deleteModal) {
            openDeleteBtn.addEventListener('click', () => {
                deleteModal.classList.add('show');
            });
            closeDeleteBtn.addEventListener('click', () => {
                deleteModal.classList.remove('show');
            });
        }

        // Click outside backdrop to close
        window.addEventListener('click', (e) => {
            if (deleteModal && e.target === deleteModal) {
                deleteModal.classList.remove('show');
            }
        });
    });
</script>
@endpush
