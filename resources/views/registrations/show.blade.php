@extends('layout.main')

@section('title', 'Registration Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Registration Details</h4>
                <a href="{{ route('registrations.edit', $registration) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> Edit
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>ID:</strong> {{ $registration->id }}</p>
                        <p><strong>Event:</strong> {{ $registration->event->title }}</p>
                        <p><strong>User:</strong> {{ $registration->user->name }} ({{ $registration->user->email }})</p>
                        <p><strong>Ticket Code:</strong> <code>{{ $registration->ticket_code }}</code></p>
                        <p><strong>Status:</strong>
                            <span class="badge bg-{{ $registration->status === 'attended' ? 'success' : 'secondary' }}">
                                {{ ucfirst($registration->status) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Registered At:</strong> {{ $registration->created_at->format('M d, Y H:i') }}</p>
                        <p><strong>Updated At:</strong> {{ $registration->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
                <hr>
                <h5>Attendance Logs</h5>
                @if($registration->attendanceLogs->count() > 0)
                    <ul class="list-group">
                        @foreach($registration->attendanceLogs as $log)
                            <li class="list-group-item">
                                Logged at: {{ $log->timestamp->format('M d, Y H:i:s') }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No attendance logs yet.</p>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('registrations.index') }}" class="btn btn-secondary">Back to Registrations</a>
            </div>
        </div>
    </div>
</div>
@endsection