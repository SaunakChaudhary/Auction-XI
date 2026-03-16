@extends('layouts.app')

@section('title', isset($team) ? 'Edit Team' : 'Add Team — ' . $tournament->name)

@section('content')

<div style="background:#f9fafb; min-height:calc(100vh - 62px); padding:40px 0;">
<div class="container">
<div class="row justify-content-center">
<div class="col-lg-7">

    {{-- Back --}}
    <a href="{{ route('tournaments.show', $tournament->id) }}"
       style="display:inline-flex; align-items:center; gap:6px; color:#6b7280;
              font-size:14px; font-weight:500; text-decoration:none; margin-bottom:24px;">
        <i class="bi bi-arrow-left"></i> Back to {{ $tournament->name }}
    </a>

    {{-- Progress --}}
    @if(!isset($team))
        @php
            $created  = $tournament->teams()->count();
            $total    = $tournament->total_teams;
            $pct      = $total > 0 ? ($created / $total) * 100 : 0;
        @endphp
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:14px;
                    padding:16px 20px; margin-bottom:24px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                <span style="font-size:14px; font-weight:600; color:#374151;">
                    Teams Progress
                </span>
                <span style="font-size:13px; color:#6b7280;">
                    {{ $created }} / {{ $total }} teams created
                </span>
            </div>
            <div style="background:#f3f4f6; border-radius:8px; height:8px;">
                <div style="background:#1a56db; width:{{ $pct }}%; height:8px;
                            border-radius:8px; transition:width 0.3s;"></div>
            </div>
            @if($created < $total)
                <div style="font-size:12px; color:#6b7280; margin-top:8px;">
                    {{ $total - $created }} more team(s) needed to complete setup
                </div>
            @endif
        </div>
    @endif

    {{-- Header --}}
    <div style="margin-bottom:24px;">
        <h1 style="font-size:1.6rem; font-weight:800; color:#111827; margin:0;">
            {{ isset($team) ? '✏️ Edit Team' : '🛡️ Add New Team' }}
        </h1>
        <p style="color:#6b7280; margin:6px 0 0; font-size:0.9rem;">
            {{ isset($team) ? 'Update team details below.' : 'Add a team to ' . $tournament->name }}
        </p>
    </div>

    {{-- Errors --}}
    @if($errors->any())
        <div style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626;
                    border-radius:12px; padding:14px 18px; font-size:14px; margin-bottom:20px;">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Flash --}}
    @if(session('success'))
        <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a;
                    border-radius:12px; padding:14px 18px; font-size:14px; margin-bottom:20px;">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <form method="POST"
          action="{{ isset($team)
            ? route('tournaments.teams.update', [$tournament->id, $team->id])
            : route('tournaments.teams.store', $tournament->id) }}">
        @csrf
        @if(isset($team)) @method('PUT') @endif

        {{-- Preview Card --}}
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:16px;
                    padding:24px; margin-bottom:20px; text-align:center;">
            <div style="font-size:12px; color:#9ca3af; font-weight:600;
                        letter-spacing:1px; text-transform:uppercase; margin-bottom:16px;">
                Team Preview
            </div>
            <div id="previewBadge"
                 style="display:inline-flex; align-items:center; justify-content:center;
                        width:80px; height:80px; border-radius:20px; margin:0 auto 12px;
                        font-size:24px; font-weight:900; color:#fff;
                        background:{{ $team->color ?? '#1a56db' }};
                        border:3px solid {{ $team->color ?? '#1a56db' }};">
                <span id="previewShort">
                    {{ isset($team) ? $team->short_name : 'TM' }}
                </span>
            </div>
            <div id="previewName"
                 style="font-size:1rem; font-weight:700; color:#111827;">
                {{ isset($team) ? $team->name : 'Team Name' }}
            </div>
            <div style="font-size:13px; color:#6b7280; margin-top:4px;">
                Budget: {{ $tournament->formattedBudget }}
            </div>
        </div>

        {{-- Form Card --}}
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:28px;">

            {{-- Team Name --}}
            <div class="mb-4">
                <label class="field-label">
                    Team Name <span class="text-danger">*</span>
                </label>
                <input
                    type="text"
                    name="name"
                    id="teamName"
                    class="field-input @error('name') is-invalid @enderror"
                    placeholder="e.g. Mumbai Indians, Royal Challengers"
                    value="{{ old('name', $team->name ?? '') }}"
                    maxlength="100"
                    autofocus
                >
                @error('name')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Short Name --}}
            <div class="mb-4">
                <label class="field-label">
                    Short Name <span class="text-danger">*</span>
                </label>
                <input
                    type="text"
                    name="short_name"
                    id="shortName"
                    class="field-input @error('short_name') is-invalid @enderror"
                    placeholder="e.g. MI, CSK, RCB"
                    value="{{ old('short_name', $team->short_name ?? '') }}"
                    maxlength="6"
                    style="text-transform:uppercase; letter-spacing:2px; font-weight:700;"
                >
                <div style="font-size:12px; color:#9ca3af; margin-top:6px;">
                    2–6 letters only. Used as team badge abbreviation.
                </div>
                @error('short_name')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Team Color --}}
            <div class="mb-4">
                <label class="field-label">
                    Team Color <span class="text-danger">*</span>
                </label>

                {{-- Preset Colors --}}
                <div style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:12px;">
                    @foreach([
                        '#1a56db','#16a34a','#dc2626','#9333ea',
                        '#ea580c','#0891b2','#be185d','#854d0e',
                        '#065f46','#1e3a8a','#7c3aed','#b45309'
                    ] as $clr)
                        <button type="button"
                                class="color-preset {{ (old('color', $team->color ?? '#1a56db')) === $clr ? 'active' : '' }}"
                                style="background:{{ $clr }}; border-color:{{ $clr }};"
                                onclick="selectColor('{{ $clr }}')">
                            <i class="bi bi-check-lg" style="color:#fff; font-size:14px;
                               {{ (old('color', $team->color ?? '#1a56db')) === $clr ? '' : 'display:none;' }}"></i>
                        </button>
                    @endforeach
                </div>

                {{-- Custom Color --}}
                <div style="display:flex; align-items:center; gap:10px;">
                    <input
                        type="color"
                        id="colorPicker"
                        value="{{ old('color', $team->color ?? '#1a56db') }}"
                        style="width:44px; height:44px; border:none; border-radius:10px;
                               padding:2px; cursor:pointer; background:none;"
                        onchange="selectColor(this.value)"
                    >
                    <input
                        type="text"
                        name="color"
                        id="colorHex"
                        class="field-input @error('color') is-invalid @enderror"
                        value="{{ old('color', $team->color ?? '#1a56db') }}"
                        style="width:130px; font-family:monospace; font-weight:600; letter-spacing:1px;"
                        placeholder="#1a56db"
                        oninput="syncColor(this.value)"
                    >
                    <span style="font-size:13px; color:#6b7280;">Custom color</span>
                </div>
                @error('color')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

        </div>

        {{-- Buttons --}}
        <div class="d-flex gap-3 justify-content-end mt-4 flex-wrap">
            <a href="{{ route('tournaments.show', $tournament->id) }}"
               class="btn btn-outline-secondary px-4"
               style="border-radius:10px; font-weight:600;">
                Cancel
            </a>

            @if(!isset($team) && $tournament->teams()->count() + 1 < $tournament->total_teams)
                <button type="submit" name="add_another" value="1"
                        class="btn btn-outline-primary px-4"
                        style="border-radius:10px; font-weight:600;">
                    Save & Add Another
                </button>
            @endif

            <button type="submit"
                    class="btn btn-primary px-5"
                    style="border-radius:10px; font-weight:700;">
                {{ isset($team) ? 'Update Team' : 'Add Team 🛡️' }}
            </button>
        </div>

    </form>

</div>
</div>
</div>
</div>

@endsection

@push('styles')
<style>
    .field-label {
        display:block; font-weight:600; font-size:14px;
        color:#374151; margin-bottom:8px;
    }
    .field-input {
        width:100%; border:1.5px solid #e5e7eb; border-radius:10px;
        padding:10px 14px; font-size:14px; color:#111827;
        transition:border-color 0.2s; outline:none;
        font-family:'Inter', sans-serif;
    }
    .field-input:focus { border-color:#1a56db; box-shadow:0 0 0 3px rgba(26,86,219,0.08); }
    .field-input.is-invalid { border-color:#ef4444; }
    .field-error { font-size:12px; color:#ef4444; margin-top:6px; font-weight:500; }

    .color-preset {
        width:38px; height:38px; border-radius:10px;
        border:3px solid transparent; cursor:pointer;
        display:flex; align-items:center; justify-content:center;
        transition:transform 0.15s, border-color 0.15s;
    }
    .color-preset:hover { transform:scale(1.1); }
    .color-preset.active { border-color:#111827 !important; transform:scale(1.1); }
</style>
@endpush

@push('scripts')
<script>
    // Live preview
    document.getElementById('teamName').addEventListener('input', function () {
        document.getElementById('previewName').textContent = this.value || 'Team Name';
    });

    document.getElementById('shortName').addEventListener('input', function () {
        const val = this.value.toUpperCase();
        this.value = val;
        document.getElementById('previewShort').textContent = val || 'TM';
    });

    // Color selection
    function selectColor(hex) {
        if (!/^#[0-9A-Fa-f]{6}$/.test(hex)) return;

        document.getElementById('colorHex').value    = hex;
        document.getElementById('colorPicker').value = hex;

        // Update preview
        document.getElementById('previewBadge').style.background   = hex;
        document.getElementById('previewBadge').style.borderColor  = hex;

        // Update preset active state
        document.querySelectorAll('.color-preset').forEach(btn => {
            const btnColor = btn.style.background;
            const isActive = btnColor === hex ||
                             btn.getAttribute('onclick') === "selectColor('" + hex + "')";
            btn.classList.toggle('active', isActive);
            const icon = btn.querySelector('i');
            if (icon) icon.style.display = isActive ? '' : 'none';
        });
    }

    function syncColor(val) {
        if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
            selectColor(val);
        }
    }

    // Init preview on load
    selectColor('{{ old('color', $team->color ?? '#1a56db') }}');
</script>
@endpush