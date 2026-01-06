<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Absensi;
use Illuminate\Http\Request;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Auth;

echo "<h1>Test History Filter</h1>";

// Login as student
$student = User::where('role', 'siswa')->firstOrFail();
Auth::login($student);
echo "Logged in as: {$student->name} <br>";

// Create dummy attendance for testing if needed
$today = today()->toDateString();
if (Absensi::where('siswa_id', $student->id)->count() == 0) {
    echo "Creating dummy data...<br>";
    // ... create dummy data logic if needed, but likely existing data is fine
}

$controller = new SiswaController();

// 1. Test No Filter
echo "<h3>1. No Filter</h3>";
$req1 = Request::create('/siswa/detail-kehadiran', 'GET');
$view1 = $controller->detailKehadiran($req1);
$data1 = $view1->getData();
echo "Total Records: " . $data1['absensi']->count() . "<br>";

// 2. Test Date Filter (Today)
echo "<h3>2. Date Filter ($today)</h3>";
$req2 = Request::create('/siswa/detail-kehadiran', 'GET', ['tanggal' => $today]);
$view2 = $controller->detailKehadiran($req2);
$data2 = $view2->getData();
echo "Records Today: " . $data2['absensi']->count() . "<br>";
foreach($data2['absensi'] as $a) {
    echo "- {$a->mata_pelajaran} ({$a->tanggal}) <br>";
}

// 3. Test Mapel Filter (Pick one from list)
if ($data1['mapelList']->isNotEmpty()) {
    $mapel = $data1['mapelList'][0];
    echo "<h3>3. Mapel Filter ($mapel)</h3>";
    $req3 = Request::create('/siswa/detail-kehadiran', 'GET', ['mapel' => $mapel]);
    $view3 = $controller->detailKehadiran($req3);
    $data3 = $view3->getData();
    echo "Records for $mapel: " . $data3['absensi']->count() . "<br>";
}

echo "<h3 style='color:green'>Test Completed</h3>";
