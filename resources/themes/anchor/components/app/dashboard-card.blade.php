<a href="{{ $href ?? '' }}" @if($target ?? false) target="_blank" @endif class="flex overflow-hidden relative p-5 w-full liquid-glass-panel duration-300 ease-out group hover:scale-[1.01]">
    <span class="flex relative flex-col justify-center items-start pr-0 pb-1 space-y-3 h-full">
        <span class="block text-lg font-bold tracking-tight leading-tight text-slate-800 dark:text-white">{{ $title ?? '' }}</span>
        <span class="block text-sm opacity-70 dark:text-slate-200">{{ $description ?? '' }}</span>
        <span class="inline-flex relative justify-start items-center -mt-1 mb-2 w-auto text-xs tracking-tight leading-none text-slate-600 dark:text-slate-300">
            <span class="inline-block flex-shrink-0 mr-0">{{ $linkText ?? '' }}</span>
            <svg class="mt-0.5 ml-2 stroke-1 stroke-slate-600 dark:stroke-slate-300" fill="none" width="10" height="10" viewBox="0 0 10 10" aria-hidden="true"><path class="opacity-0 transition group-hover:opacity-100" d="M0 5h7"></path><path class="transition group-hover:translate-x-[3px]" d="M1 1l4 4-4 4"></path></svg>
            <span class="absolute bottom-0 left-0 w-0 h-px duration-200 ease-out translate-y-1 group-hover:w-full bg-slate-400"></span>
        </span>
    </span>
    <img src="{{ $image ?? '' }}" class="w-auto h-32 dark:brightness-95">
</a>
