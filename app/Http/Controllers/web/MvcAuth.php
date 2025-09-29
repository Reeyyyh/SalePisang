<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class MvcAuth extends Controller
{
    // Tampilkan halaman register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses register
    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6' // Todo : Testing
            // 'password' => [
            //     'required',
            //     'string',
            //     'min:8',
            //     'confirmed',
            //     'regex:/^[A-Z](?=.*\d)(?=.*[\W_]).{7,}$/',
            // ],

        ], [
            'password.regex' => 'Password harus diawali huruf besar, mengandung angka dan simbol, serta minimal 8 karakter.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'verification_expires_at' => now()->addDay(),
            // 'email_verified_at' => null,
            'role' => 'user',
            'email_verified_at' => now(), // Todo : test
        ]);

        // Kirim email verifikasi
        // app(MvcVerificationMail::class)->sendVerificationEmail($user); // Todo : test

        return redirect()->route('login')->with([
            'message' => 'Registrasi berhasil! Silakan cek email untuk verifikasi sebelum login.',
            'status' => 'success'
        ]);
    }

    // Tampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);


        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Cek email verified
            if (is_null($user->email_verified_at)) {
                Auth::logout();
                return back()->withErrors(['email' => 'Silakan verifikasi email Anda terlebih dahulu.']);
            }

            // ! still need this??
            if ($user->role === 'admin') {
                return redirect('/admin-dashboard');
            }

            if ($user->role === 'seller') {
                return redirect('/seller-dashboard');
            }

            $request->session()->regenerate();
            return redirect()->intended(route('landingpage'));
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email')->with([
            'message' => 'Email atau password salah!',
            'status' => 'danger',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {

        /** @var \App\Models\User $user */
        $user = Auth::user();

        Auth::logout();

        if ($user) {
            // Hapus remember_token dari database
            $user->setRememberToken(null);
            $user->save();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Cookie::queue(Cookie::forget(Auth::getRecallerName()));

        return redirect()->route('login')->with([
            'message' => 'Berhasil logout!',
            'status' => 'success'
        ]);
    }
}
