<?php
$link = 'public/storage';
if (file_exists($link)) {
    echo "Generic Exists\n";
    if (is_link($link)) {
        echo "It is a Link to: " . readlink($link) . "\n";
    } else {
        echo "It is a Directory (Not a link)\n";
    }
} else {
    echo "Does Not Exist\n";
    // Try to create it manually if missing
    try {
        symlink(__DIR__.'/storage/app/public', __DIR__.'/public/storage');
        echo "Created symlink manually.\n";
    } catch (Exception $e) {
        echo "Failed to create symlink: " . $e->getMessage() . "\n";
    }
}
