@if ($paginator->hasPages())
    <ul class="pagination">

        @if ($paginator->onFirstPage())
            <li><span class="button disabled">← Назад</span></li>
        @else
            <li><a class="button" href="{{ $paginator->previousPageUrl() }}" rel="prev">← Назад</a></li>
        @endif



        @foreach ($elements as $element)

            @if (is_string($element))
                <li><span class="button disabled">{{ $element }}</span></li>
            @endif



            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li><span class="page active">{{ $page }}</span></li>
                    @else
                        <li><a class="page" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach



        @if ($paginator->hasMorePages())
            <li><a class="button" href="{{ $paginator->nextPageUrl() }}" rel="next">Вперед →</a></li>
        @else
            <li><span class="button disabled">Вперед →</span></li>
        @endif
    </ul>
@endif
