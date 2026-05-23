<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Kelas - Tata Usaha | SMP GIKI 2 Surabaya</title>
    
    <link rel="icon" type="image/png" href="{{ asset('uploads/logo-giki.png') }}">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/styletu.css') . '?v=' . time() }}" rel="stylesheet">
</head>

<body data-page="kelas">
    <div class="app">
        @include('components.settings-modal')

        <!-- ==================== SIDEBAR ==================== -->
        <aside class="sidebar d-flex flex-column align-items-center py-3">
            <a href="{{ route('tatausaha.dashboard') }}" class="btn-icon" title="Beranda">
                <i class="bi bi-house-door"></i>
            </a>
            <a href="{{ route('tatausaha.data-kelas') }}" class="btn-icon active" title="Kelola Absensi">
                <i class="bi bi-journal-text"></i>
            </a>
            <a href="{{ route('tatausaha.data-guru') }}" class="btn-icon" title="Kelola Guru">
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
                <button class="hamburger d-md-none" aria-label="Toggle navigation">
    <span></span>
    <span></span>
    <span></span>
</button>

                <div class="topbar-title">
                    <h1>Absensi Siswa | SMP GIKI 2 Surabaya</h1>
                    <p class="topbar-subtitle">Data Kelas</p>
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
                        <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
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

                <!-- ==================== HEADER KELAS ==================== -->
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <div>
                        <h2 class="page-heading mb-0">Kelas {{ $kelas }}</h2>
                        @if($kelasData)
                            <div class="text-muted small">
                                Wali Kelas: <strong>{{ $kelasData->walikelas }}</strong>
                                <a href="#" class="ms-2 text-primary text-decoration-none" data-bs-toggle="modal"
                                    data-bs-target="#modalEditKelas" data-id="{{ $kelasData->id }}"
                                    data-nama="{{ $kelasData->nama_kelas }}" data-wali="{{ $kelasData->walikelas }}"
                                    onclick="populateEditKelas(this)">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <a href="#" class="ms-2 text-danger text-decoration-none" data-bs-toggle="modal"
                                    data-bs-target="#modalDeleteKelas"
                                    onclick="setDeleteKelas('{{ $kelasData->id }}', '{{ $kelasData->nama_kelas }}')">
                                    <i class="bi bi-trash"></i> Hapus
                                </a>
                            </div>
                        @else
                            <div class="text-muted small fst-italic">Data kelas belum lengkap</div>
                        @endif
                    </div>
                    <div class="ms-auto d-flex gap-2">
                        <select class="form-select form-select-sm rounded-pill tu-select" id="pilihKelas"
                            onchange="window.location.href='{{ route('tatausaha.data-kelas') }}?kelas='+this.value">
                            @foreach($daftarKelas as $k)
                                <option value="{{ $k }}" {{ $k == $kelas ? 'selected' : '' }}>Kelas {{ $k }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal"
                            data-bs-target="#modalListBackup" title="Riwayat Backup">
                            <i class="bi bi-clock-history"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                            data-bs-target="#modalResetTahun">
                            <i class="bi bi-arrow-repeat me-1"></i>Reset Siswa
                        </button>
                        <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal"
                            data-bs-target="#modalImportSiswa">
                            <i class="bi bi-file-earmark-spreadsheet me-1"></i>Import Excel
                        </button>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#modalKelas">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Kelas
                        </button>
                        <button class="btn btn-sm btn-tambah" data-bs-toggle="modal" data-bs-target="#modalSiswa">
                            <i class="bi bi-person-plus me-1"></i>Tambah Siswa
                        </button>
                    </div>
                </div>

                <!-- ... (Tabs and Content remain the same) ... -->

                <!-- ==================== TAB ==================== -->
                <ul class="nav nav-underline tu-tabs mb-3" id="tabSiswa" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-daftar" type="button"
                            role="tab">Daftar Siswa</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-absen" type="button"
                            role="tab">Daftar Absensi</button>
                    </li>
                </ul>

                <div class="tab-content" id="tabContentSiswa">

                    <!-- ==================== TAB DAFTAR SISWA ==================== -->
                    <div id="tab-daftar" class="tab-pane fade show active" role="tabpanel">
                        <div class="panel">
                            <div class="panel-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <h2 class="mb-0">Daftar Siswa Kelas {{ $kelas }}</h2>
                                <form action="{{ route('tatausaha.data-kelas') }}" method="GET" class="d-flex gap-2">
                                    <!-- Preserve Class Selection -->
                                    <input type="hidden" name="kelas" value="{{ $kelas }}">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white border-end-0"><i
                                                class="bi bi-search text-muted"></i></span>
                                        <input type="text" name="search" class="form-control border-start-0 ps-0"
                                            placeholder="Cari nama siswa..." value="{{ $search ?? '' }}">
                                        <button class="btn btn-outline-secondary" type="submit">Cari</button>
                                    </div>
                                </form>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table tu-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>NIS</th>
                                                <th>Nama Siswa</th>
                                                <th>Jenis Kelamin</th>
                                                <th>UID Kartu</th>
                                                <th>No. Telepon</th> <!-- NEW -->
                                                <th>Alamat</th>
                                                <th class="text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($siswa as $s)
                                                <tr>
                                                    <td>{{ $s->nis }}</td>
                                                    <td>{{ $s->name }}</td>
                                                    <td>{{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                                    <td><span
                                                            class="badge bg-light text-dark border">{{ $s->uid_rfid ?? '-' }}</span>
                                                    </td>
                                                    <td>{{ $s->no_telp ?? '-' }}</td> <!-- NEW -->
                                                    <td>{{ $s->alamat ?? '-' }}</td>
                                                    <td class="text-end">
                                                        <button class="btn btn-sm btn-outline-primary me-1 btn-edit"
                                                            data-siswa="{{ $s }}" data-bs-toggle="modal"
                                                            data-bs-target="#modalSiswa">
                                                            Edit
                                                        </button>
                                                        <form action="{{ route('tatausaha.siswa.destroy', $s->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                onclick="return confirm('Yakin hapus {{ $s->name }}?')">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted py-4">
                                                        Belum ada siswa di kelas ini.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="panel-footer mt-3 px-3">
                                {{ $siswa->links() }}
                            </div>
                        </div>
                    </div>

                    <!-- ==================== TAB ABSENSI ==================== -->
                    <div id="tab-absen" class="tab-pane fade" role="tabpanel">
                        <div class="panel">
                            <div class="panel-header d-flex flex-wrap align-items-center gap-2">
                                <h2 class="mb-0">Rekap Absensi Kelas {{ $kelas }}</h2>
                                <div class="ms-auto d-flex flex-wrap gap-2">
                                    <!-- Filter Mapel -->
                                    <select class="form-select form-select-sm tu-select" style="width:auto;"
                                        onchange="updateFilter('mapel', this.value)">
                                        <option value="">Semua Mapel</option>
                                        @foreach($daftarMapel as $m)
                                            <option value="{{ $m }}" {{ $mapel == $m ? 'selected' : '' }}>{{ $m }}</option>
                                        @endforeach
                                    </select>

                                    <!-- Filter Bulan -->
                                    <select class="form-select form-select-sm tu-select" style="width:auto;"
                                        onchange="updateFilter('bulan', this.value)">
                                        @foreach(range(1, 12) as $m)
                                            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <!-- Filter Tahun -->
                                    <select class="form-select form-select-sm tu-select" style="width:auto;"
                                        onchange="updateFilter('tahun', this.value)">
                                        @foreach(range(date('Y'), date('Y') - 5) as $y)
                                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endforeach
                                    </select>

                                    <!-- Export Buttons -->
                                    <div class="vr mx-1"></div>
                                    <a href="{{ route('tatausaha.rekap.cetak-pdf', ['kelas' => $kelas, 'bulan' => $bulan, 'tahun' => $tahun, 'mapel' => $mapel]) }}"
                                        target="_blank" class="btn btn-sm btn-outline-danger" title="Cetak PDF">
                                        <i class="bi bi-file-earmark-pdf"></i> PDF
                                    </a>
                                    <a href="{{ route('tatausaha.rekap.export-excel', ['kelas' => $kelas, 'bulan' => $bulan, 'tahun' => $tahun, 'mapel' => $mapel]) }}"
                                        target="_blank" class="btn btn-sm btn-outline-success" title="Export Excel">
                                        <i class="bi bi-file-earmark-excel"></i> Excel
                                    </a>
                                </div>
                            </div>
                            <div class="panel-body p-0">
                                <div class="table-responsive">
                                    <table class="table tu-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>NIS</th>
                                                <th>Nama Siswa</th>
                                                <th>Hadir</th>
                                                <th>Izin</th>
                                                <th>Sakit</th>
                                                <th>Alpa</th>
                                                <th>Persentase (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rekapAbsensi as $r)
                                                <tr>
                                                    <td>{{ $r->nis }}</td>
                                                    <td>{{ $r->name }}</td>
                                                    <td>{{ $r->hadir }}</td>
                                                    <td>{{ $r->izin }}</td>
                                                    <td>{{ $r->sakit }}</td>
                                                    <td>{{ $r->alpa }}</td>
                                                    <td>
                                                        <strong>{{ $r->persentase }}%</strong>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- (Modals moved to bottom of body) -->
            </section>
        </main>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script>
        // ... (Scripts remain same) ...
        // Ganti tab
        document.querySelectorAll('.tu-tabs .nav-link').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.tu-tabs .nav-link').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-pane-tu').forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                document.querySelector(this.dataset.target).classList.add('active');
            });
        });

        // Edit Siswa → isi modal
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function () {
                const s = JSON.parse(this.dataset.siswa);
                document.getElementById('modalTitle').textContent = 'Edit Data Siswa';
                document.getElementById('siswaId').value = s.id;
                document.getElementById('modalNis').value = s.nis;
                document.getElementById('modalNama').value = s.name;
                document.getElementById('modalUid').value = s.uid_rfid || '';
                document.getElementById('modalJK').value = s.jenis_kelamin == 'Laki-laki' ? 'L' : 'P';
                document.getElementById('modalAlamat').value = s.alamat || '';
                document.getElementById('modalEmail').value = s.email || '';
                const form = document.querySelector('#modalSiswa form');
                form.action = `/tatausaha/siswa/${s.id}`;
                if (!form.querySelector('input[name="_method"]')) {
                    form.insertAdjacentHTML('beforeend', '<input type="hidden" name="_method" value="PUT">');
                }
                document.getElementById('btnHapusModal').classList.remove('d-none');
            });
        });

        // Reset modal saat ditutup
        document.getElementById('modalSiswa').addEventListener('hidden.bs.modal', function () {
            document.getElementById('modalTitle').textContent = 'Tambah Siswa Baru';
            const form = document.querySelector('#modalSiswa form');
            form.action = '{{ route('tatausaha.siswa.store') }}';
            form.reset();
            document.getElementById('siswaId').value = '';

            form.querySelector('input[name="_method"]')?.remove();
            document.getElementById('btnHapusModal').classList.add('d-none');
        });
        // Populate Edit Kelas Modal (Direct Function)
        function populateEditKelas(el) {
            // If icon clicked, find parent anchor
            if (!el.hasAttribute('data-id')) {
                el = el.closest('a');
            }

            var id = el.getAttribute('data-id');
            var nama = el.getAttribute('data-nama');
            var wali = el.getAttribute('data-wali');

            document.getElementById('editKelasId').value = id;
            document.getElementById('editNamaKelas').value = nama;
            document.getElementById('editWaliKelas').value = wali;
        }

        // Direct Function to Set Delete Action (More Robust)
        function setDeleteKelas(id, nama) {
            document.getElementById('deleteKelasName').textContent = nama;
            document.getElementById('formDeleteKelas').action = `/tatausaha/kelas/${id}`;
        }

        // Update Filter Rekap
        function updateFilter(key, value) {
            const url = new URL(window.location.href);
            if (value) {
                url.searchParams.set(key, value);
            } else {
                url.searchParams.delete(key);
            }
            localStorage.setItem('activeTab', '#tab-absen');
            window.location.href = url.toString();
        }

        // Restore Tab on Load
        document.addEventListener('DOMContentLoaded', function () {
            // Restore Tab
            const activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                const triggerEl = document.querySelector(`button[data-bs-target="${activeTab}"]`);
                if (triggerEl) {
                    const tab = new bootstrap.Tab(triggerEl);
                    tab.show();
                }
            }

            // Save Tab on Click
            document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(btn => {
                btn.addEventListener('shown.bs.tab', function (e) {
                    localStorage.setItem('activeTab', e.target.getAttribute('data-bs-target'));
                });
            });
        });
    </script>

    <!-- ==================== DATALK MODALS MOVED HERE ==================== -->

    <!-- MODAL TAMBAH KELAS -->
    <div class="modal fade" id="modalKelas" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content tu-modal">
                <form action="{{ route('tatausaha.kelas.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Tambah Kelas Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kelas" class="form-control" placeholder="Contoh: 7A" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Wali Kelas <span class="text-danger">*</span></label>
                            <select name="walikelas" class="form-select" required>
                                <option value="" disabled selected>Pilih Wali Kelas</option>
                                @foreach($guru as $g)
                                    <option value="{{ $g->name }}">{{ $g->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-0 mt-3">
                        <button type="submit" class="btn btn-primary w-100">Simpan Kelas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT KELAS -->
    <div class="modal fade" id="modalEditKelas" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content tu-modal">
                <form action="{{ route('tatausaha.kelas.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="editKelasId">
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Edit Data Kelas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kelas" id="editNamaKelas" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Wali Kelas <span class="text-danger">*</span></label>
                            <select name="walikelas" id="editWaliKelas" class="form-select" required>
                                <option value="" disabled>Pilih Wali Kelas</option>
                                @foreach($guru as $g)
                                    <option value="{{ $g->name }}">{{ $g->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-0 mt-3">
                        <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH/EDIT SISWA -->
    <div class="modal fade" id="modalSiswa" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content tu-modal">
                <form action="{{ route('tatausaha.siswa.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="siswaId">

                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="modalTitle">Tambah Siswa Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-2">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NIS <span class="text-danger">*</span></label>
                                <input type="text" name="nis" id="modalNis" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Siswa <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="modalNama" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">UID Kartu (RFID)</label>
                            <input type="text" name="uid_rfid" id="modalUid" class="form-control"
                                placeholder="Scan Kartu Disini">
                        </div>

                        <div class="mb-3">
                             <label class="form-label">
                                 Email Siswa <span class="text-danger">*</span>
                             </label>

                             <input
                                type="email"
                                name="email"
                                id="modalEmail"
                                class="form-control"
                                placeholder="contoh@gmail.com"
                                required
                             >
                    
                             <small class="text-muted">
                            L   ink setup akun akan dikirim ke email ini
                             </small>
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kelas <span class="text-danger">*</span></label>
                                <select name="kelas" id="modalKelasInput" class="form-select" required>
                                    @foreach($daftarKelas as $k)
                                        <option value="{{ $k }}" {{ $k == $kelas ? 'selected' : '' }}>{{ $k }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select name="jenis_kelamin" id="modalJK" class="form-select">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" id="modalAlamat" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 justify-content-between">
                        <button type="button" class="btn btn-outline-danger d-none" id="btnHapusModal">
                            Hapus Siswa
                        </button>
                        <div>
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL IMPORT EXCEL -->
    <div class="modal fade" id="modalImportSiswa" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content tu-modal">
                <form action="{{ route('tatausaha.siswa.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Import Data Siswa (Excel)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info small mb-3">
                            <strong>Format Excel:</strong><br>
                            Kolom A: <code>nis</code><br>
                            Kolom B: <code>nama</code><br>
                            Kolom C: <code>kelas</code> (Misal: 9A)<br>
                            Kolom D: <code>jenis_kelamin</code> (L/P)<br>
                            Kolom E: <code>alamat</code> (Opsional)
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pilih File Excel (.xlsx / .csv)</label>
                            <input type="file" name="file_excel" class="form-control" accept=".xlsx, .xls, .csv"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-0 mt-3">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-upload me-1"></i> Upload & Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL RESET TAHUN AJARAN -->
    <div class="modal fade" id="modalResetTahun" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-danger">
                <form action="{{ route('tatausaha.siswa.reset') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold text-danger">Reset Tahun Ajaran?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <div class="mb-3">
                            <i class="bi bi-exclamation-triangle-fill text-danger display-1"></i>
                        </div>
                        <h5 class="fw-bold mb-3">PERINGATAN KERAS!</h5>
                        <p class="mb-3">
                            Tindakan ini akan <strong>MENGHAPUS SEMUA DATA SISWA</strong> dan <strong>SEMUA RIWAYAT
                                ABSENSI</strong> secara permanen.
                        </p>
                        <p class="text-muted small">
                            Fitur ini digunakan saat pergantian tahun ajaran baru. Kelas akan menjadi kosong dan siap
                            diisi siswa baru (via Import Excel).
                        </p>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Ketik <strong>RESET</strong> untuk
                                konfirmasi:</label>
                            <input type="text" class="form-control text-center" id="confirmResetInput"
                                onkeyup="checkReset(this)" required placeholder="RESET">
                        </div>
                    </div>
                    <div class="modal-footer border-0 justify-content-center pt-0 pb-4">
                        <button type="button" class="btn btn-secondary px-4 me-2" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger px-4" id="btnConfirmReset" disabled>Ya, Hapus Semua
                            Siswa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function checkReset(input) {
            const btn = document.getElementById('btnConfirmReset');
            if (input.value === 'RESET') {
                btn.removeAttribute('disabled');
            } else {
                btn.setAttribute('disabled', 'disabled');
            }
        }
    </script>


    <!-- MODAL LIST BACKUP -->
    <div class="modal fade" id="modalListBackup" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Riwayat Backup Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($backups as $b)
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                                <div>
                                    <h6 class="mb-1 text-primary fw-bold">{{ $b->name }}</h6>
                                    <small class="text-muted">
                                        {{ $b->created_at->format('d M Y, H:i') }} •
                                        <strong>{{ $b->total_records }}</strong> Siswa
                                    </small>
                                </div>
                                <form action="{{ route('tatausaha.siswa.restore', $b->id) }}" method="POST"
                                    onsubmit="return confirm('Kembalikan data dari backup ini? Data saat ini tidak akan dihapus, hanya data yang hilang akan ditambahkan.')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted">Belum ada data backup.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL HAPUS KELAS -->
    <div class="modal fade" id="modalDeleteKelas" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-danger">
                <form action="" method="POST" id="formDeleteKelas">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold text-danger">Hapus Kelas?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <div class="mb-3">
                            <i class="bi bi-exclamation-circle text-danger display-1"></i>
                        </div>
                        <p class="mb-1 fs-5">Apakah anda yakin ingin menghapus kelas ini?</p>
                        <h4 class="fw-bold" id="deleteKelasName">...</h4>
                        <div class="alert alert-warning mt-3 small text-start">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            <strong>Perhatian:</strong> Semua siswa dan jadwal yang terkait dengan kelas ini akan tetap ada, namun kelas ini tidak akan muncul lagi di daftar kelas.
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

    <!-- Login/Logout Modal Place -->
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