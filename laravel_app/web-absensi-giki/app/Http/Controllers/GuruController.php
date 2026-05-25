<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class GuruController extends Controller
{
    public function dashboard()
    {
        // Define day names for mapping if needed (View expects Indonesian keys in $jadwalPerHari)
        // Since DB now stores 'Senin', 'Selasa', etc. in 'hari' column, we can group directly.
        
        $jadwalPerHari = Jadwal::where('guru_id', Auth::id())
            ->with(['kelas' => function($query) {
                $query->withCount('siswa');
            }])
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('hari');

        return view('guru.dashboard', compact('jadwalPerHari'));
    }

    public function kelolaAbsensi(Request $request)
    {
        $request->validate([
            'kelas' => 'required|string',
            'jadwal_id' => 'required|integer',
            'tanggal' => 'nullable|date',
        ]);

        $jadwal = Jadwal::with('kelas')->findOrFail($request->jadwal_id);

        // Determine Target Date
        // If 'tanggal' is provided in Request, use it.
        // If NOT provided, calculate the date for this week corresponding to the Schedule's Day ($jadwal->hari)
        
        if ($request->has('tanggal')) {
            $reqDate = \Carbon\Carbon::parse($request->tanggal);
        } else {
            // Map Indonesian Day to English Day for Carbon
            $indoDayToEng = [
                'Senin' => 'Monday', 'Selasa' => 'Tuesday', 'Rabu' => 'Wednesday',
                'Kamis' => 'Thursday', 'Jumat' => 'Friday', 'Sabtu' => 'Saturday', 'Minggu' => 'Sunday'
            ];
            
            $targetDayName = $indoDayToEng[$jadwal->hari] ?? 'Monday';
            
            // Get the date of that day in the CURRENT WEEK
            $reqDate = now()->startOfWeek()->modify($targetDayName);
            
            // IF the calculated date is in the future (e.g. valid day is Friday but today is Monday),
            // Default to the PREVIOUS occurrence (last week's Friday) so user can view/edit past attendance
            if ($reqDate->isFuture()) {
                $reqDate->subWeek();
            }
        }

        $targetDate = $reqDate->format('Y-m-d');
        
        // Validation: Ensure we are managing the correct date for this schedule item
        // Since the schedule is specific to a date (per DB schema), we ideally should only allow managing THAT date.
        // However, if the user navigates from a generic link, we might default to the schedule's date.
        
        // REMOVED: Strict date check causing infinite loop for Weekly Schedules
        // if ($targetDate !== $jadwal->tanggal) { ... }

        // NEW: Validate strictly against the Day of the Week
        $dayNameIndo = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $selectedDayName = $dayNameIndo[\Carbon\Carbon::parse($targetDate)->format('l')];

        if ($selectedDayName !== $jadwal->hari) {
            // Determine fallback date (correct day of this week)
            $indoDayToEng = [
                'Senin' => 'Monday', 'Selasa' => 'Tuesday', 'Rabu' => 'Wednesday',
                'Kamis' => 'Thursday', 'Jumat' => 'Friday', 'Sabtu' => 'Saturday', 'Minggu' => 'Sunday'
            ];
            $targetDayName = $indoDayToEng[$jadwal->hari] ?? 'Monday';
            $fallbackDateObj = now()->startOfWeek()->modify($targetDayName);
            
            // Fix: If fallback is future, use last week's
            if ($fallbackDateObj->isFuture()) {
                $fallbackDateObj->subWeek();
            }
            $fallbackDate = $fallbackDateObj->format('Y-m-d');

            return redirect()->route('guru.kelola', [
                'kelas' => $request->kelas,
                'mapel' => $request->mapel,
                'jadwal_id' => $request->jadwal_id,
                'tanggal' => $fallbackDate
            ])->with('error', "Tanggal yang dipilih bukan hari {$jadwal->hari}. Dialihkan ke tanggal yang sesuai.");
        }
        
        // Prevent future dates
        if (\Carbon\Carbon::parse($targetDate)->isFuture()) {
             return redirect()->route('guru.dashboard')
                ->with('error', 'Anda tidak dapat mengisi absensi untuk tanggal di masa depan.');
        }

        $siswa = User::where('role', 'siswa')
                     ->where('kelas', $request->kelas)
                     ->with(['absensis' => function($q) use ($targetDate, $jadwal) {
                         $q->where('tanggal', $targetDate)
                           ->where('jadwal_id', $jadwal->id);
                     }])
                     ->orderBy('name')
                     ->orderBy('name')
                     ->get();

        $tanggal = $targetDate;

        return view('guru.kelola-guru', compact('siswa', 'jadwal', 'tanggal'));
    }

    public function simpanAbsensi(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|integer',
            'tanggal'   => 'required|date',
            // 'mapel' is no longer strictly required from request since we get it from DB
            'absen'     => 'required|array',
            'absen.*'   => 'required|in:H,I,S,A',
        ]);

        $jadwal = Jadwal::findOrFail($request->jadwal_id);

        foreach ($request->absen as $siswaId => $status) {
            Absensi::updateOrCreate(
                [
                    'siswa_id'  => $siswaId,
                    'jadwal_id' => $jadwal->id,
                    'tanggal'   => $request->tanggal,
                ],
                [
                    'mata_pelajaran' => $jadwal->mata_pelajaran, // Use Source of Truth
                    'kelas'     => $request->kelas,
                    'status'    => $status,
                    'catatan'   => $request->catatan[$siswaId] ?? null,
                    'guru_id'   => Auth::id(),
                    'jam'       => now()->format('H:i:s'),
                ]
            );
        }

        // LOG ACTIVITY
        \App\Models\ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Simpan Absensi',
            'description' => "Mengisi absensi Kelas {$request->kelas} Mapel {$jadwal->mata_pelajaran} Tanggal {$request->tanggal}",
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', 'Absensi berhasil disimpan!');
    }

    public function profil()
    {
        return view('guru.profil-guru');
    }

    public function updateProfil(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'name'    => 'required|string|max:255',
        'email'   => 'required|email|unique:users,email,' . $user->id,
        'no_telp' => 'nullable|string|max:20',
        'foto'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $user->name = $request->name;
    $user->email = $request->email;
    $user->no_telp = $request->no_telp;

    if ($request->hasFile('foto')) {
        $uploadFolder = 'uploads/profil-guru';

        // Simpan langsung ke public_html/uploads/profil-guru
        $folderPath = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/' . $uploadFolder;

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        // Hapus foto lama jika ada
        if (!empty($user->foto)) {
            $oldFile = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/' . $user->foto;

            if (file_exists($oldFile)) {
                @unlink($oldFile);
            }
        }

        $file = $request->file('foto');
        $filename = 'guru_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

        $file->move($folderPath, $filename);

        $user->foto = $uploadFolder . '/' . $filename;
    }

    $user->save();

    return back()->with('success', 'Profil berhasil diperbarui!');
    }
}