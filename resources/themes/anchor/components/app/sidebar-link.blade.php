@props([
    'href' => '',
    'icon' => 'phosphor-house-duotone',
    'active' => false,
    'hideUntilGroupHover' => true,
    'target' => '_self',
    'ajax' => true
])

@php
    $isActive = filter_var($active, FILTER_VALIDATE_BOOLEAN);
@endphp

<a {{ $attributes }} href="{{ $href }}" @if((($href ?? false) && $target == '_self') && $ajax) wire:navigate @else @if($ajax) target="_blank" @endif @endif class="@if($isActive){{ 'bg-blue-500/10 border-pink-500 text-blue-700 dark:text-blue-300 shadow-sm backdrop-blur-md font-bold' }}@else{{ 'border-transparent text-zinc-500 dark:text-zinc-400' }}@endif border-l-4 px-2.5 py-2 flex rounded-xl w-full h-auto text-sm hover:bg-pink-500/5 hover:text-pink-600 dark:hover:text-pink-400 justify-start items-center space-x-2 overflow-hidden">
    <x-dynamic-component :component="$icon" class="flex-shrink-0 w-5 h-5" />
    <span class="flex-shrink-0 ease-out duration-50">{{ $slot }}</span>
</a>
