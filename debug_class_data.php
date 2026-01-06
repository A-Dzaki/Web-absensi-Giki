<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use App\Models\Kelas;

$kelasName = '7A';
$kelas = Kelas::where('nama_kelas', $kelasName)->first();

echo "--- DEBUG CLASS DATA ---\n";
if ($kelas) {
    echo "ID: {$kelas->id}\n";
    echo "Nama Kelas: {$kelas->nama_kelas}\n";
    echo "Wali Kelas: {$kelas->walikelas}\n";
} else {
    echo "Class '$kelasName' NOT FOUND.\n";
}
echo "----------------------\n";
