<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;

// Set ID siswa manual (misal ID 1 atau user pertama yang role siswa)
$user = User::where('role', 'siswa')->first();

if (!$user) {
    die("Tidak ada user siswa.");
}

// Login manual
auth()->login($user);

echo "<h1>Debug Status Absen</h1>";
echo "User: " . $user->name . " (ID: " . $user->id . ")<br>";
echo "Timezone Config: " . config('app.timezone') . "<br>";
echo "Now (Carbon): " . now()->format('Y-m-d H:i:s') . "<br>";
echo "Today (Date): " . today()->toDateString() . "<br><hr>";

// Cek Record Hari Ini
$today = today()->toDateString();
$absensiToday = Absensi::where('siswa_id', $user->id)
                ->whereDate('tanggal', $today)
                ->get();

echo "<h3>Absensi Tercatat Tanggal Ini ($today):</h3>";
if ($absensiToday->isEmpty()) {
    echo "<span style='color:red'>TIDAK ADA RECORD ABSENSI UNTUK HARI INI</span><br>";
} else {
    foreach ($absensiToday as $ab) {
        echo "ID: {$ab->id} | Mapel: '{$ab->mata_pelajaran}' | Status: {$ab->status} | Tanggal: {$ab->tanggal} <br>";
    }
}

echo "<hr>";
echo "<h3>Semua Absensi User Ini (5 Terakhir):</h3>";
$all = Absensi::where('siswa_id', $user->id)->latest()->take(5)->get();
foreach ($all as $ab) {
    echo "ID: {$ab->id} | Mapel: '{$ab->mata_pelajaran}' | Status: {$ab->status} | Tanggal: {$ab->tanggal} <br>";
}
