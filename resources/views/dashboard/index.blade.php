@extends('layout.main')

@section('title', 'Dashboard')

@section('content')
<h1>Dashboard</h1>

@php $user = auth()->user(); @endphp

<!-- Statistics Cards for Students -->
@if($user->role === 'student')
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card text-center">
            <div class="card-body">
                <div class="fs-2 mb-2">{{ $stats['my_registrations'] }}</div>
                <div class="text-muted small">My Registrations</div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-center">
            <div class="card-body">
                <div class="fs-2 mb-2">{{ $stats['my_attendance'] }}</div>
                <div class="text-muted small">My Attendance</div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Summary Information -->
@if($user->role === 'admin' || $user->role === 'officer')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Summary</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    @if($user->role === 'admin')
                        <div class="col-md-3">
                            <div class="border-end">
                                <h3 class="text-primary">{{ $stats['total_users'] }}</h3>
                                <p class="text-muted mb-0">Total Users</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h3 class="text-success">{{ $stats['total_clubs'] }}</h3>
                                <p class="text-muted mb-0">Active Clubs</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h3 class="text-info">{{ $stats['total_events'] }}</h3>
                                <p class="text-muted mb-0">Total Events</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h3 class="text-warning">{{ $stats['total_registrations'] }}</h3>
                            <p class="text-muted mb-0">Total Registrations</p>
                        </div>
                    @elseif($user->role === 'officer')
                        <div class="col-md-4">
                            <div class="border-end">
                                <h3 class="text-primary">{{ $stats['club_events'] }}</h3>
                                <p class="text-muted mb-0">Club Events</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border-end">
                                <h3 class="text-success">{{ $stats['club_registrations'] }}</h3>
                                <p class="text-muted mb-0">Club Registrations</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h3 class="text-info">{{ $stats['club_attendance'] }}</h3>
                            <p class="text-muted mb-0">Attendance Records</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Recent Activity -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    @if($user->role === 'officer') Club Events @else Recent Events @endif
                </h5>
            </div>
            <div class="card-body">
                @forelse($recent_events as $event)
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="fw-bold">{{ $event->title }}</div>
                        <small class="text-muted">{{ $event->club->name }} • {{ $event->date->format('M d, Y') }}</small>
                    </div>
                @empty
                    <p class="text-muted mb-0">No events yet.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    @if($user->role === 'student') My Registrations @elseif($user->role === 'officer') Club Registrations @else Recent Registrations @endif
                </h5>
            </div>
            <div class="card-body">
                @forelse($recent_registrations as $registration)
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="fw-bold">{{ $registration->user->name }}</div>
                        <small class="text-muted">{{ $registration->event->title }} • {{ $registration->created_at->format('M d, Y') }}</small>
                    </div>
                @empty
                    <p class="text-muted mb-0">
                        @if($user->role === 'student') No registrations yet. @else No registrations yet. @endif
                    </p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
