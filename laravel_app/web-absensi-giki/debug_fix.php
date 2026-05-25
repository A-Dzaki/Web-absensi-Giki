<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Disable time limit
set_time_limit(0);

echo "--- STARTING FIX ---\n";

try {
    $dbName = DB::connection()->getDatabaseName();
    echo "Connected to Database: " . $dbName . "\n";

    // 1. Check if table 'jadwals' exists
    if (!Schema::hasTable('jadwals')) {
        die("CRITICAL: Table 'jadwals' does not exist.\n");
    }
    echo "Table 'jadwals' found.\n";

    // 2. Check columns using basic Schema builder
    $columns = Schema::getColumnListing('jadwals');
    echo "Current Columns: " . implode(', ', $columns) . "\n";

    // 3. Raw Check for 'hari'
    $hasHari = in_array('hari', $columns);
    
    if (!$hasHari) {
        echo "Column 'hari' is MISSING. Attempting RAW SQL ADD...\n";
        
        // Use raw PDO for direct feedback
        DB::statement("ALTER TABLE jadwals ADD COLUMN hari VARCHAR(20) NULL AFTER kelas_id");
        echo "RAW SQL executed.\n";
        
        // Re-check
        $columnsNew = Schema::getColumnListing('jadwals');
        if (in_array('hari', $columnsNew)) {
            echo "SUCCESS: 'hari' column added successfully.\n";
        } else {
            echo "FAILED: 'hari' column still missing after ALTER.\n";
        }
    } else {
        echo "INFO: 'hari' column already exists in Schema.\n";
    }

    // 4. Fix 'tanggal' column
    echo "Attempting to modify 'tanggal' column to NULL...\n";
    try {
        DB::statement("ALTER TABLE jadwals MODIFY COLUMN tanggal DATE NULL");
        echo "SUCCESS: 'tanggal' column modified.\n";
    } catch (\Exception $e) {
        echo "WARNING: Could not modify 'tanggal': " . $e->getMessage() . "\n";
    }

} catch (\Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}

echo "--- FINISHED ---\n";
