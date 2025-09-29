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
    <script src="//unpkg.com/alpinejs" defer></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Changa:wght@200..800&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 font-Montserrat">

     <!--cutomerchat-->
    {{-- <script src="//code.tidio.co/hqsiluutklrxvrzkhjvle2sh7trgrvs1.js" async></script> --}}

    <!-- Loader -->
    <div id="page-loader" class="fixed inset-0 flex items-center justify-center bg-white z-50">
        <div class="text-4xl font-bold flex space-x-1 text-darkblue">
            <span class="dot animate-pulse delay-[0ms]">.</span>
            <span class="dot animate-pulse delay-[200ms]">.</span>
            <span class="dot animate-pulse delay-[400ms]">.</span>
        </div>
    </div>

    <script>
        const loader = document.getElementById('page-loader');
        window.addEventListener('beforeunload', () => {
            loader.classList.remove('hidden');
        });
        window.addEventListener('load', () => {
            setTimeout(() => loader.classList.add('hidden'), 300);
        });
        setTimeout(() => {
            loader.classList.add('hidden');
        }, 500);
    </script>

    <x-navbar></x-navbar>

    <!-- Mobile Filter + Search -->
    <div class="lg:hidden flex items-center gap-2 px-4 py-3 bg-white  sticky top-0 z-40">
        <form action="{{ route('search') }}" method="GET" class="flex w-full gap-2 items-center">
            <input type="text" name="query" placeholder="Search..."
                class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm" value="{{ request('query') }}">
            <button type="submit"
                class="p-2 bg-darkblue hover:bg-slate-800 rounded text-white flex items-center justify-center">
                <!-- SVG ikon search -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M11 5a6 6 0 014.472 10.056l4.386 4.387a1 1 0 11-1.414 1.414l-4.387-4.386A6 6 0 1111 5z" />
                </svg>
            </button>
        </form>
        <button id="mobile-filter-btn" class="bg-darkblue text-white px-3 py-2 rounded text-sm">
            Filter
        </button>
    </div>

    <!-- Modal Sidebar for Mobile -->
    <form method="GET" action="{{ route('product') }}" id="mobile-sidebar-modal"
        class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-end items-end"
        x-data="{ showAll: false }">

        <div class="w-full max-h-[90%] bg-[#f5f0ea] rounded-t-2xl p-6 overflow-y-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Filter</h3>
                <button id="close-mobile-sidebar" type="button" class="text-xl font-bold">&times;</button>
            </div>

            <!-- Content -->
            <div class="text-sm text-[#1f2d5c] space-y-6">
                <h2 class="text-sm text-gray-500">
                    Showing <span class="font-bold text-[#1f2d5c]">{{ $productCount }}</span> Products
                </h2>

                <!-- Filter Price -->
                <div>
                    <h3 class="text-sm font-bold mb-2">Price</h3>
                    <div class="flex items-center gap-2">
                        <span class="text-sm">Rp</span>
                        <input type="text" name="price_from" value="{{ request('price_from') }}" placeholder="From"
                            class="w-[30%] p-1 border border-gray-300 rounded text-sm focus:outline-none">
                        <input type="text" name="price_to" value="{{ request('price_to') }}" placeholder="To"
                            class="w-[30%] p-1 border border-gray-300 rounded text-sm focus:outline-none">
                    </div>
                </div>

                <!-- Filter Sub Category -->
<div x-data="{ showAll: false }">
    <h3 class="text-sm font-bold mb-2">Sub Category</h3>
    <ul class="space-y-3 text-sm">
        @foreach ($categories as $index => $category)
            <li class="flex items-start gap-2" x-show="showAll || {{ $index < 10 ? 'true' : 'false' }}">
                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                    {{ is_array(request('categories')) && in_array($category->id, request('categories')) ? 'checked' : '' }}
                    class="mt-[2px] accent-darkblue focus:outline-none">
                <label class="uppercase">{{ $category->category_name }}</label>
            </li>
        @endforeach
    </ul>

    @if ($categories->count() > 10)
        <div class="mt-2">
            <button type="button" @click="showAll = !showAll"
                class="text-darkblue text-xs sm:text-sm font-semibold hover:underline focus:outline-none">
                <span x-show="!showAll">Lihat Semua</span>
                <span x-show="showAll">Sembunyikan</span>
            </button>
        </div>
    @endif
</div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="p-2 bg-darkblue hover:bg-slate-800 rounded text-white flex items-center justify-center w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 5a6 6 0 014.472 10.056l4.386 4.387a1 1 0 11-1.414 1.414l-4.387-4.386A6 6 0 1111 5z" />
                        </svg>
                        <span class="ml-2">Terapkan Filter</span>
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal Sidebar for laptop -->
    <div class="mx-auto max-w-full px-4 lg:px-[200px]">
        <!-- Breadcrumb -->
        <nav class="text-sm text-darkblue mb-4 lg:mt-12 mt-6">
            <a href="#" class="hover:underline">HOME</a> /
            <a href="#" class="hover:underline">ALL PRODUCT</a>
        </nav>

        <form method="GET" action="{{ route('product') }}" id="filter-form">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Desktop Sidebar -->
                <aside
                    class="hidden lg:block w-1/6 lg:w-1/4 2xl:w-[18%] bg-[#f5f0ea] rounded-xl p-6 text-sm text-[#1f2d5c] space-y-6 sticky top-24 h-fit overflow-y-auto max-h-[100vh] "
                    id="sidebar">

                    <h2 class="text-sm text-gray-500">
                        Showing <span class="font-bold text-[#1f2d5c]">{{ $productCount }}</span> Products
                    </h2>

                    <div>
                        <h3 class="text-sm font-bold mb-2">Price</h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm">Rp</span>
                            <input type="text" name="price_from" value="{{ request('price_from') }}"
                                placeholder="From"
                                class="w-[40%] p-1 border border-gray-300 rounded text-sm focus:outline-none">
                            <input type="text" name="price_to" value="{{ request('price_to') }}"
                                placeholder="To"
                                class="w-[40%] p-1 border border-gray-300 rounded text-sm focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-bold mb-2">Sub Category</h3>
                        <ul class="space-y-3 text-sm" id="category-list">
                            @foreach ($categories as $index => $category)
                                <li class="flex items-start gap-2 {{ $index >= 10 ? 'hidden extra-category' : '' }}">
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                        {{ is_array(request('categories')) && in_array($category->id, request('categories')) ? 'checked' : '' }}
                                        class="mt-[2px] accent-darkblue focus:outline-none">
                                    <label class="uppercase">{{ $category->category_name }}</label>
                                </li>
                            @endforeach
                        </ul>


                        @if ($categories->count() > 10)
                            <button type="button" class="text-darkblue mt-2 text-xs font-medium"
                                id="show-toggle-btn">
                                + Show more
                            </button>
                        @endif
                    </div>
                </aside>

                <!-- Main Content -->
                <main class="flex-1">
                    <div class="flex justify-end mb-7">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-[#1f2d5c] text-base">Sort By</span>
                            <select id="sort-price" name="sort"
                                class="p-2 w-[180px] h-[40px] rounded bg-[#f5f0ea] text-base text-black focus:ring-0 focus:outline-none border-none">
                                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>
                                    Price, low to high</option>
                                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
                                    Price, high to low</option>
                            </select>
                        </div>
                    </div>

                    <div id="product-grid"
                        class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 2xl:grid-cols-5 gap-6">
                        @foreach ($products as $product)
                            <a href="{{ url('/detail-product/' . $product->sku) }}"
                                class="bg-white rounded-lg w-full h-[300px] shadow-md overflow-hidden">
                                <img src="{{ asset('storage/' . $product->image) }}"
                                    alt="{{ $product->product_name }}" class="w-full h-40 object-cover">
                                <div class="p-4 pl-2 space-y-2">
                                    <h3 class="text-sm font-semibold">{{ $product->product_name }}</h3>
                                    <p class="text-darkblue font-semibold">Rp.
                                        {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <p class="text-gray-500 text-xs">Stok: {{ $product->stock }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </main>
            </div>
        </form>

        <div class="flex justify-center mt-[80px]">
            {{ $products->links() }}
        </div>

        <div class="xl:mt-20 lg:mt-20 2xl:mt-20 sm:mt-2 md:mt-2 ">
            <x-footer></x-footer>
        </div>
    </div>

    @vite('resources/js/product.js')

    <script>
        // Mobile modal toggle
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showMoreBtn = document.getElementById('show-more-btn');
            const extraCategories = document.querySelectorAll('.extra-category');

            showMoreBtn?.addEventListener('click', () => {
                extraCategories.forEach(el => el.classList.remove('hidden'));
                showMoreBtn.remove(); // sembunyikan tombol setelah diklik
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('show-toggle-btn');
            const extraCategories = document.querySelectorAll('.extra-category');
            let expanded = false;

            toggleBtn?.addEventListener('click', () => {
                extraCategories.forEach(el => el.classList.toggle('hidden'));
                expanded = !expanded;
                toggleBtn.textContent = expanded ? '- Show less' : '+ Show more';
            });
        });
    </script>


</body>

</html>
