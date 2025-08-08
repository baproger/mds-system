@props(['items' => []])

<nav class="breadcrumb-nav" aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach($items as $index => $item)
            <li class="breadcrumb-item {{ $index === count($items) - 1 ? 'active' : '' }}">
                @if($index === count($items) - 1)
                    @if(isset($item['icon']))
                        <i class="{{ $item['icon'] }}"></i>
                    @endif
                    <span class="breadcrumb-text">{{ $item['text'] ?? $item['title'] ?? '' }}</span>
                @else
                    <a href="{{ $item['url'] }}" class="breadcrumb-link">
                        @if(isset($item['icon']))
                            <i class="{{ $item['icon'] }}"></i>
                        @endif
                        <span class="breadcrumb-text">{{ $item['text'] ?? $item['title'] ?? '' }}</span>
                    </a>
                @endif
            </li>
            
            @if($index < count($items) - 1)
                <li class="breadcrumb-separator">
                    <i class="fas fa-chevron-right"></i>
                </li>
            @endif
        @endforeach
    </ol>
</nav> 