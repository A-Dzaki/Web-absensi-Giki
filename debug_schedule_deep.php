<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use App\Models\User;
use App\Models\Jadwal;
use App\Models\Kelas;
use Carbon\Carbon;

echo "--- DEEP DEBUG SCHEDULE ---\n";

// 1. Get Student
$user = User::where('role', 'siswa')->first();
if (!$user) die("No student found.\n");

echo "Student: {$user->name} ({$user->kelas})\n";

// 2. Get Class
$kelas = Kelas::where('nama_kelas', $user->kelas)->first();
if (!$kelas) die("Class '{$user->kelas}' not found.\n");

echo "Class ID: {$kelas->id}\n";

// 3. Current Day
$todayEng = date('l'); // Monday
$todayIndo = \Carbon\Carbon::now()->locale('id')->isoFormat('dddd'); // Senin

echo "Today Eng: [$todayEng]\n";
echo "Today Indo: [$todayIndo]\n";

// 4. Dump Table Content for this Class
$schedules = Jadwal::where('kelas_id', $kelas->id)->get();

echo "\n--- RAW DB CONTENT (Class {$kelas->id}) ---\n";
if ($schedules->isEmpty()) {
    echo "NO SCHEDULES FOUND for this class.\n";
} else {
    foreach ($schedules as $s) {
        $hariRaw = json_encode($s->hari); // Reveal hidden chars
        echo "ID: {$s->id} | Hari: {$s->hari} (Raw: $hariRaw) | Mapel: {$s->mata_pelajaran}\n";
        
        // Check Match
        if (trim($s->hari) == $todayEng) echo "   -> MATCHES English!\n";
        if (trim($s->hari) == $todayIndo) echo "   -> MATCHES Indo!\n";
        if (strcasecmp(trim($s->hari), $todayIndo) == 0) echo "   -> Matches Indo (Case-Insensitive)\n";
    }
}
echo "-------------------------------\n";
