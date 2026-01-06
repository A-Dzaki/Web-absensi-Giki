<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<h1>Debug ID Types V2</h1>";

$tables = ['users', 'absensis'];
foreach ($tables as $t) {
    echo "<h3>Create Table: $t</h3>";
    $create = DB::select("SHOW CREATE TABLE $t");
    // Output is usually object with property 'Create Table'
    foreach ($create as $row) {
        $key = "Create Table";
        if (isset($row->$key)) {
             echo "<pre>" . htmlspecialchars($row->$key) . "</pre>";
        } else {
            dump($row);
        }
    }
}

echo "<h3>Check User ID 1</h3>";
$u = DB::select("SELECT id, name FROM users WHERE id = 1");
if (empty($u)) {
    echo "<h2 style='color:red'>USER ID 1 DOES NOT EXIST</h2>";
    // Check what IDs DO exist
    $ids = DB::select("SELECT id FROM users LIMIT 10");
    echo "Existing IDs: ";
    foreach($ids as $i) echo $i->id . ", ";
} else {
    echo "<h2 style='color:green'>USER ID 1 EXISTS: " . $u[0]->name . "</h2>";
}
