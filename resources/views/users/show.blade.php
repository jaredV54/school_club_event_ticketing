@extends('layout.main')

@section('title', 'User Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>User Details</h4>
                <a href="{{ route('users.edit', $user) }}" class="btn btn-light btn-sm">
                    <i class="bi bi-pencil"></i> Edit
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>ID:</strong> {{ $user->id }}</p>
                        <p><strong>Name:</strong> {{ $user->name }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>Role:</strong>
                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'officer' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Created At:</strong> {{ $user->created_at->format('M d, Y H:i') }}</p>
                        <p><strong>Updated At:</strong> {{ $user->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
                <hr>
                <h5>Event Registrations</h5>
                @if($user->eventRegistrations->count() > 0)
                    <ul class="list-group">
                        @foreach($user->eventRegistrations as $registration)
                            <li class="list-group-item">
                                {{ $registration->event->title }} - {{ ucfirst($registration->status) }}
                                <small class="text-muted">({{ $registration->created_at->format('M d, Y') }})</small>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No registrations yet.</p>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to Users</a>
            </div>
        </div>
    </div>
</div>
@endsection