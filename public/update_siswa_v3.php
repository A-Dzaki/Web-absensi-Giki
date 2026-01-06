<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

try {
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    // Find Student
    // Since we don't have kelas_id, valid students either have 'kelas'='7E' or we find generic
    $user = \App\Models\User::where('role', 'siswa')
                ->where(function($q){
                     $q->where('kelas', '7E')
                       ->orWhere('name', 'Siswa')
                       ->orWhere('name', 'User');
                })->first();

    if ($user) {
        $user->kelas = '7E'; // String column
        $user->name = 'Ahmad Siswa';
        $user->nis = '2025001';
        $user->save();
        echo "Updated User: {$user->name} (ID: {$user->id}, Kelas: {$user->kelas}, NIS: {$user->nis})";
    } else {
        echo "No suitable student user found to update.";
    }

} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage();
}
