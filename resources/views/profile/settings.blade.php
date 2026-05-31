@extends('layouts.app')

@section('title', 'LetterIn - Account Settings')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/settings.css') }}">
@endpush

@section('content')
    <main class="main-container">
    <a href="{{ Auth::check() && Auth::user()->is_admin ? route('admin.dashboard') : route('profile') }}" class="btn-back" style="display:inline-flex; align-items:center; margin-bottom:20px; color:#333; text-decoration:none; font-weight:600;">
        <i class="fa-solid fa-arrow-left" style="margin-right:5px;"></i> Back
    </a>
        
        <div class="settings-card">
            
            <h2 class="settings-title">ACCOUNT SETTINGS</h2>

            @if(session('success'))
                <div class="alert-success" style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('profile.update') }}" class="settings-form" method="POST" enctype="multipart/form-data">
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
                            <textarea id="bio" name="bio">{{ Auth::user()->bio }}</textarea>
                        </div>
                    </div>

                    <div class="avatar-column">
                        <div class="avatar-wrapper">
                            @if(Auth::user()->profile)
                                <img src="{{ asset('images/' . Auth::user()->profile) }}" alt="Profile Avatar" class="avatar-img" id="avatar-preview" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">
                                <div class="avatar-placeholder" id="avatar-placeholder" style="display: none;">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                            @else
                                <img src="" alt="Profile Avatar" class="avatar-img" id="avatar-preview" style="display: none; width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">
                                <div class="avatar-placeholder" id="avatar-placeholder">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                            @endif
                            <input type="file" name="profile" id="profile_input" style="display: none;" accept="image/*">
                            <button type="button" class="btn-edit-avatar" id="btn-edit-avatar">
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

    @push('scripts')
    <script>
        document.getElementById('btn-edit-avatar').addEventListener('click', function() {
            document.getElementById('profile_input').click();
        });

        document.getElementById('profile_input').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatar-preview');
                    const placeholder = document.getElementById('avatar-placeholder');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    placeholder.style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
    @endpush
@endsection
