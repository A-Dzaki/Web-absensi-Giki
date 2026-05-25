<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// We will manually test the logic by mimicking a request or just querying the logic.
// Actually, it's easier to just use CURL via shell or a small PHP script that uses Guzzle if available, 
// OR just boot the framework and call the controller method.
// But we cannot easily "call" the controller method without full request mocking.
// So let's create a "User data seeder" for testing first.

echo "Setting up test data...\n";

// 1. Create/Find a User
$user = \App\Models\User::first();
if (!$user) {
    echo "No user found. Creating one.\n";
    $user = \App\Models\User::create([
        'name' => 'Test Student',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
        'role' => 'siswa',
        'kelas' => 'X-RPL', // Ensure this class exists
        'username' => 'testsiswa',
        'nis' => '12345'
    ]);
}

// 2. Set UID for User
$testUid = 'TESTUID123';
$user->uid_rfid = $testUid;
$user->save();
echo "User {$user->name} assigned UID: {$testUid}\n";

// 3. Ensure Class exists
$kelas = \App\Models\Kelas::firstOrCreate(['nama_kelas' => $user->kelas]);
echo "Class ensured: {$kelas->nama_kelas}\n";

// 4. Create Schedule for TODAY
$hariIni = \Carbon\Carbon::now()->locale('id')->isoFormat('dddd');
$daysMap = [
    'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 
    'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
];
$englishDay = date('l');
// Determine what the DB likely uses (Indo or English) based on previous check, but let's just make sure ONE exists.
// We'll create one for the Indonesian name common expectation.
if(isset($daysMap[$englishDay])) {
    $hariIndo = $daysMap[$englishDay];
} else {
    $hariIndo = $hariIni;
}

echo "Date: $englishDay / $hariIndo\n";

$guru = \App\Models\User::where('role', 'guru')->first();
if(!$guru) {
    $guru = \App\Models\User::create([
        'name' => 'Test Guru',
        'email' => 'guru@example.com',
        'password' => bcrypt('password'),
        'role' => 'guru',
        'username' => 'testguru',
        'nis' => '99999'
    ]);
}

// Create a schedule
\App\Models\Jadwal::create([
    'hari' => $hariIndo,
    'kelas_id' => $kelas->id,
    'mata_pelajaran' => 'Test Subject ' . rand(1,100),
    'jam_mulai' => '07:00:00',
    'jam_selesai' => '15:00:00',
    'guru_id' => $guru->id
]);
echo "Created schedule for $hariIndo for class {$kelas->nama_kelas}.\n";

// 5. Test the Endpoint via internal request dispatch if possible, OR just instruct the agent to use CURL.
// Let's print the CURL command to run.
echo "\nDONE. Test Data Ready.\n";
echo "Run this command to test:\n";
echo "curl \"http://127.0.0.1:8000/input-rfid?uid={$testUid}\"\n";
