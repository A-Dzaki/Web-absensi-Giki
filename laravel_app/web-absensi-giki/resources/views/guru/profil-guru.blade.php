<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Guru | Absensi SMP GIKI 2 Surabaya</title>
    
    <link rel="icon" type="image/png" href="{{ asset('uploads/logo-giki.png') }}">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/styleguruu.css') . '?v=' . time() }}" rel="stylesheet">
</head>

<body>
    <div class="app">
        @include('components.settings-modal')

        <!-- ==================== SIDEBAR ==================== -->
        <aside class="sidebar d-flex flex-column align-items-center py-3">
            <a href="{{ route('guru.dashboard') }}" class="btn-icon" title="Beranda">
                <i class="bi bi-house-door"></i>
            </a>
            <!-- Removed Kelola Absensi link to avoid error and inconsistency -->
            <a href="{{ route('guru.profil') }}" class="btn-icon active" title="Akun">
                <i class="bi bi-person"></i>
            </a>

            <div class="sidebar-footer">
                <div class="btn-icon" title="Pengaturan" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalSettings"><i class="bi bi-gear"></i></div>
                <a href="#" class="btn-icon" title="Keluar" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <i class="bi bi-box-arrow-left"></i>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </aside>

        <!-- ==================== MAIN CONTENT ==================== -->
        <main class="page">
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
                    <div class="subtle">Profil Guru</div>
                </div>

                <a href="{{ route('guru.profil') }}" class="profile-chip text-decoration-none text-reset">
                    <div class="avatar">
                        @if(Auth::user()->foto)
                            <img src="{{ asset(Auth::user()->foto) }}" alt="Foto Profil"
                                style="width:100%;height:100%;object-fit:cover;border-radius:50%">
                        @else
                            {{ Str::upper(substr(Auth::user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div style="line-height:1">
                        <div class="fw-semibold small">{{ Auth::user()->name }}</div>
                        <div class="text-secondary" style="font-size:.75rem">
                            NIP. {{ Auth::user()->nis ?? '-' }}
                        </div>
                    </div>
                </a>
            </div>

            <!-- ==================== FORM PROFIL ==================== -->
            <section class="wrap" id="view-profil">
                <div class="row g-4">

                    <!-- Foto Profil -->
                    <div class="col-md-4 d-flex flex-column align-items-center">
                        <div class="mb-3 position-relative">
                            <img src="{{ Auth::user()->foto ? asset(Auth::user()->foto) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=4f78c8&color=fff&size=250' }}"
                                 alt="Foto Profil" id="previewFoto"
                                 style="width:250px;height:250px;border-radius:50%;object-fit:cover;box-shadow:0 4px 12px rgba(0,0,0,.1)">
                        </div>

                        <h6 class="fw-semibold mb-2">{{ Auth::user()->name }}</h6>

                        <button type="button" id="btnGantiFoto" class="btn btn-light border rounded-pill px-3 d-none">
                            <i class="bi bi-camera"></i> Ganti Foto
                        </button>
                        <input type="file" id="fotoInput" accept="image/*" class="d-none">
                    </div>

                    <!-- Form Edit Profil -->
                    <div class="col-lg-8">
                        <div class="card card-soft p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Profil Saya</h6>
                                <button type="button" id="btnEnableEdit" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil-square"></i> Edit Profil
                                </button>
                            </div>

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('guru.profil.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Upload foto tersembunyi (akan diproses di controller) -->
                                <input type="file" name="foto" id="fotoUpload" class="d-none">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small">Nama Lengkap</label>
                                        <input type="text" name="name" class="form-control"
                                               value="{{ old('name', Auth::user()->name) }}" readonly required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">Email</label>
                                        <input type="email" name="email" class="form-control"
                                               value="{{ old('email', Auth::user()->email) }}" readonly required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">No. Telp</label>
                                        <input type="text" name="no_telp" class="form-control"
                                               value="{{ old('no_telp', Auth::user()->no_telp) }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">Mapel Diampu</label>
                                        <input type="text" name="mapel" class="form-control bg-light"
                                               value="{{ old('mapel', Auth::user()->mapel) }}" readonly disabled title="Diatur oleh Tata Usaha">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label small">Kelas Diampu</label>
                                        <input type="text" name="kelas_diampu" class="form-control bg-light"
                                               value="{{ old('kelas_diampu', Auth::user()->kelas_diampu) }}"
                                               placeholder="Contoh: 9A, 9B, 9C" readonly disabled title="Diatur oleh Tata Usaha">
                                    </div>
                                </div>

                                <div class="text-end mt-4 d-none" id="btnActionGroup">
                                    <button type="button" id="btnCancelEdit" class="btn btn-outline-secondary px-3 me-2">Batal</button>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-check2"></i> Update Profil
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
    <script src="{{ asset('js/Guru.js') }}"></script>

    <script>
        // Toggle Edit Mode
        const btnEnableEdit = document.getElementById('btnEnableEdit');
        const btnActionGroup = document.getElementById('btnActionGroup');
        const btnCancelEdit = document.getElementById('btnCancelEdit');
        const btnGantiFoto = document.getElementById('btnGantiFoto');
        // Select only inputs that are NOT disabled
        const formInputs = document.querySelectorAll('form input.form-control:not([disabled])');

        btnEnableEdit.addEventListener('click', () => {
            // Enable inputs
            formInputs.forEach(input => input.removeAttribute('readonly'));
            // Show buttons
            btnActionGroup.classList.remove('d-none');
            btnGantiFoto.classList.remove('d-none');
            // Hide edit button
            btnEnableEdit.classList.add('d-none');
        });

        btnCancelEdit.addEventListener('click', () => {
            // Disable inputs
            formInputs.forEach(input => input.setAttribute('readonly', 'true'));
            // Hide buttons
            btnActionGroup.classList.add('d-none');
            btnGantiFoto.classList.add('d-none');
            // Show edit button
            btnEnableEdit.classList.remove('d-none');
        });

        // Ganti Foto Profil (Preview + kirim ke input hidden)
        btnGantiFoto.addEventListener('click', function () {
            document.getElementById('fotoInput').click();
        });

        document.getElementById('fotoInput').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (ev) {
                    document.getElementById('previewFoto').src = ev.target.result;
                    // Masukkan file ke input form yang akan dikirim
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    document.getElementById('fotoUpload').files = dataTransfer.files;
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