@extends('layouts.app')

@section('title', 'LetterIn - Notifications')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/notification.css') }}">
@endpush

@section('content')
    <main class="notif-container">
        
        <div class="page-header">
            <h1>NOTIFICATIONS</h1>
            <i class="fa-regular fa-bell"></i>
        </div>

        <div class="notif-list">
            @forelse ($notifications as $notif)
                @if ($notif->type === 'follow')
                    @php
                        $follower = $notif->user;
                        if (!$follower) continue;
                        $isFollowingBack = auth()->user()->following->contains('following_id', $follower->user_id);
                    @endphp
                    <div class="notif-item" style="position: relative;">
                        <div class="avatar-wrapper" style="width: 60px; height: 60px; border-radius: 50%; overflow: hidden; flex-shrink: 0; box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.2);">
                            @if($follower->profile)
                                <img src="{{ asset('images/' . $follower->profile) }}" alt="Avatar" class="avatar-img" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div class="avatar-placeholder" style="width: 100%; height: 100%; background: #674636; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                    <i class="fa-solid fa-user" style="font-size: 1.5rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="notif-content" style="flex: 1; display: flex; flex-direction: column; gap: 8px; padding-right: 95px;">
                            <p class="notif-text" style="font-family: var(--font-sans); font-size: 1.1rem; color: var(--text-brown); margin: 0;">
                                <a href="{{ route('profile.show', ['username' => $follower->username]) }}" style="text-decoration: none; color: inherit; font-weight: bold; transition: color 0.2s;" onmouseover="this.style.color='#674636'" onmouseout="this.style.color='inherit'">
                                    {{ $follower->fullname }}
                                </a>
                                <span style="color: #a67c52; font-size: 0.95rem; font-weight: normal;"> ({{ '@' . $follower->username }})</span> has followed you
                            </p>
                            <div class="action-buttons" style="display: flex; gap: 10px;">
                                <button type="button" class="follow-toggle-btn btn" data-user-id="{{ $follower->user_id }}" style="background-color: {{ $isFollowingBack ? 'transparent' : '#674636' }}; color: {{ $isFollowingBack ? '#674636' : 'white' }}; border: 1.5px solid #674636; padding: 6px 16px; border-radius: 20px; font-weight: bold; cursor: pointer; font-size: 0.85rem; transition: all 0.2s;">
                                    {{ $isFollowingBack ? 'Following' : 'Follow Back' }}
                                </button>
                            </div>
                        </div>
                        <span class="notif-time" style="font-size: 0.85rem; color: #a67c52; font-family: var(--font-sans); position: absolute; top: 25px; right: 0; font-weight: bold;">
                            {{ $notif->created_at->diffForHumans() }}
                        </span>
                    </div>
                @elseif ($notif->type === 'comment_deleted')
                    <div class="notif-item" style="position: relative; display: flex; align-items: center; gap: 15px;">
                        <div class="avatar-placeholder" style="width: 60px; height: 60px; background: #C0392B; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.1);">
                            <i class="fa-solid fa-comment-slash" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="notif-content" style="flex: 1; display: flex; flex-direction: column; gap: 4px; padding-right: 95px;">
                            <p class="notif-text" style="font-family: var(--font-sans); font-size: 1.05rem; color: var(--text-brown); margin: 0; line-height: 1.4;">
                                The review for book <strong style="color: #6D4C41;">"{{ $notif->data['book_title'] ?? 'Book' }}"</strong> that you commented on has been deleted by the Admin for violating policies, so your comment: <span style="font-style: italic; color: #8D6E63;">"{{ Str::limit($notif->data['comment_content'] ?? '', 80) }}"</span> was also deleted.
                            </p>
                        </div>
                        <span class="notif-time" style="font-size: 0.85rem; color: #a67c52; font-family: var(--font-sans); position: absolute; top: 25px; right: 0; font-weight: bold;">
                            {{ $notif->created_at->diffForHumans() }}
                        </span>
                    </div>
                @endif
            @empty
                <div style="text-align: center; padding: 60px 20px; color: #674636;">
                    <i class="fa-regular fa-bell-slash" style="font-size: 3.5rem; opacity: 0.5; margin-bottom: 15px;"></i>
                    <p style="font-size: 1.2rem; font-family: var(--font-serif); font-weight: bold; margin: 0;">No notifications yet.</p>
                </div>
            @endforelse
        </div>
    </main>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.follow-toggle-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const userId = this.getAttribute('data-user-id');
                        const btn = this;
                        
                        btn.disabled = true;
                        btn.style.opacity = '0.6';

                        fetch("{{ route('follow.toggle') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ user_id: userId })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.status === 'followed') {
                                btn.textContent = 'Following';
                                btn.style.backgroundColor = 'transparent';
                                btn.style.color = '#674636';
                            } else if (data.status === 'unfollowed') {
                                btn.textContent = 'Follow Back';
                                btn.style.backgroundColor = '#674636';
                                btn.style.color = 'white';
                            }
                        })
                        .catch(error => {
                            console.error('Error toggling follow:', error);
                            alert('Oops! Something went wrong. Please try again.');
                        })
                        .finally(() => {
                            btn.disabled = false;
                            btn.style.opacity = '1';
                        });
                    });
                });
            });
        </script>
    @endpush
@endsection
