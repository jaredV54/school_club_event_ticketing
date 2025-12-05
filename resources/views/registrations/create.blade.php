@extends('layout.main')

@section('title', 'Register for Event - EventOps')

@section('content')
@php
    $user = auth()->user();
    $isStudent = $user->role === 'student';
@endphp

<div style="max-width: 600px; margin: 0 auto;">
    <!-- Page Header -->
    <div style="margin-bottom: 24px;">
        <h1 style="margin-bottom: 4px;">{{ $isStudent ? 'Register for Event' : 'Create Registration' }}</h1>
        <p class="text-muted" style="font-size: 14px;">
            {{ $isStudent ? 'Select an event to register' : 'Create a new event registration' }}
        </p>
    </div>

    <!-- Registration Form Card -->
    <x-card>
        <form method="POST" action="{{ route('registrations.store') }}">
            @csrf
            
            <!-- Event Selection -->
            <div style="margin-bottom: 20px;">
                <label for="event_id" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Event <span style="color: var(--color-danger-600);">*</span>
                </label>
                <select 
                    class="input" 
                    id="event_id" 
                    name="event_id" 
                    required
                    style="width: 100%; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; padding-right: 40px;"
                >
                    <option value="">Select an event</option>
                    @foreach($events as $event)
                        @php
                            $registrationCount = $event->registrations->count();
                            $isFull = $registrationCount >= $event->capacity;
                            $spotsLeft = $event->capacity - $registrationCount;
                        @endphp
                        <option 
                            value="{{ $event->id }}" 
                            {{ old('event_id') == $event->id ? 'selected' : '' }}
                            {{ $isFull ? 'disabled' : '' }}
                        >
                            {{ $event->title }} - {{ $event->date->format('M d, Y') }}
                            @if($isFull)
                                (FULL)
                            @elseif($spotsLeft <= 5)
                                ({{ $spotsLeft }} spots left)
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('event_id')
                    <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                @enderror
            </div>

            @if(!$isStudent)
                <!-- User Search by Email (Admin only) -->
                <div style="margin-bottom: 20px;">
                    <label for="email_search" style="display: flex; align-items: center; justify-content: space-between; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 8px;">
                        <span>
                            <i class='bx bx-search' style="margin-right: 6px;"></i>
                            Student Email <span style="color: var(--color-danger-600);">*</span>
                        </span>
                        <span id="email-status" class="search-status" style="font-size: 12px; font-weight: 400; color: var(--color-text-muted);">
                            <i class='bx bx-search' style="margin-right: 4px;"></i>
                            Ready
                        </span>
                    </label>
                    <div style="position: relative;">
                        <div style="position: relative;">
                            <input
                                type="email"
                                class="input"
                                id="email_search"
                                name="email_search"
                                placeholder="Search by student email..."
                                autocomplete="off"
                                style="padding-left: 40px; padding-right: 40px;"
                            >
                            <i class='bx bx-envelope' style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-text-muted); font-size: 16px;"></i>
                            <div id="email-loading" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); display: none;">
                                <i class='bx bx-loader-alt bx-spin' style="color: var(--color-text-muted); font-size: 16px;"></i>
                            </div>
                        </div>
                        <div id="email-suggestions" class="search-suggestions enhanced-search-suggestions" style="display: none; max-height: 250px; overflow-y: auto; border: 1px solid #e5e7eb; background-color: #ffffff; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
                            <!-- Suggestions will appear here -->
                        </div>
                    </div>

                    <!-- Selected User Display -->
                    <div id="selected-user" style="margin-top: 12px; padding: 12px; border: 2px solid var(--color-primary-600); display: none;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 32px; height: 32px; background-color: var(--color-primary-600); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class='bx bx-check' style="color: white; font-size: 16px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 14px; font-weight: 600; color: var(--color-text-heading);">
                                    <span id="selected-user-name"></span>
                                </div>
                                <div style="font-size: 12px; color: var(--color-text-muted);">
                                    <i class='bx bx-envelope' style="margin-right: 4px;"></i>
                                    <span id="selected-user-email"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="user_id" name="user_id">

                    @error('user_id')
                        <p style="margin-top: 8px; font-size: 12px; color: var(--color-danger-600); display: flex; align-items: center; gap: 4px;">
                            <i class='bx bx-error-circle'></i>
                            {{ $message }}
                        </p>
                    @enderror
                    <p style="margin-top: 8px; font-size: 12px; color: var(--color-text-muted); display: flex; align-items: center; gap: 4px;">
                        <i class='bx bx-info-circle'></i>
                        Start typing an email address to search for registered students
                    </p>
                </div>
            @else
                <!-- Hidden user_id for students -->
                <input type="hidden" name="user_id" value="{{ $user->id }}">
            @endif

            <!-- Status (hidden for students, default to registered) -->
            @if($isStudent)
                <input type="hidden" name="status" value="registered">
            @else
                <div style="margin-bottom: 20px;">
                    <label for="status" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                        Status <span style="color: var(--color-danger-600);">*</span>
                    </label>
                    <select 
                        class="input" 
                        id="status" 
                        name="status" 
                        required
                        style="width: 100%; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; padding-right: 40px;"
                    >
                        <option value="registered" {{ old('status', 'registered') === 'registered' ? 'selected' : '' }}>Registered</option>
                        <option value="attended" {{ old('status') === 'attended' ? 'selected' : '' }}>Attended</option>
                    </select>
                </div>
            @endif

            <!-- Form Actions -->
            <div style="display: flex; gap: 12px; padding-top: 8px;">
                <x-button type="submit" variant="primary" style="flex: 1;">
                    <i class='bx bx-check'></i>
                    <span>{{ $isStudent ? 'Register' : 'Create Registration' }}</span>
                </x-button>
                <x-button type="button" variant="secondary" href="{{ route('registrations.index') }}">
                    Cancel
                </x-button>
            </div>
        </form>
    </x-card>

    <!-- Info Card for Students -->
    @if($isStudent)
        <div style="margin-top: 16px; padding: 16px; background-color: var(--color-info-bg); border: 1px solid var(--color-info-600); color: var(--color-info-text); font-size: 14px;">
            <div style="display: flex; gap: 12px;">
                <i class='bx bx-info-circle' style="font-size: 20px; flex-shrink: 0;"></i>
                <div>
                    <strong style="display: block; margin-bottom: 4px;">Registration Information</strong>
                    <ul style="margin: 0; padding-left: 20px;">
                        <li>You'll receive a unique ticket code after registration</li>
                        <li>Present your ticket code at the event for check-in</li>
                        <li>Some events have limited capacity - register early!</li>
                    </ul>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    /* Custom select dropdown styling */
    select.input {
        cursor: pointer;
    }

    select.input:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    select.input option:disabled {
        color: var(--color-text-muted);
    }
</style>
@endpush

<script>
// User search by email with suggestions for registration creation
let userSearchTimeout;
let selectedUserIndex = -1;

const emailInput = document.getElementById('email_search');
const emailSuggestions = document.getElementById('email-suggestions');

emailInput?.addEventListener('input', function() {
    clearTimeout(userSearchTimeout);
    const emailQuery = this.value.trim();
    selectedUserIndex = -1;

    if (emailQuery.length >= 3) {
        userSearchTimeout = setTimeout(() => searchUserSuggestions(emailQuery), 300);
    } else {
        hideEmailSuggestions();
        hideSelectedUser();
    }
});

emailInput?.addEventListener('keydown', function(e) {
    if (!emailSuggestions.style.display || emailSuggestions.style.display === 'none') return;

    const items = emailSuggestions.querySelectorAll('.search-suggestion-item');

    if (e.key === 'ArrowDown') {
        e.preventDefault();
        selectedUserIndex = Math.min(selectedUserIndex + 1, items.length - 1);
        updateUserSelection(items);
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        selectedUserIndex = Math.max(selectedUserIndex - 1, -1);
        updateUserSelection(items);
    } else if (e.key === 'Enter' && selectedUserIndex >= 0) {
        e.preventDefault();
        items[selectedUserIndex].click();
    } else if (e.key === 'Escape') {
        hideEmailSuggestions();
    }
});

emailInput?.addEventListener('blur', function() {
    // Delay hiding to allow click on suggestions
    setTimeout(() => hideEmailSuggestions(), 150);
});

function searchUserSuggestions(emailQuery) {
    // Show loading state
    document.getElementById('email-loading').style.display = 'block';
    updateEmailStatus('searching', 'Searching...');

    fetch(`/api/users/suggestions?email=${encodeURIComponent(emailQuery)}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || window.Laravel?.csrfToken || '',
        },
        credentials: 'same-origin',
    })
    .then(response => response.json())
    .then(data => {
        // Hide loading state
        document.getElementById('email-loading').style.display = 'none';

        if (data.success && data.suggestions.length > 0) {
            showEmailSuggestions(data.suggestions, emailQuery);
            updateEmailStatus('found', `${data.suggestions.length} found`);
        } else {
            hideEmailSuggestions();
            updateEmailStatus('not-found', 'No results');
        }
    })
    .catch(error => {
        console.error('User suggestions error:', error);
        document.getElementById('email-loading').style.display = 'none';
        hideEmailSuggestions();
        updateEmailStatus('error', 'Error');
    });
}

function showEmailSuggestions(suggestions, query) {
    emailSuggestions.innerHTML = '';

    suggestions.forEach((suggestion, index) => {
        const item = document.createElement('div');
        item.style.cssText = `
            padding: 12px 16px;
            border-bottom: 1px solid #e5e7eb;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            background-color: #ffffff;
            color: #1f2937;
        `;

        item.addEventListener('mouseenter', () => {
            item.style.background = '#f7f8fa';
        });

        item.addEventListener('mouseleave', () => {
            item.style.background = '#ffffff';
        });

        const roleIcon = suggestion.role === 'officer' ? 'bx-building' : 'bx-user';

        item.innerHTML = `
            <div style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; background-color: #eff8ff; color: #175cd3;">
                <i class='bx bx-envelope'></i>
            </div>
            <div style="flex: 1; min-width: 0;">
                <div style="font-weight: 600; color: #111827; font-size: 14px; margin-bottom: 2px;">${highlightMatch(suggestion.email, query)}</div>
                <div style="font-size: 12px; color: #667085; line-height: 1.3;">
                    <i class='bx bx-user' style="margin-right: 4px;"></i>
                    ${suggestion.name} •
                    <i class='bx ${roleIcon}' style="margin: 0 4px;"></i>
                    ${suggestion.role}
                </div>
            </div>
        `;
        item.addEventListener('click', () => selectUserSuggestion(suggestion));
        emailSuggestions.appendChild(item);
    });

    emailSuggestions.style.display = 'block';
}

function hideEmailSuggestions() {
    emailSuggestions.style.display = 'none';
    selectedUserIndex = -1;
    updateEmailStatus('ready', 'Ready');
}

function updateEmailStatus(status, text) {
    const statusElement = document.getElementById('email-status');
    if (!statusElement) return;

    let icon = 'bx-search';
    let color = 'var(--color-text-muted)';

    switch (status) {
        case 'searching':
            icon = 'bx-loader-alt bx-spin';
            color = 'var(--color-primary-600)';
            break;
        case 'found':
            icon = 'bx-check-circle';
            color = 'var(--color-success-600)';
            break;
        case 'not-found':
            icon = 'bx-x-circle';
            color = 'var(--color-text-muted)';
            break;
        case 'error':
            icon = 'bx-error-circle';
            color = 'var(--color-danger-600)';
            break;
        case 'ready':
        default:
            icon = 'bx-search';
            color = 'var(--color-text-muted)';
            break;
    }

    statusElement.innerHTML = `<i class='bx ${icon}' style="margin-right: 4px;"></i>${text}`;
    statusElement.style.color = color;
}

function updateUserSelection(items) {
    items.forEach((item, index) => {
        item.classList.toggle('active', index === selectedUserIndex);
    });
}

function selectUserSuggestion(suggestion) {
    emailInput.value = suggestion.email;
    hideEmailSuggestions();

    // Show selected user info and set hidden field
    showSelectedUser(suggestion);
}

function showSelectedUser(user) {
    const selectedUserDiv = document.getElementById('selected-user');
    const userNameSpan = document.getElementById('selected-user-name');
    const userEmailSpan = document.getElementById('selected-user-email');
    const userIdInput = document.getElementById('user_id');

    userNameSpan.textContent = user.name;
    userEmailSpan.textContent = user.email;
    userIdInput.value = user.id;

    selectedUserDiv.style.display = 'block';
}

function hideSelectedUser() {
    document.getElementById('selected-user').style.display = 'none';
    document.getElementById('user_id').value = '';
}

function highlightMatch(text, query) {
    const regex = new RegExp(`(${query})`, 'gi');
    return text.replace(regex, '<span class="suggestion-highlight">$1</span>');
}
</script>
@endsection