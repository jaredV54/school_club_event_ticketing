<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EventOps - School Club Event Ticketing')</title>
    
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body>
    @auth
        <!-- Header -->
        <x-header />
        
        <!-- Sidebar -->
        <x-sidebar />
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class='bx bx-check-circle' style="font-size: 20px;"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <div style="display: flex; align-items: start; gap: 8px;">
                        <i class='bx bx-error-circle' style="font-size: 20px; margin-top: 2px;"></i>
                        <div>
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </main>
    @else
        <!-- Guest Layout (Login/Register pages) -->
        <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background-color: var(--color-page-bg);">
            @yield('content')
        </div>
    @endauth
    
    @stack('scripts')
</body>
</html>