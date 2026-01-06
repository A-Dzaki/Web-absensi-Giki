<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Absensi;
use App\Models\Jadwal;
use Illuminate\Support\Facades\DB;

echo "<h1>Test RFID Flow</h1>";

// 1. Setup Data
$dummyUid = "TEST-UID-" . time();
$student = User::where('role', 'siswa')->firstOrFail();
$student->uid_rfid = $dummyUid;
$student->save();

echo "Student: {$student->name} | UID: $dummyUid <br>";

// Ensure a schedule exists for today
$kelasId = \App\Models\Kelas::where('nama_kelas', $student->kelas)->value('id');
$today = today()->toDateString();
// We need a schedule for today to make it work
$jadwal = Jadwal::where('kelas_id', $kelasId)->first(); 
if($jadwal) {
    // Force day to match today for testing logic if controller checks day
     // Actually AbsensiController::inputRfid checks:
     // $jadwal = Jadwal::where('hari', $hariIni)...
     // So we must ensure DB has a schedule for THIS day name
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
         $jadwalForToday->mata_pelajaran = "RFID TEST MAPEL";
         $jadwalForToday->kelas_id = $kelasId;
         $jadwalForToday->guru_id = 1; // dummy
         $jadwalForToday->save();
     } else {
         echo "Found schedule for today: {$jadwalForToday->mata_pelajaran}<br>";
     }
}

// 2. Call Endpoint simulating ESP32
$url = url("/input-rfid?uid=" . $dummyUid);
echo "Calling: $url <br>";

// Use curl or file_get_contents
try {
    $response = file_get_contents($url);
    echo "<h3>Response:</h3>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
} catch (\Exception $e) {
    echo "Error calling API: " . $e->getMessage();
}

// 3. Verify Absensi
echo "<h3>Verification:</h3>";
$abs = Absensi::where('siswa_id', $student->id)
        ->where('tanggal', $today)
        ->latest()
        ->first();
        
if ($abs) {
    echo "<span style='color:green'>SUCCESS! Record created. Status: {$abs->status} | Note: {$abs->catatan}</span>";
} else {
    echo "<span style='color:red'>FAILED. No record found.</span>";
}
