<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // TAMPILKAN PROFIL USER DI BLADE
    public function show(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')->with([
                'message' => 'silahkan login terlebih dahulu',
                'status' => 'info'
            ]);
        }

        $addresses = $user->addresses;

        return view('profile.profile', compact('user', 'addresses'));
    }

    public function address()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $addresses = $user->addresses()->orderByDesc('is_default')->get();
        return view('profile.address', compact('user', 'addresses'));
    }


    // PROSES UPDATE PROFIL USER
    // TODO : Update profile
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')->with([
                'message' => 'silahkan login terlebih dahulu',
                'status' => 'warning'
            ]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
        ]);

        $user->update([
            'name' => $validated['name'],
            'phone_number' => $validated['phone_number'] ?? $user->phone_number,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? $user->tanggal_lahir,
        ]);

        return redirect()->back()->with([
            'message' => 'profile berhasil di perbarui',
            'status' => 'success'
        ]);
    }

    // TODO : Add address
    public function addAddress(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')->with([
                'message' => 'silahkan login terlebih dahulu',
                'status' => 'warning'
            ]);
        }

        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'address1' => 'required|string',
            'address2' => 'nullable|string',
            'country' => 'required|string',
            'city' => 'required|string',
            'postal' => 'required|string',
            'phone' => 'required|string',
            'setAsDefault' => 'nullable|boolean',
        ]);

        // Cek apakah user sudah memiliki alamat
        $hasAddress = Address::where('user_id', $user->id)->exists();

        // Logika default
        $isDefault = $request->has('setAsDefault') || !$hasAddress;

        // Jika set as default, nonaktifkan default lainnya
        if ($isDefault) {
            Address::where('user_id', $user->id)->update(['is_default' => false]);
        }

        Address::create([
            'user_id' => $user->id,
            'fullname' => $validated['fullname'],
            'company' => $validated['company'] ?? '-',
            'address1' => $validated['address1'],
            'address2' => $validated['address2'] ?? '-',
            'country' => $validated['country'],
            'city' => $validated['city'],
            'postal' => $validated['postal'],
            'phone' => $validated['phone'],
            'is_default' => $isDefault,
        ]);

        return redirect()->route('profile.address')->with([
            'message' => 'alamat berhasil ditambahkan',
            'status' => 'success'
        ]);
    }

    // TODO : Update address
    public function updateAddress(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'address1' => 'required|string|max:255',
            'address2' => 'nullable|string|max:255',
            'country' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'postal' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'is_default' => 'nullable|boolean',
        ]);

        $address = $user->addresses()->where('id', $id)->firstOrFail();

        if ($request->is_default) {
            // Reset alamat default lain milik user
            $user->addresses()->update(['is_default' => false]);
        }

        $address->update([
            ...$validated,
            'is_default' => $validated['is_default'] ?? false,
        ]);

        return redirect()->route('profile.address')->with([
            'message' => 'alamat berhasil di perbarui',
            'status' => 'info'
        ]);
    }

    // TODO : Delete address
    public function deleteAddress($id)
    {
        $address = Address::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$address) {
            return redirect()->back()->with([
                'message' => 'Alamat tidak ditemukan.',
                'status' => 'danger',
            ]);
        }

        // Cek apakah alamat ini adalah alamat default
        if ($address->is_default) {
            return redirect()->back()->with([
                'message' => 'Alamat utama tidak boleh dihapus.',
                'status' => 'warning',
            ]);
        }

        $address->delete();

        return redirect()->route('profile.address')->with([
            'message' => 'Alamat berhasil dihapus.',
            'status' => 'success',
        ]);
    }

    // TODO : Set default address
    public function setDefaultAddress($id)
    {
        $user = auth()->user();

        /** @var \App\Models\User $user */
        $user->addresses()->update(['is_default' => false]);
        $user->addresses()->where('id', $id)->update(['is_default' => true]);

        return back()->with([
            'message' => 'Alamat utama berhasil di perbarui.',
            'status' => 'success',
        ]);
    }

    // ! HISTORY
    public function history()
    {
        $user = auth()->user();

        // Ambil semua ID order yang harus expired
        $expiredOrderIds = Order::where('user_id', Auth::id())
            ->where('orders_status', 'pending')
            ->where('payment_expired_at', '<', now())
            ->pluck('id');

        // Update orders_status ke expired
        Order::whereIn('id', $expiredOrderIds)->update([
            'orders_status' => 'expired',
        ]);

        // Update payment_status juga
        PaymentStatus::whereIn('order_id', $expiredOrderIds)
            ->where('status', 'pending')
            ->update([
                'status' => 'expired',
            ]);

        // Ambil semua order kecuali draft
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('profile.history', compact('user', 'orders'));
    }

    // ? order detail
    public function orderDetail($invoice)
    {
        $order = Order::with('orderDetails.product')
            ->where('invoice_number', $invoice)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('profile.order-detail', compact('order'));
    }
}
