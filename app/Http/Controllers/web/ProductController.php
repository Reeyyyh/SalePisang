<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    public function index(Request $request)
    {

        $user = Auth::user();

        if ($user?->role === 'admin') {
            return redirect('/admin-dashboard'); // langsung ke Filament admin panel
        }

        // if ($user?->role === 'seller') {
        //     return redirect('/seller-dashboard'); // langsung ke Filament seller panel
        // }

        $request->validate([
            'sort' => 'nullable|in:price_asc,price_desc',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
            'price_from' => 'nullable|numeric|min:0',
            'price_to' => 'nullable|numeric|min:0',
            'query' => 'nullable|string|max:100'
        ]);

        $sort = $request->query('sort');
        $categoriesFilter = $request->query('categories'); // array kategori dari checkbox
        $priceFrom = $request->query('price_from');
        $priceTo = $request->query('price_to');
        $searchQuery = $request->query('query');

        $query = Product::query()->where('is_verified', true);;

        // Filter berdasarkan kategori (misal kategori di-checkbox bisa lebih dari 1)
        if ($categoriesFilter && is_array($categoriesFilter)) {
            $query->whereIn('category_id', $categoriesFilter);
        }

        // Filter harga: from - to
        if ($priceFrom !== null && is_numeric($priceFrom)) {
            $query->where('price', '>=', $priceFrom);
        }
        if ($priceTo !== null && is_numeric($priceTo)) {
            $query->where('price', '<=', $priceTo);
        }

        // Filter search nama produk
        if ($searchQuery) {
            $query->where('product_name', 'like', '%' . $searchQuery  . '%');
        }

        // Sorting
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        }

        // Clone query untuk hitung total data sesuai filter, tanpa paginate
        $productCount = (clone $query)->count();

        /** @var \Illuminate\Pagination\LengthAwarePaginator $products */
        $products = $query->paginate(25);
        $products->withQueryString();

        $categories = Category::all();

        return view('product.product', compact('products', 'productCount', 'categories'));
    }

    public function show($sku)
    {
        $user = Auth::user();

        if ($user?->role === 'admin') {
            return redirect('/admin-dashboard'); // langsung ke Filament admin panel
        }

        if ($user?->role === 'seller') {
            return redirect('/seller-dashboard'); // langsung ke Filament seller panel
        }

        $product = Product::where('sku', $sku)->where('is_verified', true)->firstOrFail();
        return view('product.detail-product', compact('product'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string|max:100'
        ]);

        $query = $request->input('query');

        $products = Product::where('is_verified', true)->when($query, function ($q) use ($query) {
            $q->where('product_name', 'like', '%' . $query . '%');
        })->paginate(12);

        // Jika tidak ditemukan, arahkan ke view productnotfound
        if ($products->isEmpty()) {
            return view('errors.productnotfound', [
                'searchQuery' => $query
            ]);
        }

        $searchQuery = $query;
        $productCount = $products->total();
        $categories = Category::all();

        return view('product.product', compact('products', 'searchQuery', 'productCount', 'categories'));
    }




    // CRUD PRODUCT



}
