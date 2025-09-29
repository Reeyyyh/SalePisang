@props(['categories'])

<div class="py-4 px-4 mt-8 max-w-[1280px] mx-auto w-full rounded-[10px] lg:bg-bananaCream">

    <!-- MOBILE & TABLET: Horizontal Scroll -->
    <div class="flex lg:hidden gap-3 pb-4 overflow-x-auto pl-4 scrollbar-hide">
        @foreach ($categories as $category)
            <a href="{{ route('byCategory', $category->slug) }}"
                class="flex-shrink-0 w-[80px] flex flex-col items-center text-center space-y-2">

                <!-- Icon Container (Besar & Bulat) -->
                <div
                    class="w-16 h-16 rounded-full bg-bananaYellow border border-bananaGreen shadow-sm flex items-center justify-center overflow-hidden hover:ring-2 hover:ring-bananaGreen transition">
                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->category_name }}"
                        class="w-full h-full object-cover" />
                </div>

                <!-- Text -->
                <h3 class="text-[11px] font-bold text-bananaBrown leading-tight line-clamp-2">
                    {{ $category->category_name }}
                </h3>
            </a>
        @endforeach
    </div>


    <!-- LAPTOP & PC: Grid Max 10 Items -->
    <div class="hidden lg:grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mt-4">
        @foreach ($categories->take(10) as $category)
            <a href="{{ route('byCategory', $category->slug) }}"
                class="bg-yellow-500 hover:bg-bananaGreen hover:text-white text-white rounded-[20px] flex items-center h-20 w-[240px] shadow-md transition">
                <div class="h-full w-[80px] flex-shrink-0">
                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->category_name }}"
                        class="h-full w-full object-cover rounded-tl-[20px] rounded-bl-[20px]">
                </div>
                <div class="pl-3">
                    <h3 class="font-semibold text-[14px] w-1/2 uppercase tracking-wide">{{ $category->category_name }}
                    </h3>
                    <div class="leading-none mt-1">
                        <p class="text-[10px] line-clamp-2 text-bananaBrown">
                            {{ $category->short_description }}</p>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
