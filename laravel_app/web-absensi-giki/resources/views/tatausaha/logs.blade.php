<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log Aktivitas - Tata Usaha</title>
    
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/styletu.css') . '?v=' . time() }}" rel="stylesheet">
</head>
<body data-page="logs">
    <div class="app">
        @include('components.settings-modal')
        <!-- SIDEBAR -->
        <aside class="sidebar d-flex flex-column align-items-center py-3">
            <a href="{{ route('tatausaha.dashboard') }}" class="btn-icon" title="Beranda"><i class="bi bi-house-door"></i></a>
            <a href="{{ route('tatausaha.data-kelas') }}" class="btn-icon" title="Kelas"><i class="bi bi-journal-text"></i></a>
            <a href="{{ route('tatausaha.data-guru') }}" class="btn-icon" title="Guru"><i class="bi bi-people"></i></a>
            <a href="{{ route('tatausaha.logs') }}" class="btn-icon active" title="Log Aktivitas"><i class="bi bi-clock-history"></i></a>
            <a href="{{ route('tatausaha.profil') }}" class="btn-icon" title="Akun"><i class="bi bi-person"></i></a>
            <div class="sidebar-footer">
                <a href="#" class="btn-icon" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="bi bi-box-arrow-left"></i></a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </div>
        </aside>

        <!-- MAIN -->
        <main class="main">
            <header class="topbar">
                <button class="hamburger d-md-none" aria-label="Toggle navigation">
    <span></span>
    <span></span>
    <span></span>
</button>

                <div class="topbar-title">
                    <h1>Log Aktivitas</h1>
                    <p class="topbar-subtitle">Memantau aktivitas pengguna sistem</p>
                </div>
                <div class="topbar-user">
                    <a href="{{ route('tatausaha.profil') }}" class="profile-chip text-decoration-none text-reset">
                        <div class="avatar">
                            @if(Auth::user()->foto)
                                <img src="{{ asset(Auth::user()->foto) }}" alt="Foto Profil" style="width:100%;height:100%;object-fit:cover;border-radius:50%">
                            @else
                                {{ Str::upper(substr(Auth::user()->name, 0, 2)) }}
                            @endif
                        </div>
                        <div class="fw-semibold small">{{ Auth::user()->name }}</div>
                    </a>
                </div>
            </header>

            <section class="content p-4">
                <div class="panel">
                    <div class="panel-body p-0">
                        <div class="table-responsive">
                            <table class="table tu-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Waktu</th>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Aksi</th>
                                        <th>Deskripsi</th>
                                        <th>IP Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logs as $log)
                                        <tr>
                                            <td>{{ $log->created_at->format('d M Y H:i:s') }}</td>
                                            <td>
                                                @if($log->user)
                                                    <span class="fw-semibold">{{ $log->user->name }}</span>
                                                @else
                                                    <span class="text-muted">User Terhapus</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($log->user)
                                                    <span class="badge bg-secondary">{{ ucfirst($log->user->role) }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td><span class="badge bg-primary">{{ $log->action }}</span></td>
                                            <td>{{ $log->description }}</td>
                                            <td class="text-muted small">{{ $log->ip_address }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">Belum ada aktivitas tercatat.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="panel-footer mt-3 px-3">
                        {{ $logs->links() }}
                    </div>
                </div>
            </section>
        </main>
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
                    <button type="button" class="btn btn-danger px-4" onclick="document.getElementById('logout-form').submit()">Iya</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="/js/bootstrap.bundle.min.js"></script>

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

