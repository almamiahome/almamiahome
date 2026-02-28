<div {{ $attributes->merge(['class' => 'inline-flex items-center']) }}>
    {{-- Logo para modo claro --}}
    <img
        src="https://almamiafragancias.com.ar/storage/logos/logo-claro.png"
        alt="Alma Mía Fragancias"
        class="block dark:hidden h-full w-auto"
    >

    {{-- Logo para modo oscuro --}}
    <img
        src="https://almamiafragancias.com.ar/storage/logos/logo-oscuro.png"
        alt="Alma Mía Fragancias"
        class="hidden dark:block h-full w-auto"
    >
</div>
