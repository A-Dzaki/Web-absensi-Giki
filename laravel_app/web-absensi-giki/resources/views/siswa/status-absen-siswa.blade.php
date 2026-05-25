<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Absen Siswa | SMP GIKI 2 Surabaya</title>
    
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
            <a href="{{ route('siswa.status') }}" class="btn-icon active" title="Status Absen">
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
                        <span class="fw-semibold">Status Absen Hari Ini</span>
                        <span class="ms-1">| {{ now()->translatedFormat('l, j F Y') }}</span>
                    </div>
                    @if($mapel)
                        <div class="mt-3 fw-semibold fs-6 text-primary">
                            {{ $mapel }} - {{ Auth::user()->kelas }}
                        </div>
                    @endif
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

            <!-- ==================== BOX STATUS ABSEN ==================== -->
            <section id="viewStatus" class="view active">
                <div class="status-wrap">
                    <div class="status-box">
                        <div class="status-header">Cek Status Absen</div>
                        <div class="table-responsive"><table>
                            <tr>
                                <td>No. Induk Siswa</td>
                                <td class="fw-semibold">{{ Auth::user()->nis ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td class="fw-semibold">{{ Auth::user()->name }}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>
                                    @if($statusHariIni)
                                        @switch($statusHariIni->status)
                                            @case('H')
                                                <span class="text-success fw-semibold">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Hadir
                                                </span>
                                                @break
                                            @case('A')
                                                <span class="text-danger fw-semibold">
                                                    <i class="bi bi-x-circle-fill me-1"></i> Alfa
                                                </span>
                                                @break
                                            @case('I')
                                                <span class="text-warning fw-semibold">
                                                    <i class="bi bi-clock-fill me-1"></i> Izin
                                                </span>
                                                @break
                                            @case('S')
                                                <span class="text-info fw-semibold">
                                                    <i class="bi bi-heart-fill me-1"></i> Sakit
                                                </span>
                                                @break
                                            @default
                                                <span class="text-muted">—</span>
                                        @endswitch

                                        @if($statusHariIni->catatan)
                                            <br><small class="text-muted">{{ $statusHariIni->catatan }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted fw-semibold">
                                            <i class="bi bi-hourglass-split me-1"></i> Belum Diabsen
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        </table></div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('siswa.dashboard') }}" class="btn btn-light border rounded-pill px-4">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
                    </a>
                </div>
            </section>
        </main>
    </div>

    <script src="/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-refresh/polling logic
        const currentMapel = "{{ $mapel ?? '' }}";
        
        if(currentMapel) {
            setInterval(checkStatus, 3000);
        }

        function checkStatus() {
            if (!currentMapel) return;

            fetch("{{ route('siswa.get-status') }}?mapel=" + encodeURIComponent(currentMapel))
                .then(response => response.json())
                .then(data => {
                    // Start refreshing only if status changes to something valid
                    if (data.status) {
                        // Easier to just reload to get full server-side formatting
                        // But let's check: if the text currently says "Belum Diabsen" and we have status, reload.
                        const statusText = document.body.innerText;
                        if(statusText.includes('Belum Diabsen') && data.status !== null) {
                            window.location.reload();
                        }
                    }
                })
                .catch(err => console.error('Error polling:', err));
        }
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
