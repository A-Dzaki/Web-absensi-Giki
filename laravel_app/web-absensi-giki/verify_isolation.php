<?php

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- VERIFICATION START ---\n";

// 1. Setup Data
// Find '7E' Class
$kelasE = Kelas::firstOrCreate(['nama_kelas' => '7E']);
// Find '7A' Class (Dummy)
$kelasA = Kelas::firstOrCreate(['nama_kelas' => '7A']);

// Create Dummy Schedules (using Database directly to avoid Model guards if any)
$date = now()->format('Y-m-d');

// Schedule for 7E
$idE = DB::table('jadwals')->insertGetId([
    'kelas_id' => $kelasE->id,
    'mata_pelajaran' => 'VERIF_MAPEL_7E',
    'guru_id' => 1, // Assumed valid or ignored by test
    'tanggal' => $date,
    'jam_mulai' => '08:00',
    'jam_selesai' => '09:00',
    'created_at' => now(), 'updated_at' => now()
]);

// Schedule for 7A
$idA = DB::table('jadwals')->insertGetId([
    'kelas_id' => $kelasA->id,
    'mata_pelajaran' => 'VERIF_MAPEL_7A',
    'guru_id' => 1,
    'tanggal' => $date,
    'jam_mulai' => '08:00',
    'jam_selesai' => '09:00',
    'created_at' => now(), 'updated_at' => now()
]);

echo "Created Dummy Schedules: ID $idE (7E) and ID $idA (7A)\n";

// 2. Simulate Student in 7E
// We don't login, we just run the query logic extracted from SiswaController
$userKelasString = '7E';
$kelasLookup = Kelas::where('nama_kelas', $userKelasString)->first();
$kelasId = $kelasLookup ? $kelasLookup->id : null;

echo "Student is in Class: $userKelasString (ID: $kelasId)\n";

$results = Jadwal::where('kelas_id', $kelasId)
    ->where('tanggal', $date)
    ->get();

echo "Query Results:\n";
$foundE = false;
$foundA = false;

foreach ($results as $res) {
    echo "- Mapel: " . $res->mata_pelajaran . " (Class ID: " . $res->kelas_id . ")\n";
    if ($res->mata_pelajaran == 'VERIF_MAPEL_7E') $foundE = true;
    if ($res->mata_pelajaran == 'VERIF_MAPEL_7A') $foundA = true;
}

// 3. Cleanup
DB::table('jadwals')->whereIn('id', [$idE, $idA])->delete();
echo "Cleanup Done.\n";

// 4. Assert
if ($foundE && !$foundA) {
    echo "SUCCESS: Student only saw 7E schedule.\n";
} else {
    echo "FAILURE: Visibility logic incorrect.\n";
    if (!$foundE) echo " - Did not see 7E mapel.\n";
    if ($foundA) echo " - Saw 7A mapel (Leak!).\n";
}
