@extends('layout.main')

@section('title', 'Edit Attendance Log')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Edit Attendance Log</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('attendance.update', $attendance) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="registration_id" class="form-label">Registration</label>
                        <select class="form-select" id="registration_id" name="registration_id" required>
                            <option value="">Select Registration</option>
                            @foreach($registrations as $registration)
                                <option value="{{ $registration->id }}" {{ old('registration_id', $attendance->registration_id) == $registration->id ? 'selected' : '' }}>
                                    {{ $registration->event->title }} - {{ $registration->user->name }} ({{ $registration->ticket_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="timestamp" class="form-label">Timestamp</label>
                        <input type="datetime-local" class="form-control" id="timestamp" name="timestamp" value="{{ old('timestamp', $attendance->timestamp->format('Y-m-d\TH:i')) }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Log</button>
                    <a href="{{ route('attendance.show', $attendance) }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection