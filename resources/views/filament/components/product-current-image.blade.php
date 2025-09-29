<div class="mb-4 p-4 border rounded bg-gray-50">
    @php
        $product = $getRecord(); // ambil record saat ini
    @endphp

    @if($product && $product->image)
        <img src="{{ asset('storage/' . $product->image) }}" width="150" class="rounded border mb-2">
        <p class="text-sm text-gray-700">
            Jika ingin mengganti gambar, hapus gambar terlebih dahulu menggunakan tombol <span class="font-semibold">Hapus Gambar</span>
        </p>
    @else
        <p class="text-sm text-gray-500">
            Produk ini belum memiliki gambar.
            Silakan unggah gambar baru agar dapat tampil di halaman produk.
        </p>
    @endif
</div>
