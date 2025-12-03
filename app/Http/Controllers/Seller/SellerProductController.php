<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SellerProductController extends Controller
{
    // Daftar produk seller
    public function index()
    {
        $sellerId = Auth::id();
        $products = Product::where('user_id', $sellerId)->paginate(20);

        return view('client.seller.products.index', compact('products'));
    }

    // Form tambah produk
    public function create()
    {
        $categories = Category::all();
        return view('client.seller.products.create', compact('categories'));
    }

    // Simpan produk baru
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:120',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:1000|max:10000000',
            'stock' => 'required|integer|min:0|max:9999',
            'weight' => 'nullable|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:available,out_of_stock',
            'is_verified' => 'boolean',
        ]);

        $data = $request->only([
            'product_name',
            'category_id',
            'price',
            'stock',
            'weight',
            'description',
            'status',
            'is_verified'
        ]);
        $data['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        return redirect()->route('seller.products.index')
            ->with('message', 'Produk berhasil ditambahkan')
            ->with('status', 'success');
    }

    // Form edit produk
    public function edit($id)
    {
        $product = Product::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $categories = Category::all();
        return view('client.seller.products.edit', compact('product', 'categories'));
    }

    // Update produk
    public function update(Request $request, $id)
    {
        $product = Product::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'product_name' => 'required|string|max:120',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:1000|max:10000000',
            'stock' => 'required|integer|min:0|max:9999',
            'weight' => 'nullable|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:available,out_of_stock',
            'is_verified' => 'boolean',
        ]);

        $data = $request->only([
            'product_name',
            'category_id',
            'price',
            'stock',
            'weight',
            'description',
            'status',
            'is_verified'
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('seller.products.index')
            ->with('message', 'Produk berhasil diperbarui')
            ->with('status', 'success');
    }

    // Hapus produk
    public function destroy($id)
    {
        $product = Product::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Hapus file gambar dulu
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->forceDelete();

        return redirect()->route('seller.products.index')
            ->with('message', 'Produk berhasil dihapus')
            ->with('status', 'success');
    }
}
