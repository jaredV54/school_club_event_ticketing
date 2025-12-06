@extends('layout.main')

@section('title', 'User Account Approvals')

@section('content')
@php
    $user = auth()->user();
@endphp

<!-- Page Header -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; margin-top: -56px;">
    <div>
        <h1 style="margin-bottom: 4px;">User Account Approvals</h1>
        <p class="text-muted" style="font-size: 14px;">
            Review and approve newly created user accounts
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
    <form method="GET" action="{{ route('approvals.user-accounts.index') }}" style="display: flex; flex-direction: column; gap: 20px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
            <div>
                <label for="name" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Name
                </label>
                <input
                    type="text"
                    class="input"
                    id="name"
                    name="name"
                    value="{{ request('name') }}"
                    placeholder="Search by name"
                >
            </div>

            <div>
                <label for="email" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Email
                </label>
                <input
                    type="text"
                    class="input"
                    id="email"
                    name="email"
                    value="{{ request('email') }}"
                    placeholder="Search by email"
                >
            </div>
        </div>

        <!-- Actions Row -->
        <div style="display: flex; gap: 12px; justify-content: flex-end; padding-top: 8px; border-top: 1px solid var(--color-border-subtle);">
            <x-button type="submit" variant="primary">
                <i class='bx bx-search'></i>
                <span>Filter</span>
            </x-button>
            <x-button type="button" variant="secondary" onclick="window.location.href='{{ route('approvals.user-accounts.index') }}'">
                <i class='bx bx-x'></i>
                <span>Clear</span>
            </x-button>
        </div>
    </form>
</x-card>

<!-- Pending Accounts Table Card -->
<x-card>
    <x-slot:title>
        Account Requests
        <span style="margin-left: 8px; font-size: 14px; font-weight: 500; color: var(--color-text-muted);">
            ({{ $pendingAccounts->count() }})
        </span>
    </x-slot:title>

    @if($pendingAccounts->count() > 0)
        <!-- Desktop Table -->
        <div style="overflow-x: auto;" class="table-responsive-card">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Club</th>
                        <th>Registered At</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingAccounts as $account)
                        <tr>
                            <td>
                                <div style="font-weight: 500; color: var(--color-text-heading);">
                                    {{ $account->name }}
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 14px; color: var(--color-text-body);">
                                    {{ $account->email }}
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 14px; color: var(--color-text-body);">
                                    {{ $account->club->name }}
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 14px;">
                                    {{ $account->created_at->format('M d, Y') }}<br>
                                    <span style="color: var(--color-text-muted); font-size: 12px;">{{ $account->created_at->setTimezone('Asia/Manila')->format('h:i A') }}</span>
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                    <form method="POST" action="{{ route('approvals.user-accounts.approve', $account) }}" style="display: inline;">
                                        @csrf
                                        <x-button
                                            variant="ghost"
                                            size="sm"
                                            type="submit"
                                            title="Approve"
                                            onclick="return confirm('Are you sure you want to approve this account?')"
                                        >
                                            <i class='bx bx-check' style="color: var(--color-success-600);"></i>
                                        </x-button>
                                    </form>

                                    <form method="POST" action="{{ route('approvals.user-accounts.reject', $account) }}" style="display: inline;">
                                        @csrf
                                        <x-button
                                            variant="ghost"
                                            size="sm"
                                            type="submit"
                                            title="Reject"
                                            onclick="return confirm('Are you sure you want to reject this account?')"
                                        >
                                            <i class='bx bx-x' style="color: var(--color-danger-600);"></i>
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
            @foreach($pendingAccounts as $account)
                <div class="mobile-table-card">
                    <div class="mobile-table-card-title">{{ $account->name }}</div>
                    <div class="mobile-table-card-meta">
                        <div class="mobile-table-card-meta-item">
                            <i class='bx bx-envelope'></i>
                            {{ $account->email }}
                        </div>
                        <div class="mobile-table-card-meta-item">
                            <i class='bx bx-building'></i>
                            {{ $account->club->name }}
                        </div>
                        <div class="mobile-table-card-meta-item">
                            <i class='bx bx-calendar'></i>
                            {{ $account->created_at->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}
                        </div>
                    </div>
                    <div class="mobile-table-card-actions">
                        <form method="POST" action="{{ route('approvals.user-accounts.approve', $account) }}" style="display: inline;">
                            @csrf
                            <x-button
                                variant="ghost"
                                size="sm"
                                type="submit"
                                title="Approve"
                                onclick="return confirm('Are you sure you want to approve this account?')"
                            >
                                <i class='bx bx-check' style="color: var(--color-success-600);"></i>
                            </x-button>
                        </form>

                        <form method="POST" action="{{ route('approvals.user-accounts.reject', $account) }}" style="display: inline;">
                            @csrf
                            <x-button
                                variant="ghost"
                                size="sm"
                                type="submit"
                                title="Reject"
                                onclick="return confirm('Are you sure you want to reject this account?')"
                            >
                                <i class='bx bx-x' style="color: var(--color-danger-600);"></i>
                            </x-button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div style="text-align: center; padding: 48px 16px;">
            <i class='bx bx-user-check' style="font-size: 64px; color: var(--color-text-muted); margin-bottom: 16px;"></i>
            <h3 style="font-size: 18px; font-weight: 600; color: var(--color-text-heading); margin-bottom: 8px;">
                No account requests
            </h3>
            <p style="font-size: 14px; color: var(--color-text-muted); margin-bottom: 24px;">
                All account requests have been processed
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