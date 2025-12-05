@extends('layout.main')

@section('title', 'User Details - EventOps')

@section('content')
@php
    $currentUser = auth()->user();
@endphp

<div style="max-width: 1000px; margin: 0 auto;">
    <!-- Page Header -->
    <div style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
            <h1 style="margin: 0;">{{ $user->name }}</h1>
            @if($currentUser->role === 'admin')
                <x-button variant="secondary" size="sm" href="{{ route('users.edit', $user) }}">
                    <i class='bx bx-edit'></i>
                    <span>Edit User</span>
                </x-button>
            @endif
        </div>
        <p class="text-muted" style="font-size: 14px;">
            User Profile
        </p>
    </div>

    <!-- User Information Card -->
    <x-card title="User Information" style="margin-bottom: 16px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px;">
            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">User ID</div>
                <div style="font-size: 16px; font-weight: 600; color: var(--color-text-heading);">#{{ $user->id }}</div>
            </div>

            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Full Name</div>
                <div style="font-size: 16px; font-weight: 600; color: var(--color-text-heading);">{{ $user->name }}</div>
            </div>

            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Email Address</div>
                <div style="font-size: 16px; font-weight: 600; color: var(--color-text-heading);">{{ $user->email }}</div>
            </div>

            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Role</div>
                <div style="margin-top: 8px;">
                    @if($user->role === 'admin')
                        <x-badge variant="danger" style="font-size: 14px; padding: 4px 12px;">
                            <i class='bx bx-shield'></i>
                            <span>Administrator</span>
                        </x-badge>
                    @elseif($user->role === 'officer')
                        <x-badge variant="warning" style="font-size: 14px; padding: 4px 12px;">
                            <i class='bx bx-user-check'></i>
                            <span>Club Officer</span>
                        </x-badge>
                    @else
                        <x-badge variant="info" style="font-size: 14px; padding: 4px 12px;">
                            <i class='bx bx-user'></i>
                            <span>Student</span>
                        </x-badge>
                    @endif
                </div>
            </div>

            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Club Affiliation</div>
                <div style="font-size: 16px; font-weight: 600; color: var(--color-text-heading);">
                    {{ $user->club ? $user->club->name : 'No Club' }}
                </div>
            </div>

            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Account Created</div>
                <div style="font-size: 16px; font-weight: 600; color: var(--color-text-heading);">{{ $user->created_at->format('M d, Y') }}</div>
                <div style="font-size: 14px; color: var(--color-text-muted); margin-top: 2px;">{{ $user->created_at->format('h:i A') }}</div>
            </div>
        </div>
    </x-card>

    <!-- Event Registrations Card -->
    <x-card title="Event Registrations" style="margin-bottom: 16px;">
        <x-slot:actions>
            <span style="font-size: 14px; font-weight: 500; color: var(--color-text-muted);">
                {{ $user->eventRegistrations->count() }} total
            </span>
        </x-slot:actions>

        @if($user->eventRegistrations->count() > 0)
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Date</th>
                            <th>Ticket Code</th>
                            <th>Status</th>
                            <th>Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->eventRegistrations as $registration)
                            <tr>
                                <td>
                                    <div style="font-weight: 500; color: var(--color-text-heading);">
                                        {{ $registration->event->title }}
                                    </div>
                                    <div style="font-size: 12px; color: var(--color-text-muted);">
                                        {{ $registration->event->club->name }}
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size: 14px;">
                                        {{ $registration->event->date->format('M d, Y') }}
                                    </div>
                                    <div style="font-size: 12px; color: var(--color-text-muted);">
                                        {{ $registration->event->time_start }} - {{ $registration->event->time_end }}
                                    </div>
                                </td>
                                <td>
                                    <code style="padding: 2px 6px; background-color: var(--color-page-bg); color: var(--color-text-body); font-size: 12px; font-family: monospace;">
                                        {{ $registration->ticket_code }}
                                    </code>
                                </td>
                                <td>
                                    @if($registration->status === 'attended')
                                        <x-badge variant="success">Attended</x-badge>
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
                <i class='bx bx-calendar-x' style="font-size: 64px; color: var(--color-text-muted); margin-bottom: 16px;"></i>
                <h3 style="font-size: 18px; font-weight: 600; color: var(--color-text-heading); margin-bottom: 8px;">
                    No event registrations yet
                </h3>
                <p style="font-size: 14px; color: var(--color-text-muted);">
                    This user hasn't registered for any events yet
                </p>
            </div>
        @endif
    </x-card>

    <!-- Actions -->
    <div style="display: flex; gap: 12px;">
        <x-button variant="secondary" href="{{ route('users.index') }}">
            <i class='bx bx-arrow-back'></i>
            <span>Back to Users</span>
        </x-button>

        @if($currentUser->role === 'admin')
            <form method="POST" action="{{ route('users.destroy', $user) }}" style="margin-left: auto;">
                @csrf
                @method('DELETE')
                <x-button
                    variant="danger"
                    type="submit"
                    onclick="return confirm('Are you sure you want to delete this user? This will also delete all their registrations and attendance records. This action cannot be undone.')"
                >
                    <i class='bx bx-trash'></i>
                    <span>Delete User</span>
                </x-button>
            </form>
        @endif
    </div>
</div>
@endsection