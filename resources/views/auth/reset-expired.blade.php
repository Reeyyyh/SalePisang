<x-layouts.app>
    <div class="max-w-md mx-auto mt-20 bg-white p-8 rounded shadow text-center">
        <h2 class="text-2xl font-bold text-red-600 mb-4">Token Tidak Berlaku</h2>
        <p class="text-gray-700 mb-6">
            Link reset password yang Anda gunakan sudah tidak berlaku atau telah digunakan.
        </p>
        <a href="{{ route('password.request') }}"
        class="inline-block bg-darkblue text-white px-6 py-3 rounded-md hover:bg-blue-800 transition">
            Minta Link Baru
        </a>
    </div>
</x-layouts.app>
