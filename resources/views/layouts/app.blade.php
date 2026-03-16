<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Auction XI - Cricket Tournament Auction Platform')</title>

    {{-- Bootstrap 5 CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- Google Fonts: Inter --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Custom CSS --}}
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>

{{-- ===== NAVBAR ===== --}}
<nav class="main-navbar">
    <div class="container">
        <div class="navbar-inner">

            {{-- Brand --}}
            <a href="{{ url('/') }}" class="nav-brand">
                <i class="bi bi-trophy-fill brand-icon"></i>
                Auction<span>XI</span>
            </a>

            {{-- Desktop Nav Links --}}
            @auth
                <div class="nav-links">
                    <a href="{{ route('dashboard') }}"
                       class="nav-link-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2"></i> Dashboard
                    </a>
                    <a href="{{ route('tournaments.create') }}"
                       class="nav-link-item {{ request()->routeIs('tournaments.create') ? 'active' : '' }}">
                        <i class="bi bi-plus-circle"></i> New Tournament
                    </a>
                    <a href="{{ route('home') }}" class="nav-link-item">
                        <i class="bi bi-house"></i> Home
                    </a>
                </div>
            @endauth

            {{-- Nav Right --}}
            <div class="nav-right">
                @auth
                    {{-- User Dropdown --}}
                    <div class="nav-dropdown" id="userDropdown">
                        <button class="nav-user-btn" onclick="toggleDropdown()">
                            <div class="nav-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <span class="nav-username">{{ Auth::user()->name }}</span>
                            <i class="bi bi-chevron-down nav-chevron"></i>
                        </button>

                        <div class="nav-dropdown-menu" id="dropdownMenu">
                            <div class="dropdown-user-info">
                                <div class="dropdown-user-name">{{ Auth::user()->name }}</div>
                                <div class="dropdown-user-email">{{ Auth::user()->email }}</div>
                            </div>
                            <hr class="dropdown-divider-custom">
                            <a href="{{ route('dashboard') }}" class="dropdown-item-custom">
                                <i class="bi bi-grid-1x2"></i> Dashboard
                            </a>
                            <a href="{{ route('tournaments.create') }}" class="dropdown-item-custom">
                                <i class="bi bi-plus-circle"></i> New Tournament
                            </a>
                            <hr class="dropdown-divider-custom">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item-custom danger">
                                    <i class="bi bi-box-arrow-right"></i> Sign Out
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Mobile Toggle --}}
                    <button class="mobile-toggle" onclick="toggleMobileMenu()" id="mobileToggle">
                        <i class="bi bi-list" id="mobileIcon"></i>
                    </button>

                @else
                    <a href="{{ route('login') }}" class="nav-link-item nav-signin">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="btn-nav-primary">
                        Get Started Free
                    </a>
                @endauth
            </div>

        </div>
    </div>

    {{-- Mobile Menu --}}
    @auth
        <div class="mobile-menu" id="mobileMenu">
            <div class="container">
                <div class="mobile-user-info">
                    <div class="nav-avatar mobile-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="mobile-user-name">{{ Auth::user()->name }}</div>
                        <div class="mobile-user-email">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <a href="{{ route('dashboard') }}"
                   class="mobile-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i> Dashboard
                </a>
                <a href="{{ route('tournaments.create') }}" class="mobile-nav-link">
                    <i class="bi bi-plus-circle"></i> New Tournament
                </a>
                <a href="{{ route('home') }}" class="mobile-nav-link">
                    <i class="bi bi-house"></i> Home
                </a>
                <hr class="mobile-divider">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="mobile-nav-link mobile-logout">
                        <i class="bi bi-box-arrow-right"></i> Sign Out
                    </button>
                </form>
            </div>
        </div>
    @endauth
</nav>

{{-- ===== PAGE CONTENT ===== --}}
@yield('content')

{{-- ===== TOAST ===== --}}
<div class="toast-container-custom" id="toastContainer"></div>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            showToast('{{ addslashes(session('success')) }}', 'success');
        });
    </script>
@endif
@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            showToast('{{ addslashes(session('error')) }}', 'error');
        });
    </script>
@endif

{{-- ===== FOOTER ===== --}}
<footer class="main-footer">
    <div class="container">
        <div class="footer-inner">
            <div class="footer-brand-col">
                <a href="{{ url('/') }}" class="footer-brand">
                    <i class="bi bi-trophy-fill"></i> Auction<span>XI</span>
                </a>
                <p class="footer-tagline">Free cricket auction platform. Beta version.</p>
            </div>
            <div class="footer-center-col">
                <p class="footer-copy">
                    &copy; {{ date('Y') }} Auction XI &nbsp;&middot;&nbsp; All rights reserved
                </p>
                <p class="footer-dev">
                    Developed by
                    <a href="https://saunak-info.onrender.com" target="_blank" rel="noopener">
                        Saunak Chaudhary
                    </a>
                </p>
            </div>
            <div class="footer-links-col">
                @auth
                    <a href="{{ route('dashboard') }}" class="footer-link">Dashboard</a>
                    <a href="{{ route('tournaments.create') }}" class="footer-link">New Tournament</a>
                @else
                    <a href="{{ route('login') }}" class="footer-link">Login</a>
                    <a href="{{ route('register') }}" class="footer-link">Register</a>
                @endauth
            </div>
        </div>
    </div>
</footer>

{{-- Bootstrap JS CDN --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function toggleDropdown() {
        document.getElementById('dropdownMenu').classList.toggle('open');
    }
    document.addEventListener('click', function (e) {
        const dropdown = document.getElementById('userDropdown');
        if (dropdown && !dropdown.contains(e.target)) {
            document.getElementById('dropdownMenu')?.classList.remove('open');
        }
    });

    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        const icon = document.getElementById('mobileIcon');
        menu.classList.toggle('open');
        icon.className = menu.classList.contains('open') ? 'bi bi-x-lg' : 'bi bi-list';
    }

    function showToast(message, type = 'success') {
        const container = document.getElementById('toastContainer');
        const toast     = document.createElement('div');
        const icon      = type === 'success'
            ? '<i class="bi bi-check-circle-fill toast-icon toast-icon-success"></i>'
            : '<i class="bi bi-exclamation-circle-fill toast-icon toast-icon-error"></i>';

        toast.className = `toast-custom toast-${type}`;
        toast.innerHTML = icon +
            `<span class="toast-msg">${message}</span>` +
            `<button class="toast-close" onclick="this.parentElement.remove()">
                <i class="bi bi-x"></i>
            </button>`;
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease forwards';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }
</script>

@stack('scripts')
</body>
</html>