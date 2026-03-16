<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tournament->name }} — Live Auction Room</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #0f172a;
            color: #f1f5f9;
            margin: 0;
            min-height: 100vh;
        }

        /* ── HEADER ── */
        .room-header {
            background: #1e293b;
            border-bottom: 1px solid #334155;
            padding: 14px 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .room-brand {
            font-size: 1.1rem;
            font-weight: 800;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .room-brand span { color: #f59e0b; }

        .live-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #052e16;
            border: 1px solid #166534;
            color: #4ade80;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }
        .live-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #22c55e;
            animation: blink 1.5s infinite;
            flex-shrink: 0;
        }
        @keyframes blink {
            0%,100% { opacity:1; }
            50%      { opacity:0.2; }
        }

        /* ── STATS BAR ── */
        .stats-bar {
            background: #1e293b;
            border-bottom: 1px solid #334155;
            padding: 12px 0;
        }
        .stat-item { text-align: center; }
        .stat-val  { font-size: 1.4rem; font-weight: 800; line-height: 1; }
        .stat-lbl  {
            font-size: 10px; color: #475569; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.5px; margin-top: 3px;
        }

        /* ── SPOTLIGHT ── */
        .spotlight-wrap {
            background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 100%);
            border: 1px solid #3b82f6;
            border-radius: 16px;
            padding: 28px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .spotlight-wrap::before {
            content: '';
            position: absolute;
            width: 180px; height: 180px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
            top: -60px; right: -40px;
        }
        .spotlight-avatar-img {
            width: 96px; height: 96px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid rgba(255,255,255,0.25);
            margin-bottom: 14px;
        }
        .spotlight-avatar-initials {
            width: 96px; height: 96px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            border: 4px solid rgba(255,255,255,0.25);
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; font-weight: 800; color: #fff;
            margin: 0 auto 14px;
        }
        .spotlight-name {
            font-size: 1.4rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 6px;
        }
        .spotlight-sub {
            font-size: 13px;
            color: rgba(255,255,255,0.6);
            margin-bottom: 14px;
        }
        .spotlight-price-big {
            font-size: 2rem;
            font-weight: 800;
            color: #fbbf24;
            line-height: 1;
        }
        .spotlight-price-lbl {
            font-size: 11px;
            color: rgba(255,255,255,0.45);
            margin-top: 4px;
        }
        .spotlight-tags {
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 14px;
        }
        .spotlight-tag {
            background: rgba(255,255,255,0.1);
            border-radius: 7px;
            padding: 5px 12px;
            font-size: 12px;
            color: rgba(255,255,255,0.7);
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .spotlight-empty {
            padding: 32px 0;
            color: rgba(255,255,255,0.3);
        }
        .spotlight-empty i { font-size: 2.5rem; display: block; margin-bottom: 10px; }

        /* ── ROOM CARD ── */
        .room-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 14px;
            padding: 16px;
        }
        .room-card-hdr {
            display: flex;
            align-items: center;
            font-weight: 700;
            font-size: 14px;
            color: #e2e8f0;
            margin-bottom: 14px;
            padding-bottom: 12px;
            border-bottom: 1px solid #334155;
            flex-wrap: wrap;
            gap: 8px;
        }

        /* ── TEAM BUDGET ── */
        .team-bud-row {
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 8px;
        }
        .team-bud-row:last-child { margin-bottom: 0; }

        /* ── PLAYER ROW ── */
        .player-row {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 10px;
            border-radius: 8px;
            border: 1px solid #334155;
            margin-bottom: 5px;
            transition: border-color 0.15s;
        }
        .player-row:last-child { margin-bottom: 0; }
        .player-row.st-sold      { background: #052e16; border-color: #166534; }
        .player-row.st-unsold    { background: #1c0a0a; border-color: #991b1b; }
        .player-row.st-available,
        .player-row.st-registered { background: #1e293b; border-color: #334155; }

        .p-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 11px; color: #fff;
            flex-shrink: 0;
            object-fit: cover;
        }
        .s-badge {
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .s-sold       { background: #052e16; color: #4ade80; }
        .s-unsold     { background: #1c0a0a; color: #f87171; }
        .s-available,
        .s-registered { background: #0c1a3a; color: #60a5fa; }

        /* ── SEARCH ── */
        .dark-input {
            width: 100%;
            background: #0f172a;
            border: 1.5px solid #334155;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 13px;
            color: #f1f5f9;
            outline: none;
            font-family: inherit;
            margin-bottom: 12px;
        }
        .dark-input:focus { border-color: #3b82f6; }

        /* ── FILTER PILLS ── */
        .filter-pill {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            border: 1px solid #334155;
            background: transparent;
            color: #64748b;
            cursor: pointer;
            transition: all 0.15s;
        }
        .filter-pill.active,
        .filter-pill:hover { background: #1d4ed8; color: #fff; border-color: #1d4ed8; }

        /* ── REFRESH INDICATOR ── */
        .refresh-bar {
            height: 2px;
            background: #1d4ed8;
            width: 0%;
            transition: width linear;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9999;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .spotlight-name      { font-size: 1.1rem; }
            .spotlight-price-big { font-size: 1.5rem; }
        }
    </style>
</head>
<body>

{{-- Refresh Progress Bar --}}
<div class="refresh-bar" id="refreshBar"></div>

{{-- ── HEADER ── --}}
<div class="room-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <a href="{{ url('/') }}" class="room-brand">
                <i class="bi bi-trophy-fill" style="color:#1d4ed8;"></i>
                Auction<span>XI</span>
            </a>
            <div style="text-align:center;">
                <div style="font-weight:700;font-size:14px;color:#fff;">
                    {{ $tournament->name }}
                </div>
                <div style="font-size:11px;color:#475569;">Live Auction Room</div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="live-badge">
                    <div class="live-dot"></div>
                    LIVE
                </div>
                <div style="font-size:11px;color:#475569;" id="lastUpdated">
                    Connecting...
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── STATS BAR ── --}}
<div class="stats-bar">
    <div class="container">
        <div class="row g-2">
            <div class="col-3">
                <div class="stat-item">
                    <div class="stat-val" style="color:#60a5fa;" id="statTotal">
                        {{ $players->count() }}
                    </div>
                    <div class="stat-lbl">Total</div>
                </div>
            </div>
            <div class="col-3">
                <div class="stat-item">
                    <div class="stat-val" style="color:#34d399;" id="statAvail">
                        {{ $players->whereIn('status',['available','registered'])->count() }}
                    </div>
                    <div class="stat-lbl">Available</div>
                </div>
            </div>
            <div class="col-3">
                <div class="stat-item">
                    <div class="stat-val" style="color:#fbbf24;" id="statSold">
                        {{ $players->where('status','sold')->count() }}
                    </div>
                    <div class="stat-lbl">Sold</div>
                </div>
            </div>
            <div class="col-3">
                <div class="stat-item">
                    <div class="stat-val" style="color:#f87171;" id="statUnsold">
                        {{ $players->where('status','unsold')->count() }}
                    </div>
                    <div class="stat-lbl">Unsold</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── MAIN CONTENT ── --}}
<div class="container py-4">
    <div class="row g-4">

        {{-- ── LEFT: Spotlight + Team Budgets ── --}}
        <div class="col-lg-4">

            {{-- Spotlight --}}
            <div class="mb-4">
                <div style="font-size:11px;color:#475569;font-weight:700;
                            text-transform:uppercase;letter-spacing:0.5px;
                            margin-bottom:10px;display:flex;align-items:center;gap:6px;">
                    <div class="live-dot"></div> Now on Auction
                </div>
                <div class="spotlight-wrap" id="spotlightCard">
                    @if($spotlight)
                        @if($spotlight->photo)
                            <img src="{{ Storage::url($spotlight->photo) }}"
                                 class="spotlight-avatar-img" id="spotlightImg">
                        @else
                            <div class="spotlight-avatar-initials" id="spotlightInitials">
                                {{ strtoupper(substr($spotlight->name,0,2)) }}
                            </div>
                        @endif
                        <div class="spotlight-name" id="spotlightName">{{ $spotlight->name }}</div>
                        <div class="spotlight-sub" id="spotlightSub">
                            {{ $spotlight->roleLabel }}
                            @if($spotlight->city) &middot; {{ $spotlight->city }} @endif
                        </div>
                        <div class="spotlight-price-big" id="spotlightPrice">
                            @php
                                $bp = $spotlight->base_price;
                                echo '₹' . ($bp >= 100000
                                    ? number_format($bp/100000,1).'L'
                                    : number_format($bp/1000,0).'K');
                            @endphp
                        </div>
                        <div class="spotlight-price-lbl">Base Price</div>
                        <div class="spotlight-tags" id="spotlightTags">
                            @if($spotlight->age)
                                <div class="spotlight-tag">
                                    <i class="bi bi-calendar3"></i> Age {{ $spotlight->age }}
                                </div>
                            @endif
                            @if($spotlight->batting_style)
                                <div class="spotlight-tag">
                                    <i class="bi bi-person-standing"></i>
                                    {{ ucwords(str_replace('_',' ',$spotlight->batting_style)) }}
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="spotlight-empty" id="spotlightEmpty">
                            <i class="bi bi-search"></i>
                            <div style="font-size:14px;">Waiting for next player...</div>
                        </div>
                        <div id="spotlightName" style="display:none;"></div>
                        <div id="spotlightSub"  style="display:none;"></div>
                        <div id="spotlightPrice" style="display:none;"></div>
                        <div id="spotlightTags"  style="display:none;"></div>
                        <div id="spotlightImg"   style="display:none;"></div>
                        <div id="spotlightInitials" style="display:none;"></div>
                    @endif
                </div>
            </div>

            {{-- Team Budgets --}}
            <div class="room-card">
                <div class="room-card-hdr">
                    <i class="bi bi-wallet2 me-2" style="color:#a78bfa;"></i>
                    Team Budgets
                </div>
                <div id="teamBudgetList">
                    @foreach($teams as $team)
                        @php
                            $rem = $team->budget - $team->spent;
                            $pct = $team->budget > 0
                                ? min(100,($team->spent/$team->budget)*100) : 0;
                        @endphp
                        <div class="team-bud-row">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div style="width:30px;height:30px;border-radius:7px;
                                            background:{{ $team->color }}22;
                                            border:2px solid {{ $team->color }};
                                            display:flex;align-items:center;justify-content:center;
                                            font-size:9px;font-weight:800;color:{{ $team->color }};
                                            flex-shrink:0;">
                                    {{ $team->short_name }}
                                </div>
                                <div style="flex:1;min-width:0;">
                                    <div style="font-weight:700;font-size:13px;color:#e2e8f0;
                                                white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        {{ $team->name }}
                                    </div>
                                    <div style="font-size:11px;color:#475569;">
                                        {{ $team->players->count() }}/{{ $tournament->max_squad_size }} players
                                    </div>
                                </div>
                                <div style="text-align:right;flex-shrink:0;">
                                    <div style="font-size:12px;font-weight:700;
                                                color:{{ $rem < ($team->budget*0.2) ? '#f87171' : '#34d399' }};">
                                        ₹{{ $rem >= 100000
                                            ? number_format($rem/100000,1).'L'
                                            : number_format($rem/1000,0).'K' }}
                                    </div>
                                    <div style="font-size:10px;color:#475569;">left</div>
                                </div>
                            </div>
                            <div style="background:#334155;border-radius:4px;height:4px;">
                                <div style="background:{{ $pct>80 ? '#ef4444' : $team->color }};
                                            width:{{ $pct }}%;height:4px;border-radius:4px;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── RIGHT: Players List ── --}}
        <div class="col-lg-8">
            <div class="room-card">
                <div class="room-card-hdr">
                    <i class="bi bi-people me-2" style="color:#60a5fa;"></i>
                    Registered Players
                    <span id="playerCountBadge"
                          style="margin-left:8px;background:#0f172a;color:#64748b;
                                 padding:2px 8px;border-radius:20px;font-size:11px;">
                        {{ $players->count() }}
                    </span>
                    <div style="margin-left:auto;display:flex;gap:4px;flex-wrap:wrap;">
                        @foreach(['all'=>'All','available'=>'Available','registered'=>'Registered','sold'=>'Sold','unsold'=>'Unsold'] as $v=>$l)
                            <button class="filter-pill {{ $v==='all' ? 'active' : '' }}"
                                    onclick="filterPlayers('{{ $v }}',this)">
                                {{ $l }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Search --}}
                <input type="text"
                       class="dark-input"
                       placeholder="Search player by name..."
                       id="playerSearch"
                       oninput="searchPlayers()">

                {{-- Players Grid --}}
                <div id="playerGrid"
                     style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:8px;
                            max-height:600px;overflow-y:auto;">
                    @foreach($players as $player)
                        <div class="player-row st-{{ $player->status }}"
                             data-status="{{ $player->status }}"
                             data-name="{{ strtolower($player->name) }}"
                             data-id="{{ $player->id }}">

                            {{-- Avatar --}}
                            @if($player->photo)
                                <img src="{{ Storage::url($player->photo) }}"
                                     class="p-avatar"
                                     style="border:2px solid #334155;">
                            @else
                                <div class="p-avatar"
                                     style="background:#1d4ed8;font-size:11px;">
                                    {{ strtoupper(substr($player->name,0,2)) }}
                                </div>
                            @endif

                            {{-- Info --}}
                            <div style="flex:1;min-width:0;">
                                <div style="font-weight:700;font-size:13px;color:#e2e8f0;
                                            white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    #{{ $player->id }} {{ $player->name }}
                                </div>
                                <div style="font-size:11px;color:#475569;">
                                    {{ $player->roleLabel }}
                                    @if($player->city) &middot; {{ $player->city }} @endif
                                </div>
                                @if($player->status === 'sold' && $player->team)
                                    <div style="font-size:11px;color:#34d399;margin-top:2px;
                                                display:flex;align-items:center;gap:4px;">
                                        <span style="width:8px;height:8px;border-radius:50%;
                                                     background:{{ $player->team->color }};
                                                     display:inline-block;flex-shrink:0;"></span>
                                        {{ $player->team->name }}
                                    </div>
                                @endif
                            </div>

                            {{-- Price + Status --}}
                            <div style="text-align:right;flex-shrink:0;">
                                @if($player->status === 'sold' && $player->sold_price)
                                    <div style="font-size:13px;font-weight:800;color:#34d399;">
                                        ₹{{ $player->sold_price >= 100000
                                            ? number_format($player->sold_price/100000,1).'L'
                                            : number_format($player->sold_price/1000,0).'K' }}
                                    </div>
                                @elseif($player->base_price > 0)
                                    <div style="font-size:12px;font-weight:600;color:#fbbf24;">
                                        ₹{{ $player->base_price >= 100000
                                            ? number_format($player->base_price/100000,1).'L'
                                            : number_format($player->base_price/1000,0).'K' }}
                                    </div>
                                @endif
                                <span class="s-badge s-{{ $player->status }}">
                                    {{ strtoupper($player->status) }}
                                </span>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    const STATE_URL    = '{{ route("auction.state", $tournament->id) }}';
    const POLL_INTERVAL = 4000; // 4 seconds
    let   currentFilter = 'all';
    let   currentSearch = '';
    let   lastTimestamp = 0;

    // ── FILTER ──────────────────────────────────────────────────
    function filterPlayers(status, btn) {
        currentFilter = status;
        document.querySelectorAll('.filter-pill')
            .forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        applyFilters();
    }

    function searchPlayers() {
        currentSearch = document.getElementById('playerSearch').value.toLowerCase();
        applyFilters();
    }

    function applyFilters() {
        document.querySelectorAll('.player-row').forEach(row => {
            const statusMatch = currentFilter === 'all' ||
                                row.dataset.status === currentFilter;
            const searchMatch = !currentSearch ||
                                row.dataset.name.includes(currentSearch) ||
                                row.dataset.id.includes(currentSearch);
            row.style.display = (statusMatch && searchMatch) ? '' : 'none';
        });
    }

    // ── FORMAT NUMBER ────────────────────────────────────────────
    function fmtRupee(n) {
        if (!n) return '₹0';
        n = parseFloat(n);
        if (n >= 10000000) return '₹' + (n/10000000).toFixed(1) + 'Cr';
        if (n >= 100000)   return '₹' + (n/100000).toFixed(1) + 'L';
        if (n >= 1000)     return '₹' + (n/1000).toFixed(0) + 'K';
        return '₹' + n;
    }

    // ── UPDATE SPOTLIGHT ─────────────────────────────────────────
    function updateSpotlight(sp) {
        if (!sp) {
            document.getElementById('spotlightCard').innerHTML = `
                <div style="text-align:center;padding:32px 0;color:rgba(255,255,255,0.3);">
                    <i class="bi bi-search" style="font-size:2.5rem;display:block;margin-bottom:10px;"></i>
                    <div style="font-size:14px;">Waiting for next player...</div>
                </div>`;
            return;
        }

        const photoHtml = sp.photo
            ? `<img src="${sp.photo}" class="spotlight-avatar-img">`
            : `<div class="spotlight-avatar-initials">${sp.initials}</div>`;

        const tagsHtml = [
            sp.age ? `<div class="spotlight-tag"><i class="bi bi-calendar3"></i> Age ${sp.age}</div>` : '',
            sp.city ? `<div class="spotlight-tag"><i class="bi bi-geo-alt"></i> ${sp.city}</div>` : '',
        ].join('');

        const statusColor = {
            sold: '#fbbf24', unsold: '#f87171',
            available: '#34d399', registered: '#60a5fa'
        }[sp.status] || '#94a3b8';

        document.getElementById('spotlightCard').innerHTML = `
            ${photoHtml}
            <div class="spotlight-name">${sp.name}</div>
            <div class="spotlight-sub">
                ${sp.role}${sp.city ? ' · ' + sp.city : ''}
            </div>
            <div class="spotlight-price-big">${fmtRupee(sp.base_price)}</div>
            <div class="spotlight-price-lbl">Base Price</div>
            ${tagsHtml ? `<div class="spotlight-tags">${tagsHtml}</div>` : ''}
            ${sp.status === 'sold' && sp.team
                ? `<div style="margin-top:14px;display:inline-flex;align-items:center;gap:8px;
                               background:rgba(0,0,0,0.2);border-radius:8px;padding:8px 14px;">
                       <span style="width:10px;height:10px;border-radius:50%;
                                    background:${sp.team.color};display:inline-block;"></span>
                       <span style="font-size:13px;font-weight:600;color:#fff;">Sold to ${sp.team.name}</span>
                       <span style="font-size:14px;font-weight:800;color:#34d399;">${fmtRupee(sp.sold_price)}</span>
                   </div>`
                : ''}`;
    }

    // ── UPDATE TEAM BUDGETS ──────────────────────────────────────
    function updateTeams(teams) {
        const maxSquad = {{ $tournament->max_squad_size }};
        const html = teams.map(t => {
            const rem = t.remaining;
            const pct = t.budget > 0 ? Math.min(100,(t.spent/t.budget)*100) : 0;
            const clr = rem < t.budget * 0.2 ? '#f87171' : '#34d399';
            const barClr = pct > 80 ? '#ef4444' : t.color;
            return `
                <div class="team-bud-row">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div style="width:30px;height:30px;border-radius:7px;
                                    background:${t.color}22;border:2px solid ${t.color};
                                    display:flex;align-items:center;justify-content:center;
                                    font-size:9px;font-weight:800;color:${t.color};flex-shrink:0;">
                            ${t.short_name}
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-weight:700;font-size:13px;color:#e2e8f0;
                                        white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                ${t.name}
                            </div>
                            <div style="font-size:11px;color:#475569;">
                                ${t.players_count}/${maxSquad} players
                            </div>
                        </div>
                        <div style="text-align:right;flex-shrink:0;">
                            <div style="font-size:12px;font-weight:700;color:${clr};">
                                ${fmtRupee(rem)}
                            </div>
                            <div style="font-size:10px;color:#475569;">left</div>
                        </div>
                    </div>
                    <div style="background:#334155;border-radius:4px;height:4px;">
                        <div style="background:${barClr};width:${pct}%;
                                    height:4px;border-radius:4px;transition:width 0.5s;"></div>
                    </div>
                </div>`;
        }).join('');
        document.getElementById('teamBudgetList').innerHTML = html;
    }

    // ── UPDATE PLAYERS LIST ──────────────────────────────────────
    function updatePlayers(players) {
        const html = players.map(p => {
            const rowCls = `player-row st-${p.status}`;
            const avatarHtml = p.photo
                ? `<img src="${p.photo}" class="p-avatar" style="border:2px solid #334155;">`
                : `<div class="p-avatar" style="background:#1d4ed8;font-size:11px;">${p.initials}</div>`;

            const teamHtml = p.status === 'sold' && p.team
                ? `<div style="font-size:11px;color:#34d399;margin-top:2px;
                               display:flex;align-items:center;gap:4px;">
                       <span style="width:8px;height:8px;border-radius:50%;
                                    background:${p.team.color};display:inline-block;flex-shrink:0;"></span>
                       ${p.team.name}
                   </div>` : '';

            const priceHtml = p.status === 'sold' && p.sold_price
                ? `<div style="font-size:13px;font-weight:800;color:#34d399;">${fmtRupee(p.sold_price)}</div>`
                : p.base_price > 0
                    ? `<div style="font-size:12px;font-weight:600;color:#fbbf24;">${fmtRupee(p.base_price)}</div>`
                    : '';

            const badgeCls = `s-badge s-${p.status}`;

            return `
                <div class="${rowCls}"
                     data-status="${p.status}"
                     data-name="${p.name.toLowerCase()}"
                     data-id="${p.id}">
                    ${avatarHtml}
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:700;font-size:13px;color:#e2e8f0;
                                    white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            #${p.id} ${p.name}
                        </div>
                        <div style="font-size:11px;color:#475569;">
                            ${p.role}${p.city ? ' · ' + p.city : ''}
                        </div>
                        ${teamHtml}
                    </div>
                    <div style="text-align:right;flex-shrink:0;">
                        ${priceHtml}
                        <span class="${badgeCls}">${p.status.toUpperCase()}</span>
                    </div>
                </div>`;
        }).join('');

        document.getElementById('playerGrid').innerHTML = html;
        applyFilters();
    }

    // ── UPDATE STATS ─────────────────────────────────────────────
    function updateStats(stats) {
        document.getElementById('statTotal').textContent  = stats.total;
        document.getElementById('statAvail').textContent  = stats.available;
        document.getElementById('statSold').textContent   = stats.sold;
        document.getElementById('statUnsold').textContent = stats.unsold;
        document.getElementById('playerCountBadge').textContent = stats.total;
    }

    // ── PROGRESS BAR ANIMATION ───────────────────────────────────
    function animateProgressBar() {
        const bar = document.getElementById('refreshBar');
        bar.style.transition = 'none';
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.transition = `width ${POLL_INTERVAL}ms linear`;
            bar.style.width = '100%';
        }, 50);
    }

    // ── MAIN POLL ────────────────────────────────────────────────
    async function pollState() {
        try {
            const res  = await fetch(STATE_URL);
            const data = await res.json();

            // Only update DOM if data changed
            if (data.timestamp !== lastTimestamp) {
                lastTimestamp = data.timestamp;
                updateSpotlight(data.spotlight);
                updateTeams(data.teams);
                updatePlayers(data.players);
                updateStats(data.stats);
            }

            const now = new Date();
            document.getElementById('lastUpdated').textContent =
                'Updated ' + now.toLocaleTimeString('en-IN', {hour:'2-digit',minute:'2-digit',second:'2-digit'});

        } catch(e) {
            document.getElementById('lastUpdated').textContent = 'Connection error...';
        }
        animateProgressBar();
    }

    // Start polling
    pollState();
    setInterval(pollState, POLL_INTERVAL);
    animateProgressBar();
</script>
</body>
</html>