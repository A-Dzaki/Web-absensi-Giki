<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Kelas;

// Find Class 7E
$kelas = Kelas::where('nama_kelas', '7E')->first();
if (!$kelas) {
    echo "Class 7E not found!";
    exit;
}

// Find Student in that class or just generic 'siswa'
$user = User::where('role', 'siswa')->where('kelas_id', $kelas->id)->first();

if (!$user) {
    // try to find generic 'Siswa' user
    $user = User::where('name', 'Siswa')->first();
    if($user) {
         $user->kelas_id = $kelas->id;
         $user->save();
         echo "Assignable 'Siswa' user found and assigned to 7E.\n";
    }
}

if ($user) {
    $user->update([
        'name' => 'Ahmad Siswa',
        'nis' => '2025001',
        'no_telp' => '081234567890',
        'alamat' => 'Jl. Pendidikan No. 1'
    ]);
    echo "Updated User: {$user->name} (ID: {$user->id})\n";
} else {
    echo "No suitable student user found to update.\n";
}
