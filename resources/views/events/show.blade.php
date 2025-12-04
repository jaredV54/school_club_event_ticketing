@extends('layout.main')

@section('title', 'Event Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Event Details</h4>
                @if(auth()->user()->role !== 'student')
                    <a href="{{ route('events.edit', $event) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                @endif
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>ID:</strong> {{ $event->id }}</p>
                        <p><strong>Title:</strong> {{ $event->title }}</p>
                        <p><strong>Club:</strong> {{ $event->club ? $event->club->name : 'No Club' }}</p>
                        <p><strong>Venue:</strong> {{ $event->venue }}</p>
                        <p><strong>Capacity:</strong> {{ $event->capacity }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Date:</strong> {{ $event->date->format('M d, Y') }}</p>
                        <p><strong>Time:</strong> {{ $event->time_start }} - {{ $event->time_end }}</p>
                        <p><strong>Created At:</strong> {{ $event->created_at->format('M d, Y H:i') }}</p>
                        <p><strong>Updated At:</strong> {{ $event->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
                <div class="mb-3">
                    <strong>Description:</strong>
                    <p>{{ $event->description }}</p>
                </div>
                <hr>
                <h5>Registrations ({{ $event->registrations->count() }})</h5>
                @if($event->registrations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Ticket Code</th>
                                    <th>Status</th>
                                    <th>Registered At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($event->registrations as $registration)
                                    <tr>
                                        <td>{{ $registration->user->name }}</td>
                                        <td>{{ $registration->user->email }}</td>
                                        <td><code>{{ $registration->ticket_code }}</code></td>
                                        <td>
                                            <span class="badge bg-{{ $registration->status === 'attended' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($registration->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $registration->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No registrations yet.</p>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('events.index') }}" class="btn btn-secondary">Back to Events</a>
            </div>
        </div>
    </div>
</div>
@endsection