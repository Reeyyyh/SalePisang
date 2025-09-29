<!-- Alpine.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<!-- Top Bar -->
<div class="bg-blue-900 text-white text-[10px] sm:text-xs md:text-sm py-2 px-3 sm:px-4 md:px-8 flex justify-between items-center w-full font-Montserrat">
    <div class="flex items-center">
        <img src="{{ asset('images/icons/Call.png') }}" alt="phone-icon" class="mr-1 w-4 h-4 sm:w-5 sm:h-5">
        <p class="text-[10px] sm:text-xs md:text-sm">+62 123-4567-8910</p>
    </div>
    <span class="text-[10px] sm:text-xs text-center md:text-left">
        Shop Something Special Today!
    </span>
    <div class="hidden md:flex gap-3">
        <a href="#" class="hover:opacity-75">
            <img src="{{ asset('images/icons/Instagram.png') }}" alt="Instagram Icon" class="w-5 h-5">
        </a>
        <a href="#" class="hover:opacity-75">
            <img src="{{ asset('images/icons/Youtube.png') }}" alt="Youtube Icon" class="w-5 h-5">
        </a>
        <a href="#" class="hover:opacity-75">
            <img src="{{ asset('images/icons/fb.png') }}" alt="Facebook Icon" class="w-5 h-5">
        </a>
    </div>
</div>

<!-- Main Navbar -->
<div class="sticky top-0 z-50 font-Montserrat bg-white shadow-sm">
    <nav class="px-4 py-3 md:px-6 md:py-4 max-w-7xl mx-auto flex items-center justify-between">
        <!-- Logo -->
        <a href="{{ route('landingpage') }}" class="text-[24px] md:text-[32px] font-extrabold font-Changa text-yellow-600">
            SalePisang.
        </a>

        <!-- Desktop Menu -->
        <div class="hidden md:flex space-x-8 text-darkblue font-medium">
            <a href="{{ route('landingpage') }}" class="hover:text-gray-600">Beranda</a>
            <a href="{{ route('product') }}" class="hover:text-gray-600">Produk</a>
            <a href="{{ route('store-location') }}" class="hover:text-gray-600">Lokasi Toko</a>
        </div>

        <!-- Search + Icons -->
        <div class="flex items-center gap-4">
            <!-- Search (Desktop only) -->
            <form action="{{ route('search') }}" method="GET" class="hidden md:block relative">
                <img src="{{ asset('images/icons/Search.png') }}" alt="Search Icon"
                    class="absolute left-2 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-500">
                <input type="text" name="query" placeholder="Search..." value="{{ request('query') }}"
                    class="w-35 h-8 py-2 pl-10 pr-4 rounded-md border border-gray-300 focus:outline-none focus:ring-1 focus:ring-blue-600" />
            </form>

            <!-- Cart -->
            <a href="{{ Auth::check() ? route('cart') : route('login') }}">
                <img src="{{ asset('images/icons/Vector.png') }}" alt="Cart Icon" class="w-5 h-5" />
            </a>

            <!-- Profile -->
            <a href="{{ Auth::check() ? route('profile') : route('login') }}" class="hidden md:block">
                <img src="{{ asset('images/icons/User-Profile.png') }}" alt="User Icon" class="w-6 h-6" />
            </a>
        </div>
    </nav>
</div>

<!-- Bottom Navbar for Mobile/Tablet -->
<div class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-300 md:hidden flex justify-around items-center py-2 text-xs sm:text-sm font-medium text-darkblue">

    <!-- Beranda -->
    <a href="{{ route('landingpage') }}" class="flex flex-col items-center hover:text-gray-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-1" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                d="M3 12l9-9 9 9M4 10v10h6v-6h4v6h6V10" />
        </svg>
        Beranda
    </a>

    <!-- Produk -->
    <a href="{{ route('product') }}" class="flex flex-col items-center hover:text-gray-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-1" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                d="M5 8h14l1 9H4l1-9zm2 0V6a2 2 0 012-2h4a2 2 0 012 2v2" />
        </svg>
        Produk
    </a>

    <!-- Lokasi -->
    <a href="{{ route('store-location') }}" class="flex flex-col items-center hover:text-gray-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-1" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                d="M12 11c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm0 10s6-5.686 6-10a6 6 0 10-12 0c0 4.314 6 10 6 10z" />
        </svg>
        Lokasi
    </a>

    <!-- Profile -->
    <a href="{{ Auth::check() ? route('profile') : route('login') }}" class="flex flex-col items-center hover:text-gray-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-1" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                d="M5.121 17.804A9.953 9.953 0 0112 15c2.33 0 4.472.797 6.121 2.137M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        Profil
    </a>
</div>
