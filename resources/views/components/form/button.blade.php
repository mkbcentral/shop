@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'disabled' => false,
    'icon' => null,
    'iconPosition' => 'left',
    'href' => null,
    'fullWidth' => false,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold rounded-lg shadow-sm transition duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

    $variantClasses = [
        'primary' => 'text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500',
        'primary-gradient' => 'text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:ring-indigo-500',
        'secondary' => 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:ring-indigo-500 shadow-sm',
        'danger' => 'text-white bg-red-600 hover:bg-red-700 focus:ring-red-500',
        'danger-gradient' => 'text-white bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 focus:ring-red-500',
        'success' => 'text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500',
        'success-gradient' => 'text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 focus:ring-emerald-500',
        'warning' => 'text-white bg-amber-600 hover:bg-amber-700 focus:ring-amber-500',
        'warning-gradient' => 'text-white bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-700 hover:to-orange-700 focus:ring-yellow-500',
        'ghost' => 'text-gray-700 bg-transparent hover:bg-gray-100 focus:ring-gray-500 shadow-none',
        'link' => 'text-indigo-600 bg-transparent hover:text-indigo-800 hover:underline focus:ring-indigo-500 shadow-none',
    ];

    $sizeClasses = [
        'xs' => 'px-2.5 py-1.5 text-xs',
        'sm' => 'px-3 py-2 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-4 py-2.5 text-base',
        'xl' => 'px-6 py-3 text-base',
    ];

    $iconSizeClasses = [
        'xs' => 'w-3.5 h-3.5',
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-5 h-5',
        'xl' => 'w-6 h-6',
    ];

    $classes = $baseClasses . ' ' .
               ($variantClasses[$variant] ?? $variantClasses['primary']) . ' ' .
               ($sizeClasses[$size] ?? $sizeClasses['md']) .
               ($fullWidth ? ' w-full' : '');

    $iconSize = $iconSizeClasses[$size] ?? $iconSizeClasses['md'];
    $iconMargin = $iconPosition === 'left' ? 'mr-2' : 'ml-2';
@endphp

@if($href)
    <a
        href="{{ $href }}"
        {{ $attributes->merge(['class' => $classes]) }}
    >
        @if($icon && $iconPosition === 'left')
            <x-dynamic-component :component="'icons.' . $icon" :class="$iconSize . ' ' . $iconMargin" />
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <x-dynamic-component :component="'icons.' . $icon" :class="$iconSize . ' ' . $iconMargin" />
        @endif
    </a>
@else
    <button
        type="{{ $type }}"
        @if($disabled) disabled @endif
        {{ $attributes->merge(['class' => $classes]) }}
    >
        @if($icon && $iconPosition === 'left')
            <x-dynamic-component :component="'icons.' . $icon" :class="$iconSize . ' ' . $iconMargin" />
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <x-dynamic-component :component="'icons.' . $icon" :class="$iconSize . ' ' . $iconMargin" />
        @endif
    </button>
@endif
