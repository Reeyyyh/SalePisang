<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Menunggu Pembayaran - Structura</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/icons/Favicon.png') }}" sizes="64x64" type="image/png">
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen font-Montserrat">

    <div class="bg-white shadow-lg rounded-xl p-8 max-w-md w-full text-center">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Menunggu Pembayaran</h1>

        <p class="text-gray-600 mb-2">Invoice:</p>
        <p class="text-lg font-semibold text-gray-900 mb-4">{{ $order->invoice_number }}</p>

        <p class="text-gray-600 mb-2">Total Pembayaran:</p>
        <p class="text-xl font-bold text-green-600 mb-4">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>

        <p class="text-gray-600 mb-2">Batas Waktu Pembayaran:</p>
        <p class="text-gray-800 mb-6">{{ $order->payment_expired_at->format('d M Y H:i') }}</p>

        <!-- Tombol Lanjutkan Pembayaran -->
        <div onclick="window.open('{{ $order->payment_url }}', '_blank')"
            class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 mb-4">
            Lanjutkan Pembayaran
        </div>

        <!-- Tombol Kembali ke Keranjang -->
        <div onclick="window.location.href='{{ route('cart') }}'"
            class="cursor-pointer bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition duration-200">
            Kembali ke Keranjang
        </div>
    </div>

    <script>
    // Replace history supaya back browser tidak kembali ke /checkout
    window.history.replaceState({}, document.title, "{{ route('order.waiting', $order->uuid) }}");

    // Force reload jika user menggunakan back/forward
    window.addEventListener('pageshow', function(event) {
        if (event.persisted || window.performance && window.performance.getEntriesByType('navigation')[0].type === 'back_forward') {
            window.location.reload();
        }
    });
</script>


</body>

</html>
