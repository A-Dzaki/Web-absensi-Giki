<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request = Illuminate\Http\Request::capture());

use App\Models\User;
use App\Models\Kelas;
use App\Models\Jadwal;
use Carbon\Carbon;

echo "--- DATA DEBUGGER ---\n";
$now = Carbon::now();
echo "Time: " . $now->toDateTimeString() . "\n";
echo "Day (Eng): " . $now->format('l') . "\n";
echo "Day (Indo): " . $now->locale('id')->isoFormat('dddd') . "\n";
echo "---------------------\n";

// 1. Get First Student
$student = User::where('role', 'siswa')->first();
if (!$student) {
    die("No students found in DB.\n");
}
echo "Student: {$student->name} (UID: {$student->uid_rfid})\n";
echo "Student Class String: '{$student->kelas}'\n";

// 2. Find Class ID
$kelasModel = Kelas::where('nama_kelas', $student->kelas)->first();
if (!$kelasModel) {
    echo "ERROR: Class '{$student->kelas}' NOT FOUND in 'kelas' table!\n";
    // List available classes
    echo "Available Classes: " . Kelas::pluck('nama_kelas')->implode(', ') . "\n";
    die();
}
echo "Class found in DB. ID: {$kelasModel->id}, Name: '{$kelasModel->nama_kelas}'\n";

// 3. Find Schedule
echo "Searching Schedules for Class ID {$kelasModel->id}...\n";

// Check 'Senin'
$schedulesIndo = Jadwal::where('kelas_id', $kelasModel->id)->where('hari', 'Senin')->get();
echo "Count (Senin): " . $schedulesIndo->count() . "\n";

// Check 'Monday'
$schedulesEng = Jadwal::where('kelas_id', $kelasModel->id)->where('hari', 'Monday')->get();
echo "Count (Monday): " . $schedulesEng->count() . "\n";

// Check ALL for this class
$allSchedules = Jadwal::where('kelas_id', $kelasModel->id)->get();
echo "Total Schedules for Class: " . $allSchedules->count() . "\n";
if ($allSchedules->count() > 0) {
    echo "Days present: " . $allSchedules->pluck('hari')->unique()->implode(', ') . "\n";
}

echo "---------------------\n";
