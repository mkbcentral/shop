@props([
    'title' => null,
    'label' => null,
    'value',
    'icon' => null,
    'color' => 'indigo',
    'subtitle' => null,
])

@php
    // Allow both 'title' and 'label' for backwards compatibility
    $displayTitle = $title ?? $label;

    $colorClasses = [
        'indigo' => 'from-indigo-500 to-purple-600',
        'green' => 'from-emerald-500 to-green-600',
        'red' => 'from-red-500 to-rose-600',
        'yellow' => 'from-amber-500 to-orange-500',
        'blue' => 'from-blue-500 to-cyan-600',
        'purple' => 'from-purple-500 to-violet-600',
    ];

    $textColorClasses = [
        'indigo' => 'text-indigo-100',
        'green' => 'text-emerald-100',
        'red' => 'text-red-100',
        'yellow' => 'text-amber-100',
        'blue' => 'text-blue-100',
        'purple' => 'text-purple-100',
    ];

    // Mapping des noms d'icônes vers les SVG paths
    $iconPaths = [
        'clock' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        'arrow-down' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>',
        'arrow-up' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>',
        'truck' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>',
        'check-circle' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        'currency-dollar' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        'shopping-cart' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>',
        'users' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>',
        'cube' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
        'chart-bar' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
        'switch-horizontal' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>',
        'exclamation' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
    ];
@endphp

<div class="bg-gradient-to-br {{ $colorClasses[$color] ?? $colorClasses['indigo'] }} rounded-2xl shadow-xl p-6 text-white relative overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
    {{-- Background decoration --}}
    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white bg-opacity-10 rounded-full"></div>
    <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-16 h-16 bg-white bg-opacity-10 rounded-full"></div>
    
    <div class="flex items-center justify-between relative z-10">
        <div>
            <p class="{{ $textColorClasses[$color] ?? $textColorClasses['indigo'] }} text-sm font-semibold uppercase tracking-wide">{{ $displayTitle }}</p>
            <p class="text-4xl font-extrabold mt-2 drop-shadow-md">{{ $value }}</p>
            @if($subtitle)
                <p class="{{ $textColorClasses[$color] ?? $textColorClasses['indigo'] }} text-xs mt-2 font-medium">{{ $subtitle }}</p>
            @endif
        </div>
        @if($icon)
            <div class="bg-white bg-opacity-25 backdrop-blur-sm rounded-xl p-4 shadow-lg">
                @if(isset($iconPaths[$icon]))
                    <svg class="w-8 h-8 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $iconPaths[$icon] !!}
                    </svg>
                @else
                    {!! $icon !!}
                @endif
            </div>
        @endif
    </div>
</div>
