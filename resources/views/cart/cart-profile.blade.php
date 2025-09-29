<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="{{ asset('images/icons/Favicon.png') }}" sizes="64x64" type="image/png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Structura</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Changa:wght@200..800&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

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

    <script>
        window.addEventListener('beforeunload', () => {
            document.getElementById('page-loader').classList.remove('hidden');
        });
        window.addEventListener('load', () => {
            document.getElementById('page-loader').classList.add('hidden');
        });
    </script>

    <x-navbar></x-navbar>

    {{-- Notifikasi --}}
    @if (session('message'))
        @php $status = session('status', 'info'); @endphp
        <x-toast :message="session('message')" :status="$status" />
    @endif

    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 md:px-8">
        <a href="{{ route('product') }}" class="text-sm text-gray-600 mb-4 inline-block">
            <img src="{{ asset('images/icons/back.png') }}" alt="Back" class="w-3 h-3 inline-block mr-2">
            Continue shopping
        </a>

        @if ($cartItems->isEmpty())
            <div class="bg-[#F8F9F9] p-6 rounded-lg shadow h-[400px] text-center flex items-center justify-center">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold mb-4 text-darkblue">KERANJANG ANDA KOSONG.</h2>
                    <p class="text-darkblue text-sm md:text-base">
                        Sepertinya Anda belum menambahkan apa pun ke keranjang Anda.<br />
                        Silakan dan jelajahi produk kami.
                    </p>
                </div>
            </div>
        @else
            <h2 class="text-xl md:text-2xl font-bold mb-6">Cart</h2>
            <div class="bg-[#F8F9F9] p-4 rounded-lg shadow-md">
                <div class="text-xs md:text-sm text-gray-500 mb-4 border-b pb-4">
                    PRODUCT
                </div>

                <div class="space-y-4 mb-6">
                    @foreach ($cartItems as $item)
                        <div class="flex flex-row items-center justify-between border-b pb-4 gap-2">
                            <div class="flex items-center gap-3 sm:gap-4 w-[55%] sm:w-[60%]">
                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="Product"
                                    class="w-16 h-16 sm:w-20 sm:h-20 rounded-md object-cover">
                                <div class="text-sm sm:text-base">
                                    <p class="font-semibold leading-tight">{{ $item->product->product_name }}</p>
                                    <p class="text-xs sm:text-sm text-darkblue">stok : {{ $item->product->stock }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 w-[45%] sm:w-[40%] justify-end">
                                <div class="flex flex-col items-center gap-2">

                                    <form action="{{ route('cart.update', $item->id) }}" method="POST"
                                        class="flex items-center gap-2">
                                        @csrf

                                        {{-- Tombol - --}}
                                        <button type="submit" name="action" value="decrease"
                                            class="w-6 h-6 text-sm font-bold rounded-md bg-gray-200 text-darkblue hover:bg-gray-300">
                                            -
                                        </button>

                                        {{-- Jumlah --}}
                                        <span
                                            class="px-2 text-sm font-semibold text-darkblue">{{ $item->quantity }}</span>

                                        {{-- Tombol + --}}
                                        <button type="submit" name="action" value="increase"
                                            class="w-6 h-6 text-sm font-bold rounded-md bg-gray-200 text-darkblue hover:bg-gray-300">
                                            +
                                        </button>
                                    </form>

                                    <form action="{{ route('cart.delete', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="text-[10px] text-darkblue hover:underline font-bold flex items-center gap-1">
                                            <img src="{{ asset('images/icons/Trash.png') }}" alt="Delete"
                                                class="w-3 h-3">
                                            Remove
                                        </button>
                                    </form>
                                </div>

                                <p class="text-red-500 font-bold text-sm text-right min-w-[80px]">
                                    Rp. {{ number_format($item->product->price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div
                class="mt-8 flex flex-col sm:flex-row justify-between items-start sm:items-center p-6 rounded-lg bg-[#F8F9F9] shadow-md gap-4">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-6">
                    <p class="text-gray-600 font-medium text-sm">Estimated total</p>
                    <p class="text-red-600 font-extrabold text-lg">Rp. {{ number_format($total, 0, ',', '.') }}</p>
                </div>
                <a href="{{ route('checkout.form') }}"
                    class="bg-darkblue text-white font-extrabold px-6 py-2 rounded-[10px] hover:bg-darkblue transition text-sm text-center w-full sm:w-auto">
                    CHECKOUT
                </a>
            </div>
        @endif
    </div>

    <div class="mt-16">
        <x-footer></x-footer>
    </div>
</body>

</html>
