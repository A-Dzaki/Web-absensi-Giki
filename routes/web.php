<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordCustomController;
use App\Http\Controllers\Auth\ResetPasswordCustomController;

use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TataUsahaController;

// ===== HOMEPAGE =====
Route::get('/', function () {
    return view('welcome');
});

// ===== RFID ENDPOINT (IoT) =====
use App\Http\Controllers\AbsensiController;
Route::get('/input-rfid', [AbsensiController::class, 'inputRfid'])->name('absensi.rfid');

// Utility Route to Fix Table
Route::get('/fix-backups-table', function () {
    try {
        Illuminate\Support\Facades\DB::statement("
            CREATE TABLE IF NOT EXISTS backups (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                data LONGTEXT NOT NULL,
                total_records INT NOT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )
        ");
        return "Table backups created successfully.";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/fix-logs-table', function () {
    try {
        Illuminate\Support\Facades\DB::statement("
            CREATE TABLE IF NOT EXISTS activity_logs (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id BIGINT UNSIGNED NULL,
                action VARCHAR(255) NOT NULL,
                description TEXT NULL,
                ip_address VARCHAR(45) NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )
        ");
        return "Table activity_logs created successfully.";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/', function () {
    return redirect()->route('loginForm');
});

// ESP32 RFID Endpoint
Route::get('/input-rfid', [App\Http\Controllers\AbsensiController::class, 'inputRfid']);

// AJAX Polling Endpoint
Route::get('/siswa/get-status-absen', [SiswaController::class, 'getStatusAbsen'])->name('siswa.get-status');

Route::get('/debug-jadwal', function () {
    require base_path('routes/debug_jadwal.php');
});

Route::get('/fix-db', function () {
    try {
        $msg = "";

        // 1. Add 'hari'
        $hasHari = \Illuminate\Support\Facades\Schema::hasColumn('jadwals', 'hari');
        if (!$hasHari) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE jadwals ADD COLUMN hari VARCHAR(20) NULL AFTER kelas_id");
            $msg .= "Added 'hari' column. <br>";
        } else {
            $msg .= "'hari' already exists. <br>";
        }

        // 2. Modify 'tanggal'
        try {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE jadwals MODIFY COLUMN tanggal DATE NULL");
            $msg .= "Modified 'tanggal' to NULL. <br>";
        } catch (\Exception $e) {
            $msg .= "Date mod error (ignorable): " . $e->getMessage() . "<br>";
        }

        // 3. Add 'catatan', 'jam', 'kelas', 'guru_id', 'jadwal_id' to absensis if missing
        $cols = ['catatan' => 'TEXT', 'jam' => 'TIME', 'kelas' => 'VARCHAR(10)', 'guru_id' => 'BIGINT', 'jadwal_id' => 'BIGINT'];
        foreach ($cols as $col => $type) {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('absensis', $col)) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE absensis ADD COLUMN $col $type NULL");
                $msg .= "Added '$col' to absensis. <br>";
            }
        }

        return "<h1>FIX APPLIED</h1><p>$msg</p><a href='/tatausaha/data-guru'>Go Back</a>";

    } catch (\Exception $e) {
        return "ERROR: " . $e->getMessage();
    }
});



// ===== LOGIN =====
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('loginForm');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ===== SETTINGS =====
Route::post('/settings/password', [App\Http\Controllers\SettingsController::class, 'updatePassword'])
    ->name('settings.update-password')
    ->middleware('auth');

// ===== REGISTER =====
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// ===== PASSWORD RESET =====
Route::get('/password/reset', [ForgotPasswordCustomController::class, 'showLinkForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordCustomController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordCustomController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordCustomController::class, 'resetPassword'])->name('password.update');


// ===========================
//            GURU
// ===========================
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruController::class, 'dashboard'])->name('dashboard');
    Route::get('/kelola-absensi', [GuruController::class, 'kelolaAbsensi'])->name('kelola');
    Route::post('/simpan-absensi', [GuruController::class, 'simpanAbsensi'])->name('simpan');
    Route::get('/profil', [GuruController::class, 'profil'])->name('profil');
    Route::put('/update-profil', [GuruController::class, 'updateProfil'])->name('profil.update');
});



// ===========================
//            SISWA
// ===========================
Route::middleware(['auth', 'role:siswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {
        Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('dashboard');
        Route::get('/status-absen', [SiswaController::class, 'statusAbsen'])->name('status');
        Route::get('/detail-kehadiran', [SiswaController::class, 'detailKehadiran'])->name('detail'); // New Route
        Route::get('/jadwal', [SiswaController::class, 'jadwal'])->name('jadwal');
        Route::get('/profil', [SiswaController::class, 'profil'])->name('profil');
        Route::put('/profil', [SiswaController::class, 'updateProfil'])->name('profil.update');
    });


// ===========================
//         TATA USAHA
// ===========================
Route::middleware(['auth', 'role:tatausaha'])
    ->prefix('tatausaha')
    ->name('tatausaha.')
    ->group(function () {
        Route::get('/dashboard', [TataUsahaController::class, 'dashboard'])->name('dashboard');
        Route::get('/data-kelas', [TataUsahaController::class, 'dataKelas'])->name('data-kelas');
        Route::get('/profil', [TataUsahaController::class, 'profil'])->name('profil');
        Route::put('/profil', [TataUsahaController::class, 'updateProfil'])->name('profil.update');

        // Kelola Guru
        Route::get('/data-guru', [TataUsahaController::class, 'dataGuru'])->name('data-guru');
        Route::post('/input-guru', [TataUsahaController::class, 'storeGuru'])->name('guru.store');
        Route::put('/guru/{id}', [TataUsahaController::class, 'updateGuru'])->name('guru.update');
        Route::delete('/guru/{id}', [TataUsahaController::class, 'destroyGuru'])->name('guru.destroy');

        // Kelola Tata Usaha
        Route::get('/data-tatausaha', [TataUsahaController::class, 'dataTataUsaha'])->name('data-tatausaha');
        Route::post('/input-tatausaha', [TataUsahaController::class, 'storeTataUsaha'])->name('tatausaha.store');
        Route::put('/tatausaha/{id}', [TataUsahaController::class, 'updateTataUsaha'])->name('tatausaha.update');
        Route::delete('/tatausaha/{id}', [TataUsahaController::class, 'destroyTataUsaha'])->name('tatausaha.destroy');

        // Logs
        Route::post('/kelas', [TataUsahaController::class, 'storeKelas'])->name('kelas.store');
        Route::put('/kelas/update', [TataUsahaController::class, 'updateKelas'])->name('kelas.update');
        Route::delete('/kelas/{id}', [TataUsahaController::class, 'destroyKelas'])->name('kelas.destroy');
        Route::post('/siswa', [TataUsahaController::class, 'siswaStore'])->name('siswa.store');
        Route::put('/siswa/{id}', [TataUsahaController::class, 'siswaUpdate'])->name('siswa.update');
        Route::delete('/siswa/{id}', [TataUsahaController::class, 'siswaDestroy'])->name('siswa.destroy');
        Route::post('/siswa/import', [TataUsahaController::class, 'importSiswa'])->name('siswa.import');
        Route::post('/siswa/reset', [TataUsahaController::class, 'resetSiswa'])->name('siswa.reset');
        Route::post('/siswa/restore/{id}', [TataUsahaController::class, 'restoreSiswa'])->name('siswa.restore');
        Route::get('/logs', [TataUsahaController::class, 'logAktivitas'])->name('logs');
        Route::post('/siswa/import', [TataUsahaController::class, 'importSiswa'])->name('siswa.import');

        // Export Recap
        Route::get('/rekap/cetak-pdf', [TataUsahaController::class, 'cetakPdf'])->name('rekap.cetak-pdf');
        Route::get('/rekap/export-excel', [TataUsahaController::class, 'exportExcel'])->name('rekap.export-excel');

        // Logs
        Route::get('/logs', [TataUsahaController::class, 'logAktivitas'])->name('logs');
    });
