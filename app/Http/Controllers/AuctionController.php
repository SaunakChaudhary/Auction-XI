<?php

namespace App\Http\Controllers;

use App\Models\Tournament;

class AuctionController extends Controller
{
    public function index(Tournament $tournament)
    {
        return view('auction.index', compact('tournament'));
    }

    public function live(Tournament $tournament)
    {
        return view('auction.live', compact('tournament'));
    }

    public function results(Tournament $tournament)
    {
        return view('auction.results', compact('tournament'));
    }
}