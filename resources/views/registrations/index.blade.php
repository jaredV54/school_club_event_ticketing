@extends('layout.main')

@section('title', 'My Tickets - EventOps')

@section('content')
@php
    $user = auth()->user();
    $isStudent = $user->role === 'student';
@endphp

<!-- Page Header -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; margin-top: -56px;">
    <div>
        <h1 style="margin-bottom: 4px;">{{ $isStudent ? 'My Tickets' : 'Event Registrations' }}</h1>
        <p class="text-muted" style="font-size: 14px;">
            {{ $isStudent ? 'View and manage your event registrations' : 'Manage event registrations' }}
        </p>
    </div>
    <div style="display: flex; gap: 12px; align-items: center;">
        @if(!$isStudent)
            <x-button variant="secondary" type="button" id="filter-toggle-btn">
                <i class='bx bx-filter-alt'></i>
                <span>Filters</span>
            </x-button>
        @endif
        @if($user->role !== 'officer')
            <x-button variant="primary" href="{{ route('registrations.create') }}">
                <i class='bx bx-plus'></i>
                <span>{{ $isStudent ? 'Register for Event' : 'Create Registration' }}</span>
            </x-button>
        @endif
    </div>
</div>

<!-- Filters -->
@if(!$isStudent)
    <x-card style="margin-bottom: 24px; display: none;" id="filters-card">
        <form method="GET" action="{{ route('registrations.index') }}" style="display: flex; flex-direction: column; gap: 20px;">
            <!-- Event & Student Information Row -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
                <div>
                    <label for="event_title" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                        Event Title
                    </label>
                    <input
                        type="text"
                        class="input"
                        id="event_title"
                        name="event_title"
                        value="{{ request('event_title') }}"
                        placeholder="Search by event title"
                    >
                </div>

                <div>
                    <label for="student_name" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                        Student Name
                    </label>
                    <input
                        type="text"
                        class="input"
                        id="student_name"
                        name="student_name"
                        value="{{ request('student_name') }}"
                        placeholder="Search by student name"
                    >
                </div>

                <div>
                    <label for="student_email" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                        Student Email
                    </label>
                    <input
                        type="text"
                        class="input"
                        id="student_email"
                        name="student_email"
                        value="{{ request('student_email') }}"
                        placeholder="Search by email"
                    >
                </div>
            </div>

            <!-- Ticket & Status Row -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                <div>
                    <label for="ticket_code" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                        Ticket Code
                    </label>
                    <input
                        type="text"
                        class="input"
                        id="ticket_code"
                        name="ticket_code"
                        value="{{ request('ticket_code') }}"
                        placeholder="Search by ticket code"
                    >
                </div>

                <div>
                    <label for="status" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                        Status
                    </label>
                    <select
                        class="input"
                        id="status"
                        name="status"
                        style="width: 100%; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; padding-right: 40px;"
                    >
                        <option value="">All Statuses</option>
                        <option value="registered" {{ request('status') === 'registered' ? 'selected' : '' }}>Registered</option>
                        <option value="attended" {{ request('status') === 'attended' ? 'selected' : '' }}>Attended</option>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <div>
                        <label for="registered_from" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                            Registered From
                        </label>
                        <input
                            type="date"
                            class="input"
                            id="registered_from"
                            name="registered_from"
                            value="{{ request('registered_from') }}"
                        >
                    </div>
                    <div>
                        <label for="registered_to" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                            Registered To
                        </label>
                        <input
                            type="date"
                            class="input"
                            id="registered_to"
                            name="registered_to"
                            value="{{ request('registered_to') }}"
                        >
                    </div>
                </div>
            </div>

            <!-- Actions Row -->
            <div style="display: flex; gap: 12px; justify-content: flex-end; padding-top: 8px; border-top: 1px solid var(--color-border-subtle);">
                <x-button type="submit" variant="primary">
                    <i class='bx bx-search'></i>
                    <span>Filter</span>
                </x-button>
                <x-button type="button" variant="secondary" onclick="window.location.href='{{ route('registrations.index') }}'">
                    <i class='bx bx-x'></i>
                    <span>Clear</span>
                </x-button>
            </div>
        </form>
    </x-card>
@endif

<!-- Registrations Table Card -->
<x-card>
    <x-slot:title>
        {{ $isStudent ? 'My Registrations' : 'All Registrations' }}
        <span style="margin-left: 8px; font-size: 14px; font-weight: 500; color: var(--color-text-muted);">
            ({{ $registrations->count() }})
        </span>
    </x-slot:title>
    
    @if($registrations->count() > 0)
        <!-- Desktop Table -->
        <div style="overflow-x: auto;" class="table-responsive-card">
            <table class="table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Date</th>
                        @if(!$isStudent)
                            <th class="table-col-mobile-hide">Student</th>
                        @endif
                        <th>Ticket Code</th>
                        <th>Status</th>
                        <th class="table-col-mobile-hide">Registered</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registrations as $registration)
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
                                <div class="table-date-mobile">
                                    <div class="date-main">{{ $registration->event->date->format('M d, Y') }}</div>
                                    <div class="date-time">{{ $registration->event->time_start->format('H:i') }}</div>
                                </div>
                            </td>
                            @if(!$isStudent)
                                <td class="table-col-mobile-hide">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div class="user-avatar" style="width: 24px; height: 24px; font-size: 10px;">
                                            {{ strtoupper(substr($registration->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-size: 14px; font-weight: 500;">{{ $registration->user->name }}</div>
                                            <div style="font-size: 12px; color: var(--color-text-muted);">{{ $registration->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                            @endif
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
                            <td class="table-col-mobile-hide" style="font-size: 14px; color: var(--color-text-muted);">
                                {{ $registration->created_at->format('M d, Y') }}
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                    <x-button
                                        variant="ghost"
                                        size="sm"
                                        href="{{ route('registrations.show', $registration) }}"
                                        title="View Details"
                                    >
                                        <i class='bx bx-show'></i>
                                    </x-button>

                                    @if($user->role === 'admin')
                                        <x-button
                                            variant="ghost"
                                            size="sm"
                                            href="{{ route('registrations.edit', $registration) }}"
                                            title="Edit"
                                        >
                                            <i class='bx bx-edit'></i>
                                        </x-button>

                                        <form method="POST" action="{{ route('registrations.destroy', $registration) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <x-button
                                                variant="ghost"
                                                size="sm"
                                                type="submit"
                                                title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this registration?')"
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
            @foreach($registrations as $registration)
                <div class="mobile-table-card">
                    <div class="mobile-table-card-title">{{ $registration->event->title }}</div>
                    <div class="mobile-table-card-meta">
                        <div class="mobile-table-card-meta-item">
                            <i class='bx bx-calendar'></i>
                            {{ $registration->event->date->format('M d, Y') }}
                        </div>
                        <div class="mobile-table-card-meta-item">
                            <i class='bx bx-time'></i>
                            {{ $registration->event->time_start->format('H:i') }}
                        </div>
                        <div class="mobile-table-card-meta-item">
                            <i class='bx bx-receipt'></i>
                            {{ $registration->ticket_code }}
                        </div>
                        @if(!$isStudent)
                            <div class="mobile-table-card-meta-item">
                                <i class='bx bx-user'></i>
                                {{ $registration->user->name }}
                            </div>
                        @endif
                        <div class="mobile-table-card-meta-item">
                            @if($registration->status === 'attended')
                                <x-badge variant="success">Attended</x-badge>
                            @else
                                <x-badge variant="info">Registered</x-badge>
                            @endif
                        </div>
                    </div>
                    <div class="mobile-table-card-actions">
                        <x-button
                            variant="ghost"
                            size="sm"
                            href="{{ route('registrations.show', $registration) }}"
                            title="View Details"
                        >
                            <i class='bx bx-show'></i>
                        </x-button>

                        @if($user->role === 'admin')
                            <x-button
                                variant="ghost"
                                size="sm"
                                href="{{ route('registrations.edit', $registration) }}"
                                title="Edit"
                            >
                                <i class='bx bx-edit'></i>
                            </x-button>

                            <form method="POST" action="{{ route('registrations.destroy', $registration) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <x-button
                                    variant="ghost"
                                    size="sm"
                                    type="submit"
                                    title="Delete"
                                    onclick="return confirm('Are you sure you want to delete this registration?')"
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
            <i class='bx bx-receipt' style="font-size: 64px; color: var(--color-text-muted); margin-bottom: 16px;"></i>
            <h3 style="font-size: 18px; font-weight: 600; color: var(--color-text-heading); margin-bottom: 8px;">
                {{ $isStudent ? 'No tickets yet' : 'No registrations found' }}
            </h3>
            <p style="font-size: 14px; color: var(--color-text-muted); margin-bottom: 24px;">
                {{ $isStudent ? 'Start by registering for an upcoming event' : 'Create your first registration to get started' }}
            </p>
            @if($user->role !== 'officer')
                <x-button variant="primary" href="{{ route('registrations.create') }}">
                    <i class='bx bx-plus'></i>
                    <span>{{ $isStudent ? 'Browse Events' : 'Create Registration' }}</span>
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
        toggleIcon.className = 'bx bx-filter-alt-off';
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