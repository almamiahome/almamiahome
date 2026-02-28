<div {{ $attributes->twMerge('flex items-start space-x-4') }}>
    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary-100 text-primary-700">
        {{ $icon ?? '' }}
    </div>
    <div>
        <h4 class="font-semibold text-primary-800">{{ $title ?? 'Título' }}</h4>
        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $slot ?? 'Descripción.' }}</p>
    </div>
</div>