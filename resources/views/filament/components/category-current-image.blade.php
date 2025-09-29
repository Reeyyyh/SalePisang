<div class="mb-4">
    @php
        $category = $getRecord(); // ambil record saat ini
    @endphp

    @if ($category && $category->image)
        <img src="{{ asset('storage/' . $category->image) }}" width="150" class="rounded border mb-2">
        <p class="text-sm text-gray-700">
            Jika ingin mengganti gambar, hapus gambar terlebih dahulu menggunakan tombol <span
                class="font-semibold">Hapus Gambar</span>
        </p>
    @else
        <p class="text-gray-500">
            Belum ada gambar kategori. Bisa upload gambar baru menggunakan form di bawah.
        </p>
    @endif
</div>
