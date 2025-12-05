@extends('layout.main')

@section('title', 'Attendance Log Details - EventOps')

@section('content')
@php
    $user = auth()->user();
@endphp

<div style="max-width: 800px; margin: 0 auto;">
    <!-- Page Header -->
    <div style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
            <h1 style="margin: 0;">Attendance Log Details</h1>
            @if($user->role !== 'student')
                <x-button variant="secondary" size="sm" href="{{ route('attendance.edit', $attendance) }}">
                    <i class='bx bx-edit'></i>
                    <span>Edit Log</span>
                </x-button>
            @endif
        </div>
        <p class="text-muted" style="font-size: 14px;">
            Log #{{ $attendance->id }}
        </p>
    </div>

    <!-- Attendance Information Card -->
    <x-card title="Attendance Information" style="margin-bottom: 16px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px;">
            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Event</div>
                <div style="font-size: 16px; font-weight: 600; color: var(--color-text-heading);">{{ $attendance->registration->event->title }}</div>
                <div style="font-size: 14px; color: var(--color-text-muted); margin-top: 2px;">{{ $attendance->registration->event->club->name }}</div>
            </div>

            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Student</div>
                <div style="display: flex; align-items: center; gap: 8px; margin-top: 8px;">
                    <div class="user-avatar" style="width: 32px; height: 32px; font-size: 14px;">
                        {{ strtoupper(substr($attendance->registration->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-size: 14px; font-weight: 500; color: var(--color-text-heading);">{{ $attendance->registration->user->name }}</div>
                        <div style="font-size: 12px; color: var(--color-text-muted);">{{ $attendance->registration->user->email }}</div>
                    </div>
                </div>
            </div>

            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Ticket Code</div>
                <div style="margin-top: 8px;">
                    <code style="padding: 4px 8px; background-color: var(--color-page-bg); color: var(--color-text-heading); font-size: 14px; font-weight: 600; font-family: monospace;">
                        {{ $attendance->registration->ticket_code }}
                    </code>
                </div>
            </div>

            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Check-in Time</div>
                <div style="font-size: 16px; font-weight: 600; color: var(--color-text-heading); margin-top: 8px;">
                    {{ $attendance->timestamp->format('M d, Y') }}
                </div>
                <div style="font-size: 14px; color: var(--color-text-muted); margin-top: 2px;">
                    {{ $attendance->timestamp->format('h:i:s A') }}
                </div>
            </div>
        </div>
    </x-card>

    <!-- Additional Information Card -->
    <x-card title="Additional Information">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px;">
            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Log ID</div>
                <div style="font-size: 16px; font-weight: 600; color: var(--color-text-heading);">#{{ $attendance->id }}</div>
            </div>

            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Created</div>
                <div style="font-size: 16px; font-weight: 600; color: var(--color-text-heading);">{{ $attendance->created_at->format('M d, Y') }}</div>
                <div style="font-size: 14px; color: var(--color-text-muted); margin-top: 2px;">{{ $attendance->created_at->format('h:i A') }}</div>
            </div>

            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Last Updated</div>
                <div style="font-size: 16px; font-weight: 600; color: var(--color-text-heading);">{{ $attendance->updated_at->format('M d, Y') }}</div>
                <div style="font-size: 14px; color: var(--color-text-muted); margin-top: 2px;">{{ $attendance->updated_at->format('h:i A') }}</div>
            </div>
        </div>
    </x-card>

    <!-- Actions -->
    <div style="display: flex; gap: 12px; margin-top: 16px;">
        <x-button variant="secondary" href="{{ route('attendance.index') }}">
            <i class='bx bx-arrow-back'></i>
            <span>Back to Attendance Logs</span>
        </x-button>

        @if($user->role !== 'student')
            <form method="POST" action="{{ route('attendance.destroy', $attendance) }}" style="margin-left: auto;">
                @csrf
                @method('DELETE')
                <x-button
                    variant="danger"
                    type="submit"
                    onclick="return confirm('Are you sure you want to delete this attendance log? This action cannot be undone.')"
                >
                    <i class='bx bx-trash'></i>
                    <span>Delete Log</span>
                </x-button>
            </form>
        @endif
    </div>
</div>
@endsection