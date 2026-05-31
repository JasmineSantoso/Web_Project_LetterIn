@extends('layouts.admin')

@section('title', 'LetterIn - Manage Users')

@section('content')
<section class="dashboard-hero">
    <h1 class="page-title">MANAGE USER</h1>
</section>

<div class="dashboard-container">
    <!-- Filter Pills Section -->
    <div class="filter-pills-wrapper">
        <a href="{{ route('admin.users', ['status' => 'all']) }}" class="filter-pill {{ $status === 'all' ? 'active' : '' }}">
            <span>All</span>
            <span class="count-badge">{{ $counts['all'] }}</span>
        </a>
        <a href="{{ route('admin.users', ['status' => 'active']) }}" class="filter-pill {{ $status === 'active' ? 'active' : '' }}">
            <span>Active</span>
            <span class="count-badge">{{ $counts['active'] }}</span>
        </a>
        <a href="{{ route('admin.users', ['status' => 'banned']) }}" class="filter-pill {{ $status === 'banned' ? 'active' : '' }}">
            <span>Banned</span>
            <span class="count-badge">{{ $counts['banned'] }}</span>
        </a>
    </div>

    <!-- Users Table Section -->
    <div class="users-table-wrapper">
        <div class="users-table-wrapper-responsive">
            <table class="users-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Following</th>
                        <th>Followers</th>
                        <th>Total Reviews</th>
                        <th>Total Bookshelf</th>
                        <th>Registered Date</th>
                        <th>Last Login</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr onclick="window.location='{{ route('admin.users.show', ['id' => $user->user_id, 'type' => $user->is_banned_user ? 'banned' : 'active']) }}'">
                            <td>{{ $users->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar-mini">
                                        @if(!empty($user->profile))
                                            <img src="{{ asset('images/' . $user->profile) }}" alt="{{ $user->username }}">
                                        @else
                                            <i class="fa-solid fa-user"></i>
                                        @endif
                                    </div>
                                    <div class="user-name-info">
                                        <span class="user-username">@ {{ $user->username }}</span>
                                        <span class="user-fullname">{{ $user->fullname }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->following_count }}</td>
                            <td>{{ $user->followers_count }}</td>
                            <td>{{ $user->reviews_count }}</td>
                            <td>{{ $user->bookshelves_count }}</td>
                            <td>
                                {{ $user->registered_at ? \Illuminate\Support\Carbon::parse($user->registered_at)->format('d-m-Y') : '-' }}
                            </td>
                            <td>
                                {{ $user->last_login_at ? \Illuminate\Support\Carbon::parse($user->last_login_at)->format('d-m-Y, H:i') : '-' }}
                            </td>
                            <td>
                                <span class="status-badge {{ $user->status_label }}">
                                    {{ $user->status_label }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center" style="padding: 40px; text-align: center; color: #8D6E63; font-weight: bold;">
                                <i class="fa-regular fa-folder-open" style="font-size: 2.5rem; display: block; margin-bottom: 10px;"></i>
                                Tidak ada data pengguna ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination links -->
    <div class="pagination-wrapper">
        {{ $users->appends(request()->input())->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
