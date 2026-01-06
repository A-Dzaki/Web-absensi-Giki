<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    public function dashboard()
    {
        // Siswa login -> ambil kelas_id dari user (lookup via string kelas)
        $namaKelas = auth()->user()->kelas;
        $kelas = \App\Models\Kelas::where('nama_kelas', $namaKelas)->first();
        $kelasId = $kelas ? $kelas->id : null;

        // Ambil jadwal berdasarkan kelas_id dan tanggal hari ini
        $tanggalHariIni = Carbon::today()->toDateString();

        // Ambil jadwal:
        // 1. Yang tanggalnya hari ini (khusus)
        // 2. ATAU yang harinya sama dengan hari ini (rutin) DAN tanggalnya NULL
        // Pastikan load 'guru'
        
        $todayNameMap = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $hariIni = $todayNameMap[Carbon::now()->format('l')];

        $jadwalHariIni = Jadwal::with('guru')
            ->where('kelas_id', $kelasId)
            ->where(function($q) use ($tanggalHariIni, $hariIni) {
                $q->where('tanggal', $tanggalHariIni)
                  ->orWhere('hari', $hariIni);
            })
            ->orderBy('jam_mulai')
            ->get();

        return view('siswa.dashboard-siswa', compact('jadwalHariIni'));
    }

    public function statusAbsen(Request $request)
    {
        $mapel = $request->query('mapel');

        $query = Absensi::where('siswa_id', auth()->id());

        if ($mapel) {
            $query->where('mata_pelajaran', $mapel);
        }

        $absensi = $query->orderByDesc('tanggal')->get();

        $statusHariIni = Absensi::where('siswa_id', auth()->id())
            ->whereDate('tanggal', today())
            ->when($mapel, fn($q) => $q->where('mata_pelajaran', $mapel))
            ->latest() // Get the most recent one
            ->first();

        return view('siswa.status-absen-siswa', compact('absensi', 'statusHariIni', 'mapel'));
    }

    public function detailKehadiran(Request $request)
    {
        $query = Absensi::where('siswa_id', auth()->id());

        // Filter by Date
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // Filter by Mapel
        if ($request->filled('mapel')) {
            $query->where('mata_pelajaran', $request->mapel);
        }

        $absensi = $query->with('guru')->orderByDesc('tanggal')->orderByDesc('jam')->get();

        // Get list of distinct subjects for the dropdown from JADWAL (all available subjects)
        $namaKelas = auth()->user()->kelas;
        $kelas = \App\Models\Kelas::where('nama_kelas', $namaKelas)->first();
        
        $mapelList = [];
        if ($kelas) {
            $mapelList = Jadwal::where('kelas_id', $kelas->id)
                        ->select('mata_pelajaran')
                        ->distinct()
                        ->pluck('mata_pelajaran');
        }

        return view('siswa.detailkehadiran-siswa', [
            'absensi' => $absensi,
            'mapelList' => $mapelList,
            'selectedTanggal' => $request->tanggal,
            'selectedMapel' => $request->mapel
        ]);
    }

    public function getStatusAbsen(Request $request)
    {
        $jadwalId = $request->query('jadwal_id');
        
        // Cek absen berdasarkan jadwal_id dan hari ini
        $statusHariIni = Absensi::where('siswa_id', auth()->id())
            ->whereDate('tanggal', today())
            ->when($jadwalId, fn($q) => $q->where('jadwal_id', $jadwalId))
            ->first();

        if ($statusHariIni) {
            return response()->json([
                'status' => $statusHariIni->status, // H, I, S, A
                'jam' => $statusHariIni->jam ? \Carbon\Carbon::parse($statusHariIni->jam)->format('H:i') : '-',
                'catatan' => $statusHariIni->catatan
            ]);
        } else {
            return response()->json(['status' => null]); // Belum Absen
        }
    }

    public function jadwal()
    {
        $namaKelas = auth()->user()->kelas;
        $kelas = \App\Models\Kelas::where('nama_kelas', $namaKelas)->first();
        $kelasId = $kelas ? $kelas->id : null;

        // Define day names for mapping Carbon's English output
        $daysMap = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];

        // Ambil jadwal minggu ini berdasarkan kelas_id, grouped by Hari (Senin-Minggu)
        $jadwalPerHari = Jadwal::with('guru')
            ->where('kelas_id', $kelasId)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('hari');

        return view('siswa.jadwal-siswa', compact('jadwalPerHari'));
    }

    public function profil() 
    { 
        return view('siswa.profil-siswa'); 
    }

    public function updateProfil(Request $request) 
    {
        $data = $request->validate([
            'email'  => 'required|email|unique:users,email,' . auth()->id(),
            'no_telp'=> 'nullable',
            'alamat' => 'nullable'
        ]);

        if ($request->hasFile('foto')) {

            // hapus foto lama
            if (auth()->user()->foto && file_exists(public_path(auth()->user()->foto))) {
                @unlink(public_path(auth()->user()->foto));
            }

            // Simpan langsung ke public/uploads/profil-siswa (Bypass Symlink)
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/profil-siswa'), $filename);
            
            $data['foto'] = 'uploads/profil-siswa/' . $filename;
        }

        auth()->user()->update($data);

        return back()->with('success', 'Profil diperbarui!');
    }
}
