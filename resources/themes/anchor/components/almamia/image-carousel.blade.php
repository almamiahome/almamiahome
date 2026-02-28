@props(['images' => []])

<div {{ $attributes->twMerge('relative w-full overflow-hidden') }}>
    <div class="flex transition-transform duration-300" x-data="{ current: 0, images: {{ json_encode($images) }} }" x-init="setInterval(() => { current = (current + 1) % images.length }, 5000)" :style="`transform: translateX(-${current * 100}%); width: ${images.length * 100}%`">
        @foreach($images as $img)
            <img src="{{ $img }}" alt="" class="w-full object-cover">
        @endforeach
    </div>
</div>