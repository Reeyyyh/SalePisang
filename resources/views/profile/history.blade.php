{{-- Load AlpineJS --}}
<script src="https://unpkg.com/alpinejs" defer></script>
<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<x-layouts.sidebar-profile :title="'ORDER HISTORY'">
    @if ($orders->count())
        <section class="space-y-6 mb-12">
            @foreach ($orders as $order)
                @php
                    $badgeColor = match ($order->orders_status) {
                        'pending' => 'bg-yellow-400 text-white',
                        'paid' => 'bg-blue-500 text-white',
                        'processing' => 'bg-indigo-500 text-white',
                        'shipped' => 'bg-purple-500 text-white',
                        'delivered' => 'bg-green-500 text-white',
                        'canceled' => 'bg-red-500 text-white',
                        'expired' => 'bg-gray-500 text-white',
                        default => 'bg-gray-300 text-darkblue',
                    };
                @endphp

                <div x-data="{ isOpen: false }" x-cloak>
                    {{-- Order Card --}}
                    <div
                        class="bg-white p-5 sm:p-6 rounded-xl shadow-md border border-gray-200 transition hover:shadow-lg">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                            <h3 class="text-base sm:text-lg font-semibold text-darkblue">
                                Order {{ $order->invoice_number }}
                            </h3>
                            <span class="px-4 py-1 text-xs sm:text-sm rounded-md font-semibold {{ $badgeColor }}">
                                {{ ucfirst($order->orders_status) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-6 text-sm sm:text-base text-gray-700">
                            <p><span
                                    class="font-bold text-darkblue">Tanggal:</span><br>{{ $order->created_at->format('d M Y') }}
                            </p>
                            <p><span class="font-bold text-darkblue">Total:</span><br>
                                <span
                                    class="text-red-500 font-bold">Rp{{ number_format($order->total_amount ?? $order->total, 0, ',', '.') }}</span>
                            </p>
                            <p class="sm:col-span-2">
                                <span class="font-bold text-darkblue">Batas Pembayaran:</span><br>
                                {{ \Carbon\Carbon::parse($order->payment_expired_at)->format('d M Y - H:i') }} WIB
                            </p>
                            <div class="sm:col-span-2">
                                <p class="font-bold text-darkblue mb-1">Alamat Pengiriman:</p>
                                <pre
                                    class="whitespace-pre-wrap text-xs text-gray-600 bg-gray-100 border border-gray-200 p-3 rounded-md leading-snug max-h-32 overflow-auto">{{ $order->shipping_address }}</pre>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-col sm:flex-row justify-end gap-3 font-medium">
                            @if ($order->orders_status === 'pending' && now()->lt($order->payment_expired_at))
                                <a href="{{ route('order.cancel', ['uuid' => $order->uuid]) }}"
                                    onclick="return confirm('Apakah kamu yakin ingin membatalkan pesanan ini?')"
                                    class="inline-block px-4 py-2 bg-red-500 text-white text-sm rounded-md hover:bg-red-600 transition">
                                    Batalkan Pembayaran
                                </a>
                                <a href="{{ $order->payment_url }}" target="_blank"
                                    class="inline-block px-4 py-2 bg-yellow-500 text-white text-sm rounded-md hover:bg-yellow-600 transition">
                                    Bayar Sekarang
                                </a>
                            @endif

                            <button @click="isOpen = true"
                                class="inline-block px-4 py-2 bg-darkblue text-white text-sm rounded-md hover:bg-[#15202b] transition">
                                Lihat Detail
                            </button>
                        </div>
                    </div>

                    {{-- Modal --}}
                    <div x-show="isOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
                        {{-- Overlay --}}
                        <div x-show="isOpen" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0" class="absolute inset-0 bg-gray-800/50"
                            @click="isOpen = false">
                        </div>

                        {{-- Modal Box --}}
                        <div x-show="isOpen" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            @keydown.escape.window="isOpen = false" @click.away="isOpen = false"
                            class="relative z-10 w-full max-w-2xl bg-white rounded-xl shadow-lg p-6 space-y-6 overflow-y-auto max-h-[90vh]">

                            {{-- Modal Header --}}
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                                <h2 class="text-lg sm:text-xl font-bold text-darkblue">Order
                                    {{ $order->invoice_number }}</h2>
                                <span class="px-4 py-1 text-sm rounded-md font-semibold {{ $badgeColor }}">
                                    {{ ucfirst($order->orders_status) }}
                                </span>
                            </div>

                            {{-- Info --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
                                <div>
                                    <p><span class="font-medium text-darkblue">Tanggal Pemesanan:</span><br>
                                        {{ $order->created_at->format('d M Y') }}</p>
                                </div>
                                <div>
                                    <p><span class="font-medium text-darkblue">Total Pembayaran:</span><br>
                                        Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                </div>
                                <div class="sm:col-span-2">
                                    <p class="font-medium text-darkblue mb-1">Alamat Pengiriman:</p>
                                    <pre
                                        class="whitespace-pre-wrap break-words text-sm text-gray-600 leading-tight bg-gray-100 p-3 rounded-md border border-gray-200 max-h-32 overflow-hidden no-scrollbar">
{{ $order->shipping_address ?? 'Alamat tidak tersedia' }}
                                    </pre>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Produk --}}
                            <div>
                                <h3 class="font-semibold text-darkblue mb-3">Produk yang Dipesan</h3>
                                <ul class="divide-y divide-gray-200">
                                    @foreach ($order->orderDetails as $detail)
                                        <li
                                            class="py-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                                            <div>
                                                <p class="font-medium text-darkblue">
                                                    {{ $detail->product->product_name }}</p>
                                                <p class="text-sm text-gray-500">Jumlah: {{ $detail->quantity }}</p>
                                            </div>
                                            <p class="text-red-600 font-semibold text-sm sm:text-base">
                                                Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                <span class="font-semibold">Toko :</span>
                                                {{ $detail->product->user->name ?? 'Tidak diketahui' }}
                                            </p>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            {{-- Close --}}
                            <div class="text-right pt-2">
                                <button @click="isOpen = false"
                                    class="px-4 py-2 bg-gray-200 text-darkblue text-sm rounded-md hover:bg-gray-300 transition">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </section>
    @else
        <section
            class="mb-12 min-h-[400px] rounded-xl shadow flex flex-col items-center justify-center text-center bg-[#F8F9F9] p-6">
            <img src="{{ asset('images/icons/boxempty.png') }}" alt="No Order Icon"
                class="w-14 h-14 sm:w-16 sm:h-16 mb-4">
            <h3 class="text-xl sm:text-2xl font-bold text-darkblue">No Order History</h3>
            <p class="text-sm sm:text-base text-darkblue mb-6">Kamu belum mempunyai pesanan apapun.</p>
            <a href="{{ route('product') }}">
                <button
                    class="px-6 py-2 bg-darkblue text-white font-medium rounded-md text-sm sm:text-base hover:bg-[#15202b] transition">
                    Belanja Sekarang
                </button>
            </a>
        </section>
    @endif
</x-layouts.sidebar-profile>
