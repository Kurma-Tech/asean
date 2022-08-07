<div>
    @if ($paginator->hasPages())
    <ul class="pagination pagination-sm m-0 float-right">
    
        
        @if ($paginator->onFirstPage())
        <li class="page-item">
            <span class="page-link">«</span>
        </li>
        @else
        <li class="page-item">
            <a class="page-link" href="#" wire:click.prevent="previousPage">«</a>
        </li>
        @endif


        @foreach ($elements as $element)
        @if (is_string($element))
        <li class="page-item">
            <span class="page-link">{{$element}}</span>
        </li>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                <li class="page-item">
                    <span class="page-link">{{ $page }}</span>
                </li>
                @else
                <li class="page-item">
                    <a class="page-link" href="#" wire:click.prevent="gotoPage({{ $page }})">{{ $page }}</a>
                </li>
                @endif
            @endforeach
        @endif
        @endforeach


        @if ($paginator->hasMorePages())
        <li class="page-item">
            <a class="page-link" href="#" wire:click.prevent="nextPage">»</a>
        </li>
        @else
        <li class="page-item">
            <span class="page-link">»</span>
        </li>
        @endif

    </ul>
    @endif
</div>

