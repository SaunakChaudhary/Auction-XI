@extends('layouts.guest')

@section('title', 'Register — ' . $tournament->name)

@section('content')

{{-- Override guest layout max-width for this page --}}
<style>
    body { padding: 32px 16px; }
    .auth-card { max-width: 600px; padding: 36px; }
</style>

{{-- Tournament Header --}}
<div style="text-align:center; margin-bottom:28px;">
    <div style="display:inline-flex; align-items:center; justify-content:center;
                width:60px; height:60px; background:#eff6ff; border-radius:16px;
                font-size:28px; margin-bottom:12px;">🏏</div>
    <h1 style="font-size:1.4rem; font-weight:800; color:#111827; margin:0;">
        {{ $tournament->name }}
    </h1>
    <p style="color:#6b7280; font-size:14px; margin:6px 0 0;">
        Player Registration Form
    </p>
    <div style="display:inline-flex; align-items:center; gap:6px; margin-top:10px;
                background:#dcfce7; color:#16a34a; border-radius:20px;
                padding:4px 14px; font-size:12px; font-weight:600;">
        <i class="bi bi-circle-fill" style="font-size:7px;"></i>
        Registration Open
    </div>
</div>

{{-- Success Message --}}
@if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a;
                border-radius:12px; padding:16px 18px; margin-bottom:24px; text-align:center;">
        <div style="font-size:2rem; margin-bottom:8px;">🎉</div>
        <div style="font-weight:700; font-size:15px; margin-bottom:4px;">You're Registered!</div>
        <div style="font-size:13px;">{{ session('success') }}</div>
    </div>
@endif

<form method="POST"
      action="{{ route('player.register', $tournament->slug) }}"
      enctype="multipart/form-data">
    @csrf

    {{-- Section: Personal Info --}}
    <div class="reg-section">
        <div class="reg-section-title">
            <i class="bi bi-person me-2"></i>Personal Information
        </div>

        <div class="row g-3">
            <div class="col-12">
                <label class="field-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="name"
                       class="field-input @error('name') is-invalid @enderror"
                       placeholder="Your full name"
                       value="{{ old('name') }}" autofocus>
                @error('name')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="field-label">Phone Number <span class="text-danger">*</span></label>
                <div style="display:flex; border:1.5px solid #e5e7eb; border-radius:10px; overflow:hidden;">
                    <span style="padding:10px 12px; background:#f9fafb; color:#374151;
                                 font-size:14px; font-weight:600; border-right:1px solid #e5e7eb;
                                 white-space:nowrap;">+91</span>
                    <input type="tel" name="phone"
                           class="field-input @error('phone') is-invalid @enderror"
                           style="border:none; border-radius:0;"
                           placeholder="9876543210"
                           value="{{ old('phone') }}" maxlength="10">
                </div>
                @error('phone')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="field-label">Age <span class="text-danger">*</span></label>
                <input type="number" name="age"
                       class="field-input @error('age') is-invalid @enderror"
                       placeholder="25" min="10" max="60"
                       value="{{ old('age') }}">
                @error('age')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="field-label">City <span class="text-danger">*</span></label>
                <input type="text" name="city"
                       class="field-input @error('city') is-invalid @enderror"
                       placeholder="Mumbai, Ahmedabad..."
                       value="{{ old('city') }}">
                @error('city')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="field-label">Email <span style="color:#9ca3af;">(Optional)</span></label>
                <input type="email" name="email"
                       class="field-input @error('email') is-invalid @enderror"
                       placeholder="you@example.com"
                       value="{{ old('email') }}">
                @error('email')<div class="field-error">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- Section: Playing Details --}}
    <div class="reg-section">
        <div class="reg-section-title">
            <i class="bi bi-trophy me-2"></i>Playing Details
        </div>

        {{-- Role --}}
        <div class="mb-4">
            <label class="field-label">Playing Role <span class="text-danger">*</span></label>
            <div class="role-grid">
                @foreach([
                    'batsman'       => ['🏏', 'Batsman'],
                    'bowler'        => ['⚡', 'Bowler'],
                    'all_rounder'   => ['🔄', 'All-rounder'],
                    'wicket_keeper' => ['🥊', 'Wicket Keeper'],
                ] as $val => [$icon, $label])
                    <label class="role-card">
                        <input type="radio" name="role" value="{{ $val }}"
                               {{ old('role') === $val ? 'checked' : '' }}>
                        <div class="role-icon">{{ $icon }}</div>
                        <div class="role-label">{{ $label }}</div>
                        <div class="role-check"><i class="bi bi-check-circle-fill"></i></div>
                    </label>
                @endforeach
            </div>
            @error('role')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        {{-- Batting Style --}}
        <div class="mb-4">
            <label class="field-label">Batting Style <span class="text-danger">*</span></label>
            <div style="display:flex; gap:12px;">
                @foreach(['right_hand' => '🏏 Right Hand', 'left_hand' => '🏏 Left Hand'] as $val => $label)
                    <label class="style-pill">
                        <input type="radio" name="batting_style" value="{{ $val }}"
                               {{ old('batting_style') === $val ? 'checked' : '' }}>
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            @error('batting_style')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        {{-- Bowling Style --}}
        <div class="mb-2">
            <label class="field-label">Bowling Style <span class="text-danger">*</span></label>
            <select name="bowling_style"
                    class="field-input @error('bowling_style') is-invalid @enderror">
                <option value="">Select bowling style</option>
                @foreach([
                    'right_arm_fast' => 'Right Arm Fast',
                    'right_arm_spin' => 'Right Arm Spin',
                    'left_arm_fast'  => 'Left Arm Fast',
                    'left_arm_spin'  => 'Left Arm Spin',
                    'none'           => 'Does Not Bowl',
                ] as $val => $label)
                    <option value="{{ $val }}" {{ old('bowling_style') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('bowling_style')<div class="field-error">{{ $message }}</div>@enderror
        </div>
    </div>

    {{-- Section: Photo --}}
    <div class="reg-section">
        <div class="reg-section-title">
            <i class="bi bi-camera me-2"></i>Profile Photo
            <span style="font-size:12px; color:#9ca3af; font-weight:400; margin-left:6px;">(Optional)</span>
        </div>

        <div id="photoDropzone" class="photo-dropzone"
             onclick="document.getElementById('photoInput').click()">
            <div id="photoPreviewWrap" style="display:none; text-align:center;">
                <img id="photoPreview"
                     style="width:80px; height:80px; border-radius:50%;
                            object-fit:cover; margin-bottom:8px;">
                <div style="font-size:13px; color:#374151; font-weight:600;" id="photoName"></div>
            </div>
            <div id="photoPlaceholder">
                <i class="bi bi-cloud-upload" style="font-size:2rem; color:#9ca3af;"></i>
                <div style="font-size:14px; color:#374151; font-weight:500; margin-top:8px;">
                    Click to upload photo
                </div>
                <div style="font-size:12px; color:#9ca3af; margin-top:4px;">
                    JPG or PNG · Max 2MB
                </div>
            </div>
        </div>
        <input type="file" name="photo" id="photoInput"
               accept="image/jpeg,image/png,image/jpg"
               style="display:none" onchange="previewPhoto(this)">
        @error('photo')<div class="field-error mt-2">{{ $message }}</div>@enderror
    </div>

    {{-- Submit --}}
    <button type="submit" class="btn-auth mt-2">
        Complete Registration 🏏
    </button>

    <p style="text-align:center; font-size:12px; color:#9ca3af; margin-top:14px;">
        By registering you agree to participate in {{ $tournament->name }}
    </p>

</form>

@push('styles')
<style>
    .reg-section {
        border:1px solid #e5e7eb; border-radius:14px;
        padding:20px; margin-bottom:20px;
    }
    .reg-section-title {
        font-size:14px; font-weight:700; color:#374151;
        margin-bottom:16px; display:flex; align-items:center;
    }
    .field-label {
        display:block; font-weight:600; font-size:13px;
        color:#374151; margin-bottom:6px;
    }
    .field-input {
        width:100%; border:1.5px solid #e5e7eb; border-radius:10px;
        padding:10px 14px; font-size:14px; color:#111827;
        transition:border-color 0.2s; outline:none;
        font-family:'Inter',sans-serif; background:#fff;
    }
    .field-input:focus { border-color:#1a56db; box-shadow:0 0 0 3px rgba(26,86,219,0.08); }
    .field-input.is-invalid { border-color:#ef4444; }
    .field-error { font-size:12px; color:#ef4444; margin-top:5px; font-weight:500; }

    /* Role Cards */
    .role-grid {
        display:grid; grid-template-columns:repeat(2, 1fr); gap:10px;
    }
    .role-card {
        border:2px solid #e5e7eb; border-radius:12px;
        padding:14px 10px; cursor:pointer; text-align:center;
        position:relative; transition:all 0.2s;
    }
    .role-card input { display:none; }
    .role-card:hover { border-color:#93c5fd; background:#fafeff; }
    .role-card:has(input:checked) { border-color:#1a56db; background:#eff6ff; }
    .role-icon  { font-size:1.6rem; margin-bottom:6px; }
    .role-label { font-size:13px; font-weight:600; color:#374151; }
    .role-check {
        position:absolute; top:8px; right:8px;
        color:#1a56db; font-size:16px; display:none;
    }
    .role-card:has(input:checked) .role-check { display:block; }

    /* Style Pills */
    .style-pill {
        flex:1; border:2px solid #e5e7eb; border-radius:10px;
        padding:10px; cursor:pointer; text-align:center;
        transition:all 0.2s;
    }
    .style-pill input { display:none; }
    .style-pill:has(input:checked) { border-color:#1a56db; background:#eff6ff; }
    .style-pill span { font-size:13px; font-weight:600; color:#374151; }

    /* Photo Dropzone */
    .photo-dropzone {
        border:2px dashed #e5e7eb; border-radius:14px;
        padding:28px; text-align:center; cursor:pointer;
        transition:border-color 0.2s;
    }
    .photo-dropzone:hover { border-color:#1a56db; background:#fafeff; }
</style>
@endpush

@push('scripts')
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('photoPreview').src = e.target.result;
            document.getElementById('photoName').textContent = input.files[0].name;
            document.getElementById('photoPreviewWrap').style.display = 'block';
            document.getElementById('photoPlaceholder').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush

@endsection