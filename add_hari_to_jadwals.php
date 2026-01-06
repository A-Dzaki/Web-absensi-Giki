<?php
// Script to add 'hari' column to jadwals table
$host = '127.0.0.1';
$db   = 'absensi_giki';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass, $options);
} catch (\PDOException $e) {
    // If DB name is wrong, try connecting without DB and check
    try {
        $pdo = new PDO("mysql:host=$host;charset=$charset", $user, $pass, $options);
        // Try to find the correct DB
        $stmt = $pdo->query("SHOW DATABASES LIKE 'absensi%'");
        $dbs = $stmt->fetchAll();
        if (count($dbs) > 0) {
            $db = array_values($dbs[0])[0];
            $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass, $options);
        } else {
            die("Database not found. Error: " . $e->getMessage());
        }
    } catch (\PDOException $ex) {
        die("Connection failed: " . $ex->getMessage());
    }
}

echo "Connected to database: $db\n";

// 1. Check if 'hari' column exists
$stmt = $pdo->query("SHOW COLUMNS FROM jadwals LIKE 'hari'");
$exists = $stmt->fetch();

if (!$exists) {
    echo "Adding 'hari' column...\n";
    try {
        $pdo->exec("ALTER TABLE jadwals ADD COLUMN hari VARCHAR(20) NULL AFTER kelas_id");
        echo "Column 'hari' added successfully.\n";
    } catch (PDOException $e) {
        echo "Error adding column: " . $e->getMessage() . "\n";
    }
} else {
    echo "Column 'hari' already exists.\n";
}

// 2. Make 'tanggal' nullable because generic schedule doesn't use specific date
echo "Modifying 'tanggal' column to be nullable...\n";
try {
    $pdo->exec("ALTER TABLE jadwals MODIFY COLUMN tanggal DATE NULL");
    echo "Column 'tanggal' modified successfully.\n";
} catch (PDOException $e) {
    echo "Error modifying column: " . $e->getMessage() . "\n";
}

echo "Database update complete.\n";
