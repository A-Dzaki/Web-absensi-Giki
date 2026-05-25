<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Siswa | Absensi SMP GIKI 2 Surabaya</title>
    
    <link rel="icon" type="image/png" href="{{ asset('uploads/logo-giki.png') }}">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/stylesiswa.css') . '?v=' . time() }}" rel="stylesheet">
</head>

<body>
    @php
        $user = Auth::user();

        $userName = $user->name ?? $user->nama ?? 'Siswa';
        $userNis = $user->nis ?? '-';

        $defaultAvatar = 'data:image/svg+xml;base64,' . base64_encode('
            <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200">
                <rect width="200" height="200" rx="100" fill="#6d8df5"/>
                <circle cx="100" cy="78" r="38" fill="#ffffff"/>
                <path d="M45 170c8-42 38-62 55-62s47 20 55 62" fill="#ffffff"/>
                <text x="100" y="188" text-anchor="middle" font-size="18" font-family="Arial" fill="#ffffff">Foto</text>
            </svg>
        ');

        if (!empty($user->foto)) {
            if (str_starts_with($user->foto, 'http://') || str_starts_with($user->foto, 'https://')) {
                $fotoUrl = $user->foto;
            } else {
                $fotoUrl = asset($user->foto) . '?v=' . optional($user->updated_at)->timestamp;
            }
        } else {
            $fotoUrl = $defaultAvatar;
        }
    @endphp

    <div class="app d-flex">
        @include('components.settings-modal')

        <!-- ==================== SIDEBAR ==================== -->
        <aside class="sidebar d-flex flex-column align-items-center py-3">
            <a href="{{ route('siswa.dashboard') }}" class="btn-icon" title="Beranda">
                <i class="bi bi-house-door"></i>
            </a>

            <a href="{{ route('siswa.status') }}" class="btn-icon" title="Status Absen">
                <i class="bi bi-journal-text"></i>
            </a>

            <a href="{{ route('siswa.jadwal') }}" class="btn-icon" title="Jadwal">
                <i class="bi bi-calendar3"></i>
            </a>

            <a href="{{ route('siswa.profil') }}" class="btn-icon active" title="Akun">
                <i class="bi bi-person"></i>
            </a>

            <div class="sidebar-footer w-100 text-center pt-3">
                <div class="btn-icon" title="Pengaturan" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalSettings">
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

        <!-- ==================== MAIN CONTENT ==================== -->
        <main class="page flex-grow-1">
            <div class="page-header d-flex justify-content-between align-items-start">
                <button class="hamburger d-md-none" aria-label="Toggle navigation">
    <span></span>
    <span></span>
    <span></span>
</button>

                <div>
                    <h5 class="brand-title mb-1">
                        Absensi Siswa <span class="fw-normal">| SMP GIKI 2 Surabaya</span>
                    </h5>
                    <div class="subtle fw-semibold">Profil Siswa</div>
                </div>

                <div class="profile-chip">
                    <div class="avatar" id="chipAvatar">
                        <img
                            id="chipAvatarImg"
                            src="{{ $fotoUrl }}"
                            alt="Foto Profil"
                            onerror="this.onerror=null;this.src='{{ $defaultAvatar }}';"
                            style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                    </div>

                    <div style="line-height:1">
                        <div class="fw-semibold small" id="chipName">{{ $userName }}</div>
                        <div class="text-secondary" style="font-size:.75rem" id="chipNis">
                            NIS. {{ $userNis }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==================== FORM PROFIL SISWA ==================== -->
            <section id="profilSiswa" class="view active">
                <div class="card p-4 border-0 shadow-sm"
                     style="background: var(--card); border-radius: var(--r); box-shadow: 0 8px 20px rgba(0,0,0,0.05);">

                    <div class="row g-4 align-items-center">

                        <!-- Foto Profil -->
                        <div class="col-12 col-md-4 text-center">
                            <div class="mb-3 position-relative">
                                <img
                                    id="fotoPreview"
                                    src="{{ $fotoUrl }}"
                                    alt="Foto Profil"
                                    onerror="this.onerror=null;this.src='{{ $defaultAvatar }}';"
                                    style="width:150px;height:150px;border-radius:50%;object-fit:cover;box-shadow:0 4px 12px rgba(0,0,0,.1);">
                            </div>

                            <h6 class="fw-semibold mb-2">{{ $userName }}</h6>

                            <button type="button" id="btnGantiFoto" class="btn btn-light border rounded-pill px-3 d-none">
                                <i class="bi bi-camera"></i> Ganti Foto
                            </button>

                            <div class="small text-secondary mt-2 d-none" id="fotoInfo">
                                Foto baru sudah dipilih. Klik Simpan Perubahan.
                            </div>
                        </div>

                        <!-- Form Edit Profil -->
                        <div class="col-12 col-md-8">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <strong>Terjadi kesalahan:</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <div class="d-flex justify-content-end mb-3">
                                <button type="button" id="btnEditProfil" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                    <i class="bi bi-pencil me-1"></i> Edit Profil
                                </button>
                            </div>

                            <form action="{{ route('siswa.profil.update') }}" method="POST" enctype="multipart/form-data" id="formProfil">
                                @csrf
                                @method('PUT')

                                <!-- Input foto profil -->
                                <input type="file" name="foto" id="fotoUpload" accept="image/*" class="d-none">

                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label small text-secondary">Nama Lengkap</label>
                                        <input
                                            type="text"
                                            name="name"
                                            class="form-control bg-light"
                                            value="{{ old('name', $userName) }}"
                                            placeholder="Nama Lengkap"
                                            readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small text-secondary">NIS</label>
                                        <input
                                            type="text"
                                            name="nis"
                                            class="form-control bg-light"
                                            value="{{ $userNis }}"
                                            readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small text-secondary">Email</label>
                                        <input
                                            type="email"
                                            name="email"
                                            class="form-control bg-light editable-input"
                                            value="{{ old('email', $user->email) }}"
                                            disabled>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small text-secondary">Telepon</label>
                                        <input
                                            type="text"
                                            name="no_telp"
                                            class="form-control bg-light editable-input"
                                            value="{{ old('no_telp', $user->no_telp) }}"
                                            placeholder="08xx-xxxx-xxxx"
                                            disabled>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small text-secondary">Kelas</label>
                                        <input
                                            type="text"
                                            class="form-control bg-light"
                                            value="{{ $user->kelas ?? '-' }}"
                                            readonly>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label small text-secondary">Alamat</label>
                                        <input
                                            type="text"
                                            name="alamat"
                                            class="form-control bg-light editable-input"
                                            value="{{ old('alamat', $user->alamat) }}"
                                            placeholder="Alamat lengkap"
                                            disabled>
                                    </div>
                                </div>

                                <div class="text-end mt-4">
                                    <button type="submit" id="btnUpdateProfil" class="btn btn-primary rounded-pill px-4 d-none">
                                        <i class="bi bi-check2 me-1"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="/js/bootstrap.bundle.min.js"></script>

    <script>
        const btnEditProfil = document.getElementById('btnEditProfil');
        const btnGantiFoto = document.getElementById('btnGantiFoto');
        const btnUpdateProfil = document.getElementById('btnUpdateProfil');
        const fotoUpload = document.getElementById('fotoUpload');
        const fotoPreview = document.getElementById('fotoPreview');
        const chipAvatarImg = document.getElementById('chipAvatarImg');
        const fotoInfo = document.getElementById('fotoInfo');
        const editableInputs = document.querySelectorAll('.editable-input');

        btnGantiFoto.addEventListener('click', function () {
            fotoUpload.click();
        });

        fotoUpload.addEventListener('change', function (e) {
            const file = e.target.files[0];

            if (!file) {
                return;
            }

            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];

            if (!allowedTypes.includes(file.type)) {
                alert('Format foto harus JPG, JPEG, PNG, atau WEBP.');
                fotoUpload.value = '';
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran foto maksimal 2MB.');
                fotoUpload.value = '';
                return;
            }

            const reader = new FileReader();

            reader.onload = function (ev) {
                fotoPreview.src = ev.target.result;
                chipAvatarImg.src = ev.target.result;
                btnUpdateProfil.classList.remove('d-none');
                fotoInfo.classList.remove('d-none');
            };

            reader.readAsDataURL(file);
        });

        btnEditProfil.addEventListener('click', function () {
            const isEditing = this.classList.contains('active');

            if (!isEditing) {
                this.classList.add('active');
                this.innerHTML = '<i class="bi bi-x-lg me-1"></i> Batal Edit';
                this.classList.replace('btn-outline-primary', 'btn-outline-danger');

                btnUpdateProfil.classList.remove('d-none');
                btnGantiFoto.classList.remove('d-none');

                editableInputs.forEach(input => {
                    input.disabled = false;
                    input.classList.remove('bg-light');
                });
            } else {
                this.classList.remove('active');
                this.innerHTML = '<i class="bi bi-pencil me-1"></i> Edit Profil';
                this.classList.replace('btn-outline-danger', 'btn-outline-primary');

                btnUpdateProfil.classList.add('d-none');
                btnGantiFoto.classList.add('d-none');
                fotoInfo.classList.add('d-none');

                editableInputs.forEach(input => {
                    input.disabled = true;
                    input.classList.add('bg-light');
                });

                fotoUpload.value = '';
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
                    <button type="button" class="btn btn-danger px-4" onclick="document.getElementById('logout-form').submit()">Iya</button>
                </div>
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
