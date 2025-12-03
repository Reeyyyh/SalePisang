<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class ResetPasswordMailController extends Controller
{
    // 1. Tampilkan form untuk meminta link reset
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // 2. Kirim email reset password
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        if (!$user->email_verified_at) {
            return back()->with(['status' => 'failed', 'message' => 'Email belum diverifikasi.']);
        }

        $token = Password::createToken($user);
        $resetUrl = url('reset-password?token=' . $token . '&email=' . $user->email);

        Mail::to($user->email)->send(new ResetPasswordMail($user, $resetUrl));

        return back()->with([
            'status' => 'success',
            'message' => 'Link reset password telah dikirim ke email Anda.'
        ]);
    }

    // 3. Tampilkan form reset password (via link email)
    public function showResetForm(Request $request)
    {
        return view('auth.reset-password', [
            'token' => $request->token,
            'email' => $request->email
        ]);
    }

    // 4. Simpan password baru
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with([
                'status' => 'success',
                'message' => 'Password berhasil direset. Silakan login kembali.'
            ]);
        } elseif ($status === Password::INVALID_TOKEN) {
            return redirect()->route('password.link.expired')->with([
                'status' => 'failed',
                'message' => 'Link reset password sudah tidak berlaku atau sudah digunakan. Silakan minta link baru.'
            ]);
        } else {
            return back()->withErrors(['email' => __($status)]);
        }
    }
}
