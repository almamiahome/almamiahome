<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('theme::partials.head', ['seo' => ($seo ?? null) ])
    <script>
        if (typeof(Storage) !== "undefined") {
            if(localStorage.getItem('theme') && localStorage.getItem('theme') == 'dark'){
                document.documentElement.classList.add('dark');
            }
        }
        document.addEventListener("livewire:navigated", () => {
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });
    </script>
    
    <style>
        /* Scrollbar minimalista estilo OS */
        .scrollbar-hidden::-webkit-scrollbar { width: 6px; }
        .scrollbar-hidden::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-hidden::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.2); border-radius: 10px; }
        .dark .scrollbar-hidden::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); }
        
        /* Imagen de fondo fija y nítida */
        .fixed-wallpaper {
            position: fixed;
            inset: 0;
            z-index: -1;
            background-image: url('https://images.unsplash.com/photo-1557683316-973673baf926?auto=format&fit=crop&q=80&w=2000');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
</head>
<body x-data class="flex flex-col lg:min-h-screen bg-zinc-100 dark:bg-zinc-950 @if(config('wave.dev_bar')){{ 'pb-10' }}@endif">

    {{-- Imagen de Fondo Fija Detrás de Todo --}}
    <div class="fixed-wallpaper">
        {{-- Overlay al 50% de opacidad sin desenfoque --}}
        <div class="absolute inset-0 bg-white/50 dark:bg-zinc-950/50"></div>
    </div>

    <x-app.sidebar />

    <div class="flex flex-col pl-0 min-h-screen justify-stretch lg:pl-64">
        {{-- Mobile Header --}}
        <header class="lg:hidden px-5 block flex justify-between sticky top-0 z-40 bg-white/50 dark:bg-zinc-900/50 border-b border-zinc-200/50 dark:border-zinc-800 h-[72px] items-center">
            <button x-on:click="window.dispatchEvent(new CustomEvent('open-sidebar'))" class="flex flex-shrink-0 justify-center items-center w-10 h-10 rounded-md text-zinc-700 dark:text-zinc-200 hover:bg-white/20">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" /></svg>
            </button>
            <x-app.user-menu position="top" />
        </header>

        <main class="relative flex flex-col flex-1 xl:px-0 lg:h-screen">
            
            {{-- CONTENEDOR PRINCIPAL (Transparente al 50% para ver el fondo nítido) --}}
            <div class="relative z-10 flex-1 h-full border-l-0 border-zinc-200/30 overflow-hidden">
                <div class="w-full h-full lg:overflow-y-auto scrollbar-hidden">
                    <div class="px-5 py-6 sm:px-8 lg:px-10">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </main>
    </div>

    @livewire('notifications')
    @auth
        <livewire:onboarding-modal :key="'onboarding-modal-' . auth()->id()" />
    @endauth
    @if(!auth()->guest() && auth()->user()->hasChangelogNotifications())
        @include('theme::partials.changelogs')
    @endif
    @include('theme::partials.footer-scripts')
    {{ $javascript ?? '' }}

</body>
</html>