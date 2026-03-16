@extends('layouts.app')

@section('title', 'Auction XI - Cricket Tournament Auction Platform')

@section('content')

{{-- ===== HERO ===== --}}
<section class="hero-section">
    <div class="container position-relative" style="z-index:2;">
        <div class="row align-items-center g-5">

            {{-- Left --}}
            <div class="col-lg-6">
                <div class="hero-badge">
                    <i class="bi bi-star-fill me-1"></i>
                    Beta &mdash; Free for Everyone
                </div>
                <h1 class="hero-title">
                    Run Your Cricket<br>
                    <span>Auction Like a Pro</span>
                </h1>
                <p class="hero-subtitle mt-3">
                    Create tournaments, manage teams, register players and run
                    live auctions — all in one place. Import player data directly
                    from Google Sheets.
                </p>
                <div class="d-flex gap-3 mt-4 flex-wrap">
                    <a href="{{ route('register') }}" class="btn-hero-primary">
                        <i class="bi bi-plus-circle-fill me-2"></i>
                        Create Tournament Free
                    </a>
                    <a href="#how-it-works" class="btn-hero-outline">
                        <i class="bi bi-play-circle me-2"></i>
                        See How it Works
                    </a>
                </div>

                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="hero-stat-number">100%</div>
                        <div class="hero-stat-label">Free Beta</div>
                    </div>
                    <div class="hero-stat-divider"></div>
                    <div class="hero-stat">
                        <div class="hero-stat-number">Live</div>
                        <div class="hero-stat-label">Auction Mode</div>
                    </div>
                    <div class="hero-stat-divider"></div>
                    <div class="hero-stat">
                        <div class="hero-stat-number">Sheets</div>
                        <div class="hero-stat-label">Import Ready</div>
                    </div>
                </div>
            </div>

            {{-- Right — Mock Auction Card --}}
            <div class="col-lg-6">
                <div class="hero-card">
                    <div class="hero-card-label">
                        <span class="live-dot"></span>
                        Live Auction &mdash; IPL Locals 2025
                    </div>

                    {{-- Current Player --}}
                    <div class="hero-now-bidding">
                        <div class="hero-now-label">
                            <i class="bi bi-hammer me-1"></i> Now Bidding
                        </div>
                        <div class="d-flex align-items-center gap-3 mt-2">
                            <div class="hero-player-avatar" style="background:#f59e0b;">RK</div>
                            <div class="flex-grow-1">
                                <div class="hero-player-name">Rahul Kumar</div>
                                <div class="hero-player-meta">
                                    <i class="bi bi-cricket me-1"></i>
                                    Right-hand Bat &middot; Mumbai
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="hero-bid-amount">&#8377;85,000</div>
                                <div class="hero-bid-label">Current Bid</div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <div class="hero-tag">
                                <i class="bi bi-tag me-1"></i>
                                Base: &#8377;50,000
                            </div>
                            <div class="hero-tag hero-tag-green">
                                <i class="bi bi-arrow-up me-1"></i>
                                +3 bids
                            </div>
                        </div>
                    </div>

                    {{-- Sold Players --}}
                    <div class="hero-sold-row">
                        <div class="hero-sold-avatar" style="background:#3b82f6;">AS</div>
                        <div class="flex-grow-1">
                            <div class="hero-sold-name">Arjun Singh</div>
                            <div class="hero-sold-meta">
                                <i class="bi bi-lightning me-1"></i>Fast Bowler
                            </div>
                        </div>
                        <span class="sold-pill sold-yellow">
                            <i class="bi bi-hammer me-1"></i>SOLD &#8377;1.2L
                        </span>
                    </div>

                    <div class="hero-sold-row" style="margin-bottom:0;">
                        <div class="hero-sold-avatar" style="background:#8b5cf6;">PV</div>
                        <div class="flex-grow-1">
                            <div class="hero-sold-name">Priya Varma</div>
                            <div class="hero-sold-meta">
                                <i class="bi bi-arrow-left-right me-1"></i>All-rounder
                            </div>
                        </div>
                        <span class="sold-pill sold-green">
                            <i class="bi bi-hammer me-1"></i>SOLD &#8377;75K
                        </span>
                    </div>

                    {{-- Budget Bar --}}
                    <div class="hero-budget-bar">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="hero-budget-label">
                                <i class="bi bi-people me-1"></i>
                                Team Mumbai &mdash; Budget
                            </span>
                            <span class="hero-budget-remaining">
                                &#8377;3.15L / &#8377;5L remaining
                            </span>
                        </div>
                        <div class="hero-progress-track">
                            <div class="hero-progress-fill" style="width:63%;"></div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

{{-- ===== FEATURES ===== --}}
<section class="features-section" id="features">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label-tag">
                <i class="bi bi-grid me-1"></i> Everything You Need
            </div>
            <h2 class="section-heading">Built for Cricket Organisers</h2>
            <p class="section-subtext">
                From player registration to live auction — manage your entire
                cricket tournament in one platform.
            </p>
        </div>

        <div class="row g-4">
            @foreach([
                [
                    'icon'  => 'bi-trophy',
                    'color' => '#eff6ff',
                    'iclr'  => '#1a56db',
                    'title' => 'Tournament Management',
                    'desc'  => 'Create and manage multiple tournaments. Set team count, budget per team, squad size and auction rules all in one place.',
                ],
                [
                    'icon'  => 'bi-person-plus',
                    'color' => '#f0fdf4',
                    'iclr'  => '#16a34a',
                    'title' => 'Player Self-Registration',
                    'desc'  => 'Share a public link. Players register themselves with name, contact, role, age and photo. No manual entry needed.',
                ],
                [
                    'icon'  => 'bi-lightning-charge',
                    'color' => '#fefce8',
                    'iclr'  => '#ca8a04',
                    'title' => 'Live Auction Mode',
                    'desc'  => 'Run real-time bidding between team owners. Budgets auto-update after every sold player with full validation.',
                ],
                [
                    'icon'  => 'bi-file-earmark-spreadsheet',
                    'color' => '#fdf4ff',
                    'iclr'  => '#9333ea',
                    'title' => 'Google Sheets Import',
                    'desc'  => 'Already have player data in a Google Sheet? Just paste the link and we\'ll import all players instantly.',
                ],
                [
                    'icon'  => 'bi-wallet2',
                    'color' => '#fff7ed',
                    'iclr'  => '#ea580c',
                    'title' => 'Smart Budget Tracker',
                    'desc'  => 'Auto-calculate remaining budget per team. Get warnings when a bid exceeds the team\'s remaining budget.',
                ],
                [
                    'icon'  => 'bi-share',
                    'color' => '#f0fdfa',
                    'iclr'  => '#0d9488',
                    'title' => 'Shareable Tournament Page',
                    'desc'  => 'Every tournament gets a public page. Share with players, team owners and spectators easily.',
                ],
            ] as $feature)
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"
                             style="background:{{ $feature['color'] }}; color:{{ $feature['iclr'] }};">
                            <i class="bi {{ $feature['icon'] }}"></i>
                        </div>
                        <div class="feature-title">{{ $feature['title'] }}</div>
                        <div class="feature-desc">{{ $feature['desc'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== HOW IT WORKS ===== --}}
<section class="how-section" id="how-it-works">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label-tag">
                <i class="bi bi-list-ol me-1"></i> Simple Process
            </div>
            <h2 class="section-heading">How It Works</h2>
        </div>

        <div class="row g-4 justify-content-center">
            @foreach([
                ['bi-plus-circle',      '1', 'Create Tournament',  'Set up your tournament with teams, budget per team and squad size limits.'],
                ['bi-person-plus',      '2', 'Add Players',        'Players self-register via link or import from Google Sheets in one click.'],
                ['bi-hammer',           '3', 'Run Auction',        'Start the auction. Set base price, take bids and assign players to teams.'],
                ['bi-bar-chart-line',   '4', 'View Results',       'See full squad lists, spend summaries and export the auction results.'],
            ] as [$icon, $num, $title, $desc])
                <div class="col-6 col-md-3">
                    <div class="step-card">
                        <div class="step-number-wrap">
                            <div class="step-icon">
                                <i class="bi {{ $icon }}"></i>
                            </div>
                            <div class="step-num">{{ $num }}</div>
                        </div>
                        <div class="step-title">{{ $title }}</div>
                        <div class="step-desc">{{ $desc }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>


@endsection

@push('styles')
<style>
/* ===== HERO ===== */
.hero-section {
    background: linear-gradient(135deg, #1e3a8a 0%, #1a56db 55%, #3b82f6 100%);
    min-height: 90vh;
    display: flex;
    align-items: center;
    padding: 80px 0;
    position: relative;
    overflow: hidden;
}
.hero-section::before {
    content: '';
    position: absolute;
    width: 700px; height: 700px;
    background: rgba(255,255,255,0.03);
    border-radius: 50%;
    top: -200px; right: -200px;
    pointer-events: none;
}
.hero-section::after {
    content: '';
    position: absolute;
    width: 400px; height: 400px;
    background: rgba(255,255,255,0.03);
    border-radius: 50%;
    bottom: -100px; left: -100px;
    pointer-events: none;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    background: rgba(245,158,11,0.15);
    color: #fcd34d;
    border: 1px solid rgba(245,158,11,0.3);
    padding: 6px 16px;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 20px;
    letter-spacing: 0.3px;
}

.hero-title {
    font-size: clamp(2rem, 4vw, 3.2rem);
    font-weight: 800;
    color: #fff;
    line-height: 1.15;
    letter-spacing: -0.5px;
    margin: 0;
}
.hero-title span { color: #fcd34d; }

.hero-subtitle {
    font-size: 1rem;
    color: rgba(255,255,255,0.75);
    line-height: 1.75;
    max-width: 500px;
    margin: 0;
}

.btn-hero-primary {
    display: inline-flex;
    align-items: center;
    padding: 13px 28px;
    background: #f59e0b;
    color: #fff;
    border-radius: 10px;
    font-weight: 700;
    font-size: 15px;
    text-decoration: none;
    transition: background 0.2s, transform 0.15s;
    border: none;
}
.btn-hero-primary:hover {
    background: #d97706;
    color: #fff;
    transform: translateY(-1px);
}

.btn-hero-outline {
    display: inline-flex;
    align-items: center;
    padding: 13px 24px;
    background: transparent;
    color: rgba(255,255,255,0.9);
    border-radius: 10px;
    font-weight: 600;
    font-size: 15px;
    text-decoration: none;
    border: 1.5px solid rgba(255,255,255,0.3);
    transition: background 0.2s, border-color 0.2s;
}
.btn-hero-outline:hover {
    background: rgba(255,255,255,0.1);
    border-color: rgba(255,255,255,0.5);
    color: #fff;
}

.hero-stats {
    display: flex;
    align-items: center;
    gap: 28px;
    margin-top: 36px;
    flex-wrap: wrap;
}
.hero-stat-number {
    font-size: 1.7rem;
    font-weight: 800;
    color: #fff;
    line-height: 1;
}
.hero-stat-label {
    font-size: 11px;
    color: rgba(255,255,255,0.55);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 3px;
}
.hero-stat-divider {
    width: 1px;
    height: 36px;
    background: rgba(255,255,255,0.18);
}

/* Hero Card */
.hero-card {
    background: rgba(255,255,255,0.09);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 20px;
    padding: 24px;
}
.hero-card-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: rgba(255,255,255,0.6);
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin-bottom: 16px;
}
.live-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: #22c55e;
    display: inline-block;
    animation: pulse 1.5s infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50%       { opacity: 0.4; }
}

.hero-now-bidding {
    background: rgba(255,255,255,0.1);
    border-radius: 14px;
    padding: 18px;
    margin-bottom: 14px;
}
.hero-now-label {
    font-size: 11px;
    color: rgba(255,255,255,0.5);
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.hero-player-avatar {
    width: 48px; height: 48px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 15px; color: #fff;
    flex-shrink: 0;
    letter-spacing: 0.5px;
}
.hero-player-name {
    font-weight: 700;
    font-size: 15px;
    color: #fff;
}
.hero-player-meta {
    font-size: 12px;
    color: rgba(255,255,255,0.55);
    margin-top: 2px;
}
.hero-bid-amount {
    font-size: 1.3rem;
    font-weight: 800;
    color: #fcd34d;
}
.hero-bid-label {
    font-size: 11px;
    color: rgba(255,255,255,0.45);
    margin-top: 2px;
}

.hero-tag {
    display: inline-flex;
    align-items: center;
    background: rgba(255,255,255,0.1);
    border-radius: 7px;
    padding: 5px 12px;
    font-size: 12px;
    color: rgba(255,255,255,0.7);
}
.hero-tag-green {
    background: rgba(34,197,94,0.18);
    color: #86efac;
    font-weight: 600;
}

.hero-sold-row {
    background: #fff;
    border-radius: 12px;
    padding: 12px 14px;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px;
}
.hero-sold-avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 12px; color: #fff;
    flex-shrink: 0;
}
.hero-sold-name {
    font-weight: 700;
    font-size: 13px;
    color: #111827;
}
.hero-sold-meta {
    font-size: 11px;
    color: #6b7280;
    margin-top: 1px;
}
.sold-pill {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
    flex-shrink: 0;
}
.sold-yellow { background: #fef9c3; color: #ca8a04; }
.sold-green  { background: #dcfce7; color: #16a34a; }

.hero-budget-bar {
    margin-top: 16px;
    padding-top: 14px;
    border-top: 1px solid rgba(255,255,255,0.1);
}
.hero-budget-label   { font-size: 12px; color: rgba(255,255,255,0.55); }
.hero-budget-remaining { font-size: 12px; color: #86efac; font-weight: 600; }
.hero-progress-track {
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
    height: 6px;
}
.hero-progress-fill {
    background: #22c55e;
    height: 6px;
    border-radius: 10px;
}

/* ===== FEATURES ===== */
.features-section {
    padding: 96px 0;
    background: #f9fafb;
}
.section-label-tag {
    display: inline-flex;
    align-items: center;
    color: #1a56db;
    font-weight: 700;
    font-size: 12px;
    letter-spacing: 1px;
    text-transform: uppercase;
    background: #eff6ff;
    padding: 5px 14px;
    border-radius: 20px;
    margin-bottom: 14px;
}
.section-heading {
    font-size: clamp(1.6rem, 3vw, 2.2rem);
    font-weight: 800;
    color: #111827;
    line-height: 1.25;
    margin: 0 0 12px;
}
.section-subtext {
    color: #6b7280;
    font-size: 15px;
    max-width: 520px;
    margin: 0 auto;
    line-height: 1.7;
}
.feature-card {
    background: #fff;
    border-radius: 16px;
    padding: 28px;
    border: 1px solid #e5e7eb;
    height: 100%;
    transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
}
.feature-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.08);
    border-color: #bfdbfe;
}
.feature-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    margin-bottom: 18px;
}
.feature-title {
    font-size: 1rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
}
.feature-desc {
    color: #6b7280;
    font-size: 14px;
    line-height: 1.7;
}

/* ===== HOW IT WORKS ===== */
.how-section {
    padding: 96px 0;
    background: #fff;
}
.step-card {
    text-align: center;
    padding: 28px 16px;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    background: #fff;
    height: 100%;
    transition: box-shadow 0.2s;
}
.step-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.07); }
.step-number-wrap {
    position: relative;
    display: inline-flex;
    margin-bottom: 18px;
}
.step-icon {
    width: 56px; height: 56px;
    border-radius: 50%;
    background: #eff6ff;
    color: #1a56db;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    margin: 0 auto;
}
.step-num {
    position: absolute;
    top: -6px; right: -6px;
    width: 22px; height: 22px;
    background: #1a56db;
    color: #fff;
    border-radius: 50%;
    font-size: 11px;
    font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid #fff;
}
.step-title {
    font-weight: 700;
    font-size: 15px;
    color: #111827;
    margin-bottom: 8px;
}
.step-desc {
    font-size: 13px;
    color: #6b7280;
    line-height: 1.65;
}

/* ===== CTA ===== */
.cta-section {
    background: linear-gradient(135deg, #1e3a8a 0%, #1a56db 100%);
    padding: 80px 0;
}
.cta-icon {
    width: 64px; height: 64px;
    background: rgba(255,255,255,0.12);
    border-radius: 18px;
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; color: #fcd34d;
    margin: 0 auto 20px;
}
.cta-title {
    font-size: clamp(1.6rem, 3vw, 2.2rem);
    font-weight: 800;
    color: #fff;
    margin-bottom: 12px;
}
.cta-sub {
    color: rgba(255,255,255,0.7);
    font-size: 15px;
    margin-bottom: 32px;
}
.btn-cta {
    display: inline-flex;
    align-items: center;
    padding: 14px 36px;
    background: #f59e0b;
    color: #fff;
    border-radius: 12px;
    font-weight: 800;
    font-size: 16px;
    text-decoration: none;
    transition: background 0.2s, transform 0.15s;
    border: none;
}
.btn-cta:hover {
    background: #d97706;
    color: #fff;
    transform: translateY(-2px);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 991px) {
    .hero-section { min-height: auto; padding: 60px 0; }
    .hero-card    { margin-top: 12px; }
}
@media (max-width: 767px) {
    .hero-section { padding: 48px 0; }
    .hero-stats   { gap: 16px; }
    .features-section, .how-section { padding: 60px 0; }
    .cta-section  { padding: 56px 0; }
    .step-card    { padding: 20px 12px; }
}
</style>
@endpush