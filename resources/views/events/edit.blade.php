@extends('layout.main')

@section('title', 'Edit Event - EventOps')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <!-- Page Header -->
    <div style="margin-bottom: 24px;">
        <h1 style="margin-bottom: 4px;">Edit Event</h1>
        <p class="text-muted" style="font-size: 14px;">Update event details</p>
    </div>

    <!-- Event Form Card -->
    <x-card>
        <form method="POST" action="{{ route('events.update', $event) }}">
            @csrf
            @method('PUT')
            
            <!-- Club Selection -->
            <div style="margin-bottom: 20px;">
                <label for="club_id" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Club <span style="color: var(--color-danger-600);">*</span>
                </label>
                <select 
                    class="input" 
                    id="club_id" 
                    name="club_id" 
                    required
                    style="width: 100%; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; padding-right: 40px;"
                >
                    <option value="">Select a club</option>
                    @foreach($clubs as $club)
                        <option value="{{ $club->id }}" {{ old('club_id', $event->club_id) == $club->id ? 'selected' : '' }}>
                            {{ $club->name }}
                        </option>
                    @endforeach
                </select>
                @error('club_id')
                    <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div style="margin-bottom: 20px;">
                <label for="title" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Event Title <span style="color: var(--color-danger-600);">*</span>
                </label>
                <input 
                    type="text" 
                    class="input" 
                    id="title" 
                    name="title" 
                    value="{{ old('title', $event->title) }}" 
                    placeholder="Enter event title"
                    required
                >
                @error('title')
                    <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div style="margin-bottom: 20px;">
                <label for="description" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Description <span style="color: var(--color-danger-600);">*</span>
                </label>
                <textarea 
                    class="input" 
                    id="description" 
                    name="description" 
                    rows="4" 
                    placeholder="Describe your event"
                    required
                    style="resize: vertical; min-height: 100px;"
                >{{ old('description', $event->description) }}</textarea>
                @error('description')
                    <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                @enderror
            </div>

            <!-- Venue -->
            <div style="margin-bottom: 20px;">
                <label for="venue" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Venue <span style="color: var(--color-danger-600);">*</span>
                </label>
                <input 
                    type="text" 
                    class="input" 
                    id="venue" 
                    name="venue" 
                    value="{{ old('venue', $event->venue) }}" 
                    placeholder="Enter venue location"
                    required
                >
                @error('venue')
                    <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date and Time Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px;">
                <!-- Date -->
                <div>
                    <label for="date" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                        Date <span style="color: var(--color-danger-600);">*</span>
                    </label>
                    <input
                        type="date"
                        class="input"
                        id="date"
                        name="date"
                        value="{{ old('date', $event->date->format('Y-m-d')) }}"
                        min="1900-01-01"
                        required
                    >
                    @error('date')
                        <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Time -->
                <div>
                    <label for="time_start" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                        Start Time <span style="color: var(--color-danger-600);">*</span>
                    </label>
                    <input 
                        type="time" 
                        class="input" 
                        id="time_start" 
                        name="time_start" 
                        value="{{ old('time_start', $event->time_start) }}" 
                        required
                    >
                    @error('time_start')
                        <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Time -->
                <div>
                    <label for="time_end" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                        End Time <span style="color: var(--color-danger-600);">*</span>
                    </label>
                    <input 
                        type="time" 
                        class="input" 
                        id="time_end" 
                        name="time_end" 
                        value="{{ old('time_end', $event->time_end) }}" 
                        required
                    >
                    @error('time_end')
                        <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Capacity -->
            <div style="margin-bottom: 20px;">
                <label for="capacity" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Capacity <span style="color: var(--color-danger-600);">*</span>
                </label>
                <input 
                    type="number" 
                    class="input" 
                    id="capacity" 
                    name="capacity" 
                    value="{{ old('capacity', $event->capacity) }}" 
                    min="1" 
                    placeholder="Maximum number of attendees"
                    required
                    style="max-width: 200px;"
                >
                @error('capacity')
                    <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                @enderror
                <p style="margin-top: 4px; font-size: 12px; color: var(--color-text-muted);">
                    Current registrations: {{ $event->registrations->count() }}
                </p>
            </div>

            <!-- Form Actions -->
            <div style="display: flex; gap: 12px; padding-top: 8px; border-top: 1px solid var(--color-border-subtle);">
                <x-button type="submit" variant="primary" style="flex: 1;">
                    <i class='bx bx-check'></i>
                    <span>{{ in_array($event->status, ['cancelled', 'passed']) ? 'Reactivate Event' : 'Update Event' }}</span>
                </x-button>
                <x-button type="button" variant="secondary" href="{{ route('events.show', $event) }}">
                    Cancel
                </x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection