<?php
$target = __DIR__ . '/storage/app/public';
$link = __DIR__ . '/public/storage';

echo "Target: $target\n";
echo "Link: $link\n";

if (file_exists($link)) {
    echo "Existing public/storage found.\n";
    if (is_link($link)) {
        echo "It is a link. Deleting...\n";
        unlink($link);
    } elseif (is_dir($link)) {
        echo "It is a DIRECTORY. renaming to storage_backup_".time()."\n";
        rename($link, $link . '_backup_'.time());
    } else {
        unlink($link);
    }
}

if (symlink($target, $link)) {
    echo "SUCCESS: Symlink created.\n";
} else {
    echo "ERROR: Failed to create symlink.\n";
}
