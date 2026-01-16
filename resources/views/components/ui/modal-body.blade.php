@props([
    'padding' => true,
    'scrollable' => true,
])

@php
$classes = collect([
    $padding ? 'p-5 sm:p-6' : '',
    $scrollable ? 'overflow-y-auto flex-1' : '',
])->filter()->join(' ');
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
