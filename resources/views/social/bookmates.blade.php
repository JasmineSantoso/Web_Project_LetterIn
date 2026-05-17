@extends('layouts.app')

@section('title', 'LetterIn - Bookmates')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/bookmates.css') }}">
@endpush

@section('content')
    <main class="bookmates-container">
        
        <nav class="sub-nav">
            <a href="#" class="tab-link active-tab">All Updates</a>
            <a href="#" class="tab-link">Friends</a>
            <a href="#" class="tab-link">Similar User</a>
        </nav>

        <form action="{{ route('bookmates') }}" method="GET" class="mate-search-bar" style="margin-bottom: 20px;">
            <input type="text" name="q" placeholder="Search users by name or username" value="{{ $search ?? '' }}">
            <button type="submit" style="background: none; border: none; cursor: pointer; color: #674636;">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>

        <div class="feed-list">
            @if(isset($search) && $search !== '')
                <h3 style="color: #674636; margin-bottom: 15px;">Search Results for "{{ $search }}"</h3>
                @forelse($users as $user)
                    <div class="feed-card" style="padding: 15px; display: flex; align-items: center; gap: 15px; border-radius: 8px; margin-bottom: 10px;">
                        @if($user->profile)
                            <img src="{{ asset('images/' . $user->profile) }}" alt="Avatar" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                        @else
                            <div style="width: 50px; height: 50px; border-radius: 50%; background-color: #e0e0e0; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #757575;">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        @endif
                        <div style="flex-grow: 1;">
                            <a href="{{ route('profile.show', ['username' => $user->username]) }}" style="text-decoration: none; color: #674636;">
                                <h4 style="margin: 0;">{{ $user->fullname }}</h4>
                                <p style="margin: 0; font-size: 0.9rem; color: #a67c52;">{{ '@' . $user->username }}</p>
                            </a>
                        </div>
                        <a href="{{ route('profile.show', ['username' => $user->username]) }}" style="background-color: #674636; color: white; padding: 6px 12px; border-radius: 20px; text-decoration: none; font-size: 0.9rem;">View Profile</a>
                    </div>
                @empty
                    <p style="color: #674636;">No users found matching "{{ $search }}".</p>
                @endforelse
            @else
            @php
                $feeds = [
                    ['user' => '@anastasia', 'time' => '1 minute ago', 'rating' => 4.5, 'book' => 'Hujan'],
                    ['user' => '@serenaa', 'time' => '25 minutes ago', 'rating' => 3.5, 'book' => 'Hujan'],
                    ['user' => '@david', 'time' => '58 minutes ago', 'rating' => 4.0, 'book' => 'Hujan'],
                    ['user' => '@kaylani', 'time' => '12 hours ago', 'rating' => 5.0, 'book' => 'Hujan'],
                ];
            @endphp

            @foreach ($feeds as $feed)
            <div class="feed-card">
                <div class="card-header">
                    <div class="user-info">
                        <div class="avatar-circle"><i class="fa-solid fa-user"></i></div> 
                        <span class="username">{{ $feed['user'] }} reviewed:</span>
                    </div>
                    <span class="time-stamp">about {{ $feed['time'] }}</span>
                </div>
                <div class="card-body">
                    <img src="{{ asset('images/image10.jpg') }}" alt="Hujan" class="feed-book-cover">
                    <div class="feed-book-info">
                        <h3 class="feed-book-title">{{ $feed['book'] }}</h3>
                        <p class="feed-book-author">Tere Liye</p>
                        <div class="feed-rating">
                            <span>{{ $feed['rating'] }}</span> <i class="fa-solid fa-star"></i>
                        </div>
                        <div class="feed-actions">
                            <span class="see-review">See full review</span>
                            <div class="action-icons">
                                <i class="fa-regular fa-heart"></i>
                                <i class="fa-solid fa-music"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </main>
@endsection
