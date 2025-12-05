<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EventOps - School Club Event Ticketing')</title>
    
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

        <!-- Toast Notifications -->
        <div id="toast-container" class="toast-container">
            @if(session('success'))
                <div class="toast toast-success" data-autohide="true">
                    <div class="toast-content">
                        <i class='bx bx-check-circle' style="font-size: 18px;"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button class="toast-close" onclick="closeToast(this)">
                        <i class='bx bx-x'></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="toast toast-danger" data-autohide="true">
                    <div class="toast-content">
                        <i class='bx bx-error-circle' style="font-size: 18px;"></i>
                        <div>
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                    <button class="toast-close" onclick="closeToast(this)">
                        <i class='bx bx-x'></i>
                    </button>
                </div>
            @endif
        </div>

        <!-- Main Content -->
        <main class="main-content">
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

    <script>
        // Toast notification functionality
        function closeToast(button) {
            const toast = button.closest('.toast');
            hideToast(toast);
        }

        function hideToast(toast) {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }

        function showToast(toast) {
            toast.classList.add('show');
        }

        // Initialize toasts on page load
        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('.toast');

            toasts.forEach((toast, index) => {
                // Show toast with staggered animation
                setTimeout(() => {
                    showToast(toast);
                }, index * 100);

                // Auto-hide after 3.5 seconds if data-autohide is true
                if (toast.dataset.autohide === 'true') {
                    setTimeout(() => {
                        hideToast(toast);
                    }, 3500);
                }
            });
        });
    </script>
</body>
</html>