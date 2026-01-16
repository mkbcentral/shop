@props(['padding' => true, 'shadow' => 'sm', 'hover' => false])

@php
$classes = 'bg-white rounded-2xl border border-gray-100';
$classes .= $padding ? ' p-6' : '';
$classes .= ' shadow-' . $shadow;
$classes .= $hover ? ' hover:shadow-xl transition-all duration-300' : '';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @isset($header)
        <div class="mb-6">
            {{ $header }}
        </div>
    @endisset

    {{ $slot }}

    @isset($footer)
        <div class="mt-6 pt-6 border-t border-gray-100">
            {{ $footer }}
        </div>
    @endisset
</div>
