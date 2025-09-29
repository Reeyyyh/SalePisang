<x-layouts.app>
    <div class="max-w-md w-full mx-auto mt-20 px-6 sm:px-8">
        <div class="bg-white rounded-2xl p-8 sm:p-10">
            <h2 class="text-2xl sm:text-3xl font-bold text-darkblue text-center mb-8">
                Atur Ulang Password
            </h2>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                {{-- Password Baru --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password Baru
                    </label>
                    <input id="password" name="password" type="password" required
                        class="w-full bg-[#F3F4F6] rounded-lg border border-gray-300 px-4 py-2.5 text-sm shadow-sm
               focus:outline-none focus:border-gray-300 focus:ring-0" />
                    @error('password')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        Konfirmasi Password
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="w-full bg-[#F3F4F6] rounded-lg border border-gray-300 px-4 py-2.5 text-sm shadow-sm
               focus:outline-none focus:border-gray-300 focus:ring-0" />
                </div>


                {{-- Tombol Submit --}}
                <div>
                    <button type="submit"
                        class="w-full bg-darkblue text-white font-semibold text-sm sm:text-base
                               py-3 rounded-lg transition duration-200 shadow-md">
                        Simpan Password Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
