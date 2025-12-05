@extends('layout.main')

@section('title', 'Club Details - EventOps')

@section('content')
@php
    $user = auth()->user();
@endphp

<div style="max-width: 1000px; margin: 0 auto;">
    <!-- Page Header -->
    <div style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
            <h1 style="margin: 0;">{{ $club->name }}</h1>
            <x-button variant="secondary" size="sm" href="{{ route('clubs.edit', $club) }}">
                <i class='bx bx-edit'></i>
                <span>Edit Club</span>
            </x-button>
        </div>
        <p class="text-muted" style="font-size: 14px;">
            Club Information
        </p>
    </div>

    <!-- Club Information Card -->
    <x-card title="Club Information" style="margin-bottom: 16px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px;">
            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Club ID</div>
                <div style="font-size: 16px; font-weight: 600; color: var(--color-text-heading);">#{{ $club->id }}</div>
            </div>

            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Name</div>
                <div style="font-size: 16px; font-weight: 600; color: var(--color-text-heading);">{{ $club->name }}</div>
            </div>

            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Members</div>
                <div style="font-size: 16px; font-weight: 600; color: var(--color-text-heading);">{{ $club->users->count() }} members</div>
            </div>

            <div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Created</div>
                <div style="font-size: 16px; font-weight: 600; color: var(--color-text-heading);">{{ $club->created_at->format('M d, Y') }}</div>
                <div style="font-size: 14px; color: var(--color-text-muted); margin-top: 2px;">{{ $club->created_at->format('h:i A') }}</div>
            </div>
        </div>

        <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--color-border-subtle);">
            <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em;">Description</div>
            <p style="font-size: 14px; line-height: 1.6; color: var(--color-text-body); margin: 0;">
                {{ $club->description ?: 'No description provided.' }}
            </p>
        </div>
    </x-card>

    <!-- Events Card -->
    <x-card>
        <x-slot:title>
            Events
            <span style="margin-left: 8px; font-size: 14px; font-weight: 500; color: var(--color-text-muted);">
                ({{ $club->events->count() }})
            </span>
        </x-slot:title>

        @if($club->events->count() > 0)
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Date</th>
                            <th>Venue</th>
                            <th>Registrations</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($club->events as $event)
                            @php
                                $registrationCount = $event->registrations->count();
                                $percentageFull = $event->capacity > 0 ? ($registrationCount / $event->capacity) * 100 : 0;
                                $isUpcoming = $event->date >= now()->toDateString();
                            @endphp
                            <tr>
                                <td>
                                    <div style="font-weight: 500; color: var(--color-text-heading);">
                                        {{ $event->title }}
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size: 14px;">
                                        {{ $event->date->format('M d, Y') }}
                                    </div>
                                    <div style="font-size: 12px; color: var(--color-text-muted);">
                                        {{ $event->time_start }} - {{ $event->time_end }}
                                    </div>
                                </td>
                                <td style="color: var(--color-text-muted);">
                                    {{ $event->venue }}
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <span style="font-weight: 500;">{{ $registrationCount }}/{{ $event->capacity }}</span>
                                        @if($percentageFull >= 90)
                                            <x-badge variant="danger">{{ round($percentageFull) }}%</x-badge>
                                        @elseif($percentageFull >= 70)
                                            <x-badge variant="warning">{{ round($percentageFull) }}%</x-badge>
                                        @else
                                            <x-badge variant="success">{{ round($percentageFull) }}%</x-badge>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($isUpcoming)
                                        <x-badge variant="info">Upcoming</x-badge>
                                    @else
                                        <x-badge variant="success">Completed</x-badge>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 48px 16px;">
                <i class='bx bx-calendar' style="font-size: 64px; color: var(--color-text-muted); margin-bottom: 16px;"></i>
                <h3 style="font-size: 18px; font-weight: 600; color: var(--color-text-heading); margin-bottom: 8px;">
                    No events yet
                </h3>
                <p style="font-size: 14px; color: var(--color-text-muted);">
                    This club hasn't organized any events yet
                </p>
            </div>
        @endif
    </x-card>

    <!-- Actions -->
    <div style="display: flex; gap: 12px; margin-top: 16px;">
        <x-button variant="secondary" href="{{ route('clubs.index') }}">
            <i class='bx bx-arrow-back'></i>
            <span>Back to Clubs</span>
        </x-button>

        <form method="POST" action="{{ route('clubs.destroy', $club) }}" style="margin-left: auto;">
            @csrf
            @method('DELETE')
            <x-button
                variant="danger"
                type="submit"
                onclick="return confirm('Are you sure you want to delete this club? This will also delete all associated events and registrations. This action cannot be undone.')"
            >
                <i class='bx bx-trash'></i>
                <span>Delete Club</span>
            </x-button>
        </form>
    </div>
</div>
@endsection