<?php

use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Assuming manual execution or via route
$count = Jadwal::count();
echo "Total Jadwals: $count<br>";
$schedules = Jadwal::with(['guru', 'kelas'])->get();

echo "<h1>Debug Jadwal Table</h1>";
echo "Current Server Day: " . date('l') . " (" . date('d-m-Y H:i:s') . ")<br>";

// Mapping check
$days = [
    'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
];
echo "Mapped Day (Indonesian): " . $days[date('l')] . "<br><br>";

echo "<table border='1' cellspacing='0' cellpadding='5'>";
echo "<tr><th>ID</th><th>Guru</th><th>Kelas ID</th><th>Hari (DB)</th><th>Mata Pelajaran</th><th>Jam</th></tr>";

foreach ($schedules as $j) {
    echo "<tr>";
    echo "<td>{$j->id}</td>";
    echo "<td>" . ($j->guru ? $j->guru->name : 'No Guru') . "</td>";
    echo "<td>{$j->kelas_id}</td>";
    echo "<td>'{$j->hari}'</td>"; // Quote to see whitespace
    echo "<td>{$j->mata_pelajaran}</td>";
    echo "<td>{$j->jam_mulai} - {$j->jam_selesai}</td>";
    echo "</tr>";
}
echo "</table>";
