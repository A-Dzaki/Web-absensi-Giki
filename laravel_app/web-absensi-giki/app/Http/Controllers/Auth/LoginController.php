<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // TAMPILKAN HALAMAN LOGIN
    public function showLoginForm()
    {
        // Jika sudah login, jangan tunjukkan halaman login — arahkan ke dashboard sesuai role
        // Auth check removed to allow viewing login page even if logged in
        // if (Auth::check()) { ... }

        return view('auth.login');
    }

    // PROSES LOGIN
    public function login(Request $request)
    {
        // VALIDASI INPUT (Role tidak perlu validasi input lagi)
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // CEK LOGIN
        $credentials = $request->only('username', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // SINGLE DEVICE LOGIN CHECK
            // Semua user akan logout dari perangkat sebelumnya
            Auth::logoutOtherDevices($request->password);

            // SINKRONISASI ROLE OTOMATIS
            // Kita tidak perlu mengecek $request->role lagi karena user tidak input role.
            // Langsung arahkan berdasarkan role di database.

            // Redirect based on role
            switch ($user->role) {
                case 'guru':
                case 'walikelas':
                    return redirect()->route('guru.dashboard');
                case 'siswa':
                    return redirect()->route('siswa.dashboard');
                case 'tatausaha':
                    return redirect()->route('tatausaha.dashboard');
                default:
                    Auth::logout();
                    return back()->withErrors(['role' => 'Role akun Anda tidak dikenali. Hubungi admin.']);
            }
        }

        // JIKA USERNAME/PASSWORD SALAH
        return back()->withErrors(['username' => 'Username atau password salah.']);
    }

    // LOGOUT
    public function logout()
    {
        Auth::logout();
        return redirect()->route('loginForm');
    }
}
