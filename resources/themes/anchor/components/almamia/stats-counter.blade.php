@props(['value' => 0, 'label' => ''])

<div {{ $attributes->twMerge('text-center') }}>
    <span class="text-4xl font-bold text-primary-800">{{ $value }}</span>
    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $label }}</p>
</div>