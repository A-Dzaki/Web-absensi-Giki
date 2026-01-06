<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;

class AbsensiController extends Controller
{
    public function store(Request $request)
    {
        $mapel = $request->mata_pelajaran;
        $status = $request->status;

        Absensi::create([
            'siswa_id' => auth()->id(),
            'kelas' => auth()->user()->kelas,
            'tanggal' => today(),
            'mata_pelajaran' => $mapel,
            'status' => $status,
            'jam' => now()->format('H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Absensi berhasil disimpan!');
    }

    public function inputRfid(Request $request)
    {
        try {
            $uid = $request->query('uid');
            
            if (!$uid) {
                return response()->json(['message' => 'UID MISSING', 'status' => 'ERROR'], 200); // Return 200 so Arduino can read body
            }

            // 1. Cari User
            $user = \App\Models\User::where('uid_rfid', $uid)->first();
            if (!$user) {
                return response()->json(['message' => 'Tidak Dikenal', 'status' => 'ERROR'], 200);
            }

            // 2. Tentukan Hari Ini (FORCE INDONESIA)
            $mapHari = [
                'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
            ];
            $hariInggris = date('l'); 
            $hariIni = $mapHari[$hariInggris]; 

            // 3. Ambil Jadwal
            $kelasUser = \App\Models\Kelas::where('nama_kelas', $user->kelas)->first();
            
            if (!$kelasUser) {
                 return response()->json(['message' => 'Kelas Invalid', 'status' => 'ERROR'], 200);
            }

            // Cari jadwal yang harinya cocok (Case Insensitive)
            $jadwals = \App\Models\Jadwal::where('kelas_id', $kelasUser->id)
                        ->where('hari', $hariIni) 
                        ->get();

            if ($jadwals->isEmpty()) {
                 // Coba cari pakai bahasa inggris kalau indo kosong
                 $jadwals = \App\Models\Jadwal::where('kelas_id', $kelasUser->id)
                            ->where('hari', $hariInggris)
                            ->get();
            }

            if ($jadwals->isEmpty()) {
                 return response()->json(['message' => 'Tidak Ada Jadwal', 'status' => 'ERROR'], 200);
            }

            $totalMarked = 0;
            $statusAbsen = 'H';
            
            // Cek Keterlambatan (Lewat jam 7 pagi)
            if (date('H') >= 7) {
                $statusAbsen = 'H'; 
                $catatan = 'Terlambat via RFID';
            } else {
                $catatan = 'Hadir via RFID (Auto)';
            }

            foreach ($jadwals as $jadwal) {
                // Cek duplikasi
                $existing = Absensi::where('siswa_id', $user->id)
                            ->where('tanggal', today())
                            ->where('jadwal_id', $jadwal->id) 
                            ->first();

                if (!$existing) {
                    Absensi::create([
                        'siswa_id' => $user->id,
                        'kelas' => $user->kelas,
                        'tanggal' => today(),
                        'mata_pelajaran' => $jadwal->mata_pelajaran,
                        'status' => $statusAbsen,
                        'jam' => now()->format('H:i:s'),
                        'guru_id' => $jadwal->guru_id,
                        'jadwal_id' => $jadwal->id,
                        'catatan' => $catatan
                    ]);
                    $totalMarked++;
                }
            }

            if ($totalMarked == 0) {
                 return response()->json([
                    'message' => 'Sudah Absen',
                    'status' => 'ALREADY',
                    'siswa' => $user->name
                ], 200);
            }

            return response()->json([
                'message' => 'Selamat Datang ' . $user->name,
                'status' => 'SUCCESS',
                'marked_count' => $totalMarked
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Server Error: ' . $e->getMessage(), 'status' => 'ERROR'], 200);
        }
    }
}
