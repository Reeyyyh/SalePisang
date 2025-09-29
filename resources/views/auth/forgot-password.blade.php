<x-layouts.app>
    <x-navbar></x-navbar>
    <div class="max-w-md mx-auto mt-16 bg-white p-8 rounded shadow">
        <h2 class="text-2xl font-bold text-darkblue mb-6 text-center">Lupa Password</h2>

        @if (session('message'))
            <div class="mb-4 text-sm text-green-600 font-medium text-center">
                {{ session('message') }}
            </div>
        @endif

        @if (session('status') === 'failed')
            <div class="mb-4 text-sm text-red-600 font-medium">
                {{ session('message') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-base font-medium text-gray-700 mb-1">
                    Email
                </label>
                <input id="email" name="email" type="email" required
                    class="mt-1 bg-[#F3F4F6] block w-full rounded-lg border border-gray-300 shadow-sm
           focus:outline-none focus:border-gray-300
           text-base px-4 py-3" />
                @error('email')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-darkblue text-white py-2 px-4 rounded-md transition">
                Kirim Link Reset Password
            </button>
        </form>

        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-sm text-darkblue hover:underline">Kembali ke Login</a>
        </div>
    </div>
</x-layouts.app>
