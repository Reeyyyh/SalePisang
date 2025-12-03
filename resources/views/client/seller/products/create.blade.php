@extends('client.seller.layouts')

@section('content')
    <div class="p-6 max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Add New Product</h1>

        <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" novalidate
            class="space-y-6 bg-white p-6 rounded-lg shadow-md">
            @csrf

            {{-- Image Preview --}}
            <div>
                <label class="block text-gray-700 font-medium mb-2">Product Image Preview</label>
                <div id="image-preview" class="mb-4">
                    {{-- Preview akan muncul di sini --}}
                </div>
            </div>

            {{-- Image Upload --}}
            <div>
                <label for="image" class="block text-gray-700 font-medium mb-2">Product Image</label>
                <input type="file" name="image" id="image"
                    class="image-preview-input w-full border border-gray-300 rounded px-4 py-2 focus:ring focus:ring-blue-200 @error('image') @enderror"
                    accept="image/*">
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Product Name --}}
            <div>
                <label for="product_name" class="block text-gray-700 font-medium mb-2">Product Name</label>
                <input type="text" name="product_name" id="product_name" value="{{ old('product_name') }}"
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:ring focus:ring-blue-200 @error('product_name') @enderror">
                @error('product_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- SKU --}}
            <div>
                <label for="sku" class="block text-gray-700 font-medium mb-2">SKU</label>
                <input type="text" name="sku" id="sku" value="{{ old('sku') }}"
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:ring focus:ring-blue-200" disabled>
                <p class="text-gray-500 text-sm mt-1">Will be auto-generated</p>
            </div>

            {{-- Price --}}
            <div>
                <label for="price" class="block text-gray-700 font-medium mb-2">Price</label>
                <input type="number" name="price" id="price" value="{{ old('price') }}"
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:ring focus:ring-blue-200 @error('price') @enderror"
                    min="1000" step="100">
                @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Stock --}}
            <div>
                <label for="stock" class="block text-gray-700 font-medium mb-2">Stock</label>
                <input type="number" name="stock" id="stock" value="{{ old('stock') }}"
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:ring focus:ring-blue-200 @error('stock') @enderror"
                    min="0">
                @error('stock')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Weight --}}
            <div>
                <label for="weight" class="block text-gray-700 font-medium mb-2">Weight (Kg)</label>
                <input type="number" name="weight" id="weight" value="{{ old('weight') }}"
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:ring focus:ring-blue-200 @error('weight') @enderror"
                    step="0.01" min="0">
                @error('weight')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Category --}}
            <div>
                <label for="category_id" class="block text-gray-700 font-medium mb-2">Category</label>
                <select name="category_id" id="category_id"
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:ring focus:ring-blue-200 @error('category_id') @enderror">
                    <option value="">-- Select Category --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-gray-700 font-medium mb-2">Description</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:ring focus:ring-blue-200 @error('description') @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-gray-700 font-medium mb-2">Status</label>
                <div class="flex items-center gap-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="status" value="available"
                            {{ old('status', 'available') == 'available' ? 'checked' : '' }}
                            class="form-radio text-green-500">
                        <span class="ml-2">Available</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="status" value="out_of_stock"
                            {{ old('status') == 'out_of_stock' ? 'checked' : '' }} class="form-radio text-red-500">
                        <span class="ml-2">Out of Stock</span>
                    </label>
                </div>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Display Product --}}
            <div>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_verified" value="1" {{ old('is_verified', 1) ? 'checked' : '' }}
                        class="form-checkbox text-blue-500">
                    <span class="ml-2">Display Product</span>
                </label>
            </div>

            {{-- Featured Product (admin only) --}}
            @if (auth()->user()?->role === 'admin')
                <div>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_featured" value="1"
                            {{ old('is_featured') ? 'checked' : '' }} class="form-checkbox text-yellow-500">
                        <span class="ml-2">Featured Product</span>
                    </label>
                </div>
            @endif

            <div class="pt-4">
                <button type="submit" class="px-6 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">
                    Save Product
                </button>
                <a href="{{ route('seller.products.index') }}"
                    class="px-6 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    @vite('resources/js/seller/image_helper.js')
    <script type="module">
        import {
            setupImagePreview
        } from '/resources/js/seller/image_helper.js';

        // Pasang preview image untuk halaman create product
        setupImagePreview('image', 'image-preview');
    </script>
@endsection
