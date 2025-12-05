@extends('layout.main')

@section('title', 'Mark Attendance - EventOps')

@section('content')
<div style="max-width: 700px; margin: 0 auto;">
    <!-- Page Header -->
    <div style="margin-bottom: 24px;">
        <h1 style="margin-bottom: 4px;">Mark Attendance</h1>
        <p class="text-muted" style="font-size: 14px;">Record student attendance for an event</p>
    </div>

    <!-- Attendance Form Card -->
    <x-card>
        <form method="POST" action="{{ route('attendance.store') }}">
            @csrf
            
            <!-- Ticket Code Search with Suggestions -->
            <div style="margin-bottom: 20px;">
                <label for="ticket_code" style="display: flex; align-items: center; justify-content: space-between; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 8px;">
                    <span>
                        <i class='bx bx-receipt' style="margin-right: 6px;"></i>
                        Ticket Code <span style="color: var(--color-danger-600);">*</span>
                    </span>
                    <span id="ticket-status" class="search-status" style="font-size: 12px; font-weight: 400; color: var(--color-text-muted);">
                        <i class='bx bx-search' style="margin-right: 4px;"></i>
                        Ready
                    </span>
                </label>
                <div style="position: relative;">
                    <div style="position: relative;">
                        <input
                            type="text"
                            class="input"
                            id="ticket_code"
                            name="ticket_code"
                            value="{{ old('ticket_code') }}"
                            placeholder="Search by ticket code..."
                            required
                            autocomplete="off"
                            style="padding-left: 40px; padding-right: 40px;"
                        >
                        <i class='bx bx-receipt' style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-text-muted); font-size: 16px;"></i>
                        <div id="ticket-loading" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); display: none;">
                            <i class='bx bx-loader-alt bx-spin' style="color: var(--color-text-muted); font-size: 16px;"></i>
                        </div>
                    </div>
                    <div id="ticket-suggestions" class="search-suggestions enhanced-search-suggestions" style="display: none; max-height: 250px; overflow-y: auto; border: 1px solid #e5e7eb; background-color: #ffffff; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
                        <!-- Suggestions will appear here -->
                    </div>
                </div>
                @error('ticket_code')
                    <p style="margin-top: 8px; font-size: 12px; color: var(--color-danger-600); display: flex; align-items: center; gap: 4px;">
                        <i class='bx bx-error-circle'></i>
                        {{ $message }}
                    </p>
                @enderror
                <p style="margin-top: 8px; font-size: 12px; color: var(--color-text-muted); display: flex; align-items: center; gap: 4px;">
                    <i class='bx bx-info-circle'></i>
                    Search for registered students only (not already attended)
                </p>
            </div>

            <!-- Registration Details (shown when found) -->
            <div id="registration-details" style="margin-bottom: 20px; display: none;">
                <div style="border: 1px solid #e5e7eb; background-color: #ffffff;">
                    <div style="padding: 12px 16px; border-bottom: 1px solid #e5e7eb; background-color: #f7f8fa;">
                        <div style="font-size: 14px; font-weight: 600; color: #111827; display: flex; align-items: center; gap: 8px;">
                            <i class='bx bx-check-circle' style="color: #059669; font-size: 16px;"></i>
                            Registration Found
                        </div>
                    </div>
                    <div id="registration-info" style="padding: 16px;">
                        <!-- Registration details will be populated here -->
                    </div>
                </div>
                <input type="hidden" id="registration_id" name="registration_id">
            </div>

            <!-- Timestamp -->
            <div style="margin-bottom: 20px;">
                <label for="timestamp" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                    Check-in Time <span style="color: var(--color-danger-600);">*</span>
                </label>
                <input 
                    type="datetime-local" 
                    class="input" 
                    id="timestamp" 
                    name="timestamp" 
                    value="{{ old('timestamp', now()->format('Y-m-d\TH:i')) }}" 
                    required
                >
                @error('timestamp')
                    <p style="margin-top: 4px; font-size: 12px; color: var(--color-danger-600);">{{ $message }}</p>
                @enderror
                <p style="margin-top: 4px; font-size: 12px; color: var(--color-text-muted);">
                    Default is current date and time
                </p>
            </div>

            <!-- Form Actions -->
            <div style="display: flex; gap: 12px; padding-top: 8px; border-top: 1px solid var(--color-border-subtle);">
                <x-button type="submit" variant="primary" style="flex: 1;">
                    <i class='bx bx-check-circle'></i>
                    <span>Mark Attendance</span>
                </x-button>
                <x-button type="button" variant="secondary" href="{{ route('attendance.index') }}">
                    Cancel
                </x-button>
            </div>
        </form>
    </x-card>

    <!-- Info Card -->
    <div style="margin-top: 16px; padding: 16px; background-color: var(--color-info-bg); border: 1px solid var(--color-info-600); color: var(--color-info-text); font-size: 14px;">
        <div style="display: flex; gap: 12px;">
            <i class='bx bx-info-circle' style="font-size: 20px; flex-shrink: 0;"></i>
            <div>
                <strong style="display: block; margin-bottom: 4px;">Attendance Guidelines</strong>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Verify the student's ticket code before marking attendance</li>
                    <li>Ensure the timestamp reflects the actual check-in time</li>
                    <li>Attendance can only be marked once per registration</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
// Ticket code search with suggestions
let ticketSearchTimeout;
let selectedTicketIndex = -1;

const ticketInput = document.getElementById('ticket_code');
const ticketSuggestions = document.getElementById('ticket-suggestions');

ticketInput.addEventListener('input', function() {
    clearTimeout(ticketSearchTimeout);
    const query = this.value.trim();
    selectedTicketIndex = -1;

    if (query.length >= 2) {
        ticketSearchTimeout = setTimeout(() => searchTicketSuggestions(query), 300);
    } else {
        hideTicketSuggestions();
        hideRegistrationDetails();
    }
});

ticketInput.addEventListener('keydown', function(e) {
    if (!ticketSuggestions.style.display || ticketSuggestions.style.display === 'none') return;

    const items = ticketSuggestions.querySelectorAll('.search-suggestion-item');

    if (e.key === 'ArrowDown') {
        e.preventDefault();
        selectedTicketIndex = Math.min(selectedTicketIndex + 1, items.length - 1);
        updateTicketSelection(items);
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        selectedTicketIndex = Math.max(selectedTicketIndex - 1, -1);
        updateTicketSelection(items);
    } else if (e.key === 'Enter' && selectedTicketIndex >= 0) {
        e.preventDefault();
        items[selectedTicketIndex].click();
    } else if (e.key === 'Escape') {
        hideTicketSuggestions();
    }
});

ticketInput.addEventListener('blur', function() {
    // Delay hiding to allow click on suggestions
    setTimeout(() => {
        hideTicketSuggestions();
        const ticketCode = this.value.trim();
        if (ticketCode && !document.getElementById('registration_id').value) {
            // If no registration selected, try to find exact match
            searchRegistration(ticketCode);
        }
    }, 150);
});

function searchTicketSuggestions(query) {
    // Show loading state
    document.getElementById('ticket-loading').style.display = 'block';
    updateTicketStatus('searching', 'Searching...');

    fetch(`/api/registrations/suggestions?ticket_code=${encodeURIComponent(query)}`, {
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
        document.getElementById('ticket-loading').style.display = 'none';

        if (data.success && data.suggestions.length > 0) {
            showTicketSuggestions(data.suggestions, query);
            updateTicketStatus('found', `${data.suggestions.length} found`);
        } else {
            hideTicketSuggestions();
            updateTicketStatus('not-found', 'No results');
        }
    })
    .catch(error => {
        console.error('Suggestions error:', error);
        document.getElementById('ticket-loading').style.display = 'none';
        hideTicketSuggestions();
        updateTicketStatus('error', 'Error');
    });
}

function showTicketSuggestions(suggestions, query) {
    ticketSuggestions.innerHTML = '';

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

        const roleIcon = suggestion.student_role === 'officer' ? 'bx-building' : 'bx-user';

        item.innerHTML = `
            <div style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; background-color: #ecfdf5; color: #059669;">
                <i class='bx bx-receipt'></i>
            </div>
            <div style="flex: 1; min-width: 0;">
                <div style="font-weight: 600; color: #111827; font-size: 14px; margin-bottom: 2px;">${highlightMatch(suggestion.ticket_code, query)}</div>
                <div style="font-size: 12px; color: #667085; line-height: 1.3;">
                    <i class='bx bx-envelope' style="margin-right: 4px;"></i>
                    ${suggestion.student_email} •
                    <i class='bx ${roleIcon}' style="margin: 0 4px;"></i>
                    ${suggestion.student_role} •
                    <i class='bx bx-calendar-event' style="margin-right: 4px;"></i>
                    ${suggestion.event_title}
                </div>
            </div>
        `;
        item.addEventListener('click', () => selectTicketSuggestion(suggestion));
        ticketSuggestions.appendChild(item);
    });

    ticketSuggestions.style.display = 'block';
}

function hideTicketSuggestions() {
    ticketSuggestions.style.display = 'none';
    selectedTicketIndex = -1;
    updateTicketStatus('ready', 'Ready');
}

function updateTicketStatus(status, text) {
    const statusElement = document.getElementById('ticket-status');
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

function updateTicketSelection(items) {
    items.forEach((item, index) => {
        item.classList.toggle('active', index === selectedTicketIndex);
    });
}

function selectTicketSuggestion(suggestion) {
    ticketInput.value = suggestion.ticket_code;
    hideTicketSuggestions();
    showRegistrationDetails(suggestion);
}

function highlightMatch(text, query) {
    const regex = new RegExp(`(${query})`, 'gi');
    return text.replace(regex, '<span class="suggestion-highlight">$1</span>');
}

function searchRegistration(ticketCode) {
    fetch(`/api/registrations/search?ticket_code=${encodeURIComponent(ticketCode)}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.registration) {
            showRegistrationDetails(data.registration);
        } else {
            hideRegistrationDetails();
            if (data.message) {
                showError(data.message);
            }
        }
    })
    .catch(error => {
        console.error('Search error:', error);
        hideRegistrationDetails();
        showError('An error occurred while searching');
    });
}

function showRegistrationDetails(registration) {
    const detailsDiv = document.getElementById('registration-details');
    const infoDiv = document.getElementById('registration-info');
    const registrationIdInput = document.getElementById('registration_id');

    const statusText = registration.status === 'attended' ? 'Attended' : 'Registered';
    const statusColor = registration.status === 'attended' ? '#059669' : '#0891b2';

    infoDiv.innerHTML = `
        <div style="display: grid; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; background-color: #f7f8fa;">
                    <i class='bx bx-calendar-event' style="color: #667085; font-size: 14px;"></i>
                </div>
                <div>
                    <div style="font-size: 12px; color: #667085; font-weight: 500; margin-bottom: 2px;">EVENT</div>
                    <div style="font-size: 14px; color: #111827; font-weight: 500;">${registration.event_title}</div>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; background-color: #f7f8fa;">
                    <i class='bx bx-user' style="color: #667085; font-size: 14px;"></i>
                </div>
                <div>
                    <div style="font-size: 12px; color: #667085; font-weight: 500; margin-bottom: 2px;">STUDENT</div>
                    <div style="font-size: 14px; color: #111827; font-weight: 500;">${registration.student_name}</div>
                    <div style="font-size: 12px; color: #667085;">${registration.student_email}</div>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; background-color: #f7f8fa;">
                    <i class='bx bx-info-circle' style="color: #667085; font-size: 14px;"></i>
                </div>
                <div>
                    <div style="font-size: 12px; color: #667085; font-weight: 500; margin-bottom: 2px;">STATUS</div>
                    <div style="font-size: 14px; font-weight: 500; color: ${statusColor};">${statusText}</div>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; background-color: #f7f8fa;">
                    <i class='bx bx-time' style="color: #667085; font-size: 14px;"></i>
                </div>
                <div>
                    <div style="font-size: 12px; color: #667085; font-weight: 500; margin-bottom: 2px;">REGISTERED</div>
                    <div style="font-size: 14px; color: #111827; font-weight: 500;">${registration.registered_at}</div>
                </div>
            </div>
        </div>
    `;

    registrationIdInput.value = registration.id;
    detailsDiv.style.display = 'block';

    // Clear any previous error
    clearError();
}

function hideRegistrationDetails() {
    document.getElementById('registration-details').style.display = 'none';
    document.getElementById('registration_id').value = '';
}

function showError(message) {
    const ticketInput = document.getElementById('ticket_code');
    const existingError = ticketInput.parentNode.querySelector('.error-message');

    if (!existingError) {
        const errorDiv = document.createElement('p');
        errorDiv.className = 'error-message';
        errorDiv.style.cssText = 'margin-top: 4px; font-size: 12px; color: var(--color-danger-600);';
        errorDiv.textContent = message;
        ticketInput.parentNode.insertBefore(errorDiv, ticketInput.nextSibling);
    }
}

function clearError() {
    const errorMessage = document.querySelector('.error-message');
    if (errorMessage) {
        errorMessage.remove();
    }
}
</script>
@endsection