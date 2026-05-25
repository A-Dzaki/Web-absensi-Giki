<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f1f1f1;
        }

        .container {
            width: 95%;
            max-width: 1400px;
            margin: 30px auto;
            display: flex;
            min-height: 620px;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .left {
            flex: 1;
            background: #668fd3;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 40px;
            text-align: center;
        }

        .left h2 {
            font-size: 28px;
            font-weight: bold;
        }

        .left .icon {
            font-size: 60px;
        }

        .left h3 {
            font-size: 24px;
        }

        .right {
            flex: 1;
            padding: 80px 70px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .right h1 {
            text-align: center;
            color: #1269f3;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .right p {
            text-align: center;
            color: #444;
            margin-bottom: 30px;
            font-size: 18px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            color: #333;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            border: 1px solid #cfd8e3;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 15px;
            background: #1269f3;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 20px;
            cursor: pointer;
            margin-top: 15px;
        }

        button:hover {
            background: #0d57cc;
        }

        .error {
            background: #ffe5e5;
            color: #b00020;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .back {
            text-align: center;
            margin-top: 25px;
        }

        .back a {
            color: #1269f3;
            text-decoration: none;
            font-size: 18px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="left">
        <h2>SMP GIKI 2 Surabaya</h2>
        <div class="icon">📋</div>
        <h3>ABSENSI SISWA</h3>
    </div>

    <div class="right">
        <h1>RESET PASSWORD</h1>
        <p>Masukkan password baru untuk akun kamu.</p>

        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label>Email</label>
                <input 
                    type="email" 
                    name="email" 
                    value="{{ $email ?? old('email') }}" 
                    required 
                    autofocus
                >
            </div>

            <div class="form-group">
                <label>Password Baru</label>
                <input 
                    type="password" 
                    name="password" 
                    required
                >
            </div>

            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    required
                >
            </div>

            <button type="submit">Reset Password</button>
        </form>

        <div class="back">
            <a href="{{ route('login') }}">Kembali ke Login</a>
        </div>
    </div>
</div>


    <!-- Hamburger Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger');
    const sidebar = document.querySelector('.sidebar');
    
    if (hamburger && sidebar) {
        hamburger.addEventListener('click', function(e) {
            e.stopPropagation();
            hamburger.classList.toggle('active');
            sidebar.classList.toggle('active');
        });

        // Close sidebar when clicking outside
        document.addEventListener('click', function(event) {
            if (sidebar.classList.contains('active') && !event.target.closest('.sidebar') && !event.target.closest('.hamburger')) {
                hamburger.classList.remove('active');
                sidebar.classList.remove('active');
            }
        });
        
        // Close sidebar on link click
        const sidebarLinks = sidebar.querySelectorAll('a:not(.disabled)');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                sidebar.classList.remove('active');
            });
        });
    }
});
</script>
</body>
</html>