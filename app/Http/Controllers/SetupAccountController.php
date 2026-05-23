<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SetupAccountController extends Controller
{
    public function showForm($token)
    {
        $user = User::where('remember_token', $token)->first();

        // token tidak ditemukan
        if (!$user) {
        return response()->view('auth.link-expired');
        }

        // kalau username/password SUDAH ada
        if ($user->username && $user->password) {
        return response()->view('auth.link-expired');
        }

        // tampilkan form setup akun
        return view('auth.setup-account', compact('user', 'token'));
    }

    public function store(Request $request, $token)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::where('remember_token', $token)->first();

        if (!$user) {
        return response()->view('auth.link-expired');
        }

        $user->username = $request->username;
        $user->password = Hash::make($request->password);

        // hapus token setelah setup selesai
        $user->remember_token = null;

        $user->save();

        return redirect('/login')
            ->with('success', 'Akun berhasil dibuat');
    }
}