@extends('client.seller.layouts')

@section('content')
    <div class="p-6">
        <h1 class="text-3xl font-bold mb-6">Orders</h1>

        <div class="overflow-x-auto">
            <table class="w-full bg-white rounded-lg shadow-md">
                <thead class="bg-gray-100 border-b-2 border-gray-200">
                    <tr>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Invoice</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Customer</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Total</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Status</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="py-3 px-6">{{ $order->invoice_number }}</td>
                            <td class="py-3 px-6">{{ $order->user->name ?? 'Guest' }}</td>
                            <td class="py-3 px-6">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="py-3 px-6">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'paid' => 'bg-green-100 text-green-800',
                                        'shipped' => 'bg-blue-100 text-blue-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                    $statusClass = $statusColors[$order->orders_status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusClass }}">
                                    {{ ucfirst($order->orders_status) }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-center space-x-2">
                                <a href="{{ route('seller.orders.show', $order->id) }}"
                                    class="inline-flex items-center gap-1 px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    View
                                </a>

                                <form action="{{ route('seller.orders.updateStatus', $order->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Update Status
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-400 font-medium">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
