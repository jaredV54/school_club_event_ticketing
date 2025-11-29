@extends('layout.main')

@section('title', auth()->user()->role === 'student' ? 'My Attendance' : 'Attendance Logs')

@section('content')
@php $user = auth()->user(); @endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>{{ $user->role === 'student' ? 'My Attendance History' : 'Attendance Logs' }}</h1>
    @if($user->role !== 'student')
        <a href="{{ route('attendance.create') }}" class="btn btn-primary">
            <i class='bx bx-plus'></i> Create Log
        </a>
    @endif
</div>

@if($user->role === 'student')
    <!-- Student View - Simple attendance history -->
    <div class="row">
        @forelse($logs as $log)
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">{{ $log->registration->event->title }}</h6>
                        <p class="card-text">
                            <small class="text-muted">
                                <i class='bx bx-calendar'></i> {{ $log->timestamp->format('M d, Y') }}<br>
                                <i class='bx bx-time'></i> {{ $log->timestamp->format('H:i:s') }}<br>
                                <i class='bx bx-building'></i> {{ $log->registration->event->club->name }}
                            </small>
                        </p>
                        <span class="badge bg-success">Attended</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class='bx bx-calendar-x fs-1 text-muted mb-3'></i>
                        <h5 class="text-muted">No Attendance Records</h5>
                        <p class="text-muted">You haven't attended any events yet.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
@else
    <!-- Admin/Officer View - Full management table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Event</th>
                            <th>User</th>
                            <th>Timestamp</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->id }}</td>
                                <td>{{ $log->registration->event->title }}</td>
                                <td>{{ $log->registration->user->name }}</td>
                                <td>{{ $log->timestamp->format('M d, Y H:i:s') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('attendance.show', $log) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                            <i class='bx bx-show'></i>
                                        </a>
                                        <a href="{{ route('attendance.edit', $log) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                            <i class='bx bx-edit'></i>
                                        </a>
                                        <form method="POST" action="{{ route('attendance.destroy', $log) }}" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete log?')">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <p class="text-muted">No attendance logs found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection