<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "<h3>Absensis</h3>";
dump(Schema::getColumnListing('absensis'));
echo "<h3>Users</h3>";
dump(Schema::getColumnListing('users'));
