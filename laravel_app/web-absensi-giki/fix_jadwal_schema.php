<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

echo "Checking 'jadwals' table schema...\n";

if (Schema::hasTable('jadwals')) {
    echo "Table 'jadwals' exists.\n";

    if (!Schema::hasColumn('jadwals', 'hari')) {
        echo "Column 'hari' is MISSING. Adding it now...\n";
        Schema::table('jadwals', function (Blueprint $table) {
            $table->string('hari')->after('kelas_id')->nullable();
        });
        echo "Column 'hari' added successfully.\n";
    } else {
        echo "Column 'hari' already exists.\n";
    }

    if (Schema::hasColumn('jadwals', 'tanggal')) {
        echo "Modifying 'tanggal' column to be nullable...\n";
        // Raw SQL is often more reliable for modification if doctrine/dbal is missing
        try {
            DB::statement("ALTER TABLE jadwals MODIFY COLUMN tanggal DATE NULL");
            echo "Column 'tanggal' made nullable.\n";
        } catch (\Exception $e) {
            echo "Error modifying 'tanggal': " . $e->getMessage() . "\n";
        }
    }

} else {
    echo "Table 'jadwals' does NOT exist!\n";
}

echo "Done.\n";
