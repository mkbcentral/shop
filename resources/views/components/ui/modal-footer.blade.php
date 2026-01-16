@props([
    'align' => 'right',
    'sticky' => true,
])

@php
$alignClasses = [
    'left' => 'justify-start',
    'center' => 'justify-center',
    'right' => 'justify-end',
    'between' => 'justify-between',
];
$alignClass = $alignClasses[$align] ?? $alignClasses['right'];
@endphp

<div {{ $attributes->merge(['class' => 'flex-shrink-0 flex items-center gap-3 px-5 sm:px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl ' . $alignClass]) }}>
    {{ $slot }}
</div>
