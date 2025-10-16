<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="{{ asset('images/icons/Favicon.png') }}" sizes="64x64" type="image/png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Changa:wght@200..800&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <title>Sale Pisang</title>
    @vite('resources/css/app.css')
</head>

<body class="font-Montserrat bg-white">

    {{-- Loader --}}
    <div id="page-loader" class="fixed inset-0 flex items-center justify-center bg-white z-50">
        <div class="text-4xl font-bold flex space-x-1 text-yellow-400">
            <span class="dot animate-pulse delay-[0ms]">.</span>
            <span class="dot animate-pulse delay-[200ms]">.</span>
            <span class="dot animate-pulse delay-[400ms]">.</span>
        </div>
    </div>

    {{-- Loader Script --}}
    <script>
        window.addEventListener('beforeunload', () => {
            document.getElementById('page-loader').classList.remove('hidden');
        });
        window.addEventListener('load', () => {
            document.getElementById('page-loader').classList.add('hidden');
        });
    </script>

    {{-- Navbar --}}
    <x-navbar></x-navbar>

    {{-- Toast Notification --}}
    @if (session('message'))
        @php
            $status = session('status', 'info');
        @endphp
        <x-toast :message="session('message')" :status="$status" />
    @endif

    <div id="notification" class="hidden"></div>

    <section class="py-10 px-4 sm:px-6 lg:px-0">
        <div class="min-h-screen flex flex-col items-center justify-start py-10 sm:py-16 overflow-y-auto pb-[120px]">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 max-w-6xl w-full">

                {{-- Welcome Text --}}
                <div class="text-center md:text-left px-4 sm:px-6 md:px-0">
                    <h2
                        class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-yellow-600 leading-tight animate-popUpOut">
                        Halo, Selamat Datang Di Sale Pisang!
                    </h2>
                    <p class="mt-6 text-sm sm:text-base text-gray-700 animate-popUpOut">
                        Masuk ke akunmu untuk menikmati promo, melihat riwayat pesanan, dan mengelola profil dengan mudah.
                    </p>
                    <p class="mt-8 text-sm sm:text-base text-gray-700 animate-popUpOut">
                        Belum mempunyai akun?
                        <a href="{{ route('register') }}" class="text-yellow-500 font-semibold hover:underline ml-1">
                            Register here
                        </a>
                    </p>
                </div>

                {{-- Login Form --}}
                <form id="login-form" method="POST" action="{{ route('login') }}"
                    class="bg-white rounded-lg max-w-md w-full space-y-6 mx-auto px-6 py-8 shadow-md animate-popUpOut">

                    @csrf
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-yellow-600 mb-4 animate-popUpOut">Sign in</h3>

                    {{-- Email --}}
                    <div class="animate-popUpOut">
                        <input name="email" type="email" required class="bg-gray-100 w-full text-sm sm:text-base text-gray-800 px-4 py-3 rounded-md focus:bg-white focus:ring-2 focus:ring-yellow-400 border border-gray-200"
                            placeholder="Email address"/>
                            {{--  --}}
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="relative animate-popUpOut">
                        <input id="password" name="password" type="password" required
                            class="bg-gray-100 w-full text-sm sm:text-base text-gray-800 px-4 py-3 rounded-md focus:bg-white focus:ring-2 focus:ring-yellow-400 border border-gray-200 pr-10"
                            placeholder="Password" />
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-yellow-600 focus:outline-none">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember + Forgot --}}
                    <div
                        class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 animate-popUpOut">
                        <label class="flex items-center text-sm text-gray-700">
                            <input id="remember" name="remember" type="checkbox"
                                class="h-4 w-4 text-yellow-500 border-gray-300 rounded mr-2">
                            Remember me
                        </label>
                        <a href="{{ route('password.request') }}"
                            class="text-sm text-yellow-600 font-semibold hover:underline">
                            Lupa kata sandi?
                        </a>
                    </div>

                    {{-- Submit --}}
                    <div class="animate-popUpOut">
                        <button type="submit"
                            class="bg-yellow-500 w-full py-3 text-sm sm:text-base font-semibold rounded text-white hover:bg-yellow-600 transition duration-300 shadow-md">
                            Log in
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </section>

    @vite('resources/js/login.js')
</body>

</html>
