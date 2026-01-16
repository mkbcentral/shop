@props([
    'title',
    'value',
    'color' => 'indigo',
    'icon' => null,
    'clickable' => false,
    'wireClick' => null
])

@php
    $colorClasses = [
        'indigo' => [
            'bg' => 'bg-indigo-100',
            'text' => 'text-indigo-600',
            'value' => 'text-gray-900'
        ],
        'green' => [
            'bg' => 'bg-green-100',
            'text' => 'text-green-600',
            'value' => 'text-green-600'
        ],
        'orange' => [
            'bg' => 'bg-orange-100',
            'text' => 'text-orange-600',
            'value' => 'text-orange-600'
        ],
        'red' => [
            'bg' => 'bg-red-100',
            'text' => 'text-red-600',
            'value' => 'text-red-600'
        ],
        'purple' => [
            'bg' => 'bg-purple-100',
            'text' => 'text-purple-600',
            'value' => 'text-purple-600'
        ],
    ];

    $colors = $colorClasses[$color] ?? $colorClasses['indigo'];
    $cursorClass = $clickable ? 'cursor-pointer' : '';
    $wireClickAttr = $wireClick ? "wire:click=\"{$wireClick}\"" : '';
@endphp

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-2.5 hover:shadow-md transition-shadow duration-200 {{ $cursorClass }}"
     {!! $wireClickAttr !!}>
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-medium text-gray-600">{{ $title }}</p>
            <p class="text-xl font-bold {{ $colors['value'] }} mt-0.5">{{ $value }}</p>
        </div>
        <div class="{{ $colors['bg'] }} rounded-full p-1.5">
            @if($icon)
                {!! $icon !!}
            @else
                {{ $slot }}
            @endif
        </div>
    </div>
</div>
