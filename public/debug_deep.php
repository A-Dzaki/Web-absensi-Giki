<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Absensi;
use App\Models\User;
use App\Models\Jadwal;

echo "<h1>Debug Deep Dive</h1>";

// 1. Cek Data Siswa
$user = User::where('role', 'siswa')->first();
echo "<h3>Student Info</h3>";
echo "ID: " . $user->id . "<br>";
echo "Name: " . $user->name . "<br>";
echo "Kelas (String): " . $user->kelas . "<br>";

// 2. Cek Jadwal Hari Ini untuk Kelas tersebut
echo "<h3>Jadwal Hari Ini (" . today()->isoFormat('dddd') . ")</h3>";
$kelasId = \App\Models\Kelas::where('nama_kelas', $user->kelas)->value('id');
if(!$kelasId) echo "ERR: Kelas ID not found for name {$user->kelas}<br>";

$jadwals = Jadwal::where('kelas_id', $kelasId)->get();
foreach($jadwals as $j) {
    echo "ID: $j->id | Hari: $j->hari | Mapel: $j->mata_pelajaran <br>";
}

// 3. Cek SEMUA Absensi yang baru dibuat (regardless of user)
echo "<h3>ALL Recent Absensi Records (Last 10)</h3>";
$allAbs = Absensi::latest()->take(10)->get();
if ($allAbs->isEmpty()) {
    echo "NO DATA IN ABSENSIS TABLE.<br>";
} else {
    foreach($allAbs as $a) {
        echo "ID: $a->id | SiswaID: $a->siswa_id | JadwalID: $a->jadwal_id | Status: $a->status | Tgl: $a->tanggal <br>";
    }
}
