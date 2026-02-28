@props([
    'title' => '',
    'type' => 'gray', // info, success, warning, danger
    'id' => uniqid(),
    'dismissable' => true
])

@php

    $alertIcon = 'phosphor-info-duotone';

    $alertIcon = match($type)
    {
        'info' => 'phosphor-info-duotone',
        'success' => 'icon-check-circle-duotone',
        'warning' => 'icon-warning-duotone',
        'danger' => 'icon-warning-circle-duotone',
        'gray' => 'icon-info-duotone'
    };


@endphp

<div 
    x-show="alert_{{ $id }}"
    x-data="{ alert_{{ $id }}: $persist(true) }"
    {{ $attributes->class([
        'relative pl-5 pr-10 py-4 w-full rounded-xl border backdrop-blur-xl',
        'bg-white/70 dark:bg-slate-900/70 text-slate-700 dark:text-slate-200 border-white/60 dark:border-sky-300/15' => $type == 'gray',
		'bg-blue-100/70 dark:bg-blue-500/15 text-blue-700 dark:text-blue-200 border-blue-200/80 dark:border-blue-300/20' => $type == 'info',
		'bg-emerald-100/75 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-200 border-emerald-200/80 dark:border-emerald-300/20' => $type == 'success',
		'bg-amber-100/75 dark:bg-amber-500/15 text-amber-700 dark:text-amber-200 border-amber-200/80 dark:border-amber-300/20' => $type == 'warning',
		'bg-red-100/75 dark:bg-red-500/15 text-red-700 dark:text-red-200 border-red-200/80 dark:border-red-300/20' => $type == 'danger'
    ]) }}
    x-collapse
    x-cloak
>
    @if($dismissable)
        <button @click="alert_{{ $id }}=false" class="absolute right-0 top-0 z-50 p-1.5 mr-3 rounded-full opacity-70 mt-3.5 cursor-pointer hover:opacity-100 hover:bg-white/60 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-300"><x-phosphor-x-bold class="w-3.5 h-3.5" /></button>
    @endif
    @if($title ?? false)
        <div class="flex items-start space-x-2">
            <x-icon name="{{ $alertIcon }}" class="w-5 h-5 -translate-y-0.5" />
            <h5 class="mb-1 font-medium leading-none tracking-tight">{{ $title }}</h5>
        </div>
    @endif
    <div class="@if($title ?? false){{ 'pl-7' }}@endif text-sm leading-6">{{ $slot }}</div>
    
</div>
