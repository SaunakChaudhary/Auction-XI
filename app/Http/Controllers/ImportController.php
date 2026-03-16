<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ImportController extends Controller
{
    public function index(Tournament $tournament)
    {
        $this->authorizeOwner($tournament);
        $recentImports = $tournament->players()
            ->whereNotNull('created_at')
            ->latest()
            ->take(5)
            ->get();
        return view('import.index', compact('tournament', 'recentImports'));
    }

    public function import(Request $request, Tournament $tournament)
    {
        $this->authorizeOwner($tournament);

        $request->validate([
            'sheet_url' => 'required|url',
        ], [
            'sheet_url.required' => 'Please paste your Google Sheets link.',
            'sheet_url.url'      => 'Please enter a valid URL.',
        ]);

        // Convert Google Sheets URL to CSV export URL
        $csvUrl = $this->convertToCsvUrl($request->sheet_url);

        if (!$csvUrl) {
            return back()->withErrors([
                'sheet_url' => 'Invalid Google Sheets URL. Please use a valid shareable link.'
            ]);
        }

        // Fetch CSV data
        try {
            $response = Http::timeout(15)->get($csvUrl);

            if (!$response->successful()) {
                return back()->withErrors([
                    'sheet_url' => 'Could not fetch the sheet. Make sure it is shared publicly (Anyone with link can view).'
                ]);
            }

            $csvData = $response->body();
        } catch (\Exception $e) {
            return back()->withErrors([
                'sheet_url' => 'Failed to fetch sheet: ' . $e->getMessage()
            ]);
        }

        // Parse CSV
        $rows    = $this->parseCsv($csvData);
        $headers = array_map('strtolower', array_map('trim', $rows[0] ?? []));

        if (empty($headers)) {
            return back()->withErrors(['sheet_url' => 'Sheet appears to be empty.']);
        }

        // Map column indexes
        $colMap = $this->mapColumns($headers);

        if (!isset($colMap['name'])) {
            return back()->withErrors([
                'sheet_url' => 'Could not find a "Name" column. Make sure your sheet has a Name column.'
            ]);
        }

        // Process rows
        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        foreach (array_slice($rows, 1) as $lineNo => $row) {
            if (empty(array_filter($row))) continue; // skip empty rows

            $name = trim($row[$colMap['name']] ?? '');
            if (empty($name)) {
                $skipped++;
                continue;
            }

            // Check duplicate phone
            $phone = isset($colMap['phone'])
                ? preg_replace('/\D/', '', trim($row[$colMap['phone']] ?? ''))
                : null;

            if ($phone && strlen($phone) > 10) {
                $phone = substr($phone, -10);
            }

            if ($phone && $tournament->players()->where('phone', $phone)->exists()) {
                $skipped++;
                $errors[] = "Row " . ($lineNo + 2) . ": {$name} (phone {$phone}) already registered.";
                continue;
            }

            // Parse role
            $roleRaw = strtolower(trim($row[$colMap['role']] ?? ''));
            $role    = $this->parseRole($roleRaw);

            // Parse batting style
            $battingRaw = strtolower(trim($row[$colMap['batting_style']] ?? ''));
            $batting    = $this->parseBatting($battingRaw);

            // Parse bowling style
            $bowlingRaw = strtolower(trim($row[$colMap['bowling_style']] ?? ''));
            $bowling    = $this->parseBowling($bowlingRaw);

            // Parse base price
            $basePriceRaw = trim($row[$colMap['base_price']] ?? '0');
            $basePrice    = $this->parsePrice($basePriceRaw);

            // Parse age
            $age = isset($colMap['age'])
                ? intval(trim($row[$colMap['age']] ?? 0))
                : null;
            if ($age < 10 || $age > 60) $age = null;

            Player::create([
                'tournament_id' => $tournament->id,
                'name'          => $name,
                'phone'         => $phone ?: null,
                'email'         => isset($colMap['email'])
                    ? trim($row[$colMap['email']] ?? '') ?: null
                    : null,
                'age'           => $age,
                'city'          => isset($colMap['city'])
                    ? trim($row[$colMap['city']] ?? '') ?: null
                    : null,
                'role'          => $role,
                'batting_style' => $batting,
                'bowling_style' => $bowling,
                'base_price'    => $basePrice,
                'status'        => $basePrice > 0 ? 'available' : 'registered',
            ]);

            $imported++;
        }

        $msg = "{$imported} players imported successfully!";
        if ($skipped > 0) $msg .= " {$skipped} rows skipped.";

        return redirect()
            ->route('players.index', $tournament->id)
            ->with('success', $msg)
            ->with('import_errors', $errors);
    }

    // Convert various Google Sheets URL formats to CSV export URL
    private function convertToCsvUrl(string $url): ?string
    {
        // Format 1: /spreadsheets/d/{ID}/edit
        // Format 2: /spreadsheets/d/{ID}/pub
        // Format 3: Already CSV export URL

        if (preg_match('/\/spreadsheets\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $sheetId = $matches[1];

            // Check for gid (sheet tab)
            $gid = '0';
            if (preg_match('/gid=(\d+)/', $url, $gidMatch)) {
                $gid = $gidMatch[1];
            }

            return "https://docs.google.com/spreadsheets/d/{$sheetId}/export?format=csv&gid={$gid}";
        }

        return null;
    }

    // Parse CSV string into array
    private function parseCsv(string $data): array
    {
        $rows   = [];
        $lines  = explode("\n", trim($data));

        foreach ($lines as $line) {
            $rows[] = str_getcsv($line);
        }

        return $rows;
    }

    // Map column names to indexes
    private function mapColumns(array $headers): array
    {
        $map = [];
        $aliases = [
            'name'         => ['name', 'player name', 'player', 'full name', 'playername'],
            'phone'        => ['phone', 'mobile', 'contact', 'phone number', 'mobile number', 'number'],
            'email'        => ['email', 'email address', 'mail'],
            'age'          => ['age', 'years'],
            'city'         => ['city', 'location', 'town', 'place'],
            'role'         => ['role', 'playing role', 'position', 'type'],
            'batting_style' => ['batting', 'batting style', 'bat', 'batting hand'],
            'bowling_style' => ['bowling', 'bowling style', 'bowl', 'bowling type'],
            'base_price'   => ['base price', 'base', 'price', 'base_price', 'starting price', 'minimum price'],
        ];

        foreach ($aliases as $field => $aliasList) {
            foreach ($headers as $idx => $header) {
                if (in_array(trim($header), $aliasList)) {
                    $map[$field] = $idx;
                    break;
                }
            }
        }

        return $map;
    }

    private function parseRole(string $raw): string
    {
        if (str_contains($raw, 'keeper') || str_contains($raw, 'wk'))
            return 'wicket_keeper';
        if (str_contains($raw, 'all'))
            return 'all_rounder';
        if (str_contains($raw, 'bowl'))
            return 'bowler';
        return 'batsman';
    }

    private function parseBatting(string $raw): string
    {
        if (str_contains($raw, 'left')) return 'left_hand';
        return 'right_hand';
    }

    private function parseBowling(string $raw): string
    {
        if (str_contains($raw, 'left') && str_contains($raw, 'spin')) return 'left_arm_spin';
        if (str_contains($raw, 'left')) return 'left_arm_fast';
        if (str_contains($raw, 'spin')) return 'right_arm_spin';
        if (str_contains($raw, 'none') || str_contains($raw, 'no') || empty($raw)) return 'none';
        return 'right_arm_fast';
    }

    private function parsePrice(string $raw): float
    {
        // Remove ₹, commas, spaces
        $clean = preg_replace('/[₹,\s]/u', '', $raw);

        // Handle L (lakh) and K (thousand)
        if (preg_match('/^([\d.]+)\s*[Ll]$/i', $clean, $m)) {
            return (float)$m[1] * 100000;
        }
        if (preg_match('/^([\d.]+)\s*[Kk]$/i', $clean, $m)) {
            return (float)$m[1] * 1000;
        }

        return (float)$clean ?: 0;
    }

    private function authorizeOwner(Tournament $tournament)
    {
        if ($tournament->user_id !== auth()->id()) {
            abort(403, 'Unauthorized.');
        }
    }
    public function sampleCsv(Tournament $tournament)
    {
        $this->authorizeOwner($tournament);

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sample_players.csv"',
        ];

        $rows = [
            ['Name', 'Phone', 'Age', 'City', 'Role', 'Batting Style', 'Bowling Style', 'Base Price', 'Email'],
            ['Rahul Kumar', '9876543210', '25', 'Mumbai', 'Batsman', 'Right Hand', 'None', '50000', 'rahul@example.com'],
            ['Arjun Singh', '9876543211', '28', 'Delhi', 'Bowler', 'Right Hand', 'Right Arm Fast', '75000', ''],
            ['Priya Patel', '9876543212', '22', 'Ahmedabad', 'All Rounder', 'Left Hand', 'Left Arm Spin', '60000', ''],
            ['Suresh Yadav', '9876543213', '30', 'Chennai', 'Wicket Keeper', 'Right Hand', 'None', '80000', ''],
        ];

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
