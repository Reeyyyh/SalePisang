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
    // 'email' => 'required|string|email|unique:users,email', // Todo : Testing
    // 'password' => 'required|string|min:6', // Todo : Testing
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:100',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:100',
                    'unique:users,email',
                    'regex:/^[A-Za-z0-9._%+-]+@gmail\.com$/',
                ],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/',
                ],
            ], [
                'password.regex' => 'Password harus mengandung huruf besar, angka, simbol, serta minimal 8 karakter.',
                'email.regex' => 'Email harus menggunakan domain @gmail.com.',
                'password.confirmed' => 'The password confirmation does not match.',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
                'email_verified_at' => now(),
            ]);

            return redirect()->route('login')->with([
                'message' => 'Registrasi berhasil! silahkan login',
                'status' => 'success'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangkap error validasi dan redirect dengan errors
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            // Tangani error lain (misal DB error)
            return redirect()->back()->with([
                'message' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.',
                'status' => 'error'
            ])->withInput();
        }
    }

    // Tampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // login white-box
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|string|email|max:100',
                'password' => 'required|string',
            ]); // (S1)

            $remember = $request->has('remember'); // (S2)

            if (Auth::attempt($credentials, $remember)) { // (S3)
                $user = Auth::user(); // (S4)
                $request->session()->regenerate(); // (S5)

                if ($user->role === 'admin') { // (S6)
                    Notification::make()
                        ->title('Login Berhasil')
                        ->body('Selamat datang, Admin!')
                        ->success()
                        ->send();
                    return redirect()->intended('/admin-dashboard'); // (S7)

                } elseif ($user->role === 'seller') { // (S8)
                    Notification::make()
                        ->title('Login Berhasil')
                        ->body('Selamat datang Seller, selamat berjualan!')
                        ->success()
                        ->send();
                    return redirect()->intended('/seller-dashboard'); // (S9)

                } else { // (S10)
                    return redirect()->intended(route('landingpage'))->with([
                        'message' => 'Login berhasil!',
                        'status' => 'success'
                    ]);
                }
            } else { // (S11)
                return redirect()->back()->with([
                    'message' => 'Email atau password salah!',
                    'status' => 'error'
                ])->withInput();
            }
        } catch (\Illuminate\Validation\ValidationException $e) { // (S12)
            return redirect()->back()->with([
                'message' => $e->validator->errors()->first(),
                'status' => 'error'
            ])->withInput(); // (S13)

        } catch (\Exception $e) { // (S14)
            return redirect()->back()->with([
                'message' => 'Terjadi kesalahan saat login. Silakan coba lagi.',
                'status' => 'error'
            ])->withInput(); // (S15)
        }
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
