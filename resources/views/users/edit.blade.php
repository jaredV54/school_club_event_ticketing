@extends('layout.main')

@section('title', 'Edit User')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Edit User</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password (leave blank to keep current)</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Student</option>
                            <option value="officer" {{ old('role', $user->role) === 'officer' ? 'selected' : '' }}>Officer</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <div class="mb-3" id="club-field" style="display: {{ old('role', $user->role) === 'officer' ? 'block' : 'none' }};">
                        <label for="club_id" class="form-label">Club</label>
                        <select class="form-select" id="club_id" name="club_id">
                            <option value="">Select Club</option>
                            @foreach(\App\Models\Club::all() as $club)
                                <option value="{{ $club->id }}" {{ old('club_id', $user->club_id) == $club->id ? 'selected' : '' }}>{{ $club->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update User</button>
                    <a href="{{ route('users.show', $user) }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('role').addEventListener('change', function() {
        const clubField = document.getElementById('club-field');
        if (this.value === 'officer') {
            clubField.style.display = 'block';
            document.getElementById('club_id').required = true;
        } else {
            clubField.style.display = 'none';
            document.getElementById('club_id').required = false;
        }
    });

    // Trigger on page load if officer is selected
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        if (roleSelect.value === 'officer') {
            document.getElementById('club-field').style.display = 'block';
            document.getElementById('club_id').required = true;
        }
    });
</script>
@endsection