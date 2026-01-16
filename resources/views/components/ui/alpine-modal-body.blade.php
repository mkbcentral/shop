@props([
    'padding' => true,
    'maxHeight' => 'calc(90vh - 180px)'
])

<div {{ $attributes->merge(['class' => 'flex-1 overflow-y-auto' . ($padding ? ' p-6' : '')]) }}
     style="max-height: {{ $maxHeight }};">
    {{ $slot }}
</div>
