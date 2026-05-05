@extends('layouts.app')

@section('title', 'LetterIn - Search Result')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/searchbook_signed.css') }}">
@endpush

@section('content')
    <section class="page-title-section">
        <h1>Book result for 'Hujan'</h1>
    </section>

    <section class="filter-bar">
        <div class="filter-buttons">
            <button class="filter-btn">Genre <i class="fa-solid fa-chevron-down"></i></button>
            <button class="filter-btn">Publish <i class="fa-solid fa-chevron-down"></i></button>
            <button class="filter-btn">Rating <i class="fa-solid fa-chevron-down"></i></button>
        </div>
    </section>

    <section class="result-list">
        @php
            $books = [
                ['title' => 'Dan Hujan Pun Berhenti', 'year' => 2007, 'author' => 'Farida Susanty', 'img' => 'image1.jpg', 'rating' => 3.11],
                ['title' => 'Episode Hujan', 'year' => 2016, 'author' => 'Lucia Priandarini', 'img' => 'image2.jpg', 'rating' => 3.55],
                ['title' => 'Hujan Kepagian', 'year' => 1958, 'author' => 'Nugroho Notosusanto', 'img' => 'image3.jpg', 'rating' => 3.86],
                ['title' => 'Wait For The Rain', 'year' => 2015, 'author' => 'Maria Murnane', 'img' => 'image4.jpg', 'rating' => 3.77],
                ['title' => 'Hujan', 'year' => 2016, 'author' => 'Tere Liye', 'img' => 'image5.jpg', 'rating' => 4.22],
                ['title' => 'Hujan', 'year' => 2008, 'author' => 'Rien, Thee&Rien', 'img' => 'image6.jpg', 'rating' => 5.0],
            ];
        @endphp

        @foreach ($books as $book)
        <div class="book-card">
            <img src="{{ asset('images/' . $book['img']) }}" alt="{{ $book['title'] }}" class="book-cover">
            
            <div class="book-info">
                <div class="info-top">
                    <h2 class="book-title">{{ $book['title'] }} <span class="book-year">{{ $book['year'] }}</span></h2>
                    <p class="book-author">{{ $book['author'] }}</p>
                </div>
                <div class="book-rating">
                    <i class="fa-solid fa-star"></i>
                    <span class="rating-text">{{ $book['rating'] }} rating</span>
                </div>
            </div>

            <div class="action-box">
                <div class="action-item dropdown-item">
                    <span>To Read</span> <i class="fa-regular fa-bookmark"></i>
                </div>
                <div class="action-item">
                    <span>Add Favorite</span> <i class="fa-regular fa-heart"></i>
                </div>
                <div class="action-item">
                    <span>Add Bookshelf</span> <i class="fa-regular fa-square-check"></i>
                </div>
            </div>
        </div>
        @endforeach
    </section>
@endsection
