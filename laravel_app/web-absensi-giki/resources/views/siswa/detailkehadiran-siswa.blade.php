<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kehadiran | Absensi SMP GIKI 2 Surabaya</title>
    
    <link rel="icon" type="image/png" href="{{ asset('uploads/logo-giki.png') }}">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/stylesiswa.css') . '?v=' . time() }}" rel="stylesheet">
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
            <a href="{{ route('siswa.profil') }}" class="btn-icon" title="Akun">
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
            <div class="container-fluid px-3 px-md-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <div class="brand-title mb-1">
                            Absensi Siswa <span class="fw-normal">| SMP GIKI 2 Surabaya</span>
                        </div>
                        <div class="fw-semibold">{{ Auth::user()->name }} - {{ Auth::user()->kelas ?? '' }}</div>
                    </div>

                    <div class="profile-chip">
                        <div class="avatar">
                            @if(Auth::user()->foto)
                                <img src="{{ asset(Auth::user()->foto) }}" alt="Foto Profil" style="width:100%;height:100%;object-fit:cover;border-radius:50%">
                            @else
                                {{ Str::upper(substr(Auth::user()->name, 0, 1)) }}
                            @endif
                        </div>
                        <div style="line-height:1">
                            <div class="fw-semibold small">{{ Auth::user()->name }}</div>
                            <div class="text-secondary" style="font-size:.75rem">
                                NIS. {{ Auth::user()->nis ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==================== CARD DETAIL KEHADIRAN ==================== -->
            <div class="detail-card shadow-sm rounded-4 overflow-hidden">
                <div class="detail-card__header px-4 py-3 text-white">
                    Detail Kehadiran
                </div>
                
                <div class="p-3 bg-light border-bottom">
                    <form action="{{ route('siswa.detail') }}" method="GET" class="row g-2 align-items-center">
                        @if(request('tanggal'))
                            <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">
                        @endif
                        <div class="col-auto">
                            <label class="visually-hidden">Mata Pelajaran</label>
                            <select name="mapel" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Semua Mapel</option>
                                @foreach($mapelList as $m)
                                    <option value="{{ $m }}" {{ ($selectedMapel ?? '') == $m ? 'selected' : '' }}>
                                        {{ $m }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            @if(request()->filled('tanggal') || request()->filled('mapel'))
                                <a href="{{ route('siswa.detail') }}" class="btn btn-secondary btn-sm">
                                    <i class="bi bi-x-circle"></i> Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="bg-white p-0">
                    @if($absensi->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr class="text-secondary small">
                                        <th>Mata Pelajaran</th>
                                        <th>Nama Guru</th>
                                        <th>Hari & Tanggal</th>
                                        <th class="text-center">Status</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($absensi as $a)
                                        <tr>
                                            <td class="fw-semibold">{{ $a->mata_pelajaran }}</td>
                                            <td>{{ $a->guru?->name ?? '-' }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($a->tanggal)->translatedFormat('l, j F Y') }}
                                            </td>
                                            <td class="text-center">
                                                @switch($a->status)
                                                    @case('H')
                                                        <span class="badge bg-success">Hadir</span>
                                                        @break
                                                    @case('A')
                                                        <span class="badge bg-danger">Alfa</span>
                                                        @break
                                                    @case('I')
                                                        <span class="badge bg-warning text-dark">Izin</span>
                                                        @break
                                                    @case('S')
                                                        <span class="badge bg-info">Sakit</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">—</span>
                                                @endswitch
                                            </td>
                                            <td class="text-muted small">
                                                {{ $a->catatan ?? '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-4 text-muted"></i>
                            <p class="mt-3 text-muted">Belum ada data kehadiran.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-4 px-3 px-md-4">
                <a href="{{ route('siswa.jadwal') }}" class="btn btn-light border rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Jadwal
                </a>
            </div>
        </main>
    </div>

    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/Siswa.js') }}"></script>
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
