@extends('client.seller.layouts')

@section('content')
<h1 class="text-3xl font-bold mb-6">Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Produk -->
    <div class="bg-gradient-to-r from-blue-400 to-blue-600 text-white p-6 rounded-lg shadow-lg flex items-center justify-between">
        <div>
            <h2 class="font-semibold text-lg">Total Produk</h2>
            <p class="text-3xl font-bold mt-2">{{ $totalProducts }}</p>
        </div>
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0H4m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6" />
            </svg>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="bg-gradient-to-r from-green-400 to-green-600 text-white p-6 rounded-lg shadow-lg flex items-center justify-between">
        <div>
            <h2 class="font-semibold text-lg">Total Orders</h2>
            <p class="text-3xl font-bold mt-2">{{ $totalOrders }}</p>
        </div>
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
            </svg>
        </div>
    </div>

    <!-- Total Pendapatan -->
    <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 text-white p-6 rounded-lg shadow-lg flex items-center justify-between">
        <div>
            <h2 class="font-semibold text-lg">Total Pendapatan</h2>
            <p class="text-3xl font-bold mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.333 0-2 1-2 2s.667 2 2 2 2 1 2 2-.667 2-2 2m0-8v4m0 4v4" />
            </svg>
        </div>
    </div>
</div>
@endsection
