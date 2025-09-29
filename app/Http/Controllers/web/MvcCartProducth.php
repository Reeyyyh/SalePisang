<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\CartProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class MvcCartProducth extends Controller
{
    //

    public function getUserCart()
    {
        $user_id = auth()->id();

        // Ambil cart dengan relasi product
        $cartItems = CartProduct::with('product')
            ->where('user_id', $user_id)
            ->get();

        // Hitung total harga semua item
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // return view('cart.cart-profile', compact('cartItems', 'total'));
        return response()
        ->view('cart.cart-profile', compact('cartItems', 'total'))
        ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
    }


    public function addToCart(Request $request)
    {
        $user_id = auth()->id();

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::find($request->product_id);

        if (!$product || is_null($product->stock) || $request->quantity > $product->stock) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $cartItem = CartProduct::where('user_id', $user_id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $newQty = $cartItem->quantity + $request->quantity;

            if ($newQty > $product->stock) {
                return back()->with('error', 'Jumlah melebihi stok tersedia.');
            }

            $cartItem->update(['quantity' => $newQty]);
        } else {
            CartProduct::create([
                'user_id' => $user_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }


        return back()->with([
            'status' => 'success',
            'message' => 'Produk berhasil ditambahkan ke keranjang.'
        ]);
    }

    public function updateCart(Request $request, $id)
    {
        $cartItem = CartProduct::findOrFail($id);

        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        if ($request->action === 'increase') {
            $cartItem->quantity += 1;
            $message = 'Produk berhasil ditambahkan.';
            $status = 'success';

        } elseif ($request->action === 'decrease' && $cartItem->quantity > 1) {
            $cartItem->quantity -= 1;
            $message = 'Produk berhasil dikurangi.';
            $status = 'success';
        } else {
            $message = 'Tidak ada perubahan pada keranjang.';
            $status = 'info';
        }

        $cartItem->save();

        return redirect()->route('cart')->with([
            'status' => $status,
            'message' => $message,
        ]);
    }

    public function deleteCart($id)
    {
        $cartItem = CartProduct::findOrFail($id);

        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->delete();

        return redirect()->route('cart')->with([
            'status' => 'danger',
            'message' => 'Item berhasil dihapus.'
        ]);
    }
}
