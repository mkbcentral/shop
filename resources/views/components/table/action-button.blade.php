@props(['color' => 'indigo', 'icon' => null, 'href' => null, 'wire:click' => null])

@php
    $colorClasses = match($color) {
        'red' => 'text-red-600 hover:text-red-900',
        'green' => 'text-green-600 hover:text-green-900',
        'blue' => 'text-blue-600 hover:text-blue-900',
        'yellow' => 'text-yellow-600 hover:text-yellow-900',
        default => 'text-indigo-600 hover:text-indigo-900',
    };

    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    @if($href) href="{{ $href }}" @endif
    {{ $attributes->merge(['class' => "{$colorClasses} transition"]) }}
>
    @if($icon)
        {{ $icon }}
    @else
        {{ $slot }}
    @endif
</{{ $tag }}>
