@if ($paginator->hasPages())
    <nav class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="page-link disabled">
                <x-lucide-chevron-left class="lucide-icon-inline" /> Sebelumnya
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-link">
                <x-lucide-chevron-left class="lucide-icon-inline" /> Sebelumnya
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="page-link dots">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="page-link active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-link">
                Selanjutnya <x-lucide-chevron-right class="lucide-icon-inline" />
            </a>
        @else
            <span class="page-link disabled">
                Selanjutnya <x-lucide-chevron-right class="lucide-icon-inline" />
            </span>
        @endif
    </nav>
@endif
