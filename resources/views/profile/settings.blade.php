@extends('layouts.app')

@section('title', 'LetterIn - Account Settings')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/settings.css') }}">
@endpush

@section('content')
    <main class="main-container">
        
        <div class="settings-card">
            
            <h2 class="settings-title">ACCOUNT SETTINGS</h2>

            <form action="#" class="settings-form" method="POST">
                @csrf
                <div class="form-layout">
                    
                    <div class="input-column">
                        <div class="form-group">
                            <label for="fullname">Full Name</label>
                            <input type="text" id="fullname" name="fullname" value="{{ Auth::user()->fullname }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" value="{{ Auth::user()->username }}">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="{{ Auth::user()->email }}">
                        </div>

                        <div class="form-group">
                            <label for="bio">Bio</label>
                            <textarea id="bio" name="bio"></textarea>
                        </div>
                    </div>

                    <div class="avatar-column">
                        <div class="avatar-wrapper">
                            <div class="avatar-placeholder">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <button type="button" class="btn-edit-avatar">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                        </div>
                    </div>

                </div>

                <div class="form-actions">
                    <button type="button" class="btn-discard" onclick="window.location.href='{{ route('profile') }}'">DISCARD</button>
                    <button type="submit" class="btn-save">SAVE</button>
                </div>

            </form>
        </div>

    </main>
@endsection
