<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>
        @if($user->role == 'guru')
            Setup Akun Guru
        @else
            Setup Akun Siswa
        @endif
    </title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, sans-serif;
        }

        body{
            background:#f3f6fb;
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .container{
            width:420px;
            background:white;
            padding:40px;
            border-radius:18px;
            box-shadow:0 10px 30px rgba(0,0,0,0.1);
        }

        .logo{
            text-align:center;
            margin-bottom:25px;
        }

        .logo h1{
            color:#1e3a8a;
            margin-top:10px;
            font-size:28px;
        }

        .logo p{
            color:#6b7280;
            margin-top:8px;
            font-size:14px;
        }

        .form-group{
            margin-bottom:18px;
        }

        label{
            display:block;
            margin-bottom:8px;
            font-weight:bold;
            color:#374151;
        }

        input{
            width:100%;
            padding:14px;
            border:1px solid #d1d5db;
            border-radius:10px;
            font-size:15px;
        }

        input:focus{
            outline:none;
            border-color:#2563eb;
            box-shadow:0 0 0 3px rgba(37,99,235,0.15);
        }

        button{
            width:100%;
            padding:14px;
            background:#2563eb;
            border:none;
            border-radius:10px;
            color:white;
            font-size:16px;
            font-weight:bold;
            cursor:pointer;
            margin-top:10px;
        }

        button:hover{
            background:#1d4ed8;
        }

        .error{
            background:#fee2e2;
            color:#b91c1c;
            padding:12px;
            border-radius:10px;
            margin-bottom:18px;
            font-size:14px;
        }

        .footer{
            text-align:center;
            margin-top:20px;
            color:#9ca3af;
            font-size:13px;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="logo">

        <h1>
            @if($user->role == 'guru')
                Setup Akun Guru
            @else
                Setup Akun Siswa
            @endif
        </h1>

        <p>
            Silakan buat username dan password Anda
        </p>

    </div>

    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="/setup-account/{{ $token }}">
        @csrf

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required>
        </div>

        <button type="submit">
            Simpan Akun
        </button>
    </form>

    <div class="footer">
        Absensi Siswa SMP GIKI 2 Surabaya
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