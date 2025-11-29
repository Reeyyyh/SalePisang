<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Changa:wght@200..800&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('images/icons/Favicon.png') }}" sizes="64x64" type="image/png">

    <title>Sale Pisang</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-white font-Montserrat">

    {{-- Customer Chat --}}
    {{-- <script src="//code.tidio.co/hqsiluutklrxvrzkhjvle2sh7trgrvs1.js" async></script> --}}

    {{-- Navbar --}}
    <x-navbar></x-navbar>

    <div
        class="mx-auto py-10 px-6 w-full max-w-[1280px] rounded-[15px] bg-gradient-to-r from-yellow-100 to-yellow-50 mt-6 shadow-lg hover:shadow-xl transition-shadow duration-300 grid grid-cols-1 md:grid-cols-2 gap-6 items-center">

        <!-- Gambar kiri -->
        <div class="hidden md:flex justify-center">
            <img src="{{ asset('images/icons/Favicon.png') }}" alt="Ilustrasi Pisang"
                class="w-full max-w-[300px] h-auto rounded-lg animate-fadeIn delay-200">
        </div>

        <!-- Teks + tombol kanan -->
        <div class="text-center md:text-left space-y-4">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-[#213555] animate-fadeIn">
                Mulai Belanja Pisang Segar!
            </h2>
            <p class="text-sm sm:text-base text-gray-700 max-w-lg animate-fadeIn delay-100">
                Temukan produk pisang segar langsung dari petani dan nikmati promo menarik.
            </p>

            <!-- Tombol vertikal -->
            <div class="flex flex-col space-y-3 mt-4 md:mt-6">
                @guest
                    <a href="{{ route('register') }}"
                        class="px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 transform hover:scale-105 transition-all duration-300">
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}"
                        class="px-6 py-3 border border-blue-500 text-blue-500 font-semibold rounded-lg hover:bg-blue-100 transform hover:scale-105 transition-all duration-300">
                        Login
                    </a>
                @endguest

                @auth
                    <a href="{{ route('product') }}"
                        class="px-6 py-3 bg-darkblue text-white font-semibold rounded-lg hover:bg-green-600 transform hover:scale-105 transition-all duration-300">
                        Belanja Sekarang
                    </a>
                @endauth
            </div>
        </div>
    </div>

    {{-- About Section --}}
    <div class="mx-auto text-center py-6 sm:py-8 lg:py-10 px-6 w-full max-w-[1280px] rounded-[10px] mt-6 bg-blue-50">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-[#213555]">SALE PISANG.</h1>
        <p class="text-sm sm:text-base text-gray-600 mt-4 max-w-2xl mx-auto">
            SALE PISANG adalah platform e-commerce penjualan pisang segar langsung dari petani ke rumah Anda.
            Kami menyediakan berbagai jenis pisang berkualitas tinggi, siap dikirim dengan cepat dan harga terjangkau.
        </p>
    </div>


    {{-- Category Section --}}
    <x-category :categories="$categories"></x-category>

    {{-- Featured Products --}}
    <div class="mx-auto text-center">
        <h1 class="mt-8 mb-8 font-extrabold text-darkblue text-xl">PRODUK PILIHAN</h1>
    </div>

    <div class="mx-auto mb-8 h-auto w-full max-w-[1280px] flex items-center justify-center px-4">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 w-full">
            @forelse ($featuredProducts as $product)
                <div
                    class="bg-white p-2 sm:p-3 md:p-4 text-left w-full max-w-[180px] sm:max-w-[200px] md:max-w-[220px] text-darkblue mx-auto shadow rounded">
                    <a href="{{ route('product.detail', $product->sku) }}" class="group block">
                        <div
                            class="w-full h-[160px] sm:h-[180px] md:h-[200px] overflow-hidden mx-auto rounded-sm bg-gray-100">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/200x200?text=No+Image' }}"
                                alt="{{ $product->product_name }}"
                                class="w-full h-full object-cover transition-transform duration-300 ease-in-out transform group-hover:scale-105">
                        </div>
                        <h2 class="mt-2 text-[12px] sm:text-[14px] md:text-[16px] font-semibold ml-1 sm:ml-2">
                            {{ $product->product_name }}
                        </h2>
                        <p class="font-extrabold ml-1 sm:ml-2 text-[12px] sm:text-[14px] md:text-[16px]">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    </a>
                </div>
            @empty
                <div
                    class="bg-white border border-dashed border-gray-300 p-6 flex flex-col items-center justify-center text-center w-full col-span-2 sm:col-span-3 md:col-span-4 rounded shadow">
                    <h2 class="text-gray-500 font-semibold text-sm sm:text-base">
                        Belum ada produk pilihan
                    </h2>
                    <p class="text-gray-400 text-xs sm:text-sm mt-1">
                        Produk pilihan akan muncul di sini setelah ditambahkan admin.
                    </p>
                </div>
            @endforelse
        </div>
    </div>


    {{-- Latest Products --}}
    <div class="mx-auto text-center">
        <h1 class="mt-8 mb-8 font-extrabold text-darkblue text-xl">TERBARU DI SALE PISANG</h1>
    </div>

    <div class="mx-auto mb-8 h-auto w-full max-w-[1280px] flex items-center justify-center px-4">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 w-full">
            @forelse ($latestProducts as $product)
                <div
                    class="bg-white p-2 sm:p-3 md:p-4 text-left w-full max-w-[180px] sm:max-w-[200px] md:max-w-[220px] text-darkblue mx-auto shadow rounded">
                    <a href="{{ route('product.detail', $product->sku) }}" class="group block">
                        <div
                            class="w-full h-[160px] sm:h-[180px] md:h-[200px] overflow-hidden mx-auto rounded-sm bg-gray-100">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/200x200?text=No+Image' }}"
                                alt="{{ $product->product_name }}"
                                class="w-full h-full object-cover transition-transform duration-300 ease-in-out transform group-hover:scale-105">
                        </div>
                        <h2 class="mt-2 text-[12px] sm:text-[14px] md:text-[16px] font-semibold ml-1 sm:ml-2">
                            {{ $product->product_name }}
                        </h2>
                        <p class="font-extrabold ml-1 sm:ml-2 text-[12px] sm:text-[14px] md:text-[16px]">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    </a>
                </div>
            @empty
                <div
                    class="bg-white border border-dashed border-gray-300 p-6 flex flex-col items-center justify-center text-center w-full col-span-2 sm:col-span-3 md:col-span-4 rounded shadow">
                    <h2 class="text-gray-500 font-semibold text-sm sm:text-base">
                        Belum ada produk terbaru
                    </h2>
                    <p class="text-gray-400 text-xs sm:text-sm mt-1">
                        Produk terbaru akan tampil di sini setelah seller menambahkan produk.
                    </p>
                </div>
            @endforelse
        </div>
    </div>


    {{-- Footer --}}
    <x-footer></x-footer>

</body>

</html>
