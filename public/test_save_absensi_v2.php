<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Absensi;
use Illuminate\Support\Facades\Schema;

echo "<h1>Debug Save V2</h1>";

// 1. Cek Kolom
echo "<h3>Schema Absensis</h3>";
dump(Schema::getColumnListing('absensis'));

    echo "<h3>Attempting Create...</h3>";
    try {
    
    $student = \App\Models\User::where('role', 'siswa')->firstOrFail();
    $sId = $student->id;
    echo "Using Student ID: $sId <br>";
    
    $abs = new Absensi();
    $abs->siswa_id = $sId; 
    $abs->jadwal_id = 2; // Make sure this exists too, or use DB::table('jadwals')->first()->id
    
    $jadwal = \App\Models\Jadwal::first();
    $abs->jadwal_id = $jadwal->id ?? 1;

    $abs->tanggal = today()->toDateString();
    $abs->mata_pelajaran = "DEBUG MAPEL";
    $abs->kelas = $student->kelas ?? "7E";
    $abs->status = "H";
    $abs->jam = now()->format('H:i:s');
    $abs->catatan = "Debug V3 Success";
    $abs->guru_id = \App\Models\User::where('role','guru')->first()->id ?? $sId;
    
    $abs->save();
    
    echo "<h3 style='color:green'>SAVE SUCCESS! ID: {$abs->id}</h3>";
    
} catch (\Exception $e) {
    echo "<h3 style='color:red'>SAVE FAILED</h3>";
    echo "Message: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "<br>";
    dump($e);
}
