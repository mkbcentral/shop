@props(['hoverable' => true])

@php
    $hoverClass = $hoverable ? 'hover:bg-gray-50 transition' : '';
@endphp

<tr {{ $attributes->merge(['class' => $hoverClass]) }}>
    {{ $slot }}
</tr>
