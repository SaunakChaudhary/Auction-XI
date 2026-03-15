<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CricAuction')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Inter', sans-serif; }
        :root {
            --primary: #1a56db;
            --primary-dark: #1e429f;
            --accent: #f59e0b;
        }
        body {
            background: linear-gradient(135deg, #1e3a8a 0%, #1a56db 60%, #3b82f6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }
        .auth-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px 36px;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.18);
        }
        .auth-logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: #111827;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 8px;
        }
        .auth-logo span { color: var(--accent); }
        .auth-title {
            font-size: 1.6rem;
            font-weight: 800;
            color: #111827;
            margin-bottom: 4px;
        }
        .auth-subtitle {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 28px;
        }
        .form-label {
            font-weight: 600;
            font-size: 0.875rem;
            color: #374151;
            margin-bottom: 6px;
        }
        .form-control {
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.9rem;
            transition: border-color 0.2s;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26,86,219,0.1);
        }
        .form-control.is-invalid { border-color: #ef4444; }
        .invalid-feedback { font-size: 12px; }
        .input-group .form-control { border-right: none; }
        .input-group .input-group-text {
            background: #fff;
            border: 1.5px solid #e5e7eb;
            border-left: none;
            border-radius: 0 10px 10px 0;
            cursor: pointer;
            color: #6b7280;
        }
        .btn-auth {
            background: var(--primary);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            color: #fff;
            width: 100%;
            transition: background 0.2s;
        }
        .btn-auth:hover { background: var(--primary-dark); color: #fff; }
        .divider {
            text-align: center;
            color: #9ca3af;
            font-size: 13px;
            margin: 20px 0;
            position: relative;
        }
        .divider::before, .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 42%;
            height: 1px;
            background: #e5e7eb;
        }
        .divider::before { left: 0; }
        .divider::after { right: 0; }
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <a href="{{ url('/') }}" class="auth-logo">🏏 Cric<span>Auction</span></a>

        @if(session('success'))
            <div class="alert-success">✅ {{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                <strong>Please fix the errors below:</strong><br>
                {{ $errors->first() }}
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>