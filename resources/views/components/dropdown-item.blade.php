@props([
    'href' => null,
    'wireNavigate' => false,
    'wireClick' => null,
    'icon' => null,
    'iconColor' => null,
    'variant' => 'default', // default, danger, success
    'disabled' => false,
])

@php
    $variantClasses = match($variant) {
        'danger' => 'text-red-600 hover:bg-red-50',
        'success' => 'text-green-600 hover:bg-green-50',
        default => 'text-gray-700 hover:bg-gray-100',
    };

    $baseClasses = "flex items-center w-full px-4 py-2 text-sm transition-colors " . $variantClasses;

    if ($disabled) {
        $baseClasses .= ' opacity-50 cursor-not-allowed';
    }

    $iconClasses = "w-4 h-4 mr-3" . ($iconColor ? " {$iconColor}" : "");
@endphp

@if($href)
    <a
        href="{{ $href }}"
        @if($wireNavigate) wire:navigate @endif
        class="{{ $baseClasses }}"
        @click="open = false"
        {{ $attributes }}
    >
        @if($icon)
            <x-dynamic-component :component="'icons.' . $icon" class="{{ $iconClasses }}" />
        @endif
        {{ $slot }}
    </a>
@else
    <button
        type="button"
        @if($wireClick) wire:click="{{ $wireClick }}" @endif
        class="{{ $baseClasses }}"
        @if(!$disabled) @click="open = false" @endif
        @if($disabled) disabled @endif
        {{ $attributes }}
    >
        @if($icon)
            <x-dynamic-component :component="'icons.' . $icon" class="{{ $iconClasses }}" />
        @endif
        {{ $slot }}
    </button>
@endif
