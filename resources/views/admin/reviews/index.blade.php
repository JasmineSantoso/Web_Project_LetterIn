@extends('layouts.admin')

@section('title', 'LetterIn - Moderate Reviews')

@section('content')
<section class="dashboard-hero">
    <h1 class="page-title">MODERATE REVIEWS</h1>
</section>

<div class="dashboard-container">
    <!-- Reviews Table Section -->
    <div class="users-table-wrapper">
        <div class="users-table-wrapper-responsive">
            <table class="users-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Reviewer</th>
                        <th>Book</th>
                        <th>Rating</th>
                        <th>Review Snippet</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr onclick="window.location='{{ route('admin.reviews.show', $review->id) }}'">
                            <td>{{ $reviews->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar-mini">
                                        @if(!empty($review->user) && !empty($review->user->profile))
                                            <img src="{{ asset('images/' . $review->user->profile) }}" alt="{{ $review->user->username }}">
                                        @else
                                            <i class="fa-solid fa-user"></i>
                                        @endif
                                    </div>
                                    <div class="user-name-info">
                                        <span class="user-username">@ {{ $review->user ? $review->user->username : 'unknown' }}</span>
                                        <span class="user-fullname">{{ $review->user ? $review->user->fullname : 'Unknown User' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column;">
                                    <span style="font-weight: 700; font-size: 0.95rem;">
                                        {{ $review->book ? $review->book->title : 'Unknown Book' }}
                                    </span>
                                    <span style="font-size: 0.8rem; color: #8D6E63;">
                                        by {{ $review->book ? $review->book->author : '-' }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="rating-stars" style="color: var(--star-yellow); white-space: nowrap;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-solid fa-star {{ $i <= $review->rating ? 'starred' : 'unstarred' }}" style="color: {{ $i <= $review->rating ? 'var(--star-yellow)' : '#E0E0E0' }}; font-size: 0.85rem;"></i>
                                    @endfor
                                </span>
                            </td>
                            <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ Str::limit($review->content, 80) }}
                            </td>
                            <td>
                                {{ $review->created_at ? $review->created_at->format('d-m-Y') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center" style="padding: 40px; text-align: center; color: #8D6E63; font-weight: bold;">
                                <i class="fa-regular fa-folder-open" style="font-size: 2.5rem; display: block; margin-bottom: 10px;"></i>
                                Tidak ada ulasan ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination links -->
    <div class="pagination-wrapper">
        {{ $reviews->appends(request()->input())->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
