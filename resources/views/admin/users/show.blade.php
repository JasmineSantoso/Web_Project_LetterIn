@extends('layouts.admin')

@section('title', 'LetterIn - User Details')

@section('content')
<section class="dashboard-hero">
    <h1 class="page-title">MANAGE USER DETAILS</h1>
</section>

<div class="dashboard-container">
    <div class="detail-card-wrapper">
        <div class="detail-card">
            <!-- Header Section -->
            <div class="detail-card-header">
                <span class="detail-date">
                    <i class="fa-regular fa-calendar-days"></i> 
                    Registered on: {{ $user->created_at ? \Illuminate\Support\Carbon::parse($user->created_at)->format('d M Y') : '-' }}
                </span>
                <span class="status-badge {{ $user->status_label }}">
                    {{ $user->status_label }}
                </span>
            </div>

            <!-- Body Section -->
            <div class="detail-card-body">
                <div class="user-detail-card">
                    <!-- Avatar & Basic Info -->
                    <div class="user-detail-left">
                        <div class="user-avatar-large">
                            @if(!empty($user->profile))
                                <img src="{{ asset('images/' . $user->profile) }}" alt="{{ $user->username }}">
                            @else
                                <i class="fa-solid fa-user"></i>
                            @endif
                        </div>
                        <h2 style="font-family: var(--font-serif); font-size: 1.6rem; color: var(--text-brown); margin-bottom: 5px;">
                            {{ $user->fullname }}
                        </h2>
                        <span style="font-weight: 700; color: #8D6E63; font-size: 1rem; margin-bottom: 12px; display: block;">
                            @ {{ $user->username }}
                        </span>
                        <div style="font-size: 0.95rem; font-style: italic; color: #6D4C41; background-color: rgba(230, 213, 195, 0.2); padding: 10px 15px; border-radius: 8px; width: 100%; border-left: 3px solid var(--box-beige);">
                            {{ $user->bio ?: 'No bio provided.' }}
                        </div>
                    </div>

                    <!-- Details & Stats -->
                    <div class="user-detail-right">
                        <!-- Stats Grid -->
                        <div class="detail-stats-grid">
                            <div class="detail-stat-box">
                                <div class="stat-num">{{ $user->following_count }}</div>
                                <div class="stat-lbl">Following</div>
                            </div>
                            <div class="detail-stat-box">
                                <div class="stat-num">{{ $user->followers_count }}</div>
                                <div class="stat-lbl">Followers</div>
                            </div>
                            <div class="detail-stat-box">
                                <div class="stat-num">{{ $user->reviews_count }}</div>
                                <div class="stat-lbl">Total Reviews</div>
                            </div>
                            <div class="detail-stat-box">
                                <div class="stat-num">{{ $user->bookshelves_count }}</div>
                                <div class="stat-lbl">Bookshelf Items</div>
                            </div>
                        </div>

                        <!-- System Fields -->
                        <div class="detail-field">
                            <label>Email Address :</label>
                            <span class="field-value">{{ $user->email }}</span>
                        </div>

                        <div class="detail-field">
                            <label>Last Sign In :</label>
                            <span class="field-value">
                                {{ $user->last_login_at ? \Illuminate\Support\Carbon::parse($user->last_login_at)->format('d M Y, H:i') : '-' }}
                            </span>
                        </div>

                        @if($user->is_banned_user)
                            <div class="detail-field">
                                <label>Suspended At :</label>
                                <span class="field-value highlight" style="color: #E74C3C; background-color: #FDEDEC;">
                                    {{ $user->banned_at ? \Illuminate\Support\Carbon::parse($user->banned_at)->format('d M Y, H:i') : '-' }}
                                </span>
                            </div>

                            <div class="detail-field full-width">
                                <label>Suspension Reason :</label>
                                <div class="field-box reason-box" style="border-left: 5px solid #E74C3C; background-color: #FDEDEC; color: #C0392B;">
                                    {{ $user->ban_reason ?: 'No reason provided.' }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Footer Actions Section -->
            <div class="detail-card-actions">
                <a href="{{ route('admin.users') }}" class="btn-action btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </a>

                <div class="decision-buttons">
                    @if(!$user->is_banned_user)
                        <button type="button" class="btn-action btn-reject" id="openBanModal">
                            <i class="fa-solid fa-ban"></i> Ban Account
                        </button>
                    @endif
                    <button type="button" class="btn-action btn-reject" id="openDeleteModal" style="background-color: #C0392B; border-color: #C0392B;">
                        <i class="fa-solid fa-trash-can"></i> Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Backdrops -->

@if(!$user->is_banned_user)
    <!-- ── 1. Ban Account Modal ── -->
    <div class="modal-backdrop" id="banModal">
        <div class="modal-box">
            <h3 class="modal-title">Banned this account?</h3>
            <p style="font-size: 0.9rem; color: #8D6E63; margin-bottom: 20px;">
                This account will be removed from active users and moved to the suspended list (banned users).
            </p>
            <form action="{{ route('admin.users.ban', $user->user_id) }}" method="POST" class="ban-reason-form">
                @csrf
                <label for="ban_reason">Suspension Reason:</label>
                <textarea name="ban_reason" id="ban_reason" rows="4" placeholder="Enter the reason why this account is suspended..." required></textarea>
                
                <div class="modal-actions" style="margin-top: 10px;">
                    <button type="button" class="modal-btn btn-cancel" id="closeBanModal">Cancel</button>
                    <button type="submit" class="modal-btn" style="background-color: #E74C3C; color: white;">Ban</button>
                </div>
            </form>
        </div>
    </div>
@endif

<!-- ── 2. Delete Account Modal ── -->
<div class="modal-backdrop" id="deleteModal">
    <div class="modal-box">
        <h3 class="modal-title" style="color: #C0392B;">Delete Account Permanently?</h3>
        <p style="font-size: 0.95rem; color: #5D4037; margin-bottom: 25px; line-height: 1.5;">
            Are you sure you want to delete the account <strong>@ {{ $user->username }}</strong> permanently?<br>
            This action will permanently delete all data associated with this account from the system.
        </p>
        <div class="modal-actions">
            <button type="button" class="modal-btn btn-cancel" id="closeDeleteModal">Cancel</button>
            <form action="{{ route('admin.users.delete', ['id' => $user->user_id, 'type' => $user->is_banned_user ? 'banned' : 'active']) }}" method="POST" style="display: inline-block;">
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
        const banModal = document.getElementById('banModal');
        const deleteModal = document.getElementById('deleteModal');
        
        const openBanBtn = document.getElementById('openBanModal');
        const closeBanBtn = document.getElementById('closeBanModal');
        
        const openDeleteBtn = document.getElementById('openDeleteModal');
        const closeDeleteBtn = document.getElementById('closeDeleteModal');

        // Ban Modal Controls
        if (openBanBtn && closeBanBtn && banModal) {
            openBanBtn.addEventListener('click', () => {
                banModal.classList.add('show');
            });
            closeBanBtn.addEventListener('click', () => {
                banModal.classList.remove('show');
            });
        }

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
            if (banModal && e.target === banModal) {
                banModal.classList.remove('show');
            }
            if (deleteModal && e.target === deleteModal) {
                deleteModal.classList.remove('show');
            }
        });
    });
</script>
@endpush
