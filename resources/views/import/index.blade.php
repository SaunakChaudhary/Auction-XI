@extends('layouts.app')

@section('title', 'Import Players — ' . $tournament->name)

@section('content')

<div style="background:#f9fafb; min-height:calc(100vh - 62px); padding:40px 0;">
<div class="container">
<div class="row justify-content-center">
<div class="col-lg-8">

    {{-- Back --}}
    <a href="{{ route('tournaments.show', $tournament->id) }}"
       style="display:inline-flex; align-items:center; gap:6px; color:#6b7280;
              font-size:14px; font-weight:500; text-decoration:none; margin-bottom:24px;">
        <i class="bi bi-arrow-left"></i> Back to {{ $tournament->name }}
    </a>

    {{-- Header --}}
    <div style="margin-bottom:28px;">
        <h1 style="font-size:1.6rem; font-weight:800; color:#111827; margin:0;">
            📊 Import from Google Sheets
        </h1>
        <p style="color:#6b7280; margin:6px 0 0; font-size:0.9rem;">
            Import all players at once from a Google Sheets spreadsheet
        </p>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a;
                    border-radius:12px; padding:14px 18px; font-size:14px; margin-bottom:20px;
                    display:flex; align-items:center; gap:8px;">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626;
                    border-radius:12px; padding:14px 18px; font-size:14px; margin-bottom:20px;">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ $errors->first() }}
        </div>
    @endif

    {{-- How to Prepare Sheet --}}
    <div style="background:#fffbeb; border:1px solid #fde68a; border-radius:14px;
                padding:20px; margin-bottom:24px;">
        <div style="font-weight:700; font-size:14px; color:#92400e; margin-bottom:12px;">
            📋 How to prepare your Google Sheet
        </div>
        <div style="font-size:13px; color:#78350f; line-height:1.8;">
            <strong>Step 1:</strong> First row must be column headers<br>
            <strong>Step 2:</strong> Go to File → Share → Anyone with the link (Viewer)<br>
            <strong>Step 3:</strong> Copy the link and paste it below
        </div>

        {{-- Column Reference --}}
        <div style="margin-top:14px; background:#fff; border-radius:10px;
                    border:1px solid #fde68a; overflow:hidden;">
            <div style="padding:10px 14px; background:#fef9c3;
                        font-size:12px; font-weight:700; color:#92400e;">
                Supported Column Names (case-insensitive)
            </div>
            <div style="padding:14px; display:grid;
                        grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:8px;">
                @foreach([
                    ['Name',          'name / player name / full name',     true],
                    ['Phone',         'phone / mobile / contact',           false],
                    ['Age',           'age / years',                        false],
                    ['City',          'city / location / town',             false],
                    ['Role',          'role / playing role / position',     false],
                    ['Batting Style', 'batting / batting style',            false],
                    ['Bowling Style', 'bowling / bowling style',            false],
                    ['Base Price',    'base price / price / base',          false],
                    ['Email',         'email / email address',              false],
                ] as [$col, $aliases, $required])
                    <div style="display:flex; align-items:flex-start; gap:8px;
                                padding:8px; background:#fafaf5; border-radius:8px;
                                border:1px solid #fde68a;">
                        <div style="flex:1;">
                            <div style="font-size:12px; font-weight:700; color:#111827;">
                                {{ $col }}
                                @if($required)
                                    <span style="color:#dc2626; font-size:10px;"> *required</span>
                                @endif
                            </div>
                            <div style="font-size:11px; color:#6b7280; margin-top:2px;">
                                {{ $aliases }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Sample Sheet Download --}}
    <div style="background:#fff; border:1px solid #e5e7eb; border-radius:14px;
                padding:20px; margin-bottom:24px;">
        <div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
            <div style="font-size:2rem;">📥</div>
            <div style="flex:1;">
                <div style="font-weight:700; font-size:14px; color:#111827; margin-bottom:2px;">
                    Download Sample Sheet
                </div>
                <div style="font-size:13px; color:#6b7280;">
                    Use this as a template to fill in your player data
                </div>
            </div>
            <a href="{{ route('import.sample', $tournament->id) }}"
               style="padding:8px 20px; background:#f0fdf4; color:#16a34a;
                      border:1px solid #bbf7d0; border-radius:10px;
                      font-size:13px; font-weight:600; text-decoration:none;">
                <i class="bi bi-download me-1"></i> Download CSV
            </a>
        </div>
    </div>

    {{-- Import Form --}}
    <div style="background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:28px;">
        <div style="font-weight:700; font-size:15px; color:#111827;
                    margin-bottom:20px; padding-bottom:16px; border-bottom:1px solid #f3f4f6;
                    display:flex; align-items:center; gap:8px;">
            <i class="bi bi-file-earmark-spreadsheet" style="color:#16a34a;"></i>
            Paste Google Sheets Link
        </div>

        <form method="POST" action="{{ route('import.store', $tournament->id) }}"
              id="importForm">
            @csrf

            <div class="mb-4">
                <label style="display:block; font-weight:600; font-size:14px;
                               color:#374151; margin-bottom:8px;">
                    Google Sheets URL <span class="text-danger">*</span>
                </label>
                <input type="url"
                       name="sheet_url"
                       id="sheetUrl"
                       style="width:100%; border:1.5px solid #e5e7eb; border-radius:10px;
                              padding:12px 14px; font-size:14px; color:#111827;
                              outline:none; font-family:'Inter',sans-serif;
                              transition:border-color 0.2s;"
                       placeholder="https://docs.google.com/spreadsheets/d/..."
                       value="{{ old('sheet_url') }}"
                       oninput="validateUrl(this)">

                {{-- URL Validation --}}
                <div id="urlFeedback" style="margin-top:8px; font-size:13px;"></div>

                <div style="font-size:12px; color:#9ca3af; margin-top:6px;">
                    Make sure the sheet is shared as "Anyone with the link can view"
                </div>
            </div>

            {{-- Options --}}
            <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:12px;
                        padding:16px; margin-bottom:24px;">
                <div style="font-size:13px; font-weight:600; color:#374151; margin-bottom:12px;">
                    Import Options
                </div>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                        <input type="checkbox" name="skip_duplicates" value="1" checked
                               style="width:16px; height:16px; cursor:pointer;">
                        <div>
                            <div style="font-size:13px; font-weight:600; color:#374151;">
                                Skip duplicate phone numbers
                            </div>
                            <div style="font-size:12px; color:#6b7280;">
                                Players already registered with same phone will be skipped
                            </div>
                        </div>
                    </label>
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                        <input type="checkbox" name="auto_available" value="1" checked
                               style="width:16px; height:16px; cursor:pointer;">
                        <div>
                            <div style="font-size:13px; font-weight:600; color:#374151;">
                                Auto-mark as Available if base price is set
                            </div>
                            <div style="font-size:12px; color:#6b7280;">
                                Players with base price will be ready for auction immediately
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <button type="submit" id="importBtn"
                    class="btn btn-primary w-100 py-2"
                    style="border-radius:10px; font-weight:700; font-size:1rem;">
                <i class="bi bi-cloud-download me-2"></i>
                Import Players from Sheet
            </button>
        </form>
    </div>

    {{-- How it works steps --}}
    <div style="margin-top:24px; display:grid;
                grid-template-columns:repeat(3,1fr); gap:14px;">
        @foreach([
            ['🔗', 'Paste Link',    'Copy the shareable Google Sheets link'],
            ['⚙️', 'Auto-parse',   'We detect columns automatically'],
            ['✅', 'Import Done',   'Players are added ready for auction'],
        ] as [$icon, $title, $desc])
            <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px;
                        padding:16px; text-align:center;">
                <div style="font-size:1.8rem; margin-bottom:8px;">{{ $icon }}</div>
                <div style="font-weight:700; font-size:13px; color:#111827; margin-bottom:4px;">
                    {{ $title }}
                </div>
                <div style="font-size:12px; color:#6b7280; line-height:1.5;">{{ $desc }}</div>
            </div>
        @endforeach
    </div>

</div>
</div>
</div>
</div>

@endsection

@push('scripts')
<script>
function validateUrl(input) {
    const val      = input.value.trim();
    const feedback = document.getElementById('urlFeedback');
    const btn      = document.getElementById('importBtn');

    if (!val) {
        feedback.textContent = '';
        input.style.borderColor = '#e5e7eb';
        return;
    }

    const isSheets = val.includes('docs.google.com/spreadsheets');

    if (isSheets) {
        feedback.innerHTML = '<span style="color:#16a34a;">✅ Valid Google Sheets URL detected</span>';
        input.style.borderColor = '#16a34a';
        btn.disabled = false;
    } else if (val.startsWith('http')) {
        feedback.innerHTML = '<span style="color:#dc2626;">⚠️ This does not look like a Google Sheets URL</span>';
        input.style.borderColor = '#ef4444';
    } else {
        feedback.textContent = '';
        input.style.borderColor = '#e5e7eb';
    }
}

document.getElementById('importForm').addEventListener('submit', function() {
    const btn = document.getElementById('importBtn');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Importing...';
    btn.disabled = true;
});
</script>
@endpush