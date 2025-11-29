<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="{{ asset('images/icons/Favicon.png') }}" sizes="64x64" type="image/png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Changa:wght@200..800&display=swap" rel="stylesheet">

    <title>404 - Page Not Found</title>
    @vite('resources/css/app.css')
</head>

    {{-- Navbar --}}
    <x-navbar></x-navbar>

    <main class="flex flex-col items-center justify-center min-h-[70vh] text-center px-4 sm:px-6 md:px-8">

        <!-- Error Text -->
        <h1 class="text-6xl sm:text-7xl md:text-8xl font-bold text-red-500 mb-4 animate-pulse">404</h1>
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-semibold mb-4 text-gray-800">Oops! Page not found.</h2>
        <p class="text-gray-600 mb-6 max-w-md mx-auto">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>

        <!-- Button -->
        <a href="{{ route('landingpage') }}">
            <button class="px-6 py-2 sm:px-8 sm:py-3 md:px-10 md:py-3 bg-darkblue text-white font-semibold rounded-lg text-sm sm:text-base md:text-lg shadow-lg transition-transform duration-300 transform hover:scale-105 hover:shadow-xl">
                Back to Shop
            </button>
        </a>
    </main>
</body>

</html>
