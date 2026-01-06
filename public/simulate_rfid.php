<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// 1. Get a valid Student UID
$user = \App\Models\User::where('role', 'siswa')->whereNotNull('uid_rfid')->first();
if (!$user) {
    die("Error: No student with UID found for testing.\n");
}
$uid = $user->uid_rfid;
echo "Testing with UID: $uid (User: {$user->name})\n";

// 2. Simulate Request
$request = Illuminate\Http\Request::create('/input-rfid', 'GET', ['uid' => $uid]);
$response = $kernel->handle($request);

echo "Status: " . $response->getStatusCode() . "\n";
echo "Content: " . $response->getContent() . "\n";
