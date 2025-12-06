@extends('layout.main')

@section('title', 'Event Details - EventOps')

@section('content')
@php
    $user = auth()->user();
@endphp

<div style="max-width: 1000px; margin: 0 auto;">
    <!-- Page Header -->
    <div style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
            <h1 style="margin: 0;">{{ $event->title }}</h1>
            @if($user->role !== 'student')
                <x-button variant="secondary" size="sm" href="{{ route('events.edit', $event) }}">
                    <i class='bx bx-edit'></i>
                    <span>Edit Event</span>
                </x-button>
            @endif
        </div>
        <p class="text-muted" style="font-size: 14px;">
            {{ $event->club ? $event->club->name : 'No Club' }}
        </p>
    </div>

    <!-- Event Information Card -->
    <x-card title="Event Information" style="margin-bottom: 16px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px;">
            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Date & Time</div>
                <div style="font-size: 16px; font-weight: 600; color: var(--color-text-heading);">{{ $event->date->format('M d, Y') }}</div>
                <div style="font-size: 14px; color: var(--color-text-muted); margin-top: 2px;">{{ \Carbon\Carbon::createFromFormat('H:i:s', $event->time_start)->format('h:i A') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $event->time_end)->format('h:i A') }}</div>
            </div>
            
            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Venue</div>
                <div style="font-size: 16px; font-weight: 600; color: var(--color-text-heading);">{{ $event->venue }}</div>
            </div>
            
            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Capacity</div>
                <div style="font-size: 16px; font-weight: 600; color: var(--color-text-heading);">{{ $event->capacity }} attendees</div>
                @php
                    $registrationCount = $event->registrations->count();
                    $percentageFull = $event->capacity > 0 ? ($registrationCount / $event->capacity) * 100 : 0;
                @endphp
                <div style="margin-top: 8px;">
                    <div style="width: 100%; height: 6px; background-color: var(--color-page-bg); overflow: hidden;">
                        <div style="height: 100%; background-color: {{ $percentageFull >= 90 ? 'var(--color-danger-600)' : ($percentageFull >= 70 ? 'var(--color-warning-600)' : 'var(--color-success-600)') }}; width: {{ min($percentageFull, 100) }}%; transition: width 0.3s ease;"></div>
                    </div>
                    <div style="font-size: 12px; color: var(--color-text-muted); margin-top: 4px;">
                        {{ $registrationCount }} registered ({{ round($percentageFull) }}%)
                    </div>
                </div>
            </div>
            
            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Created</div>
                <div style="font-size: 16px; font-weight: 600; color: var(--color-text-heading);">{{ $event->created_at->format('M d, Y') }}</div>
                <div style="font-size: 14px; color: var(--color-text-muted); margin-top: 2px;">{{ $event->created_at->format('h:i A') }}</div>
            </div>
        </div>
        
        <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--color-border-subtle);">
            <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em;">Description</div>
            <p style="font-size: 14px; line-height: 1.6; color: var(--color-text-body); margin: 0;">
                {{ $event->description }}
            </p>
        </div>
    </x-card>

    <!-- Registrations Card -->
    <x-card>
        <x-slot:title>
            Registrations
            <span style="margin-left: 8px; font-size: 14px; font-weight: 500; color: var(--color-text-muted);">
                ({{ $event->registrations->count() }})
            </span>
        </x-slot:title>
        
        @if($event->registrations->count() > 0)
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Email</th>
                            <th>Ticket Code</th>
                            <th>Status</th>
                            <th>Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($event->registrations as $registration)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div class="user-avatar" style="width: 28px; height: 28px; font-size: 12px;">
                                            {{ strtoupper(substr($registration->user->name, 0, 1)) }}
                                        </div>
                                        <div style="font-weight: 500; color: var(--color-text-heading);">
                                            {{ $registration->user->name }}
                                        </div>
                                    </div>
                                </td>
                                <td style="color: var(--color-text-muted);">
                                    {{ $registration->user->email }}
                                </td>
                                <td>
                                    <code style="padding: 2px 6px; background-color: var(--color-page-bg); color: var(--color-text-body); font-size: 12px; font-family: monospace;">
                                        {{ $registration->ticket_code }}
                                    </code>
                                </td>
                                <td>
                                    @if($registration->status === 'attended')
                                        <x-badge variant="success">
                                            <i class='bx bx-check'></i>
                                            <span>Attended</span>
                                        </x-badge>
                                    @else
                                        <x-badge variant="info">Registered</x-badge>
                                    @endif
                                </td>
                                <td style="font-size: 14px; color: var(--color-text-muted);">
                                    {{ $registration->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 48px 16px;">
                <i class='bx bx-user-x' style="font-size: 64px; color: var(--color-text-muted); margin-bottom: 16px;"></i>
                <h3 style="font-size: 18px; font-weight: 600; color: var(--color-text-heading); margin-bottom: 8px;">
                    No registrations yet
                </h3>
                <p style="font-size: 14px; color: var(--color-text-muted);">
                    Students haven't registered for this event yet
                </p>
            </div>
        @endif
    </x-card>

    <!-- Actions -->
    <div style="display: flex; gap: 12px; margin-top: 16px;">
        <x-button variant="secondary" href="{{ route('events.index') }}">
            <i class='bx bx-arrow-back'></i>
            <span>Back to Events</span>
        </x-button>
        
        @if($user->role !== 'student' && $event->status === 'active')
            <form method="POST" action="{{ route('events.destroy', $event) }}" style="margin-left: auto;">
                @csrf
                @method('DELETE')
                <x-button
                    variant="danger"
                    type="submit"
                    onclick="return confirm('Are you sure you want to cancel this event? This will cancel all registrations. This action cannot be undone.')"
                >
                    <i class='bx bx-x'></i>
                    <span>Cancel Event</span>
                </x-button>
            </form>
        @endif
    </div>
</div>
@endsection