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
    <div class="text-center p-8 max-w-md shadow-lg rounded-2xl border border-green-200">
        <img src="{{ asset('images/icons/success.png') }}" alt="Success" class="mx-auto w-24 h-24 mb-6">
        <h1 class="text-3xl font-bold text-green-600 mb-2">Pembayaran Berhasil!</h1>
        <p class="text-gray-700 mb-4">Terima kasih telah melakukan pembelian di Structura.</p>
        <a href="{{ route('product') }}"
            class="bg-darkblue text-white px-6 py-2 rounded-lg hover:bg-blue-900 transition">
            Kembali ke Beranda
        </a>
    </div>
</body>

</html>
