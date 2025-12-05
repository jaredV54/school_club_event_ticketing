@extends('layout.main')

@section('title', 'Events - EventOps')

@section('content')
@php
    $user = auth()->user();
@endphp

<!-- Page Header -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; margin-top: -56px;">
    <div>
        <h1 style="margin-bottom: 4px;">Events</h1>
        <p class="text-muted" style="font-size: 14px;">
            {{ $user->role === 'student' ? 'Browse and register for upcoming events' : 'Manage events and registrations' }}
        </p>
    </div>
    <div style="display: flex; gap: 12px; align-items: center;">
        <x-button variant="secondary" type="button" id="filter-toggle-btn">
            <i class='bx bx-filter-alt'></i>
            <span class="btn-text">Filters</span>
        </x-button>
        @if($user->role === 'student')
            <x-button variant="primary" href="{{ route('registrations.create') }}">
                <i class='bx bx-plus'></i>
                <span class="btn-text">Register for Event</span>
            </x-button>
        @endif
    </div>
</div>

<!-- Filters -->
<x-card style="margin-bottom: 24px; display: none;" id="filters-card">
    <form method="GET" action="{{ route('events.index') }}" style="display: flex; flex-direction: column; gap: 20px;">
        @if($user->role === 'student')
            <!-- Student Filters -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
                <div>
                    <label for="title" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                        Event Title
                    </label>
                    <input
                        type="text"
                        class="input"
                        id="title"
                        name="title"
                        value="{{ request('title') }}"
                        placeholder="Search by title"
                    >
                </div>

                <div>
                    <label for="club_id" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                        Club
                    </label>
                    <select
                        class="input"
                        id="club_id"
                        name="club_id"
                        style="width: 100%; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; padding-right: 40px;"
                    >
                        <option value="">All Clubs</option>
                        @foreach($clubs as $club)
                            <option value="{{ $club->id }}" {{ request('club_id') == $club->id ? 'selected' : '' }}>
                                {{ $club->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="venue" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                        Venue
                    </label>
                    <input
                        type="text"
                        class="input"
                        id="venue"
                        name="venue"
                        value="{{ request('venue') }}"
                        placeholder="Search by venue"
                    >
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <div>
                        <label for="date_from" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                            Date From
                        </label>
                        <input
                            type="date"
                            class="input"
                            id="date_from"
                            name="date_from"
                            value="{{ request('date_from') }}"
                        >
                    </div>
                    <div>
                        <label for="date_to" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                            Date To
                        </label>
                        <input
                            type="date"
                            class="input"
                            id="date_to"
                            name="date_to"
                            value="{{ request('date_to') }}"
                        >
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <div>
                        <label for="capacity_min" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                            Min Capacity
                        </label>
                        <input
                            type="number"
                            class="input"
                            id="capacity_min"
                            name="capacity_min"
                            value="{{ request('capacity_min') }}"
                            min="1"
                        >
                    </div>
                    <div>
                        <label for="capacity_max" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                            Max Capacity
                        </label>
                        <input
                            type="number"
                            class="input"
                            id="capacity_max"
                            name="capacity_max"
                            value="{{ request('capacity_max') }}"
                            min="1"
                        >
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <div>
                        <label for="registration_rate_min" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                            Min Reg. Rate (%)
                        </label>
                        <input
                            type="number"
                            class="input"
                            id="registration_rate_min"
                            name="registration_rate_min"
                            value="{{ request('registration_rate_min') }}"
                            min="0"
                            max="100"
                        >
                    </div>
                    <div>
                        <label for="registration_rate_max" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                            Max Reg. Rate (%)
                        </label>
                        <input
                            type="number"
                            class="input"
                            id="registration_rate_max"
                            name="registration_rate_max"
                            value="{{ request('registration_rate_max') }}"
                            min="0"
                            max="100"
                        >
                    </div>
                </div>
            </div>
        @else
            <!-- Admin/Officer Filters -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
                <div>
                    <label for="title" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                        Event Title
                    </label>
                    <input
                        type="text"
                        class="input"
                        id="title"
                        name="title"
                        value="{{ request('title') }}"
                        placeholder="Search by title"
                    >
                </div>

                <div>
                    <label for="club_id" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                        Club
                    </label>
                    <select
                        class="input"
                        id="club_id"
                        name="club_id"
                        style="width: 100%; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; padding-right: 40px;"
                    >
                        <option value="">All Clubs</option>
                        @foreach($clubs as $club)
                            <option value="{{ $club->id }}" {{ request('club_id') == $club->id ? 'selected' : '' }}>
                                {{ $club->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="venue" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                        Venue
                    </label>
                    <input
                        type="text"
                        class="input"
                        id="venue"
                        name="venue"
                        value="{{ request('venue') }}"
                        placeholder="Search by venue"
                    >
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <div>
                        <label for="date_from" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                            Date From
                        </label>
                        <input
                            type="date"
                            class="input"
                            id="date_from"
                            name="date_from"
                            value="{{ request('date_from') }}"
                        >
                    </div>
                    <div>
                        <label for="date_to" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                            Date To
                        </label>
                        <input
                            type="date"
                            class="input"
                            id="date_to"
                            name="date_to"
                            value="{{ request('date_to') }}"
                        >
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <div>
                        <label for="capacity_min" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                            Min Capacity
                        </label>
                        <input
                            type="number"
                            class="input"
                            id="capacity_min"
                            name="capacity_min"
                            value="{{ request('capacity_min') }}"
                            min="1"
                        >
                    </div>
                    <div>
                        <label for="capacity_max" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                            Max Capacity
                        </label>
                        <input
                            type="number"
                            class="input"
                            id="capacity_max"
                            name="capacity_max"
                            value="{{ request('capacity_max') }}"
                            min="1"
                        >
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <div>
                        <label for="registration_rate_min" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                            Min Reg. Rate (%)
                        </label>
                        <input
                            type="number"
                            class="input"
                            id="registration_rate_min"
                            name="registration_rate_min"
                            value="{{ request('registration_rate_min') }}"
                            min="0"
                            max="100"
                        >
                    </div>
                    <div>
                        <label for="registration_rate_max" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                            Max Reg. Rate (%)
                        </label>
                        <input
                            type="number"
                            class="input"
                            id="registration_rate_max"
                            name="registration_rate_max"
                            value="{{ request('registration_rate_max') }}"
                            min="0"
                            max="100"
                        >
                    </div>
                </div>
            </div>
        @endif

        <!-- Actions Row -->
        <div style="display: flex; gap: 12px; justify-content: flex-end; padding-top: 8px; border-top: 1px solid var(--color-border-subtle);">
            <x-button type="submit" variant="primary">
                <i class='bx bx-search'></i>
                <span>Filter</span>
            </x-button>
            <x-button type="button" variant="secondary" onclick="window.location.href='{{ route('events.index') }}'">
                <i class='bx bx-x'></i>
                <span>Clear</span>
            </x-button>
        </div>
    </form>
</x-card>

<!-- Events Table Card -->
<x-card>
    <x-slot:title>
        All Events
        <span style="margin-left: 8px; font-size: 14px; font-weight: 500; color: var(--color-text-muted);">
            ({{ $events->count() }})
        </span>
    </x-slot:title>
    
    @if($events->count() > 0)
        <!-- Desktop Table -->
        <div style="overflow-x: auto;" class="table-responsive-card">
            <table class="table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th class="table-col-mobile-hide">Club</th>
                        <th>Date & Time</th>
                        <th class="table-col-mobile-hide">Venue</th>
                        <th class="table-col-mobile-hide">Capacity</th>
                        <th>Registered</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                        @php
                            $registrationCount = $event->registrations->count();
                            $percentageFull = $event->capacity > 0 ? ($registrationCount / $event->capacity) * 100 : 0;
                        @endphp
                        <tr>
                            <td>
                                <div style="font-weight: 500; color: var(--color-text-heading);">
                                    {{ $event->title }}
                                </div>
                            </td>
                            <td class="table-col-mobile-hide">
                                <div style="font-size: 14px; color: var(--color-text-body);">
                                    {{ $event->club ? $event->club->name : 'No Club' }}
                                </div>
                            </td>
                            <td>
                                <div class="table-date-mobile">
                                    <div class="date-main">{{ $event->date->format('M d, Y') }}</div>
                                    <div class="date-time">{{ $event->time_start->format('H:i') }} - {{ $event->time_end->format('H:i') }}</div>
                                </div>
                            </td>
                            <td class="table-col-mobile-hide">
                                <div style="font-size: 14px; color: var(--color-text-body);">
                                    {{ $event->venue }}
                                </div>
                            </td>
                            <td class="table-col-mobile-hide">
                                <div style="font-size: 14px; font-weight: 500;">
                                    {{ $event->capacity }}
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="font-size: 14px; font-weight: 500;">
                                        {{ $registrationCount }}
                                    </div>
                                    @if($percentageFull >= 90)
                                        <x-badge variant="danger">{{ round($percentageFull) }}%</x-badge>
                                    @elseif($percentageFull >= 70)
                                        <x-badge variant="warning">{{ round($percentageFull) }}%</x-badge>
                                    @else
                                        <x-badge variant="success">{{ round($percentageFull) }}%</x-badge>
                                    @endif
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                    <x-button
                                        variant="ghost"
                                        size="sm"
                                        href="{{ route('events.show', $event) }}"
                                        title="View Details"
                                    >
                                        <i class='bx bx-show'></i>
                                    </x-button>

                                    @if($user->role !== 'student')
                                        <x-button
                                            variant="ghost"
                                            size="sm"
                                            href="{{ route('events.edit', $event) }}"
                                            title="Edit"
                                        >
                                            <i class='bx bx-edit'></i>
                                        </x-button>

                                        <form method="POST" action="{{ route('events.destroy', $event) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <x-button
                                                variant="ghost"
                                                size="sm"
                                                type="submit"
                                                title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this event?')"
                                            >
                                                <i class='bx bx-trash' style="color: var(--color-danger-600);"></i>
                                            </x-button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Card Layout -->
        <div class="table-mobile-cards">
            @foreach($events as $event)
                @php
                    $registrationCount = $event->registrations->count();
                    $percentageFull = $event->capacity > 0 ? ($registrationCount / $event->capacity) * 100 : 0;
                @endphp
                <div class="mobile-table-card">
                    <div class="mobile-table-card-title">{{ $event->title }}</div>
                    <div class="mobile-table-card-meta">
                        <div class="mobile-table-card-meta-item">
                            <i class='bx bx-calendar'></i>
                            {{ $event->date->format('M d, Y') }}
                        </div>
                        <div class="mobile-table-card-meta-item">
                            <i class='bx bx-time'></i>
                            {{ $event->time_start->format('H:i') }} - {{ $event->time_end->format('H:i') }}
                        </div>
                        <div class="mobile-table-card-meta-item">
                            <i class='bx bx-group'></i>
                            {{ $registrationCount }}/{{ $event->capacity }}
                        </div>
                        @if($event->club)
                            <div class="mobile-table-card-meta-item">
                                <i class='bx bx-building'></i>
                                {{ $event->club->name }}
                            </div>
                        @endif
                        @if($event->venue)
                            <div class="mobile-table-card-meta-item">
                                <i class='bx bx-map-pin'></i>
                                {{ $event->venue }}
                            </div>
                        @endif
                    </div>
                    <div class="mobile-table-card-actions">
                        <x-button
                            variant="ghost"
                            size="sm"
                            href="{{ route('events.show', $event) }}"
                            title="View Details"
                        >
                            <i class='bx bx-show'></i>
                        </x-button>

                        @if($user->role !== 'student')
                            <x-button
                                variant="ghost"
                                size="sm"
                                href="{{ route('events.edit', $event) }}"
                                title="Edit"
                            >
                                <i class='bx bx-edit'></i>
                            </x-button>

                            <form method="POST" action="{{ route('events.destroy', $event) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <x-button
                                    variant="ghost"
                                    size="sm"
                                    type="submit"
                                    title="Delete"
                                    onclick="return confirm('Are you sure you want to delete this event?')"
                                >
                                    <i class='bx bx-trash' style="color: var(--color-danger-600);"></i>
                                </x-button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div style="text-align: center; padding: 48px 16px;">
            <i class='bx bx-calendar' style="font-size: 64px; color: var(--color-text-muted); margin-bottom: 16px;"></i>
            <h3 style="font-size: 18px; font-weight: 600; color: var(--color-text-heading); margin-bottom: 8px;">
                No events found
            </h3>
            <p style="font-size: 14px; color: var(--color-text-muted); margin-bottom: 24px;">
                {{ $user->role === 'student' ? 'Check back later for upcoming events' : 'Create your first event to get started' }}
            </p>
            @if($user->role !== 'student')
                <x-button variant="primary" href="{{ route('events.create') }}">
                    <i class='bx bx-plus'></i>
                    <span>Create Event</span>
                </x-button>
            @endif
        </div>
    @endif
</x-card>

<script>
function toggleFilters() {
    const filtersCard = document.getElementById('filters-card');
    const toggleBtn = document.getElementById('filter-toggle-btn');
    const toggleIcon = toggleBtn.querySelector('i');
    const toggleText = toggleBtn.querySelector('span');

    if (filtersCard.style.display === 'none' || filtersCard.style.display === '') {
        filtersCard.style.display = 'block';
        toggleIcon.className = 'bx bx-chevron-up';
        toggleText.textContent = 'Hide Filters';
    } else {
        filtersCard.style.display = 'none';
        toggleIcon.className = 'bx bx-filter-alt';
        toggleText.textContent = 'Filters';
    }
}

// Show filters if there are active filters
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('filter-toggle-btn');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', toggleFilters);
    }

    const urlParams = new URLSearchParams(window.location.search);
    const hasFilters = Array.from(urlParams.keys()).some(key =>
        key !== '' && urlParams.get(key) !== '' && urlParams.get(key) !== null
    );

    if (hasFilters) {
        toggleFilters();
    }
});
</script>
@endsection