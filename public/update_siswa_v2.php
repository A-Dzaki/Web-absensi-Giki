<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

try {
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    // Find Class 7E
    $kelas = \App\Models\Kelas::where('nama_kelas', '7E')->first();
    if (!$kelas) {
         echo "Class 7E not found!";
         exit;
    }

    // Find Student
    $user = \App\Models\User::where('role', 'siswa')
                ->where(function($q) use ($kelas){
                     $q->where('kelas_id', $kelas->id)
                       ->orWhere('name', 'Siswa')
                       ->orWhere('name', 'User');
                })->first();

    if ($user) {
        $user->kelas_id = $kelas->id;
        $user->name = 'Ahmad Siswa';
        $user->nis = '2025001';
        $user->save();
        echo "Updated User: {$user->name} (ID: {$user->id}, Class: {$kelas->nama_kelas}, NIS: {$user->nis})";
    } else {
        echo "No suitable student user found to update.";
    }

} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage();
}
