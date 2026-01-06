<?php

use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Current Date: " . date('Y-m-d H:i:s') . "\n";
echo "Current Day (English): " . date('l') . "\n";

$days = [
    'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
];
echo "Current Day (Indonesian): " . $days[date('l')] . "\n";

echo "\n--- All Jadwals ---\n";
$jadwals = Jadwal::with('guru', 'kelas')->get();
foreach ($jadwals as $j) {
    echo "ID: {$j->id} | Guru: " . ($j->guru->name ?? 'None') . " | Hari: {$j->hari} | Kelas: " . ($j->kelas->nama_kelas ?? 'None') . "\n";
}

echo "\n--- Guru Users ---\n";
$gurus = User::where('role', 'guru')->get();
foreach ($gurus as $g) {
    echo "ID: {$g->id} | Name: {$g->name}\n";
}
