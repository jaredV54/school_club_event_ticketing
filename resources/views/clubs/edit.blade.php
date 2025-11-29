@extends('layout.main')

@section('title', 'Edit Club')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Edit Club</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('clubs.update', $club) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $club->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $club->description) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Club</button>
                    <a href="{{ route('clubs.show', $club) }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection