@if (session('message'))
    @php
        $status = session('status', 'info');
        $styles = [
            'success' => ['text' => 'text-green-700', 'icon' => 'text-green-600'],
            'danger' => ['text' => 'text-red-700', 'icon' => 'text-red-600'],
            'info' => ['text' => 'text-blue-700', 'icon' => 'text-blue-600'],
            'warning' => ['text' => 'text-yellow-700', 'icon' => 'text-yellow-600'],
        ];
        $textColor = $styles[$status]['text'] ?? 'text-blue-700';
        $iconColor = $styles[$status]['icon'] ?? 'text-blue-600';
    @endphp

    <div id="success-toast"
        class="fixed top-5 right-5 z-50 flex items-center justify-between bg-white border border-gray-200 {{ $textColor }} text-sm md:text-base rounded-[8px] px-4 py-3 shadow-lg min-h-[50px] w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl animate-slide-in-right transition-all duration-300">
        <div class="flex items-center gap-3 w-full h-full">
            <svg class="w-5 h-5 md:w-6 md:h-6 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            <span class="font-medium">{{ session('message') }}</span>
        </div>
        <button onclick="closeToast()" class="ml-3 text-gray-500 hover:text-gray-700 text-xl font-bold leading-none">
            &times;
        </button>
    </div>

    <script>
        function closeToast() {
            const toast = document.getElementById('success-toast');
            if (toast) {
                toast.classList.remove('animate-slide-in-right');
                toast.classList.add('animate-slide-out-right');
                setTimeout(() => toast.remove(), 400);
            }
        }

        setTimeout(() => {
            closeToast();
        }, 5000);
    </script>

    <style>
        @keyframes slide-in-right {
            0% {
                transform: translateX(100%);
                opacity: 0;
            }
            100% {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slide-out-right {
            0% {
                transform: translateX(0);
                opacity: 1;
            }
            100% {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        .animate-slide-in-right {
            animation: slide-in-right 0.4s ease-out forwards;
        }
        .animate-slide-out-right {
            animation: slide-out-right 0.4s ease-in forwards;
        }
    </style>
@endif
