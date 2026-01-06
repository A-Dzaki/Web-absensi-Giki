<?php
$target = str_replace('/', '\\', __DIR__ . '/storage/app/public');
$link = str_replace('/', '\\', __DIR__ . '/public/storage');

echo "Target: $target\n";
echo "Link: $link\n";

// 1. Check Source
if (!is_dir($target)) {
    die("CRITICAL: Source directory '$target' does not exist! Creating it...\n");
    mkdir($target, 0777, true);
}

// 2. Clear Existing
if (file_exists($link)) {
    echo "Existing link found. Deleting...\n";
    if (is_link($link)) unlink($link);
    else rmdir($link);
}

// 3. Try PHP Symlink
echo "Attempt 1: PHP symlink()...\n";
if (@symlink($target, $link)) {
    echo "SUCCESS: PHP symlink created.\n";
    exit;
}
echo "Failed: " . error_get_last()['message'] . "\n";

// 4. Try Windows junction (mklink /J) - Doesn't require Admin usually
echo "Attempt 2: Windows Junction (mklink /J)...\n";
$cmd = "mklink /J \"$link\" \"$target\"";
echo "Command: $cmd\n";
exec($cmd, $output, $return);
echo implode("\n", $output) . "\n";

if ($return === 0 && file_exists($link)) {
    echo "SUCCESS: Junction created.\n";
    exit;
}

// 5. Try Windows Soft Link (mklink /D)
echo "Attempt 3: Windows Soft Link (mklink /D)...\n";
$cmd = "mklink /D \"$link\" \"$target\"";
exec($cmd, $output, $return); 
echo implode("\n", $output) . "\n";

if ($return === 0 && file_exists($link)) {
    echo "SUCCESS: Soft Link created.\n";
    exit;
}

echo "FAILURE: Could not create link. You may need to run this shell as Administrator.\n";
