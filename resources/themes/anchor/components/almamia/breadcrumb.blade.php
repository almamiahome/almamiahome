@props(['items' => []])

<nav {{ $attributes->twMerge('flex items-center space-x-2 text-sm') }} aria-label="Breadcrumb">
    @foreach($items as $index => $item)
        @if($index > 0)
            <span class="text-gray-400">/</span>
        @endif
        @if(isset($item['url']))
            <a href="{{ $item['url'] }}" class="text-primary-700 hover:underline">{{ $item['label'] }}</a>
        @else
            <span class="text-gray-600 dark:text-gray-400">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>