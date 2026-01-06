// js/tatausaha.js
document.addEventListener("DOMContentLoaded", () => {
   // setActiveSidebar();
    setupSidebarNavigation();
    setupTabs();
    initCharts();
});

/** Menandai icon sidebar yang aktif berdasarkan data-page di <body> */


/** Navigasi antar halaman TU */
function setupSidebarNavigation() {
    const base = "./"; // semua file berada dalam folder Tatausaha

    const navConfig = {
        btnDashboard: "dashboard-tatausaha.html",
        btnKelas: "data-kelas.html",
        btnProfil: "profiltu.html"
    };

    Object.entries(navConfig).forEach(([id, file]) => {
        const btn = document.getElementById(id);
        if (!btn) return;

        btn.addEventListener("click", () => {
            const current = window.location.pathname.split("/").pop();
            if (current !== file) {
                window.location.href = base + file;
            }
        });
    });
}

/** Tab "Daftar Siswa" / "Daftar Absensi" */
function setupTabs() {
    const tabContainer = document.getElementById("tabSiswa");
    if (!tabContainer) return;

    const buttons = tabContainer.querySelectorAll(".nav-link");
    buttons.forEach(btn => {
        btn.addEventListener("click", () => {
            const targetSelector = btn.getAttribute("data-target");
            if (!targetSelector) return;

            // ganti status tombol
            buttons.forEach(b => b.classList.remove("active"));
            btn.classList.add("active");

            // ganti konten
            document.querySelectorAll(".tab-pane-tu").forEach(pane => {
                pane.classList.remove("active");
            });
            const targetPane = document.querySelector(targetSelector);
            if (targetPane) targetPane.classList.add("active");
        });
    });
}

/** Inisialisasi Chart.js kalau canvas ada (hanya di dashboard) */
function initCharts() {
    if (typeof Chart === "undefined") return;

    const ctxBar = document.getElementById("chartKehadiran");
if (ctxBar) {
    new Chart(ctxBar, {
        type: "bar",
        data: {
            labels: ["7A", "7B", "8A", "8B", "9A", "9B"],
            datasets: [
                {
                    label: "Hadir",
                    data: [10, 9, 11, 8, 10, 9]   // contoh data
                },
                {
                    label: "Izin",
                    data: [1, 2, 0, 1, 1, 1]      // contoh data
                },
                {
                    label: "Sakit",
                    data: [0, 1, 1, 1, 0, 1]      // contoh data
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: true,   
                    position: "top"
                }
            }
        }
    });
}


    const ctxPie = document.getElementById("chartKeterangan");
    if (ctxPie) {
        new Chart(ctxPie, {
            type: "pie",
            data: {
                labels: ["Hadir", "Izin", "Sakit", "Alpa"],
                datasets: [{
                    data: [80, 8, 7, 5],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
}
