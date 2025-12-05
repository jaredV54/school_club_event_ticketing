@extends('layout.main')

@section('title', 'Event Registration Approvals')

@section('content')
@php
    $user = auth()->user();
@endphp

<!-- Page Header -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; margin-top: -56px;">
    <div>
        <h1 style="margin-bottom: 4px;">Event Registration Approvals</h1>
        <p class="text-muted" style="font-size: 14px;">
            Review and approve pending event registration requests
        </p>
    </div>
    <div style="display: flex; gap: 12px; align-items: center;">
        <x-button variant="secondary" type="button" id="filter-toggle-btn">
            <i class='bx bx-filter-alt'></i>
            <span class="btn-text">Filters</span>
        </x-button>
    </div>
</div>

<!-- Filters -->
<x-card style="margin-bottom: 24px; display: none;" id="filters-card">
    <form method="GET" action="{{ route('approvals.event-registrations.index') }}" style="display: flex; flex-direction: column; gap: 20px;">
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
        </div>

        <!-- Actions Row -->
        <div style="display: flex; gap: 12px; justify-content: flex-end; padding-top: 8px; border-top: 1px solid var(--color-border-subtle);">
            <x-button type="submit" variant="primary">
                <i class='bx bx-search'></i>
                <span>Filter</span>
            </x-button>
            <x-button type="button" variant="secondary" onclick="window.location.href='{{ route('approvals.event-registrations.index') }}'">
                <i class='bx bx-x'></i>
                <span>Clear</span>
            </x-button>
        </div>
    </form>
</x-card>

<!-- Pending Registrations Table Card -->
<x-card>
    <x-slot:title>
        Registration Requests
        <span style="margin-left: 8px; font-size: 14px; font-weight: 500; color: var(--color-text-muted);">
            ({{ $pendingRegistrations->count() }})
        </span>
    </x-slot:title>

    @if($pendingRegistrations->count() > 0)
        <!-- Desktop Table -->
        <div style="overflow-x: auto;" class="table-responsive-card">
            <table class="table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Student</th>
                        <th>Role</th>
                        <th>Requested At</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingRegistrations as $registration)
                        <tr>
                            <td>
                                <div style="font-weight: 500; color: var(--color-text-heading);">
                                    {{ $registration->event->title }}
                                </div>
                                <div style="font-size: 14px; color: var(--color-text-body);">
                                    {{ $registration->event->date->format('M d, Y') }} at {{ $registration->event->time_start->format('H:i') }}
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 500; color: var(--color-text-heading);">
                                    {{ $registration->user->name }}
                                </div>
                                <div style="font-size: 14px; color: var(--color-text-body);">
                                    {{ $registration->user->email }}
                                </div>
                            </td>
                            <td>
                                <x-badge variant="secondary">{{ ucfirst($registration->role) }}</x-badge>
                            </td>
                            <td>
                                <div style="font-size: 14px;">
                                    {{ $registration->created_at->format('M d, Y') }}<br>
                                    <span style="color: var(--color-text-muted);">{{ $registration->created_at->format('H:i') }}</span>
                                </div>
                            </td>
                            <td style="text-align: right;">
                                @if($registration->status === 'pending')
                                    <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                        <form method="POST" action="{{ route('approvals.event-registrations.approve', $registration) }}" style="display: inline;">
                                            @csrf
                                            <x-button
                                                variant="ghost"
                                                size="sm"
                                                type="submit"
                                                title="Approve"
                                                onclick="return confirm('Are you sure you want to approve this registration?')"
                                            >
                                                <i class='bx bx-check' style="color: var(--color-success-600);"></i>
                                            </x-button>
                                        </form>

                                        <form method="POST" action="{{ route('approvals.event-registrations.reject', $registration) }}" style="display: inline;">
                                            @csrf
                                            <x-button
                                                variant="ghost"
                                                size="sm"
                                                type="submit"
                                                title="Reject"
                                                onclick="return confirm('Are you sure you want to reject this registration?')"
                                            >
                                                <i class='bx bx-x' style="color: var(--color-danger-600);"></i>
                                            </x-button>
                                        </form>
                                    </div>
                                @else
                                    <span style="font-size: 14px; color: var(--color-text-muted);">{{ ucfirst($registration->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Card Layout -->
        <div class="table-mobile-cards">
            @foreach($pendingRegistrations as $registration)
                <div class="mobile-table-card">
                    <div class="mobile-table-card-title">{{ $registration->event->title }}</div>
                    <div class="mobile-table-card-meta">
                        <div class="mobile-table-card-meta-item">
                            <i class='bx bx-user'></i>
                            {{ $registration->user->name }}
                        </div>
                        <div class="mobile-table-card-meta-item">
                            <i class='bx bx-calendar'></i>
                            {{ $registration->event->date->format('M d, Y') }}
                        </div>
                        <div class="mobile-table-card-meta-item">
                            <i class='bx bx-time'></i>
                            {{ $registration->event->time_start->format('H:i') }}
                        </div>
                    </div>
                    <div class="mobile-table-card-actions">
                        @if($registration->status === 'pending')
                            <form method="POST" action="{{ route('approvals.event-registrations.approve', $registration) }}" style="display: inline;">
                                @csrf
                                <x-button
                                    variant="ghost"
                                    size="sm"
                                    type="submit"
                                    title="Approve"
                                    onclick="return confirm('Are you sure you want to approve this registration?')"
                                >
                                    <i class='bx bx-check' style="color: var(--color-success-600);"></i>
                                </x-button>
                            </form>

                            <form method="POST" action="{{ route('approvals.event-registrations.reject', $registration) }}" style="display: inline;">
                                @csrf
                                <x-button
                                    variant="ghost"
                                    size="sm"
                                    type="submit"
                                    title="Reject"
                                    onclick="return confirm('Are you sure you want to reject this registration?')"
                                >
                                    <i class='bx bx-x' style="color: var(--color-danger-600);"></i>
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
            <i class='bx bx-check-circle' style="font-size: 64px; color: var(--color-text-muted); margin-bottom: 16px;"></i>
            <h3 style="font-size: 18px; font-weight: 600; color: var(--color-text-heading); margin-bottom: 8px;">
                No registration requests
            </h3>
            <p style="font-size: 14px; color: var(--color-text-muted); margin-bottom: 24px;">
                All registration requests have been processed
            </p>
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