<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordCustomController extends Controller
{
    public function showLinkForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => ['required', new \App\Rules\ValidEmail]]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        Mail::raw(
            "Klik link reset password: " . url('/password/reset/' . $token),
            function ($msg) use ($request) {
                $msg->to($request->email)
                    ->subject('Reset Password');
            }
        );

        return back()->with('status', 'Link reset password telah dikirim!');
    }
}
