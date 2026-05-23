<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Mapel | Absensi SMP GIKI 2 Surabaya</title>
    
    <link rel="icon" type="image/png" href="{{ asset('uploads/logo-giki.png') }}">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
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

            <a href="{{ route('siswa.jadwal') }}" class="btn-icon active" title="Jadwal">
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
                        <span class="fw-semibold">Jadwal Mapel</span>
                        <span class="ms-1">| {{ now()->translatedFormat('l, j F Y') }}</span>
                    </div>
                </div>

                <a href="{{ route('siswa.profil') }}" class="profile-chip text-decoration-none text-reset">
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
                </a>
            </div>

            <!-- ==================== DAFTAR MAPEL ==================== -->
            <section id="viewMapel" class="view active">
                <div class="schedule-wrap">
                    @php
                        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                        $isEmpty = true;
                    @endphp

                    @foreach($days as $hari)
                        @if(isset($jadwalPerHari[$hari]) &&count($jadwalPerHari[$hari]) > 0)
                            @php $isEmpty = false; @endphp
                            <h5 class="mb-3 fw-bold text-secondary">{{ $hari }}</h5>
                            <div class="row g-4 mb-4">
                                @foreach($jadwalPerHari[$hari] as $index => $j)
                                    <div class="col-12 col-md-6 col-xl-4">
                                        <div class="card-lesson c{{ ($index % 6) + 1 }}">
                                            <div class="title">
                                                {{ $j->mata_pelajaran }}
                                            </div>
                                            <div class="line"></div>
                                            <div class="meta d-flex justify-content-between">
                                                <span>{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</span>
                                            </div>
                                            <br>
                                            <div class="text-muted small mb-3">
                                                <i class="bi bi-person me-1"></i> {{ $j->guru->name ?? 'Guru belum ditentukan' }}
                                            </div>
                                            
                                            <a href="{{ route('siswa.detail') }}?mapel={{ urlencode($j->mata_pelajaran) }}"
                                               class="btn btn-status w-100">
                                                Detail Kehadiran
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endforeach

                    @if($isEmpty)
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x display-1 text-muted"></i>
                            <p class="mt-3 text-muted fs-5">Belum ada jadwal untuk minggu ini.</p>
                        </div>
                    @endif
                </div>
            </section>
        </main>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
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