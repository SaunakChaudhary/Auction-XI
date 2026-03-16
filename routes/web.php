<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\ImportController;

// ===== PUBLIC =====
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login',     [LoginController::class,    'showForm'])->name('login');
    Route::post('/login',    [LoginController::class,    'login']);
    Route::get('/register',  [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Public Tournament Page
Route::get('/t/{slug}',          [TournamentController::class, 'public'])->name('tournament.public');
Route::get('/t/{slug}/register', [PlayerController::class,     'showForm'])->name('player.register.form');
Route::post('/t/{slug}/register', [PlayerController::class,     'register'])->name('player.register');
// Public auction room + state API (no auth needed)
Route::get(
    '/auction/{slug}/room',
    [AuctionController::class, 'room']
)->name('auction.room');
Route::get(
    '/tournaments/{tournament}/auction/state',
    [AuctionController::class, 'state']
)->name('auction.state');
// ===== PROTECTED =====
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tournaments
    Route::resource('tournaments', TournamentController::class);
    Route::post(
        '/tournaments/{tournament}/toggle-registration',
        [TournamentController::class, 'toggleRegistration']
    )->name('tournaments.toggle-registration');
    Route::post(
        '/tournaments/{tournament}/status',
        [TournamentController::class, 'updateStatus']
    )->name('tournaments.update-status');

    // Teams
    Route::resource('tournaments.teams', TeamController::class);

    // Players
    Route::get(
        '/tournaments/{tournament}/players',
        [PlayerController::class, 'index']
    )->name('players.index');
    Route::patch(
        '/tournaments/{tournament}/players/{player}/base-price',
        [PlayerController::class, 'updateBasePrice']
    )->name('players.base-price');
    Route::post(
        '/tournaments/{tournament}/players/bulk-status',
        [PlayerController::class, 'bulkUpdateStatus']
    )->name('players.bulk-status');
    Route::delete(
        '/tournaments/{tournament}/players/{player}',
        [PlayerController::class, 'destroy']
    )->name('players.destroy');

    // Import
    Route::get(
        '/tournaments/{tournament}/import',
        [ImportController::class, 'index']
    )->name('import.index');
    Route::post(
        '/tournaments/{tournament}/import',
        [ImportController::class, 'import']
    )->name('import.store');
    Route::get(
        '/tournaments/{tournament}/import/sample',
        [ImportController::class, 'sampleCsv']
    )->name('import.sample');

    // Auction
    Route::get(
        '/tournaments/{tournament}/auction',
        [AuctionController::class, 'index']
    )->name('auction.index');
    Route::post(
        '/tournaments/{tournament}/auction/sell',
        [AuctionController::class, 'sellPlayer']
    )->name('auction.sell');
    Route::post(
        '/tournaments/{tournament}/auction/unsold',
        [AuctionController::class, 'markUnsold']
    )->name('auction.unsold');
    Route::post(
        '/tournaments/{tournament}/auction/undo',
        [AuctionController::class, 'undoSell']
    )->name('auction.undo');
    Route::get(
        '/tournaments/{tournament}/auction/live',
        [AuctionController::class, 'live']
    )->name('auction.live');
    Route::get(
        '/tournaments/{tournament}/auction/results',
        [AuctionController::class, 'results']
    )->name('auction.results');
});
