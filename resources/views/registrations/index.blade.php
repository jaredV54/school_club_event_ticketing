@extends('layout.main')

@section('title', 'Event Registrations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Event Registrations</h1>
    <a href="{{ route('registrations.create') }}" class="btn btn-primary">
        <i class='bx bx-plus'></i> Create Registration
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Event</th>
                        <th>User</th>
                        <th>Ticket Code</th>
                        <th>Status</th>
                        <th>Registered At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $registration)
                        <tr>
                            <td>{{ $registration->id }}</td>
                            <td>{{ $registration->event->title }}</td>
                            <td>{{ $registration->user->name }}</td>
                            <td><code>{{ $registration->ticket_code }}</code></td>
                            <td>
                                <span class="badge">{{ ucfirst($registration->status) }}</span>
                            </td>
                            <td>{{ $registration->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('registrations.show', $registration) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                        <i class='bx bx-show'></i>
                                    </a>
                                    <a href="{{ route('registrations.edit', $registration) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class='bx bx-edit'></i>
                                    </a>
                                    <form method="POST" action="{{ route('registrations.destroy', $registration) }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete registration?')">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <p class="text-muted">No registrations found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection