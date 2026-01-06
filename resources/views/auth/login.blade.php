<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Absensi Siswa SMP GIKI 2 Surabaya</title>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            max-width: 1000px;
            width: 90%;
            min-height: 600px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .left-panel {
            background: linear-gradient(135deg, #aaccff 0%, #729ad8 100%);
            color: #1a3c6e;
        }
        .right-panel {
            background-color: #f8f9fa;
        }
        .form-control, .form-select {
            background-color: #e9ecef;
            border: none;
            padding: 12px 15px;
            border-radius: 10px;
        }
        .form-control:focus, .form-select:focus {
            background-color: #e9ecef;
            box-shadow: 0 0 0 2px #729ad8;
        }
        .btn-primary {
            background-color: #638ecb;
            border: none;
            padding: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background-color: #4f78c8;
        }
        .icon-large {
            font-size: 6rem;
            color: #1a3c6e;
        }
        .icon-login {
            font-size: 4rem;
            color: #729ad8;
        }
        .text-blue-dark {
            color: #1a3c6e;
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center vh-100">

    <div class="login-card row bg-white">
        
        <!-- LEFT PANEL (Branding) -->
        <div class="col-md-6 left-panel d-flex flex-column align-items-center justify-content-center text-center p-5">
            <h2 class="fw-bold mb-0 text-blue-dark">ABSENSI SISWA</h2>
            <h5 class="fw-bold text-blue-dark opacity-75 mb-5">SMP GIKI 2 Surabaya</h5>
            
            <div class="mb-5">
                <i class="bi bi-journal-check icon-large"></i>
            </div>
            
            <!-- Login/Signup buttons removed as per request -->
        </div>

        <!-- RIGHT PANEL (Login Form) -->
        <div class="col-md-6 right-panel d-flex flex-column justify-content-center p-5">
            
            <div class="text-center mb-4">
                <i class="bi bi-building icon-login"></i>
                <h3 class="fw-bold mt-2 text-primary">LOGIN</h3>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show p-2 small">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close small p-2" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username anda" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                        <button type="button" class="btn btn-outline-secondary border-0 bg-transparent ms-n5" id="togglePassword" style="z-index: 100;">
                            <i class="bi bi-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <!-- Role Selection Removed (Auto-detected) -->

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small text-muted" for="remember">Remember me</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="small text-decoration-none fw-bold text-primary">Forgot Password?</a>
                </div>

                <button type="submit" class="btn btn-primary w-100 shadow-sm">Login</button>
            </form>

        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script>
        // Password Toggle Script
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const icon = togglePassword.querySelector('i');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });
    </script>
</body>
</html>
