@extends('layouts.app')

@section('title', 'LetterIn - Add Review')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/add_review.css') }}">
@endpush

@section('content')
    <main class="review-container">
        
        <div class="left-column">
            <div class="book-cover-wrapper">
                <img src="{{ asset('images/image11.jpg') }}" alt="Laut Bercerita" class="book-img">
            </div>
        </div>

        <div class="right-column">
            <h1 class="book-title">Laut Bercerita</h1>
            <h2 class="book-author">Leila S. Chudori</h2>

            <div class="star-rating-input">
                <i class="fa-regular fa-star" data-value="1"></i>
                <i class="fa-regular fa-star" data-value="2"></i>
                <i class="fa-regular fa-star" data-value="3"></i>
                <i class="fa-regular fa-star" data-value="4"></i>
                <i class="fa-regular fa-star" data-value="5"></i>
            </div>

            <textarea class="review-textarea" placeholder="Write your review here"></textarea>

            <div class="song-section">
                <h3 class="section-label">Add Related Song</h3>
                
                <div class="song-input-box">
                    <input type="text" id="songInput" placeholder="Search song...">
                </div>

                <div class="song-tags">
                    <div class="song-tag">
                        <img src="{{ asset('images/cover2.jpg') }}" alt="Cover">
                        <span>Daylight - Harry Style</span>
                        <i class="fa-solid fa-xmark remove-song"></i>
                    </div>
                    <div class="song-tag">
                        <img src="{{ asset('images/cover4.jpg') }}" alt="Cover">
                        <span>Love Notes - Olivia D.</span>
                        <i class="fa-solid fa-xmark remove-song"></i>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <div class="bookshelf-wrapper">
                    <button class="btn-bookshelf" id="bookshelfBtn">
                        Add Bookshelf
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                    <div class="bookshelf-dropdown" id="bookshelfDropdown">
                        <div class="dropdown-item">
                            <span>To Read</span>
                        </div>
                        <div class="dropdown-item">
                            <span>Currently Read</span>
                        </div>
                        <div class="dropdown-item">
                            <span>Done Read</span>
                        </div>
                    </div>
                </div>
                <button class="btn-send">SEND</button>
            </div>

        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('js/add_review.js') }}"></script>
@endpush
