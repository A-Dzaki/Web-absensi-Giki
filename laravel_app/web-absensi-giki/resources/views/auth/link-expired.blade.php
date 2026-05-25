<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Sudah Digunakan</title>

    <style>
        body{
            margin:0;
            padding:0;
            font-family:Arial, sans-serif;
            background:#f4f7fb;
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .card{
            background:white;
            padding:40px;
            border-radius:16px;
            box-shadow:0 10px 25px rgba(0,0,0,0.1);
            text-align:center;
            width:400px;
        }

        .icon{
            font-size:70px;
            color:#22c55e;
        }

        h1{
            margin-top:20px;
            color:#111827;
        }

        p{
            color:#6b7280;
            margin-top:10px;
            line-height:1.6;
        }

        .btn{
            display:inline-block;
            margin-top:25px;
            padding:12px 24px;
            background:#2563eb;
            color:white;
            text-decoration:none;
            border-radius:10px;
            font-weight:bold;
        }

        .btn:hover{
            background:#1d4ed8;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="icon">✓</div>

    <h1>Akun Sudah Aktif</h1>

    <p>
        Link setup akun ini sudah digunakan sebelumnya.
        Silakan login menggunakan username dan password Anda.
    </p>

    <a href="/login" class="btn">
        Login Sekarang
    </a>
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