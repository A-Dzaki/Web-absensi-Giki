<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Force fixing database...\n";

try {
    // Check if column exists first to avoid error
    $exists = DB::select("SHOW COLUMNS FROM jadwals LIKE 'hari'");
    if (empty($exists)) {
        DB::statement("ALTER TABLE jadwals ADD COLUMN hari VARCHAR(20) NULL AFTER kelas_id");
        echo "SUCCESS: Column 'hari' added.\n";
    } else {
        echo "INFO: Column 'hari' already exists.\n";
    }
} catch (\Exception $e) {
    echo "ERROR adding 'hari': " . $e->getMessage() . "\n";
}

try {
    DB::statement("ALTER TABLE jadwals MODIFY COLUMN tanggal DATE NULL");
    echo "SUCCESS: Column 'tanggal' modified to NULL.\n";
} catch (\Exception $e) {
    echo "ERROR modifying 'tanggal': " . $e->getMessage() . "\n";
}
