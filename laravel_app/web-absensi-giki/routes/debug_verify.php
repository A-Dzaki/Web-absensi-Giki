<?php

use App\Models\Jadwal;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;

// Mock login or just query a known teacher id if Auth::id() is not available in console context directly without login.
// Assuming we can run this via a route or artisan tinker. But for file-based debug, a route is easiest.

// Let's create a temporary route logic here that I can manually check if I were to run it properly.
// But since I can't browse, I will rely on reading the code I wrote.

// HOWEVER, I can create a route file to verify syntax at least.
Route::get('/debug-siswa-count', function() {
    // Pick a random teacher for demo, or first one found
    $teacher = User::where('role', 'guru')->first();
    if (!$teacher) return "No teacher found";

    Auth::login($teacher);

    $jadwals = Jadwal::where('guru_id', $teacher->id)
        ->with(['kelas' => function($query) {
            $query->withCount('siswa');
        }])
        ->get();

    $output = "<h1>Debug Siswa Count for " . $teacher->name . "</h1>";
    foreach ($jadwals as $j) {
        $output .= "Kelas: " . $j->kelas->nama_kelas . " | Count: " . $j->kelas->siswa_count . "<br>";
    }
    return $output;
});
