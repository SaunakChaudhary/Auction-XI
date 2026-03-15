@extends('layouts.app')

@section('title', 'CricAuction - Free Cricket Tournament Auction Platform')

@section('content')

{{-- ===== HERO ===== --}}
<section class="ca-hero">
    <div class="ca-hero-bg-circle ca-hero-bg-circle--1"></div>
    <div class="ca-hero-bg-circle ca-hero-bg-circle--2"></div>

    <div class="container position-relative" style="z-index:1;">
        <div class="row align-items-center g-5 min-vh-hero">

            {{-- Left --}}
            <div class="col-12 col-lg-6">
                <div class="ca-hero-badge">
                    <i class="bi bi-patch-check-fill me-1"></i> Beta — Free for Everyone
                </div>
                <h1 class="ca-hero-title">
                    Run Your Cricket<br>
                    <span class="ca-hero-title--accent">Auction Like a Pro</span>
                </h1>
                <p class="ca-hero-subtitle">
                    Create tournaments, manage teams, register players and run live auctions — all in one place. Import player data directly from Google Sheets.
                </p>
                <div class="ca-hero-actions">
                    <a href="{{ route('register') }}" class="btn ca-btn-accent ca-btn-lg">
                        <i class="bi bi-plus-circle-fill me-2"></i>Create Tournament Free
                    </a>
                    <a href="#how-it-works" class="btn ca-btn-ghost ca-btn-lg">
                        <i class="bi bi-play-circle me-2"></i>See How it Works
                    </a>
                </div>

                <div class="ca-hero-stats">
                    <div class="ca-hero-stat">
                        <div class="ca-hero-stat__number">100%</div>
                        <div class="ca-hero-stat__label">Free Beta</div>
                    </div>
                    <div class="ca-hero-stat-divider"></div>
                    <div class="ca-hero-stat">
                        <div class="ca-hero-stat__number">Live</div>
                        <div class="ca-hero-stat__label">Auction Mode</div>
                    </div>
                    <div class="ca-hero-stat-divider"></div>
                    <div class="ca-hero-stat">
                        <div class="ca-hero-stat__number">Sheets</div>
                        <div class="ca-hero-stat__label">Import Ready</div>
                    </div>
                </div>
            </div>

            {{-- Right — Mock Auction Card --}}
            <div class="col-12 col-lg-6">
                <div class="ca-auction-card">
                    <div class="ca-auction-card__live-label">
                        <span class="ca-live-dot"></span>
                        Live Auction — IPL Locals 2025
                    </div>

                    {{-- Current Player --}}
                    <div class="ca-bidding-block">
                        <div class="ca-bidding-block__label">
                            <i class="bi bi-megaphone-fill me-1"></i>Now Bidding
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="ca-avatar ca-avatar--amber ca-avatar--lg">RK</div>
                            <div class="flex-grow-1">
                                <div class="ca-bidding-block__name">Rahul Kumar</div>
                                <div class="ca-bidding-block__role">
                                    <i class="bi bi-person-fill me-1"></i>Right-hand Bat &middot; Mumbai
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="ca-bidding-block__price">&#8377;85,000</div>
                                <div class="ca-bidding-block__price-label">Current Bid</div>
                            </div>
                        </div>
                        <div class="ca-bidding-block__meta">
                            <span class="ca-meta-tag">
                                <i class="bi bi-tag me-1"></i>Base: &#8377;50,000
                            </span>
                            <span class="ca-meta-tag ca-meta-tag--green">
                                <i class="bi bi-arrow-up-circle me-1"></i>+3 bids
                            </span>
                        </div>
                    </div>

                    {{-- Recent Players --}}
                    <div class="ca-player-row">
                        <div class="ca-avatar ca-avatar--blue">AS</div>
                        <div class="flex-grow-1">
                            <div class="ca-player-row__name">Arjun Singh</div>
                            <div class="ca-player-row__role">
                                <i class="bi bi-wind me-1"></i>Fast Bowler
                            </div>
                        </div>
                        <span class="ca-badge ca-badge--amber">
                            <i class="bi bi-hammer me-1"></i>SOLD &#8377;1.2L
                        </span>
                    </div>

                    <div class="ca-player-row ca-player-row--last">
                        <div class="ca-avatar ca-avatar--purple">PV</div>
                        <div class="flex-grow-1">
                            <div class="ca-player-row__name">Priya Varma</div>
                            <div class="ca-player-row__role">
                                <i class="bi bi-stars me-1"></i>All-rounder
                            </div>
                        </div>
                        <span class="ca-badge ca-badge--green">
                            <i class="bi bi-hammer me-1"></i>SOLD &#8377;75K
                        </span>
                    </div>

                    {{-- Budget Bar --}}
                    <div class="ca-budget-bar">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="ca-budget-bar__label">
                                <i class="bi bi-people-fill me-1"></i>Team Mumbai — Budget
                            </span>
                            <span class="ca-budget-bar__value">&#8377;3.15L / &#8377;5L remaining</span>
                        </div>
                        <div class="ca-budget-track">
                            <div class="ca-budget-fill" style="width:63%"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ===== FEATURES ===== --}}
<section class="ca-features" id="features">
    <div class="container">
        <div class="text-center ca-section-header">
            <div class="ca-section-label">Everything You Need</div>
            <h2 class="ca-section-title">Built for Cricket Organisers</h2>
            <p class="ca-section-subtitle">
                From player registration to live auction — manage your entire cricket tournament in one platform.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="ca-feature-card">
                    <div class="ca-feature-icon ca-feature-icon--blue">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <div class="ca-feature-title">Tournament Management</div>
                    <div class="ca-feature-desc">Create and manage multiple tournaments. Set team count, budget per team, squad size and auction rules all in one place.</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="ca-feature-card">
                    <div class="ca-feature-icon ca-feature-icon--green">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <div class="ca-feature-title">Player Self-Registration</div>
                    <div class="ca-feature-desc">Share a public link. Players register themselves with name, contact, role, age and photo. No manual entry needed.</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="ca-feature-card">
                    <div class="ca-feature-icon ca-feature-icon--yellow">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                    <div class="ca-feature-title">Live Auction Mode</div>
                    <div class="ca-feature-desc">Run real-time bidding between team owners. Budgets auto-update after every sold player with full validation.</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="ca-feature-card">
                    <div class="ca-feature-icon ca-feature-icon--purple">
                        <i class="bi bi-table"></i>
                    </div>
                    <div class="ca-feature-title">Google Sheets Import</div>
                    <div class="ca-feature-desc">Already have player data in a Google Sheet? Just paste the link and we'll import all players instantly.</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="ca-feature-card">
                    <div class="ca-feature-icon ca-feature-icon--orange">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div class="ca-feature-title">Smart Budget Tracker</div>
                    <div class="ca-feature-desc">Auto-calculate remaining budget per team. Get warnings when a bid exceeds the team's remaining budget.</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="ca-feature-card">
                    <div class="ca-feature-icon ca-feature-icon--teal">
                        <i class="bi bi-share-fill"></i>
                    </div>
                    <div class="ca-feature-title">Shareable Tournament Page</div>
                    <div class="ca-feature-desc">Every tournament gets a public page. Share with players, team owners and spectators easily.</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== HOW IT WORKS ===== --}}
<section class="ca-how" id="how-it-works">
    <div class="container">
        <div class="text-center ca-section-header">
            <div class="ca-section-label">Simple Process</div>
            <h2 class="ca-section-title">How It Works</h2>
        </div>

        <div class="row g-4 g-lg-0 ca-steps-row">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="ca-step">
                    <div class="ca-step__number">1</div>
                    <div class="ca-step__connector"></div>
                    <div class="ca-step__icon"><i class="bi bi-plus-square-fill"></i></div>
                    <div class="ca-step__title">Create Tournament</div>
                    <div class="ca-step__desc">Set up your tournament with teams, budget per team and squad size limits.</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="ca-step">
                    <div class="ca-step__number">2</div>
                    <div class="ca-step__connector"></div>
                    <div class="ca-step__icon"><i class="bi bi-person-lines-fill"></i></div>
                    <div class="ca-step__title">Add Players</div>
                    <div class="ca-step__desc">Players self-register via link or import from Google Sheets in one click.</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="ca-step">
                    <div class="ca-step__number">3</div>
                    <div class="ca-step__connector"></div>
                    <div class="ca-step__icon"><i class="bi bi-hammer"></i></div>
                    <div class="ca-step__title">Run Auction</div>
                    <div class="ca-step__desc">Start the auction. Set base price, take bids and assign players to teams.</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="ca-step ca-step--last">
                    <div class="ca-step__number">4</div>
                    <div class="ca-step__icon"><i class="bi bi-bar-chart-fill"></i></div>
                    <div class="ca-step__title">View Results</div>
                    <div class="ca-step__desc">See full squad lists, spend summaries and export the auction results.</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="ca-cta">
    <div class="ca-cta-bg-circle ca-cta-bg-circle--1"></div>
    <div class="ca-cta-bg-circle ca-cta-bg-circle--2"></div>
    <div class="container text-center position-relative" style="z-index:1;">
        <div class="ca-cta-icon">
            <i class="bi bi-rocket-takeoff-fill"></i>
        </div>
        <h2 class="ca-cta-title">Ready to Run Your Auction?</h2>
        <p class="ca-cta-subtitle">It&rsquo;s completely free during beta. No credit card needed.</p>
        <a href="{{ route('register') }}" class="btn ca-btn-accent ca-btn-lg">
            <i class="bi bi-arrow-right-circle-fill me-2"></i>Get Started for Free
        </a>
    </div>
</section>

@endsection