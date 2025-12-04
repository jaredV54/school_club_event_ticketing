@extends('layout.main')

@section('title', 'Edit Registration - EventOps')

@section('content')
<div style="max-width: 700px; margin: 0 auto;">
    <!-- Page Header -->
    <div style="margin-bottom: 24px;">
        <h1 style="margin-bottom: 4px;">Edit Registration</h1>
        <p class="text-muted" style="font-size: 14px;">Update registration details</p>
    </div>

    <!-- Registration Form Card -->
    <x-card>
        <form method="POST" action="{{ route('registrations.update', $registration) }}">
            @csrf
            @method('PUT')
            
            <!-- Event Selection -->
            <div style="margin-bottom: 20px;">
                <label for="event_id" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Event <span style="color: var(--color-danger-600);">*</span>
                </label>
                <select 
                    class="input" 
                    id="event_id" 
                    name="event_id" 
                    required
                    style="width: 100%; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; padding-right: 40px;"
                >
                    <option value="">Select an event</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}" {{ old('event_id', $registration->event_id) == $event->id ? 'selected' : '' }}>
                            {{ $event->title }} - {{ $event->date->format('M d, Y') }}
                        </option>
                    @endforeach
                </select>
                @error('event_id')
                    <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                @enderror
            </div>

            <!-- User Selection -->
            <div style="margin-bottom: 20px;">
                <label for="user_id" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Student <span style="color: var(--color-danger-600);">*</span>
                </label>
                <select 
                    class="input" 
                    id="user_id" 
                    name="user_id" 
                    required
                    style="width: 100%; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; padding-right: 40px;"
                >
                    <option value="">Select a student</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id', $registration->user_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Selection -->
            <div style="margin-bottom: 20px;">
                <label for="status" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Status <span style="color: var(--color-danger-600);">*</span>
                </label>
                <select 
                    class="input" 
                    id="status" 
                    name="status" 
                    required
                    style="width: 100%; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; padding-right: 40px;"
                >
                    <option value="registered" {{ old('status', $registration->status) === 'registered' ? 'selected' : '' }}>Registered</option>
                    <option value="attended" {{ old('status', $registration->status) === 'attended' ? 'selected' : '' }}>Attended</option>
                </select>
                @error('status')
                    <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Ticket Code Display -->
            <div style="margin-bottom: 20px; padding: 12px; background-color: var(--color-page-bg); border: 1px solid var(--color-border-subtle);">
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px;">Ticket Code</div>
                <code style="font-size: 14px; font-weight: 600; color: var(--color-text-heading);">
                    {{ $registration->ticket_code }}
                </code>
            </div>

            <!-- Form Actions -->
            <div style="display: flex; gap: 12px; padding-top: 8px; border-top: 1px solid var(--color-border-subtle);">
                <x-button type="submit" variant="primary" style="flex: 1;">
                    <i class='bx bx-check'></i>
                    <span>Update Registration</span>
                </x-button>
                <x-button type="button" variant="secondary" href="{{ route('registrations.show', $registration) }}">
                    Cancel
                </x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection