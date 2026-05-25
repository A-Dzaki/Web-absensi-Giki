<?php
$base_dir = 'd:/Semester 5/giki/Giki/laravel_app/web-absensi-giki';
$views_dir = $base_dir . '/resources/views';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($views_dir));
foreach ($iterator as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $content = file_get_contents($file->getPathname());
        $modified = false;
        
        // Add cache busting to css/styletu.css
        if (preg_match('/asset\(\'css\/styletu\.css\'\)(?!\?v=)/i', $content)) {
            $content = preg_replace('/asset\(\'css\/styletu\.css\'\)/i', "asset('css/styletu.css') . '?v=' . time()", $content);
            $modified = true;
        }
        if (preg_match('/asset\(\'css\/styleguruu\.css\'\)(?!\?v=)/i', $content)) {
            $content = preg_replace('/asset\(\'css\/styleguruu\.css\'\)/i', "asset('css/styleguruu.css') . '?v=' . time()", $content);
            $modified = true;
        }
        if (preg_match('/asset\(\'css\/stylesiswa\.css\'\)(?!\?v=)/i', $content)) {
            $content = preg_replace('/asset\(\'css\/stylesiswa\.css\'\)/i', "asset('css/stylesiswa.css') . '?v=' . time()", $content);
            $modified = true;
        }

        if ($modified) {
            file_put_contents($file->getPathname(), $content);
            echo "Added cache busting to: " . $file->getFilename() . "\n";
        }
    }
}
?>
