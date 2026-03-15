<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CricAuction - Cricket Tournament Auction Platform')</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/css2.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    @stack('styles')
</head>
<body>

    <!-- ===== NAVBAR ===== -->
    <nav class="navbar navbar-expand-lg ca-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand ca-brand" href="{{ url('/') }}">
                <i class="bi bi-trophy-fill ca-brand-icon"></i>
                Cric<span>Auction</span>
            </a>
            <button class="navbar-toggler ca-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                    <li class="nav-item"><a class="nav-link ca-nav-link" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link ca-nav-link" href="#how-it-works">How it Works</a></li>
                    <li class="nav-item"><a class="nav-link ca-nav-link" href="#features">Features</a></li>
                    @auth
                        <li class="nav-item ms-lg-2">
                            <a class="btn ca-btn-primary" href="{{ route('dashboard') }}">
                                <i class="bi bi-grid-fill me-1"></i>Dashboard
                            </a>
                        </li>
                    @else
                        <li class="nav-item ms-lg-1">
                            <a class="btn ca-btn-outline" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item ms-lg-1">
                            <a class="btn ca-btn-primary" href="{{ route('register') }}">
                                Get Started Free
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    @yield('content')

    <!-- ===== FOOTER ===== -->
    <footer class="ca-footer">
        <div class="container">
            <div class="row align-items-center gy-3">
                <div class="col-12 col-md-4">
                    <div class="ca-footer-brand">
                        <i class="bi bi-trophy-fill ca-brand-icon"></i>
                        Cric<span>Auction</span>
                    </div>
                    <p class="ca-footer-tagline">Free cricket auction platform for everyone.</p>
                </div>
                <div class="col-12 col-md-4 text-md-center">
                    <small class="ca-footer-copy">&copy; {{ date('Y') }} CricAuction. All rights reserved.</small>
                </div>
                <div class="col-12 col-md-4 text-md-end">
                    <a href="#" class="ca-footer-link me-3">Privacy Policy</a>
                    <a href="#" class="ca-footer-link me-3">Terms</a>
                    <a href="#" class="ca-footer-link">Contact</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>