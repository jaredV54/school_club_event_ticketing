@extends('layout.main')

@section('title', auth()->user()->role === 'student' ? 'My Attendance' : 'Attendance Logs - EventOps')

@section('content')
@php $user = auth()->user(); @endphp

<!-- Page Header -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; margin-top: -56px;">
    <div>
        <h1 style="margin-bottom: 4px;">{{ $user->role === 'student' ? 'My Attendance History' : 'Attendance Logs' }}</h1>
        <p class="text-muted" style="font-size: 14px;">
            {{ $user->role === 'student' ? 'View your event attendance records' : 'Manage attendance logs' }}
        </p>
    </div>
    <div style="display: flex; gap: 12px; align-items: center;">
        <x-button variant="secondary" type="button" id="filter-toggle-btn">
            <i class='bx bx-filter-alt'></i>
            <span class="btn-text">Filters</span>
        </x-button>
        @if($user->role !== 'student')
            <x-button variant="primary" href="{{ route('attendance.create') }}">
                <i class='bx bx-plus'></i>
                <span class="btn-text">Create Log</span>
            </x-button>
        @endif
    </div>
    </div>
    
<!-- Filters -->
<x-card style="margin-bottom: 24px; display: none;" id="filters-card">
    <form method="GET" action="{{ route('attendance.index') }}" style="display: flex; flex-direction: column; gap: 20px;">
        @if($user->role === 'student')
            <!-- Student Filters -->
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

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <div>
                        <label for="timestamp_from" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                            From Date
                        </label>
                        <input
                            type="date"
                            class="input"
                            id="timestamp_from"
                            name="timestamp_from"
                            value="{{ request('timestamp_from') }}"
                        >
                    </div>
                    <div>
                        <label for="timestamp_to" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                            To Date
                        </label>
                        <input
                            type="date"
                            class="input"
                            id="timestamp_to"
                            name="timestamp_to"
                            value="{{ request('timestamp_to') }}"
                        >
                    </div>
                </div>
            </div>
        @else
            <!-- Admin/Officer Filters -->
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

                <div>
                    <label for="status" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                        Registration Status
                    </label>
                    <select
                        class="input"
                        id="status"
                        name="status"
                        style="width: 100%; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; padding-right: 40px;"
                    >
                        <option value="">All Statuses</option>
                        <option value="registered" {{ request('status') == 'registered' ? 'selected' : '' }}>Registered</option>
                        <option value="attended" {{ request('status') == 'attended' ? 'selected' : '' }}>Attended</option>
                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div>
                    <label for="club_id" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                        Club
                    </label>
                    <select
                        class="input"
                        id="club_id"
                        name="club_id"
                        style="width: 100%; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline%3e%3cpolyline%3e'); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; padding-right: 40px;"
                    >
                        <option value="">All Clubs</option>
                        @foreach($clubs as $club)
                            <option value="{{ $club->id }}" {{ request('club_id') == $club->id ? 'selected' : '' }}>
                                {{ $club->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

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

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <div>
                        <label for="timestamp_from" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                            Timestamp From
                        </label>
                        <input
                            type="date"
                            class="input"
                            id="timestamp_from"
                            name="timestamp_from"
                            value="{{ request('timestamp_from') }}"
                        >
                    </div>
                    <div>
                        <label for="timestamp_to" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                            Timestamp To
                        </label>
                        <input
                            type="date"
                            class="input"
                            id="timestamp_to"
                            name="timestamp_to"
                            value="{{ request('timestamp_to') }}"
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
            <x-button type="button" variant="secondary" onclick="window.location.href='{{ route('attendance.index') }}'">
                <i class='bx bx-x'></i>
                <span>Clear</span>
            </x-button>
        </div>
    </form>
</x-card>

@if($user->role === 'student')
    <!-- Student View - Simple attendance history -->
    @if($logs->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px;">
            @foreach($logs as $log)
                <x-card>
                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div style="flex-shrink: 0; width: 40px; height: 40px; background-color: var(--color-success-100); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class='bx bx-check' style="color: var(--color-success-600); font-size: 20px;"></i>
                        </div>
                        <div style="flex: 1;">
                            <h4 style="margin-bottom: 4px; font-size: 16px; font-weight: 600; color: var(--color-text-heading);">
                                {{ $log->registration->event->title }}
                            </h4>
                            <div style="font-size: 14px; color: var(--color-text-body); margin-bottom: 8px;">
                                {{ $log->registration->event->club->name }}
                            </div>
                            <div style="display: flex; align-items: center; gap: 16px; font-size: 12px; color: var(--color-text-muted);">
                                <div style="display: flex; align-items: center; gap: 4px;">
                                    <i class='bx bx-calendar'></i>
                                    {{ $log->timestamp->setTimezone('Asia/Manila')->format('M d, Y') }}
                                </div>
                                <div style="display: flex; align-items: center; gap: 4px;">
                                    <i class='bx bx-time'></i>
                                    {{ $log->timestamp->setTimezone('Asia/Manila')->format('h:i A') }}
                                </div>
                            </div>
                            <div style="margin-top: 12px;">
                                <x-badge variant="success">Attended</x-badge>
                            </div>
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <x-card>
            <div style="text-align: center; padding: 48px 16px;">
                <i class='bx bx-calendar-x' style="font-size: 64px; color: var(--color-text-muted); margin-bottom: 16px;"></i>
                <h3 style="font-size: 18px; font-weight: 600; color: var(--color-text-heading); margin-bottom: 8px;">
                    No attendance records
                </h3>
                <p style="font-size: 14px; color: var(--color-text-muted); margin-bottom: 24px;">
                    You haven't attended any events yet.
                </p>
            </div>
        </x-card>
    @endif
@else
    <!-- Admin/Officer View - Full management table -->
    <x-card>
        <x-slot:title>
            All Attendance Logs
            <span style="margin-left: 8px; font-size: 14px; font-weight: 500; color: var(--color-text-muted);">
                ({{ $logs->count() }})
            </span>
        </x-slot:title>

        @if($logs->count() > 0)
            <!-- Desktop Table -->
            <div style="overflow-x: auto;" class="table-responsive-card">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Event</th>
                            <th>Student</th>
                            <th>Ticket Code</th>
                            <th>Timestamp</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td>
                                    <div style="font-size: 14px; font-weight: 500; color: var(--color-text-heading);">
                                        #{{ $log->id }}
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 500; color: var(--color-text-heading);">
                                        {{ $log->registration->event->title }}
                                    </div>
                                    <div style="font-size: 12px; color: var(--color-text-muted);">
                                        {{ $log->registration->event->club->name }}
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div class="user-avatar" style="width: 24px; height: 24px; font-size: 10px;">
                                            {{ strtoupper(substr($log->registration->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-size: 14px; font-weight: 500;">{{ $log->registration->user->name }}</div>
                                            <div style="font-size: 12px; color: var(--color-text-muted);">{{ $log->registration->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code style="padding: 2px 6px; background-color: var(--color-page-bg); color: var(--color-text-body); font-size: 12px; font-family: monospace;">
                                        {{ $log->registration->ticket_code }}
                                    </code>
                                </td>
                                <td>
                                    <div style="font-size: 14px;">
                                        {{ $log->timestamp->setTimezone('Asia/Manila')->format('M d, Y') }}
                                    </div>
                                    <div style="font-size: 12px; color: var(--color-text-muted);">
                                        {{ $log->timestamp->setTimezone('Asia/Manila')->format('h:i A') }}
                                    </div>
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                        <x-button
                                            variant="ghost"
                                            size="sm"
                                            href="{{ route('attendance.show', $log) }}"
                                            title="View Details"
                                        >
                                            <i class='bx bx-show'></i>
                                        </x-button>

                                        <x-button
                                            variant="ghost"
                                            size="sm"
                                            href="{{ route('attendance.edit', $log) }}"
                                            title="Edit"
                                        >
                                            <i class='bx bx-edit'></i>
                                        </x-button>

                                        <form method="POST" action="{{ route('attendance.destroy', $log) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <x-button
                                                variant="ghost"
                                                size="sm"
                                                type="submit"
                                                title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this attendance log?')"
                                            >
                                                <i class='bx bx-trash' style="color: var(--color-danger-600);"></i>
                                            </x-button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card Layout -->
            <div class="table-mobile-cards">
                @foreach($logs as $log)
                    <div class="mobile-table-card">
                        <div class="mobile-table-card-title">#{{ $log->id }} - {{ $log->registration->event->title }}</div>
                        <div class="mobile-table-card-meta">
                            <div class="mobile-table-card-meta-item">
                                <i class='bx bx-user'></i>
                                {{ $log->registration->user->name }}
                            </div>
                            <div class="mobile-table-card-meta-item">
                                <i class='bx bx-receipt'></i>
                                {{ $log->registration->ticket_code }}
                            </div>
                            <div class="mobile-table-card-meta-item">
                                <i class='bx bx-calendar-check'></i>
                                {{ $log->timestamp->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}
                            </div>
                            <div class="mobile-table-card-meta-item">
                                <i class='bx bx-building'></i>
                                {{ $log->registration->event->club->name }}
                            </div>
                        </div>
                        <div class="mobile-table-card-actions">
                            <x-button
                                variant="ghost"
                                size="sm"
                                href="{{ route('attendance.show', $log) }}"
                                title="View Details"
                            >
                                <i class='bx bx-show'></i>
                            </x-button>

                            <x-button
                                variant="ghost"
                                size="sm"
                                href="{{ route('attendance.edit', $log) }}"
                                title="Edit"
                            >
                                <i class='bx bx-edit'></i>
                            </x-button>

                            <form method="POST" action="{{ route('attendance.destroy', $log) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <x-button
                                    variant="ghost"
                                    size="sm"
                                    type="submit"
                                    title="Delete"
                                    onclick="return confirm('Are you sure you want to delete this attendance log?')"
                                >
                                    <i class='bx bx-trash' style="color: var(--color-danger-600);"></i>
                                </x-button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div style="text-align: center; padding: 48px 16px;">
                <i class='bx bx-calendar-check' style="font-size: 64px; color: var(--color-text-muted); margin-bottom: 16px;"></i>
                <h3 style="font-size: 18px; font-weight: 600; color: var(--color-text-heading); margin-bottom: 8px;">
                    No attendance logs found
                </h3>
                <p style="font-size: 14px; color: var(--color-text-muted); margin-bottom: 24px;">
                    Create your first attendance log to get started
                </p>
                <x-button variant="primary" href="{{ route('attendance.create') }}">
                    <i class='bx bx-plus'></i>
                    <span>Create Log</span>
                </x-button>
            </div>
        @endif
    </x-card>
@endif

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