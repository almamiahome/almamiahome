<table {{ $attributes->twMerge('min-w-full divide-y divide-primary-100 dark:divide-zinc-700') }}>
    <thead class="bg-primary-100 dark:bg-zinc-700 text-primary-800 dark:text-gray-200">
        {{ $head ?? '' }}
    </thead>
    <tbody class="bg-white dark:bg-zinc-800 divide-y divide-primary-100 dark:divide-zinc-700">
        {{ $slot }}
    </tbody>
</table>