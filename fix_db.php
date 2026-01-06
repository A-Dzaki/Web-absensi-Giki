<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking database schema...\n";

if (Schema::hasTable('users')) {
    echo "Table 'users' exists.\n";
    
    if (!Schema::hasColumn('users', 'uid_rfid')) {
        echo "Column 'uid_rfid' MISSING. Adding it now...\n";
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->string('uid_rfid')->nullable()->after('username');
            });
            echo "Column 'uid_rfid' added successfully!\n";
        } catch (\Exception $e) {
            echo "Error adding column 'uid_rfid': " . $e->getMessage() . "\n";
        }
    } else {
        echo "Column 'uid_rfid' ALREADY EXISTS.\n";
    }

    if (!Schema::hasColumn('users', 'alamat')) {
        echo "Column 'alamat' MISSING. Adding it now...\n";
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->text('alamat')->nullable()->after('jenis_kelamin');
            });
            echo "Column 'alamat' added successfully!\n";
        } catch (\Exception $e) {
            echo "Error adding column 'alamat': " . $e->getMessage() . "\n";
        }
    } else {
        echo "Column 'alamat' ALREADY EXISTS.\n";
    }
} else {
    echo "Table 'users' DOES NOT EXIST!\n";
}

echo "Done.\n";
