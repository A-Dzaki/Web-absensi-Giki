<?php

use App\Models\Jadwal;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Assuming user is in class '7E' based on previous screenshots, or we can list all
// Let's find the class first
$kelas = Kelas::where('nama_kelas', '7E')->first();
$kelasId = $kelas ? $kelas->id : null;

echo "Kelas 7E ID: " . ($kelasId ?? 'Not Found') . "\n";

if ($kelasId) {
    $jadwals = Jadwal::with('guru')
        ->where('kelas_id', $kelasId)
        ->get();

    echo "Found " . $jadwals->count() . " schedules for 7E:\n";
    foreach ($jadwals as $j) {
        echo "ID: {$j->id} | Date: {$j->tanggal} | Mapel: {$j->mata_pelajaran} | Guru: " . ($j->guru->name ?? 'None') . "\n";
    }
} else {
    // List all schedules if class not found
    $jadwals = Jadwal::with(['guru', 'kelas'])->get();
    echo "dumping all schedules:\n";
    foreach ($jadwals as $j) {
         echo "ID: {$j->id} | Class: " . ($j->kelas->nama_kelas ?? 'None') . " | Date: {$j->tanggal} | Mapel: {$j->mata_pelajaran}\n";
    }
}
