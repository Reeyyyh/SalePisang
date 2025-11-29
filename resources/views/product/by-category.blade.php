<!-- versi responsive (mirip sebelumnya), konten tetap -->
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
    <link href="https://fonts.googleapis.com/css2?family=Changa:wght@200..800&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 font-Montserrat flex flex-col min-h-screen">

    {{-- Navbar --}}
    <x-navbar></x-navbar>

    {{-- Mobile Filter Button --}}
    <div class="lg:hidden flex items-center gap-2 px-4 py-3 bg-white shadow-md sticky top-0 z-40">
        <form action="{{ url('/category/' . $category->slug) }}" method="GET" class="flex w-full gap-2 items-center">
            <input type="text" name="query" placeholder="Search..." class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm" value="{{ request('query') }}">
            <button type="submit" class="p-2 bg-darkblue hover:bg-slate-800 rounded text-white flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5a6 6 0 014.472 10.056l4.386 4.387a1 1 0 11-1.414 1.414l-4.387-4.386A6 6 0 1111 5z" />
                </svg>
            </button>
        </form>
        <button id="mobile-filter-btn" class="bg-darkblue text-white px-3 py-2 rounded text-sm">
            Filter
        </button>
    </div>

    {{-- Mobile Sidebar --}}
    <div id="mobile-sidebar-modal" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-end items-end">
        <div class="w-full max-h-[90%] bg-[#f5f0ea] rounded-t-2xl p-6 overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Filter</h3>
                <button id="close-mobile-sidebar" class="text-xl font-bold">&times;</button>
            </div>

            <form method="GET" action="{{ url('/category/' . $category->slug) }}" class="text-sm text-[#1f2d5c] space-y-6">
                <h2 class="text-sm text-gray-500">
                    Showing <span class="font-bold text-[#1f2d5c]">{{ $products->count() }}</span> products in
                    <span class="font-bold">{{ $category->category_name }}</span>
                </h2>

                <div>
                    <h3 class="text-sm font-bold mb-2 mt-4">Price</h3>
                    <div class="flex items-center gap-2">
                        <span class="text-sm">Rp</span>
                        <input type="text" name="price_from" value="{{ request('price_from') }}" placeholder="From" class="w-[40%] p-1 border border-gray-300 rounded text-sm">
                        <input type="text" name="price_to" value="{{ request('price_to') }}" placeholder="To" class="w-[40%] p-1 border border-gray-300 rounded text-sm">
                    </div>
                    <button type="submit" class="mt-4 bg-darkblue text-white px-3 py-1 text-sm rounded hover:opacity-80">
                        Apply Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MAIN --}}
    <div class="mx-auto w-full px-4 lg:px-[200px] mt-6">
        <nav class="text-sm text-darkblue mb-4">
            <a href="{{ route('landingpage') }}" class="hover:underline">HOME</a> /
            <span class="font-semibold">{{ strtoupper($category->category_name) }}</span>
        </nav>

        <div class="flex flex-col lg:flex-row gap-6 mb-8">

            {{-- Sidebar Desktop --}}
            <aside class="hidden lg:block w-1/6 bg-[#f5f0ea] rounded-xl p-6 text-sm text-[#1f2d5c] space-y-6" id="sidebar">
                <h2 class="text-sm text-gray-500">
                    Showing <span class="font-bold text-[#1f2d5c]">{{ $products->count() }}</span> products in
                    <span class="font-bold">{{ $category->category_name }}</span>
                </h2>

                <form method="GET" action="{{ url('/category/' . $category->slug) }}">
                    <h3 class="text-sm font-bold mb-2 mt-4">Price</h3>
                    <div class="flex items-center gap-2">
                        <span class="text-sm">Rp</span>
                        <input type="text" name="price_from" value="{{ request('price_from') }}" placeholder="From" class="w-[40%] p-1 border border-gray-300 rounded text-sm">
                        <input type="text" name="price_to" value="{{ request('price_to') }}" placeholder="To" class="w-[40%] p-1 border border-gray-300 rounded text-sm">
                    </div>
                    <button type="submit" class="mt-4 bg-darkblue text-white px-3 py-1 text-sm rounded hover:opacity-80">
                        Apply Filter
                    </button>
                </form>
            </aside>

            {{-- Main --}}
            <main class="flex-1">
                <div class="flex justify-end mb-7">
                    <form method="GET" action="{{ url('/category/' . $category->slug) }}">
                        <input type="hidden" name="price_from" value="{{ request('price_from') }}">
                        <input type="hidden" name="price_to" value="{{ request('price_to') }}">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-[#1f2d5c] text-base">Sort By</span>
                            <select name="sort" onchange="this.form.submit()" class="p-2 w-[180px] h-[40px] rounded bg-[#f5f0ea] text-base text-black border-none">
                                <option value="">-- Default --</option>
                                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price, low to high</option>
                                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price, high to low</option>
                            </select>
                        </div>
                    </form>
                </div>

                @if ($products->count() > 0)
                    <div id="product-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
                        @foreach ($products as $product)
                            <a href="{{ url('/detail-product/' . $product->sku) }}" class="bg-white rounded-lg shadow-md overflow-hidden w-full h-[300px]">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" class="w-full h-40 object-cover">
                                <div class="p-4 pl-2 space-y-2">
                                    <h3 class="text-sm font-semibold">{{ $product->product_name }}</h3>
                                    <p class="text-darkblue font-semibold">Rp. {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <p class="text-gray-500 text-xs">Stok: {{ $product->stock }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="w-full col-span-5">
                        <div class="flex flex-col items-center justify-center text-center py-12 min-h-[500px] bg-white rounded-xl shadow-md">
                            <img src="{{ asset('images/icons/empty-box.png') }}" alt="Empty" class="w-32 h-32 opacity-60 mb-8">
                            <h3 class="text-2xl font-semibold text-gray-700">Produk Belum Tersedia</h3>
                            <p class="text-base text-gray-500 mt-2 max-w-md">Kategori ini belum memiliki produk yang tersedia saat ini. Coba lihat kategori lainnya atau kembali ke halaman semua produk.</p>
                            <a href="{{ route('product') }}" class="inline-block mt-6 px-6 py-3 bg-darkblue text-white text-base rounded hover:opacity-90 transition duration-200">
                                Lihat Semua Produk
                            </a>
                        </div>
                    </div>
                @endif
            </main>
        </div>
    </div>

    <div class="mt-auto">
        <x-footer />
    </div>

    {{-- JS for Mobile Modal --}}
    <script>
        const mobileFilterBtn = document.getElementById('mobile-filter-btn');
        const mobileSidebarModal = document.getElementById('mobile-sidebar-modal');
        const closeMobileSidebar = document.getElementById('close-mobile-sidebar');

        mobileFilterBtn.addEventListener('click', () => {
            mobileSidebarModal.classList.remove('hidden');
            mobileSidebarModal.classList.add('flex');
        });

        closeMobileSidebar.addEventListener('click', () => {
            mobileSidebarModal.classList.add('hidden');
            mobileSidebarModal.classList.remove('flex');
        });
    </script>
</body>

</html>
