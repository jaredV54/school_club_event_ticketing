<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EventOps</title>
    
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px;">
        <div style="width: 100%; max-width: 440px;">
            <!-- Logo and Title -->
            <div style="text-align: center; margin-bottom: 32px;">
                <div style="display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; background-color: var(--color-primary-50); margin-bottom: 16px;">
                    <i class='bx bx-calendar-event' style="font-size: 32px; color: var(--color-primary-600);"></i>
                </div>
                <h1 style="font-size: 24px; font-weight: 700; color: var(--color-text-heading); margin-bottom: 8px;">
                    Welcome Back
                </h1>
                <p style="font-size: 14px; color: var(--color-text-muted);">
                    Sign in to EventOps
                </p>
            </div>

            <!-- Login Card -->
            <div class="card" style="padding: 32px;">
                @if($errors->any())
                    <div class="alert alert-danger" style="margin-bottom: 24px;">
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

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <!-- Email -->
                    <div style="margin-bottom: 20px;">
                        <label for="email" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                            Email Address <span style="color: var(--color-danger-600);">*</span>
                        </label>
                        <div style="position: relative;">
                            <input 
                                type="email" 
                                class="input" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                placeholder="Enter your email"
                                required
                                style="padding-left: 40px;"
                            >
                            <i class='bx bx-envelope' style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-text-muted); font-size: 18px;"></i>
                        </div>
                    </div>

                    <!-- Password -->
                    <div style="margin-bottom: 24px;">
                        <label for="password" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                            Password <span style="color: var(--color-danger-600);">*</span>
                        </label>
                        <div style="position: relative;">
                            <input 
                                type="password" 
                                class="input" 
                                id="password" 
                                name="password" 
                                placeholder="Enter your password"
                                required
                                style="padding-left: 40px;"
                            >
                            <i class='bx bx-lock' style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-text-muted); font-size: 18px;"></i>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <x-button type="submit" variant="primary" style="width: 100%; justify-content: center;">
                        <i class='bx bx-log-in'></i>
                        <span>Sign In</span>
                    </x-button>
                </form>

                <!-- Register Link -->
                <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--color-border-subtle);">
                    <p style="font-size: 14px; color: var(--color-text-muted); margin: 0;">
                        Don't have an account? 
                        <a href="{{ route('register') }}" style="color: var(--color-primary-600); font-weight: 500; text-decoration: none;">
                            Sign up here
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>