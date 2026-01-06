<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\User;

echo "<h1>Debug Absensi Consistency</h1>";

$user = User::where('role', 'siswa')->first();
if (!$user) die("No student found");

echo "Student: " . $user->name . "<br>";

$today = today()->toDateString();
echo "Today: $today <br>";

$absensis = Absensi::where('tanggal', $today)->get();

echo "<h3>Today's Absensi Records:</h3>";
foreach($absensis as $a) {
    echo "ID: $a->id | Mapel: '{$a->mata_pelajaran}' | Status: {$a->status} | Jam: {$a->jam} <br>";
}

echo "<h3>Today's Schedules for class {$user->kelas}:</h3>";
// Get class ID
$kelas = \App\Models\Kelas::where('nama_kelas', $user->kelas)->first();
if ($kelas) {
    $jadwals = Jadwal::where('kelas_id', $kelas->id)->get(); // Get all to check day logic separately
    foreach($jadwals as $j) {
        // filter by day for display context
        echo "ID: $j->id | Hari: $j->hari | Mapel: '{$j->mata_pelajaran}' <br>";
    }
} else {
    echo "Class not found.";
}
