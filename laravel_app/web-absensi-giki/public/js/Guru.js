// Helper
const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));
const $  = (s, r = document) => r.querySelector(s);

// Highlight sidebar berdasarkan URL
function highlightSidebar() {
  const current = location.pathname.split("/").pop().toLowerCase();
  $$('.sidebar a.btn-icon').forEach(link => {
    const href = (link.getAttribute('href') || '').toLowerCase();
    link.classList.toggle('active', href === current);
  });
}

// Toast sederhana (frontend only)
function toast(msg) {
  const el = document.createElement('div');
  el.className = 'position-fixed bottom-0 end-0 p-3';
  el.innerHTML = `
    <div class="toast align-items-center text-bg-dark border-0 show">
      <div class="d-flex">
        <div class="toast-body">${msg}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>`;
  document.body.appendChild(el);
  setTimeout(() => el.remove(), 1600);
}

// Logout: bersihkan localStorage lalu submit form logout Laravel jika ada
function initLogout() {
  const btn = $('#btnLogout');
  if (!btn) return;
  btn.addEventListener('click', (e) => {
    e.preventDefault();
    
    if (!confirm('Apakah Anda yakin ingin keluar?')) return;

    localStorage.clear();
    // Jika ada form logout Laravel, submitkan (POST /logout)
    const logoutForm = document.getElementById('logout-form');
    if (logoutForm) {
      logoutForm.submit();
      return;
    }
    // Fallback: redirect ke route login
    window.location.href = '/login';
  });
}

// --- Halaman HOME (dashboard-guru.html) ---
function initDashboard() {
  const selectPert = $('#selectPertemuan');
  if (selectPert) {
    // load pertemuan tersimpan
    const savedPert = localStorage.getItem('guru.pertemuan');
    if (savedPert) {
      Array.from(selectPert.options).forEach(opt => {
        if (opt.text === savedPert) opt.selected = true;
      });
    }
    selectPert.addEventListener('change', e => {
      localStorage.setItem('guru.pertemuan', e.target.value);
    });
  }

  // tombol Detail Absensi
  $$('.btn-detail[data-open="kelola"]').forEach(btn => {
    btn.addEventListener('click', () => {
      const card  = btn.closest('.card-lesson');
      const title = card?.querySelector('.title')?.textContent.trim() || 'Matematika 9A';
      const meta  = card?.querySelector('.meta')?.textContent.trim() || '';
      const kelas = btn.dataset.kelas || title.split(' ').pop();
      const mapel = btn.dataset.mapel || 'Matematika';

      localStorage.setItem('guru.kelas', kelas);
      localStorage.setItem('guru.mapel', mapel);
      localStorage.setItem('guru.jam', meta);

      // pertemuan
      const pertemuan = selectPert ? selectPert.value : 'Pertemuan 1';
      localStorage.setItem('guru.pertemuan', pertemuan);

      window.location.href = 'kelola-guru.html';
    });
  });
}

// --- Halaman KELOLA (kelola-guru.html) ---
function initKelola() {
  const kelas = localStorage.getItem('guru.kelas') || '9A';
  const mapel = localStorage.getItem('guru.mapel') || 'Matematika';
  const per   = localStorage.getItem('guru.pertemuan') || 'Pertemuan 1';

  const lblKM = $('#labelKelasMapel');
  if (lblKM) lblKM.textContent = `Kelas ${kelas} • ${mapel}`;

  const lblKelas = $('#lblKelas');
  const lblMapel = $('#lblMapel');
  const lblPert  = $('#lblPert');
  const lblPertHead = $('#labelPertemuanHeader');

  if (lblKelas) lblKelas.textContent = kelas;
  if (lblMapel) lblMapel.textContent = mapel;
  if (lblPert)  lblPert.textContent  = per;
  if (lblPertHead) lblPertHead.textContent = per;

  // group radio per baris
  $$('#view-kelola tbody tr').forEach((tr, i) => {
    const group = 'r' + (i + 1);
    tr.querySelectorAll('input[type="radio"]').forEach(r => r.name = group);
  });

  // tombol Semua Hadir
  const btnAllHadir = $('#btnAllHadir');
  if (btnAllHadir) {
    btnAllHadir.addEventListener('click', () => {
      $$('#view-kelola tbody tr').forEach(tr => {
        const rH = Array.from(tr.querySelectorAll('input[type="radio"]'))
          .find(r => r.value === 'H');
        if (rH) rH.checked = true;
      });
      toast('Semua siswa ditandai Hadir (demo FE).');
    });
  }

  // tombol dummy
  const btnExport = $('#btnExport');
  const btnSave   = $('#btnSimpanSemua');
  if (btnExport) btnExport.addEventListener('click', () =>
    toast('Frontend only — sambungkan ke backend untuk export.')
  );
  if (btnSave) btnSave.addEventListener('click', () =>
    toast('Frontend only — sambungkan ke backend untuk simpan.')
  );
}

// --- Halaman PROFIL (profil-guru.html) ---
function initProfil() {
  const btn = $('#btnDummyUpdate');
  if (!btn) return;
  btn.addEventListener('click', () => {
    toast('Profil berhasil di-update (demo FE).');
  });
}

// --- ENTRY POINT ---
document.addEventListener('DOMContentLoaded', () => {
  highlightSidebar();
  initLogout();

  const file = location.pathname.split('/').pop().toLowerCase();
  if (file === 'dashboard-guru.html') initDashboard();
  if (file === 'kelola-guru.html')     initKelola();
  if (file === 'profil-guru.html')     initProfil();
});
