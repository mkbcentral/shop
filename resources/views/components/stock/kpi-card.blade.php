@props(['title', 'value', 'subtitle', 'color' => 'blue', 'icon'])

@php
$colorClasses = [
    'blue' => ['text' => 'text-blue-600', 'bg' => 'bg-blue-50'],
    'green' => ['text' => 'text-green-600', 'bg' => 'bg-green-50'],
    'red' => ['text' => 'text-red-600', 'bg' => 'bg-red-50'],
    'orange' => ['text' => 'text-orange-600', 'bg' => 'bg-orange-50'],
];
$colors = $colorClasses[$color] ?? $colorClasses['blue'];
@endphp

<x-card :padding="false" class="p-4">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600">{{ $title }}</p>
            <p class="text-xl font-bold {{ $colors['text'] }} mt-1">{{ $value }}</p>
            @if($subtitle)
                <p class="text-xs text-gray-500 mt-1">{{ $subtitle }}</p>
            @endif
        </div>
        <div class="p-2 {{ $colors['bg'] }} rounded-lg">
            <svg class="w-5 h-5 {{ $colors['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $icon !!}
            </svg>
        </div>
    </div>
</x-card>
