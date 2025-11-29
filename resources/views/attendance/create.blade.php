@extends('layout.main')

@section('title', 'Create Attendance Log')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Create Attendance Log</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('attendance.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="registration_id" class="form-label">Registration</label>
                        <select class="form-select" id="registration_id" name="registration_id" required>
                            <option value="">Select Registration</option>
                            @foreach($registrations as $registration)
                                <option value="{{ $registration->id }}" {{ old('registration_id') == $registration->id ? 'selected' : '' }}>
                                    {{ $registration->event->title }} - {{ $registration->user->name }} ({{ $registration->ticket_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="timestamp" class="form-label">Timestamp</label>
                        <input type="datetime-local" class="form-control" id="timestamp" name="timestamp" value="{{ old('timestamp', now()->format('Y-m-d\TH:i')) }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Log</button>
                    <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection