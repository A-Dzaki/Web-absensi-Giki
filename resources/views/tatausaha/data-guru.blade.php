<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Guru - Tata Usaha | SMP GIKI 2 Surabaya</title>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/styletu.css') }}" rel="stylesheet">
</head>

<body data-page="guru">
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
            <a href="{{ route('tatausaha.data-guru') }}" class="btn-icon active" title="Kelola Guru">
                <i class="bi bi-people"></i>
            </a>
            <a href="{{ route('tatausaha.data-tatausaha') }}" class="btn-icon" title="Kelola Tata Usaha">
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
                <div class="topbar-title">
                    <h1>Absensi Siswa | SMP GIKI 2 Surabaya</h1>
                    <p class="topbar-subtitle">Kelola Data Guru</p>
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

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> {!! session('warning') !!}
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
                        <h2 class="mb-0">Daftar Guru Pengajar</h2>
                        <form action="{{ route('tatausaha.data-guru') }}" method="GET" class="d-flex ms-auto me-2">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" name="search" class="form-control" placeholder="Cari Nama / NIP..."
                                    value="{{ $search ?? '' }}">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahGuru">
                            <i class="bi bi-person-plus-fill me-1"></i> Tambah Guru
                        </button>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table tu-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Nama Lengkap</th>
                                        <th>NIP</th>
                                        <th>Email</th>
                                        <th>No. Telepon</th>
                                        <th>Mapel</th>
                                        <th>Kelas Diampu</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($guru as $g)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($g->foto)
                                                        <img src="{{ asset($g->foto) }}" class="rounded-circle"
                                                            style="width:32px;height:32px;object-fit:cover">
                                                    @else
                                                        <div class="avatar-sm rounded-circle bg-light text-primary d-flex align-items-center justify-content-center"
                                                            style="width:32px;height:32px;font-size:12px">
                                                            {{ substr($g->name, 0, 2) }}
                                                        </div>
                                                    @endif
                                                    {{ $g->name }}
                                                </div>
                                            </td>
                                            <td>{{ $g->nis ?? '-' }}</td>
                                            <td>{{ $g->email }}</td>
                                            <td>{{ $g->no_telp ?? '-' }}</td>
                                            <td>{{ $g->mapel ?? '-' }}</td>
                                            <td>{{ $g->kelas_diampu ?? '-' }}</td>
                                            <td class="text-end">
                                                <button type="button"
                                                    class="btn btn-outline-primary btn-sm me-1 btn-edit-guru"
                                                    data-bs-toggle="modal" data-bs-target="#modalEditGuru"
                                                    data-guru="{{ $g }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>

                                                <!-- DELETE BUTTON (Trigger Modal) -->
                                                <button type="button" class="btn btn-outline-danger btn-sm" title="Hapus"
                                                    data-bs-toggle="modal" data-bs-target="#modalDeleteGuru"
                                                    data-id="{{ $g->id }}" data-nama="{{ $g->name }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                Belum ada data guru.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $guru->links() }}
                        </div>
                    </div>
                </div>


            </section>
        </main>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // EDIT Elements
            const editButtons = document.querySelectorAll('.btn-edit-guru');
            const editModal = document.getElementById('modalEditGuru');
            const editForm = editModal.querySelector('form');
            const scheduleContainer = document.getElementById('scheduleContainer');
            const btnAddSchedule = document.getElementById('btnAddSchedule');

            // CREATE Elements
            const scheduleContainerCreate = document.getElementById('scheduleContainerCreate');
            const btnAddScheduleCreate = document.getElementById('btnAddScheduleCreate');

            // Elements in Edit Form
            const inputName = editModal.querySelector('input[name="name"]');
            const inputNis = editModal.querySelector('input[name="nis"]');
            const inputUsername = editModal.querySelector('input[name="username"]');
            const inputEmail = editModal.querySelector('input[name="email"]');
            const inputTelp = editModal.querySelector('input[name="no_telp"]');
            const inputMapel = editModal.querySelector('input[name="mapel"]');

            // List Kelas for Select Options
            const daftarKelas = @json($daftarKelas ?? []);

            function createScheduleRow(container, data = {}) {
                const index = container.children.length;
                const row = document.createElement('div');
                row.className = 'row g-2 mb-2 align-items-end schedule-row';

                let kelasOptions = '<option value="">Pilih Kelas</option>';
                daftarKelas.forEach(k => {
                    const selected = data.kelas_id == k.id ? 'selected' : '';
                    kelasOptions += `<option value="${k.id}" ${selected}>${k.nama_kelas}</option>`;
                });

                row.innerHTML = `
                    <div class="col-3">
                        <select name="jadwals[${index}][hari]" class="form-select form-select-sm" required>
                            <option value="">Hari</option>
                            <option value="Senin" ${data.hari == 'Senin' ? 'selected' : ''}>Senin</option>
                            <option value="Selasa" ${data.hari == 'Selasa' ? 'selected' : ''}>Selasa</option>
                            <option value="Rabu" ${data.hari == 'Rabu' ? 'selected' : ''}>Rabu</option>
                            <option value="Kamis" ${data.hari == 'Kamis' ? 'selected' : ''}>Kamis</option>
                            <option value="Jumat" ${data.hari == 'Jumat' ? 'selected' : ''}>Jumat</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <select name="jadwals[${index}][kelas_id]" class="form-select form-select-sm" required>
                            ${kelasOptions}
                        </select>
                    </div>
                    <div class="col-3">
                        <input type="time" name="jadwals[${index}][jam_mulai]" class="form-control form-select-sm" value="${data.jam_mulai || ''}" required>
                    </div>
                    <div class="col-3">
                        <input type="time" name="jadwals[${index}][jam_selesai]" class="form-control form-select-sm" value="${data.jam_selesai || ''}" required>
                    </div>
                    <div class="col-1 text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-schedule">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;

                row.querySelector('.btn-remove-schedule').addEventListener('click', function () {
                    row.remove();
                });

                container.appendChild(row);
            }

            // Edit Modal Listener
            btnAddSchedule.addEventListener('click', () => createScheduleRow(scheduleContainer));

            // Create Modal Listener
            if (btnAddScheduleCreate && scheduleContainerCreate) {
                btnAddScheduleCreate.addEventListener('click', () => createScheduleRow(scheduleContainerCreate));
                // Add default empty row for Create
                createScheduleRow(scheduleContainerCreate);
            }

            editButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const data = JSON.parse(this.getAttribute('data-guru'));

                    // Set Action URL
                    editForm.action = `/tatausaha/guru/${data.id}`;

                    // Populate Fields
                    inputName.value = data.name;
                    inputNis.value = data.nis || '';
                    inputUsername.value = data.username;
                    inputEmail.value = data.email;
                    inputTelp.value = data.no_telp || '';
                    inputMapel.value = data.mapel || '';

                    // Populate Schedule
                    scheduleContainer.innerHTML = '';
                    if (data.jadwals && data.jadwals.length > 0) {
                        data.jadwals.forEach(j => createScheduleRow(scheduleContainer, j));
                    } else {
                        // Optional: Add one empty row if no schedule
                        // createScheduleRow(scheduleContainer); 
                    }
                });
            });

            // Delete Modal Listener
            const modalDeleteGuru = document.getElementById('modalDeleteGuru');
            if (modalDeleteGuru) {
                modalDeleteGuru.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const id = button.getAttribute('data-id');
                    const name = button.getAttribute('data-nama');

                    this.querySelector('#deleteGuruName').textContent = name;
                    this.querySelector('#formDeleteGuru').action = `/tatausaha/guru/${id}`;
                });
            }
        });
    </script>

    <!-- ==================== MOVED MODALS ==================== -->

    <!-- MODAL TAMBAH GURU -->
    <div class="modal fade" id="modalTambahGuru" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content tu-modal">
                <form action="{{ route('tatausaha.guru.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Tambah Data Guru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required
                                placeholder="Nama lengkap guru">
                        </div>
                        <div class="row g-2">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">NIP <span class="text-danger">*</span></label>
                                <input type="number" name="nis" class="form-control" placeholder="Opsional">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" required
                                    placeholder="Min. 6 karakter">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" name="no_telp" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mata Pelajaran</label>
                                <input type="text" name="mapel" class="form-control" placeholder="Opsional">
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">Jadwal Mengajar</label>
                                <button type="button" class="btn btn-sm btn-outline-success" id="btnAddScheduleCreate">
                                    <i class="bi bi-plus-lg"></i> Tambah
                                </button>
                            </div>
                            <div id="scheduleContainerCreate">
                                <!-- Dynamic Rows Here -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-0 mt-3">
                        <button type="submit" class="btn btn-primary w-100">Simpan Data Guru</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT GURU -->
    <div class="modal fade" id="modalEditGuru" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content tu-modal">
                <form action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Edit Data Guru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">NIP</label>
                                <input type="number" name="nis" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Kosongkan jika tidak ubah">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" name="no_telp" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mata Pelajaran</label>
                                <input type="text" name="mapel" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">Jadwal Mengajar</label>
                                <button type="button" class="btn btn-sm btn-outline-success" id="btnAddSchedule">
                                    <i class="bi bi-plus-lg"></i> Tambah
                                </button>
                            </div>
                            <div id="scheduleContainer">
                                <!-- Dynamic Rows Here -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-0 mt-3">
                        <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL DELETE GURU -->
    <div class="modal fade" id="modalDeleteGuru" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="" method="POST" id="formDeleteGuru">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold text-danger">Hapus Data Guru?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <div class="mb-3">
                            <i class="bi bi-exclamation-circle text-danger display-1"></i>
                        </div>
                        <p class="mb-1 fs-5">Apakah anda yakin ingin menghapus guru ini?</p>
                        <h4 class="fw-bold" id="deleteGuruName">...</h4>
                        <div class="alert alert-warning mt-3 small text-start">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            <strong>Perhatian:</strong> Semua data jadwal mengajar dan absensi kelas yang diampu oleh
                            guru ini akan ikut terhapus secara permanen.
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

    <!-- Auto-Alert for Session Errors -->
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                alert("{{ session('error') }}");
            });
        </script>
    @endif
</body>

</html>