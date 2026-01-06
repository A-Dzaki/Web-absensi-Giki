<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResetPasswordCustomController extends Controller
{
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', new \App\Rules\ValidEmail],
            'password' => 'required|min:6|confirmed'
        ]);

        $data = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$data) {
            return back()->withErrors(['email' => 'Token tidak valid.']);
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/login')->with('status', 'Password berhasil direset.');
    }
}
