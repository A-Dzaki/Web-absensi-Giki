<!-- Settings Modal Component -->
<div class="modal fade" id="modalSettings" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content tu-modal">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">Pengaturan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Tabs -->
                <ul class="nav nav-pills tu-tabs mb-3" id="settingsTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="theme-tab" data-bs-toggle="tab" data-bs-target="#theme-pane" type="button" role="tab">Tampilan</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password-pane" type="button" role="tab">Ganti Password</button>
                    </li>
                </ul>

                <div class="tab-content" id="settingsTabContent">
                    <!-- Theme Tab -->
                    <div class="tab-pane fade show active" id="theme-pane" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border">
                            <div class="d-flex align-items-center gap-3">
                                <div class="stat-icon" style="width:40px;height:40px;background:#2d3a5d;color:#fff;">
                                    <i class="bi bi-moon-stars"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Mode Malam</h6>
                                    <small class="text-muted">Aktifkan tema gelap</small>
                                </div>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="darkModeToggle" style="width: 3em; height: 1.5em; cursor: pointer;">
                            </div>
                        </div>
                    </div>

                    <!-- Password Tab -->
                    <div class="tab-pane fade" id="password-pane" role="tabpanel">
                        <form action="{{ route('settings.update-password') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Password Saat Ini</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="new_password" class="form-control" required minlength="6">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" name="new_password_confirmation" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Simpan Password Baru</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dark Mode Logic
        const toggle = document.getElementById('darkModeToggle');
        const root = document.documentElement; // html element
        const STORAGE_KEY = 'giki_theme';

        // Check saved theme
        const savedTheme = localStorage.getItem(STORAGE_KEY);
        if (savedTheme === 'dark') {
            root.setAttribute('data-theme', 'dark');
            toggle.checked = true;
        }

        // Toggle Event
        toggle.addEventListener('change', function() {
            if (this.checked) {
                root.setAttribute('data-theme', 'dark');
                localStorage.setItem(STORAGE_KEY, 'dark');
            } else {
                root.removeAttribute('data-theme');
                localStorage.setItem(STORAGE_KEY, 'light');
            }
        });
    });
</script>
