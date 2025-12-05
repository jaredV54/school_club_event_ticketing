@extends('layout.main')

@section('title', 'Create Club - EventOps')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <!-- Page Header -->
    <div style="margin-bottom: 24px;">
        <h1 style="margin-bottom: 4px;">Create Club</h1>
        <p class="text-muted" style="font-size: 14px;">Fill in the details to create a new club</p>
    </div>

    <!-- Club Form Card -->
    <x-card>
        <form method="POST" action="{{ route('clubs.store') }}">
            @csrf

            <!-- Name -->
            <div style="margin-bottom: 20px;">
                <label for="name" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Club Name <span style="color: var(--color-danger-600);">*</span>
                </label>
                <input
                    type="text"
                    class="input"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Enter club name"
                    required
                >
                @error('name')
                    <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div style="margin-bottom: 20px;">
                <label for="description" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Description
                </label>
                <textarea
                    class="input"
                    id="description"
                    name="description"
                    rows="4"
                    placeholder="Describe your club"
                    style="resize: vertical; min-height: 100px;"
                >{{ old('description') }}</textarea>
                @error('description')
                    <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div style="display: flex; gap: 12px; padding-top: 8px; border-top: 1px solid var(--color-border-subtle);">
                <x-button type="submit" variant="primary" style="flex: 1;">
                    <i class='bx bx-check'></i>
                    <span>Create Club</span>
                </x-button>
                <x-button type="button" variant="secondary" href="{{ route('clubs.index') }}">
                    Cancel
                </x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection