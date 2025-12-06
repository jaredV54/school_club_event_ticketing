@extends('layout.main')

@section('title', 'Registration Details - EventOps')

@section('content')
@php
    $user = auth()->user();
    $isStudent = $user->role === 'student';
@endphp

<div style="max-width: 800px; margin: 0 auto;">
    <!-- Page Header -->
    <div style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
            <h1 style="margin: 0;">{{ $isStudent ? 'My Ticket' : 'Registration Details' }}</h1>
            @if($user->role === 'admin')
                <x-button variant="secondary" size="sm" href="{{ route('registrations.edit', $registration) }}">
                    <i class='bx bx-edit'></i>
                    <span>Edit</span>
                </x-button>
            @endif
        </div>
        <p class="text-muted" style="font-size: 14px;">
            Registration #{{ $registration->id }}
        </p>
    </div>

    <!-- Event Information Card -->
    <x-card title="Event Information" style="margin-bottom: 16px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px;">
            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Event</div>
                <div style="font-size: 16px; font-weight: 600; color: var(--color-text-heading);">{{ $registration->event->title }}</div>
                <div style="font-size: 14px; color: var(--color-text-muted); margin-top: 2px;">{{ $registration->event->club->name }}</div>
            </div>
            
            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Date & Time</div>
                <div style="font-size: 16px; font-weight: 600; color: var(--color-text-heading);">{{ $registration->event->date->format('M d, Y') }}</div>
                <div style="font-size: 14px; color: var(--color-text-muted); margin-top: 2px;">{{ \Carbon\Carbon::createFromFormat('H:i:s', $registration->event->time_start)->format('h:i A') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $registration->event->time_end)->format('h:i A') }}</div>
            </div>
            
            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Venue</div>
                <div style="font-size: 16px; font-weight: 600; color: var(--color-text-heading);">{{ $registration->event->venue }}</div>
            </div>
        </div>
    </x-card>

    <!-- Ticket Information Card -->
    <x-card title="Ticket Information" style="margin-bottom: 16px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px;">
            @if(!$isStudent)
                <div>
                    <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Student</div>
                    <div style="display: flex; align-items: center; gap: 8px; margin-top: 8px;">
                        <div class="user-avatar" style="width: 32px; height: 32px; font-size: 14px;">
                            {{ strtoupper(substr($registration->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-size: 14px; font-weight: 500; color: var(--color-text-heading);">{{ $registration->user->name }}</div>
                            <div style="font-size: 12px; color: var(--color-text-muted);">{{ $registration->user->email }}</div>
                        </div>
                    </div>
                </div>
            @endif
            
            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Ticket Code</div>
                <div style="margin-top: 8px;">
                    <code style="padding: 8px 12px; background-color: var(--color-page-bg); color: var(--color-text-heading); font-size: 18px; font-weight: 600; font-family: monospace; display: inline-block; border: 1px solid var(--color-border-subtle);">
                        {{ $registration->ticket_code }}
                    </code>
                </div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-top: 4px;">
                    Present this code at the event
                </div>
            </div>
            
            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Status</div>
                <div style="margin-top: 8px;">
                    @if($registration->status === 'attended')
                        <x-badge variant="success" style="font-size: 14px; padding: 4px 12px;">
                            <i class='bx bx-check-circle'></i>
                            <span>Attended</span>
                        </x-badge>
                    @else
                        <x-badge variant="info" style="font-size: 14px; padding: 4px 12px;">
                            <i class='bx bx-time'></i>
                            <span>Registered</span>
                        </x-badge>
                    @endif
                </div>
            </div>
            
            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Registered On</div>
                <div style="font-size: 16px; font-weight: 600; color: var(--color-text-heading); margin-top: 8px;">
                    {{ $registration->created_at->format('M d, Y') }}
                </div>
                <div style="font-size: 14px; color: var(--color-text-muted); margin-top: 2px;">
                    {{ $registration->created_at->format('h:i A') }}
                </div>
            </div>
        </div>
    </x-card>

    <!-- Attendance History Card -->
    <x-card title="Attendance History" style="margin-bottom: 16px;">
        @if($registration->attendanceLogs->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($registration->attendanceLogs as $log)
                    <div style="display: flex; align-items: center; gap: 12px; padding: 12px; background-color: var(--color-page-bg); border: 1px solid var(--color-border-subtle);">
                        <div style="width: 40px; height: 40px; background-color: var(--color-success-bg); color: var(--color-success-text); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class='bx bx-check' style="font-size: 24px;"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-size: 14px; font-weight: 500; color: var(--color-text-heading);">
                                Checked in successfully
                            </div>
                            <div style="font-size: 12px; color: var(--color-text-muted); margin-top: 2px;">
                                {{ $log->timestamp->format('M d, Y \a\t h:i A') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 32px 16px;">
                <i class='bx bx-calendar-x' style="font-size: 48px; color: var(--color-text-muted); margin-bottom: 12px;"></i>
                <div style="font-size: 14px; color: var(--color-text-muted);">
                    No attendance records yet
                </div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-top: 4px;">
                    Check in at the event to record attendance
                </div>
            </div>
        @endif
    </x-card>

    <!-- Actions -->
    <div style="display: flex; gap: 12px;">
        <x-button variant="secondary" href="{{ route('registrations.index') }}">
            <i class='bx bx-arrow-back'></i>
            <span>Back to {{ $isStudent ? 'My Tickets' : 'Registrations' }}</span>
        </x-button>
        
        @if($user->role === 'admin')
            <form method="POST" action="{{ route('registrations.destroy', $registration) }}" style="margin-left: auto;">
                @csrf
                @method('DELETE')
                <x-button 
                    variant="danger" 
                    type="submit"
                    onclick="return confirm('Are you sure you want to delete this registration? This action cannot be undone.')"
                >
                    <i class='bx bx-trash'></i>
                    <span>Delete Registration</span>
                </x-button>
            </form>
        @endif
    </div>
</div>
@endsection