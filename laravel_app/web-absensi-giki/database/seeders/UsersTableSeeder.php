<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus dulu user dengan email yang sama (idempotent)
        User::where('email', 'guru@example.com')->delete();
        User::where('email', 'siswa@example.com')->delete();
        User::where('email', 'tu@example.com')->delete();

        // Guru
        User::create([
            'name' => 'Guru Contoh',
            'username' => 'guru01',
            'email' => 'guru@example.com',
            'nis' => 'GURU001',
            'role' => 'guru',
            'kelas' => null,
            'password' => Hash::make('password123'),
        ]);

        // Siswa
        User::create([
            'name' => 'Siswa Contoh',
            'username' => 'siswa01',
            'email' => 'siswa@example.com',
            'nis' => 'SISWA001',
            'role' => 'siswa',
            'kelas' => '9A',
            'password' => Hash::make('password123'),
        ]);

        // Tata Usaha
        User::create([
            'name' => 'TU Contoh',
            'username' => 'tu01',
            'email' => 'tu@example.com',
            'nis' => 'TU001',
            'role' => 'tatausaha',
            'kelas' => null,
            'password' => Hash::make('password123'),
        ]);
    }
}
