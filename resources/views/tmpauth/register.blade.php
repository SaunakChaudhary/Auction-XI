@extends('layouts.guest')

@section('title', 'Create Account — CricAuction')

@section('content')

    <div class="auth-title">Create your account</div>
    <div class="auth-subtitle">Free forever during beta 🎉</div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Name --}}
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                placeholder="Rahul Kumar" value="{{ old('name') }}" autofocus>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                placeholder="rahul@example.com" value="{{ old('email') }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Phone --}}
        <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <div class="input-group">
                <span class="input-group-text"
                    style="border-right:none; border-radius:10px 0 0 10px; border:1.5px solid #e5e7eb; background:#f9fafb; color:#374151; font-weight:600;">+91</span>
                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                    placeholder="9876543210" value="{{ old('phone') }}" maxlength="10"
                    style="border-left:none; border-radius: 0 10px 10px 0;">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
                <input type="password" name="password" id="password"
                    class="form-control @error('password') is-invalid @enderror" placeholder="Min. 8 characters">
                <span class="input-group-text" onclick="togglePassword('password', 'eyeIcon1')">
                    <i class="bi bi-eye" id="eyeIcon1"></i>
                </span>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Confirm Password --}}
        <div class="mb-4">
            <label class="form-label">Confirm Password</label>
            <div class="input-group">
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                    placeholder="Re-enter password">
                <span class="input-group-text" onclick="togglePassword('password_confirmation', 'eyeIcon2')">
                    <i class="bi bi-eye" id="eyeIcon2"></i>
                </span>
            </div>
        </div>

        <button type="submit" class="btn-auth">
            Create Account 🚀
        </button>

        <div class="divider">already have an account?</div>

        <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100" style="border-radius:10px; font-weight:600;">
            Sign In Instead
        </a>
    </form>

@endsection

@push('scripts')
    <script>
        function togglePassword(fieldId, iconId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
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
