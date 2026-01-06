<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "Checking absensis table...\n";

$cols = [
    'catatan' => 'TEXT', 
    'jam' => 'TIME', 
    'kelas' => 'VARCHAR(10)', 
    'guru_id' => 'BIGINT', 
    'jadwal_id' => 'BIGINT',
    // Check mata_pelajaran too just in case
    'mata_pelajaran' => 'VARCHAR(255)' 
];

foreach ($cols as $col => $type) {
    if (!Schema::hasColumn('absensis', $col)) {
        try {
            DB::statement("ALTER TABLE absensis ADD COLUMN $col $type NULL");
            echo "Added column: $col\n";
        } catch (\Exception $e) {
            echo "Error adding $col: " . $e->getMessage() . "\n";
        }
    } else {
        echo "Column exists: $col\n";
    }
}
echo "Database fix completed.\n";
