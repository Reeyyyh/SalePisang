<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class LandingPageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user?->role === 'admin') {
            return redirect('/admin-dashboard'); // langsung ke Filament admin panel
        }

        if ($user?->role === 'seller') {
            return redirect('/seller-dashboard'); // langsung ke Filament seller panel
        }

        // Untuk user biasa / guest
        $latestProducts = Product::orderBy('id', 'desc')->take(4)->get();
        $featuredProducts = Product::where('is_featured', true)->get();
        $categories = Category::all();

        return view('home.landingpage', compact('latestProducts', 'categories', 'featuredProducts'));
    }
}
