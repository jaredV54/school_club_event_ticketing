@extends('layout.main')

@section('title', 'Create Registration')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Create Registration</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('registrations.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="event_id" class="form-label">Event</label>
                        <select class="form-select" id="event_id" name="event_id" required>
                            <option value="">Select Event</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                    {{ $event->title }} ({{ $event->date->format('M d, Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="user_id" class="form-label">User</label>
                        <select class="form-select" id="user_id" name="user_id" required>
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="registered" {{ old('status', 'registered') === 'registered' ? 'selected' : '' }}>Registered</option>
                            <option value="attended" {{ old('status') === 'attended' ? 'selected' : '' }}>Attended</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Registration</button>
                    <a href="{{ route('registrations.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection