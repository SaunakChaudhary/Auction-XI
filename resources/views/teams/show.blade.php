@extends('layouts.app')

@section('title', $team->name . ' — ' . $tournament->name)

@section('content')

<div style="background:#f9fafb; min-height:calc(100vh - 62px); padding:40px 0;">
<div class="container">

    {{-- Back --}}
    <a href="{{ route('tournaments.show', $tournament->id) }}"
       style="display:inline-flex; align-items:center; gap:6px; color:#6b7280;
              font-size:14px; font-weight:500; text-decoration:none; margin-bottom:24px;">
        <i class="bi bi-arrow-left"></i> Back to {{ $tournament->name }}
    </a>

    <div class="row g-4">

        {{-- Team Header Card --}}
        <div class="col-12">
            <div style="background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:28px;">
                <div class="d-flex align-items-center gap-4 flex-wrap">
                    <div style="width:72px; height:72px; border-radius:18px;
                                background:{{ $team->color }}22;
                                border:3px solid {{ $team->color }};
                                display:flex; align-items:center; justify-content:center;
                                font-size:22px; font-weight:900; color:{{ $team->color }};
                                flex-shrink:0;">
                        {{ $team->short_name }}
                    </div>
                    <div style="flex:1;">
                        <h1 style="font-size:1.5rem; font-weight:800; color:#111827; margin:0;">
                            {{ $team->name }}
                        </h1>
                        <div style="font-size:13px; color:#6b7280; margin-top:4px;">
                            {{ $tournament->name }} · {{ $players->count() }} Players
                        </div>
                    </div>
                    <div style="display:flex; gap:24px; flex-wrap:wrap;">
                        <div style="text-align:center;">
                            <div style="font-size:1.4rem; font-weight:800; color:#111827;">
                                @php
                                    echo $team->budget >= 100000
                                        ? '₹'.number_format($team->budget/100000,1).'L'
                                        : '₹'.number_format($team->budget/1000,0).'K';
                                @endphp
                            </div>
                            <div style="font-size:11px; color:#6b7280; font-weight:500; text-transform:uppercase;">Total Budget</div>
                        </div>
                        <div style="text-align:center;">
                            <div style="font-size:1.4rem; font-weight:800; color:#dc2626;">
                                @php
                                    echo $team->spent >= 100000
                                        ? '₹'.number_format($team->spent/100000,1).'L'
                                        : '₹'.number_format($team->spent/1000,0).'K';
                                @endphp
                            </div>
                            <div style="font-size:11px; color:#6b7280; font-weight:500; text-transform:uppercase;">Spent</div>
                        </div>
                        <div style="text-align:center;">
                            <div style="font-size:1.4rem; font-weight:800; color:#16a34a;">
                                @php
                                    $remaining = $team->budget - $team->spent;
                                    echo $remaining >= 100000
                                        ? '₹'.number_format($remaining/100000,1).'L'
                                        : '₹'.number_format($remaining/1000,0).'K';
                                @endphp
                            </div>
                            <div style="font-size:11px; color:#6b7280; font-weight:500; text-transform:uppercase;">Remaining</div>
                        </div>
                    </div>
                    <a href="{{ route('tournaments.teams.edit', [$tournament->id, $team->id]) }}"
                       class="btn btn-outline-secondary btn-sm px-3"
                       style="border-radius:8px; font-weight:600;">
                        <i class="bi bi-pencil me-1"></i> Edit Team
                    </a>
                </div>

                {{-- Budget Bar --}}
                @php $pct = $team->budget > 0 ? min(100, ($team->spent / $team->budget) * 100) : 0; @endphp
                <div style="margin-top:20px;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                        <span style="font-size:13px; color:#6b7280;">Budget used</span>
                        <span style="font-size:13px; font-weight:600; color:{{ $pct > 80 ? '#dc2626' : '#374151' }};">
                            {{ number_format($pct, 1) }}%
                        </span>
                    </div>
                    <div style="background:#f3f4f6; border-radius:8px; height:10px;">
                        <div style="background:{{ $pct > 80 ? '#ef4444' : $team->color }};
                                    width:{{ $pct }}%; height:10px; border-radius:8px; transition:width 0.3s;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Players List --}}
        <div class="col-12">
            <div style="background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:24px;">
                <div style="display:flex; justify-content:space-between; align-items:center;
                            margin-bottom:20px; padding-bottom:16px; border-bottom:1px solid #f3f4f6;">
                    <div>
                        <div style="font-weight:700; font-size:15px; color:#111827;">Squad</div>
                        <div style="font-size:12px; color:#6b7280; margin-top:2px;">
                            {{ $players->count() }} / {{ $tournament->max_squad_size }} players
                        </div>
                    </div>
                </div>

                @if($players->isEmpty())
                    <div style="text-align:center; padding:40px 0; color:#9ca3af;">
                        <div style="font-size:3rem; margin-bottom:12px;">👤</div>
                        <div style="font-weight:600; color:#374151;">No players assigned yet</div>
                        <div style="font-size:13px; margin-top:6px;">
                            Players will appear here after auction
                        </div>
                    </div>
                @else
                    <div class="table-responsive">
                        <table style="width:100%; border-collapse:collapse;">
                            <thead>
                                <tr style="border-bottom:1px solid #f3f4f6;">
                                    <th style="text-align:left; padding:10px 12px; font-size:12px;
                                               color:#6b7280; font-weight:600; text-transform:uppercase;">Player</th>
                                    <th style="text-align:left; padding:10px 12px; font-size:12px;
                                               color:#6b7280; font-weight:600; text-transform:uppercase;">Role</th>
                                    <th style="text-align:left; padding:10px 12px; font-size:12px;
                                               color:#6b7280; font-weight:600; text-transform:uppercase;">City</th>
                                    <th style="text-align:right; padding:10px 12px; font-size:12px;
                                               color:#6b7280; font-weight:600; text-transform:uppercase;">Sold Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($players as $player)
                                    <tr style="border-bottom:1px solid #f9fafb;">
                                        <td style="padding:12px;">
                                            <div style="display:flex; align-items:center; gap:10px;">
                                                <div style="width:36px; height:36px; border-radius:50%;
                                                            background:{{ $team->color }}22; color:{{ $team->color }};
                                                            display:flex; align-items:center; justify-content:center;
                                                            font-weight:700; font-size:12px; flex-shrink:0;">
                                                    {{ strtoupper(substr($player->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div style="font-weight:600; font-size:14px; color:#111827;">
                                                        {{ $player->name }}
                                                    </div>
                                                    <div style="font-size:12px; color:#6b7280;">
                                                        {{ $player->phone ?? '—' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding:12px; font-size:13px; color:#374151;">
                                            {{ $player->roleLabel ?? '—' }}
                                        </td>
                                        <td style="padding:12px; font-size:13px; color:#374151;">
                                            {{ $player->city ?? '—' }}
                                        </td>
                                        <td style="padding:12px; text-align:right;">
                                            @if($player->sold_price)
                                                <span style="font-weight:700; color:#16a34a; font-size:14px;">
                                                    ₹{{ number_format($player->sold_price) }}
                                                </span>
                                            @else
                                                <span style="color:#9ca3af; font-size:13px;">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
</div>

@endsection