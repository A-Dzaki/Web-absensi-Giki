<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Tata Usaha - Tata Usaha | SMP GIKI 2 Surabaya</title>
    
    <link rel="icon" type="image/png" href="{{ asset('uploads/logo-giki.png') }}">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/styletu.css') . '?v=' . time() }}" rel="stylesheet">
</head>

<body data-page="tatausaha">
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
            <a href="{{ route('tatausaha.data-tatausaha') }}" class="btn-icon active" title="Kelola Tata Usaha">
                <i class="bi bi-person-badge"></i>
            </a>
            <a href="{{ route('tatausaha.profil') }}" class="btn-icon" title="Akun">
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
                <button class="hamburger d-md-none" aria-label="Toggle navigation">
    <span></span>
    <span></span>
    <span></span>
</button>

                <div class="topbar-title">
                    <h1>Absensi Siswa | SMP GIKI 2 Surabaya</h1>
                    <p class="topbar-subtitle">Kelola Data Tata Usaha</p>
                </div>
                <div class="topbar-user">
                    <a href="{{ route('tatausaha.profil') }}" class="profile-chip text-decoration-none text-reset">
                        <div class="avatar">
                            @if(Auth::user()->foto)
                                <img src="{{ asset(Auth::user()->foto) }}" alt="Foto Profil"
                                    style="width:100%;height:100%;object-fit:cover;border-radius:50%">
                            @else
                                {{ Str::upper(substr(Auth::user()->name, 0, 2)) }}
                            @endif
                        </div>
                        <div style="line-height:1">
                            <div class="fw-semibold small">{{ Auth::user()->name }}</div>
                        </div>
                    </a>
                </div>
            </header>

            <section class="content p-3 p-md-4">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="panel">
                    <div class="panel-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <h2 class="mb-0">Daftar Tata Usaha</h2>
                        <form action="{{ route('tatausaha.data-tatausaha') }}" method="GET" class="d-flex ms-auto me-2">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Cari Nama / Username..." value="{{ $search ?? '' }}">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTataUsaha">
                            <i class="bi bi-person-plus-fill me-1"></i> Tambah Tata Usaha
                        </button>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table tu-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Nama Lengkap</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>No. Telepon</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tataUsaha as $tu)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($tu->foto)
                                                        <img src="{{ asset($tu->foto) }}" class="rounded-circle"
                                                            style="width:32px;height:32px;object-fit:cover">
                                                    @else
                                                        <div class="avatar-sm rounded-circle bg-light text-primary d-flex align-items-center justify-content-center"
                                                            style="width:32px;height:32px;font-size:12px">
                                                            {{ substr($tu->name, 0, 2) }}
                                                        </div>
                                                    @endif
                                                    {{ $tu->name }}
                                                </div>
                                            </td>
                                            <td>{{ $tu->username }}</td>
                                            <td>{{ $tu->email }}</td>
                                            <td>{{ $tu->no_telp ?? '-' }}</td>
                                            <td class="text-end">
                                                <button type="button"
                                                    class="btn btn-outline-primary btn-sm me-1 btn-edit-tu"
                                                    data-bs-toggle="modal" data-bs-target="#modalEditTataUsaha"
                                                    data-tu="{{ $tu }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>

                                                @if($tu->id !== Auth::id())
                                                    <button type="button" class="btn btn-outline-danger btn-sm" title="Hapus"
                                                        data-bs-toggle="modal" data-bs-target="#modalDeleteTataUsaha"
                                                        data-id="{{ $tu->id }}" data-nama="{{ $tu->name }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                Belum ada data tata usaha.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $tataUsaha->links() }}
                        </div>
                    </div>
                </div>


            </section>
        </main>
    </div>

    <script src="/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // EDIT Elements
            const editButtons = document.querySelectorAll('.btn-edit-tu');
            const editModal = document.getElementById('modalEditTataUsaha');
            const editForm = editModal.querySelector('form');

            // Elements in Edit Form
            const inputName = editModal.querySelector('input[name="name"]');
            const inputUsername = editModal.querySelector('input[name="username"]');
            const inputEmail = editModal.querySelector('input[name="email"]');
            const inputTelp = editModal.querySelector('input[name="no_telp"]');

            editButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const data = JSON.parse(this.getAttribute('data-tu'));

                    // Set Action URL
                    editForm.action = `/tatausaha/tatausaha/${data.id}`;

                    // Populate Fields
                    inputName.value = data.name;
                    inputUsername.value = data.username;
                    inputEmail.value = data.email;
                    inputTelp.value = data.no_telp || '';
                });
            });

            // Delete Modal Listener
            const modalDeleteTataUsaha = document.getElementById('modalDeleteTataUsaha');
            if (modalDeleteTataUsaha) {
                modalDeleteTataUsaha.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const id = button.getAttribute('data-id');
                    const name = button.getAttribute('data-nama');

                    this.querySelector('#deleteTataUsahaName').textContent = name;
                    this.querySelector('#formDeleteTataUsaha').action = `/tatausaha/tatausaha/${id}`;
                });
            }
        });
    </script>

    <!-- ==================== MOVED MODALS ==================== -->

    <!-- MODAL TAMBAH TATA USAHA -->
    <div class="modal fade" id="modalTambahTataUsaha" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content tu-modal">
                <form action="{{ route('tatausaha.tatausaha.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Tambah Data Tata Usaha</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required placeholder="Nama lengkap">
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" required
                                    placeholder="Min. 6 karakter">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="no_telp" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-0 mt-3">
                        <button type="submit" class="btn btn-primary w-100">Simpan Data Tata Usaha</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT TATA USAHA -->
    <div class="modal fade" id="modalEditTataUsaha" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content tu-modal">
                <form action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Edit Data Tata Usaha</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Kosongkan jika tidak ubah">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="no_telp" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-0 mt-3">
                        <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL DELETE TATA USAHA -->
    <div class="modal fade" id="modalDeleteTataUsaha" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="" method="POST" id="formDeleteTataUsaha">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold text-danger">Hapus Data Tata Usaha?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <div class="mb-3">
                            <i class="bi bi-exclamation-circle text-danger display-1"></i>
                        </div>
                        <p class="mb-1 fs-5">Apakah anda yakin ingin menghapus akun ini?</p>
                        <h4 class="fw-bold" id="deleteTataUsahaName">...</h4>
                        <div class="alert alert-warning mt-3 small text-start">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            <strong>Perhatian:</strong> Data akun ini akan terhapus secara permanen.
                        </div>
                    </div>
                    <div class="modal-footer border-0 justify-content-center pt-0 pb-4">
                        <button type="button" class="btn btn-secondary px-4 me-2" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger px-4">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
