@extends('layouts.admin')

@section('title', 'LetterIn - Report Details')

@section('content')
<section class="dashboard-hero">
    <h1 class="page-title">REPORT</h1>
</section>

<div class="dashboard-container">
    <!-- Filter Pills Section (Locked view matching counts) -->
    <div class="filter-pills-wrapper">
        <a href="{{ route('admin.reports', ['status' => 'all']) }}" class="filter-pill">
            <span>All</span>
            <span class="count-badge">{{ $counts['all'] }}</span>
        </a>
        <a href="{{ route('admin.reports', ['status' => 'pending']) }}" class="filter-pill {{ $report->status === 'pending' ? 'active' : '' }}">
            <span>Request</span>
            <span class="count-badge">{{ $counts['pending'] }}</span>
        </a>
        <a href="{{ route('admin.reports', ['status' => 'compiled']) }}" class="filter-pill {{ $report->status !== 'pending' ? 'active' : '' }}">
            <span>Compiled</span>
            <span class="count-badge">{{ $counts['compiled'] }}</span>
        </a>
        <a href="{{ route('admin.reports', ['status' => 'resolved']) }}" class="filter-pill">
            <span>Valid</span>
            <span class="count-badge">{{ $counts['resolved'] }}</span>
        </a>
        <a href="{{ route('admin.reports', ['status' => 'rejected']) }}" class="filter-pill">
            <span>Invalid</span>
            <span class="count-badge">{{ $counts['rejected'] }}</span>
        </a>
    </div>

    <!-- Report Detail Container -->
    <div class="detail-card-wrapper">
        <div class="detail-card">
            <!-- Header Section -->
            <div class="detail-card-header">
                <span class="detail-date">
                    <i class="fa-regular fa-calendar-days"></i> 
                    {{ $report->created_at ? $report->created_at->format('d M Y, H:i') : '-' }}
                </span>
                <span class="status-badge {{ $report->status }}">
                    {{ $report->status === 'pending' ? 'pending' : ($report->status === 'resolved' ? 'resolved' : 'rejected') }}
                </span>
            </div>

            <!-- Body Section -->
            <div class="detail-card-body">
                <div class="detail-field">
                    <label>Category :</label>
                    <span class="field-value highlight">{{ $report->category }}</span>
                </div>

                <div class="detail-field">
                    <label>reporter :</label>
                    <span class="field-value">@ {{ $report->reporter ? $report->reporter->username : 'unknown' }}</span>
                </div>

                <div class="detail-field">
                    <label>reported :</label>
                    <span class="field-value">@ {{ $report->reported ? $report->reported->username : 'unknown' }}</span>
                </div>

                <div class="detail-field full-width">
                    <label>konten :</label>
                    <div class="field-box reason-box">
                        {{ $report->content ?: 'Tidak ada penjelasan laporan tertulis.' }}
                    </div>
                </div>

                <div class="detail-field full-width">
                    <label>Review :</label>
                    <div class="field-box review-box">
                        @if($report->review)
                            <div class="review-meta">
                                <span class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-solid fa-star {{ $i <= $report->review->rating ? 'starred' : 'unstarred' }}"></i>
                                    @endfor
                                </span>
                                <span class="review-book">
                                    on <strong>{{ $report->review->book ? $report->review->book->title : 'Unknown Book' }}</strong>
                                </span>
                            </div>
                            <p class="review-content">{{ $report->review->content }}</p>
                        @elseif($report->reported_review_text)
                            <!-- Showing snapshot log because review was deleted -->
                            <div class="review-meta">
                                <span class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-solid fa-star {{ $i <= $report->reported_review_rating ? 'starred' : 'unstarred' }}"></i>
                                    @endfor
                                </span>
                                <span class="history-badge"><i class="fa-solid fa-clock-rotate-left"></i> LOG HISTORIS</span>
                            </div>
                            <p class="review-content historical">{{ $report->reported_review_text }}</p>
                        @else
                            <p class="review-content empty">Review tidak ditemukan atau sudah dihapus.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Footer Actions Section -->
            <div class="detail-card-actions">
                <a href="{{ route('admin.reports') }}" class="btn-action btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </a>

                @if($report->status === 'pending')
                    <div class="decision-buttons">
                        <button type="button" class="btn-action btn-solve" id="openSolveModal">
                            <i class="fa-solid fa-gavel"></i> Solve
                        </button>
                        <button type="button" class="btn-action btn-reject" id="openRejectModal">
                            <i class="fa-solid fa-circle-xmark"></i> Reject
                        </button>
                    </div>
                @else
                    <div class="processed-badge">
                        @if($report->status === 'resolved')
                            <span class="outcome-badge solved">
                                <i class="fa-solid fa-circle-check"></i> Resolved (Review Deleted)
                            </span>
                        @else
                            <span class="outcome-badge rejected">
                                <i class="fa-solid fa-circle-minus"></i> Rejected (Review Kept)
                            </span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($report->status === 'pending')
    <!-- ── 1. Solve Confirmation Modal ── -->
    <div class="modal-backdrop" id="solveModal">
        <div class="modal-box">
            <h3 class="modal-title">Delete this review and warn user?</h3>
            <div class="modal-actions">
                <button type="button" class="modal-btn btn-cancel" id="closeSolveModal">Cancel</button>
                <form action="{{ route('admin.reports.solve', $report->id) }}" method="POST" style="display: inline-block;">
                    @csrf
                    <button type="submit" class="modal-btn btn-confirm-delete">Delete</button>
                </form>
            </div>
        </div>
    </div>

    <!-- ── 2. Reject Confirmation Modal ── -->
    <div class="modal-backdrop" id="rejectModal">
        <div class="modal-box">
            <h3 class="modal-title">Keep this review?</h3>
            <div class="modal-actions">
                <button type="button" class="modal-btn btn-cancel" id="closeRejectModal">Cancel</button>
                <form action="{{ route('admin.reports.reject', $report->id) }}" method="POST" style="display: inline-block;">
                    @csrf
                    <button type="submit" class="modal-btn btn-confirm-keep">Keep</button>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
@if($report->status === 'pending')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const solveModal = document.getElementById('solveModal');
        const rejectModal = document.getElementById('rejectModal');
        
        const openSolveBtn = document.getElementById('openSolveModal');
        const closeSolveBtn = document.getElementById('closeSolveModal');
        
        const openRejectBtn = document.getElementById('openRejectModal');
        const closeRejectBtn = document.getElementById('closeRejectModal');

        // Solve Modal Controls
        openSolveBtn.addEventListener('click', () => {
            solveModal.classList.add('show');
        });
        closeSolveBtn.addEventListener('click', () => {
            solveModal.classList.remove('show');
        });

        // Reject Modal Controls
        openRejectBtn.addEventListener('click', () => {
            rejectModal.classList.add('show');
        });
        closeRejectBtn.addEventListener('click', () => {
            rejectModal.classList.remove('show');
        });

        // Click outside backdrop to close
        window.addEventListener('click', (e) => {
            if (e.target === solveModal) {
                solveModal.classList.remove('show');
            }
            if (e.target === rejectModal) {
                rejectModal.classList.remove('show');
            }
        });
    });
</script>
@endif
@endpush
