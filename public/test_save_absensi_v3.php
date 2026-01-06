<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Absensi;
use App\Models\User;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Schema;

echo "<h1>Debug Save V3</h1>";

// 1. Cek Kolom
echo "<h3>Schema Absensis</h3>";
dump(Schema::getColumnListing('absensis'));

try {
    echo "<h3>Attempting Create...</h3>";
    
    // FETCH REAL STUDENT
    $student = User::where('role', 'siswa')->firstOrFail();
    $sId = $student->id;
    echo "Using Student ID: $sId ({$student->name}) <br>";
    
    // FETCH REAL JADWAL
    $jadwal = Jadwal::first();
    if (!$jadwal) {
        // Create dummy if needed, but better to use existing
        die("No Jadwal found in DB. Cannot save absensi.");
    }
    $jId = $jadwal->id;
    echo "Using Jadwal ID: $jId ({$jadwal->mata_pelajaran}) <br>";
    
    // FETCH REAL GURU
    $guru = User::where('role', 'guru')->first();
    $gId = $guru ? $guru->id : $sId; // Fallback
    echo "Using Guru ID: $gId <br>";

    $abs = new Absensi();
    $abs->siswa_id = $sId; 
    $abs->jadwal_id = $jId;
    $abs->tanggal = today()->toDateString();
    $abs->mata_pelajaran = $jadwal->mata_pelajaran;
    $abs->kelas = $student->kelas ?? "7E";
    $abs->status = "H";
    $abs->jam = now()->format('H:i:s');
    $abs->catatan = "Debug V3 Success";
    $abs->guru_id = $gId;
    
    $abs->save();
    
    echo "<h3 style='color:green'>SAVE SUCCESS! ID: {$abs->id}</h3>";
    
} catch (\Exception $e) {
    echo "<h3 style='color:red'>SAVE FAILED</h3>";
    echo "Message: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "<br>";
    dump($e);
}
