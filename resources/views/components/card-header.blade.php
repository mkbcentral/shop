@props(['divider' => true])

@php
$classes = $divider ? 'flex items-center justify-between mb-6' : 'mb-6';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>

@if($divider)
    <div class="border-b border-gray-100 -mx-6 mb-6"></div>
@endif
