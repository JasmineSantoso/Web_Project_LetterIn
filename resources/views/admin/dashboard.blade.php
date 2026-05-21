@extends('layouts.admin')

@section('title', 'LetterIn - Admin Dashboard')

@section('content')
<section class="dashboard-hero">
    <h1 class="page-title">Welcome to the Admin Panel</h1>
</section>

<div class="dashboard-container">
    <!-- Stat Cards Deck -->
    <div class="stats-deck">
        <div class="stat-card">
            <span class="stat-label">Total user</span>
            <span class="stat-value">{{ $totalUsers }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-label">Total review</span>
            <span class="stat-value">{{ $totalReviews }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-label">Total bookshelf</span>
            <span class="stat-value">{{ $totalBookshelves }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-label">Total report</span>
            <span class="stat-value">{{ $totalReports }}</span>
        </div>
    </div>

    <!-- Charts Layout Section -->
    <div class="charts-list">
        <!-- User Chart -->
        <div class="chart-card">
            <h2 class="chart-title">User</h2>
            <div class="chart-wrapper">
                <canvas id="userChart"></canvas>
            </div>
        </div>

        <!-- Review Chart -->
        <div class="chart-card">
            <h2 class="chart-title">Review</h2>
            <div class="chart-wrapper">
                <canvas id="reviewChart"></canvas>
            </div>
        </div>

        <!-- Bookshelf Chart -->
        <div class="chart-card">
            <h2 class="chart-title">Bookshelf</h2>
            <div class="chart-wrapper">
                <canvas id="bookshelfChart"></canvas>
            </div>
        </div>

        <!-- Report Chart -->
        <div class="chart-card">
            <h2 class="chart-title">Report</h2>
            <div class="chart-wrapper">
                <canvas id="reportChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Universal Chart Configuration
    const labels = {!! json_encode($labels) !!};
    
    // ── 1. User Chart (Line Chart) ──
    const ctxUser = document.getElementById('userChart').getContext('2d');
    const userGradient = ctxUser.createLinearGradient(0, 0, 0, 300);
    userGradient.addColorStop(0, 'rgba(93, 64, 55, 0.25)');
    userGradient.addColorStop(1, 'rgba(93, 64, 55, 0.00)');

    new Chart(ctxUser, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Users Added',
                data: {!! json_encode($userChartData) !!},
                borderColor: '#5D4037',
                borderWidth: 3,
                backgroundColor: userGradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#5D4037',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    min: 0,
                    max: 40,
                    ticks: {
                        stepSize: 10,
                        font: { family: 'Lato', size: 12 },
                        color: '#4E342E'
                    },
                    grid: { color: 'rgba(93, 64, 55, 0.1)' }
                },
                x: {
                    ticks: {
                        font: { family: 'Lato', size: 12 },
                        color: '#4E342E'
                    },
                    grid: { display: false }
                }
            }
        }
    });

    // ── 2. Review Chart (Line Chart) ──
    const ctxReview = document.getElementById('reviewChart').getContext('2d');
    const reviewGradient = ctxReview.createLinearGradient(0, 0, 0, 300);
    reviewGradient.addColorStop(0, 'rgba(188, 198, 168, 0.35)');
    reviewGradient.addColorStop(1, 'rgba(188, 198, 168, 0.00)');

    new Chart(ctxReview, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Reviews Written',
                data: {!! json_encode($reviewChartData) !!},
                borderColor: '#8A9A5B', // Darker sage green
                borderWidth: 3,
                backgroundColor: reviewGradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#8A9A5B',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    min: 0,
                    max: 40,
                    ticks: {
                        stepSize: 10,
                        font: { family: 'Lato', size: 12 },
                        color: '#4E342E'
                    },
                    grid: { color: 'rgba(93, 64, 55, 0.1)' }
                },
                x: {
                    ticks: {
                        font: { family: 'Lato', size: 12 },
                        color: '#4E342E'
                    },
                    grid: { display: false }
                }
            }
        }
    });

    // ── 3. Bookshelf Chart (Line Chart) ──
    const ctxBookshelf = document.getElementById('bookshelfChart').getContext('2d');
    const bookshelfGradient = ctxBookshelf.createLinearGradient(0, 0, 0, 300);
    bookshelfGradient.addColorStop(0, 'rgba(141, 110, 99, 0.25)');
    bookshelfGradient.addColorStop(1, 'rgba(141, 110, 99, 0.00)');

    new Chart(ctxBookshelf, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Bookshelves Added',
                data: {!! json_encode($bookshelfChartData) !!},
                borderColor: '#8D6E63',
                borderWidth: 3,
                backgroundColor: bookshelfGradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#8D6E63',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    min: 0,
                    max: 40,
                    ticks: {
                        stepSize: 10,
                        font: { family: 'Lato', size: 12 },
                        color: '#4E342E'
                    },
                    grid: { color: 'rgba(93, 64, 55, 0.1)' }
                },
                x: {
                    ticks: {
                        font: { family: 'Lato', size: 12 },
                        color: '#4E342E'
                    },
                    grid: { display: false }
                }
            }
        }
    });

    // ── 4. Report Chart (Line Chart) ──
    const ctxReport = document.getElementById('reportChart').getContext('2d');
    const reportGradient = ctxReport.createLinearGradient(0, 0, 0, 300);
    reportGradient.addColorStop(0, 'rgba(161, 136, 127, 0.25)');
    reportGradient.addColorStop(1, 'rgba(161, 136, 127, 0.00)');

    new Chart(ctxReport, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Reports Filed',
                data: {!! json_encode($reportChartData) !!},
                borderColor: '#A1887F', // Clay brown
                borderWidth: 3,
                backgroundColor: reportGradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#A1887F',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    min: 0,
                    max: 40,
                    ticks: {
                        stepSize: 10,
                        font: { family: 'Lato', size: 12 },
                        color: '#4E342E'
                    },
                    grid: { color: 'rgba(93, 64, 55, 0.1)' }
                },
                x: {
                    ticks: {
                        font: { family: 'Lato', size: 12 },
                        color: '#4E342E'
                    },
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endpush
