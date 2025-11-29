<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="{{ asset('images/icons/Favicon.png') }}" sizes="64x64" type="image/png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Structura - {{ $product->product_name }}</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-white font-Montserrat">

    <x-navbar />

    {{-- Toast Message --}}
    @if (session('message'))
        <x-toast :message="session('message')" :status="session('status', 'info')" />
    @endif

    <div class="container mx-auto w-full flex flex-col mt-8">
        {{-- breadcrumb --}}
        <div class="max-w-[1280px] w-full mx-auto px-4 flex flex-col gap-2 md:flex-row md:justify-between md:items-center mb-4">
            <nav class="text-sm text-darkblue flex flex-wrap items-center gap-x-1">
                <a href="{{ route('landingpage') }}" class="hover:underline">HOME</a>
                <span>/</span>
                <a href="{{ route('product') }}" class="hover:underline">{{ $product->product_name }}</a>
            </nav>

            <a href="{{ route('product') }}" class="text-sm text-gray-600 inline-flex items-center hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M12.707 15.707a1 1 0 01-1.414 0L6.586 11H17a1 1 0 110-2H6.586l4.707-4.707a1 1 0 00-1.414-1.414l-6.414 6.414a1 1 0 000 1.414l6.414 6.414a1 1 0 001.414 0z"
                        clip-rule="evenodd" />
                </svg>
                <span>Kembali ke Produk</span>
            </a>
        </div>

        <!-- Produk Container -->
        <div class="w-full max-w-5xl mx-auto mt-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
                {{-- Gambar Produk --}}
                <div class="bg-white rounded-xl overflow-hidden shadow-lg w-full h-[400px] md:h-[500px]">
                    <img src="{{ asset('storage/' . $product->image) }}"
                         alt="{{ $product->product_name }}"
                         class="w-full h-full object-cover">
                </div>

                {{-- Detail Produk --}}
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $product->product_name }}</h1>
                    <p class="text-red-600 text-2xl md:text-3xl font-bold mt-2">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>

                    <div class="flex items-center gap-3 mt-4 mb-4">
                        <div class="w-8 h-8 flex items-center justify-center rounded-full bg-darkblue">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4
                                    1.79-4 4 1.79 4 4 4zm0 2c-2.67
                                    0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                        </div>
                        <p class="text-sm md:text-base text-gray-800">
                            <span class="font-semibold">Toko oleh:</span> {{ $product->user->name ?? 'Unknown' }}
                        </p>
                    </div>

                    <h3 class="text-lg font-semibold mt-4">Deskripsi</h3>
                    <p class="text-gray-700 mt-2 text-sm md:text-base leading-relaxed">
                        {{ $product->description ?? '-' }}
                    </p>

                    <div class="mt-6 grid grid-cols-2 gap-x-6 gap-y-2 text-sm md:text-base text-gray-800">
                        <p><span class="font-semibold">Kategori:</span> {{ $product->category->category_name ?? '-' }}</p>
                        <p><span class="font-semibold">SKU:</span> {{ $product->sku ?? '-' }}</p>
                        <p><span class="font-semibold">Stok:</span> {{ $product->stock }}</p>
                        <p><span class="font-semibold">Status:</span>
                            <span class="{{ $product->status === 'available' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $product->status }}
                            </span>
                        </p>
                        <p><span class="font-semibold">Berat:</span> {{ $product->weight ? $product->weight . ' kg' : '-' }}</p>
                        <p><span class="font-semibold">Produk Unggulan:</span> {{ $product->is_featured ? 'Ya' : 'Tidak' }}</p>
                    </div>

                    <form action="{{ route('cart.add') }}" method="POST" class="mt-6">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button
                            class="bg-darkblue hover:bg-blue-950 text-white font-semibold py-3 px-6 rounded-lg w-full">
                            Tambahkan ke Keranjang
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="mt-[100px]">
            <x-footer />
        </div>
    </div>
</body>
</html>
