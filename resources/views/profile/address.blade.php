<x-layouts.sidebar-profile :title="'ADDRESS'">
    <section class="space-y-6">
        @if ($addresses->count())
            @foreach ($addresses as $address)
                <div class="bg-white p-4 sm:p-6 rounded-xl shadow border border-gray-200 w-full relative ">
                    <!-- Informasi Alamat -->
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2 mb-4">
                        <div>
                            <p class="text-base sm:text-lg font-semibold text-darkblue">{{ $address->fullname }}</p>
                            <p class="text-sm text-gray-700">{{ $address->address1 }}</p>
                            <p class="text-sm text-gray-700">{{ $address->city }}, {{ $address->country }}</p>
                        </div>

                        <div class="flex flex-col sm:items-end gap-2 sm:gap-1">
                            @if ($address->is_default)
                                <span
                                    class="inline-block bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-md">
                                    Alamat Utama
                                </span>
                            @endif

                            @if (!$address->is_default)
                                <form method="POST" action="{{ route('profile.setDefault', $address->id) }}">
                                    @csrf
                                    <label
                                        class="flex items-center gap-2 text-xs sm:text-sm font-medium text-gray-700 cursor-pointer">
                                        <input type="radio" name="default_address" value="{{ $address->id }}"
                                            onchange="this.form.submit()"
                                            class="accent-darkblue focus:outline-none w-4 h-4">
                                        Jadikan Alamat Utama
                                    </label>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Tombol Edit & Hapus -->
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6">
                        @if (!$address->is_default)
                            <form method="POST" action="{{ route('profile.address.delete', $address->id) }}">
                                @csrf
                                @method('DELETE')
                                <button
                                    class="w-full sm:w-auto text-sm bg-red-500 hover:bg-red-600 text-white font-medium px-4 py-2 rounded-md transition">
                                    Hapus Alamat
                                </button>
                            </form>
                        @endif

                        <button
                            class="editAddressBtn w-full sm:w-auto text-sm bg-darkblue text-white font-medium px-4 py-2 rounded-md hover:bg-[#15202b] transition"
                            data-address='@json($address)'>
                            Edit Alamat
                        </button>
                    </div>
                </div>
            @endforeach
        @else
            <div class="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div
                    class="bg-[#F8F9F9] p-6 sm:p-8 rounded-lg shadow text-center flex flex-col items-center justify-center space-y-5">

                    <!-- Judul -->
                    <h2 class="text-xl sm:text-2xl md:text-[26px] font-bold text-darkblue">
                        TIDAK ADA ALAMAT YANG DISIMPAN.
                    </h2>

                    <!-- Deskripsi -->
                    <p class="text-sm sm:text-base md:text-lg text-darkblue leading-relaxed">
                        Anda belum menambahkan alamat pengiriman.<br />
                        Silakan tambahkan alamat untuk melanjutkan pembelian Anda.
                    </p>

                    <!-- Tombol Tambah Alamat -->
                    <button id="toggleAddressFormBtn"
                        class="bg-darkblue text-white font-medium py-3 px-6 rounded-md hover:bg-[#15202b] transition text-sm sm:text-base">
                        Add New Address
                    </button>
                </div>
            </div>
        @endif
    </section>

    <!-- Tombol Trigger -->
    @if ($addresses->count())
        <div class="flex justify-center mt-8 pb-[80px]">
            <button id="toggleAddressFormBtn"
                class="bg-darkblue text-white font-medium py-3 px-6 rounded-md hover:bg-[#15202b] transition">
                Add New Address
            </button>
        </div>
    @endif


    <!-- Form Tambah Alamat -->
    <section id="addressFormSection" class="bg-white shadow-lg p-6 rounded-xl mt-10 hidden">
        <h2 class="text-2xl font-bold text-darkblue mb-6">Tambah Alamat Baru</h2>

        <form action="{{ route('profile.address.add') }}" method="POST" class="space-y-5">
            @csrf

            @php
                $baseInputClass =
                    'w-full rounded-lg px-4 py-3 text-base focus:outline-none  transition duration-200 ease-in-out';
            @endphp


            <!-- Nama Lengkap -->
            <div>
                <label for="fullname" class="block text-base font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="fullname" id="fullname" required class="bg-[#F3F4F6] {{ $baseInputClass }}">
            </div>

            <!-- Perusahaan -->
            <div>
                <label for="company" class="block text-base font-medium text-gray-700 mb-1">Perusahaan
                    (Opsional)</label>
                <input type="text" name="company" id="company" class="bg-[#F3F4F6] {{ $baseInputClass }}">
            </div>

            <!-- Alamat 1 -->
            <div>
                <label for="address1" class="block text-base font-medium text-gray-700 mb-1">Alamat 1</label>
                <input type="text" name="address1" id="address1" required class="bg-[#F3F4F6] {{ $baseInputClass }}">
            </div>

            <!-- Alamat 2 -->
            <div>
                <label for="address2" class="block text-base font-medium text-gray-700 mb-1">Alamat 2
                    (Opsional)</label>
                <input type="text" name="address2" id="address2" class="bg-[#F3F4F6] {{ $baseInputClass }}">
            </div>

            <!-- Negara -->
            <div>
                <label for="country" class="block text-base font-medium text-gray-700 mb-1">Negara/Wilayah</label>
                <input type="text" name="country" id="country" required class="bg-[#F3F4F6] {{ $baseInputClass }}">
            </div>

            <!-- Kota -->
            <div>
                <label for="city" class="block text-base font-medium text-gray-700 mb-1">Kota</label>
                <input type="text" name="city" id="city" required class="bg-[#F3F4F6] {{ $baseInputClass }}">
            </div>

            <!-- Kode Pos -->
            <div>
                <label for="postal" class="block text-base font-medium text-gray-700 mb-1">Kode Pos</label>
                <input type="text" name="postal" id="postal" required class="bg-[#F3F4F6] {{ $baseInputClass }}">
            </div>

            <!-- Nomor Telepon -->
            <div>
                <label for="phone" class="block text-base font-medium text-gray-700 mb-1">Nomor Telepon</label>
                <input type="text" name="phone" id="phone" required class="bg-[#F3F4F6] {{ $baseInputClass }}">
            </div>

            <!-- Checkbox -->
            <div class="flex items-center">
                <input type="checkbox" id="setAsDefault" name="setAsDefault" value="1"
                    class="mr-2 accent-darkblue">
                <label for="setAsDefault" class="text-base font-medium text-gray-700">Jadikan sebagai alamat
                    utama</label>
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-darkblue text-white font-medium py-3 rounded-md hover:bg-[#15202b] transition text-base">
                Tambahkan Alamat
            </button>
        </form>
    </section>


    {{-- ! panel edit address --}}
    <!-- Overlay -->
    <div id="addressOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-[99] hidden"></div>

    <!-- Slide-in Edit Address Panel -->
    <div id="editAddressPanel"
        class="fixed top-0 right-0 h-full w-full sm:max-w-[500px] md:max-w-[450px] bg-[#F9FAFB] z-[100] transform translate-x-full transition-transform duration-500 ease-in-out shadow-lg overflow-y-auto">

        <div class="flex items-center justify-between p-4 sm:p-6">
            <h2 class="text-xl sm:text-2xl font-extrabold text-darkblue ml-auto">Edit Alamat</h2>
        </div>
        <form id="editAddressForm" method="POST">
            @csrf
            @method('PUT')

            <div class="px-4 sm:px-6 pb-8 space-y-6 text-[#1E2A3B] text-base">
                @php
                    $baseInputClass =
                        'w-full rounded-lg border-gray-300 shadow-sm px-4 py-3 text-base outline-none transition duration-200 ease-in-out ring-1 ring-gray-200 focus:ring-2 focus:ring-darkblue focus:border-darkblue';
                @endphp

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="fullname" class="{{ $baseInputClass }}" required />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Perusahaan (Opsional)</label>
                    <input type="text" name="company" class="{{ $baseInputClass }}" />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat 1</label>
                    <input type="text" name="address1" class="{{ $baseInputClass }}" required />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat 2 (Opsional)</label>
                    <input type="text" name="address2" class="{{ $baseInputClass }}" />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Negara/Wilayah</label>
                    <input type="text" name="country" class="{{ $baseInputClass }}" required />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kota</label>
                    <input type="text" name="city" class="{{ $baseInputClass }}" required />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kode Pos</label>
                    <input type="text" name="postal" class="{{ $baseInputClass }}" required />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Telepon</label>
                    <input type="text" name="phone" id="phoneInput" class="{{ $baseInputClass }}" required />
                </div>

                <div class="flex justify-center items-center gap-4 pt-4">
                    <!-- Tombol Simpan -->
                    <button type="submit"
                        class="w-1/2 sm:w-1/2 md:w-1/2 lg:w-full bg-darkblue text-white text-sm sm:text-base font-medium py-3 sm:py-4 rounded-xl hover:bg-[#15202b] transition">
                        Simpan Perubahan
                    </button>

                    <!-- Tombol Batal -->
                    <button type="button" id="cancelEditBtn"
                        class="w-1/2 sm:w-1/2 md:w-1/2 lg:hidden bg-red-500 text-white text-sm sm:text-base font-medium py-3 sm:py-4 rounded-xl hover:bg-red-800 transition">
                        Batal
                    </button>
                </div>
            </div>

        </form>
    </div>


</x-layouts.sidebar-profile>
