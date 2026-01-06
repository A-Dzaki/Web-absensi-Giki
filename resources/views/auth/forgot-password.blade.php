<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password - Absensi SMP GIKI 2 Surabaya</title>

    <!-- Bootstrap CSS dari public (atau CDN kalau mau) -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS kamu (jika ada file tambahan) -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet"> <!-- optional kalau ada -->
</head>

<body class="bg-light">

    <div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
        <div class="row shadow-lg rounded-4 overflow-hidden"
             style="max-width: 1100px; background-color: white; width:100%;">

            <!-- KIRI -->
            <div class="col-md-6 text-center text-white d-flex flex-column justify-content-center p-5"
                 style="background: linear-gradient(to bottom right, #729ad8, #4f78c8);">
                <h5 class="fw-bold mb-5">SMP GIKI 2 Surabaya</h5>
                <div class="mb-4">
                    <i class="bi bi-journal-text" style="font-size: 3.2rem;"></i>
                </div>
                <h6 class="fw-semibold mb-4">ABSENSI SISWA</h6>
                <!-- Buttons removed -->
            </div>

            <!-- KANAN -->
            <div class="col-md-6 bg-light d-flex flex-column justify-content-center p-5" style="padding: 3rem 3.2rem;">
                <div class="text-center mb-4">
                    <i class="bi bi-key" style="font-size: 3rem; color: #4f78c8;"></i>
                    <h4 class="fw-bold mt-2 text-primary">FORGOT PASSWORD</h4>
                    <p class="text-muted small">Masukkan Email kamu untuk menerima link reset password.</p>
                </div>

                <!-- Flash Message Laravel (sukses / error) -->
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Form Forgot Password Laravel -->
                <form method="POST" action="{{ route('password.email') }}" novalidate>
                    @csrf

                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                               placeholder="Email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">Masukkan Email yang valid.</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-text text-muted">
                        Link reset password akan dikirim ke email kamu jika akun terdaftar.
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-3">
                        Kirim Link Reset Password
                    </button>

                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="text-decoration-none text-primary">Kembali ke Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <!-- Optional: kalau masih mau pakai localStorage redirect (bisa dihapus kalau pakai Laravel Auth) -->
    <script>
        // Optional scripts
    </script>
</body>

</html>