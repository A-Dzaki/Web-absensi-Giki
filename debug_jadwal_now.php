<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request = Illuminate\Http\Request::capture());

use App\Models\Jadwal;
use App\Models\User;
use Carbon\Carbon;

$now = Carbon::now();
$localeDay = $now->locale('id')->isoFormat('dddd');
$englishDay = date('l');

echo "Current Time: " . $now->toDateTimeString() . "\n";
echo "Day (Indo): " . $localeDay . "\n";
echo "Day (Eng): " . $englishDay . "\n";

// Fallback logic from Controller
$daysMap = [
    'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 
    'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
];
$mappedDay = $daysMap[$englishDay] ?? $englishDay;
echo "Mapped Day: " . $mappedDay . "\n\n";

echo "Checking Schedules for '$mappedDay'...\n";
$schedules = Jadwal::where('hari', $mappedDay)->get();
echo "Found " . $schedules->count() . " schedules.\n";

if ($schedules->count() > 0) {
    foreach ($schedules->take(5) as $s) {
        $kelas = \App\Models\Kelas::find($s->kelas_id);
        $kelasName = $kelas ? $kelas->nama_kelas : 'Unknown';
        echo "- Kelas: $kelasName | Mapel: $s->mata_pelajaran | Jam: $s->jam_mulai - $s->jam_selesai\n";
    }
}
