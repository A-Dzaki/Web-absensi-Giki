<?php

use App\Models\Jadwal;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$deleted = Jadwal::destroy(2);

if ($deleted) {
    echo "Successfully deleted Jadwal ID 2 (Agama Islam).";
} else {
    echo "Record ID 2 not found or already deleted.";
}
