<?php
// Audit script to check DB connection and structure

// Load .env if possible, or use hardcoded defaults based on what we find
// Since we are running outside of Laravel, we'll try to parse .env ourselves
$envFile = __DIR__ . '/.env';
$env = [];
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && substr($line, 0, 1) !== '#') {
            list($key, $value) = explode('=', $line, 2);
            $env[trim($key)] = trim($value);
        }
    }
}

$host = $env['DB_HOST'] ?? '127.0.0.1';
$db   = $env['DB_DATABASE'] ?? 'web_absensi_giki'; // Fallback
$user = $env['DB_USERNAME'] ?? 'root';
$pass = $env['DB_PASSWORD'] ?? '';
$port = $env['DB_PORT'] ?? '3306';

echo "CONFIG: Host=$host, DB=$db, User=$user\n";

try {
    $dsn = "mysql:host=$host;dbname=$db;port=$port;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected successfully to '$db'.\n";
    
    // Check tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables found: " . implode(", ", $tables) . "\n";
    
    if (in_array('users', $tables)) {
        echo "\nColumns in 'users' table:\n";
        $stmt = $pdo->query("DESCRIBE users");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($columns as $col) {
            echo "- " . $col['Field'] . " (" . $col['Type'] . ")\n";
        }
        
        // Attempt to add if missing
        $hasAlamat = false;
        foreach ($columns as $col) {
            if ($col['Field'] === 'alamat') $hasAlamat = true;
        }
        
        if (!$hasAlamat) {
            echo "\n!! 'alamat' column is MISSING !!\n";
            echo "Attempting to add it now via this script...\n";
            $pdo->exec("ALTER TABLE users ADD COLUMN alamat TEXT NULL AFTER jenis_kelamin");
            echo "Column 'alamat' added successfully.\n";
        } else {
            echo "\n'alamat' column exists.\n";
        }
        
    } else {
        echo "Table 'users' NOT FOUND.\n";
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
    // Try to list databases
    try {
        $dsnNoDb = "mysql:host=$host;port=$port;charset=utf8mb4";
        $pdoGlobal = new PDO($dsnNoDb, $user, $pass);
        $stmt = $pdoGlobal->query("SHOW DATABASES");
        echo "\nAvailable Databases:\n";
        print_r($stmt->fetchAll(PDO::FETCH_COLUMN));
    } catch (Exception $e2) {
        echo "Could not list databases: " . $e2->getMessage() . "\n";
    }
}
