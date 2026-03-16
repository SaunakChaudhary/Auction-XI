@extends('layouts.app')

@section('title', 'Auction — ' . $tournament->name)

@section('content')

    <div style="background:#f9fafb; min-height:calc(100vh - 62px);">

        {{-- Header --}}
        <div style="background:#111827; padding:20px 0;">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('tournaments.show', $tournament->id) }}"
                            style="width:36px; height:36px; border-radius:10px;
                              border:1px solid rgba(255,255,255,0.15);
                              display:flex; align-items:center; justify-content:center;
                              color:rgba(255,255,255,0.7); text-decoration:none;">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div>
                            <h1 style="font-size:1.2rem; font-weight:800; color:#fff; margin:0;">
                                ⚡ Auction Panel — {{ $tournament->name }}
                            </h1>
                            <p style="color:rgba(255,255,255,0.5); margin:3px 0 0; font-size:13px;">
                                {{ ucfirst($tournament->auction_mode) }} Mode ·
                                {{ $stats['sold'] }} / {{ $stats['total_players'] }} players sold
                            </p>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('auction.results', $tournament->id) }}"
                            style="border-radius:8px; font-weight:600; font-size:13px; padding:8px 16px;
                              background:rgba(255,255,255,0.1); color:#fff; text-decoration:none;
                              border:1px solid rgba(255,255,255,0.15);">
                            <i class="bi bi-bar-chart me-1"></i> Results
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Bar --}}
        <div style="background:#1f2937; border-bottom:1px solid #374151; padding:14px 0;">
            <div class="container">
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="auction-stat">
                            <div class="auction-stat-val" style="color:#60a5fa;">
                                {{ $stats['total_players'] }}
                            </div>
                            <div class="auction-stat-lbl">Total Players</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="auction-stat">
                            <div class="auction-stat-val" style="color:#34d399;">
                                {{ $stats['available'] }}
                            </div>
                            <div class="auction-stat-lbl">Available</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="auction-stat">
                            <div class="auction-stat-val" style="color:#fbbf24;">
                                {{ $stats['sold'] }}
                            </div>
                            <div class="auction-stat-lbl">Sold</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="auction-stat">
                            <div class="auction-stat-val" style="color:#f87171;">
                                @php
                                    $spent = $stats['total_spent'];
                                    echo $spent >= 100000
                                        ? '₹' . number_format($spent / 100000, 1) . 'L'
                                        : '₹' . number_format($spent / 1000, 0) . 'K';
                                @endphp
                            </div>
                            <div class="auction-stat-lbl">Total Spent</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container py-4">

            {{-- Flash --}}
            @if (session('success'))
                <div class="flash-success mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="flash-error mb-4">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                </div>
            @endif

            {{-- No players warning --}}
            @if ($stats['total_players'] === 0)
                <div
                    style="background:#fff; border:2px dashed #e5e7eb; border-radius:16px;
                        padding:48px 24px; text-align:center; margin-bottom:24px;">
                    <div style="font-size:3rem; margin-bottom:12px;">👤</div>
                    <div style="font-weight:700; color:#374151; font-size:1.1rem; margin-bottom:8px;">
                        No players registered yet
                    </div>
                    <div style="font-size:14px; color:#6b7280; margin-bottom:20px;">
                        Open registration and let players register, or import from Google Sheets
                    </div>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ route('tournaments.show', $tournament->id) }}" class="btn btn-primary px-4"
                            style="border-radius:10px; font-weight:600;">
                            Manage Tournament
                        </a>
                        <a href="{{ route('import.index', $tournament->id) }}" class="btn btn-outline-secondary px-4"
                            style="border-radius:10px; font-weight:600;">
                            Import from Sheets
                        </a>
                    </div>
                </div>
            @endif

            <div class="row g-4">

                {{-- LEFT: Sell Player Form --}}
                <div class="col-lg-5">

                    {{-- Sell Form --}}
                    <div class="auction-card mb-4">
                        <div class="auction-card-header">
                            <i class="bi bi-hammer me-2" style="color:#f59e0b;"></i>
                            <span>Sell Player</span>
                        </div>

                        <form method="POST" action="{{ route('auction.sell', $tournament->id) }}" id="sellForm">
                            @csrf

                            {{-- Player Select --}}
                            <div class="mb-3">
                                <label class="a-label">Select Player <span class="text-danger">*</span></label>
                                <select name="player_id" id="playerSelect"
                                    class="a-input @error('player_id') is-invalid @enderror"
                                    onchange="updatePlayerInfo(this)">
                                    <option value="">Choose a player...</option>
                                    @foreach ($players->whereIn('status', ['available', 'registered']) as $player)
                                        <option value="{{ $player->id }}" data-base="{{ $player->base_price }}"
                                            data-role="{{ $player->roleLabel }}" data-city="{{ $player->city }}"
                                            data-age="{{ $player->age }}"
                                            {{ old('player_id') == $player->id ? 'selected' : '' }}>
                                            {{ $player->name }}
                                            @if ($player->base_price > 0)
                                                (Base: ₹{{ number_format($player->base_price) }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Player Info Preview --}}
                            <div id="playerInfoBox"
                                style="display:none; background:#f9fafb; border:1px solid #e5e7eb;
                                    border-radius:10px; padding:14px; margin-bottom:16px;">
                                <div style="display:flex; align-items:center; gap:12px;">
                                    <div id="playerAvatar"
                                        style="width:44px; height:44px; border-radius:50%;
                                            background:#eff6ff; color:#1a56db;
                                            display:flex; align-items:center; justify-content:center;
                                            font-weight:800; font-size:14px; flex-shrink:0;">
                                    </div>
                                    <div>
                                        <div id="playerInfoName" style="font-weight:700; font-size:14px; color:#111827;">
                                        </div>
                                        <div id="playerInfoMeta" style="font-size:12px; color:#6b7280; margin-top:2px;">
                                        </div>
                                    </div>
                                    <div style="margin-left:auto; text-align:right;">
                                        <div style="font-size:11px; color:#6b7280;">Base Price</div>
                                        <div id="playerBasePrice" style="font-size:1.1rem; font-weight:800; color:#1a56db;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Team Select --}}
                            <div class="mb-3">
                                <label class="a-label">Assign to Team <span class="text-danger">*</span></label>
                                <select name="team_id" id="teamSelect"
                                    class="a-input @error('team_id') is-invalid @enderror"
                                    onchange="updateTeamBudget(this)">
                                    <option value="">Choose a team...</option>
                                    @foreach ($teams as $team)
                                        @php
                                            $remaining = $team->budget - $team->spent;
                                            $squadCount = $team->players()->count();
                                            $isFull = $squadCount >= $tournament->max_squad_size;
                                        @endphp
                                        <option value="{{ $team->id }}" data-remaining="{{ $remaining }}"
                                            data-squad="{{ $squadCount }}" data-color="{{ $team->color }}"
                                            {{ $isFull ? 'disabled' : '' }}
                                            {{ old('team_id') == $team->id ? 'selected' : '' }}>
                                            {{ $team->name }}
                                            (₹{{ $remaining >= 100000 ? number_format($remaining / 100000, 1) . 'L' : number_format($remaining / 1000, 0) . 'K' }}
                                            left
                                            · {{ $squadCount }}/{{ $tournament->max_squad_size }})
                                            {{ $isFull ? '[FULL]' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Team Budget Info --}}
                            <div id="teamBudgetBox"
                                style="display:none; border-radius:10px; padding:12px 14px;
                                    margin-bottom:16px; border:1px solid #e5e7eb; background:#fff;">
                                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                                    <span style="font-size:13px; color:#6b7280;">Remaining Budget</span>
                                    <span id="teamRemainingBudget"
                                        style="font-size:13px; font-weight:700; color:#16a34a;"></span>
                                </div>
                                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                                    <span style="font-size:13px; color:#6b7280;">Squad Size</span>
                                    <span id="teamSquadSize"
                                        style="font-size:13px; font-weight:700; color:#374151;"></span>
                                </div>
                                <div style="background:#f3f4f6; border-radius:6px; height:6px;">
                                    <div id="teamBudgetBar"
                                        style="height:6px; border-radius:6px; background:#1a56db;
                                            transition:width 0.3s; width:0%;">
                                    </div>
                                </div>
                            </div>

                            {{-- Sold Price --}}
                            <div class="mb-4">
                                <label class="a-label">
                                    Final Sold Price (₹) <span class="text-danger">*</span>
                                </label>
                                <div style="position:relative;">
                                    <span
                                        style="position:absolute; left:14px; top:50%;
                                             transform:translateY(-50%); font-weight:700;
                                             color:#374151; font-size:16px;">₹</span>
                                    <input type="number" name="sold_price" id="soldPrice"
                                        class="a-input @error('sold_price') is-invalid @enderror"
                                        style="padding-left:30px; font-size:1.2rem; font-weight:700;" placeholder="0"
                                        min="0" step="1000" value="{{ old('sold_price') }}"
                                        oninput="validateSoldPrice()">
                                </div>

                                {{-- Quick Price Buttons --}}
                                <div style="display:flex; flex-wrap:wrap; gap:6px; margin-top:8px;"
                                    id="quickPriceButtons">
                                </div>

                                {{-- Validation Message --}}
                                <div id="priceValidation" style="margin-top:6px; font-size:12px; font-weight:500;"></div>
                            </div>

                            <button type="submit" id="sellBtn" class="btn btn-warning w-100 py-2"
                                style="border-radius:10px; font-weight:800; font-size:1rem;
                                       border:none; background:#f59e0b; color:#fff;">
                                <i class="bi bi-hammer me-2"></i>SOLD!
                            </button>

                        </form>
                    </div>

                    {{-- Mark Unsold Form --}}
                    <div class="auction-card">
                        <div class="auction-card-header">
                            <i class="bi bi-x-circle me-2" style="color:#ef4444;"></i>
                            <span>Mark as Unsold</span>
                        </div>
                        <form method="POST" action="{{ route('auction.unsold', $tournament->id) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="a-label">Select Player</label>
                                <select name="player_id" class="a-input">
                                    <option value="">Choose a player...</option>
                                    @foreach ($players->whereIn('status', ['available', 'registered']) as $player)
                                        <option value="{{ $player->id }}">{{ $player->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn w-100 py-2"
                                style="border-radius:10px; font-weight:700; font-size:14px;
                                       background:#fef2f2; color:#dc2626; border:1px solid #fecaca;"
                                onclick="return confirm('Mark this player as unsold?')">
                                <i class="bi bi-x-circle me-1"></i> Mark Unsold
                            </button>
                        </form>
                    </div>

                </div>

                {{-- RIGHT: Teams Budget + Players List --}}
                <div class="col-lg-7">

                    {{-- Teams Budget Overview --}}
                    <div class="auction-card mb-4">
                        <div class="auction-card-header">
                            <i class="bi bi-wallet2 me-2" style="color:#8b5cf6;"></i>
                            <span>Teams Budget Tracker</span>
                        </div>
                        @if ($teams->isEmpty())
                            <div style="text-align:center; padding:20px; color:#9ca3af; font-size:14px;">
                                No teams created yet
                            </div>
                        @else
                            <div style="display:flex; flex-direction:column; gap:12px;">
                                @foreach ($teams as $team)
                                    @php
                                        $remaining = $team->budget - $team->spent;
                                        $pct = $team->budget > 0 ? min(100, ($team->spent / $team->budget) * 100) : 0;
                                        $squadCount = $team->players()->count();
                                    @endphp
                                    <div
                                        style="padding:14px; background:#f9fafb; border-radius:12px;
                                            border:1px solid #e5e7eb;">
                                        <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                                            <div
                                                style="width:36px; height:36px; border-radius:9px;
                                                    background:{{ $team->color }}22;
                                                    border:2px solid {{ $team->color }};
                                                    display:flex; align-items:center; justify-content:center;
                                                    font-size:11px; font-weight:800; color:{{ $team->color }};
                                                    flex-shrink:0;">
                                                {{ $team->short_name }}
                                            </div>
                                            <div style="flex:1;">
                                                <div style="font-weight:700; font-size:14px; color:#111827;">
                                                    {{ $team->name }}
                                                </div>
                                                <div style="font-size:12px; color:#6b7280;">
                                                    {{ $squadCount }}/{{ $tournament->max_squad_size }} players
                                                </div>
                                            </div>
                                            <div style="text-align:right;">
                                                <div
                                                    style="font-size:13px; font-weight:700;
                                                        color:{{ $remaining < $team->budget * 0.2 ? '#dc2626' : '#16a34a' }};">
                                                    ₹{{ $remaining >= 100000
                                                        ? number_format($remaining / 100000, 1) . 'L'
                                                        : number_format($remaining / 1000, 0) . 'K' }}
                                                    <span style="font-weight:400; color:#9ca3af;">left</span>
                                                </div>
                                                <div style="font-size:11px; color:#9ca3af;">
                                                    Spent:
                                                    ₹{{ $team->spent >= 100000
                                                        ? number_format($team->spent / 100000, 1) . 'L'
                                                        : number_format($team->spent / 1000, 0) . 'K' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background:#e5e7eb; border-radius:6px; height:6px;">
                                            <div
                                                style="background:{{ $pct > 80 ? '#ef4444' : $team->color }};
                                                    width:{{ $pct }}%; height:6px; border-radius:6px;
                                                    transition:width 0.3s;">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Players Status --}}
                    <div class="auction-card">
                        <div class="auction-card-header">
                            <i class="bi bi-people me-2" style="color:#1a56db;"></i>
                            <span>Players</span>
                            <div style="margin-left:auto; display:flex; gap:6px;">
                                @foreach (['all' => 'All', 'available' => 'Available', 'sold' => 'Sold', 'unsold' => 'Unsold'] as $val => $lbl)
                                    <button class="filter-pill {{ $val === 'all' ? 'active' : '' }}"
                                        onclick="filterAuctionPlayers('{{ $val }}', this)">
                                        {{ $lbl }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        @if ($players->isEmpty())
                            <div style="text-align:center; padding:30px; color:#9ca3af; font-size:14px;">
                                No players found
                            </div>
                        @else
                            <div style="display:flex; flex-direction:column; gap:6px; max-height:500px;
                                    overflow-y:auto;"
                                id="auctionPlayersList">
                                @foreach ($players as $player)
                                    <div class="auction-player-row" data-status="{{ $player->status }}"
                                        style="display:flex; align-items:center; gap:10px;
                                            padding:10px 12px; border-radius:10px;
                                            background:{{ $player->status === 'sold' ? '#f0fdf4' : ($player->status === 'unsold' ? '#fef2f2' : '#f9fafb') }};
                                            border:1px solid {{ $player->status === 'sold' ? '#bbf7d0' : ($player->status === 'unsold' ? '#fecaca' : '#e5e7eb') }};">

                                        <div
                                            style="width:34px; height:34px; border-radius:50%;
                                                background:#eff6ff; color:#1a56db;
                                                display:flex; align-items:center; justify-content:center;
                                                font-weight:700; font-size:11px; flex-shrink:0;">
                                            {{ strtoupper(substr($player->name, 0, 2)) }}
                                        </div>

                                        <div style="flex:1; min-width:0;">
                                            <div
                                                style="font-weight:600; font-size:13px; color:#111827;
                                                    white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                {{ $player->name }}
                                            </div>
                                            <div style="font-size:11px; color:#6b7280;">
                                                {{ $player->roleLabel }}
                                                @if ($player->city)
                                                    · {{ $player->city }}
                                                @endif
                                            </div>
                                        </div>

                                        <div style="text-align:right; flex-shrink:0;">
                                            @if ($player->status === 'sold')
                                                <div style="font-size:13px; font-weight:700; color:#16a34a;">
                                                    ₹{{ $player->sold_price >= 100000
                                                        ? number_format($player->sold_price / 100000, 1) . 'L'
                                                        : number_format($player->sold_price / 1000, 0) . 'K' }}
                                                </div>
                                                <div style="font-size:11px; color:#6b7280;">
                                                    {{ $player->team->short_name ?? '' }}
                                                </div>
                                            @elseif($player->base_price > 0)
                                                <div style="font-size:12px; color:#6b7280;">
                                                    Base:
                                                    ₹{{ $player->base_price >= 100000
                                                        ? number_format($player->base_price / 100000, 1) . 'L'
                                                        : number_format($player->base_price / 1000, 0) . 'K' }}
                                                </div>
                                            @endif
                                        </div>

                                        <span class="ap-badge status-{{ $player->status }}">
                                            {{ ucfirst($player->status) }}
                                        </span>

                                        {{-- Undo button for sold --}}
                                        @if ($player->status === 'sold')
                                            <form method="POST" action="{{ route('auction.undo', $tournament->id) }}"
                                                onsubmit="return confirm('Undo sale for {{ $player->name }}? Budget will be refunded.')">
                                                @csrf
                                                <input type="hidden" name="player_id" value="{{ $player->id }}">
                                                <button type="submit"
                                                    style="border:1px solid #fecaca; background:#fff;
                                                           color:#dc2626; border-radius:6px; padding:4px 8px;
                                                           font-size:11px; cursor:pointer; white-space:nowrap;">
                                                    Undo
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .flash-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .flash-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .auction-stat {
            text-align: center;
        }

        .auction-stat-val {
            font-size: 1.5rem;
            font-weight: 800;
            line-height: 1;
        }

        .auction-stat-lbl {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.4);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }

        .auction-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 20px;
        }

        .auction-card-header {
            display: flex;
            align-items: center;
            font-weight: 700;
            font-size: 15px;
            color: #111827;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 1px solid #f3f4f6;
        }

        .a-label {
            display: block;
            font-weight: 600;
            font-size: 13px;
            color: #374151;
            margin-bottom: 6px;
        }

        .a-input {
            width: 100%;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px;
            color: #111827;
            outline: none;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s;
            background: #fff;
        }

        .a-input:focus {
            border-color: #1a56db;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.08);
        }

        .a-input.is-invalid {
            border-color: #ef4444;
        }

        /* Filter Pills */
        .filter-pill {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid #e5e7eb;
            background: #fff;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.15s;
        }

        .filter-pill.active,
        .filter-pill:hover {
            background: #1a56db;
            color: #fff;
            border-color: #1a56db;
        }

        /* Player Badges */
        .ap-badge {
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .status-registered {
            background: #eff6ff;
            color: #1a56db;
        }

        .status-available {
            background: #dcfce7;
            color: #16a34a;
        }

        .status-sold {
            background: #fef9c3;
            color: #ca8a04;
        }

        .status-unsold {
            background: #fef2f2;
            color: #dc2626;
        }

        /* Quick Price Btn */
        .quick-price-btn {
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid #e5e7eb;
            background: #fff;
            color: #374151;
            cursor: pointer;
            transition: all 0.15s;
        }

        .quick-price-btn:hover {
            background: #eff6ff;
            border-color: #1a56db;
            color: #1a56db;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Player info update
        function updatePlayerInfo(select) {
            const opt = select.options[select.selectedIndex];
            if (!opt.value) {
                document.getElementById('playerInfoBox').style.display = 'none';
                document.getElementById('quickPriceButtons').innerHTML = '';
                return;
            }

            const name = opt.text.split('(')[0].trim();
            const basePrice = parseFloat(opt.dataset.base) || 0;
            const role = opt.dataset.role || '';
            const city = opt.dataset.city || '';
            const age = opt.dataset.age || '';

            document.getElementById('playerAvatar').textContent =
                name.substring(0, 2).toUpperCase();
            document.getElementById('playerInfoName').textContent = name;
            document.getElementById('playerInfoMeta').textContent = [role, city, age ? 'Age ' + age : ''].filter(Boolean)
                .join(' · ');
            document.getElementById('playerBasePrice').textContent =
                basePrice > 0 ? '₹' + formatNum(basePrice) : 'No base price';
            document.getElementById('playerInfoBox').style.display = 'block';

            // Set min price
            document.getElementById('soldPrice').min = basePrice;
            document.getElementById('soldPrice').value = basePrice || '';

            // Quick price buttons
            buildQuickPrices(basePrice);
            validateSoldPrice();
        }

        function buildQuickPrices(base) {
            const container = document.getElementById('quickPriceButtons');
            container.innerHTML = '';
            if (!base || base <= 0) return;

            const multiples = [1, 1.5, 2, 3, 5];
            multiples.forEach(m => {
                const val = Math.round(base * m / 1000) * 1000;
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'quick-price-btn';
                btn.textContent = '₹' + (val >= 100000 ?
                    (val / 100000).toFixed(1) + 'L' :
                    (val / 1000).toFixed(0) + 'K');
                btn.onclick = () => {
                    document.getElementById('soldPrice').value = val;
                    validateSoldPrice();
                };
                container.appendChild(btn);
            });
        }

        // Team budget update
        function updateTeamBudget(select) {
            const opt = select.options[select.selectedIndex];
            if (!opt.value) {
                document.getElementById('teamBudgetBox').style.display = 'none';
                return;
            }

            const remaining = parseFloat(opt.dataset.remaining) || 0;
            const squad = parseInt(opt.dataset.squad) || 0;
            const maxSquad = {{ $tournament->max_squad_size }};
            const totalBudget = {{ $tournament->budget_per_team }};
            const pct = totalBudget > 0 ?
                Math.min(100, ((totalBudget - remaining) / totalBudget) * 100) :
                0;

            document.getElementById('teamRemainingBudget').textContent =
                '₹' + formatNum(remaining);
            document.getElementById('teamRemainingBudget').style.color =
                remaining < totalBudget * 0.2 ? '#dc2626' : '#16a34a';
            document.getElementById('teamSquadSize').textContent =
                squad + ' / ' + maxSquad + ' players';
            document.getElementById('teamBudgetBar').style.width = pct + '%';
            document.getElementById('teamBudgetBar').style.background =
                pct > 80 ? '#ef4444' : '#1a56db';
            document.getElementById('teamBudgetBox').style.display = 'block';

            validateSoldPrice();
        }

        // Validate sold price
        function validateSoldPrice() {
            const priceInput = document.getElementById('soldPrice');
            const price = parseFloat(priceInput.value) || 0;
            const validation = document.getElementById('priceValidation');
            const sellBtn = document.getElementById('sellBtn');

            const playerOpt = document.getElementById('playerSelect').options[
                document.getElementById('playerSelect').selectedIndex
            ];
            const teamOpt = document.getElementById('teamSelect').options[
                document.getElementById('teamSelect').selectedIndex
            ];

            const basePrice = playerOpt ? parseFloat(playerOpt.dataset.base) || 0 : 0;
            const remaining = teamOpt ? parseFloat(teamOpt.dataset.remaining) || 0 : Infinity;

            if (price > 0 && price < basePrice) {
                validation.style.color = '#dc2626';
                validation.textContent = '⚠️ Price is below base price (₹' + formatNum(basePrice) + ')';
                sellBtn.style.opacity = '0.5';
            } else if (price > remaining) {
                validation.style.color = '#dc2626';
                validation.textContent = '⚠️ Exceeds team budget! Only ₹' + formatNum(remaining) + ' remaining.';
                sellBtn.style.opacity = '0.5';
            } else if (price > 0) {
                validation.style.color = '#16a34a';
                validation.textContent = '✅ Valid price';
                sellBtn.style.opacity = '1';
            } else {
                validation.textContent = '';
                sellBtn.style.opacity = '1';
            }
        }

        // Filter players list
        function filterAuctionPlayers(status, btn) {
            document.querySelectorAll('.filter-pill').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            document.querySelectorAll('.auction-player-row').forEach(row => {
                row.style.display =
                    (status === 'all' || row.dataset.status === status) ? '' : 'none';
            });
        }

        function formatNum(n) {
            return new Intl.NumberFormat('en-IN').format(n);
        }
    </script>
@endpush
