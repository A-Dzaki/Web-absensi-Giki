<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use App\Models\User;
use App\Models\Jadwal;
use App\Models\Kelas;

echo "=== DIAGNOSTIC DUMP ===\n";

// 1. STUDENT INFO
$student = User::where('role', 'siswa')->first();
if ($student) {
    echo "Student: {$student->name}\n";
    echo "Class String: [{$student->kelas}]\n";
    
    // Resolve Class ID
    $kelas = Kelas::where('nama_kelas', $student->kelas)->first();
    if ($kelas) {
        echo "Resolved Class ID: {$kelas->id} (Name: {$kelas->nama_kelas})\n";
    } else {
        echo "!! CLASS NOT FOUND IN DB !!\n";
    }
} else {
    echo "No Student Found.\n";
}

echo "\n2. ALL SCHEDULES (Limit 20)\n";
$jadwals = Jadwal::with('kelas')->take(20)->get();
foreach ($jadwals as $j) {
    $className = $j->kelas ? $j->kelas->nama_kelas : 'Orphan (No Class)';
    echo "ID: {$j->id} | Class: [$className] (ID: {$j->kelas_id}) | Day: [{$j->hari}] | Mapel: {$j->mata_pelajaran}\n";
}

echo "\n3. CURRENT TIME\n";
echo "English: " . date('l') . "\n";
echo "Indo: " . \Carbon\Carbon::now()->locale('id')->isoFormat('dddd') . "\n";
echo "=======================\n";
