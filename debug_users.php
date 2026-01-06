<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$users = User::where('role', 'siswa')->get(['id', 'name', 'nis', 'kelas_id']);
foreach($users as $u) {
    echo "ID: {$u->id} | Name: {$u->name} | NIS: {$u->nis} | KelasID: {$u->kelas_id}\n";
}
