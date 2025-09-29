<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Structura</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/icons/Favicon.png') }}" sizes="64x64" type="image/png">
    @vite('resources/css/app.css')
</head>

<body class="bg-white flex items-center justify-center min-h-screen font-Montserrat">
    <div class="text-center p-8 max-w-md shadow-lg rounded-2xl border border-red-200">
        <img src="{{ asset('images/icons/failed.png') }}" alt="Failed" class="mx-auto w-24 h-24 mb-6">
        <h1 class="text-3xl font-bold text-red-600 mb-2">Pembayaran Gagal</h1>
        <p class="text-gray-700 mb-4">Transaksi tidak dapat diproses. Silakan coba lagi atau hubungi support.</p>
        <a href="{{ route('checkout') }}"
            class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
            Kembali ke Checkout
        </a>
    </div>
</body>

</html>
