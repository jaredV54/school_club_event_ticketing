<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - EventOps</title>
    
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px;">
        <div style="width: 100%; max-width: 600px;">
            <!-- Logo and Title -->
            <div style="text-align: center; margin-bottom: 32px;">
                <div style="display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; background-color: var(--color-primary-50); margin-bottom: 16px;">
                    <i class='bx bx-user-plus' style="font-size: 32px; color: var(--color-primary-600);"></i>
                </div>
                <h1 style="font-size: 24px; font-weight: 700; color: var(--color-text-heading); margin-bottom: 8px;">
                    Create Account
                </h1>
                <p style="font-size: 14px; color: var(--color-text-muted);">
                    Join our school club event community
                </p>
            </div>

            <!-- Register Card -->
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

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <!-- Name and Email Grid -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px;">
                        <!-- Full Name -->
                        <div>
                            <label for="name" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                                Full Name <span style="color: var(--color-danger-600);">*</span>
                            </label>
                            <div style="position: relative;">
                                <input 
                                    type="text" 
                                    class="input" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name') }}" 
                                    placeholder="Enter your full name"
                                    required
                                    style="padding-left: 40px;"
                                >
                                <i class='bx bx-user' style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-text-muted); font-size: 18px;"></i>
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
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
                    </div>

                    <!-- Password Grid -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
                        <!-- Password -->
                        <div>
                            <label for="password" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                                Password <span style="color: var(--color-danger-600);">*</span>
                            </label>
                            <div style="position: relative;">
                                <input 
                                    type="password" 
                                    class="input" 
                                    id="password" 
                                    name="password" 
                                    placeholder="Create a password"
                                    required
                                    style="padding-left: 40px;"
                                >
                                <i class='bx bx-lock' style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-text-muted); font-size: 18px;"></i>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" style="display: block; font-size: 14px; font-weight: 500; color: var(--color-text-heading); margin-bottom: 6px;">
                                Confirm Password <span style="color: var(--color-danger-600);">*</span>
                            </label>
                            <div style="position: relative;">
                                <input 
                                    type="password" 
                                    class="input" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    placeholder="Confirm your password"
                                    required
                                    style="padding-left: 40px;"
                                >
                                <i class='bx bx-lock-alt' style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-text-muted); font-size: 18px;"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <x-button type="submit" variant="primary" style="width: 100%; justify-content: center;">
                        <i class='bx bx-user-plus'></i>
                        <span>Create Account</span>
                    </x-button>
                </form>

                <!-- Login Link -->
                <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--color-border-subtle);">
                    <p style="font-size: 14px; color: var(--color-text-muted); margin: 0;">
                        Already have an account? 
                        <a href="{{ route('login') }}" style="color: var(--color-primary-600); font-weight: 500; text-decoration: none;">
                            Sign in here
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>