<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="{{ asset('images/icons/Favicon.png') }}" sizes="64x64" type="image/png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Changa:wght@200..800&display=swap" rel="stylesheet">

    <!-- TailwindCSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <title>Structura</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-white font-Montserrat">
    <x-navbar />

    @if (session('message'))
        @php $status = session('status', 'info'); @endphp
        <x-toast :message="session('message')" :status="$status" />
    @endif

    <div class="w-full max-w-[1280px] mx-auto py-10 px-4">
        <!-- Breadcrumbs -->
        <nav class="text-sm text-gray-500 mb-4 text-left">
            <a href="{{ route('landingpage') }}" class="hover:underline">HOME</a> /
            <a href="{{ route('profile') }}" class="hover:underline font-semibold text-darkblue">ACCOUNT</a> /
            <span class="text-darkblue font-bold">{{ $title ?? '' }}</span>
        </nav>

        <!-- Title: hidden on all devices except desktop -->
        <h1 class="hidden lg:block text-2xl lg:text-3xl font-extrabold text-darkblue text-center mb-10">
            {{ $title ?? 'ACCOUNT' }}
        </h1>

        <!-- Back to shopping -->
        <a href="{{ route('product') }}" class="text-sm text-gray-600 mb-4 inline-block">
            <img src="{{ asset('images/icons/back.png') }}" alt="Back" class="w-3 h-3 inline-block mr-2">
            Continue shopping
        </a>

        <!-- Layout -->
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- SIDEBAR for Desktop only -->
            <aside class="hidden lg:block w-[200px] bg-[#F8F9F9] rounded-xl shadow p-6">
                <ul class="space-y-10 font-semibold text-darkblue text-sm">
                    <li class="{{ request()->is('profile') ? 'text-red-500' : '' }}">
                        <a href="{{ route('profile') }}">Profil Saya</a>
                    </li>
                    <li class="{{ request()->is('profile/address') ? 'text-red-500' : '' }}">
                        <a href="{{ route('profile.address') }}">Alamat</a>
                    </li>
                    <li class="{{ request()->is('profile/history') ? 'text-red-500' : '' }}">
                        <a href="{{ route('profile.history') }}">Pesanan Saya</a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="text-darkblue font-semibold hover:text-red-500 w-full text-left">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </aside>

            <!-- HORIZONTAL MENU for Tablet and Mobile -->
            <div class="block lg:hidden">
                <div
                    class="flex w-full flex-nowrap justify-between gap-2 text-xs sm:text-sm font-semibold text-darkblue mb-6">
                    @php
                        $navBase =
                            'flex items-center justify-center h-[44px] px-2 rounded-md border transition-all duration-150 bg-[#F3F4F6] w-full';
                        $navActive = 'bg-red-100 text-red-500';
                        $navInactive = 'text-darkblue';
                    @endphp

                    <a href="{{ route('profile') }}"
                        class="{{ $navBase }} {{ request()->is('profile') ? $navActive : $navInactive }}">
                        Profil
                    </a>

                    <a href="{{ route('profile.address') }}"
                        class="{{ $navBase }} {{ request()->is('profile/address') ? $navActive : $navInactive }}">
                        Alamat
                    </a>

                    <a href="{{ route('profile.history') }}"
                        class="{{ $navBase }} {{ request()->is('profile/history') ? $navActive : $navInactive }}">
                        Pesanan
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="{{ $navBase }} hover:text-red-500 text-darkblue">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:w-6 sm:h-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>


            <!-- Main Content -->
            <div class="w-full lg:w-3/4">
                {{ $slot }}
            </div>
        </div>
    </div>

    @vite('resources/js/profile.js')
</body>

</html>
