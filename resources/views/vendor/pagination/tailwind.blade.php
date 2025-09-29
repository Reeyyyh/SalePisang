@if ($paginator->hasPages())
    <nav class="flex justify-center mt-[80px]" aria-label="Pagination">
        <ul class="flex items-center space-x-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="px-4 py-2 bg-gray-100 rounded-lg text-gray-400 cursor-not-allowed">&laquo;</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}"
                        class="px-4 py-2 bg-gray-200 rounded-lg text-gray-600 hover:bg-gray-300">&laquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li>
                        <span class="px-4 py-2 bg-gray-200 rounded-lg text-gray-600">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span
                                    class="px-4 py-2 bg-blue-100 text-blue-700 font-semibold rounded-lg">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}"
                                    class="px-4 py-2 bg-gray-200 rounded-lg text-gray-600 hover:bg-gray-300">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}"
                        class="px-4 py-2 bg-gray-200 rounded-lg text-gray-600 hover:bg-gray-300">&raquo;</a>
                </li>
            @else
                <li>
                    <span class="px-4 py-2 bg-gray-100 rounded-lg text-gray-400 cursor-not-allowed">&raquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
