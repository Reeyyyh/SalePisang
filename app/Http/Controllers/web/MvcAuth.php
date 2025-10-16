<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Filament\Notifications\Notification;
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
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                // 'email' => 'required|string|email|unique:users,email', // Todo : Testing
                'email' => 'required|string|email|unique:users,email|regex:/@gmail\.com$/',
                // 'password' => 'required|string|min:6', // Todo : Testing
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/',
                ],
            ], [
                'password.regex' => 'Password harus mengandung huruf besar, angka, simbol, serta minimal 8 karakter.',
                'email.regex' => 'Email harus menggunakan domain @gmail.com',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangkap error pertama dan kirim via flash message
            return redirect()->back()->with([
                'message' => $e->validator->errors()->first(),
                'status' => 'error'
            ])->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'verification_expires_at' => now()->addDay(),
            // 'email_verified_at' => null,
            'role' => 'user',
            'email_verified_at' => now(), // Todo : Testing
        ]);

        // Kirim email verifikasi
        // app(MvcVerificationMail::class)->sendVerificationEmail($user); // Todo : skip Testing

        return redirect()->route('login')->with([
            'message' => 'Registrasi berhasil! silahkan login',
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
        try {
            $credentials = $request->validate([
                'email' => 'required|string|email|max:255',
                'password' => 'required|string',
            ]);

            $remember = $request->has('remember');

            if (Auth::attempt($credentials, $remember)) {
                $user = Auth::user();
                $request->session()->regenerate();

                // Cek email verified
                // if (is_null($user->email_verified_at)) {
                //     Auth::logout();
                //     return back()->withErrors(['email' => 'Silakan verifikasi email Anda terlebih dahulu.']);
                // }

                // Cek role dan arahkan
                if ($user->role === 'admin') {
                    Notification::make()
                        ->title('Login Berhasil')
                        ->body('Selamat datang, Admin!')
                        ->success()
                        ->send();

                    return redirect()->intended('/admin-dashboard');
                }

                if ($user->role === 'seller') {
                    Notification::make()
                        ->title('Login Berhasil')
                        ->body('Selamat datang Seller, selamat berjualan!')
                        ->success()
                        ->send();

                    return redirect()->intended('/seller-dashboard');
                }

                // Kalau user biasa → Blade
                return redirect()->intended(route('landingpage'))->with([
                    'message' => 'Login berhasil!',
                    'status' => 'success'
                ]);
            }

            return redirect()->back()->with([
                'message' => 'Email atau password salah!',
                'status' => 'error'
            ])->withInput();
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->with([
                'message' => $e->validator->errors()->first(),
                'status' => 'error'
            ])->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'message' => 'Terjadi kesalahan saat login. Silakan coba lagi.',
                'status' => 'error'
            ])->withInput();
        }
    }


    // public function login(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => 'required|string|email',
    //         'password' => 'required|string',
    //     ]);


    //     $remember = $request->has('remember');

    //     if (Auth::attempt($credentials, $remember)) {
    //         $user = Auth::user();

    //         // Cek email verified
    //         // if (is_null($user->email_verified_at)) {
    //         //     Auth::logout();
    //         //     return back()->withErrors(['email' => 'Silakan verifikasi email Anda terlebih dahulu.']);
    //         // }

    //         // ! still need this??
    //         if ($user->role === 'admin') {
    //             return redirect('/admin-dashboard');
    //         }

    //         if ($user->role === 'seller') {
    //             return redirect('/seller-dashboard');
    //         }

    //         $request->session()->regenerate();
    //         return redirect()->intended(route('landingpage'));
    //     }

    //     return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email')->with([
    //         'message' => 'Email atau password salah!',
    //         'status' => 'danger',
    //     ]);
    // }

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
