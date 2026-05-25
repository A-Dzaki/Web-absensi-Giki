<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;

echo "--- ABSENSI DEBUGGER ---\n";
echo "Today: " . Carbon::today()->toDateString() . "\n";

// 1. Get Student
$user = User::where('role', 'siswa')->first();
if (!$user) die("No student found.\n");

echo "Student: {$user->name} (ID: {$user->id})\n";

// 2. Find Attendance for Today
$absensis = Absensi::where('siswa_id', $user->id)
            ->whereDate('tanggal', Carbon::today())
            ->get();

echo "Found " . $absensis->count() . " records for today.\n";

foreach ($absensis as $a) {
    echo "ID: {$a->id} | Mapel: [{$a->mata_pelajaran}] | Status: [{$a->status}] | Jam: {$a->jam}\n";
    var_dump($a->toArray());
}

echo "\n--- ALL RECENT ABSENSI (Last 5) ---\n";
$latest = Absensi::latest()->take(5)->get();
foreach ($latest as $l) {
     echo "ID: {$l->id} | StudentID: {$l->siswa_id} | Date: {$l->tanggal} | Mapel: {$l->mata_pelajaran}\n";
}
