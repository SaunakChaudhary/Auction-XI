@extends('layouts.guest')
@section('title', 'Registration Closed')
@section('content')
<div style="text-align:center; padding:20px 0;">
    <div style="font-size:4rem; margin-bottom:16px;">🔒</div>
    <h2 style="font-weight:800; color:#111827; margin-bottom:8px;">Registration Closed</h2>
    <p style="color:#6b7280; font-size:14px; margin-bottom:4px;">
        Player registration for <strong>{{ $tournament->name }}</strong> is currently closed.
    </p>
    <p style="color:#9ca3af; font-size:13px;">
        Contact the tournament organizer for more information.
    </p>
</div>
@endsection