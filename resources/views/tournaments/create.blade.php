@extends('layouts.app')

@section('title', isset($tournament) ? 'Edit Tournament — CricAuction' : 'Create Tournament — CricAuction')

@section('content')

    <div style="background:#f9fafb; min-height:calc(100vh - 62px); padding: 40px 0;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    {{-- Back --}}
                    <a href="{{ route('dashboard') }}"
                        style="display:inline-flex; align-items:center; gap:6px; color:#6b7280;
              font-size:14px; font-weight:500; text-decoration:none; margin-bottom:24px;">
                        <i class="bi bi-arrow-left"></i> Back to Dashboard
                    </a>

                    {{-- Header --}}
                    <div style="margin-bottom:28px;">
                        <h1 style="font-size:1.7rem; font-weight:800; color:#111827; margin:0;">
                            {{ isset($tournament) ? '✏️ Edit Tournament' : '🏆 Create Tournament' }}
                        </h1>
                        <p style="color:#6b7280; margin:6px 0 0; font-size:0.9rem;">
                            {{ isset($tournament) ? 'Update your tournament details below.' : 'Set up your cricket tournament. You can add teams and players after.' }}
                        </p>
                    </div>

                    {{-- Errors --}}
                    @if ($errors->any())
                        <div class="alert-error mb-4">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            <strong>Please fix the following:</strong>
                            <ul style="margin:8px 0 0; padding-left:20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST"
                        action="{{ isset($tournament) ? route('tournaments.update', $tournament->id) : route('tournaments.store') }}">
                        @csrf
                        @if (isset($tournament))
                            @method('PUT')
                        @endif

                        {{-- Card 1: Basic Info --}}
                        <div class="form-card mb-4">
                            <div class="form-card-header">
                                <div class="form-card-icon" style="background:#eff6ff; color:#1a56db;">
                                    <i class="bi bi-info-circle"></i>
                                </div>
                                <div>
                                    <div class="form-card-title">Basic Information</div>
                                    <div class="form-card-subtitle">Name and description of your tournament</div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="field-label">
                                    Tournament Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" id="tournamentName"
                                    class="field-input @error('name') is-invalid @enderror"
                                    placeholder="e.g. IPL Locals 2025, Box Cricket Premier League"
                                    value="{{ old('name', $tournament->name ?? '') }}" maxlength="100" autofocus>
                                <div class="field-hint">This will be the public name of your tournament</div>
                                @error('name')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Slug Preview --}}
                            <div class="mb-4">
                                <label class="field-label">Public Link</label>
                                <div
                                    style="display:flex; align-items:center; background:#f9fafb;
                            border:1.5px solid #e5e7eb; border-radius:10px; overflow:hidden;">
                                    <span
                                        style="padding:10px 14px; background:#f3f4f6; color:#6b7280;
                                 font-size:13px; border-right:1px solid #e5e7eb; white-space:nowrap;">
                                        {{ url('/t') }}/
                                    </span>
                                    <span id="slugPreview"
                                        style="padding:10px 14px; color:#1a56db; font-size:13px; font-weight:600;">
                                        {{ isset($tournament) ? $tournament->slug : 'your-tournament-name' }}
                                    </span>
                                </div>
                                <div class="field-hint">Auto-generated from tournament name. Players use this to register.
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="field-label">Description</label>
                                <textarea name="description" class="field-input @error('description') is-invalid @enderror" rows="3"
                                    placeholder="Brief description about the tournament, venue, dates, rules etc." maxlength="500"
                                    style="resize:vertical;">{{ old('description', $tournament->description ?? '') }}</textarea>
                                <div class="d-flex justify-content-between">
                                    <div class="field-hint">Optional but helps players understand the tournament</div>
                                    <div id="descCount" style="font-size:12px; color:#9ca3af;">0/500</div>
                                </div>
                                @error('description')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Card 2: Teams & Budget --}}
                        <div class="form-card mb-4">
                            <div class="form-card-header">
                                <div class="form-card-icon" style="background:#f0fdf4; color:#16a34a;">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div>
                                    <div class="form-card-title">Teams & Budget</div>
                                    <div class="form-card-subtitle">How many teams and what's the auction budget</div>
                                </div>
                            </div>

                            <div class="row g-4">
                                {{-- Total Teams --}}
                                <div class="col-md-6">
                                    <label class="field-label">
                                        Number of Teams <span class="text-danger">*</span>
                                    </label>
                                    <div class="number-input-wrapper">
                                        <button type="button" class="num-btn"
                                            onclick="changeValue('total_teams', -1, 2, 32)">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" name="total_teams" id="total_teams"
                                            class="num-input @error('total_teams') is-invalid @enderror"
                                            value="{{ old('total_teams', $tournament->total_teams ?? 8) }}" min="2"
                                            max="32" readonly>
                                        <button type="button" class="num-btn"
                                            onclick="changeValue('total_teams', 1, 2, 32)">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                    <div class="field-hint">Min 2 — Max 32 teams</div>
                                    @error('total_teams')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Max Squad Size --}}
                                <div class="col-md-6">
                                    <label class="field-label">
                                        Max Squad Size <span class="text-danger">*</span>
                                    </label>
                                    <div class="number-input-wrapper">
                                        <button type="button" class="num-btn"
                                            onclick="changeValue('max_squad_size', -1, 5, 30)">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" name="max_squad_size" id="max_squad_size"
                                            class="num-input @error('max_squad_size') is-invalid @enderror"
                                            value="{{ old('max_squad_size', $tournament->max_squad_size ?? 15) }}"
                                            min="5" max="30" readonly>
                                        <button type="button" class="num-btn"
                                            onclick="changeValue('max_squad_size', 1, 5, 30)">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                    <div class="field-hint">Players per team (5–30)</div>
                                    @error('max_squad_size')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Budget Per Team --}}
                                <div class="col-12">
                                    <label class="field-label">
                                        Budget Per Team (₹) <span class="text-danger">*</span>
                                    </label>
                                    <div style="position:relative;">
                                        <span
                                            style="position:absolute; left:14px; top:50%; transform:translateY(-50%);
                                     font-size:16px; font-weight:700; color:#374151;">₹</span>
                                        <input type="number" name="budget_per_team" id="budget_per_team"
                                            class="field-input @error('budget_per_team') is-invalid @enderror"
                                            style="padding-left:32px;" placeholder="500000"
                                            value="{{ old('budget_per_team', $tournament->budget_per_team ?? '') }}"
                                            min="1000" step="1000">
                                    </div>

                                    {{-- Quick Select --}}
                                    <div style="display:flex; flex-wrap:wrap; gap:8px; margin-top:10px;">
                                        @foreach ([50000, 100000, 250000, 500000, 1000000, 2000000] as $amt)
                                            <button type="button" class="quick-amt-btn"
                                                onclick="setBudget({{ $amt }})">
                                                {{ $amt >= 100000 ? '₹' . $amt / 100000 . 'L' : '₹' . $amt / 1000 . 'K' }}
                                            </button>
                                        @endforeach
                                    </div>

                                    {{-- Budget Preview --}}
                                    <div id="budgetPreview"
                                        style="display:none; margin-top:12px;
                         background:#f0fdf4; border:1px solid #bbf7d0;
                         border-radius:10px; padding:12px 16px;">
                                        <div style="font-size:13px; color:#16a34a; font-weight:600;">
                                            💰 Budget Summary
                                        </div>
                                        <div style="font-size:13px; color:#374151; margin-top:6px;">
                                            Total auction pool:
                                            <strong id="totalPool">₹0</strong>
                                            across <span id="teamsCount">0</span> teams
                                        </div>
                                    </div>

                                    <div class="field-hint">Each team gets this budget for the auction</div>
                                    @error('budget_per_team')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Card 3: Auction Mode --}}
                        <div class="form-card mb-4">
                            <div class="form-card-header">
                                <div class="form-card-icon" style="background:#fefce8; color:#ca8a04;">
                                    <i class="bi bi-lightning-charge"></i>
                                </div>
                                <div>
                                    <div class="form-card-title">Auction Mode</div>
                                    <div class="form-card-subtitle">How will players be auctioned?</div>
                                </div>
                            </div>

                            <div class="row g-3">
                                {{-- Manual --}}
                                <div class="col-md-4">
                                    <label class="auction-mode-card @error('auction_mode') border-danger @enderror">
                                        <input type="radio" name="auction_mode" value="manual"
                                            {{ old('auction_mode', $tournament->auction_mode ?? 'manual') === 'manual' ? 'checked' : '' }}>
                                        <div class="mode-icon" style="background:#eff6ff;">⚙️</div>
                                        <div class="mode-title">Manual</div>
                                        <div class="mode-desc">Creator sets price and assigns players to teams directly
                                        </div>
                                        <div class="mode-check"><i class="bi bi-check-circle-fill"></i></div>
                                    </label>
                                </div>

                                {{-- Live --}}
                                <div class="col-md-4">
                                    <label class="auction-mode-card @error('auction_mode') border-danger @enderror">
                                        <input type="radio" name="auction_mode" value="live"
                                            {{ old('auction_mode', $tournament->auction_mode ?? '') === 'live' ? 'checked' : '' }}>
                                        <div class="mode-icon" style="background:#fef9c3;">⚡</div>
                                        <div class="mode-title">Live Bidding</div>
                                        <div class="mode-desc">Team owners bid in real-time for each player</div>
                                        <div class="mode-check"><i class="bi bi-check-circle-fill"></i></div>
                                    </label>
                                </div>

                                {{-- Both --}}
                                <div class="col-md-4">
                                    <label class="auction-mode-card @error('auction_mode') border-danger @enderror">
                                        <input type="radio" name="auction_mode" value="both"
                                            {{ old('auction_mode', $tournament->auction_mode ?? '') === 'both' ? 'checked' : '' }}>
                                        <div class="mode-icon" style="background:#fdf4ff;">🔀</div>
                                        <div class="mode-title">Both Modes</div>
                                        <div class="mode-desc">Switch between manual and live bidding anytime</div>
                                        <div class="mode-check"><i class="bi bi-check-circle-fill"></i></div>
                                    </label>
                                </div>
                            </div>
                            @error('auction_mode')
                                <div class="field-error mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Submit --}}
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-4"
                                style="border-radius:10px; font-weight:600;">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-5 py-2"
                                style="border-radius:10px; font-weight:700; font-size:0.95rem;">
                                {{ isset($tournament) ? 'Update Tournament' : 'Create Tournament 🚀' }}
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
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 14px;
        }

        /* Form Card */
        .form-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 28px;
        }

        .form-card-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f3f4f6;
        }

        .form-card-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .form-card-title {
            font-weight: 700;
            font-size: 1rem;
            color: #111827;
        }

        .form-card-subtitle {
            font-size: 13px;
            color: #6b7280;
        }

        /* Fields */
        .field-label {
            display: block;
            font-weight: 600;
            font-size: 14px;
            color: #374151;
            margin-bottom: 8px;
        }

        .field-input {
            width: 100%;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px;
            color: #111827;
            transition: border-color 0.2s;
            outline: none;
            font-family: 'Inter', sans-serif;
        }

        .field-input:focus {
            border-color: #1a56db;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.08);
        }

        .field-input.is-invalid {
            border-color: #ef4444;
        }

        .field-hint {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 6px;
        }

        .field-error {
            font-size: 12px;
            color: #ef4444;
            margin-top: 6px;
            font-weight: 500;
        }

        /* Number Input */
        .number-input-wrapper {
            display: flex;
            align-items: center;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            width: fit-content;
        }

        .num-btn {
            width: 40px;
            height: 42px;
            border: none;
            background: #f9fafb;
            color: #374151;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.15s;
        }

        .num-btn:hover {
            background: #f3f4f6;
        }

        .num-input {
            width: 64px;
            height: 42px;
            border: none;
            border-left: 1.5px solid #e5e7eb;
            border-right: 1.5px solid #e5e7eb;
            text-align: center;
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            outline: none;
        }

        /* Quick Amount Buttons */
        .quick-amt-btn {
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            border: 1.5px solid #e5e7eb;
            background: #fff;
            color: #374151;
            cursor: pointer;
            transition: all 0.15s;
        }

        .quick-amt-btn:hover,
        .quick-amt-btn.active {
            background: #eff6ff;
            border-color: #1a56db;
            color: #1a56db;
        }

        /* Auction Mode Cards */
        .auction-mode-card {
            display: block;
            border: 2px solid #e5e7eb;
            border-radius: 14px;
            padding: 20px 16px;
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
            text-align: center;
            height: 100%;
        }

        .auction-mode-card input[type="radio"] {
            display: none;
        }

        .auction-mode-card:hover {
            border-color: #93c5fd;
            background: #fafeff;
        }

        .auction-mode-card:has(input:checked) {
            border-color: #1a56db;
            background: #eff6ff;
        }

        .mode-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin: 0 auto 12px;
        }

        .mode-title {
            font-weight: 700;
            font-size: 14px;
            color: #111827;
            margin-bottom: 6px;
        }

        .mode-desc {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.5;
        }

        .mode-check {
            position: absolute;
            top: 12px;
            right: 12px;
            color: #1a56db;
            font-size: 18px;
            display: none;
        }

        .auction-mode-card:has(input:checked) .mode-check {
            display: block;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Slug preview
        document.getElementById('tournamentName').addEventListener('input', function() {
            const slug = this.value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            document.getElementById('slugPreview').textContent = slug || 'your-tournament-name';
        });

        // Description counter
        const desc = document.querySelector('textarea[name="description"]');
        const descCount = document.getElementById('descCount');
        if (desc) {
            descCount.textContent = desc.value.length + '/500';
            desc.addEventListener('input', function() {
                descCount.textContent = this.value.length + '/500';
            });
        }

        // Number stepper
        function changeValue(id, delta, min, max) {
            const input = document.getElementById(id);
            let val = parseInt(input.value) + delta;
            if (val < min) val = min;
            if (val > max) val = max;
            input.value = val;
            updateBudgetPreview();
        }

        // Quick budget select
        function setBudget(amount) {
            document.getElementById('budget_per_team').value = amount;
            document.querySelectorAll('.quick-amt-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            updateBudgetPreview();
        }

        // Budget preview
        function updateBudgetPreview() {
            const budget = parseFloat(document.getElementById('budget_per_team').value);
            const teams = parseInt(document.getElementById('total_teams').value);
            const preview = document.getElementById('budgetPreview');

            if (budget >= 1000 && teams >= 2) {
                const total = budget * teams;
                document.getElementById('totalPool').textContent = formatRupee(total);
                document.getElementById('teamsCount').textContent = teams;
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        }

        function formatRupee(amount) {
            if (amount >= 10000000) return '₹' + (amount / 10000000).toFixed(1) + ' Cr';
            if (amount >= 100000) return '₹' + (amount / 100000).toFixed(1) + ' L';
            if (amount >= 1000) return '₹' + (amount / 1000).toFixed(0) + 'K';
            return '₹' + amount;
        }

        // Budget input live update
        document.getElementById('budget_per_team').addEventListener('input', updateBudgetPreview);

        // Init on page load (for edit mode)
        updateBudgetPreview();

        // Highlight active quick btn on load
        const currentBudget = document.getElementById('budget_per_team').value;
        document.querySelectorAll('.quick-amt-btn').forEach(btn => {
            if (btn.getAttribute('onclick') === 'setBudget(' + currentBudget + ')') {
                btn.classList.add('active');
            }
        });
    </script>
@endpush
