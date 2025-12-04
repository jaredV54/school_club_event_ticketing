@extends('layout.main')

@section('title', 'Clubs - EventOps')

@section('content')
<!-- Page Header -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; margin-top: -56px;">
    <div>
        <h1 style="margin-bottom: 4px;">Clubs</h1>
        <p class="text-muted" style="font-size: 14px;">Manage school clubs and organizations</p>
    </div>
    <div style="display: flex; gap: 12px; align-items: center;">
        <x-button variant="secondary" type="button" id="filter-toggle-btn">
            <i class='bx bx-filter-alt'></i>
            <span>Filters</span>
        </x-button>
        <x-button variant="primary" href="{{ route('clubs.create') }}">
            <i class='bx bx-plus'></i>
            <span>Create Club</span>
        </x-button>
    </div>
</div>

<!-- Filters -->
<x-card style="margin-bottom: 24px; display: none;" id="filters-card">
    <form method="GET" action="{{ route('clubs.index') }}" style="display: flex; flex-direction: column; gap: 20px;">
        <!-- Basic Information Row -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
            <div>
                <label for="name" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Club Name
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
                <label for="description" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Description
                </label>
                <input
                    type="text"
                    class="input"
                    id="description"
                    name="description"
                    value="{{ request('description') }}"
                    placeholder="Search by description"
                >
            </div>
        </div>

        <!-- Statistics Row -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                <div>
                    <label for="events_min" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                        Min Events
                    </label>
                    <input
                        type="number"
                        class="input"
                        id="events_min"
                        name="events_min"
                        value="{{ request('events_min') }}"
                        min="0"
                    >
                </div>
                <div>
                    <label for="events_max" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                        Max Events
                    </label>
                    <input
                        type="number"
                        class="input"
                        id="events_max"
                        name="events_max"
                        value="{{ request('events_max') }}"
                        min="0"
                    >
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                <div>
                    <label for="members_min" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                        Min Members
                    </label>
                    <input
                        type="number"
                        class="input"
                        id="members_min"
                        name="members_min"
                        value="{{ request('members_min') }}"
                        min="0"
                    >
                </div>
                <div>
                    <label for="members_max" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                        Max Members
                    </label>
                    <input
                        type="number"
                        class="input"
                        id="members_max"
                        name="members_max"
                        value="{{ request('members_max') }}"
                        min="0"
                    >
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                <div>
                    <label for="created_from" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                        Created From
                    </label>
                    <input
                        type="date"
                        class="input"
                        id="created_from"
                        name="created_from"
                        value="{{ request('created_from') }}"
                    >
                </div>
                <div>
                    <label for="created_to" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                        Created To
                    </label>
                    <input
                        type="date"
                        class="input"
                        id="created_to"
                        name="created_to"
                        value="{{ request('created_to') }}"
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
            <x-button type="button" variant="secondary" onclick="window.location.href='{{ route('clubs.index') }}'">
                <i class='bx bx-x'></i>
                <span>Clear</span>
            </x-button>
        </div>
    </form>
</x-card>

<!-- Clubs Table Card -->
<x-card>
    <x-slot:title>
        All Clubs
        <span style="margin-left: 8px; font-size: 14px; font-weight: 500; color: var(--color-text-muted);">
            ({{ $clubs->count() }})
        </span>
    </x-slot:title>
    
    @if($clubs->count() > 0)
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Club Name</th>
                        <th>Description</th>
                        <th>Events</th>
                        <th>Members</th>
                        <th>Created</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clubs as $club)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 32px; height: 32px; background-color: var(--color-primary-50); color: var(--color-primary-600); display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px;">
                                        {{ strtoupper(substr($club->name, 0, 1)) }}
                                    </div>
                                    <div style="font-weight: 500; color: var(--color-text-heading);">
                                        {{ $club->name }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 14px; color: var(--color-text-body); max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $club->description ?: 'No description' }}
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <i class='bx bx-calendar' style="font-size: 16px; color: var(--color-text-muted);"></i>
                                    <span style="font-weight: 500;">{{ $club->events->count() }}</span>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <i class='bx bx-group' style="font-size: 16px; color: var(--color-text-muted);"></i>
                                    <span style="font-weight: 500;">{{ $club->users->count() }}</span>
                                </div>
                            </td>
                            <td style="font-size: 14px; color: var(--color-text-muted);">
                                {{ $club->created_at->format('M d, Y') }}
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                    <x-button 
                                        variant="ghost" 
                                        size="sm" 
                                        href="{{ route('clubs.show', $club) }}"
                                        title="View Details"
                                    >
                                        <i class='bx bx-show'></i>
                                    </x-button>
                                    
                                    <x-button 
                                        variant="ghost" 
                                        size="sm" 
                                        href="{{ route('clubs.edit', $club) }}"
                                        title="Edit"
                                    >
                                        <i class='bx bx-edit'></i>
                                    </x-button>
                                    
                                    <form method="POST" action="{{ route('clubs.destroy', $club) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <x-button 
                                            variant="ghost" 
                                            size="sm" 
                                            type="submit"
                                            title="Delete"
                                            onclick="return confirm('Are you sure you want to delete this club? This will also delete all associated events.')"
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
    @else
        <!-- Empty State -->
        <div style="text-align: center; padding: 48px 16px;">
            <i class='bx bx-building' style="font-size: 64px; color: var(--color-text-muted); margin-bottom: 16px;"></i>
            <h3 style="font-size: 18px; font-weight: 600; color: var(--color-text-heading); margin-bottom: 8px;">
                No clubs found
            </h3>
            <p style="font-size: 14px; color: var(--color-text-muted); margin-bottom: 24px;">
                Create your first club to get started
            </p>
            <x-button variant="primary" href="{{ route('clubs.create') }}">
                <i class='bx bx-plus'></i>
                <span>Create Club</span>
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