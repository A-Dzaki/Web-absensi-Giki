<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Absensi Guru | SMP GIKI 2 Surabaya</title>
    
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

    <!-- DASHBOARD -->
    <a href="{{ route('guru.dashboard') }}"
       class="btn-icon {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}"
       title="Beranda">
        <i class="bi bi-house-door"></i>
    </a>



    <!-- PROFIL -->
    <a href="{{ route('guru.profil') }}"
       class="btn-icon {{ request()->routeIs('guru.profil') ? 'active' : '' }}"
       title="Akun">
        <i class="bi bi-person"></i>
    </a>

    <!-- FOOTER -->
    <div class="sidebar-footer">

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
        <main class="page">
            <div class="page-header d-flex justify-content-between align-items-start">
                <button class="hamburger d-md-none" aria-label="Toggle navigation">
    <span></span>
    <span></span>
    <span></span>
</button>

                <div>
                    <h5 class="brand-title mb-1">
                        Kelola Absensi <span class="fw-normal">| SMP GIKI 2 Surabaya</span>
                    </h5>
                    <div class="subtle">
                        <span id="labelKelasMapel">{{ request('kelas') }} • {{ request('mapel') }}</span>
                    </div>
                    <form id="formTanggal" action="{{ route('guru.kelola') }}" method="GET" class="mt-2">
                        <input type="hidden" name="kelas" value="{{ request('kelas') }}">
                        <input type="hidden" name="mapel" value="{{ request('mapel') }}">
                        <input type="hidden" name="jadwal_id" value="{{ request('jadwal_id') }}">
                        <input type="date" name="tanggal" 
                               value="{{ $tanggal }}" 
                               max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                               class="form-control form-control-sm d-inline-block w-auto" 
                               onchange="document.getElementById('formTanggal').submit()">
                    </form>
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
                        <div class="text-secondary" style="font-size:.75rem">NIP. {{ Auth::user()->nis ?? '-' }}</div>
                    </div>
                </a>
            </div>

            <!-- ==================== FORM KELOLA ABSENSI ==================== -->
            <section class="wrap" id="view-kelola">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div></div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('guru.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="button" class="btn btn-outline-danger btn-sm" id="btnResetAbsensi">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset / Kosongkan
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="btnAllHadir">
                            <i class="bi bi-check2-all"></i> Semua Hadir
                        </button>
                    </div>
                </div>

                <form id="formAbsensi" action="{{ route('guru.simpan') }}" method="POST">
                    @csrf

                    @if(session('error'))
                        <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <input type="hidden" name="kelas" value="{{ request('kelas') }}">
                    <input type="hidden" name="mapel" value="{{ request('mapel') }}">
                    <input type="hidden" name="pertemuan" value="{{ $pertemuan ?? 1 }}">

                    <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                    <div class="card card-soft p-3">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th style="90px">NIS</th>
                                        <th>Nama Siswa</th>
                                        <th style="width:140px">Jenis Kelamin</th>
                                        <th class="text-center">Hadir</th>
                                        <th class="text-center">Alfa</th>
                                        <th class="text-center">Izin</th>
                                        <th class="text-center">Sakit</th>
                                        <th class="text-end">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($siswa as $s)
                                        <tr>
                                            <td>{{ $s->nis }}</td>
                                            <td>{{ $s->name }}</td>
                                            <td>{{ $s->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>

                                            <!-- Radio buttons dengan name unik per siswa -->
                                            @php
                                                // Ambil status absensi dari relation absensis (jika ada)
                                                // Karena hasMany, dan kita filter di query, kita ambil first()
                                                $status = $s->absensis->first()->status ?? '';
                                                $catatan = $s->absensis->first()->catatan ?? '';
                                            @endphp
                                            <td class="text-center">
                                                <input type="radio" name="absen[{{ $s->id }}]" value="H"
                                                       {{ $status == 'H' ? 'checked' : '' }}
                                                       required>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="absen[{{ $s->id }}]" value="A"
                                                       {{ $status == 'A' ? 'checked' : '' }}>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="absen[{{ $s->id }}]" value="I"
                                                       {{ $status == 'I' ? 'checked' : '' }}>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="absen[{{ $s->id }}]" value="S"
                                                       {{ $status == 'S' ? 'checked' : '' }}>
                                            </td>

                                            <td class="text-end">
                                                <input type="text" name="catatan[{{ $s->id }}]"
                                                       class="form-control form-control-sm w-100"
                                                       placeholder="Opsional"
                                                       value="{{ $catatan }}"
                                                       {{ str_contains($catatan, 'Terlambat via RFID') ? 'readonly' : '' }}>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Links -->

                        <div class="sticky-actions d-flex justify-content-end gap-2 mt-3">
                            <button type="submit" class="btn btn-primary" id="btnSimpanSemua">
                                <i class="bi bi-save2"></i> Simpan Absensi
                            </button>
                        </div>
                    </div>
                </form>
            </section>
        </main>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/Guru.js') }}"></script>

    <script>
        // Tombol "Semua Hadir"
        document.getElementById('btnAllHadir').addEventListener('click', function() {
            document.querySelectorAll('input[value="H"]').forEach(radio => {
                radio.checked = true;
            });
            // Trigger change event to enable Save button
            document.querySelector('input[value="H"]').dispatchEvent(new Event('change'));
        });

        // Tombol "Reset/Kosongkan"
        document.getElementById('btnResetAbsensi').addEventListener('click', function() {
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.checked = false;
            });
        });

        // Aktifkan tombol Simpan saat ada perubahan
        document.querySelectorAll('input[type=radio]').forEach(input => {
            input.addEventListener('change', function() {
                document.getElementById('btnSimpanSemua').disabled = false;
                document.getElementById('btnSimpanSemua').classList.add('btn-primary');
                document.getElementById('btnSimpanSemua').classList.remove('btn-secondary');
            });
        });

        // Optional: SweetAlert saat simpan berhasil
        @if(session('success'))
            toast('{{ session('success') }}');
        @endif
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