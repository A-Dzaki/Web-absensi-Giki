<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
   protected $fillable = [
    'siswa_id',
    'kelas',
    'tanggal',
    'mata_pelajaran',
    'status',
    'jadwal_id',
    'guru_id',
    'catatan',
    'jam',
];
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
}
