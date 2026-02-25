<div {{ $attributes->twMerge('p-8 md:p-16 bg-primary-50 rounded-xl text-center space-y-6') }}>
    <h2 class="text-4xl md:text-5xl font-bold text-primary-800">{{ $title ?? 'Título del banner' }}</h2>
    <p class="text-lg md:text-xl text-primary-600">{{ $description ?? 'Descripción del banner.' }}</p>
    <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
        {{ $slot }}
    </div>
</div>