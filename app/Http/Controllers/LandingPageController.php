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
            return redirect('/admin-dashboard');
        }

        if ($user?->role === 'seller') {
            return redirect('/seller-dashboard');
        }

        // Untuk user biasa / guest
        $latestProducts = Product::where('is_verified', true)->orderBy('id', 'desc')->take(4)->get();
        $featuredProducts = Product::where('is_verified', true)->where('is_featured', true)->get();
        $categories = Category::all();

        return view('home.landingpage', compact('latestProducts', 'categories', 'featuredProducts'));
    }
}
