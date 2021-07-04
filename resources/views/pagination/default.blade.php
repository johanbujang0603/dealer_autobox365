
@if ($paginator->hasPages())
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li><span class="pagination__link"><i class="w-4 h-4" data-feather="chevrons-left"></i></span></li>
        @else
            <li><a class="pagination__link" href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="w-4 h-4" data-feather="chevrons-left"></i></a></li>
        @endif


        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li><span class="pagination__link">{{ $element }}</span></li>
            @endif


            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li><span class="pagination__link pagination__link--active">{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}" class="pagination__link">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach


        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" class="pagination__link" rel="next"><i class="w-4 h-4" data-feather="chevrons-right"></i></a></li>
        @else
            <li><span class="pagination__link"><i class="w-4 h-4" data-feather="chevrons-right"></i></span></li>
        @endif
    </ul>
@endif