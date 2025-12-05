@extends('layout.main')

@section('title', 'Edit Attendance Log - EventOps')

@section('content')
<div style="max-width: 700px; margin: 0 auto;">
    <!-- Page Header -->
    <div style="margin-bottom: 24px;">
        <h1 style="margin-bottom: 4px;">Edit Attendance Log</h1>
        <p class="text-muted" style="font-size: 14px;">Update attendance log details</p>
    </div>

    <!-- Attendance Form Card -->
    <x-card>
        <form method="POST" action="{{ route('attendance.update', $attendance) }}">
            @csrf
            @method('PUT')

            <!-- Registration Selection -->
            <div style="margin-bottom: 20px;">
                <label for="registration_id" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Registration <span style="color: var(--color-danger-600);">*</span>
                </label>
                <select
                    class="input"
                    id="registration_id"
                    name="registration_id"
                    required
                    style="width: 100%; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; padding-right: 40px;"
                >
                    <option value="">Select a registration</option>
                    @foreach($registrations as $registration)
                        <option value="{{ $registration->id }}" {{ old('registration_id', $attendance->registration_id) == $registration->id ? 'selected' : '' }}>
                            {{ $registration->event->title }} - {{ $registration->user->name }} ({{ $registration->ticket_code }})
                        </option>
                    @endforeach
                </select>
                @error('registration_id')
                    <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                @enderror
            </div>

            <!-- Timestamp -->
            <div style="margin-bottom: 20px;">
                <label for="timestamp" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Check-in Timestamp <span style="color: var(--color-danger-600);">*</span>
                </label>
                <input
                    type="datetime-local"
                    class="input"
                    id="timestamp"
                    name="timestamp"
                    value="{{ old('timestamp', $attendance->timestamp->format('Y-m-d\TH:i')) }}"
                    required
                >
                @error('timestamp')
                    <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div style="display: flex; gap: 12px; padding-top: 8px; border-top: 1px solid var(--color-border-subtle);">
                <x-button type="submit" variant="primary" style="flex: 1;">
                    <i class='bx bx-check'></i>
                    <span>Update Log</span>
                </x-button>
                <x-button type="button" variant="secondary" href="{{ route('attendance.show', $attendance) }}">
                    Cancel
                </x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection