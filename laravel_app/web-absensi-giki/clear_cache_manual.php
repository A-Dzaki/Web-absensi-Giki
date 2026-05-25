<?php
echo "Clearing caches...\n";

// Clear bootstrap/cache
$files = glob(__DIR__ . '/bootstrap/cache/*.php');
if ($files) {
    foreach ($files as $file) {
        if (basename($file) !== '.gitignore') {
            unlink($file);
            echo "Deleted: $file\n";
        }
    }
}

// Clear storage/framework/views
$files = glob(__DIR__ . '/storage/framework/views/*.php');
if ($files) {
    foreach ($files as $file) {
        if (basename($file) !== '.gitignore') {
            unlink($file);
             // echo "Deleted view cache file\n";
        }
    }
}

echo "Cache cleared.\n";
