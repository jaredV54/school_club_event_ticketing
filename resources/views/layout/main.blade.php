<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'School Club Event Ticketing')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #0a0a0a;
            color: #e9ecef;
            font-family: 'Poppins', sans-serif;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background: #1a1a1a;
            color: #e9ecef;
            z-index: 1000;
            overflow-y: hidden;
            box-shadow: 2px 0 10px rgba(0,0,0,0.3);
        }
        .sidebar .nav-link {
            color: #adb5bd;
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background-color: #343a40;
        }
        .sidebar .nav-link i {
            margin-right: 0.5rem;
            width: 1rem;
        }
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            background-color: #0a0a0a;
            min-height: 100vh;
        }
        .card {
            background-color: #1a1a1a;
            border: 1px solid #2d2d2d;
            border-radius: 0.75rem;
            color: #e9ecef;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.3);
            transition: box-shadow 0.3s ease;
        }
        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.4);
            border-color: #6c757d;
        }
        .card-header {
            background-color: #2d2d2d;
            border-bottom: 1px solid #404040;
            color: #e9ecef;
        }
        .table {
            background-color: #1a1a1a;
            color: #e9ecef;
        }
        .table thead th {
            background-color: #2d2d2d;
            border-color: #404040;
            color: #e9ecef;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .table tbody td {
            background-color: #1a1a1a;
            border-color: #404040;
            color: #e9ecef;
            font-size: 0.875rem;
        }
        .table tbody tr:hover td {
            background-color: #2d2d2d;
        }
        .form-control {
            background-color: #2d2d2d;
            border: 1px solid #404040;
            color: #e9ecef;
        }
        .form-control:focus {
            background-color: #2d2d2d;
            border-color: #6c757d;
            color: #e9ecef;
            box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.25);
        }
        .form-select {
            background-color: #2d2d2d;
            border: 1px solid #404040;
            color: #e9ecef;
        }
        .form-select:focus {
            border-color: #6c757d;
            box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.25);
        }
        .btn-primary {
            background-color: #495057;
            border-color: #495057;
            color: #fff;
        }
        .btn-primary:hover {
            background-color: #343a40;
            border-color: #343a40;
        }
        .btn-outline-info {
            color: #6c757d;
            border-color: #6c757d;
        }
        .btn-outline-info:hover {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-outline-warning {
            color: #6c757d;
            border-color: #6c757d;
        }
        .btn-outline-warning:hover {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-outline-danger {
            color: #495057;
            border-color: #495057;
        }
        .btn-outline-danger:hover {
            background-color: #495057;
            border-color: #495057;
        }
        .alert-success {
            background-color: #2d2d2d;
            border-color: #495057;
            color: #e9ecef;
        }
        .alert-danger {
            background-color: #2d2d2d;
            border-color: #495057;
            color: #e9ecef;
        }
        .badge {
            background-color: #495057;
        }
        .text-muted {
            color: #adb5bd !important;
        }
        .text-primary {
            color: #e9ecef !important;
        }
        .text-success {
            color: #e9ecef !important;
        }
        .text-danger {
            color: #e9ecef !important;
        }
        .text-warning {
            color: #e9ecef !important;
        }
        .text-info {
            color: #e9ecef !important;
        }
        .sidebar-toggle {
            display: none;
        }
        @media (max-width: 768px) {
            .sidebar {
                left: -250px;
                transition: left 0.3s ease;
            }
            .sidebar.show {
                left: 0;
            }
            .sidebar-toggle {
                display: block;
                position: fixed;
                top: 1rem;
                left: 1rem;
                z-index: 1010;
                background: #6c757d;
                color: white;
                border: none;
                border-radius: 0.375rem;
                padding: 0.5rem;
                box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            }
            .main-content {
                margin-left: 0;
            }
            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 999;
                display: none;
            }
            .overlay.show {
                display: block;
            }
        }
    </style>
</head>
<body>
    @auth
        <div class="sidebar">
            <div class="p-3">
                <h5 class="text-center mb-4">
                    <i class='bx bx-calendar-event'></i> Club Events
                </h5>
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}" href="{{ route('dashboard.index') }}">
                        <i class='bx bx-home'></i> Dashboard
                    </a>

                    @if(auth()->user()->role === 'admin')
                        <!-- Admin Menu Items -->
                        <div class="nav-item">
                            <a class="nav-link" href="#usersSubmenu" data-bs-toggle="collapse">
                                <i class='bx bx-group'></i> Users <i class='bx bx-chevron-down float-end'></i>
                            </a>
                            <div class="collapse {{ request()->routeIs('users.*') ? 'show' : '' }}" id="usersSubmenu">
                                <a class="nav-link ms-3 {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                    <i class='bx bx-list-ul'></i> All Users
                                </a>
                                <a class="nav-link ms-3 {{ request()->routeIs('users.create') ? 'active' : '' }}" href="{{ route('users.create') }}">
                                    <i class='bx bx-plus-circle'></i> Add User
                                </a>
                            </div>
                        </div>
                        <div class="nav-item">
                            <a class="nav-link" href="#clubsSubmenu" data-bs-toggle="collapse">
                                <i class='bx bx-building'></i> Clubs <i class='bx bx-chevron-down float-end'></i>
                            </a>
                            <div class="collapse {{ request()->routeIs('clubs.*') ? 'show' : '' }}" id="clubsSubmenu">
                                <a class="nav-link ms-3 {{ request()->routeIs('clubs.index') ? 'active' : '' }}" href="{{ route('clubs.index') }}">
                                    <i class='bx bx-list-ul'></i> All Clubs
                                </a>
                                <a class="nav-link ms-3 {{ request()->routeIs('clubs.create') ? 'active' : '' }}" href="{{ route('clubs.create') }}">
                                    <i class='bx bx-plus-circle'></i> Add Club
                                </a>
                            </div>
                        </div>
                        <div class="nav-item">
                            <a class="nav-link" href="#eventsSubmenu" data-bs-toggle="collapse">
                                <i class='bx bx-calendar'></i> Events <i class='bx bx-chevron-down float-end'></i>
                            </a>
                            <div class="collapse {{ request()->routeIs('events.*') ? 'show' : '' }}" id="eventsSubmenu">
                                <a class="nav-link ms-3 {{ request()->routeIs('events.index') ? 'active' : '' }}" href="{{ route('events.index') }}">
                                    <i class='bx bx-list-ul'></i> All Events
                                </a>
                                <a class="nav-link ms-3 {{ request()->routeIs('events.create') ? 'active' : '' }}" href="{{ route('events.create') }}">
                                    <i class='bx bx-plus-circle'></i> Add Event
                                </a>
                            </div>
                        </div>
                        <div class="nav-item">
                            <a class="nav-link" href="#registrationsSubmenu" data-bs-toggle="collapse">
                                <i class='bx bx-receipt'></i> Registrations <i class='bx bx-chevron-down float-end'></i>
                            </a>
                            <div class="collapse {{ request()->routeIs('registrations.*') ? 'show' : '' }}" id="registrationsSubmenu">
                                <a class="nav-link ms-3 {{ request()->routeIs('registrations.index') ? 'active' : '' }}" href="{{ route('registrations.index') }}">
                                    <i class='bx bx-list-ul'></i> All Registrations
                                </a>
                            </div>
                        </div>
                        <div class="nav-item">
                            <a class="nav-link" href="#attendanceSubmenu" data-bs-toggle="collapse">
                                <i class='bx bx-check-circle'></i> Attendance <i class='bx bx-chevron-down float-end'></i>
                            </a>
                            <div class="collapse {{ request()->routeIs('attendance.*') ? 'show' : '' }}" id="attendanceSubmenu">
                                <a class="nav-link ms-3 {{ request()->routeIs('attendance.index') ? 'active' : '' }}" href="{{ route('attendance.index') }}">
                                    <i class='bx bx-list-ul'></i> All Logs
                                </a>
                            </div>
                        </div>
                    @elseif(auth()->user()->role === 'officer')
                        <!-- Officer Menu Items -->
                        <div class="nav-item">
                            <a class="nav-link" href="#eventsSubmenu" data-bs-toggle="collapse">
                                <i class='bx bx-calendar'></i> My Events <i class='bx bx-chevron-down float-end'></i>
                            </a>
                            <div class="collapse {{ request()->routeIs('events.*') ? 'show' : '' }}" id="eventsSubmenu">
                                <a class="nav-link ms-3 {{ request()->routeIs('events.index') ? 'active' : '' }}" href="{{ route('events.index') }}">
                                    <i class='bx bx-list-ul'></i> View Events
                                </a>
                                <a class="nav-link ms-3 {{ request()->routeIs('events.create') ? 'active' : '' }}" href="{{ route('events.create') }}">
                                    <i class='bx bx-plus-circle'></i> Create Event
                                </a>
                            </div>
                        </div>
                        <div class="nav-item">
                            <a class="nav-link" href="#registrationsSubmenu" data-bs-toggle="collapse">
                                <i class='bx bx-receipt'></i> Registrations <i class='bx bx-chevron-down float-end'></i>
                            </a>
                            <div class="collapse {{ request()->routeIs('registrations.*') ? 'show' : '' }}" id="registrationsSubmenu">
                                <a class="nav-link ms-3 {{ request()->routeIs('registrations.index') ? 'active' : '' }}" href="{{ route('registrations.index') }}">
                                    <i class='bx bx-list-ul'></i> Club Registrations
                                </a>
                            </div>
                        </div>
                        <div class="nav-item">
                            <a class="nav-link" href="#attendanceSubmenu" data-bs-toggle="collapse">
                                <i class='bx bx-check-circle'></i> Attendance <i class='bx bx-chevron-down float-end'></i>
                            </a>
                            <div class="collapse {{ request()->routeIs('attendance.*') ? 'show' : '' }}" id="attendanceSubmenu">
                                <a class="nav-link ms-3 {{ request()->routeIs('attendance.index') ? 'active' : '' }}" href="{{ route('attendance.index') }}">
                                    <i class='bx bx-list-ul'></i> Club Attendance
                                </a>
                                <a class="nav-link ms-3 {{ request()->routeIs('attendance.create') ? 'active' : '' }}" href="{{ route('attendance.create') }}">
                                    <i class='bx bx-plus-circle'></i> Mark Attendance
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Student Menu Items -->
                        <div class="nav-item">
                            <a class="nav-link {{ request()->routeIs('events.index') ? 'active' : '' }}" href="{{ route('events.index') }}">
                                <i class='bx bx-calendar'></i> Events
                            </a>
                        </div>
                        <div class="nav-item">
                            <a class="nav-link" href="#registrationsSubmenu" data-bs-toggle="collapse">
                                <i class='bx bx-receipt'></i> My Tickets <i class='bx bx-chevron-down float-end'></i>
                            </a>
                            <div class="collapse {{ request()->routeIs('registrations.*') ? 'show' : '' }}" id="registrationsSubmenu">
                                <a class="nav-link ms-3 {{ request()->routeIs('registrations.index') ? 'active' : '' }}" href="{{ route('registrations.index') }}">
                                    <i class='bx bx-list-ul'></i> My Registrations
                                </a>
                                <a class="nav-link ms-3 {{ request()->routeIs('registrations.create') ? 'active' : '' }}" href="{{ route('registrations.create') }}">
                                    <i class='bx bx-plus-circle'></i> Register for Event
                                </a>
                            </div>
                        </div>
                        <div class="nav-item">
                            <a class="nav-link {{ request()->routeIs('attendance.index') ? 'active' : '' }}" href="{{ route('attendance.index') }}">
                                <i class='bx bx-check-circle'></i> My Attendance
                            </a>
                        </div>
                    @endif
                    <hr class="my-3">
                    <div class="text-center">
                        <small>Welcome, {{ Auth::user()->name }}</small><br>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline mt-2">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">
                                <i class='bx bx-log-out'></i> Logout
                            </button>
                        </form>
                    </div>
                </nav>
            </div>
        </div>
    @endauth

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>