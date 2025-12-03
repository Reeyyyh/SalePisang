<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Changa:wght@200..800&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('images/icons/Favicon.png') }}" sizes="64x64" type="image/png">

    <title>Sale Pisang</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 font-sans">

    {{-- Toast global --}}
    @if (session('message'))
        @php
            $status = session('status', 'info');
        @endphp
        <x-toast :message="session('message')" :status="$status" />
    @endif

    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg flex flex-col">
            <!-- Header -->
            <div
                class="p-6 font-bold text-2xl border-b border-gray-200 text-green-600 flex items-center justify-center">
                Seller Dashboard
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('seller.dashboard') }}"
                    class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-green-50 transition-colors {{ request()->routeIs('seller.dashboard') ? 'bg-green-100 font-semibold text-green-700' : 'text-gray-700' }}">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 20V14H14V20H19V10H14V4H10V10H5V0H0V10H5V20H10Z" />
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('seller.orders.index') }}"
                    class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-green-50 transition-colors {{ request()->routeIs('seller.orders.*') ? 'bg-green-100 font-semibold text-green-700' : 'text-gray-700' }}">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4H17V6H3V4ZM3 8H17V10H3V8ZM3 12H13V14H3V12Z" />
                    </svg>
                    Orders
                </a>

                <a href="{{ route('seller.products.index') }}"
                    class="flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-green-50 transition-colors {{ request()->routeIs('seller.products.*') ? 'bg-green-100 font-semibold text-green-700' : 'text-gray-700' }}">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 2H16V6H4V2ZM4 8H16V18H4V8Z" />
                    </svg>
                    Products
                </a>
            </nav>

            <!-- Logout -->
            <form action="{{ route('logout') }}" method="POST" class="p-4 mt-auto">
                @csrf
                <button type="submit"
                    class="w-full py-2 px-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-9V5" />
                    </svg>
                    Logout
                </button>
            </form>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-y-auto bg-gray-50">
            @yield('content')
        </main>
    </div>

</body>

</html>
