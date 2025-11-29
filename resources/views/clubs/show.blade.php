@extends('layout.main')

@section('title', 'Club Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Club Details</h4>
                <a href="{{ route('clubs.edit', $club) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> Edit
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>ID:</strong> {{ $club->id }}</p>
                        <p><strong>Name:</strong> {{ $club->name }}</p>
                        <p><strong>Description:</strong> {{ $club->description ?: 'No description' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Created At:</strong> {{ $club->created_at->format('M d, Y H:i') }}</p>
                        <p><strong>Updated At:</strong> {{ $club->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
                <hr>
                <h5>Events ({{ $club->events->count() }})</h5>
                @if($club->events->count() > 0)
                    <ul class="list-group">
                        @foreach($club->events as $event)
                            <li class="list-group-item">
                                <strong>{{ $event->title }}</strong><br>
                                <small class="text-muted">{{ $event->date }} • {{ $event->venue }}</small>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No events yet.</p>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('clubs.index') }}" class="btn btn-secondary">Back to Clubs</a>
            </div>
        </div>
    </div>
</div>
@endsection