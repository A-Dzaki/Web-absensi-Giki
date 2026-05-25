// ========= UTIL DASAR =========
function $(sel) {
  return document.querySelector(sel);
}
function $all(sel) {
  return document.querySelectorAll(sel);
}

// ========= TANGGAL HARI INI =========
function setToday() {
  const el = $('#todayStr');
  if (!el) return;

  const hari = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
  const d = new Date();
  const dd = String(d.getDate()).padStart(2, '0');
  const mm = String(d.getMonth() + 1).padStart(2, '0');
  const yyyy = d.getFullYear();

  el.textContent = `${hari[d.getDay()]}, ${dd} - ${mm} - ${yyyy}`;
}

// ========= PROFIL DI CHIP KANAN ATAS =========
// ========= PROFIL DI CHIP KANAN ATAS =========
// renderProfile removed to use server-side data


// ========= DATA DETAIL KEHADIRAN =========
const dataKehadiranByMapel = {
  "Bahasa Inggris 9A": [
    { mapel: "Bahasa Inggris 9A", nama: "Kevin Imanuel", tanggal: "Senin, 06 - 10 - 2025", pertemuan: "Pertama", status: "Hadir" },
    { mapel: "Bahasa Inggris 9A", nama: "Kevin Imanuel", tanggal: "Kamis, 09 - 10 - 2025", pertemuan: "Kedua", status: "Hadir" },
    { mapel: "Bahasa Inggris 9A", nama: "Kevin Imanuel", tanggal: "Senin, 13 - 10 - 2025", pertemuan: "Ketiga", status: "Izin" },
  ],
  "Matematika 9A": [
    { mapel: "Matematika 9A", nama: "Kevin Imanuel", tanggal: "Senin, 06 - 10 - 2025", pertemuan: "Pertama", status: "Hadir" }
  ],
  "IPA 9A": [
    { mapel: "IPA 9A", nama: "Kevin Imanuel", tanggal: "Selasa, 07 - 10 - 2025", pertemuan: "Pertama", status: "Hadir" }
  ],
  "PPKn 9A": [
    { mapel: "PPKn 9A", nama: "Kevin Imanuel", tanggal: "Rabu, 08 - 10 - 2025", pertemuan: "Pertama", status: "Hadir" }
  ],
};

function pillStatus(status) {
  const ok = String(status).toLowerCase() === 'hadir';
  const cls = ok ? 'badge-status badge-hadir' : 'badge-status badge-izin';
  return `<span class="${cls}">${status}</span>`;
}

function renderDetail(mapel) {
  const titleEl = $('#detailMapelTitle');
  const tbody = $('#detailTbody');
  if (!titleEl || !tbody) return;

  titleEl.textContent = mapel;

  const rows = (dataKehadiranByMapel[mapel] || []).map(it => `
    <tr>
      <td class="small text-secondary">${it.mapel}</td>
      <td>${it.nama}</td>
      <td>${it.tanggal}</td>
      <td>${it.pertemuan}</td>
      <td class="text-center">${pillStatus(it.status)}</td>
    </tr>
  `).join('');

  tbody.innerHTML = rows || `
    <tr><td colspan="5" class="text-center text-secondary py-4">
      Belum ada data kehadiran.
    </td></tr>
  `;

  renderProfile(); // sync profil di halaman detail
}

// ========= SIDEBAR: TANDAKAN MENU AKTIF =========
function highlightSidebar() {
  const current = location.pathname.split("/").pop().toLowerCase();

  document.querySelectorAll(".sidebar a.btn-icon").forEach(link => {
    const href = (link.getAttribute("href") || "").toLowerCase();
    if (href === current) {
      link.classList.add("active");
    }
  });
}

// ========= BIND PER HALAMAN =========
function initPerPage() {
  const file = location.pathname.split("/").pop().toLowerCase();

  // DASHBOARD → klik "Status Absen" pindah ke Statusabsen-Siswa.html
  if (file === "dashboard-siswa.html") {
    $all("#viewDashboard .btn-status, .card-lesson .btn-status").forEach(btn => {
      btn.addEventListener("click", () => {
        const mapel =
          btn.dataset.mapel ||
          btn.closest(".card-lesson")?.querySelector(".title")?.textContent.trim() ||
          "Mapel";

        localStorage.setItem("statusPage.mapel", mapel);
        location.href = "Statusabsen-Siswa.html";
      });
    });
  }

  // JADWAL → klik "Detail kehadiran" pindah ke detailkehadiran-Siswa.html
  if (file === "jadwal-siswa.html") {
    $all("#viewMapel .btn-status, .card-lesson .btn-status").forEach(btn => {
      btn.addEventListener("click", () => {
        const mapel =
          btn.dataset.mapel ||
          btn.closest(".card-lesson")?.querySelector(".title")?.textContent.trim() ||
          "Mapel";

        localStorage.setItem("detailPage.mapel", mapel);
        location.href = "detailkehadiran-Siswa.html";
      });
    });
  }

  // HALAMAN STATUSABSEN-SISWA
  if (file === "statusabsen-siswa.html") {
    const mapelName = $("#mapelName");
    // const namaCell  = $("#namaCell"); // Removed conflicting overwrite
    // const nisCell   = $("#nisCell");  // Removed conflicting overwrite

    const mapel = localStorage.getItem("statusPage.mapel") || "Matematika 9A";
    // const nama  = localStorage.getItem("name") || "Siswa";
    // const nis   = localStorage.getItem("nis")  || "-";

    if (mapelName) mapelName.textContent = mapel;
    // if (namaCell)  namaCell.textContent  = nama;
    // if (nisCell)   nisCell.textContent   = nis;
  }

  // HALAMAN DETAILKEHADIRAN-SISWA
  if (file === "detailkehadiran-siswa.html") {
    const mapel = localStorage.getItem("detailPage.mapel") || "Matematika 9A";
    renderDetail(mapel);

    const backBtn = $("#btnBackToMapel");
    if (backBtn) {
      backBtn.addEventListener("click", () => {
        location.href = "Jadwal-Siswa.html";
      });
    }
  }

}

document.addEventListener("DOMContentLoaded", () => {
  setToday();
  // renderProfile(); // Removed to prevent overwriting server-side data
  highlightSidebar();
  initPerPage();
});
