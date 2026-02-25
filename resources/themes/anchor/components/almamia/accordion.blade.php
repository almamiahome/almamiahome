@props(['title' => 'Pregunta', 'open' => false])

<div x-data="{ open: {{ $open ? 'true' : 'false' }} }" {{ $attributes->twMerge('border border-primary-100 dark:border-zinc-700 rounded-md') }}>
    <button type="button" @click="open = !open" class="flex justify-between items-center w-full px-4 py-3 text-left text-primary-800 dark:text-gray-200">
        <span>{{ $title }}</span>
        <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transform transition-transform text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </button>
    <div x-show="open" x-collapse class="px-4 pb-4 text-gray-600 dark:text-gray-400">
        {{ $slot }}
    </div>
</div>