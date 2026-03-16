@extends('layouts.app')

@section('title', 'Dashboard — CricAuction')

@section('content')

    <div style="background: #f9fafb; min-height: calc(100vh - 62px);">

        {{-- Header --}}
        <div style="background:#fff; border-bottom: 1px solid #e5e7eb; padding: 24px 0;">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h1 style="font-size:1.6rem; font-weight:800; color:#111827; margin:0;">
                            Welcome back, {{ explode(' ', Auth::user()->name)[0] }} 👋
                        </h1>
                        <p style="color:#6b7280; margin:4px 0 0; font-size:0.9rem;">
                            Manage your tournaments and auctions
                        </p>
                    </div>
                    <a href="{{ route('tournaments.create') }}" class="btn btn-primary px-4 py-2"
                        style="border-radius:10px; font-weight:700; font-size:0.9rem;">
                        <i class="bi bi-plus-lg me-1"></i> New Tournament
                    </a>
                </div>
            </div>
        </div>

        <div class="container py-4">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="alert d-flex align-items-center gap-2 mb-4"
                    style="background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; border-radius:12px; padding:14px 18px;">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Stats Row --}}
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background:#eff6ff; color:#1a56db;">
                            <i class="bi bi-trophy"></i>
                        </div>
                        <div class="stat-number">{{ $stats['total_tournaments'] }}</div>
                        <div class="stat-label">Total Tournaments</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background:#f0fdf4; color:#16a34a;">
                            <i class="bi bi-lightning-charge"></i>
                        </div>
                        <div class="stat-number">{{ $stats['active_tournaments'] }}</div>
                        <div class="stat-label">Active</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background:#fefce8; color:#ca8a04;">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="stat-number">{{ $stats['total_teams'] }}</div>
                        <div class="stat-label">Total Teams</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background:#fdf4ff; color:#9333ea;">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <div class="stat-number">{{ $stats['total_players'] }}</div>
                        <div class="stat-label">Total Players</div>
                    </div>
                </div>
            </div>

            {{-- Tournaments Section --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 style="font-size:1.1rem; font-weight:700; color:#111827; margin:0;">
                    My Tournaments
                </h2>
                @if ($tournaments->count() > 0)
                    <a href="{{ route('tournaments.create') }}"
                        style="font-size:13px; color:#1a56db; font-weight:600; text-decoration:none;">
                        + Create New
                    </a>
                @endif
            </div>

            {{-- Empty State --}}
            @if ($tournaments->isEmpty())
                <div class="empty-state">
                    <div style="font-size:4rem; margin-bottom:16px;">🏏</div>
                    <h3 style="font-size:1.2rem; font-weight:700; color:#111827; margin-bottom:8px;">
                        No tournaments yet
                    </h3>
                    <p
                        style="color:#6b7280; font-size:0.9rem; margin-bottom:24px; max-width:340px; margin-left:auto; margin-right:auto;">
                        Create your first cricket tournament and start managing teams, players and auctions.
                    </p>
                    <a href="{{ route('tournaments.create') }}" class="btn btn-primary px-5 py-2"
                        style="border-radius:10px; font-weight:700;">
                        Create Your First Tournament
                    </a>
                </div>

                {{-- Tournament Cards --}}
            @else
                <div class="row g-3">
                    @foreach ($tournaments as $tournament)
                        <div class="col-md-6 col-lg-4">
                            <div class="tournament-card">

                                {{-- Top Row --}}
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="t-icon">🏏</div>
                                    <span class="status-badge {{ $tournament->statusBadge['class'] }}">
                                        {{ $tournament->statusBadge['label'] }}
                                    </span>
                                </div>

                                {{-- Name --}}
                                <h3 class="t-name">{{ $tournament->name }}</h3>
                                @if ($tournament->description)
                                    <p class="t-desc">{{ Str::limit($tournament->description, 70) }}</p>
                                @endif

                                {{-- Meta --}}
                                <div class="t-meta">
                                    <div class="t-meta-item">
                                        <i class="bi bi-people-fill"></i>
                                        {{ $tournament->total_teams }} Teams
                                    </div>
                                    <div class="t-meta-item">
                                        <i class="bi bi-person-fill"></i>
                                        {{ $tournament->players_count }} Players
                                    </div>
                                    <div class="t-meta-item">
                                        <i class="bi bi-currency-rupee"></i>
                                        {{ $tournament->formattedBudget }}/team
                                    </div>
                                    <div class="t-meta-item">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $tournament->created_at->format('d M Y') }}
                                    </div>
                                </div>

                                {{-- Registration Badge --}}
                                @if ($tournament->registration_open)
                                    <div class="reg-open-badge">
                                        <i class="bi bi-door-open me-1"></i> Registration Open
                                    </div>
                                @endif

                                {{-- Actions --}}
                                <div class="d-flex gap-2 mt-3">
                                    <a href="{{ route('tournaments.show', $tournament->id) }}"
                                        class="btn btn-primary btn-sm flex-grow-1"
                                        style="border-radius:8px; font-weight:600; font-size:13px;">
                                        Manage
                                    </a>
                                    <a href="{{ route('auction.index', $tournament->id) }}" class="btn btn-sm"
                                        style="border-radius:8px; font-weight:600; font-size:13px;
                                          background:#fefce8; color:#ca8a04; border:1px solid #fde68a;">
                                        <i class="bi bi-lightning-charge-fill"></i> Auction
                                    </a>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" style="border-radius:8px;"
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end"
                                            style="border-radius:12px; border:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(0,0,0,0.1);">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('tournaments.show', $tournament->id) }}">
                                                    <i class="bi bi-pencil me-2"></i> Edit
                                                </a>
                                            </li>
                                            <button class="dropdown-item rounded-2" style="font-size:13px;"
                                                onclick="copyLinks('{{ url('/t/' . $tournament->slug) }}',
                           '{{ url('/auction/' . $tournament->slug . '/room') }}')">
                                                <i class="bi bi-share me-2"></i> Share Links
                                            </button>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form method="POST"
                                                    action="{{ route('tournaments.destroy', $tournament->id) }}"
                                                    onsubmit="return confirm('Delete this tournament? This cannot be undone.')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-trash me-2"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

@endsection

@push('styles')
    <style>
        /* Stat Cards */
        .stat-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 20px;
            text-align: center;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin: 0 auto 12px;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 800;
            color: #111827;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Tournament Card */
        .tournament-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 24px;
            height: 100%;
            transition: all 0.2s ease;
        }

        .tournament-card:hover {
            border-color: #bfdbfe;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            transform: translateY(-2px);
        }

        .t-icon {
            font-size: 2rem;
            line-height: 1;
        }

        .t-name {
            font-size: 1.05rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }

        .t-desc {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 14px;
            line-height: 1.5;
        }

        .t-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 12px;
        }

        .t-meta-item {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            color: #6b7280;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 4px 10px;
            font-weight: 500;
        }

        .t-meta-item i {
            font-size: 11px;
        }

        /* Status Badges */
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-draft {
            background: #f3f4f6;
            color: #374151;
        }

        .badge-active {
            background: #dcfce7;
            color: #16a34a;
        }

        .badge-auction {
            background: #fef9c3;
            color: #ca8a04;
        }

        .badge-completed {
            background: #eff6ff;
            color: #1a56db;
        }

        /* Registration Open */
        .reg-open-badge {
            display: inline-flex;
            align-items: center;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
            border-radius: 8px;
            padding: 5px 12px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Empty State */
        .empty-state {
            background: #fff;
            border: 2px dashed #e5e7eb;
            border-radius: 20px;
            padding: 64px 24px;
            text-align: center;
        }
    </style>
    @push('scripts')
        <script>
            function copyLinks(regLink, roomLink) {
                // Show a small modal with both links
                const modal = document.createElement('div');
                modal.style.cssText = `
        position:fixed; inset:0; background:rgba(0,0,0,0.5);
        display:flex; align-items:center; justify-content:center;
        z-index:9999; padding:16px;`;
                modal.innerHTML = `
        <div style="background:#fff; border-radius:16px; padding:24px;
                    max-width:480px; width:100%; box-shadow:0 24px 48px rgba(0,0,0,0.2);">
            <div style="font-weight:800; font-size:1.1rem; color:#111827; margin-bottom:4px;">
                <i class="bi bi-share" style="color:#1a56db;"></i> Share Links
            </div>
            <div style="font-size:13px; color:#6b7280; margin-bottom:20px;">
                Share these links with players and viewers
            </div>

            <div style="margin-bottom:14px;">
                <div style="font-size:11px; color:#6b7280; font-weight:600;
                            text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">
                    <i class="bi bi-person-plus me-1"></i> Player Registration
                </div>
                <div style="display:flex; gap:8px;">
                    <div style="flex:1; background:#f9fafb; border:1px solid #e5e7eb;
                                border-radius:8px; padding:8px 12px; font-size:13px;
                                color:#1a56db; overflow:hidden; text-overflow:ellipsis;
                                white-space:nowrap;">
                        ${regLink}
                    </div>
                    <button onclick="navigator.clipboard.writeText('${regLink}');
                                     this.innerHTML='<i class=\\'bi bi-check-lg\\'></i>';
                                     setTimeout(()=>this.innerHTML='<i class=\\'bi bi-copy\\'></i>',2000);"
                            style="padding:8px 12px; background:#eff6ff; color:#1a56db;
                                   border:1px solid #bfdbfe; border-radius:8px;
                                   cursor:pointer; font-size:14px;">
                        <i class="bi bi-copy"></i>
                    </button>
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <div style="font-size:11px; color:#6b7280; font-weight:600;
                            text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">
                    <i class="bi bi-broadcast me-1"></i> Live Auction Room
                </div>
                <div style="display:flex; gap:8px;">
                    <div style="flex:1; background:#f9fafb; border:1px solid #e5e7eb;
                                border-radius:8px; padding:8px 12px; font-size:13px;
                                color:#ca8a04; overflow:hidden; text-overflow:ellipsis;
                                white-space:nowrap;">
                        ${roomLink}
                    </div>
                    <button onclick="navigator.clipboard.writeText('${roomLink}');
                                     this.innerHTML='<i class=\\'bi bi-check-lg\\'></i>';
                                     setTimeout(()=>this.innerHTML='<i class=\\'bi bi-copy\\'></i>',2000);"
                            style="padding:8px 12px; background:#fefce8; color:#ca8a04;
                                   border:1px solid #fde68a; border-radius:8px;
                                   cursor:pointer; font-size:14px;">
                        <i class="bi bi-copy"></i>
                    </button>
                </div>
            </div>

            <div style="display:flex; gap:8px; justify-content:flex-end;">
                <a href="${roomLink}" target="_blank"
                   style="padding:9px 20px; background:#1a56db; color:#fff;
                          border-radius:10px; font-weight:700; font-size:13px;
                          text-decoration:none; display:flex; align-items:center; gap:6px;">
                    <i class="bi bi-broadcast"></i> Open Room
                </a>
                <button onclick="this.closest('[style*=fixed]').remove()"
                        style="padding:9px 20px; background:#f3f4f6; color:#374151;
                               border:none; border-radius:10px; font-weight:600;
                               font-size:13px; cursor:pointer;">
                    Close
                </button>
            </div>
        </div>`;
                document.body.appendChild(modal);
                modal.addEventListener('click', e => {
                    if (e.target === modal) modal.remove();
                });
            }

            function copyLink(url) {
                navigator.clipboard.writeText(url)
                    .then(() => showToast('Link copied!', 'success'));
            }
        </script>
    @endpush
@endpush
