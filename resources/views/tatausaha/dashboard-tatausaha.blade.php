<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Tata Usaha | Absensi SMP GIKI 2 Surabaya</title>
    
    <link rel="icon" type="image/png" href="{{ asset('uploads/logo-giki.png') }}">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/styletu.css') . '?v=' . time() }}" rel="stylesheet">
</head>

<body data-page="dashboard">
    <div class="app">
        @include('components.settings-modal')

        <!-- ==================== SIDEBAR ==================== -->
        <aside class="sidebar d-flex flex-column align-items-center py-3">
            <a href="{{ route('tatausaha.dashboard') }}" class="btn-icon active" title="Beranda">
                <i class="bi bi-house-door"></i>
            </a>
            <a href="{{ route('tatausaha.data-kelas') }}" class="btn-icon" title="Kelola Absensi">
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
            <!-- TOPBAR -->
            <header class="topbar">
                <button class="hamburger d-md-none" aria-label="Toggle navigation">
    <span></span>
    <span></span>
    <span></span>
</button>

                <div class="topbar-title">
                    <h1>Absensi Siswa | SMP GIKI 2 Surabaya</h1>
                    <p class="topbar-subtitle">
                        Dashboard &nbsp;|&nbsp;
                        <span class="text-primary fw-bold">Tanggal:
                            {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</span>
                    </p>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <form action="{{ route('tatausaha.dashboard') }}" method="GET" class="d-flex align-items-center">
                        <input type="date" name="tanggal" value="{{ $tanggal }}"
                            class="form-control form-control-sm me-2" onchange="this.form.submit()">
                    </form>

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
                </div>
            </header>

            <section class="content content-dashboard p-3 p-md-4">

                <!-- ==================== SUMMARY CARDS ==================== -->
                <div class="row g-3 mb-3">
                    <div class="col-md-3 col-sm-6">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                            <div class="stat-body">
                                <p class="stat-label">Total Siswa</p>
                                <h3 class="stat-value">{{ $totalSiswa }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="bi bi-building"></i></div>
                            <div class="stat-body">
                                <p class="stat-label">Total Kelas</p>
                                <h3 class="stat-value">{{ $totalKelas }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="bi bi-person-video3"></i></div>
                            <div class="stat-body">
                                <p class="stat-label">Total Guru</p>
                                <h3 class="stat-value">{{ $totalGuru }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="bi bi-clipboard-check"></i></div>
                            <div class="stat-body">
                                <p class="stat-label">Persentase Kehadiran</p>
                                <h3 class="stat-value">{{ number_format($persentaseHariIni, 1) }}%</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================== CHARTS ==================== -->
                <div class="row g-3 dashboard-charts">
                    <div class="col-lg-8 d-flex">
                        <div class="panel panel-chart flex-fill">
                            <div class="panel-header">
                                <h2>Kehadiran per Kelas</h2>
                            </div>
                            <div class="panel-body panel-body-chart">
                                <canvas id="chartKehadiran"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 d-flex">
                        <div class="panel panel-chart flex-fill">
                            <div class="panel-header">
                                <h2>Statistik Keterangan</h2>
                            </div>
                            <div class="panel-body panel-body-chart">
                                <canvas id="chartKeterangan"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Data dari Controller Laravel (dikirim via compact)
        const dataKehadiran = @json($dataKehadiranPerKelas);
        const dataKeterangan = @json($dataKeterangan);

        // Chart Kehadiran per Kelas (Bar Horizontal)
        new Chart(document.getElementById('chartKehadiran'), {
            type: 'bar',
            data: {
                labels: dataKehadiran.labels,
                datasets: [{
                    label: 'Kehadiran (%)',
                    data: dataKehadiran.values,
                    backgroundColor: 'rgba(79, 78, 200, 0.8)',
                    borderColor: '#4f78c8',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: { max: 100, ticks: { callback: v => v + '%' } }
                }
            }
        });

        // Chart Keterangan (Pie)
        new Chart(document.getElementById('chartKeterangan'), {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Alfa', 'Izin', 'Sakit'],
                datasets: [{
                    data: [
                        dataKeterangan.hadir,
                        dataKeterangan.alfa,
                        dataKeterangan.izin,
                        dataKeterangan.sakit
                    ],
                    backgroundColor: ['#28a745', '#dc3545', '#ffc107', '#17a2b8']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: { callbacks: { label: ctx => ctx.label + ': ' + ctx.parsed + '%' } }
                }
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