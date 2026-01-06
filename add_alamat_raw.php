<?php
// Script to add 'alamat' column using raw PDO to bypass framework issues

$host = '127.0.0.1';
$db   = 'web_absensi_giki'; // Assuming this based on folder name, will check if failed
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // 1. Connect without DB first to check if DB exists or find name
    echo "Connecting to MySQL...\n";
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // Check if database exists
    echo "Checking database...\n";
    // Try to guess DB name if needed, but for now try the most likely one based on previous context if any.
    // Actually, I'll try to connect to the specific DB.
    
    $dbName = 'web_absensi_giki'; // Default guess
    
    // Try to find the actual DB name from information_schema if possible? 
    // Or just try to use the one from the error message context?
    // The previous error didn't show DB name.
    // But typical XAMPP project.
    
    // Let's try to connect to the DB
    $pdo->exec("USE `$dbName`");
    echo "Connected to database '$dbName'.\n";
    
    // 2. Check column
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'users' AND COLUMN_NAME = 'alamat'");
    $stmt->execute([$dbName]);
    $exists = $stmt->fetchColumn();

    if ($exists) {
        echo "Column 'alamat' ALREADY EXISTS.\n";
    } else {
        echo "Column 'alamat' MISSING. Adding it...\n";
        $pdo->exec("ALTER TABLE users ADD COLUMN alamat TEXT NULL AFTER jenis_kelamin");
        echo "Column 'alamat' ADDED SUCCESSFULLY.\n";
    }

} catch (\PDOException $e) {
    if (strpos($e->getMessage(), "Unknown database") !== false) {
         // Fallback: Try 'web-absensi-giki' (dashes instead of underscores)
         $dbName = 'web-absensi-giki';
         try {
             $pdo->exec("USE `$dbName`");
             echo "Connected to database '$dbName'.\n";
             
             // Check column again
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'users' AND COLUMN_NAME = 'alamat'");
            $stmt->execute([$dbName]);
            $exists = $stmt->fetchColumn();

            if ($exists) {
                echo "Column 'alamat' ALREADY EXISTS.\n";
            } else {
                echo "Column 'alamat' MISSING. Adding it...\n";
                $pdo->exec("ALTER TABLE users ADD COLUMN alamat TEXT NULL AFTER jenis_kelamin");
                echo "Column 'alamat' ADDED SUCCESSFULLY.\n";
            }
         } catch (\PDOException $e2) {
             echo "Error with fallback DB name: " . $e2->getMessage() . "\n";
             exit(1);
         }
    } else {
        echo "Database Error: " . $e->getMessage() . "\n";
        exit(1);
    }
}
