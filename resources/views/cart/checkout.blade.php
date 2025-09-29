<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="{{ asset('images/icons/Favicon.png') }}" sizes="64x64" type="image/png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Structura</title>

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Changa:wght@200..800&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    @vite('resources/css/app.css')
</head>

<body class="bg-[#F9FAFB] font-Montserrat">

    {{-- Loader --}}
    <div id="page-loader" class="fixed inset-0 flex items-center justify-center bg-white z-50">
        <div class="text-4xl font-bold flex space-x-1 text-darkblue">
            <span class="dot animate-pulse delay-[0ms]">.</span>
            <span class="dot animate-pulse delay-[200ms]">.</span>
            <span class="dot animate-pulse delay-[400ms]">.</span>
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
    <x-navbar></x-navbar>

    {{-- Main Content --}}
    <div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <a href="{{ route('product') }}" class="text-sm text-gray-500 hover:underline mb-6 inline-flex items-center">
            <img src="{{ asset('images/icons/back.png') }}" alt="Back" class="w-4 h-4 mr-2"> Continue shopping
        </a>

        <h2 class="text-2xl sm:text-3xl font-bold text-darkblue mb-6">Checkout</h2>

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 mb-4 rounded-md border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf
            <div class="bg-white shadow-md rounded-lg p-4 sm:p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-separate border-spacing-y-3">
                        <thead>
                            <tr class="text-gray-500 uppercase text-xs tracking-wider border-b">
                                <th class="py-2">Produk</th>
                                <th class="py-2">Harga</th>
                                <th class="py-2">Jumlah</th>
                                <th class="py-2">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cartItems as $item)
                                <tr class="bg-[#F8F9F9] rounded-lg text-darkblue">
                                    <td class="py-3 px-2 rounded-l-lg">
                                        <span
                                            class="font-semibold">{{ $item->product->product_name ?? 'Produk tidak ditemukan' }}</span>
                                    </td>
                                    <td class="py-3 px-2">Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-2">{{ $item->quantity }}</td>
                                    <td class="py-3 px-2 rounded-r-lg font-semibold text-red-600">
                                        Rp {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center mt-6 border-t pt-4">
                    <p class="text-lg text-gray-700 font-medium">Total</p>
                    <p class="text-xl font-bold text-darkblue">Rp {{ number_format($total, 0, ',', '.') }}</p>
                </div>

                <h2 class="text-lg font-bold mb-4 text-gray-800">Metode Pembayaran</h2>
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($channels as $index => $channel)
                        @if ($channel['active'])
                            <label
                                class="flex items-center p-4 border rounded-2xl cursor-pointer hover:border-blue-500 hover:shadow-md transition-all duration-200 bg-white space-x-3">

                                <input type="radio" name="method" value="{{ $channel['code'] }}" class="hidden peer"
                                    @if ($index === 0) required @endif> {{-- required cuma di pertama --}}

                                <!-- Icon -->
                                <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-gray-50 border">
                                    <img src="{{ $channel['icon_url'] }}" alt="{{ $channel['name'] }}"
                                        class="w-8 h-8 object-contain">
                                </div>

                                <!-- Text Info -->
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">{{ $channel['name'] }}</p>
                                </div>

                                <!-- Check indicator -->
                                <div
                                    class="w-5 h-5 rounded-full border border-gray-400 peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none"
                                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </label>
                        @endif
                    @endforeach
                </div>


                <div class="mt-6 text-center">
                    <button id="pay-button" type="submit"
                        class="w-full sm:w-auto bg-darkblue text-white px-8 py-3 rounded-md text-sm sm:text-base font-semibold hover:bg-blue-900 transition">
                        Bayar Sekarang
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="mt-16">
        <x-footer></x-footer>
    </div>
</body>

</html>
