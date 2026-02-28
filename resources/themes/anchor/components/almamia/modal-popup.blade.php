@props(['id' => 'modal', 'title' => ''])

<div x-data="{ open: false }" {{ $attributes }}>
    <div @click="open = true">
        {{ $trigger ?? '' }}
    </div>
    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center" style="display: none;">
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="open = false"></div>
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-lg max-w-lg w-full p-6 z-50">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-primary-800">{{ $title }}</h3>
                <button @click="open = false" class="text-gray-500 hover:text-gray-700">×</button>
            </div>
            <div>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>