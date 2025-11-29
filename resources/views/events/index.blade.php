@extends('layout.main')

@section('title', 'Events')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Events</h1>
    @if(auth()->user()->role !== 'student')
        <a href="{{ route('events.create') }}" class="btn btn-primary">
            <i class='bx bx-plus'></i> Create Event
        </a>
    @endif
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Club</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Venue</th>
                        <th>Capacity</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td>{{ $event->title }}</td>
                            <td>{{ $event->club->name }}</td>
                            <td>{{ $event->date->format('M d, Y') }}</td>
                            <td>{{ $event->time_start }} - {{ $event->time_end }}</td>
                            <td>{{ $event->venue }}</td>
                            <td>{{ $event->capacity }}</td>
                            <td>{{ $event->registrations->count() }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                        <i class='bx bx-show'></i>
                                    </a>
                                    @if(auth()->user()->role !== 'student')
                                        <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                            <i class='bx bx-edit'></i>
                                        </a>
                                        <form method="POST" action="{{ route('events.destroy', $event) }}" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete event?')">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <p class="text-muted">No events found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection