@extends('layout.main')

@section('title', 'Create Event')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Create Event</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('events.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="club_id" class="form-label">Club</label>
                        <select class="form-select" id="club_id" name="club_id" required>
                            <option value="">Select Club</option>
                            @foreach($clubs as $club)
                                <option value="{{ $club->id }}" {{ old('club_id') == $club->id ? 'selected' : '' }}>
                                    {{ $club->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="venue" class="form-label">Venue</label>
                        <input type="text" class="form-control" id="venue" name="venue" value="{{ old('venue') }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ old('date') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="time_start" class="form-label">Start Time</label>
                                <input type="time" class="form-control" id="time_start" name="time_start" value="{{ old('time_start') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="time_end" class="form-label">End Time</label>
                                <input type="time" class="form-control" id="time_end" name="time_end" value="{{ old('time_end') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="capacity" class="form-label">Capacity</label>
                        <input type="number" class="form-control" id="capacity" name="capacity" value="{{ old('capacity', 100) }}" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Event</button>
                    <a href="{{ route('events.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection