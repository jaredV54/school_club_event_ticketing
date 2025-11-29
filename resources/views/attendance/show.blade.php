@extends('layout.main')

@section('title', 'Attendance Log Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Attendance Log Details</h4>
                <a href="{{ route('attendance.edit', $attendance) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> Edit
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>ID:</strong> {{ $attendance->id }}</p>
                        <p><strong>Event:</strong> {{ $attendance->registration->event->title }}</p>
                        <p><strong>User:</strong> {{ $attendance->registration->user->name }} ({{ $attendance->registration->user->email }})</p>
                        <p><strong>Ticket Code:</strong> <code>{{ $attendance->registration->ticket_code }}</code></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Timestamp:</strong> {{ $attendance->timestamp->format('M d, Y H:i:s') }}</p>
                        <p><strong>Created At:</strong> {{ $attendance->created_at->format('M d, Y H:i') }}</p>
                        <p><strong>Updated At:</strong> {{ $attendance->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Back to Attendance Logs</a>
            </div>
        </div>
    </div>
</div>
@endsection