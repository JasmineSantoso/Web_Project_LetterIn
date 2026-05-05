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
            @php
                $notifs = [
                    ['id' => 1, 'user' => '@azalea', 'text' => 'has sent you a friend request'],
                    ['id' => 2, 'user' => '@william', 'text' => 'has sent you a friend request'],
                    ['id' => 3, 'user' => '@erica', 'text' => 'has sent you a friend request'],
                    ['id' => 4, 'user' => '@steve', 'text' => 'has accepted your friend request'],
                ];
            @endphp

            @foreach ($notifs as $n)
            <div class="notif-item">
                <div class="avatar-wrapper">
                    <div class="avatar-placeholder" style="width: 50px; height: 50px; background: #674636; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fa-solid fa-user"></i>
                    </div>
                </div>
                <div class="notif-content">
                    <p class="notif-text">{{ $n['user'] }} {{ $n['text'] }}</p>
                    <div class="action-buttons">
                        <input type="checkbox" id="accept{{ $n['id'] }}" class="accept-toggle">
                        <input type="checkbox" id="decline{{ $n['id'] }}" class="modal-toggle">
                        <label for="accept{{ $n['id'] }}" class="btn btn-accept">ACCEPT</label>
                        <label for="decline{{ $n['id'] }}" class="btn btn-decline">DECLINE</label>
                        <span class="accepted-text">Accepted</span>

                        <div class="modal">
                            <div class="modal-box">
                                <h3 class="modal-title">WARNING</h3>
                                <p>Are you sure want to decline<br><b>{{ $n['user'] }}</b>'s friend request?</p>
                                <div class="modal-actions">
                                    <label for="decline{{ $n['id'] }}" class="btn btn-cancel">CANCEL</label>
                                    <button class="btn btn-decline-final">DECLINE</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </main>
@endsection
