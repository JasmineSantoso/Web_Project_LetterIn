@extends('layouts.admin')

@section('title', 'LetterIn - Manage Reports')

@section('content')
<section class="dashboard-hero">
    <h1 class="page-title">REPORT</h1>
</section>

<div class="dashboard-container">
    <!-- Filter Pills Section -->
    <div class="filter-pills-wrapper">
        <a href="{{ route('admin.reports', ['status' => 'all']) }}" class="filter-pill {{ $status === 'all' ? 'active' : '' }}">
            <span>All</span>
            <span class="count-badge">{{ $counts['all'] }}</span>
        </a>
        <a href="{{ route('admin.reports', ['status' => 'pending']) }}" class="filter-pill {{ $status === 'pending' ? 'active' : '' }}">
            <span>Pending</span>
            <span class="count-badge">{{ $counts['pending'] }}</span>
        </a>
        <a href="{{ route('admin.reports', ['status' => 'compiled']) }}" class="filter-pill {{ $status === 'compiled' ? 'active' : '' }}">
            <span>Compiled</span>
            <span class="count-badge">{{ $counts['compiled'] }}</span>
        </a>
        <a href="{{ route('admin.reports', ['status' => 'resolved']) }}" class="filter-pill {{ $status === 'resolved' ? 'active' : '' }}">
            <span>Resolve</span>
            <span class="count-badge">{{ $counts['resolved'] }}</span>
        </a>
        <a href="{{ route('admin.reports', ['status' => 'rejected']) }}" class="filter-pill {{ $status === 'rejected' ? 'active' : '' }}">
            <span>Rejected</span>
            <span class="count-badge">{{ $counts['rejected'] }}</span>
        </a>
    </div>

    <!-- Report Cards List -->
    <div class="reports-deck">
        @forelse($reports as $report)
            <div class="report-card status-{{ $report->status }}">
                <div class="report-card-header">
                    <span class="report-date">
                        <i class="fa-regular fa-calendar-days"></i> 
                        {{ $report->created_at ? $report->created_at->format('d M Y, H:i') : '-' }}
                    </span>
                    <span class="status-badge {{ $report->status }}">
                        {{ $report->status === 'pending' ? 'pending' : ($report->status === 'resolved' ? 'resolve' : 'rejected') }}
                    </span>
                </div>
                
                <div class="report-card-body">
                    <div class="report-meta-row">
                        <span class="meta-label">reporter:</span>
                        <span class="meta-value">@ {{ $report->reporter ? $report->reporter->username : 'unknown' }}</span>
                    </div>
                    <div class="report-meta-row">
                        <span class="meta-label">category:</span>
                        <span class="meta-value category-tag">{{ $report->category }}</span>
                    </div>
                    <div class="report-meta-row content-row">
                        <span class="meta-label">konten:</span>
                        <p class="reported-snippet">
                            {{ $report->content ?: 'Tidak ada alasan laporan tertulis.' }}
                        </p>
                    </div>
                </div>

                <div class="report-card-footer">
                    <a href="{{ route('admin.reports.show', $report->id) }}" class="detail-link">
                        Detail <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-state-card">
                <i class="fa-regular fa-folder-open"></i>
                <p>Tidak ada laporan ditemukan untuk kategori ini.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination links -->
    <div class="pagination-wrapper">
        {{ $reports->appends(request()->input())->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
