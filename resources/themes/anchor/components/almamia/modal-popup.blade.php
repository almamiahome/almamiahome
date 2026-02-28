@props(['id' => 'modal', 'title' => ''])

<div x-data="{ open: false }" {{ $attributes }}>
    <div @click="open = true">
        {{ $trigger ?? '' }}
    </div>
    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center" style="display: none;">
        <div class="fixed inset-0 bg-slate-950/45 backdrop-blur-sm" @click="open = false"></div>
        <div class="liquid-glass-panel max-w-lg w-full p-6 z-50">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-primary-900 dark:text-slate-100">{{ $title }}</h3>
                <button @click="open = false" class="text-slate-500 hover:text-slate-700 dark:text-slate-300 dark:hover:text-white">×</button>
            </div>
            <div>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>