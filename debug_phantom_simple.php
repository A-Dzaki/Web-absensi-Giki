<?php

use App\Models\Jadwal;
use App\Models\Kelas;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$output = "";

// Dump all jadwals
$jadwals = Jadwal::with(['guru', 'kelas'])->get();
$output .= "Total Schedules: " . $jadwals->count() . "\n";

foreach ($jadwals as $j) {
    if (str_contains(strtolower($j->mata_pelajaran), 'agama')) {
        $output .= "FOUND AGAMA: ID: {$j->id} | Class: " . ($j->kelas->nama_kelas ?? '-') . " | Date: {$j->tanggal} | Guru: " . ($j->guru->name ?? '-') . "\n";
    }
}

file_put_contents('debug_result.txt', $output);
echo "Done";
