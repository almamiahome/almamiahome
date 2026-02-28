<div {{ $attributes->twMerge('bg-white dark:bg-zinc-800 border border-primary-100 dark:border-zinc-700 p-6 rounded-lg shadow') }}>
    <p class="italic text-gray-700 dark:text-gray-300">{{ $quote ?? '' }}</p>
    <p class="mt-2 font-semibold text-primary-800">{{ $author ?? '' }}</p>
</div>