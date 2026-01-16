{{--
    Auth Logo Component

    Usage: <x-auth.logo />

    Displays the application logo with status badge
--}}

@props([
    'showAppName' => true,
    'size' => 'default' // 'small', 'default', 'large'
])

@php
    $sizes = [
        'small' => [
            'container' => 'w-10 h-10',
            'text' => 'text-base',
            'badge' => 'w-3 h-3',
            'badgeIcon' => 'w-2 h-2',
            'appName' => 'text-xl'
        ],
        'default' => [
            'container' => 'w-12 h-12',
            'text' => 'text-lg',
            'badge' => 'w-4 h-4',
            'badgeIcon' => 'w-2.5 h-2.5',
            'appName' => 'text-2xl'
        ],
        'large' => [
            'container' => 'w-16 h-16',
            'text' => 'text-2xl',
            'badge' => 'w-5 h-5',
            'badgeIcon' => 'w-3 h-3',
            'appName' => 'text-3xl'
        ],
    ];
    $s = $sizes[$size] ?? $sizes['default'];
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center space-x-3']) }}>
    <div class="relative">
        <div class="{{ $s['container'] }} bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
            <span class="text-white font-black {{ $s['text'] }}">SF</span>
        </div>
        <div class="absolute -bottom-1 -right-1 {{ $s['badge'] }} bg-emerald-500 rounded-full border-2 border-slate-900 flex items-center justify-center">
            <svg class="{{ $s['badgeIcon'] }} text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
        </div>
    </div>
    @if($showAppName)
        <span class="{{ $s['appName'] }} font-bold text-white">{{ config('app.name', 'ShopFlow') }}</span>
    @endif
</div>
