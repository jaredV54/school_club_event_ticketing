@extends('layout.main')

@section('title', 'Users - EventOps')

@section('content')
<!-- Page Header -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; margin-top: -56px;">
    <div>
        <h1 style="margin-bottom: 4px;">Users</h1>
        <p class="text-muted" style="font-size: 14px;">Manage system users and their roles</p>
    </div>
    <div style="display: flex; gap: 12px; align-items: center;">
        <x-button variant="secondary" type="button" id="filter-toggle-btn">
            <i class='bx bx-filter-alt'></i>
            <span class="btn-text">Filters</span>
        </x-button>
        <x-button variant="primary" href="{{ route('users.create') }}">
            <i class='bx bx-plus'></i>
            <span class="btn-text">Create User</span>
        </x-button>
    </div>
</div>

<!-- Filters -->
<x-card style="margin-bottom: 24px; display: none;" id="filters-card">
    <form method="GET" action="{{ route('users.index') }}" style="display: flex; flex-direction: column; gap: 20px;">
        <!-- User Information Row -->
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

            <div>
                <label for="role" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Role
                </label>
                <select
                    class="input"
                    id="role"
                    name="role"
                    style="width: 100%; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; padding-right: 40px;"
                >
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="officer" {{ request('role') === 'officer' ? 'selected' : '' }}>Officer</option>
                    <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Student</option>
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
        </div>

        <!-- Actions Row -->
        <div style="display: flex; gap: 12px; justify-content: flex-end; padding-top: 8px; border-top: 1px solid var(--color-border-subtle);">
            <x-button type="submit" variant="primary">
                <i class='bx bx-search'></i>
                <span>Filter</span>
            </x-button>
            <x-button type="button" variant="secondary" onclick="window.location.href='{{ route('users.index') }}'">
                <i class='bx bx-x'></i>
                <span>Clear</span>
            </x-button>
        </div>
    </form>
</x-card>

<!-- Users Table Card -->
<x-card>
    <x-slot:title>
        All Users
        <span style="margin-left: 8px; font-size: 14px; font-weight: 500; color: var(--color-text-muted);">
            ({{ $users->count() }})
        </span>
    </x-slot:title>

    @if($users->count() > 0)
        <!-- Desktop Table -->
        <div style="overflow-x: auto;" class="table-responsive-card">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Club</th>
                        <th>Created</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td style="font-weight: 500; color: var(--color-text-muted);">
                                #{{ $user->id }}
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div class="user-avatar" style="width: 28px; height: 28px; font-size: 12px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div style="font-weight: 500; color: var(--color-text-heading);">
                                        {{ $user->name }}
                                    </div>
                                </div>
                            </td>
                            <td style="color: var(--color-text-muted);">
                                {{ $user->email }}
                            </td>
                            <td>
                                @if($user->role === 'admin')
                                    <x-badge variant="danger">Admin</x-badge>
                                @elseif($user->role === 'officer')
                                    <x-badge variant="warning">Officer</x-badge>
                                @else
                                    <x-badge variant="info">Student</x-badge>
                                @endif
                            </td>
                            <td style="font-size: 14px; color: var(--color-text-body);">
                                {{ $user->club ? $user->club->name : '—' }}
                            </td>
                            <td style="font-size: 14px; color: var(--color-text-muted);">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                    <x-button
                                        variant="ghost"
                                        size="sm"
                                        href="{{ route('users.show', $user) }}"
                                        title="View Details"
                                    >
                                        <i class='bx bx-show'></i>
                                    </x-button>

                                    <x-button
                                        variant="ghost"
                                        size="sm"
                                        href="{{ route('users.edit', $user) }}"
                                        title="Edit"
                                    >
                                        <i class='bx bx-edit'></i>
                                    </x-button>

                                    <form method="POST" action="{{ route('users.destroy', $user) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <x-button
                                            variant="ghost"
                                            size="sm"
                                            type="submit"
                                            title="Delete"
                                            onclick="return confirm('Are you sure you want to delete this user?')"
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
            @foreach($users as $user)
                <div class="mobile-table-card">
                    <div class="mobile-table-card-title">{{ $user->name }}</div>
                    <div class="mobile-table-card-meta">
                        <div class="mobile-table-card-meta-item">
                            <i class='bx bx-envelope'></i>
                            {{ $user->email }}
                        </div>
                        <div class="mobile-table-card-meta-item">
                            @if($user->role === 'admin')
                                <x-badge variant="danger">Admin</x-badge>
                            @elseif($user->role === 'officer')
                                <x-badge variant="warning">Officer</x-badge>
                            @else
                                <x-badge variant="info">Student</x-badge>
                            @endif
                        </div>
                        @if($user->club)
                            <div class="mobile-table-card-meta-item">
                                <i class='bx bx-building'></i>
                                {{ $user->club->name }}
                            </div>
                        @endif
                        <div class="mobile-table-card-meta-item">
                            <i class='bx bx-calendar-plus'></i>
                            Joined {{ $user->created_at->format('M d, Y') }}
                        </div>
                    </div>
                    <div class="mobile-table-card-actions">
                        <x-button
                            variant="ghost"
                            size="sm"
                            href="{{ route('users.show', $user) }}"
                            title="View Details"
                        >
                            <i class='bx bx-show'></i>
                        </x-button>

                        <x-button
                            variant="ghost"
                            size="sm"
                            href="{{ route('users.edit', $user) }}"
                            title="Edit"
                        >
                            <i class='bx bx-edit'></i>
                        </x-button>

                        <form method="POST" action="{{ route('users.destroy', $user) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <x-button
                                variant="ghost"
                                size="sm"
                                type="submit"
                                title="Delete"
                                onclick="return confirm('Are you sure you want to delete this user?')"
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
            <i class='bx bx-user-x' style="font-size: 64px; color: var(--color-text-muted); margin-bottom: 16px;"></i>
            <h3 style="font-size: 18px; font-weight: 600; color: var(--color-text-heading); margin-bottom: 8px;">
                No users found
            </h3>
            <p style="font-size: 14px; color: var(--color-text-muted); margin-bottom: 24px;">
                Create your first user to get started
            </p>
            <x-button variant="primary" href="{{ route('users.create') }}">
                <i class='bx bx-plus'></i>
                <span>Create User</span>
            </x-button>
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