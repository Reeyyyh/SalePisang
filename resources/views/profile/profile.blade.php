<x-layouts.sidebar-profile>
    <section id="profileSection"
        class="w-full bg-[#F8F9F9] rounded-xl shadow px-4 py-5 sm:px-6 md:px-8 lg:px-10 xl:px-12 pb-[80px]">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
            <h2 class="text-base sm:text-lg font-semibold text-darkblue">Informasi Personal</h2>
            <button id="editProfileBtn" class="text-xs sm:text-sm text-gray-500 flex items-center gap-1 hover:underline">
                <img src="{{ asset('images/icons/edit.png') }}" alt="Edit" class="w-4 h-4">
                Edit Profile
            </button>
        </div>

        <!-- Grid Informasi -->
        <div
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-6 text-xs sm:text-sm text-darkblue">
            <div class="space-y-1">
                <p class="font-semibold">Email</p>
                <p>{{ auth()->user()->email }}</p>
            </div>
            <div class="space-y-1">
                <p class="font-semibold">Name</p>
                <p>{{ auth()->user()->name }}</p>
            </div>
            <div class="space-y-1">
                <p class="font-semibold">Tanggal Lahir</p>
                <p>
                    {{ $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('d-m-Y') : '-' }}
                </p>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-300 my-6"></div>

        <!-- Nomor HP -->
        <div class="text-xs sm:text-sm space-y-1 text-darkblue">
            <p class="font-semibold">Nomor Hp</p>
            <p>{{ $user->phone_number ? formatPhoneNumber($user->phone_number) : '-' }}</p>
        </div>
    </section>

    {{-- ! panel edit profile --}}
    <!-- Overlay and Slide Panel -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-[99] hidden"></div>

    <div id="editProfilePanel"
        class="fixed top-0 right-0 h-full w-full sm:max-w-[500px] md:max-w-[450px] bg-[#F9FAFB] z-[100] transform translate-x-full transition-transform duration-500 ease-in-out shadow-lg overflow-y-auto">

        <div class="flex items-center justify-between p-4 sm:p-6">
            <h2 class="text-xl sm:text-2xl font-extrabold text-darkblue ml-auto">Edit Profil</h2>
        </div>

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            <div class="px-4 sm:px-6 pb-8 space-y-6 text-[#1E2A3B] text-base">

                <!-- Email -->
                <div>
                    <label class="block font-medium mb-2">Email</label>
                    <input type="email" value="{{ old('email', auth()->user()->email ?? 'Email pengguna') }}"
                        class="bg-[#F3F4F6] w-full text-sm text-gray-800 px-4 py-3.5 rounded-md" disabled />
                </div>

                <!-- Nama -->
                <div>
                    <label class="block font-medium mb-2">Nama</label>
                    <input type="text" name="name"
                        value="{{ old('name', auth()->user()->name ?? 'Nama pengguna') }}"
                        class="bg-[#F3F4F6] w-full text-sm text-gray-800 px-4 py-3.5 rounded-md" />
                </div>

                <!-- Tanggal Lahir -->
                @php
                    $minDate = '1900-01-01';
                    $maxDate = date('Y-m-d');
                @endphp
                <div>
                    <label class="block font-medium mb-2">Tanggal Lahir</label>
                    <input type="date" min="{{ $minDate }}" max="{{ $maxDate }}" name="tanggal_lahir"
                        value="{{ old('tanggal_lahir', auth()->user()->tanggal_lahir ?? '') }}"
                        class="bg-[#F3F4F6] w-full text-sm text-gray-800 px-4 py-3.5 rounded-md" />
                </div>

                <!-- Nomor HP -->
                <div>
                    <label class="block font-medium mb-2">Nomor HP</label>
                    <input type="tel" name="phone_number"
                        value="{{ old('phone_number', auth()->user()->phone_number ?? 'Nomor HP') }}"
                        class="bg-[#F3F4F6] w-full text-sm text-gray-800 px-4 py-3.5 rounded-md" />
                </div>

                <!-- Tombol Simpan -->
                <div class="flex justify-center items-center gap-4 mt-6">
                    <!-- Tombol Simpan -->
                    <button type="submit"
                        class="w-1/2 sm:w-1/2 md:w-1/2 lg:w-full bg-darkblue text-white text-sm sm:text-base font-medium py-3 sm:py-4 rounded-xl hover:bg-[#15202b] transition">
                        Simpan
                    </button>

                    <!-- Tombol Batal: Hanya muncul di mobile/tablet -->
                    <button type="button" id="cancelEditBtn"
                        class="w-1/2 sm:w-1/2 md:w-1/2 lg:hidden bg-red-500 text-white text-sm sm:text-base font-medium py-3 sm:py-4 rounded-xl hover:bg-red-800 transition">
                        Batal
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.sidebar-profile>
