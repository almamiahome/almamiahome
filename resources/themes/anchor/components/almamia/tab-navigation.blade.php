@props(['tabs' => [], 'active' => null])

<div {{ $attributes->twMerge('border-b border-primary-100 dark:border-zinc-700') }}>
    <nav class="flex space-x-4">
        @foreach($tabs as $tab)
            @php $value = $tab['value'] ?? $tab['label']; $isActive = $value === $active; @endphp
            <a href="#" class="px-4 py-2 -mb-px text-sm font-medium border-b-2 {{ $isActive ? 'border-primary-600 text-primary-700 font-semibold' : 'border-transparent text-gray-600 dark:text-gray-400' }}">
                {{ $tab['label'] }}
            </a>
        @endforeach
    </nav>
</div>