<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$students = \App\Models\User::where('role', 'siswa')->take(10)->get();
echo "DAFTAR SISWA:\n";
foreach($students as $s) {
    echo "ID: {$s->id} | Nama: {$s->name} | Kelas: {$s->kelas} | UID: {$s->uid_rfid}\n";
}
