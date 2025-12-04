@extends('layout.main')

@section('title', 'Register for Event - EventOps')

@section('content')
@php
    $user = auth()->user();
    $isStudent = $user->role === 'student';
@endphp

<div style="max-width: 600px; margin: 0 auto;">
    <!-- Page Header -->
    <div style="margin-bottom: 24px;">
        <h1 style="margin-bottom: 4px;">{{ $isStudent ? 'Register for Event' : 'Create Registration' }}</h1>
        <p class="text-muted" style="font-size: 14px;">
            {{ $isStudent ? 'Select an event to register' : 'Create a new event registration' }}
        </p>
    </div>

    <!-- Registration Form Card -->
    <x-card>
        <form method="POST" action="{{ route('registrations.store') }}">
            @csrf
            
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
                        @php
                            $registrationCount = $event->registrations->count();
                            $isFull = $registrationCount >= $event->capacity;
                            $spotsLeft = $event->capacity - $registrationCount;
                        @endphp
                        <option 
                            value="{{ $event->id }}" 
                            {{ old('event_id') == $event->id ? 'selected' : '' }}
                            {{ $isFull ? 'disabled' : '' }}
                        >
                            {{ $event->title }} - {{ $event->date->format('M d, Y') }}
                            @if($isFull)
                                (FULL)
                            @elseif($spotsLeft <= 5)
                                ({{ $spotsLeft }} spots left)
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('event_id')
                    <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                @enderror
            </div>

            @if(!$isStudent)
                <!-- User Selection (Admin only) -->
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
                        @foreach($users as $userOption)
                            <option 
                                value="{{ $userOption->id }}" 
                                {{ old('user_id', $user->id) == $userOption->id ? 'selected' : '' }}
                            >
                                {{ $userOption->name }} ({{ $userOption->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                    @enderror
                </div>
            @else
                <!-- Hidden user_id for students -->
                <input type="hidden" name="user_id" value="{{ $user->id }}">
            @endif

            <!-- Status (hidden for students, default to registered) -->
            @if($isStudent)
                <input type="hidden" name="status" value="registered">
            @else
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
                        <option value="registered" {{ old('status', 'registered') === 'registered' ? 'selected' : '' }}>Registered</option>
                        <option value="attended" {{ old('status') === 'attended' ? 'selected' : '' }}>Attended</option>
                    </select>
                </div>
            @endif

            <!-- Form Actions -->
            <div style="display: flex; gap: 12px; padding-top: 8px;">
                <x-button type="submit" variant="primary" style="flex: 1;">
                    <i class='bx bx-check'></i>
                    <span>{{ $isStudent ? 'Register' : 'Create Registration' }}</span>
                </x-button>
                <x-button type="button" variant="secondary" href="{{ route('registrations.index') }}">
                    Cancel
                </x-button>
            </div>
        </form>
    </x-card>

    <!-- Info Card for Students -->
    @if($isStudent)
        <div style="margin-top: 16px; padding: 16px; background-color: var(--color-info-bg); border: 1px solid var(--color-info-600); color: var(--color-info-text); font-size: 14px;">
            <div style="display: flex; gap: 12px;">
                <i class='bx bx-info-circle' style="font-size: 20px; flex-shrink: 0;"></i>
                <div>
                    <strong style="display: block; margin-bottom: 4px;">Registration Information</strong>
                    <ul style="margin: 0; padding-left: 20px;">
                        <li>You'll receive a unique ticket code after registration</li>
                        <li>Present your ticket code at the event for check-in</li>
                        <li>Some events have limited capacity - register early!</li>
                    </ul>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    /* Custom select dropdown styling */
    select.input {
        cursor: pointer;
    }
    
    select.input:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    select.input option:disabled {
        color: var(--color-text-muted);
    }
</style>
@endpush
@endsection