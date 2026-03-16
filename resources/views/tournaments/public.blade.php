@extends('layouts.guest')

@section('title', $tournament->name . ' — CricAuction')

@section('content')

    <style>
        body {
            padding: 32px 16px;
        }

        .auth-card {
            max-width: 560px;
        }
    </style>

    {{-- Tournament Header --}}
    <div style="text-align:center; margin-bottom:28px;">
        <div
            style="display:inline-flex; align-items:center; justify-content:center;
                width:68px; height:68px; background:#eff6ff; border-radius:18px;
                font-size:32px; margin-bottom:14px;">
            🏏</div>
        <h1 style="font-size:1.5rem; font-weight:800; color:#111827; margin:0;">
            {{ $tournament->name }}
        </h1>
        @if ($tournament->description)
            <p style="color:#6b7280; font-size:14px; margin:8px 0 0; line-height:1.6;">
                {{ $tournament->description }}
            </p>
        @endif

        {{-- Status Badge --}}
        <div style="margin-top:12px;">
            @if ($tournament->registration_open)
                <span
                    style="display:inline-flex; align-items:center; gap:6px;
                         background:#dcfce7; color:#16a34a; border-radius:20px;
                         padding:5px 16px; font-size:13px; font-weight:600;">
                    <i class="bi bi-circle-fill" style="font-size:7px;"></i>
                    Registration Open
                </span>
            @else
                <span
                    style="display:inline-flex; align-items:center; gap:6px;
                         background:#f3f4f6; color:#6b7280; border-radius:20px;
                         padding:5px 16px; font-size:13px; font-weight:600;">
                    <i class="bi bi-lock-fill" style="font-size:10px;"></i>
                    Registration Closed
                </span>
            @endif
        </div>
    </div>

    {{-- Tournament Info --}}
    <div
        style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:14px;
            padding:20px; margin-bottom:24px;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div
                style="text-align:center; padding:12px;
                    background:#fff; border-radius:10px; border:1px solid #e5e7eb;">
                <div style="font-size:1.4rem; font-weight:800; color:#1a56db;">
                    {{ $tournament->total_teams }}
                </div>
                <div style="font-size:12px; color:#6b7280; font-weight:500; margin-top:2px;">Teams</div>
            </div>
            <div
                style="text-align:center; padding:12px;
                    background:#fff; border-radius:10px; border:1px solid #e5e7eb;">
                <div style="font-size:1.4rem; font-weight:800; color:#16a34a;">
                    {{ $tournament->max_squad_size }}
                </div>
                <div style="font-size:12px; color:#6b7280; font-weight:500; margin-top:2px;">Max Squad Size</div>
            </div>
            <div
                style="text-align:center; padding:12px;
                    background:#fff; border-radius:10px; border:1px solid #e5e7eb;">
                <div style="font-size:1.4rem; font-weight:800; color:#ca8a04;">
                    {{ $tournament->formattedBudget }}
                </div>
                <div style="font-size:12px; color:#6b7280; font-weight:500; margin-top:2px;">Budget / Team</div>
            </div>
            <div
                style="text-align:center; padding:12px;
                    background:#fff; border-radius:10px; border:1px solid #e5e7eb;">
                <div style="font-size:1.4rem; font-weight:800; color:#9333ea;">
                    {{ $tournament->players()->count() }}
                </div>
                <div style="font-size:12px; color:#6b7280; font-weight:500; margin-top:2px;">Players Registered</div>
            </div>
        </div>
    </div>

    {{-- CTA --}}
    @if ($tournament->registration_open)
        <a href="{{ route('player.register.form', $tournament->slug) }}" class="btn-auth"
            style="display:block; text-align:center; text-decoration:none; margin-bottom:14px;">
            Register as Player 🏏
        </a>
        <p style="text-align:center; font-size:13px; color:#9ca3af; margin:0;">
            Already registered? Contact the organizer for updates.
        </p>
    @else
        <div
            style="background:#fef9c3; border:1px solid #fde68a; border-radius:12px;
                padding:16px; text-align:center;">
            <div style="font-size:1.5rem; margin-bottom:8px;">🔒</div>
            <div style="font-weight:700; color:#92400e; margin-bottom:4px;">Registration is closed</div>
            <div style="font-size:13px; color:#78350f;">
                Contact the tournament organizer for more information.
            </div>
        </div>
    @endif

    {{-- Teams Section --}}
    @php $teams = $tournament->teams()->withCount('players')->get(); @endphp
    @if ($teams->count() > 0)
        <div style="margin-top:28px; padding-top:24px; border-top:1px solid #e5e7eb;">
            <div style="font-size:14px; font-weight:700; color:#374151; margin-bottom:14px;">
                🛡️ Teams ({{ $teams->count() }})
            </div>
            <div style="display:flex; flex-direction:column; gap:8px;">
                @foreach ($teams as $team)
                    <div
                        style="display:flex; align-items:center; gap:12px;
                            background:#f9fafb; border:1px solid #e5e7eb;
                            border-radius:10px; padding:10px 14px;">
                        <div
                            style="width:36px; height:36px; border-radius:9px;
                                background:{{ $team->color }}22; border:2px solid {{ $team->color }};
                                display:flex; align-items:center; justify-content:center;
                                font-size:11px; font-weight:800; color:{{ $team->color }}; flex-shrink:0;">
                            {{ $team->short_name }}
                        </div>
                        <div style="flex:1; font-weight:600; font-size:14px; color:#111827;">
                            {{ $team->name }}
                        </div>
                        <div style="font-size:12px; color:#6b7280;">
                            {{ $team->players_count }} players
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    {{-- Auction Room Link --}}
    @if ($tournament->status === 'auction' || $tournament->status === 'active')
        <div
            style="margin-top:20px; background:#0f172a; border:1px solid #1e40af;
            border-radius:14px; padding:20px;">
            <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                <div
                    style="width:44px; height:44px; border-radius:12px; background:#1d4ed8;
                    display:flex; align-items:center; justify-content:center;
                    font-size:20px; flex-shrink:0;">
                    <i class="bi bi-lightning-charge-fill" style="color:#fbbf24;"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-weight:700; font-size:15px; color:#fff; margin-bottom:3px;">
                        Auction is Live!
                    </div>
                    <div style="font-size:13px; color:#64748b;">
                        Watch the live auction — see player bids, team budgets and results in real time.
                    </div>
                </div>
                <a href="{{ route('auction.room', $tournament->slug) }}"
                    style="display:inline-flex; align-items:center; gap:8px;
                  padding:10px 20px; background:#1d4ed8; color:#fff;
                  border-radius:10px; font-weight:700; font-size:14px;
                  text-decoration:none; white-space:nowrap;">
                    <i class="bi bi-broadcast"></i> Watch Live
                </a>
            </div>
        </div>
    @endif
    {{-- Footer --}}
    <div style="text-align:center; margin-top:28px; padding-top:20px; border-top:1px solid #e5e7eb;">
        <a href="{{ url('/') }}" style="font-size:13px; color:#9ca3af; text-decoration:none; font-weight:500;">
            Powered by 🏏 <strong style="color:#1a56db;">CricAuction</strong>
        </a>
    </div>

@endsection
