<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

echo "Attempting to create activity_logs table...\n";

try {
    if (!Schema::hasTable('activity_logs')) {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('action'); // e.g. "Simpan Absensi", "Hapus Guru"
            $table->text('description')->nullable(); // Details
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
        echo "SUCCESS: Table 'activity_logs' created.\n";
    } else {
        echo "INFO: Table 'activity_logs' already exists.\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    
    // Fallback: Raw SQL if Schema fails
    try {
        DB::statement("
            CREATE TABLE IF NOT EXISTS activity_logs (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id BIGINT UNSIGNED NULL,
                action VARCHAR(255) NOT NULL,
                description TEXT NULL,
                ip_address VARCHAR(45) NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )
        ");
        echo "SUCCESS: Table 'activity_logs' created via Raw SQL.\n";
    } catch (\Exception $e2) {
        echo "FATAL ERROR: " . $e2->getMessage() . "\n";
    }
}
