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

        <div class="mate-search-bar">
            <input type="text" placeholder="Search bookmates">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>

        <div class="feed-list">
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
        </div>
    </main>
@endsection
