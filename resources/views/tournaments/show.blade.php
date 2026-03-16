@extends('layouts.app')

@section('title', $tournament->name . ' — Manage')

@section('content')

<div style="background:#f9fafb; min-height:calc(100vh - 62px);">

    {{-- Top Bar --}}
    <div style="background:#fff; border-bottom:1px solid #e5e7eb; padding:20px 0;">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('dashboard') }}"
                       style="width:36px; height:36px; border-radius:10px; border:1.5px solid #e5e7eb;
                              display:flex; align-items:center; justify-content:center;
                              color:#6b7280; text-decoration:none; background:#fff;">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div>
                        <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                            <h1 style="font-size:1.3rem; font-weight:800; color:#111827; margin:0;">
                                {{ $tournament->name }}
                            </h1>
                            <span class="status-badge badge-{{ $tournament->status }}">
                                {{ ucfirst($tournament->status) }}
                            </span>
                            @if($tournament->registration_open)
                                <span class="status-badge" style="background:#dcfce7; color:#16a34a;">
                                    <i class="bi bi-circle-fill me-1" style="font-size:8px;"></i>
                                    Registration Open
                                </span>
                            @endif
                        </div>
                        <div style="font-size:13px; color:#6b7280; margin-top:3px;">
                            <i class="bi bi-link-45deg"></i>
                            <a href="{{ route('tournament.public', $tournament->slug) }}"
                               target="_blank"
                               style="color:#1a56db; text-decoration:none;">
                                {{ url('/t/'.$tournament->slug) }}
                            </a>
                            <button onclick="copyLink()"
                                    style="border:none; background:none; color:#6b7280;
                                           cursor:pointer; padding:0 4px; font-size:12px;"
                                    title="Copy link">
                                <i class="bi bi-copy" id="copyIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex gap-2 flex-wrap">
                    {{-- Toggle Registration --}}
                    <form method="POST" action="{{ route('tournaments.toggle-registration', $tournament->id) }}">
                        @csrf
                        <button type="submit"
                                class="btn btn-sm px-3"
                                style="border-radius:8px; font-weight:600; font-size:13px;
                                       {{ $tournament->registration_open
                                          ? 'background:#fef2f2; color:#dc2626; border:1px solid #fecaca;'
                                          : 'background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0;' }}">
                            <i class="bi bi-{{ $tournament->registration_open ? 'door-closed' : 'door-open' }} me-1"></i>
                            {{ $tournament->registration_open ? 'Close Registration' : 'Open Registration' }}
                        </button>
                    </form>

                    {{-- Status Dropdown --}}
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle px-3"
                                style="border-radius:8px; font-weight:600; font-size:13px;"
                                data-bs-toggle="dropdown">
                            <i class="bi bi-gear me-1"></i> Status
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end"
                            style="border-radius:12px; border:1px solid #e5e7eb;
                                   box-shadow:0 8px 24px rgba(0,0,0,0.1); min-width:160px;">
                            @foreach(['draft' => '📝 Draft', 'active' => '✅ Active', 'auction' => '⚡ Auction', 'completed' => '🏁 Completed'] as $val => $label)
                                <li>
                                    <form method="POST" action="{{ route('tournaments.update-status', $tournament->id) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="{{ $val }}">
                                        <button type="submit"
                                                class="dropdown-item {{ $tournament->status === $val ? 'fw-700' : '' }}"
                                                style="font-size:13px; {{ $tournament->status === $val ? 'color:#1a56db; font-weight:700;' : '' }}">
                                            {{ $label }}
                                            @if($tournament->status === $val)
                                                <i class="bi bi-check ms-1"></i>
                                            @endif
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Edit --}}
                    <a href="{{ route('tournaments.edit', $tournament->id) }}"
                       class="btn btn-sm btn-outline-secondary px-3"
                       style="border-radius:8px; font-weight:600; font-size:13px;">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>

                    {{-- Start Auction --}}
                    <a href="{{ route('auction.index', $tournament->id) }}"
                       class="btn btn-sm btn-primary px-3"
                       style="border-radius:8px; font-weight:600; font-size:13px;">
                        <i class="bi bi-lightning-charge-fill me-1"></i> Auction Panel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-4">

        {{-- Flash --}}
        @if(session('success'))
            <div class="flash-success mb-4">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flash-error mb-4">
                <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
            </div>
        @endif

        {{-- Stats Row --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#eff6ff; color:#1a56db;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stat-val">{{ $stats['total_teams'] }} / {{ $tournament->total_teams }}</div>
                    <div class="stat-lbl">Teams Created</div>
                    <div class="stat-bar">
                        <div class="stat-bar-fill"
                             style="width:{{ $tournament->total_teams > 0 ? ($stats['total_teams']/$tournament->total_teams)*100 : 0 }}%;
                                    background:#1a56db;"></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#f0fdf4; color:#16a34a;">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <div class="stat-val">{{ $stats['total_players'] }}</div>
                    <div class="stat-lbl">Registered Players</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#fefce8; color:#ca8a04;">
                        <i class="bi bi-hammer"></i>
                    </div>
                    <div class="stat-val">{{ $stats['sold_players'] }}</div>
                    <div class="stat-lbl">Players Sold</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#fdf4ff; color:#9333ea;">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                    <div class="stat-val">
                        @php
                            $spent = $stats['total_spent'];
                            echo $spent >= 100000 ? '₹'.number_format($spent/100000,1).'L'
                                                  : '₹'.number_format($spent/1000,0).'K';
                        @endphp
                    </div>
                    <div class="stat-lbl">Total Spent</div>
                </div>
            </div>
        </div>

        {{-- Main Grid --}}
        <div class="row g-4">

            {{-- LEFT: Teams --}}
            <div class="col-lg-7">
                <div class="section-card">
                    <div class="section-card-header">
                        <div>
                            <div class="section-card-title">
                                <i class="bi bi-shield-fill me-2" style="color:#1a56db;"></i>Teams
                            </div>
                            <div class="section-card-subtitle">
                                {{ $stats['total_teams'] }} of {{ $tournament->total_teams }} teams created
                            </div>
                        </div>
                        <a href="{{ route('tournaments.teams.create', $tournament->id) }}"
                           class="btn btn-primary btn-sm px-3"
                           style="border-radius:8px; font-weight:600; font-size:13px;">
                            <i class="bi bi-plus-lg me-1"></i> Add Team
                        </a>
                    </div>

                    @if($teams->isEmpty())
                        <div class="empty-mini">
                            <div style="font-size:2.5rem; margin-bottom:10px;">🛡️</div>
                            <div style="font-weight:600; color:#374151; margin-bottom:6px;">No teams yet</div>
                            <div style="font-size:13px; color:#6b7280; margin-bottom:16px;">
                                Add teams to your tournament to get started
                            </div>
                            <a href="{{ route('tournaments.teams.create', $tournament->id) }}"
                               class="btn btn-primary btn-sm px-4"
                               style="border-radius:8px; font-weight:600;">
                                Add First Team
                            </a>
                        </div>
                    @else
                        <div style="display:flex; flex-direction:column; gap:10px;">
                            @foreach($teams as $team)
                                <div class="team-row">
                                    {{-- Color dot + name --}}
                                    <div style="display:flex; align-items:center; gap:12px; flex:1; min-width:0;">
                                        <div style="width:40px; height:40px; border-radius:10px;
                                                    background:{{ $team->color }}22;
                                                    border:2px solid {{ $team->color }};
                                                    display:flex; align-items:center; justify-content:center;
                                                    font-weight:800; font-size:13px; color:{{ $team->color }};
                                                    flex-shrink:0;">
                                            {{ strtoupper(substr($team->short_name ?: $team->name, 0, 2)) }}
                                        </div>
                                        <div style="min-width:0;">
                                            <div style="font-weight:700; font-size:14px; color:#111827;
                                                        white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                {{ $team->name }}
                                            </div>
                                            <div style="font-size:12px; color:#6b7280;">
                                                {{ $team->players_count }} players
                                                @if($team->spent > 0)
                                                    · Spent:
                                                    @php
                                                        echo $team->spent >= 100000
                                                            ? '₹'.number_format($team->spent/100000,1).'L'
                                                            : '₹'.number_format($team->spent/1000,0).'K';
                                                    @endphp
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Budget bar --}}
                                    <div style="flex:1; max-width:140px;">
                                        @php
                                            $budgetPct = $team->budget > 0
                                                ? min(100, ($team->spent / $team->budget) * 100)
                                                : 0;
                                        @endphp
                                        <div style="font-size:11px; color:#6b7280; margin-bottom:4px;
                                                    display:flex; justify-content:space-between;">
                                            <span>Budget used</span>
                                            <span>{{ number_format($budgetPct, 0) }}%</span>
                                        </div>
                                        <div style="background:#f3f4f6; border-radius:4px; height:5px;">
                                            <div style="background:{{ $budgetPct > 80 ? '#ef4444' : '#1a56db' }};
                                                        width:{{ $budgetPct }}%; height:5px; border-radius:4px;
                                                        transition:width 0.3s;">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('tournaments.teams.show', [$tournament->id, $team->id]) }}"
                                           class="icon-btn" title="View Team">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form method="POST"
                                              action="{{ route('tournaments.teams.destroy', [$tournament->id, $team->id]) }}"
                                              onsubmit="return confirm('Delete {{ $team->name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="icon-btn text-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- RIGHT: Players + Info --}}
            <div class="col-lg-5">

                {{-- Tournament Info Card --}}
                <div class="section-card mb-4">
                    <div class="section-card-header">
                        <div class="section-card-title">
                            <i class="bi bi-info-circle me-2" style="color:#6b7280;"></i>Tournament Details
                        </div>
                        <a href="{{ route('tournaments.edit', $tournament->id) }}"
                           style="font-size:13px; color:#1a56db; font-weight:600; text-decoration:none;">
                            Edit
                        </a>
                    </div>
                    <div style="display:flex; flex-direction:column; gap:12px;">
                        <div class="info-row">
                            <span class="info-label">Sport</span>
                            <span class="info-val">🏏 Cricket</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Teams</span>
                            <span class="info-val">{{ $tournament->total_teams }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Budget / Team</span>
                            <span class="info-val">{{ $tournament->formattedBudget }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Max Squad</span>
                            <span class="info-val">{{ $tournament->max_squad_size }} players</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Auction Mode</span>
                            <span class="info-val">
                                @php
                                    echo match($tournament->auction_mode) {
                                        'manual' => '⚙️ Manual',
                                        'live'   => '⚡ Live Bidding',
                                        'both'   => '🔀 Both Modes',
                                        default  => ucfirst($tournament->auction_mode),
                                    };
                                @endphp
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Created</span>
                            <span class="info-val">{{ $tournament->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Players Card --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <div>
                            <div class="section-card-title">
                                <i class="bi bi-person-badge me-2" style="color:#16a34a;"></i>Players
                            </div>
                            <div class="section-card-subtitle">
                                {{ $stats['total_players'] }} registered
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('import.index', $tournament->id) }}"
                               class="btn btn-sm px-3"
                               style="border-radius:8px; font-weight:600; font-size:12px;
                                      background:#fdf4ff; color:#9333ea; border:1px solid #e9d5ff;">
                                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Import
                            </a>
                            <a href="{{ route('players.index', $tournament->id) }}"
                               class="btn btn-sm px-3"
                               style="border-radius:8px; font-weight:600; font-size:12px;
                                      background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0;">
                                View All
                            </a>
                        </div>
                    </div>

                    @if($players->isEmpty())
                        <div class="empty-mini">
                            <div style="font-size:2rem; margin-bottom:8px;">👤</div>
                            <div style="font-weight:600; color:#374151; font-size:14px; margin-bottom:4px;">
                                No players yet
                            </div>
                            <div style="font-size:12px; color:#6b7280; margin-bottom:14px;">
                                Share the registration link for players to join
                            </div>
                            <button onclick="copyLink()"
                                    class="btn btn-sm px-4"
                                    style="border-radius:8px; font-weight:600; font-size:12px;
                                           background:#eff6ff; color:#1a56db; border:1px solid #bfdbfe;">
                                <i class="bi bi-copy me-1"></i> Copy Registration Link
                            </button>
                        </div>
                    @else
                        <div style="display:flex; flex-direction:column; gap:8px;">
                            @foreach($players->take(6) as $player)
                                <div style="display:flex; align-items:center; gap:10px;
                                            padding:8px; border-radius:10px; background:#f9fafb;">
                                    <div style="width:34px; height:34px; border-radius:50%;
                                                background:#eff6ff; color:#1a56db;
                                                display:flex; align-items:center; justify-content:center;
                                                font-weight:700; font-size:12px; flex-shrink:0;">
                                        {{ strtoupper(substr($player->name, 0, 2)) }}
                                    </div>
                                    <div style="flex:1; min-width:0;">
                                        <div style="font-weight:600; font-size:13px; color:#111827;
                                                    white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                            {{ $player->name }}
                                        </div>
                                        <div style="font-size:11px; color:#6b7280;">
                                            {{ $player->roleLabel ?? 'Player' }}
                                            @if($player->city) · {{ $player->city }} @endif
                                        </div>
                                    </div>
                                    <span class="player-status-badge status-{{ $player->status }}">
                                        {{ ucfirst($player->status) }}
                                    </span>
                                </div>
                            @endforeach

                            @if($players->count() > 6)
                                <a href="{{ route('players.index', $tournament->id) }}"
                                   style="text-align:center; font-size:13px; color:#1a56db;
                                          font-weight:600; text-decoration:none; padding:8px;">
                                    View all {{ $players->count() }} players →
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

            </div>
        </div>

        {{-- Share Box --}}
        <div class="share-box mt-4">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div style="font-size:1.8rem;">🔗</div>
                <div style="flex:1;">
                    <div style="font-weight:700; font-size:15px; color:#111827; margin-bottom:2px;">
                        Player Registration Link
                    </div>
                    <div style="font-size:13px; color:#6b7280;">
                        Share this link so players can register themselves
                    </div>
                </div>
                <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                    <div style="background:#fff; border:1.5px solid #e5e7eb; border-radius:10px;
                                padding:8px 16px; font-size:13px; color:#1a56db; font-weight:500;">
                        {{ url('/t/'.$tournament->slug) }}
                    </div>
                    <button onclick="copyLink()"
                            class="btn btn-primary btn-sm px-4"
                            style="border-radius:8px; font-weight:600;" id="copyBtn">
                        <i class="bi bi-copy me-1"></i> Copy Link
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('styles')
<style>
    .status-badge {
        padding:4px 12px; border-radius:20px;
        font-size:12px; font-weight:600; display:inline-flex; align-items:center;
    }
    .badge-draft     { background:#f3f4f6; color:#374151; }
    .badge-active    { background:#dcfce7; color:#16a34a; }
    .badge-auction   { background:#fef9c3; color:#ca8a04; }
    .badge-completed { background:#eff6ff; color:#1a56db; }

    .flash-success {
        background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a;
        border-radius:12px; padding:14px 18px; font-size:14px;
        display:flex; align-items:center;
    }
    .flash-error {
        background:#fef2f2; border:1px solid #fecaca; color:#dc2626;
        border-radius:12px; padding:14px 18px; font-size:14px;
        display:flex; align-items:center;
    }

    /* Stat Card */
    .stat-card {
        background:#fff; border:1px solid #e5e7eb;
        border-radius:14px; padding:18px; text-align:center;
    }
    .stat-icon {
        width:40px; height:40px; border-radius:10px;
        display:flex; align-items:center; justify-content:center;
        font-size:18px; margin:0 auto 10px;
    }
    .stat-val { font-size:1.4rem; font-weight:800; color:#111827; line-height:1; margin-bottom:4px; }
    .stat-lbl { font-size:11px; color:#6b7280; font-weight:500; text-transform:uppercase; letter-spacing:0.5px; }
    .stat-bar { background:#f3f4f6; border-radius:4px; height:4px; margin-top:10px; }
    .stat-bar-fill { height:4px; border-radius:4px; transition:width 0.3s; }

    /* Section Card */
    .section-card {
        background:#fff; border:1px solid #e5e7eb;
        border-radius:16px; padding:24px;
    }
    .section-card-header {
        display:flex; justify-content:space-between; align-items:center;
        margin-bottom:20px; padding-bottom:16px;
        border-bottom:1px solid #f3f4f6;
    }
    .section-card-title   { font-weight:700; font-size:15px; color:#111827; display:flex; align-items:center; }
    .section-card-subtitle { font-size:12px; color:#6b7280; margin-top:2px; }

    /* Team Row */
    .team-row {
        display:flex; align-items:center; gap:12px;
        padding:12px 14px; border-radius:12px;
        border:1px solid #f3f4f6; background:#fafafa;
        transition:border-color 0.2s;
    }
    .team-row:hover { border-color:#e5e7eb; background:#f9fafb; }

    /* Icon Button */
    .icon-btn {
        width:32px; height:32px; border-radius:8px;
        border:1px solid #e5e7eb; background:#fff;
        display:flex; align-items:center; justify-content:center;
        font-size:14px; color:#6b7280; text-decoration:none;
        cursor:pointer; transition:all 0.15s;
    }
    .icon-btn:hover { background:#f3f4f6; color:#111827; }

    /* Info Row */
    .info-row {
        display:flex; justify-content:space-between; align-items:center;
        padding:8px 0; border-bottom:1px solid #f9fafb;
    }
    .info-row:last-child { border-bottom:none; }
    .info-label { font-size:13px; color:#6b7280; font-weight:500; }
    .info-val   { font-size:13px; color:#111827; font-weight:600; }

    /* Player Status */
    .player-status-badge {
        padding:3px 10px; border-radius:20px;
        font-size:11px; font-weight:600; flex-shrink:0;
    }
    .status-registered { background:#eff6ff; color:#1a56db; }
    .status-available  { background:#f0fdf4; color:#16a34a; }
    .status-sold       { background:#fef9c3; color:#ca8a04; }
    .status-unsold     { background:#fef2f2; color:#dc2626; }

    /* Empty Mini */
    .empty-mini {
        text-align:center; padding:28px 16px;
        background:#fafafa; border-radius:12px;
        border:1.5px dashed #e5e7eb;
    }

    /* Share Box */
    .share-box {
        background:linear-gradient(135deg, #eff6ff, #f0fdf4);
        border:1px solid #bfdbfe; border-radius:16px; padding:24px;
    }
</style>
@endpush

@push('scripts')
<script>
function copyLink() {
    const link = '{{ url("/t/".$tournament->slug) }}';
    navigator.clipboard.writeText(link).then(() => {
        const btn = document.getElementById('copyBtn');
        const icon = document.getElementById('copyIcon');
        if (btn) {
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Copied!';
            btn.style.background = '#16a34a';
            btn.style.borderColor = '#16a34a';
            setTimeout(() => {
                btn.innerHTML = '<i class="bi bi-copy me-1"></i> Copy Link';
                btn.style.background = '';
                btn.style.borderColor = '';
            }, 2000);
        }
        if (icon) {
            icon.className = 'bi bi-check-lg';
            setTimeout(() => { icon.className = 'bi bi-copy'; }, 2000);
        }
    });
}
</script>
@endpush