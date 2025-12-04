@extends('layout.main')

@section('title', 'Dashboard - EventOps')

@section('content')
@php 
    $user = auth()->user();
@endphp

<!-- Page Header -->
<div style="margin-bottom: 24px; margin-top: -56px;">
    <h1 style="margin-bottom: 4px;">Dashboard</h1>
    <p class="text-muted" style="font-size: 14px;">Welcome back, {{ $user->name }}</p>
</div>

<!-- KPI Tiles for Admin -->
@if($user->role === 'admin')
    <div class="grid-dashboard" style="margin-bottom: 24px;">
        <x-kpi-tile 
            label="Total Events" 
            :value="$stats['total_events']"
            :delta="5.3"
            trend="up"
        />
        
        <x-kpi-tile 
            label="Total Registrations" 
            :value="$stats['total_registrations']"
            :delta="12.8"
            trend="up"
        />
        
        <x-kpi-tile 
            label="Active Clubs" 
            :value="$stats['total_clubs']"
            :delta="0"
            trend="neutral"
        />
        
        <x-kpi-tile 
            label="Total Users" 
            :value="$stats['total_users']"
            :delta="8.2"
            trend="up"
        />
    </div>
@endif

<!-- KPI Tiles for Officer -->
@if($user->role === 'officer')
    <div class="grid-dashboard" style="margin-bottom: 24px;">
        <x-kpi-tile 
            label="Club Events" 
            :value="$stats['club_events']"
            :delta="3.5"
            trend="up"
        />
        
        <x-kpi-tile 
            label="Club Registrations" 
            :value="$stats['club_registrations']"
            :delta="15.2"
            trend="up"
        />
        
        <x-kpi-tile 
            label="Attendance Records" 
            :value="$stats['club_attendance']"
            :delta="7.1"
            trend="up"
        />
    </div>
@endif

<!-- KPI Tiles for Student -->
@if($user->role === 'student')
    <div class="grid-dashboard" style="margin-bottom: 24px;">
        <x-kpi-tile 
            label="My Registrations" 
            :value="$stats['my_registrations']"
        />
        
        <x-kpi-tile 
            label="My Attendance" 
            :value="$stats['my_attendance']"
        />
    </div>
@endif

<!-- Content Cards -->
<div class="grid-dashboard">
    <!-- Recent Events Card -->
    <x-card title="{{ $user->role === 'officer' ? 'Club Events' : 'Recent Events' }}">
        <x-slot:actions>
            <a href="{{ route('events.index') }}" style="font-size: 14px; color: var(--color-primary-600); font-weight: 500;">
                View all
            </a>
        </x-slot:actions>
        
        @forelse($recent_events->take(5) as $event)
            <div style="padding: 12px 0; {{ !$loop->last ? 'border-bottom: 1px solid var(--color-border-subtle);' : '' }}">
                <div style="display: flex; justify-content: space-between; align-items: start; gap: 12px;">
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: 500; color: var(--color-text-heading); margin-bottom: 4px;">
                            {{ $event->title }}
                        </div>
                        <div style="font-size: 12px; color: var(--color-text-muted);">
                            {{ $event->club->name }} • {{ $event->date->format('M d, Y') }}
                        </div>
                    </div>
                    @if($user->role !== 'student')
                        <a href="{{ route('events.show', $event) }}" class="btn btn-secondary btn-sm">
                            Manage
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 32px 16px;">
                <i class='bx bx-calendar' style="font-size: 48px; color: var(--color-text-muted); margin-bottom: 12px;"></i>
                <div style="font-size: 14px; color: var(--color-text-muted); margin-bottom: 16px;">
                    No events yet
                </div>
                @if($user->role !== 'student')
                    <x-button variant="primary" size="sm" href="{{ route('events.create') }}">
                        <i class='bx bx-plus'></i>
                        <span>Create Event</span>
                    </x-button>
                @endif
            </div>
        @endforelse
    </x-card>
    
    <!-- Recent Registrations Card -->
    <x-card title="{{ $user->role === 'student' ? 'My Registrations' : ($user->role === 'officer' ? 'Club Registrations' : 'Recent Registrations') }}">
        <x-slot:actions>
            <a href="{{ route('registrations.index') }}" style="font-size: 14px; color: var(--color-primary-600); font-weight: 500;">
                View all
            </a>
        </x-slot:actions>
        
        @forelse($recent_registrations->take(5) as $registration)
            <div style="padding: 12px 0; {{ !$loop->last ? 'border-bottom: 1px solid var(--color-border-subtle);' : '' }}">
                <div style="display: flex; align-items: start; gap: 12px;">
                    <!-- Avatar -->
                    <div class="user-avatar" style="flex-shrink: 0;">
                        {{ strtoupper(substr($registration->user->name, 0, 1)) }}
                    </div>
                    
                    <!-- Content -->
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: 500; color: var(--color-text-heading); margin-bottom: 2px;">
                            {{ $registration->user->name }}
                        </div>
                        <div style="font-size: 12px; color: var(--color-text-muted);">
                            {{ $registration->event->title }}
                        </div>
                    </div>
                    
                    <!-- Timestamp -->
                    <div style="font-size: 12px; color: var(--color-text-muted); flex-shrink: 0;">
                        {{ $registration->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 32px 16px;">
                <i class='bx bx-receipt' style="font-size: 48px; color: var(--color-text-muted); margin-bottom: 12px;"></i>
                <div style="font-size: 14px; color: var(--color-text-muted); margin-bottom: 16px;">
                    @if($user->role === 'student')
                        No registrations yet
                    @else
                        No registrations yet
                    @endif
                </div>
                @if($user->role === 'student')
                    <x-button variant="primary" size="sm" href="{{ route('registrations.create') }}">
                        <i class='bx bx-plus'></i>
                        <span>Register for Event</span>
                    </x-button>
                @endif
            </div>
        @endforelse
    </x-card>
    
    @if($user->role === 'admin')
        <!-- Quick Stats Card -->
        <x-card title="Quick Summary">
            <div style="display: grid; gap: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px;">Total Attendance</div>
                        <div style="font-size: 20px; font-weight: 600; color: var(--color-text-heading);">{{ $stats['total_attendance'] }}</div>
                    </div>
                    <i class='bx bx-check-circle' style="font-size: 32px; color: var(--color-success-600);"></i>
                </div>
                
                <div style="padding-top: 16px; border-top: 1px solid var(--color-border-subtle);">
                    <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 8px;">System Status</div>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <x-badge variant="success">All Systems Operational</x-badge>
                    </div>
                </div>
            </div>
        </x-card>
        
        <!-- Capacity Overview Card -->
        <x-card title="Capacity Overview">
            @php
                $topEvents = $recent_events->take(3);
            @endphp
            
            @forelse($topEvents as $event)
                @php
                    $soldSeats = $event->registrations->count();
                    $percentage = $event->capacity > 0 ? ($soldSeats / $event->capacity) * 100 : 0;
                @endphp
                
                <div style="margin-bottom: {{ !$loop->last ? '16px' : '0' }};">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <div style="font-size: 13px; font-weight: 500; color: var(--color-text-heading);">
                            {{ Str::limit($event->title, 30) }}
                        </div>
                        <div style="font-size: 12px; color: var(--color-text-muted);">
                            {{ $soldSeats }}/{{ $event->capacity }}
                        </div>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div style="width: 100%; height: 6px; background-color: var(--color-page-bg); overflow: hidden;">
                        <div style="height: 100%; background-color: {{ $percentage >= 90 ? 'var(--color-danger-600)' : ($percentage >= 70 ? 'var(--color-warning-600)' : 'var(--color-success-600)') }}; width: {{ min($percentage, 100) }}%; transition: width 0.3s ease;"></div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 16px; color: var(--color-text-muted); font-size: 14px;">
                    No events to display
                </div>
            @endforelse
        </x-card>
    @endif
</div>

@endsection