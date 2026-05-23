<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;
use App\Models\Kelas;
use Illuminate\Support\Facades\Storage;
use App\Rules\ValidEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TataUsahaController extends Controller
{
    public function dashboard(Request $request)
    {
        $tanggal = $request->get('tanggal', date('Y-m-d')); // Default to today

        $totalSiswa = User::where('role', 'siswa')->count();
        $totalKelas = Kelas::count();
        $totalGuru = User::whereIn('role', ['guru', 'walikelas'])->count();

        $absenHariIni = Absensi::whereDate('tanggal', $tanggal)->get();
        $hadir = $absenHariIni->where('status', 'H')->count();
        $totalAbsen = $absenHariIni->count();

        $persentaseHariIni = $totalAbsen > 0 ? ($hadir / $totalAbsen) * 100 : 0;

        // Data for Chart: Group by Class
        $absensiPerKelas = User::where('role', 'siswa')
            ->leftJoin('absensis', function ($join) use ($tanggal) {
                $join->on('users.id', '=', 'absensis.siswa_id')
                    ->whereDate('absensis.tanggal', $tanggal);
            })
            ->selectRaw('users.kelas as kelas, 
                         count(users.id) as total_siswa, 
                         sum(case when absensis.status = "H" then 1 else 0 end) as hadir_count')
            ->groupBy('users.kelas')
            ->orderBy('users.kelas')
            ->get();

        $dataKehadiranPerKelas = [
            'labels' => $absensiPerKelas->pluck('kelas'),
            'values' => $absensiPerKelas->map(function ($item) {
                return $item->total_siswa > 0 ? round(($item->hadir_count / $item->total_siswa) * 100, 1) : 0;
            })
        ];

        $dataKeterangan = [
            'hadir' => $totalAbsen > 0 ? round($absenHariIni->where('status', 'H')->count() / $totalAbsen * 100, 1) : 0,
            'alfa' => $totalAbsen > 0 ? round($absenHariIni->where('status', 'A')->count() / $totalAbsen * 100, 1) : 0,
            'izin' => $totalAbsen > 0 ? round($absenHariIni->where('status', 'I')->count() / $totalAbsen * 100, 1) : 0,
            'sakit' => $totalAbsen > 0 ? round($absenHariIni->where('status', 'S')->count() / $totalAbsen * 100, 1) : 0,
        ];

        return view('tatausaha.dashboard-tatausaha', compact(
            'totalSiswa',
            'totalKelas',
            'totalGuru',
            'persentaseHariIni',
            'dataKehadiranPerKelas',
            'dataKeterangan',
            'tanggal'
        ));
    }

    public function dataKelas(Request $request)
    {
        // Fetch Classes from Kelas Model
        $daftarKelas = Kelas::orderBy('nama_kelas')->pluck('nama_kelas');

        // Determine Default Class (First available or '9A')
        $defaultKelas = $daftarKelas->first() ?? '9A';

        // Check Request -> Session -> Default
        if ($request->has('kelas')) {
            $kelas = $request->get('kelas');
            session(['last_kelas_tu' => $kelas]);
        } else {
            $kelas = session('last_kelas_tu', $defaultKelas);
        }

        // Ensure the selected class actually exists (e.g. if session holds a deleted class)
        if ($daftarKelas->isNotEmpty() && !$daftarKelas->contains($kelas)) {
            $kelas = $defaultKelas;
        }

        // Fetch Current Class Data (Wali Kelas etc)
        $kelasData = Kelas::where('nama_kelas', $kelas)->first();

        // Fetch Teachers for Dropdown
        $guru = User::whereIn('role', ['guru', 'walikelas'])->orderBy('name')->get();

        // Search Parameter
        $search = $request->get('search');

        $siswa = User::where('role', 'siswa')
            ->where('kelas', $kelas)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10);

        $siswa->appends($request->only(['kelas', 'search', 'bulan', 'tahun', 'mapel']));

        // [NEW] Get Backups (Safeguarded)
        try {
            $backups = \App\Models\Backup::latest()->get();
        } catch (\Exception $e) {
            $backups = collect([]);
        }

        // Filter Parameters for Attendance Tab
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));
        $mapel = $request->get('mapel');

        // Fetch List of Subjects (from Jadwal) for Filter
        $daftarMapel = \App\Models\Jadwal::distinct()->pluck('mata_pelajaran');

        // REKAP ABSENSI LOGIC
        // Using leftJoin to include all students even if no attendance
        $rekapAbsensi = User::where('users.role', 'siswa')
            ->where('users.kelas', $kelas)
            ->leftJoin('absensis', function ($join) use ($bulan, $tahun, $mapel) {
                $join->on('users.id', '=', 'absensis.siswa_id')
                    ->whereMonth('absensis.tanggal', $bulan)
                    ->whereYear('absensis.tanggal', $tahun);
                if ($mapel)
                    $join->where('absensis.mata_pelajaran', $mapel);
            })
            ->selectRaw('
                users.id, users.nis, users.name, users.jenis_kelamin,
                COUNT(CASE WHEN absensis.status = "H" THEN 1 END) as hadir,
                COUNT(CASE WHEN absensis.status = "I" THEN 1 END) as izin,
                COUNT(CASE WHEN absensis.status = "S" THEN 1 END) as sakit,
                COUNT(CASE WHEN absensis.status = "A" THEN 1 END) as alpa
            ')
            ->groupBy('users.id', 'users.nis', 'users.name', 'users.jenis_kelamin')
            ->orderBy('users.name')
            ->get();

        // Calculate Percentage (Append to object)
        $rekapAbsensi->map(function ($r) {
            $total = $r->hadir + $r->izin + $r->sakit + $r->alpa;
            $r->persentase = $total > 0 ? round(($r->hadir / $total) * 100) : 0;
            return $r;
        });

        // Return View
        return view('tatausaha.data-kelas', compact(
            'siswa',
            'kelas',
            'daftarKelas',
            'kelasData',
            'guru',
            'search',
            'backups',
            'bulan',
            'tahun',
            'mapel',
            'daftarMapel',
            'rekapAbsensi'
        ));

        // Fetch Subjects for Dropdown
        $daftarMapel = Absensi::select('mata_pelajaran')
            ->whereNotNull('mata_pelajaran')
            ->distinct()
            ->orderBy('mata_pelajaran')
            ->pluck('mata_pelajaran');

        // Rekap Absensi Query
        $rekapAbsensi = User::where('users.role', 'siswa')
            ->where('users.kelas', $kelas)
            ->leftJoin('absensis', function ($join) use ($bulan, $tahun, $mapel) {
                $join->on('users.id', '=', 'absensis.siswa_id')
                    ->whereMonth('absensis.tanggal', $bulan)
                    ->whereYear('absensis.tanggal', $tahun);

                if ($mapel) {
                    $join->where('absensis.mata_pelajaran', $mapel);
                }
            })
            ->selectRaw('
                users.id,
                users.nis as nis,
                users.name,
                COUNT(CASE WHEN absensis.status = "H" THEN 1 END) as hadir,
                COUNT(CASE WHEN absensis.status = "I" THEN 1 END) as izin,
                COUNT(CASE WHEN absensis.status = "S" THEN 1 END) as sakit,
                COUNT(CASE WHEN absensis.status = "A" THEN 1 END) as alpa
            ')
            ->groupBy('users.id', 'users.nis', 'users.name')
            ->orderBy('users.name')
            ->get()
            ->map(function ($r) {
                $total = $r->hadir + $r->izin + $r->sakit + $r->alpa;
                $r->persentase = $total > 0 ? round(($r->hadir / $total) * 100, 1) : 0;
                return $r;
            });

        return view('tatausaha.data-kelas', compact(
            'kelas',
            'daftarKelas',
            'siswa',
            'rekapAbsensi',
            'guru',
            'kelasData',
            'daftarMapel',
            'bulan',
            'tahun',
            'mapel',
            'search'
        ));
    }

    // [NEW] Import Siswa Excel
    public function importSiswa(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\SiswaImport, $request->file_excel);
            return back()->with('success', 'Data Siswa berhasil diimport!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    // [NEW] Store Kelas
    public function storeKelas(Request $request)
    {
        $data = $request->validate([
            'nama_kelas' => 'required|unique:kelas,nama_kelas',
            'walikelas' => 'required', // This will be the Teacher's Name
        ]);

        // Check if Teacher is already Wali Kelas
        if (Kelas::where('walikelas', $data['walikelas'])->exists()) {
            return back()->with('error', 'Guru tersebut sudah menjadi Wali Kelas di kelas lain!');
        }

        Kelas::create([
            'nama_kelas' => $data['nama_kelas'],
            'walikelas' => $data['walikelas'],
            'tingkat' => preg_replace('/[^0-9]/', '', $data['nama_kelas']) // Extract number from 7A -> 7
        ]);

        return back()->with('success', 'Kelas berhasil ditambahkan!');
    }

    // [NEW] Update Kelas
    public function updateKelas(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:kelas,id',
            'nama_kelas' => 'required',
            'walikelas' => 'required',
        ]);

        $kelas = Kelas::findOrFail($data['id']);

        // Check uniqueness excluding current class
        if (Kelas::where('nama_kelas', $data['nama_kelas'])->where('id', '!=', $kelas->id)->exists()) {
            return back()->withErrors(['nama_kelas' => 'Nama kelas sudah ada.']);
        }

        // Check if Teacher is already Wali Kelas (excluding this class)
        if (Kelas::where('walikelas', $data['walikelas'])->where('id', '!=', $kelas->id)->exists()) {
            return back()->with('error', 'Guru tersebut sudah menjadi Wali Kelas di kelas lain!');
        }

        $kelas->update([
            'nama_kelas' => $data['nama_kelas'],
            'walikelas' => $data['walikelas'],
        ]);

        return back()->with('success', 'Data kelas berhasil diperbarui!');
    }

    // [NEW] Delete Kelas
    public function destroyKelas($id)
    {
        $kelas = Kelas::findOrFail($id);
        $namaKelas = $kelas->nama_kelas;

        // Note: Students and schedules with this class name will remain in DB
        // but the class entry itself is deleted
        $kelas->delete();

        return back()->with('success', "Kelas {$namaKelas} berhasil dihapus!");
    }

   public function siswaStore(Request $request)
    {
        try {

            if ($request->has('nis_nip') && !$request->has('nis')) {
                $request->merge([
                    'nis' => $request->input('nis_nip')
                ]);
            }

            $data = $request->validate([
                'nis' => 'required|unique:users,nis',
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'jenis_kelamin' => 'required|in:L,P',
                'alamat' => 'nullable',
                'kelas' => 'required',
                'uid_rfid' => 'nullable|unique:users,uid_rfid',
            ]);

            // buat token setup akun
            $token = Str::random(64);

            // simpan siswa TANPA username/password
            $siswa = User::create([
                'nis' => $data['nis'],
                'name' => $data['name'],
                'email' => $data['email'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'alamat' => $data['alamat'] ?? null,
                'kelas' => $data['kelas'],
                'uid_rfid' => $data['uid_rfid'] ?? null,
                'role' => 'siswa',
                'remember_token' => $token,
            ]);

            // link setup akun
            $link = url('/setup-account/' . $token);

            // kirim email
            Mail::raw(
                "Halo {$siswa->name},

Silakan setup akun siswa Anda melalui link berikut:

{$link}

Absensi GIKI",
                function ($message) use ($siswa) {
                    $message->to($siswa->email)
                        ->subject('Setup Akun Siswa');
                }
            );

            return back()->with(
                'success',
                'Siswa berhasil ditambahkan dan email setup akun telah dikirim.'
            );

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Gagal tambah siswa: ' . $e->getMessage()
            );
        }
    }
    // Update Siswa
    public function siswaUpdate(Request $request, $id)
    {
        $siswa = User::findOrFail($id);

        $data = $request->validate([
            'nis' => 'required|unique:users,nis,' . $id,
            'name' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable',
            'kelas' => 'required',
            'username' => 'required|unique:users,username,' . $id,
            'uid_rfid' => 'nullable|unique:users,uid_rfid,' . $id
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']);
        }

        $siswa->update($data);

        return back()->with('success', 'Data siswa berhasil diperbarui!');
    }

    // Delete Siswa
    public function siswaDestroy($id)
    {
        $siswa = User::findOrFail($id);
        $siswa->delete();

        return back()->with('success', 'Siswa berhasil dihapus!');
    }

    public function profil()
    {
        return view('tatausaha.profil-tatausaha');
    }

    public function updateProfil(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => ['required', new ValidEmail, 'unique:users,email,' . auth()->id()],
            'no_telp' => 'nullable'
        ]);

        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if (auth()->user()->foto && file_exists(public_path(auth()->user()->foto))) {
                @unlink(public_path(auth()->user()->foto));
            }

            // Save directly to public/uploads/profil-tu (Bypass Symlink)
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/profil-tu'), $filename);

            $data['foto'] = 'uploads/profil-tu/' . $filename;
        }

        auth()->user()->update($data);

        return back()->with('success', 'Profil diperbarui!');
    }

    // ===== KELOLA GURU =====
    // SELF-HEALING: Ensure 'jadwals' table has correct schema
    private function ensureJadwalSchema()
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('jadwals', 'hari')) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE jadwals ADD COLUMN hari VARCHAR(20) NULL AFTER kelas_id");
            }
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE jadwals MODIFY COLUMN tanggal DATE NULL");
        } catch (\Exception $e) {
        }
    }

    // ===== KELOLA GURU =====
    // ===== KELOLA GURU =====
    public function dataGuru(Request $request)
    {
        $search = $request->get('search');

        // Filter Guru by Name if search exists
        $guru = User::where('role', 'guru')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");
            })
            ->with('jadwals.kelas')
            ->orderBy('name', 'asc')
            ->paginate(10);

        // Append search query to pagination links
        $guru->appends(['search' => $search]);

        $daftarKelas = \App\Models\Kelas::all();
        return view('tatausaha.data-guru', compact('guru', 'daftarKelas', 'search'));
    }

    public function storeGuru(Request $request)
    {
        $this->ensureJadwalSchema();

        $data = $request->validate([
            'name' => 'required',
            'nis' => 'nullable|numeric|unique:users,nis',
            'email' => 'required|email|unique:users,email',
            'no_telp' => 'nullable',
            'mapel' => 'nullable',
        ]);

        // simpan guru tanpa username/password
        $guru = User::create([
            'name' => $data['name'],
            'nis' => $data['nis'] ?? null,
            'email' => $data['email'],
            'no_telp' => $data['no_telp'] ?? null,
            'mapel' => $data['mapel'] ?? null,
            'role' => 'guru',
        ]);

        // buat token setup akun
        $token = \Illuminate\Support\Str::random(64);

        $guru->remember_token = $token;
        $guru->save();

        // link setup akun
        $link = url('/setup-account/' . $token);

        // kirim email
        try {

            \Illuminate\Support\Facades\Mail::raw(
                "Halo {$guru->name},

Silakan buat username dan password akun Anda melalui link berikut:

{$link}

Absensi GIKI",
                function ($message) use ($guru) {
                    $message->to($guru->email)
                        ->subject('Setup Akun Guru');
                }
            );

        } catch (\Exception $e) {

            \Illuminate\Support\Facades\Log::error($e->getMessage());

            return back()->with(
                'warning',
                'Data guru berhasil ditambahkan, tetapi email gagal dikirim.'
            );
        }

        return back()->with(
            'success',
            'Data guru berhasil ditambahkan dan email setup akun telah dikirim.'
        );
    }
    public function updateGuru(Request $request, $id)
    {
        $this->ensureJadwalSchema();

        $guru = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'required',
            'nis' => 'nullable|numeric|unique:users,nis,' . $id, // NIP
            'username' => 'required|unique:users,username,' . $id,
            'email' => ['required', new ValidEmail, 'unique:users,email,' . $id],
            'no_telp' => 'nullable',
            'mapel' => 'nullable'
        ]);

        // Update password only if filled
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $data['password'] = bcrypt($request->password);
        }

        $guru->update($data);

        // Always clear old schedules first
        $guru->jadwals()->delete();

        // Sync Jadwal with Partial Save
        $warningMsg = '';
        if ($request->has('jadwals')) {
            $result = $this->saveSchedulesWithConflictCheck($guru, $request->jadwals, $data['mapel'] ?? '-');

            if (!empty($result['conflicts'])) {
                $warningMsg = "Data berhasil diperbarui, namun beberapa jadwal <b>dihapus otomatis</b> karena tabrakan:<br><ul>";
                foreach ($result['conflicts'] as $c) {
                    $warningMsg .= "<li>{$c}</li>";
                }
                $warningMsg .= "</ul>";
            }
        } else {
            // If no schedules provided, clear 'kelas_diampu'
            $guru->update(['kelas_diampu' => null]);
        }

        if ($warningMsg) {
            return back()->with('warning', $warningMsg);
        }

        return back()->with('success', 'Data Guru berhasil diperbarui!');
    }

    // Delete Guru
    public function destroyGuru($id)
    {
        $guru = User::where('role', 'guru')->findOrFail($id);

        // Hapus semua jadwal terkait
        $guru->jadwals()->delete();

        // Hapus user
        $guru->delete();

        // LOG AKTIVITAS
        try {
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Hapus Guru',
                'description' => "Menghapus Guru: {$guru->name} (NIP: {$guru->nis})",
                'ip_address' => request()->ip()
            ]);
        } catch (\Exception $e) {
            // Ignore logging error to prevent crash
        }

        return back()->with('success', 'Data Guru berhasil dihapus!');
    }

    // [NEW] View Activity Logs
    public function logAktivitas(Request $request)
    {
        $logs = \App\Models\ActivityLog::with('user')
            ->latest()
            ->paginate(20);

        return view('tatausaha.logs', compact('logs'));
    }

    // Helper to process schedules, save valid ones, and return conflicts
    private function saveSchedulesWithConflictCheck($guru, $jadwals, $mataPelajaran)
    {
        $accepted = []; // Stores accepted schedules to check agaist
        $conflicts = [];
        $kelasDiampu = [];

        foreach ($jadwals as $j) {
            if (empty($j['hari']) || empty($j['kelas_id']) || empty($j['jam_mulai']) || empty($j['jam_selesai'])) {
                continue;
            }

            // Check Collision against Accepted List
            $isConflict = false;
            foreach ($accepted as $acc) {
                if ($acc['hari'] === $j['hari']) {
                    // Overlap Condition
                    if ($acc['jam_mulai'] < $j['jam_selesai'] && $acc['jam_selesai'] > $j['jam_mulai']) {
                        $isConflict = true;
                        // Get Class Names for detailed error
                        $cls1 = \App\Models\Kelas::find($acc['kelas_id'])->nama_kelas ?? '?';
                        $cls2 = \App\Models\Kelas::find($j['kelas_id'])->nama_kelas ?? '?';

                        $conflicts[] = "Jadwal <b>{$cls2} ({$j['hari']} {$j['jam_mulai']}-{$j['jam_selesai']})</b> tabrakan dengan {$cls1}";
                        break;
                    }
                }
            }

            if (!$isConflict) {
                // Save to DB
                $guru->jadwals()->create([
                    'hari' => $j['hari'],
                    'kelas_id' => $j['kelas_id'],
                    'jam_mulai' => $j['jam_mulai'],
                    'jam_selesai' => $j['jam_selesai'],
                    'mata_pelajaran' => $mataPelajaran
                ]);

                // Add to accepted (for checking subsequent inputs)
                $accepted[] = $j;

                // Collect Class Name
                $cls = \App\Models\Kelas::find($j['kelas_id']);
                if ($cls) {
                    $kelasDiampu[] = $cls->nama_kelas;
                }
            }
        }

        // Auto-update 'kelas_diampu' field
        $kelasDiampuString = !empty($kelasDiampu) ? implode(', ', array_unique($kelasDiampu)) : null;
        $guru->update(['kelas_diampu' => $kelasDiampuString]);

        return ['conflicts' => $conflicts];
    }

    // EXPORT PDF (Print View)
    public function cetakPdf(Request $request)
    {
        $kelas = $request->get('kelas', '9A');
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));
        $mapel = $request->get('mapel');

        $kelasData = Kelas::where('nama_kelas', $kelas)->first();

        // Reuse Logic
        $rekapAbsensi = User::where('users.role', 'siswa')
            ->where('users.kelas', $kelas)
            ->leftJoin('absensis', function ($join) use ($bulan, $tahun, $mapel) {
                $join->on('users.id', '=', 'absensis.siswa_id')
                    ->whereMonth('absensis.tanggal', $bulan)
                    ->whereYear('absensis.tanggal', $tahun);
                if ($mapel)
                    $join->where('absensis.mata_pelajaran', $mapel);
            })
            ->selectRaw('
                users.id, users.nis, users.name, users.jenis_kelamin,
                COUNT(CASE WHEN absensis.status = "H" THEN 1 END) as hadir,
                COUNT(CASE WHEN absensis.status = "I" THEN 1 END) as izin,
                COUNT(CASE WHEN absensis.status = "S" THEN 1 END) as sakit,
                COUNT(CASE WHEN absensis.status = "A" THEN 1 END) as alpa
            ')
            ->groupBy('users.id', 'users.nis', 'users.name', 'users.jenis_kelamin')
            ->orderBy('users.name')
            ->get();

        return view('tatausaha.cetak-pdf', compact('kelas', 'kelasData', 'bulan', 'tahun', 'mapel', 'rekapAbsensi'));
    }

    // EXPORT EXCEL (CSV)
    public function exportExcel(Request $request)
    {
        $kelas = $request->get('kelas', '9A');
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));
        $mapel = $request->get('mapel');

        // Reuse Logic
        $data = User::where('users.role', 'siswa')
            ->where('users.kelas', $kelas)
            ->leftJoin('absensis', function ($join) use ($bulan, $tahun, $mapel) {
                $join->on('users.id', '=', 'absensis.siswa_id')
                    ->whereMonth('absensis.tanggal', $bulan)
                    ->whereYear('absensis.tanggal', $tahun);
                if ($mapel)
                    $join->where('absensis.mata_pelajaran', $mapel);
            })
            ->selectRaw('
                users.nis, users.name, users.jenis_kelamin,
                COUNT(CASE WHEN absensis.status = "H" THEN 1 END) as hadir,
                COUNT(CASE WHEN absensis.status = "I" THEN 1 END) as izin,
                COUNT(CASE WHEN absensis.status = "S" THEN 1 END) as sakit,
                COUNT(CASE WHEN absensis.status = "A" THEN 1 END) as alpa
            ')
            ->groupBy('users.id', 'users.nis', 'users.name', 'users.jenis_kelamin')
            ->orderBy('users.name')
            ->get();

        $filename = "Rekap_Absensi_Kelas_{$kelas}_" . date('F', mktime(0, 0, 0, $bulan, 1)) . "_{$tahun}.csv";

        return response()->streamDownload(function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['NIS', 'Nama Siswa', 'L/P', 'Hadir', 'Izin', 'Sakit', 'Alpa', 'Total Pertemuan', 'Persentase Kehadiran']);

            foreach ($data as $d) {
                $total = $d->hadir + $d->izin + $d->sakit + $d->alpa;
                $persentase = $total > 0 ? round(($d->hadir / $total) * 100) . '%' : '0%';

                fputcsv($file, [
                    $d->nis,
                    $d->name,
                    $d->jenis_kelamin,
                    $d->hadir,
                    $d->izin,
                    $d->sakit,
                    $d->alpa,
                    $total,
                    $persentase
                ]);
            }
            fclose($file);
        }, $filename);
    }
    // RESET DATA SISWA (Ganti Tahun Ajaran)
    public function resetSiswa(Request $request)
    {
        // 1. BACKUP DATA FIRST
        $students = User::where('role', 'siswa')->get();

        if ($students->count() > 0) {
            \App\Models\Backup::create([
                'name' => 'Backup Siswa - ' . now()->format('d M Y H:i'),
                'data' => $students->toJson(),
                'total_records' => $students->count()
            ]);
        }

        // 2. DELETE ALL STUDENTS
        $deleted = User::where('role', 'siswa')->delete();

        // LOG AKTIVITAS
        try {
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Reset Tahun Ajaran',
                'description' => "Menghapus {$deleted} data siswa & membuat Backup.",
                'ip_address' => request()->ip()
            ]);
        } catch (\Exception $e) {
        }

        return back()->with('success', "Reset Berhasil. {$deleted} siswa dihapus. Backup otomatis telah dibuat.");
    }

    // RESTORE SISWA FROM BACKUP
    public function restoreSiswa($id)
    {
        $backup = \App\Models\Backup::findOrFail($id);
        $data = json_decode($backup->data, true);

        $count = 0;
        foreach ($data as $s) {
            // Re-create user if not exists (check by username or NIS)
            $exists = User::where('username', $s['username'])->orWhere('nis', $s['nis'])->exists();
            if (!$exists) {
                User::create([
                    'nis' => $s['nis'],
                    'name' => $s['name'],
                    'username' => $s['username'],
                    'password' => $s['password'], // Encrypted hash preserved
                    'role' => 'siswa',
                    'kelas' => $s['kelas'],
                    'jenis_kelamin' => $s['jenis_kelamin'],
                    'alamat' => $s['alamat'],
                    'uid_rfid' => $s['uid_rfid'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $count++;
            }
        }

        return back()->with('success', "Restore Berhasil! {$count} data siswa telah dikembalikan.");
    }

    // ===== KELOLA TATA USAHA =====
    public function dataTataUsaha(Request $request)
    {
        $search = $request->get('search');

        $tataUsaha = User::where('role', 'tatausaha')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('name', 'asc')
            ->paginate(10);

        $tataUsaha->appends(['search' => $search]);

        return view('tatausaha.data-tatausaha', compact('tataUsaha', 'search'));
    }

    public function storeTataUsaha(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'email' => ['required', new ValidEmail, 'unique:users,email'],
            'no_telp' => 'nullable',
        ]);

        $data['password'] = bcrypt($data['password']);
        $data['role'] = 'tatausaha';

        User::create($data);

        return back()->with('success', 'Data Tata Usaha berhasil ditambahkan!');
    }

    public function updateTataUsaha(Request $request, $id)
    {
        $tataUsaha = User::where('role', 'tatausaha')->findOrFail($id);

        $data = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $id,
            'email' => ['required', new ValidEmail, 'unique:users,email,' . $id],
            'no_telp' => 'nullable',
        ]);

        // Update password only if filled
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $data['password'] = bcrypt($request->password);
        }

        $tataUsaha->update($data);

        return back()->with('success', 'Data Tata Usaha berhasil diperbarui!');
    }

    public function destroyTataUsaha($id)
    {
        $tataUsaha = User::where('role', 'tatausaha')->findOrFail($id);

        // Prevent deleting yourself
        if ($tataUsaha->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        // Prevent deleting the last Tata Usaha account
        $totalTataUsaha = User::where('role', 'tatausaha')->count();
        if ($totalTataUsaha <= 1) {
            return back()->with('error', 'Tidak dapat menghapus akun terakhir! Harus ada minimal 1 akun Tata Usaha.');
        }

        $tataUsaha->delete();

        return back()->with('success', 'Data Tata Usaha berhasil dihapus!');
    }
}
