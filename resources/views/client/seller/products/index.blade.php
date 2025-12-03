@extends('client.seller.layouts')

@section('content')
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Products</h1>
            <a href="{{ route('seller.products.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">
                Add Product
            </a>
        </div>

        <div class="overflow-x-auto -mx-6">
            <table class="w-full bg-white rounded-lg shadow-md overflow-hidden">
                <thead class="bg-gray-100 border-b-2 border-gray-200">
                    <tr>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">#</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Image</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Name</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">SKU</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Price</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Stock</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Category</th>
                        <th class="py-3 px-6 text-left text-gray-700 font-medium">Status</th>
                        <th class="py-3 px-6 text-center text-gray-700 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($products as $index => $product)
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="py-3 px-6">{{ $index + 1 }}</td>
                            <td class="py-3 px-6">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                        class="w-12 h-12 object-cover rounded" alt="Product Image">
                                @else
                                    <span class="text-gray-400">No Image</span>
                                @endif
                            </td>
                            <td class="py-3 px-6">{{ $product->product_name }}</td>
                            <td class="py-3 px-6">{{ $product->sku }}</td>
                            <td class="py-3 px-6">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="py-3 px-6">
                                @if ($product->stock > 0)
                                    <span
                                        class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">{{ $product->stock }}</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-sm">Out of stock</span>
                                @endif
                            </td>
                            <td class="py-3 px-6">{{ $product->category?->category_name }}</td>
                            <td class="py-3 px-6">
                                @if ($product->status === 'available')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">Available</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">Out of
                                        Stock</span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-center space-x-2">

                                <a href="{{ route('seller.products.edit', ['id' => $product->id]) }}"
                                    class="inline-flex items-center gap-1 px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-10 0l7-7 3 3-7 7H7v-3z" />
                                    </svg>
                                    Edit
                                </a>

                                <form action="{{ route('seller.products.destroy', $product->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-6 text-center text-gray-400 font-medium">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
@endsection
