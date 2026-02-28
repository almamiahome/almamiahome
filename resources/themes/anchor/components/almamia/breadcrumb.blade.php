@props(['items' => []])

<nav {{ $attributes->twMerge('flex items-center space-x-2 text-sm text-slate-600 dark:text-slate-300') }} aria-label="Breadcrumb">
    @foreach($items as $index => $item)
        @if($index > 0)
            <span class="text-slate-400 dark:text-slate-500">/</span>
        @endif
        @if(isset($item['url']))
            <a href="{{ $item['url'] }}" class="text-secondary-700 dark:text-sky-300 hover:underline">{{ $item['label'] }}</a>
        @else
            <span class="text-slate-600 dark:text-slate-400">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>