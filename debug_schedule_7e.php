<?php

use App\Models\Kelas;
use App\Models\Jadwal;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$kelasName = '7E';
echo "Searching for Class: $kelasName\n";

$kelas = Kelas::where('nama_kelas', $kelasName)->first();

if (!$kelas) {
    echo "Class '$kelasName' not found in DB.\n";
    // List all classes
    echo "Available classes: " . Kelas::pluck('nama_kelas')->implode(', ') . "\n";
} else {
    echo "Class ID: " . $kelas->id . "\n";
    
    $count = Jadwal::where('kelas_id', $kelas->id)->count();
    echo "Jadwal count for this class: $count\n";

    if ($count > 0) {
        $mapels = Jadwal::where('kelas_id', $kelas->id)->select('mata_pelajaran')->distinct()->get();
        echo "Mapels found: " . $mapels->pluck('mata_pelajaran')->implode(', ') . "\n";
    } else {
        echo "No schedules found in 'jadwals' table for class_id {$kelas->id}.\n";
    }
}
