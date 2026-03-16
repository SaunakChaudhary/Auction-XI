@extends('layouts.app')

@section('title', 'Auction Results — ' . $tournament->name)

@section('content')

<div style="background:#f9fafb; min-height:calc(100vh - 62px);">

    {{-- Header --}}
    <div style="background:#111827; padding:20px 0;">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('auction.index', $tournament->id) }}"
                       style="width:36px; height:36px; border-radius:10px;
                              border:1px solid rgba(255,255,255,0.15);
                              display:flex; align-items:center; justify-content:center;
                              color:rgba(255,255,255,0.7); text-decoration:none;">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div>
                        <h1 style="font-size:1.2rem; font-weight:800; color:#fff; margin:0;">
                            🏆 Auction Results — {{ $tournament->name }}
                        </h1>
                        <p style="color:rgba(255,255,255,0.5); margin:3px 0 0; font-size:13px;">
                            Final squad and spend summary
                        </p>
                    </div>
                </div>
                <button onclick="window.print()"
                        style="border-radius:8px; font-weight:600; font-size:13px;
                               padding:8px 16px; background:rgba(255,255,255,0.1);
                               color:#fff; border:1px solid rgba(255,255,255,0.15);
                               cursor:pointer;">
                    <i class="bi bi-printer me-1"></i> Print Results
                </button>
            </div>
        </div>
    </div>

    <div class="container py-4">

        {{-- Summary Stats --}}
        @php
            $totalSold   = $teams->sum(fn($t) => $t->players->count());
            $totalSpent  = $teams->sum('spent');
            $totalBudget = $teams->sum('budget');
        @endphp

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="res-stat-card">
                    <div style="font-size:1.8rem; font-weight:800; color:#1a56db;">
                        {{ $teams->count() }}
                    </div>
                    <div class="res-stat-lbl">Teams</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="res-stat-card">
                    <div style="font-size:1.8rem; font-weight:800; color:#16a34a;">
                        {{ $totalSold }}
                    </div>
                    <div class="res-stat-lbl">Players Sold</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="res-stat-card">
                    <div style="font-size:1.8rem; font-weight:800; color:#ca8a04;">
                        {{ $unsoldPlayers->count() }}
                    </div>
                    <div class="res-stat-lbl">Unsold Players</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="res-stat-card">
                    <div style="font-size:1.8rem; font-weight:800; color:#9333ea;">
                        @php
                            echo $totalSpent >= 100000
                                ? '₹'.number_format($totalSpent/100000,1).'L'
                                : '₹'.number_format($totalSpent/1000,0).'K';
                        @endphp
                    </div>
                    <div class="res-stat-lbl">Total Spent</div>
                </div>
            </div>
        </div>

        {{-- Teams Results --}}
        <div class="row g-4 mb-4">
            @foreach($teams as $team)
                @php
                    $remaining = $team->budget - $team->spent;
                    $pct = $team->budget > 0
                        ? min(100, ($team->spent / $team->budget) * 100) : 0;
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="team-result-card">

                        {{-- Team Header --}}
                        <div style="display:flex; align-items:center; gap:12px;
                                    margin-bottom:16px; padding-bottom:14px;
                                    border-bottom:1px solid #f3f4f6;">
                            <div style="width:48px; height:48px; border-radius:12px;
                                        background:{{ $team->color }}22;
                                        border:2px solid {{ $team->color }};
                                        display:flex; align-items:center; justify-content:center;
                                        font-weight:900; font-size:14px; color:{{ $team->color }};
                                        flex-shrink:0;">
                                {{ $team->short_name }}
                            </div>
                            <div style="flex:1;">
                                <div style="font-weight:800; font-size:15px; color:#111827;">
                                    {{ $team->name }}
                                </div>
                                <div style="font-size:12px; color:#6b7280; margin-top:2px;">
                                    {{ $team->players->count() }} players
                                </div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-size:11px; color:#6b7280;">Spent</div>
                                <div style="font-weight:800; font-size:15px; color:#111827;">
                                    @php
                                        echo $team->spent >= 100000
                                            ? '₹'.number_format($team->spent/100000,1).'L'
                                            : '₹'.number_format($team->spent/1000,0).'K';
                                    @endphp
                                </div>
                            </div>
                        </div>

                        {{-- Budget Bar --}}
                        <div style="margin-bottom:14px;">
                            <div style="display:flex; justify-content:space-between;
                                        margin-bottom:5px;">
                                <span style="font-size:12px; color:#6b7280;">
                                    Remaining:
                                    <strong style="color:{{ $remaining < $team->budget*0.2 ? '#dc2626' : '#16a34a' }};">
                                        ₹{{ $remaining >= 100000
                                            ? number_format($remaining/100000,1).'L'
                                            : number_format($remaining/1000,0).'K' }}
                                    </strong>
                                </span>
                                <span style="font-size:12px; color:#6b7280;">
                                    {{ number_format($pct,1) }}% used
                                </span>
                            </div>
                            <div style="background:#f3f4f6; border-radius:6px; height:6px;">
                                <div style="background:{{ $pct > 80 ? '#ef4444' : $team->color }};
                                            width:{{ $pct }}%; height:6px; border-radius:6px;">
                                </div>
                            </div>
                        </div>

                        {{-- Players List --}}
                        @if($team->players->isEmpty())
                            <div style="text-align:center; padding:20px 0; color:#9ca3af; font-size:13px;">
                                No players assigned
                            </div>
                        @else
                            <div style="display:flex; flex-direction:column; gap:6px;">
                                @foreach($team->players->sortByDesc('sold_price') as $i => $player)
                                    <div style="display:flex; align-items:center; gap:8px;
                                                padding:8px 10px; border-radius:8px;
                                                background:{{ $i % 2 === 0 ? '#f9fafb' : '#fff' }};">
                                        <div style="font-size:11px; color:#9ca3af;
                                                    width:18px; text-align:center;">
                                            {{ $i + 1 }}
                                        </div>
                                        <div style="width:28px; height:28px; border-radius:50%;
                                                    background:{{ $team->color }}22;
                                                    color:{{ $team->color }};
                                                    display:flex; align-items:center; justify-content:center;
                                                    font-weight:700; font-size:10px; flex-shrink:0;">
                                            {{ strtoupper(substr($player->name,0,2)) }}
                                        </div>
                                        <div style="flex:1; min-width:0;">
                                            <div style="font-weight:600; font-size:13px; color:#111827;
                                                        white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                {{ $player->name }}
                                            </div>
                                            <div style="font-size:11px; color:#6b7280;">
                                                {{ $player->roleLabel }}
                                            </div>
                                        </div>
                                        <div style="font-weight:700; font-size:13px; color:#16a34a;
                                                    flex-shrink:0;">
                                            ₹{{ $player->sold_price >= 100000
                                                ? number_format($player->sold_price/100000,1).'L'
                                                : number_format($player->sold_price/1000,0).'K' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>
                </div>
            @endforeach
        </div>

        {{-- Unsold Players --}}
        @if($unsoldPlayers->count() > 0)
            <div style="background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:24px;">
                <div style="font-weight:700; font-size:15px; color:#111827;
                            margin-bottom:16px; padding-bottom:14px;
                            border-bottom:1px solid #f3f4f6; display:flex; align-items:center; gap:8px;">
                    <span style="background:#fef2f2; color:#dc2626; border-radius:8px;
                                 padding:4px 10px; font-size:13px;">
                        {{ $unsoldPlayers->count() }} Unsold Players
                    </span>
                </div>
                <div class="row g-2">
                    @foreach($unsoldPlayers as $player)
                        <div class="col-md-4 col-lg-3">
                            <div style="display:flex; align-items:center; gap:8px;
                                        padding:10px 12px; border-radius:10px;
                                        background:#fef2f2; border:1px solid #fecaca;">
                                <div style="width:32px; height:32px; border-radius:50%;
                                            background:#fee2e2; color:#dc2626;
                                            display:flex; align-items:center; justify-content:center;
                                            font-weight:700; font-size:11px; flex-shrink:0;">
                                    {{ strtoupper(substr($player->name,0,2)) }}
                                </div>
                                <div style="flex:1; min-width:0;">
                                    <div style="font-weight:600; font-size:13px; color:#111827;
                                                white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        {{ $player->name }}
                                    </div>
                                    <div style="font-size:11px; color:#6b7280;">
                                        {{ $player->roleLabel }}
                                        @if($player->base_price > 0)
                                            · Base ₹{{ number_format($player->base_price/1000,0) }}K
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>

@endsection

@push('styles')
<style>
    .res-stat-card {
        background:#fff; border:1px solid #e5e7eb;
        border-radius:14px; padding:20px; text-align:center;
    }
    .res-stat-lbl {
        font-size:12px; color:#6b7280; font-weight:500;
        text-transform:uppercase; letter-spacing:0.5px; margin-top:6px;
    }
    .team-result-card {
        background:#fff; border:1px solid #e5e7eb;
        border-radius:16px; padding:20px; height:100%;
    }
    @media print {
        nav, .navbar { display:none !important; }
        body { background:#fff !important; }
        .team-result-card { break-inside:avoid; }
    }
</style>
@endpush