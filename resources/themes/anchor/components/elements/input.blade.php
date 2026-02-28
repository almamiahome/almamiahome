@props([
    'prefixText' => '',
    'suffixText' => '',
    'prefixIcon' => '',
    'prefixIconColor' => '',
    'suffixIcon' => '',
    'affixIconColor' => '',
    'valid' => true
])

<x-filament::input.wrapper
    :valid="$valid"
    :prefix-icon="$prefixIcon" 
    :prefix-icon-color="$prefixIconColor" 
    :suffixIcon="$suffixIcon" 
    :affix-icon-color="$affixIconColor"
    class="rounded-xl border-white/60 dark:border-sky-300/20 bg-white/70 dark:bg-slate-900/60 backdrop-blur-lg"
>
    @if ($prefixText)
        <x-slot name="prefix">{{ $prefixText }}</x-slot>
    @endif
    <x-filament::input
        type="text"
        class="rounded-xl"
        {{ $attributes }}
    />
    @if ($suffixText)
        <x-slot name="suffix">{{ $suffixText }}</x-slot>
    @endif
</x-filament::input.wrapper>
