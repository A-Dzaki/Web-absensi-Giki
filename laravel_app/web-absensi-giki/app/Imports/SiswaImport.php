<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class SiswaImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Expected Columns: nis, nama, kelas, jenis_kelamin, alamat
        if (!isset($row['nis']) || !isset($row['nama'])) {
            return null; // Skip invalid rows
        }

        $nis = $row['nis'];
        $name = $row['nama'];
        $kelas = $row['kelas'] ?? '9A';
        $jk = isset($row['jenis_kelamin']) ? strtoupper($row['jenis_kelamin']) : 'L'; // Default L
        if ($jk !== 'L' && $jk !== 'P') $jk = 'L';
        
        $alamat = $row['alamat'] ?? '-';
        
        // Auto-generate credentials
        $username = $nis;
        $password = Hash::make((string)$nis); // Password = NIS
        $email = $nis . '@student.giki';
        
        // Use updateOrCreate to handle existing users
        return User::updateOrCreate(
            ['nis' => $nis], // Key to check
            [
                'name' => $name,
                'role' => 'siswa',
                'kelas' => $kelas,
                'jenis_kelamin' => $jk,
                'alamat' => $alamat,
                'username' => $username,
                'password' => $password, // Will overwrite password if re-imported
                'email' => $email,
            ]
        );
    }
}
