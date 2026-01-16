@props(['align' => 'right'])

@php
    $justifyClass = match($align) {
        'left' => 'justify-start',
        'center' => 'justify-center',
        default => 'justify-end',
    };
@endphp

<div {{ $attributes->merge(['class' => "flex items-center gap-2 {$justifyClass}"]) }}>
    {{ $slot }}
</div>
