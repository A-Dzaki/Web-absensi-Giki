<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="{{ url('/') }}/">
    <title>Dashboard Siswa | Absensi SMP GIKI 2 Surabaya</title>

    <link rel="icon" type="image/png" href="{{ asset('uploads/logo-giki.png') }}">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/stylesiswa.css') }}?v={{ time() }}" rel="stylesheet">
</head>

<body>
    <div class="app d-flex">
        @include('components.settings-modal')

        <!-- ==================== SIDEBAR ==================== -->
        <aside class="sidebar d-flex flex-column align-items-center py-3">
            <a href="{{ route('siswa.dashboard') }}" class="btn-icon active" title="Beranda">
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
                <div class="btn-icon" title="Pengaturan" style="cursor: pointer;" data-bs-toggle="modal"
                    data-bs-target="#modalSettings">
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
                        <span class="fw-semibold">Jadwal Saya</span>
                        <span class="ms-1">| {{ now()->translatedFormat('l, j F Y') }}</span>
                    </div>
                </div>

                <a href="{{ route('siswa.profil') }}" class="profile-chip text-decoration-none text-reset">
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
                            NIS. {{ Auth::user()->nis ?? '-' }}
                        </div>
                    </div>
                </a>
            </div>

            <!-- ==================== JADWAL HARI INI ==================== -->
            <section class="view active" id="viewDashboard">
                <div class="schedule-wrap">
                    @if($jadwalHariIni->count() > 0)
                        <div class="row g-4">
                            @foreach($jadwalHariIni as $index => $j)
                                <div class="col-12 col-md-6 col-xl-4">
                                    <div class="card-lesson c{{ ($index % 6) + 1 }}">
                                        <div class="title">{{ $j->mata_pelajaran }} {{ Auth::user()->kelas }}</div>
                                        <div class="line"></div>
                                        <div class="mb-2 text-muted small">
                                            <i class="bi bi-person me-1"></i> {{ $j->guru->name ?? 'Guru belum ditentukan' }}
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="meta">
                                                {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H.i') }} -
                                                {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H.i') }}
                                            </div>

                                            @php
                                                $now = now();
                                                $start = \Carbon\Carbon::parse($j->jam_mulai);
                                                $end = \Carbon\Carbon::parse($j->jam_selesai);
                                            @endphp

                                            @if($now < $start)
                                                <span class="badge bg-secondary rounded-pill">Upcoming</span>
                                            @elseif($now >= $start && $now <= $end)
                                                <span class="badge bg-warning text-dark rounded-pill">Sedang Berlangsung</span>
                                            @else
                                                <span class="badge bg-success rounded-pill">Selesai</span>
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-3">
                                            <button type="button" class="btn btn-status"
                                                onclick="openStatusModal('{{ $j->mata_pelajaran }}', '{{ $j->guru->name ?? '-' }}', '{{ $j->id }}')">
                                                Status Absen
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-emoji-smile display-1 text-muted"></i>
                            <p class="mt-3 text-muted">Tidak ada jadwal hari ini. Selamat beristirahat!</p>
                        </div>
                    @endif
                </div>
            </section>
        </main>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/Siswa.js') }}"></script>

    <!-- Status Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header text-white border-0"
                    style="background: linear-gradient(135deg, #8cb0f0, #4f78c8);">
                    <h5 class="modal-title fw-bold" id="statusModalTitle">Status Absen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="status-box shadow-sm mx-auto w-100">
                        <div class="table-responsive">
                            <table class="w-100">
                                <tr>
                                    <td class="text-muted">No. Induk Siswa</td>
                                    <td class="fw-bold text-end">{{ Auth::user()->nis ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Nama</td>
                                    <td class="fw-bold text-end">{{ Auth::user()->name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Mata Pelajaran</td>
                                    <td class="fw-bold text-end" id="modalMapel">-</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Pengajar</td>
                                    <td class="fw-bold text-end" id="modalGuru">-</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status</td>
                                    <td class="text-end" id="modalStatusResult">
                                        <span class="text-muted fw-semibold">
                                            <div class="spinner-border spinner-border-sm text-secondary" role="status">
                                            </div> Loading...
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="text-center mt-3 small text-muted">
                        <i class="bi bi-broadcast me-1"></i> Menunggu tap kartu RFID...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Polling -->
    <script>
        let pollingInterval = null;
        let currentJadwalId = '';
        let currentMapel = '';

        function openStatusModal(mapel, guru, jadwalId) {
            currentMapel = mapel;
            currentJadwalId = jadwalId;
            document.getElementById('modalMapel').innerText = mapel;
            document.getElementById('modalGuru').innerText = guru;
            document.getElementById('statusModalTitle').innerText = 'Cek Status: ' + mapel;

            // Tampilkan Modal
            var myModal = new bootstrap.Modal(document.getElementById('statusModal'));
            myModal.show();

            // Reset loading
            document.getElementById('modalStatusResult').innerHTML =
                '<span class="text-muted fw-semibold"><i class="bi bi-hourglass-split me-1"></i> Memeriksa...</span>';

            // Start Polling immediately
            checkStatus();

            // Clear existing interval if any
            if (pollingInterval) clearInterval(pollingInterval);

            // Set polling every 3 seconds
            pollingInterval = setInterval(checkStatus, 3000);
        }

        // Stop polling when modal is closed
        document.getElementById('statusModal').addEventListener('hidden.bs.modal', function () {
            if (pollingInterval) clearInterval(pollingInterval);
        });

        function checkStatus() {
            if (!currentJadwalId) return;

            fetch("{{ route('siswa.get-status') }}?jadwal_id=" + encodeURIComponent(currentJadwalId))
                .then(response => response.json())
                .then(data => {
                    const statusRes = document.getElementById('modalStatusResult');

                    if (data.status) {
                        let badge = '';
                        let text = '';
                        switch (data.status) {
                            case 'H':
                                badge = '<span class="text-success fw-bold fs-5"><i class="bi bi-check-circle-fill me-1"></i> Hadir</span>';
                                break;
                            case 'A':
                                badge = '<span class="text-danger fw-bold fs-5"><i class="bi bi-x-circle-fill me-1"></i> Alfa</span>';
                                break;
                            case 'I':
                                badge = '<span class="text-warning fw-bold fs-5"><i class="bi bi-clock-fill me-1"></i> Izin</span>';
                                break;
                            case 'S':
                                badge = '<span class="text-info fw-bold fs-5"><i class="bi bi-heart-fill me-1"></i> Sakit</span>';
                                break;
                        }

                        let timeInfo = data.jam != '-' ? `<br><small class="text-muted">Jam: ${data.jam}</small>` : '';
                        statusRes.innerHTML = badge + timeInfo;
                    } else {
                        statusRes.innerHTML = '<span class="text-muted fw-semibold"><i class="bi bi-hourglass-split me-1"></i> Belum Diabsen</span>';
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
                    <button type="button" class="btn btn-danger px-4"
                        onclick="document.getElementById('logout-form').submit()">Iya</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hamburger Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const hamburger = document.querySelector('.hamburger');
            const sidebar = document.querySelector('.sidebar');

            if (hamburger && sidebar) {
                hamburger.addEventListener('click', function (e) {
                    e.stopPropagation();
                    hamburger.classList.toggle('active');
                    sidebar.classList.toggle('active');
                });

                // Close sidebar when clicking outside
                document.addEventListener('click', function (event) {
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