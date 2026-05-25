<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru — Beranda | Absensi SMP GIKI 2 Surabaya</title>
    
    <link rel="icon" type="image/png" href="{{ asset('uploads/logo-giki.png') }}">
    <!-- Bootstrap CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS Guru -->
    <link href="{{ asset('css/styleguruu.css') . '?v=' . time() }}" rel="stylesheet">
</head>

<body>
    <div class="app">
        @include('components.settings-modal')

        <!-- ==================== SIDEBAR ==================== -->
        <aside class="sidebar d-flex flex-column align-items-center py-3">
            <a href="{{ route('guru.dashboard') }}" class="btn-icon active" title="Beranda">
                <i class="bi bi-house-door"></i>
            </a>

            <a href="#" class="btn-detail disabled" title="Pilih kelas dulu">
                Detail Absensi Siswa
            </a>

            <a href="{{ route('guru.profil') }}" class="btn-icon" title="Akun">
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
            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Header -->
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
                    <div class="subtle">
                        <span id="labelMapel">{{ Auth::user()->mapel ?? 'Matematika' }}</span>
                    </div>
                </div>

                <!-- Profile Chip -->
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

            <!-- ==================== DAFTAR KELAS HARI INI ==================== -->
            <!-- ==================== DAFTAR KELAS MINGGUAN ==================== -->
            <section class="wrap">
                @php
                    $d = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                @endphp

                @foreach($d as $hari)
                    @if(isset($jadwalPerHari[$hari]) && count($jadwalPerHari[$hari]) > 0)
                        <h5 class="mb-3 fw-bold text-secondary">{{ $hari }}</h5>
                        <div class="row g-4 mb-4">
                            @foreach($jadwalPerHari[$hari] as $index => $k)
                                @php
                                    $colors = ['c1', 'c2', 'c3', 'c4'];
                                    $warna = $colors[$index % count($colors)];
                                @endphp
                                <div class="col-12 col-md-6 col-xl-4">
                                    <div class="card-lesson {{ $warna }}">
                                        <div class="title">{{ $k->mata_pelajaran }} {{ $k->kelas->nama_kelas }}</div>
                                        <div class="line"></div>
                                        <div class="meta">{{ \Carbon\Carbon::parse($k->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($k->jam_selesai)->format('H:i') }}</div>
        
                                        <div class="mt-3 stack">
                                            <div class="a-sm">{{ substr($k->kelas->nama_kelas, 0, 1) }}</div>
                                            <div class="a-sm">{{ substr($k->kelas->nama_kelas, 1, 1) }}</div>
                                            <div class="a-sm">+{{ $k->kelas->siswa_count }}</div>
                                        </div>
        
                                        <a href="{{ route('guru.kelola') }}?kelas={{ $k->kelas->nama_kelas }}&mapel={{ $k->mata_pelajaran }}&jadwal_id={{ $k->id }}"
                                           class="btn-detail">
                                            Detail Absensi Siswa
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach

                @if(!isset($jadwalPerHari) || count($jadwalPerHari) == 0)
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle me-1"></i> Belum ada jadwal mengajar yang diatur.
                        </div>
                    </div>
                @endif
            </section>

        </main>
    </div>

    <!-- Bootstrap JS & Custom JS Guru -->
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/Guru.js') }}"></script>

    <!-- Optional: Logout Confirmation -->
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
