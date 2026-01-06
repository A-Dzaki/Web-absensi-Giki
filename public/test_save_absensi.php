<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Absensi;
use App\Models\User;
use App\Models\Jadwal;
use Carbon\Carbon;

echo "<h1>Test Save Absensi Manual</h1>";

// 1. Ambil Siswa
$siswa = User::where('role', 'siswa')->first();
if (!$siswa) die("No student found");
echo "Student: {$siswa->name} (ID: {$siswa->id}) <br>";

// 2. Ambil Jadwal Hari Ini
$kelasId = \App\Models\Kelas::where('nama_kelas', $siswa->kelas)->value('id');
$jadwal = Jadwal::where('kelas_id', $kelasId)->first();
if (!$jadwal) die("No schedule found for class {$siswa->kelas}");
echo "Jadwal ID: {$jadwal->id} | Mapel: {$jadwal->mata_pelajaran} <br>";

// 3. Set Tanggal Hari Ini (Pastikan Timezone Correct)
$tanggal = today()->toDateString();
echo "Tanggal Target: $tanggal <br>";

// 4. Coba Create/Update
try {
    $absensi = Absensi::updateOrCreate(
        [
            'siswa_id'  => $siswa->id,
            'jadwal_id' => $jadwal->id,
            'tanggal'   => $tanggal,
        ],
        [
            'mata_pelajaran' => $jadwal->mata_pelajaran,
            'kelas'     => $siswa->kelas,
            'status'    => 'H',
            'catatan'   => 'Manual Test via Script',
            'guru_id'   => 1, // Assume admin/guru ID 1
            'jam'       => now()->format('H:i:s'),
        ]
    );

    echo "<h3>SUCCESS!</h3>";
    echo "Saved Absensi ID: " . $absensi->id . "<br>";
    echo "Status: " . $absensi->status . "<br>";
    
} catch (\Exception $e) {
    echo "<h3>ERROR:</h3>";
    echo $e->getMessage();
}
