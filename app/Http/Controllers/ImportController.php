<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function index(Tournament $tournament)
    {
        return view('import.index', compact('tournament'));
    }

    public function import(Request $request, Tournament $tournament)
    {
        // Build next
    }
}