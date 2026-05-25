<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

// Get the first user who has a photo
$user = \App\Models\User::whereNotNull('foto')->first();

echo "--- STORAGE DEBUG ---\n";
echo "APP_URL: " . env('APP_URL') . "\n";
echo "Public Disk Root: " . config('filesystems.disks.public.root') . "\n";
echo "Public Link Path: " . public_path('storage') . "\n";

if ($user) {
    echo "User: {$user->name} has photo: {$user->foto}\n";
    
    $url = Storage::url($user->foto);
    echo "Generated URL: $url\n";
    
    $fullPath = storage_path('app/public/' . $user->foto);
    echo "Physical Path (Storage): $fullPath\n";
    
    if (file_exists($fullPath)) {
        echo "✅ File exists in storage/app/public.\n";
    } else {
        echo "❌ File MISSING in storage/app/public.\n";
    }

    $publicPath = public_path('storage/' . $user->foto);
    echo "Public Symlink Path: $publicPath\n";
    
    if (file_exists($publicPath)) {
        echo "✅ File accessible via public/storage symlink.\n";
    } else {
        echo "❌ File NOT accessible via public/storage symlink (Link broken?).\n";
    }
} else {
    echo "No user with photo found.\n";
}
