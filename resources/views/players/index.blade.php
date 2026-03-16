@extends('layouts.app')

@section('title', 'Players — ' . $tournament->name)

@section('content')

<div style="background:#f9fafb; min-height:calc(100vh - 62px);">

    {{-- Header --}}
    <div style="background:#fff; border-bottom:1px solid #e5e7eb; padding:20px 0;">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('tournaments.show', $tournament->id) }}"
                       style="width:36px; height:36px; border-radius:10px; border:1.5px solid #e5e7eb;
                              display:flex; align-items:center; justify-content:center;
                              color:#6b7280; text-decoration:none; background:#fff;">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div>
                        <h1 style="font-size:1.2rem; font-weight:800; color:#111827; margin:0;">
                            Players — {{ $tournament->name }}
                        </h1>
                        <p style="color:#6b7280; margin:3px 0 0; font-size:13px;">
                            {{ $stats['total'] }} players registered
                        </p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('import.index', $tournament->id) }}"
                       class="btn btn-sm px-3"
                       style="border-radius:8px; font-weight:600; font-size:13px;
                              background:#fdf4ff; color:#9333ea; border:1px solid #e9d5ff;">
                        <i class="bi bi-file-earmark-spreadsheet me-1"></i> Import
                    </a>
                    <a href="{{ route('auction.index', $tournament->id) }}"
                       class="btn btn-primary btn-sm px-3"
                       style="border-radius:8px; font-weight:600; font-size:13px;">
                        <i class="bi bi-lightning-charge-fill me-1"></i> Go to Auction
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

        {{-- Stats --}}
        <div class="row g-3 mb-4">
            @foreach([
                ['total',      'Total',      '#eff6ff','#1a56db','bi-people-fill'],
                ['registered', 'Registered', '#f3f4f6','#374151','bi-person-plus'],
                ['available',  'Available',  '#f0fdf4','#16a34a','bi-person-check'],
                ['sold',       'Sold',       '#fef9c3','#ca8a04','bi-hammer'],
                ['unsold',     'Unsold',     '#fef2f2','#dc2626','bi-person-x'],
            ] as [$key, $label, $bg, $color, $icon])
                <div class="col-6 col-md">
                    <div style="background:#fff; border:1px solid #e5e7eb; border-radius:14px;
                                padding:16px; text-align:center;">
                        <div style="width:36px; height:36px; border-radius:10px; background:{{ $bg }};
                                    color:{{ $color }}; display:flex; align-items:center;
                                    justify-content:center; font-size:16px; margin:0 auto 8px;">
                            <i class="bi {{ $icon }}"></i>
                        </div>
                        <div style="font-size:1.4rem; font-weight:800; color:#111827; line-height:1;">
                            {{ $stats[$key] }}
                        </div>
                        <div style="font-size:11px; color:#6b7280; font-weight:500;
                                    text-transform:uppercase; letter-spacing:0.5px; margin-top:4px;">
                            {{ $label }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Filter + Search --}}
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:14px;
                    padding:16px 20px; margin-bottom:20px;">
            <div class="d-flex gap-3 flex-wrap align-items-center">
                <input type="text" id="searchInput" placeholder="Search players..."
                       style="border:1.5px solid #e5e7eb; border-radius:10px; padding:8px 14px;
                              font-size:14px; outline:none; flex:1; min-width:200px; font-family:'Inter',sans-serif;"
                       oninput="filterPlayers()">

                <select id="roleFilter" onchange="filterPlayers()"
                        style="border:1.5px solid #e5e7eb; border-radius:10px; padding:8px 14px;
                               font-size:14px; outline:none; color:#374151; font-family:'Inter',sans-serif;">
                    <option value="">All Roles</option>
                    <option value="batsman">Batsman</option>
                    <option value="bowler">Bowler</option>
                    <option value="all_rounder">All-rounder</option>
                    <option value="wicket_keeper">Wicket Keeper</option>
                </select>

                <select id="statusFilter" onchange="filterPlayers()"
                        style="border:1.5px solid #e5e7eb; border-radius:10px; padding:8px 14px;
                               font-size:14px; outline:none; color:#374151; font-family:'Inter',sans-serif;">
                    <option value="">All Status</option>
                    <option value="registered">Registered</option>
                    <option value="available">Available</option>
                    <option value="sold">Sold</option>
                    <option value="unsold">Unsold</option>
                </select>
            </div>
        </div>

        {{-- Players Table --}}
        @if($players->isEmpty())
            <div style="background:#fff; border:2px dashed #e5e7eb; border-radius:16px;
                        padding:60px 24px; text-align:center;">
                <div style="font-size:3rem; margin-bottom:12px;">👤</div>
                <div style="font-weight:700; color:#374151; font-size:1.1rem; margin-bottom:8px;">
                    No players registered yet
                </div>
                <div style="font-size:14px; color:#6b7280; margin-bottom:20px;">
                    Share the registration link so players can sign up
                </div>
                <button onclick="copyRegLink()"
                        class="btn btn-primary px-4"
                        style="border-radius:10px; font-weight:600;">
                    <i class="bi bi-copy me-2"></i>Copy Registration Link
                </button>
            </div>
        @else
            <div style="background:#fff; border:1px solid #e5e7eb; border-radius:16px; overflow:hidden;">
                <div class="table-responsive">
                    <table style="width:100%; border-collapse:collapse;" id="playersTable">
                        <thead>
                            <tr style="background:#f9fafb; border-bottom:1px solid #e5e7eb;">
                                <th style="padding:12px 16px; text-align:left; font-size:12px;
                                           color:#6b7280; font-weight:600; text-transform:uppercase;">#</th>
                                <th style="padding:12px 16px; text-align:left; font-size:12px;
                                           color:#6b7280; font-weight:600; text-transform:uppercase;">Player</th>
                                <th style="padding:12px 16px; text-align:left; font-size:12px;
                                           color:#6b7280; font-weight:600; text-transform:uppercase;">Role</th>
                                <th style="padding:12px 16px; text-align:left; font-size:12px;
                                           color:#6b7280; font-weight:600; text-transform:uppercase;">City</th>
                                <th style="padding:12px 16px; text-align:left; font-size:12px;
                                           color:#6b7280; font-weight:600; text-transform:uppercase;">Base Price</th>
                                <th style="padding:12px 16px; text-align:left; font-size:12px;
                                           color:#6b7280; font-weight:600; text-transform:uppercase;">Status</th>
                                <th style="padding:12px 16px; text-align:left; font-size:12px;
                                           color:#6b7280; font-weight:600; text-transform:uppercase;">Team</th>
                                <th style="padding:12px 16px; text-align:center; font-size:12px;
                                           color:#6b7280; font-weight:600; text-transform:uppercase;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="playersTbody">
                            @foreach($players as $i => $player)
                                <tr class="player-row"
                                    data-name="{{ strtolower($player->name) }}"
                                    data-role="{{ $player->role }}"
                                    data-status="{{ $player->status }}"
                                    style="border-bottom:1px solid #f9fafb; transition:background 0.15s;">

                                    <td style="padding:12px 16px; font-size:13px; color:#9ca3af;">
                                        {{ $i + 1 }}
                                    </td>

                                    <td style="padding:12px 16px;">
                                        <div style="display:flex; align-items:center; gap:10px;">
                                            @if($player->photo)
                                                <img src="{{ Storage::url($player->photo) }}"
                                                     style="width:36px; height:36px; border-radius:50%;
                                                            object-fit:cover; flex-shrink:0;">
                                            @else
                                                <div style="width:36px; height:36px; border-radius:50%;
                                                            background:#eff6ff; color:#1a56db;
                                                            display:flex; align-items:center; justify-content:center;
                                                            font-weight:700; font-size:12px; flex-shrink:0;">
                                                    {{ strtoupper(substr($player->name, 0, 2)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div style="font-weight:600; font-size:14px; color:#111827;">
                                                    {{ $player->name }}
                                                </div>
                                                <div style="font-size:12px; color:#6b7280;">
                                                    {{ $player->phone }}
                                                    @if($player->age) · Age {{ $player->age }} @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td style="padding:12px 16px; font-size:13px; color:#374151;">
                                        {{ $player->roleLabel }}
                                    </td>

                                    <td style="padding:12px 16px; font-size:13px; color:#374151;">
                                        {{ $player->city ?? '—' }}
                                    </td>

                                    {{-- Inline Base Price Edit --}}
                                    <td style="padding:12px 16px;">
                                        <form method="POST"
                                              action="{{ route('players.base-price', [$tournament->id, $player->id]) }}"
                                              style="display:flex; align-items:center; gap:6px;">
                                            @csrf @method('PATCH')
                                            <span style="font-size:14px; font-weight:600; color:#374151;">₹</span>
                                            <input type="number"
                                                   name="base_price"
                                                   value="{{ $player->base_price }}"
                                                   min="0" step="1000"
                                                   style="width:90px; border:1.5px solid #e5e7eb;
                                                          border-radius:8px; padding:5px 8px;
                                                          font-size:13px; font-weight:600; outline:none;
                                                          font-family:'Inter',sans-serif;">
                                            <button type="submit"
                                                    style="border:none; background:#eff6ff; color:#1a56db;
                                                           border-radius:6px; padding:5px 8px;
                                                           font-size:12px; cursor:pointer; font-weight:600;">
                                                Set
                                            </button>
                                        </form>
                                    </td>

                                    <td style="padding:12px 16px;">
                                        <span class="p-status-badge status-{{ $player->status }}">
                                            {{ ucfirst($player->status) }}
                                        </span>
                                    </td>

                                    <td style="padding:12px 16px; font-size:13px; color:#374151;">
                                        @if($player->team)
                                            <span style="display:inline-flex; align-items:center; gap:6px;">
                                                <span style="width:10px; height:10px; border-radius:50%;
                                                             background:{{ $player->team->color }};
                                                             display:inline-block;"></span>
                                                {{ $player->team->short_name }}
                                            </span>
                                        @else
                                            <span style="color:#9ca3af;">—</span>
                                        @endif
                                    </td>

                                    <td style="padding:12px 16px; text-align:center;">
                                        <form method="POST"
                                              action="{{ route('players.destroy', [$tournament->id, $player->id]) }}"
                                              onsubmit="return confirm('Remove {{ $player->name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    style="border:1px solid #fecaca; background:#fef2f2;
                                                           color:#dc2626; border-radius:8px; padding:5px 10px;
                                                           font-size:12px; cursor:pointer;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</div>

@endsection

@push('styles')
<style>
    .flash-success {
        background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a;
        border-radius:12px; padding:14px 18px; font-size:14px;
        display:flex; align-items:center;
    }
    .p-status-badge {
        padding:3px 10px; border-radius:20px;
        font-size:11px; font-weight:600;
    }
    .status-registered { background:#eff6ff; color:#1a56db; }
    .status-available  { background:#f0fdf4; color:#16a34a; }
    .status-sold       { background:#fef9c3; color:#ca8a04; }
    .status-unsold     { background:#fef2f2; color:#dc2626; }
    .player-row:hover  { background:#fafafa; }
</style>
@endpush

@push('scripts')
<script>
function filterPlayers() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const role   = document.getElementById('roleFilter').value;
    const status = document.getElementById('statusFilter').value;

    document.querySelectorAll('.player-row').forEach(row => {
        const nameMatch   = row.dataset.name.includes(search);
        const roleMatch   = !role   || row.dataset.role   === role;
        const statusMatch = !status || row.dataset.status === status;
        row.style.display = (nameMatch && roleMatch && statusMatch) ? '' : 'none';
    });
}

function copyRegLink() {
    navigator.clipboard.writeText('{{ url("/t/".$tournament->slug) }}')
        .then(() => alert('Registration link copied!'));
}
</script>
@endpush