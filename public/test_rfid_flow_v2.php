<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Absensi;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use App\Http\Controllers\AbsensiController;

echo "<h1>Test RFID Flow V2 (Direct Call)</h1>";

// 1. Setup Data
$dummyUid = "TEST-UID-" . time();
$student = User::where('role', 'siswa')->firstOrFail();
$student->uid_rfid = $dummyUid;
$student->save();

echo "Student: {$student->name} | UID: $dummyUid <br>";

// Ensure a schedule exists for today
$kelasId = \App\Models\Kelas::where('nama_kelas', $student->kelas)->value('id');
$hariEnglish = now()->format('l');
$daysMap = [
   'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
   'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
];
$hariIndo = $daysMap[$hariEnglish];
echo "Today is: $hariIndo <br>";

$jadwalForToday = Jadwal::where('kelas_id', $kelasId)->where('hari', $hariIndo)->first();
if (!$jadwalForToday) {
    echo "Creating temp schedule for today...<br>";
    $jadwalForToday = new Jadwal();
    $jadwalForToday->hari = $hariIndo;
    $jadwalForToday->jam_mulai = '07:00:00';
    $jadwalForToday->jam_selesai = '16:00:00';
    $jadwalForToday->mata_pelajaran = "RFID TEST MAPEL " . rand(1,100);
    $jadwalForToday->kelas_id = $kelasId;
    $jadwalForToday->guru_id = \App\Models\User::where('role','guru')->first()->id ?? 1;
    $jadwalForToday->save();
} else {
    echo "Found schedule for today: {$jadwalForToday->mata_pelajaran}<br>";
}

// 2. Call Controller Method Directly
$request = Request::create('/input-rfid', 'GET', ['uid' => $dummyUid]);
$controller = new AbsensiController();
echo "Calling inputRfid...<br>";

try {
    $response = $controller->inputRfid($request);
    echo "<h3>Controller Response:</h3>";
    $content = $response->getContent();
    dump(json_decode($content, true));
} catch (\Exception $e) {
    echo "Error calling controller: " . $e->getMessage() . "<br>";
    dump($e);
}

// 3. Verify Absensi
echo "<h3>Verification:</h3>";
$today = today()->toDateString();
$abs = Absensi::where('siswa_id', $student->id)
        ->where('tanggal', $today)
        ->latest()
        ->first();
        
if ($abs) {
    echo "<span style='color:green'>SUCCESS! Record created. Status: {$abs->status} | Note: {$abs->catatan}</span>";
} else {
    echo "<span style='color:red'>FAILED. No record found.</span>";
}
