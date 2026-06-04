<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LetterIn - Admin Dashboard')</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js for Admin Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom Admin Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin_dashboard.css') }}?v={{ filemtime(public_path('css/admin_dashboard.css')) }}">
    
    @stack('styles')
</head>
<body>
    <header class="admin-header">
        <div class="nav-left">
            <a href="{{ route('admin.reports') }}" class="nav-link {{ Route::is('admin.reports*') ? 'active-nav' : '' }}">Manage Report</a>
            <a href="{{ route('admin.reviews') }}" class="nav-link {{ Route::is('admin.reviews*') ? 'active-nav' : '' }}">Moderate Reviews</a>
            <a href="{{ route('admin.users') }}" class="nav-link {{ Route::is('admin.users*') ? 'active-nav' : '' }}">Manage Users</a>
        </div>
        
        <div class="logo-container">
            <a href="{{ route('admin.dashboard') }}" class="brand-logo">
                <img src="{{ asset('images/logo1.png') }}" alt="LetterIn Logo">
            </a>
        </div>
        
        <div class="nav-right">
            <div class="profile-container">
                <a href="javascript:void(0)" class="profile-icon" id="profileBtn">
                    @if(Auth::user()->profile)
                        <img src="{{ asset('images/' . Auth::user()->profile) }}" alt="{{ Auth::user()->username }}">
                    @else
                        <i class="fa-regular fa-circle-user"></i>
                    @endif
                </a>
                <ul class="admin-dropdown" id="myDropdown">
                    <li><a href="/settings">SETTINGS</a></li>
                    <hr style="border: 0.5px solid #6d4c41; margin: 5px 0;">
                    <li>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">SIGN OUT</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
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

                // Hide automatically after 5 seconds
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
            <i class="fa-solid fa-copyright"></i> 2026 LetterIn
        </div>
        <div class="social-icons">
            <a href="#"><i class="fa-brands fa-square-x-twitter"></i></a> 
            <a href="#"><i class="fa-brands fa-square-threads"></i></a>
            <a href="#"><i class="fa-brands fa-square-tiktok"></i></a>
            <a href="#"><i class="fa-brands fa-instagram"></i></a>
        </div>
    </footer>

    <!-- Dropdown Script -->
    <script>
        document.getElementById('profileBtn')?.addEventListener('click', function() {
            document.getElementById('myDropdown').classList.toggle('show');
        });
        window.onclick = function(event) {
            if (!event.target.matches('.profile-icon') && !event.target.closest('.profile-icon')) {
                var dropdowns = document.getElementsByClassName("admin-dropdown");
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
