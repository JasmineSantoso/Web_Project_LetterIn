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
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 50px;
            background: #F7EED3;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .nav-left, .nav-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .nav-left a, .nav-right a {
            text-decoration: none;
            color: #674636;
            font-weight: 700;
        }
        .logo-container img {
            height: 40px;
        }
        .search-box {
            position: relative;
        }
        .search-box input {
            padding: 5px 30px 5px 10px;
            border-radius: 20px;
            border: 1px solid #674636;
        }
        .search-box i {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #674636;
        }
        .profile-container {
            position: relative;
        }
        .home-dropdown {
            display: none;
            position: absolute;
            right: 0;
            background: #F7EED3;
            list-style: none;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            z-index: 1000;
            min-width: 150px;
        }
        .home-dropdown li a {
            display: block;
            padding: 5px 10px;
            font-size: 0.8rem;
            color: #674636;
        }
        .home-dropdown.show {
            display: block;
        }
        .nav-btn {
            background: #674636;
            color: white !important;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        footer {
            padding: 30px 50px;
            background: #F7EED3;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid rgba(103, 70, 54, 0.1);
        }
        .footer-links a {
            margin-right: 20px;
            text-decoration: none;
            color: #674636;
            font-weight: 700;
        }
        .social-icons a {
            margin-left: 15px;
            color: #674636;
            font-size: 1.2rem;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <header>
        <div class="nav-left">
            <a href="/bookmates">Bookmates</a>
            <a href="/browse">Browse</a>
        </div>
        
        <div class="logo-container">
            <a href="/" class="brand-logo">
                <img src="{{ asset('images/logo1.png') }}" alt="LetterIn Logo">
            </a>
        </div>
        
        <div class="nav-right">
            <div class="search-box">
                <input type="text" placeholder="Search books">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            @auth
            <div class="profile-container">
                <a href="javascript:void(0)" class="profile-icon" id="profileBtn">
                    <i class="fa-regular fa-circle-user"></i>
                </a>
                <ul class="home-dropdown" id="myDropdown">
                    <li><a href="/profile">PROFILE</a></li>
                    <li><a href="/settings">SETTINGS</a></li>
                    <li><a href="/notifications">NOTIFICATIONS</a></li>
                    <hr style="border: 0.5px solid #6d4c41; margin: 5px 0;">
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" style="background: none; border: none; color: #674636; font-weight: 700; cursor: pointer; padding: 5px 10px; font-size: 0.8rem; width: 100%; text-align: left;">SIGN OUT</button>
                        </form>
                    </li>
                    <li><a href="#">CONTACT US</a></li>
                </ul>
            </div>
            @else
            <a href="{{ route('signin') }}" class="nav-btn">Sign in</a>
            <a href="{{ route('signup') }}" class="nav-btn">Sign up</a>
            @endauth
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="footer-links">
            <a href="#">Help</a>
            <a href="#">FAQ</a>
            <a href="#">Contact</a>
        </div>
        <div class="copyright">
            <i class="fa-regular fa-copyright"></i> 2026 LetterIn, Inc.
        </div>
        <div class="social-icons">
            <a href="#"><i class="fa-brands fa-square-twitter"></i></a> 
            <a href="#"><i class="fa-brands fa-tiktok"></i></a>
            <a href="#"><i class="fa-brands fa-instagram"></i></a>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        document.getElementById('profileBtn')?.addEventListener('click', function() {
            document.getElementById('myDropdown').classList.toggle('show');
        });
        window.onclick = function(event) {
            if (!event.target.matches('.profile-icon') && !event.target.matches('.fa-circle-user')) {
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
