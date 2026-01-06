<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Rules\ValidEmail;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'nis_nip' => 'required',
            'role' => 'required',
            'username' => 'required|unique:users,username',
            'email' => ['required', new ValidEmail, 'unique:users,email'],
            'password' => 'required|confirmed|min:6',
        ]);

        User::create([
            'name' => $request->name,
            // migrations rename column to `nis` — map form input `nis_nip` to DB column `nis`
            'nis' => $request->nis_nip,
            'role' => $request->role,
            'kelas' => $request->kelas,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Redirect to login form route
        return redirect()->route('loginForm')->with('success', 'Registrasi berhasil!');
    }
}
