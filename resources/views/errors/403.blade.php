<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Denied</title>
    <link rel="icon" href="{{ asset('images/icons/Favicon.png') }}" sizes="64x64" type="image/png">
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-50 font-Montserrat min-h-screen flex flex-col">

    {{-- Loader --}}
    <div id="page-loader"
        class="fixed inset-0 flex items-center justify-center bg-white z-50 transition-opacity duration-300">
        <div class="text-4xl font-bold flex space-x-2 text-blue-700">
            <span class="dot animate-pulse">.</span>
            <span class="dot animate-pulse delay-200">.</span>
            <span class="dot animate-pulse delay-400">.</span>
        </div>
    </div>

    <script>
        window.addEventListener('beforeunload', () => {
            document.getElementById('page-loader').classList.remove('hidden');
        });
        window.addEventListener('load', () => {
            document.getElementById('page-loader').classList.add('hidden');
        });
    </script>

    {{-- Navbar --}}
    <x-navbar />

    <div class="flex-grow flex items-center justify-center px-4 py-16">
        <div class="bg-white shadow-xl rounded-2xl max-w-3xl w-full p-6 md:p-12 flex flex-col md:flex-row items-center md:space-x-12">

            {{-- Content --}}
            <div class="text-center md:text-left">
                <h1 class="text-5xl md:text-6xl font-bold text-red-600 mb-2">403</h1>
                <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-4">Akses Tidak Diizinkan</h2>
                <p class="text-gray-600 mb-6 md:mb-8">Maaf, kamu tidak memiliki hak akses untuk halaman ini.
                    Silakan kembali ke halaman utama atau hubungi administrator jika ini seharusnya akses yang
                    valid.</p>
                <a href="{{ route('landingpage') }}">
                    <button
                        class="px-6 py-3 bg-blue-700 text-white font-semibold rounded-lg text-sm md:text-base transition-transform duration-300 ease-in-out hover:scale-105 hover:bg-blue-800">
                        Kembali Belanja
                    </button>
                </a>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="text-center py-4 text-gray-500 text-sm">
        &copy; {{ date('Y') }} Structura. All rights reserved.
    </footer>

    <style>
        .animate-fadeIn {
            animation: fadeIn 1s ease-in-out forwards;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</body>

</html>
