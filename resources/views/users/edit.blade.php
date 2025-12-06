@extends('layout.main')

@section('title', 'Edit User - EventOps')

@section('content')
<div style="max-width: 700px; margin: 0 auto;">
    <!-- Page Header -->
    <div style="margin-bottom: 24px;">
        <h1 style="margin-bottom: 4px;">Edit User</h1>
        <p class="text-muted" style="font-size: 14px;">Update user details</p>
    </div>

    <!-- User Form Card -->
    <x-card>
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div style="margin-bottom: 20px;">
                <label for="name" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Name <span style="color: var(--color-danger-600);">*</span>
                </label>
                <input
                    type="text"
                    class="input"
                    id="name"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    placeholder="Enter full name"
                    required
                >
                @error('name')
                    <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div style="margin-bottom: 20px;">
                <label for="email" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Email <span style="color: var(--color-danger-600);">*</span>
                </label>
                <input
                    type="email"
                    class="input"
                    id="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    placeholder="Enter email address"
                    required
                >
                @error('email')
                    <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div style="margin-bottom: 20px;">
                <label for="password" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Password
                </label>
                <input
                    type="password"
                    class="input"
                    id="password"
                    name="password"
                    placeholder="Leave blank to keep current password"
                >
                @error('password')
                    <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div style="margin-bottom: 20px;">
                <label for="password_confirmation" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Confirm Password
                </label>
                <input
                    type="password"
                    class="input"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Confirm new password"
                >
                @error('password_confirmation')
                    <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div style="margin-bottom: 20px;">
                <label for="role" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Role <span style="color: var(--color-danger-600);">*</span>
                </label>
                <select
                    class="input"
                    id="role"
                    name="role"
                    required
                    style="width: 100%; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; padding-right: 40px;"
                >
                    <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Student</option>
                    <option value="officer" {{ old('role', $user->role) === 'officer' ? 'selected' : '' }}>Officer</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                    <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                @enderror
            </div>

            <!-- Club -->
            <div style="margin-bottom: 20px;">
                <label for="club_id" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Club
                </label>
                <select
                    class="input"
                    id="club_id"
                    name="club_id"
                    style="width: 100%; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; padding-right: 40px;"
                >
                    <option value="">No club</option>
                    @foreach(\App\Models\Club::all() as $club)
                        <option value="{{ $club->id }}" {{ old('club_id', $user->club_id) == $club->id ? 'selected' : '' }}>{{ $club->name }}</option>
                    @endforeach
                </select>
                @error('club_id')
                    <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div style="display: flex; gap: 12px; padding-top: 8px; border-top: 1px solid var(--color-border-subtle);">
                <x-button type="submit" variant="primary" style="flex: 1;">
                    <i class='bx bx-check'></i>
                    <span>Update User</span>
                </x-button>
                <x-button type="button" variant="secondary" href="{{ route('users.show', $user) }}">
                    Cancel
                </x-button>
            </div>
        </form>
    </x-card>
</div>

@endsection