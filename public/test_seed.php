<?php
// Fix path to bootstrap
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Kelas;
use App\Models\User;
use App\Models\Jadwal;
use Illuminate\Support\Facades\DB;

echo "<pre>";
echo "DB Connected: " . DB::connection()->getDatabaseName() . "\n";

try {
    // 1. Ensure Class 7E
    $kelas = Kelas::firstOrCreate(
        ['nama_kelas' => '7E'],
        ['walikelas' => 'Guru Test']
    );
    echo "Kelas: " . $kelas->nama_kelas . " (ID: " . $kelas->id . ")\n";

    // 2. Ensure Guru
    $guru = User::where('role', 'guru')->first();
    if (!$guru) {
        $guru = User::create([
            'name' => 'Guru Matematika',
            'email' => 'guru.math@example.com',
            'password' => bcrypt('password'),
            'role' => 'guru',
            'username' => 'gurumath',
            'nis' => '123456'
        ]);
    }
    echo "Guru: " . $guru->name . " (ID: " . $guru->id . ")\n";

    // 3. Create Schedule
    $jadwal = Jadwal::create([
        'kelas_id' => $kelas->id,
        'guru_id' => $guru->id,
        'hari' => 'Kamis',
        'mata_pelajaran' => 'Matematika',
        'jam_mulai' => '07:00:00',
        'jam_selesai' => '09:00:00',
        'tanggal' => date('Y-m-d')
    ]);

    echo "Jadwal Created: " . $jadwal->id . "\n";
    echo "SUCCESS";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
echo "</pre>";
