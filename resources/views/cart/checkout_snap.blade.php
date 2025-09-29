<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Structura</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/icons/Favicon.png') }}" sizes="64x64" type="image/png">

    @vite('resources/css/app.css')
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-50">

    <div class="text-center">
        <div class="flex justify-center mb-6">
            <svg class="animate-spin h-12 w-12 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                </path>
            </svg>
        </div>
        {{-- <h1 class="text-2xl font-semibold text-gray-800 mb-2">Menghubungkan ke Midtrans...</h1>
        <p class="text-gray-500">Mohon tunggu sebentar, Anda akan diarahkan ke halaman pembayaran.</p> --}}
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <script>
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                window.location.href = '/payment/success';
            },
            onPending: function(result) {
                window.location.href = '/payment/pending';
            },
            onError: function(result) {
                window.location.href = '/payment/error';
            },
            onClose: function() {
                alert('Kamu menutup popup tanpa menyelesaikan pembayaran.');
                window.location.href = '{{ route('profile.history') }}';
            }
        });
    </script>

</body>

</html>
