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

    <title>Sale Pisang - Register</title>
    @vite('resources/css/app.css')
</head>

<body class="font-Montserrat bg-white">

    <x-navbar></x-navbar>

    @if (session('message'))
        @php
            $status = session('status', 'info');
        @endphp
        <x-toast :message="session('message')" :status="$status" />
    @endif

    <section class="py-10 px-4 sm:px-6 lg:px-0">
        <div class="min-h-screen flex flex-col items-center justify-center pb-[120px]">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 max-w-6xl w-full">

                {{-- Welcome Text --}}
                <div class="text-center md:text-left px-4 sm:px-6 md:px-0">
                    <h2 class="text-4xl font-extrabold text-yellow-600 leading-tight animate-popUpOut">
                        Halo, Selamat Datang Di Sale Pisang!
                    </h2>
                    <p class="mt-6 text-base text-gray-800 animate-popUpOut">
                        Bergabung sekarang dan nikmati promo, melihat riwayat pesanan, dan kelola profil dengan mudah.
                    </p>
                    <p class="mt-8 text-base text-gray-800 animate-popUpOut">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-yellow-500 font-semibold hover:underline ml-1">Login
                            here</a>
                    </p>
                </div>

                {{-- Register Form --}}
                <form id="register-form" method="POST" action="{{ route('register') }}"
                    class="bg-white rounded-lg max-w-md w-full space-y-6 mx-auto px-6 py-8 shadow-lg animate-popUpOut">

                    @csrf
                    <h3 class="text-yellow-600 text-3xl font-extrabold mb-8 animate-popUpOut">
                        Register
                    </h3>

                    <div class="space-y-4">
                        {{-- Username --}}
                        <div class="animate-popUpOut">
                            <input name="name" type="text" required
                                class="bg-gray-100 w-full text-sm text-gray-800 px-4 py-3.5 rounded-md focus:ring-2 focus:ring-yellow-400 focus:bg-white border border-gray-200"
                                placeholder="Username" value="{{ old('name') }}" />
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="animate-popUpOut">
                            <input name="email" type="email" autocomplete="email" required
                                class="bg-gray-100 w-full text-sm text-gray-800 px-4 py-3.5 rounded-md focus:ring-2 focus:ring-yellow-400 focus:bg-white border border-gray-200"
                                placeholder="Email address" value="{{ old('email') }}" />
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="relative animate-popUpOut">
                            <input id="password" name="password" type="password" autocomplete="new-password" required
                                class="bg-gray-100 w-full text-sm text-gray-800 px-4 py-3.5 pr-10 rounded-md focus:ring-2 focus:ring-yellow-400 focus:bg-white border border-gray-200"
                                placeholder="Password" />
                            <button type="button" onclick="togglePassword('password', 'eyePassword')"
                                class="absolute inset-y-0 right-3 flex items-center">
                                <svg id="eyePassword" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    class="w-5 h-5 text-gray-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="relative animate-popUpOut">
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                autocomplete="new-password" required
                                class="bg-gray-100 w-full text-sm text-gray-800 px-4 py-3.5 pr-10 rounded-md focus:ring-2 focus:ring-yellow-400 focus:bg-white border border-gray-200"
                                placeholder="Confirm Password" />
                            <button type="button" onclick="togglePassword('password_confirmation', 'eyeConfirm')"
                                class="absolute inset-y-0 right-3 flex items-center">
                                <svg id="eyeConfirm" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    class="w-5 h-5 text-gray-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="!mt-6">
                        <button type="submit"
                            class="bg-yellow-500 hover:bg-yellow-600 w-full py-3 text-sm font-semibold rounded text-white shadow-md animate-popUpOut">
                            Register
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    @vite('resources/js/app.js')
</body>

</html>
