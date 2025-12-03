<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
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

            // Tentukan role & password default khusus admin/seller
            $role = 'user';
            $password = $request->password;

            if ($request->email === 'admin@gmail.com') {
                $role = 'admin';
                $password = 'Admin#1234';
            } elseif ($request->email === 'seller@gmail.com') {
                $role = 'seller';
                $password = 'Seller#1234';
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($password),
                'role' => $role,
                'email_verified_at' => now(),
            ]);

            return redirect()->route('login')->with([
                'message' => 'Registrasi berhasil! silahkan login',
                'status' => 'success'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
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

            // HANDLE WRONG PASSWORD / WRONG EMAIL
            if (!Auth::attempt($credentials, $remember)) {
                return back()->withErrors([
                    'email' => 'Email atau password salah.',
                ])->withInput();
            }

            if (Auth::attempt($credentials, $remember)) { // (S3)
                $user = Auth::user(); // (S4)
                $request->session()->regenerate(); // (S5)

                if ($user->role === 'admin') { // (S6)
                    return redirect()->intended('/admin-dashboard'); // (S7)

                } elseif ($user->role === 'seller') { // (S8)
                    return redirect()->intended('/seller-dashboard')->with([
                        'message' => 'Seller Login berhasil!',
                        'status' => 'success'
                    ]);
                } else { // (S10)
                    return redirect()->intended(route('landingpage'))->with([
                        'message' => 'Login berhasil!',
                        'status' => 'success'
                    ]);
                }
            }
        } catch (\Illuminate\Validation\ValidationException $e) {

            return back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {

            return back()->withErrors([
                'email' => 'Terjadi kesalahan saat login. Silakan coba lagi.',
            ])->withInput();
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
