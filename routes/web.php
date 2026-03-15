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

// ===== PUBLIC ROUTES =====
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class,   'showForm'])->name('login');
    Route::post('/login',   [LoginController::class,   'login']);
    Route::get('/register', [RegisterController::class,'showForm'])->name('register');
    Route::post('/register',[RegisterController::class,'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ===== PROTECTED ROUTES =====
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tournaments
    Route::resource('tournaments', TournamentController::class);

    // Teams
    Route::resource('tournaments.teams', TeamController::class);

    // Players
    Route::get('/tournaments/{tournament}/players',
        [PlayerController::class, 'index'])->name('players.index');
    Route::delete('/tournaments/{tournament}/players/{player}',
        [PlayerController::class, 'destroy'])->name('players.destroy');

    // Import
    Route::get('/tournaments/{tournament}/import',
        [ImportController::class, 'index'])->name('import.index');
    Route::post('/tournaments/{tournament}/import',
        [ImportController::class, 'import'])->name('import.store');

    // Auction
    Route::get('/tournaments/{tournament}/auction',
        [AuctionController::class, 'index'])->name('auction.index');
    Route::get('/tournaments/{tournament}/auction/live',
        [AuctionController::class, 'live'])->name('auction.live');
    Route::get('/tournaments/{tournament}/auction/results',
        [AuctionController::class, 'results'])->name('auction.results');
});

// ===== PUBLIC TOURNAMENT PAGE (for player registration) =====
Route::get('/t/{slug}', [TournamentController::class, 'public'])->name('tournament.public');
Route::get('/t/{slug}/register', [PlayerController::class, 'showForm'])->name('player.register.form');
Route::post('/t/{slug}/register', [PlayerController::class, 'register'])->name('player.register');

// Tournament extra actions
Route::post('/tournaments/{tournament}/toggle-registration',
    [TournamentController::class, 'toggleRegistration'])->name('tournaments.toggle-registration');

Route::post('/tournaments/{tournament}/status',
    [TournamentController::class, 'updateStatus'])->name('tournaments.update-status');