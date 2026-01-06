<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Tata Usaha | Absensi SMP GIKI 2 Surabaya</title>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/styletu.css') }}" rel="stylesheet">
</head>

<body data-page="profil">
    <div class="app">
        @include('components.settings-modal')

        <!-- ==================== SIDEBAR ==================== -->
        <aside class="sidebar d-flex flex-column align-items-center py-3">
            <a href="{{ route('tatausaha.dashboard') }}" class="btn-icon" title="Beranda">
                <i class="bi bi-house-door"></i>
            </a>
            <a href="{{ route('tatausaha.data-kelas') }}" class="btn-icon" title="Kelola Absensi">
                <i class="bi bi-journal-text"></i>
            </a>
            <a href="{{ route('tatausaha.data-guru') }}" class="btn-icon" title="Kelola Guru">
                <i class="bi bi-people"></i>
            </a>
            <a href="{{ route('tatausaha.data-tatausaha') }}" class="btn-icon" title="Kelola Tata Usaha">
                <i class="bi bi-person-badge"></i>
            </a>
            <a href="{{ route('tatausaha.profil') }}" class="btn-icon active" title="Akun">
                <i class="bi bi-person"></i>
            </a>

            <div class="sidebar-footer">
                <div class="btn-icon" title="Pengaturan" data-bs-toggle="modal" data-bs-target="#modalSettings"
                    style="cursor: pointer;">
                    <i class="bi bi-gear"></i>
                </div>
                <a href="#" class="btn-icon" title="Keluar" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <i class="bi bi-box-arrow-left"></i>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </aside>

        <!-- ==================== MAIN ==================== -->
        <main class="main">
            <header class="topbar">
                <div class="topbar-title">
                    <h1>Absensi Siswa | SMP GIKI 2 Surabaya</h1>
                    <p class="topbar-subtitle">Profil</p>
                </div>
                <div class="topbar-user">
                    <div class="profile-chip">
                        <div class="avatar">{{ Str::upper(substr(Auth::user()->name, 0, 2)) }}</div>
                        <div>
                            <div class="fw-semibold small">{{ Auth::user()->name }}</div>
                        </div>
                    </div>
                </div>
            </header>

            <section class="wrap" id="view-profil">
                <div class="row g-4 align-items-start justify-content-center">
                    <!-- Foto Profil -->
                    <div class="col-md-4 d-flex flex-column align-items-center">
                        <div class="profile-photo mb-4">
                            <img src="{{ Auth::user()->foto ? asset(Auth::user()->foto) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=4f78c8&color=fff&size=400' }}"
                                alt="Foto Profil" id="fotoPreview">
                        </div>

                        <!-- Tombol Ganti Foto (Hidden by default) -->
                        <button type="button" id="btnGantiFoto" class="btn btn-outline-primary d-none">
                            <i class="bi bi-camera me-1"></i>Ubah Foto
                        </button>
                        <input type="file" name="foto" id="fotoInput" accept="image/*" class="d-none">
                    </div>

                    <!-- Form Profil -->
                    <div class="col-lg-8">
                        <div class="card card-soft p-4">

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h6 class="mb-0">Profil Tata Usaha</h6>
                                <button type="button" id="btnEditProfil" class="btn btn-sm btn-warning text-white">
                                    <i class="bi bi-pencil-square me-1"></i> Edit Profil
                                </button>
                            </div>

                            <form action="{{ route('tatausaha.profil.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <input type="file" name="foto" id="fotoUpload" class="d-none">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small text-secondary">Nama Lengkap</label>
                                        <input type="text" name="name" class="form-control" id="inputName"
                                            value="{{ old('name', Auth::user()->name) }}" required disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small text-secondary">Email</label>
                                        <input type="email" name="email" class="form-control" id="inputEmail"
                                            value="{{ old('email', Auth::user()->email) }}" required disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small text-secondary">No. Telepon</label>
                                        <input type="text" name="no_telp" class="form-control" id="inputTelp"
                                            value="{{ old('no_telp', Auth::user()->no_telp) }}" disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small text-secondary">Role</label>
                                        <input type="text" class="form-control bg-light" readonly value="Tata Usaha">
                                    </div>
                                </div>

                                <div class="text-end mt-4 d-none" id="actionButtons">
                                    <button type="button" class="btn btn-light me-2" id="btnBatal">Batal</button>
                                    <button type="submit" class="btn btn-primary px-5">
                                        <i class="bi bi-check2 me-1"></i> Update Profil
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <script>
        // Elements
        const btnEdit = document.getElementById('btnEditProfil');
        const btnGantiFoto = document.getElementById('btnGantiFoto');
        const actionButtons = document.getElementById('actionButtons');
        const btnBatal = document.getElementById('btnBatal');

        const inputs = [
            document.getElementById('inputName'),
            document.getElementById('inputEmail'),
            document.getElementById('inputTelp')
        ];

        // Toggle Edit Mode
        btnEdit.addEventListener('click', function () {
            // Enable inputs
            inputs.forEach(input => input.removeAttribute('disabled'));

            // Show buttons
            btnGantiFoto.classList.remove('d-none');
            actionButtons.classList.remove('d-none');

            // Hide Edit button
            btnEdit.classList.add('d-none');
        });

        // Cancel Edit Mode
        btnBatal.addEventListener('click', function () {
            // Disable inputs
            inputs.forEach(input => input.setAttribute('disabled', true));

            // Hide buttons
            btnGantiFoto.classList.add('d-none');
            actionButtons.classList.add('d-none');

            // Show Edit button
            btnEdit.classList.remove('d-none');
        });

        // Ubah Foto Profil + Preview
        btnGantiFoto.addEventListener('click', () => {
            document.getElementById('fotoInput').click();
        });

        document.getElementById('fotoInput').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (ev) {
                    document.getElementById('fotoPreview').src = ev.target.result;

                    // Masukkan ke input form
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    document.getElementById('fotoUpload').files = dt.files;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Konfirmasi Keluar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <p class="mb-0 fs-5">Apakah anda yakin ingin keluar?</p>
                </div>
                <div class="modal-footer border-0 justify-content-center pt-0 pb-4">
                    <button type="button" class="btn btn-secondary px-4 me-2" data-bs-dismiss="modal">Tidak</button>
                    <button type="button" class="btn btn-danger px-4"
                        onclick="document.getElementById('logout-form').submit()">Iya</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>