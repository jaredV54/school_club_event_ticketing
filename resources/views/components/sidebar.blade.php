@php
    $user = auth()->user();
@endphp

<aside class="sidebar" id="sidebar" role="navigation">
    <nav class="sidebar-nav">
        <!-- Primary Navigation -->
        <div class="sidebar-section">
            <a href="{{ route('dashboard.index') }}" class="sidebar-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                <span class="sidebar-link-icon">
                    <i class='bx bx-home'></i>
                </span>
                <span>Dashboard</span>
            </a>
        </div>
        
        @if($user->role === 'admin')
            <!-- Admin Navigation -->
            <div class="sidebar-section">
                <div class="sidebar-section-title">Management</div>
                
                <a href="{{ route('events.index') }}" class="sidebar-link {{ request()->routeIs('events.*') ? 'active' : '' }}">
                    <span class="sidebar-link-icon">
                        <i class='bx bx-calendar'></i>
                    </span>
                    <span>Events</span>
                </a>
                
                <a href="{{ route('registrations.index') }}" class="sidebar-link {{ request()->routeIs('registrations.*') ? 'active' : '' }}">
                    <span class="sidebar-link-icon">
                        <i class='bx bx-receipt'></i>
                    </span>
                    <span>Registrations</span>
                </a>

                <a href="{{ route('approvals.event-registrations.index') }}" class="sidebar-link {{ request()->routeIs('approvals.event-registrations.*') ? 'active' : '' }}">
                    <span class="sidebar-link-icon">
                        <i class='bx bx-check-double'></i>
                    </span>
                    <span>Pending Registrations</span>
                </a>

                <a href="{{ route('attendance.index') }}" class="sidebar-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                    <span class="sidebar-link-icon">
                        <i class='bx bx-check-circle'></i>
                    </span>
                    <span>Attendance</span>
                </a>
                
                <a href="{{ route('clubs.index') }}" class="sidebar-link {{ request()->routeIs('clubs.*') ? 'active' : '' }}">
                    <span class="sidebar-link-icon">
                        <i class='bx bx-building'></i>
                    </span>
                    <span>Clubs</span>
                </a>
                
                <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <span class="sidebar-link-icon">
                        <i class='bx bx-group'></i>
                    </span>
                    <span>Users</span>
                </a>

                <a href="{{ route('approvals.user-accounts.index') }}" class="sidebar-link {{ request()->routeIs('approvals.user-accounts.*') ? 'active' : '' }}">
                    <span class="sidebar-link-icon">
                        <i class='bx bx-user-check'></i>
                    </span>
                    <span>Account Approvals</span>
                </a>
            </div>
            
        @elseif($user->role === 'officer')
            <!-- Officer Navigation -->
            <div class="sidebar-section">
                <div class="sidebar-section-title">Management</div>
                
                <a href="{{ route('events.index') }}" class="sidebar-link {{ request()->routeIs('events.*') ? 'active' : '' }}">
                    <span class="sidebar-link-icon">
                        <i class='bx bx-calendar'></i>
                    </span>
                    <span>Events</span>
                </a>
                
                <a href="{{ route('registrations.index') }}" class="sidebar-link {{ request()->routeIs('registrations.*') ? 'active' : '' }}">
                    <span class="sidebar-link-icon">
                        <i class='bx bx-receipt'></i>
                    </span>
                    <span>Registrations</span>
                </a>

                <a href="{{ route('approvals.event-registrations.index') }}" class="sidebar-link {{ request()->routeIs('approvals.event-registrations.*') ? 'active' : '' }}">
                    <span class="sidebar-link-icon">
                        <i class='bx bx-check-double'></i>
                    </span>
                    <span>Pending Registrations</span>
                </a>
                
                <a href="{{ route('attendance.index') }}" class="sidebar-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                    <span class="sidebar-link-icon">
                        <i class='bx bx-check-circle'></i>
                    </span>
                    <span>Attendance</span>
                </a>
            </div>
            
        @else
            <!-- Student Navigation -->
            <div class="sidebar-section">
                <div class="sidebar-section-title">My Activities</div>
                
                <a href="{{ route('events.index') }}" class="sidebar-link {{ request()->routeIs('events.*') ? 'active' : '' }}">
                    <span class="sidebar-link-icon">
                        <i class='bx bx-calendar'></i>
                    </span>
                    <span>Events</span>
                </a>
                
                <a href="{{ route('registrations.index') }}" class="sidebar-link {{ request()->routeIs('registrations.*') ? 'active' : '' }}">
                    <span class="sidebar-link-icon">
                        <i class='bx bx-receipt'></i>
                    </span>
                    <span>My Tickets</span>
                </a>
                
                <a href="{{ route('attendance.index') }}" class="sidebar-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                    <span class="sidebar-link-icon">
                        <i class='bx bx-check-circle'></i>
                    </span>
                    <span>My Attendance</span>
                </a>
            </div>
        @endif
        
        
        <!-- User Section -->
        <div class="sidebar-section" style="margin-top: auto; padding-top: 16px; border-top: 1px solid var(--color-border-subtle);">
            <div style="padding: 12px; background-color: var(--color-page-bg); margin: 0 12px;">
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 4px;">Signed in as</div>
                <div style="font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 2px;">{{ $user->name }}</div>
                <div style="font-size: 12px; color: var(--color-text-muted); margin-bottom: 8px;">{{ ucfirst($user->role) }}</div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-button type="submit" variant="secondary" size="sm" style="width: 100%;">
                        <i class='bx bx-log-out'></i>
                        <span>Sign out</span>
                    </x-button>
                </form>
            </div>
        </div>
    </nav>
</aside>

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-toggle')?.addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('show');
    });
</script>