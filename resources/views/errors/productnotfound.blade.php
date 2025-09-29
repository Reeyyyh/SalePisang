<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="{{ asset('images/icons/Favicon.png') }}" sizes="64x64" type="image/png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Changa:wght@200..800&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <title>Produk Tidak Ditemukan - Structura</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-white font-Montserrat">
    {{-- Loader --}}
    <div id="page-loader" class="fixed inset-0 flex items-center justify-center bg-white z-50">
        <div class="text-4xl font-bold flex space-x-1 text-darkblue">
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

    {{-- Content --}}
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 md:px-8">
        <a href="{{ route('product') }}" class="text-sm text-gray-600 mb-4 inline-block">
            <img src="{{ asset('images/icons/back.png') }}" alt="Back" class="w-3 h-3 inline-block mr-2">
            Continue shopping
        </a>

        <div
            class="bg-[#F8F9F9] p-6 sm:p-8 rounded-lg shadow min-h-[280px] sm:min-h-[320px] md:min-h-[400px] flex items-center justify-center">
            <div class="text-center">
                <h2 class="text-[20px] sm:text-[24px] font-light mb-4 text-darkblue">Produk Tidak Ditemukan</h2>
                <h3 class="text-sm sm:text-base md:text-lg font-medium mb-6 text-darkblue">
                    Kami tidak menemukan hasil untuk: "<span class="font-semibold">{{ $searchQuery }}</span>"
                </h3>

                <a href="{{ route('product') }}">
                    <button
                        class="mt-4 px-6 py-2 sm:px-8 md:px-10 bg-darkblue text-white font-semibold rounded-[10px] text-sm sm:text-base transition duration-300 transform hover:scale-105">
                        Continue Shopping
                    </button>
                </a>
            </div>
        </div>
    </div>
</body>

</html>
