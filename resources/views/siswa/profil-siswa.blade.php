<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Siswa | Absensi SMP GIKI 2 Surabaya</title>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/stylesiswa.css') }}" rel="stylesheet">
</head>

<body>
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
                <div>
                    <h5 class="brand-title mb-1">
                        Absensi Siswa <span class="fw-normal">| SMP GIKI 2 Surabaya</span>
                    </h5>
                    <div class="subtle fw-semibold">Profil Siswa</div>
                </div>

                <div class="profile-chip">
                    <div class="avatar" id="chipAvatar">
                        @if(Auth::user()->foto)
                            <img src="{{ asset(Auth::user()->foto) }}" alt="Foto Profil" style="width:100%;height:100%;object-fit:cover;border-radius:50%">
                        @else
                            {{ Str::upper(substr(Auth::user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div style="line-height:1">
                        <div class="fw-semibold small" id="chipName">{{ Auth::user()->name }}</div>
                        <div class="text-secondary" style="font-size:.75rem" id="chipNis">
                            NIS. {{ Auth::user()->nis ?? '-' }}
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
                                <img id="fotoPreview"
                                     src="{{ Auth::user()->foto ? asset(Auth::user()->foto) : asset('img/avatar-default.png') }}"
                                     alt="Foto Profil"
                                     style="width:150px;height:150px;border-radius:50%;object-fit:cover;box-shadow:0 4px 12px rgba(0,0,0,.1)">
                            </div>

                            <h6 class="fw-semibold mb-2">{{ Auth::user()->name }}</h6>

                            <!-- Button Hidden by default, toggled via JS -->
                            <button type="button" id="btnGantiFoto" class="btn btn-light border rounded-pill px-3 d-none">
                                <i class="bi bi-camera"></i> Ganti Foto
                            </button>
                            <input type="file" name="foto" id="fotoInput" accept="image/*" class="d-none">
                        </div>

                        <!-- Form Edit Profil -->
                        <div class="col-12 col-md-8">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    {{ session('success') }}
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

                                <!-- File foto tersembunyi (akan diisi via JS) -->
                                <input type="file" name="foto" id="fotoUpload" class="d-none">

                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label small text-secondary">Nama Lengkap</label>
                                        <input type="text" name="name" class="form-control bg-light"
                                               value="{{ old('name', Auth::user()->name) }}" placeholder="Nama Lengkap" disabled>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small text-secondary">NIS</label>
                                        <input type="text" name="nis" class="form-control bg-light" readonly
                                               value="{{ Auth::user()->nis }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small text-secondary">Email</label>
                                        <input type="email" name="email" class="form-control bg-light"
                                               value="{{ old('email', Auth::user()->email) }}" disabled>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small text-secondary">Telepon</label>
                                        <input type="text" name="no_telp" class="form-control bg-light"
                                               value="{{ old('no_telp', Auth::user()->no_telp) }}" placeholder="08xx-xxxx-xxxx" disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small text-secondary">Kelas</label>
                                        <input type="text" class="form-control bg-light" readonly
                                               value="{{ Auth::user()->kelas ?? '-' }}">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label small text-secondary">Alamat</label>
                                        <input type="text" name="alamat" class="form-control bg-light"
                                               value="{{ old('alamat', Auth::user()->alamat) }}" placeholder="Alamat lengkap" disabled>
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

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <script>
        // Ganti Foto Profil + Preview langsung
        document.getElementById('btnGantiFoto').addEventListener('click', () => {
            document.getElementById('fotoInput').click();
        });

        document.getElementById('fotoInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    document.getElementById('fotoPreview').src = ev.target.result;

                    // Masukkan file ke input form tersembunyi
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    document.getElementById('fotoUpload').files = dt.files;
                    
                    // Auto-enable update button if photo changes
                    document.getElementById('btnUpdateProfil').classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        });

        // Toggle Edit Mode
        const btnEditProfil = document.getElementById('btnEditProfil');
        const formInputs = document.querySelectorAll('#formProfil input:not([readonly])'); 
        // Note: Inputs that should ALWAYs be readonly (NIS, Kelas) have explicit readonly attr in HTML
        
        btnEditProfil.addEventListener('click', function() {
            const isEditing = this.classList.contains('active');
            
            if (!isEditing) {
                // Enable Editing
                this.classList.add('active');
                this.innerHTML = '<i class="bi bi-x-lg me-1"></i> Batal Edit';
                this.classList.replace('btn-outline-primary', 'btn-outline-danger');
                
                document.getElementById('btnUpdateProfil').classList.remove('d-none');
                document.getElementById('btnGantiFoto').classList.remove('d-none');
                
                formInputs.forEach(input => {
                    // Start editing: Enable all EXCEPT name
                    if(input.name !== 'name') {
                        input.disabled = false;
                        input.classList.remove('bg-light');
                    }
                });
            } else {
                // Cancel Editing
                this.classList.remove('active');
                this.innerHTML = '<i class="bi bi-pencil me-1"></i> Edit Profil';
                this.classList.replace('btn-outline-danger', 'btn-outline-primary');
                
                document.getElementById('btnUpdateProfil').classList.add('d-none');
                document.getElementById('btnGantiFoto').classList.add('d-none');
                
                formInputs.forEach(input => {
                    input.disabled = true;
                    input.classList.add('bg-light');
                });
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
</body>

</html>