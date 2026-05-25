<?php

function checkOverlap($a, $b) {
    // Logic from Controller
    if ($a['jam_mulai'] < $b['jam_selesai'] && $a['jam_selesai'] > $b['jam_mulai']) {
        return "Conflict";
    }
    return "Safe";
}

$schedule1 = ['jam_mulai' => '07:00', 'jam_selesai' => '09:00'];
$schedule2 = ['jam_mulai' => '09:00', 'jam_selesai' => '12:00'];

echo "Checking S1 (07-09) vs S2 (09-12):\n";
echo checkOverlap($schedule1, $schedule2) . "\n";

$schedule3 = ['jam_mulai' => '07:00', 'jam_selesai' => '09:01'];
echo "Checking S3 (07-09:01) vs S2 (09-12):\n";
echo checkOverlap($schedule3, $schedule2) . "\n";

?>
