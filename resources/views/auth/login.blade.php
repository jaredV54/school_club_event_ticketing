<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - School Club Event Ticketing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            background-color: #0a0a0a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            color: #e9ecef;
        }
        .card {
            background-color: #1a1a1a;
            border: 1px solid #2d2d2d;
            border-radius: 1rem;
            box-shadow: 0 1rem 3rem rgba(0,0,0,0.3);
            color: #e9ecef;
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
        .btn-primary {
            background-color: #495057;
            border-color: #495057;
            border-radius: 0.5rem;
        }
        .btn-primary:hover {
            background-color: #343a40;
            border-color: #343a40;
        }
        .text-muted {
            color: #adb5bd !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class='bx bx-calendar-event text-primary fs-1 mb-3'></i>
                            <h2 class="fw-bold">Welcome Back</h2>
                            <p class="text-muted">Sign in to your account</p>
                        </div>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bx-envelope'></i></span>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bx-lock'></i></span>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                                <i class='bx bx-log-in me-2'></i>Sign In
                            </button>
                        </form>
                        <div class="text-center">
                            <p class="mb-0">Don't have an account?
                                <a href="{{ route('register') }}" class="text-decoration-none fw-bold">Sign up here</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>