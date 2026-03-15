@extends('layouts.guest')

@section('title', 'Sign In — CricAuction')

@section('content')

<div class="auth-title">Welcome back !</div>
<div class="auth-subtitle">Sign in to manage your tournaments</div>

<form method="POST" action="{{ route('login') }}">
    @csrf

    {{-- Email --}}
    <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input
            type="email"
            name="email"
            class="form-control @error('email') is-invalid @enderror"
            placeholder="rahul@example.com"
            value="{{ old('email') }}"
            autofocus
        >
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Password --}}
    <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
            <input
                type="password"
                name="password"
                id="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Your password"
            >
            <span class="input-group-text" onclick="togglePassword()">
                <i class="bi bi-eye" id="eyeIcon"></i>
            </span>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Remember Me --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label" for="remember" style="font-size:14px; color:#374151;">
                Remember me
            </label>
        </div>
        <a href="#" style="font-size:14px; color:#1a56db; font-weight:500; text-decoration:none;">
            Forgot password?
        </a>
    </div>

    <button type="submit" class="btn-auth">
        Sign In
    </button>

    <div class="divider">don't have an account?</div>

    <a href="{{ route('register') }}" class="btn btn-outline-secondary w-100" style="border-radius:10px; font-weight:600;">
        Create Free Account 🏏
    </a>

</form>

@endsection

@push('scripts')
<script>
function togglePassword() {
    const field = document.getElementById('password');
    const icon  = document.getElementById('eyeIcon');
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        field.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
@endpush