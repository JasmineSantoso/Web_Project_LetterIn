<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LetterIn - Every read leaves a letter in')</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Common Styles -->
    <style>
        :root {
            --bg-brown-dark: #6D4C41;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        /* Global Notification Style */
        .global-notification {
            position: fixed;
            top: 30px;
            right: 30px;
            background: #adbda3;
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 9999;
            transform: translateX(120%);
            transition: transform 0.5s cubic-bezier(0.68, -0.55, 0.27, 1.55);
            font-family: 'Lato', sans-serif;
            font-weight: 700;
        }

        .global-notification.show {
            transform: translateX(0);
        }

        .global-notification i {
            font-size: 1.2rem;
        }
        main {
            flex: 1;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 40px;
            background-color: #FFF1C9;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 100;
        }
        .nav-left, .nav-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .nav-left a, .nav-right a {
            text-decoration: none;
            color: rgba(78, 52, 46, 0.75);
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.3s ease;
        }
        .nav-left a:hover, .nav-right a:hover {
            color: #4E342E;
        }
        .nav-left .active-nav {
            font-weight: 900 !important;
            color: #4E342E !important;
        }
        .logo-container {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .brand-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }
        .brand-logo img {
            height: 400%;
            width: auto;
            object-fit: contain;
            position: relative;
            left: +7px;
            pointer-events: none;
        }
        .search-box {
            position: relative;
        }
        .search-box input {
            padding: 8px 35px 8px 15px;
            border-radius: 20px;
            border: 1px solid #ccc;
            font-size: 0.9rem;
            width: 200px;
            outline: none;
            background-color: #fff;
        }
        .search-box i {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            font-size: 0.8rem;
        }
        .profile-container {
            position: relative;
        }
        header .profile-container .profile-icon {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 38px !important;
            height: 38px !important;
            border-radius: 50% !important;
            overflow: hidden !important;
            background: #E6D5C3;
            border: 1.5px solid rgba(78, 52, 46, 0.2) !important;
            transition: transform 0.2s, border-color 0.2s;
            text-decoration: none !important;
            box-sizing: border-box !important;
        }
        header .profile-container .profile-icon:hover {
            transform: scale(1.05);
            border-color: #5D4037 !important;
        }
        header .profile-container .profile-icon i {
            font-size: 2.2rem !important;
            line-height: 1 !important;
            margin: 0 !important;
            padding: 0 !important;
            display: block !important;
            color: #4E342E !important;
        }
        header .profile-container .profile-icon img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            border-radius: 50% !important;
            display: block !important;
        }
        ul.home-dropdown {
            position: absolute;
            right: 0;
            top: 120%;
            width: 180px;
            background-color: #6D4C41;
            list-style: none;
            border-radius: 8px;
            padding: 8px 0;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            display: none; 
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
            z-index: 1000;
        }
        ul.home-dropdown.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
        .home-dropdown li a {
            color: #fff;
            padding: 12px 20px;
            text-decoration: none;
            display: block;
            font-size: 0.85rem;
            font-weight: bold;
            transition: background 0.2s;
        }
        .home-dropdown li a:hover {
            background-color: #4E342E !important;
        }
        .home-dropdown::before {
            content: "";
            position: absolute;
            top: -8px;
            right: 10px;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-bottom: 8px solid #6D4C41;
        }
        .nav-btn {
            background: #AAB396; /* Sage green from screenshot */
            color: white !important;
            padding: 10px 25px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: bold;
            text-decoration: none;
            transition: all 600ms cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-block;
        }
        .nav-btn:hover {
            opacity: 0.8;
            transform: translateY(-2px);
        }
        .page-transition {
            animation: fadeIn 600ms cubic-bezier(0.4, 0, 0.2, 1);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        footer {
            padding: 0 50px;
            height: 80px; /* Reduced from 119px */
            background-color: var(--bg-footer);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid rgba(103, 70, 54, 0.1);
            width: 100%;
        }
        .footer-links a {
            margin-right: 35px;
            text-decoration: none;
            color: #674636;
            font-weight: 800;
            font-size: 1.2rem;
            font-family: 'Lato', sans-serif;
        }
        .social-icons {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .social-icons a {
            color: #1a1a1a;
            font-size: 1.8rem;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .social-icons .fa-instagram {
            color: white;
            background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
            border-radius: 8px;
            padding: 2px;
            font-size: 1.6rem;
        }
        .copyright {
            color: #674636;
            font-weight: 700;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <header>
        @if(Auth::guest() || (isset($forceGuestHeader) && $forceGuestHeader))
            <div class="nav-left">
                <a href="#" class="nav-btn">About us</a>
            </div>
        @else
                <div class="nav-left">
                    @if(!Auth::user()->is_admin)
                        <a href="/bookmates" class="{{ Route::is('bookmates*') ? 'active-nav' : '' }}">Bookmates</a>
                        <a href="/browse" class="{{ Route::is('browse*') || Route::is('search*') || Route::is('book.*') ? 'active-nav' : '' }}">Browse</a>
                    @endif
                </div>
        @endif
        
        <div class="logo-container">
            <a href="{{ Auth::check() && Auth::user()->is_admin ? route('admin.dashboard') : '/' }}" class="brand-logo">
                <img src="{{ asset('images/logo1.png') }}" alt="LetterIn Logo">
            </a>
        </div>
        
        <div class="nav-right">
            @if(Auth::guest() || (isset($forceGuestHeader) && $forceGuestHeader))
                <a href="{{ route('signin') }}" class="nav-btn">Sign in</a>
                <a href="{{ route('signup') }}" class="nav-btn">Sign up</a>
            @else
                <form action="{{ route('browse') }}" method="GET" class="search-box">
                    <input type="text" name="category" placeholder="Search books" value="{{ request('category') }}">
                    <button type="submit" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0; margin: 0; cursor: pointer; color: #888; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-magnifying-glass" style="font-size: 0.8rem; position: static; transform: none; color: inherit;"></i>
                    </button>
                </form>
                <div class="profile-container">
                    <a href="javascript:void(0)" class="profile-icon" id="profileBtn">
                        @if(Auth::user()->profile)
                            <img src="{{ asset('images/' . Auth::user()->profile) }}" alt="{{ Auth::user()->username }}">
                        @else
                            <i class="fa-regular fa-circle-user"></i>
                        @endif
                    </a>
                    <ul class="home-dropdown" id="myDropdown">
                    @if(!Auth::user()->is_admin)
                        <li><a href="/profile">PROFILE</a></li>
                    @endif
                    <li><a href="/settings">SETTINGS</a></li>
                    @if(!Auth::user()->is_admin)
                        <li><a href="/notifications">NOTIFICATIONS</a></li>
                    @endif
                    <hr style="border: 0.5px solid #6d4c41; margin: 5px 0;">
                    <li>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">SIGN OUT</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                    @if(!Auth::user()->is_admin)
                        <li><a href="#">CONTACT US</a></li>
                    @endif
                </ul>
                </div>
            @endif
        </div>
    </header>

    <!-- Global Notification Container -->
    @if(session('success'))
        <div class="global-notification" id="successNotif">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const notif = document.getElementById('successNotif');
                setTimeout(() => {
                    notif.classList.add('show');
                }, 100);

                // Hilang otomatis setelah 5 detik
                setTimeout(() => {
                    notif.classList.remove('show');
                }, 5000);
            });
        </script>
    @endif

    <main class="page-transition">
        @yield('content')
    </main>

    <footer>
        <div class="footer-links">
            <a href="#">Help</a>
            <a href="#">FAQ</a>
            <a href="#">Contact</a>
        </div>
        <div class="copyright">
            <i class="fa-solid fa-copyright"></i> 2026 LetterIn, Inc.
        </div>
        <div class="social-icons">
            <a href="#"><i class="fa-brands fa-square-x-twitter"></i></a> 
            <a href="#"><i class="fa-brands fa-square-threads"></i></a>
            <a href="#"><i class="fa-brands fa-square-tiktok"></i></a>
            <a href="#"><i class="fa-brands fa-instagram"></i></a>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        document.getElementById('profileBtn')?.addEventListener('click', function() {
            document.getElementById('myDropdown').classList.toggle('show');
        });
        window.onclick = function(event) {
            if (!event.target.closest('#profileBtn')) {
                var dropdowns = document.getElementsByClassName("home-dropdown");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
