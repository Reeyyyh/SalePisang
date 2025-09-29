<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Mail\VerificationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MvcVerificationMail extends Controller
{
    public function sendVerificationEmail($user)
    {
        $verificationUrl = route('verification.verify', [
            'id' => $user->id,
            'hash' => sha1($user->email)
        ]);

        Mail::to($user->email)->send(new VerificationMail($user, $verificationUrl));
    }

    public function verifyEmail($id, $hash)
    {
        $user = User::findOrFail($id);

        if (sha1($user->email) !== $hash) {
            return redirect()->route('login')->with([
                'message' => 'Token verifikasi tidak valid.',
                'status' => 'error',
            ]);
        }

        if ($user->verification_expires_at < now()) {
            return redirect()->route('login')->with([
                'message' => 'Link verifikasi sudah kadaluarsa.',
                'status' => 'error',
            ]);
        }

        $user->update([
            'email_verified_at' => now(),
            'verification_expires_at' => null
        ]);

        return redirect()->route('login')->with([
            'message' => 'Email berhasil di verifikasi, silahkan login.',
            'status' => 'success'
        ]);
    }

    public function resendVerificationEmail(Request $request)
    {
        $request->validate(['email' => 'required|string|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with([
                'message' => 'Email tidak di temukan',
                'status' => 'error',
            ]);
        }

        if ($user->email_verified_at) {
            return back()->with([
                'message' => 'Email sudah di verifikasi',
                'status' => 'success'
            ]);
        }

        $user->update([
            'verification_expires_at' => now()->addDay(),
        ]);

        $this->sendVerificationEmail($user);

        return back()->with([
            'message' => 'Email verifikasi telah di kirim ulang',
            'status' => 'success'
        ]);
    }
}
