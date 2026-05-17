@extends('layouts.app')

@section('title', 'LetterIn - Welcome')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home_signed.css') }}">
@endpush

@section('content')
    <section class="greeting-section">
        <h1>Welcome to LetterIn!</h1>
        @auth
            <h2>Have a good book, {{ Auth::user()->fullname }}!</h2>
        @endauth
    </section>

    <section class="current-read-section">
        <h2 class="section-title-white">YOUR CURRENT READ</h2>
        
        <div class="current-read-card">
            <img src="{{ asset('images/image11.jpg') }}" alt="Laut Bercerita" class="current-cover">
            
            <div class="read-details">
                <h3 class="read-title">Laut Bercerita</h3>
                <p class="read-author">Leila S. Chudori</p>
                
                <div class="progress-container">
                    <p class="progress-label">Progress <span class="percent">35%</span></p>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 35%;"></div>
                    </div>
                </div>
                
                <p class="start-date">Start reading<br>08-01-2026</p>
                
                <button class="btn-review">
                    Add Review
                </button>
            </div>
        </div>
    </section>

    <section class="carousel-section light-bg">
        <h2 class="section-title-brown">POPULAR THIS WEEK</h2>
        <div class="books-carousel">
            @for ($i = 1; $i <= 10; $i++)
                @php
                    $img = ($i == 10) ? 'image10.jpg' : "image{$i}.jpg";
                @endphp
                <img src="{{ asset('images/' . $img) }}" alt="Book {{ $i }}">
            @endfor
            <button class="next-arrow brown-arrow">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </section>

    <section class="carousel-section dark-bg">
        <h2 class="section-title-white">RECOMMENDATIONS</h2>
        <div class="books-carousel">
            @php
                $reco = [7, 8, 9, 1, 2, 3, 4, 5, 6, 10];
            @endphp
            @foreach ($reco as $r)
                <img src="{{ asset('images/image' . $r . '.jpg') }}" alt="Book {{ $r }}">
            @endforeach
            <button class="next-arrow white-arrow">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/home_signed.js') }}"></script>
@endpush
