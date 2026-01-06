<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

echo "Attempting to create backups table...\n";

try {
    DB::statement("
        CREATE TABLE IF NOT EXISTS backups (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            data LONGTEXT NOT NULL,
            total_records INT NOT NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL
        )
    ");
    echo "SUCCESS: Table 'backups' created via Raw SQL.\n";
} catch (\Exception $e) {
    echo "FATAL ERROR: " . $e->getMessage() . "\n";
}
