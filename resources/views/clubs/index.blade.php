@extends('layout.main')

@section('title', 'Clubs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Clubs</h1>
    <a href="{{ route('clubs.create') }}" class="btn btn-primary">
        <i class='bx bx-plus'></i> Create Club
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Events</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clubs as $club)
                        <tr>
                            <td>{{ $club->name }}</td>
                            <td>{{ $club->description ?: 'No description' }}</td>
                            <td>{{ $club->events->count() }}</td>
                            <td>{{ $club->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('clubs.show', $club) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                        <i class='bx bx-show'></i>
                                    </a>
                                    <a href="{{ route('clubs.edit', $club) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class='bx bx-edit'></i>
                                    </a>
                                    <form method="POST" action="{{ route('clubs.destroy', $club) }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete club?')">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <p class="text-muted">No clubs found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection