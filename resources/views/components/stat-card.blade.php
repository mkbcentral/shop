@props([
    'title',
    'value',
    'icon',
    'color' => 'blue',
    'badge' => null,
    'badgeColor' => 'green',
    'trend' => null,
    'trendUp' => true
])

@php
$colorClasses = match($color) {
    'blue' => 'from-blue-100 to-blue-200 text-blue-600',
    'green' => 'from-green-100 to-green-200 text-green-600',
    'purple' => 'from-purple-100 to-purple-200 text-purple-600',
    'amber' => 'from-amber-100 to-amber-200 text-amber-600',
    'yellow' => 'from-yellow-100 to-yellow-200 text-yellow-600',
    'red' => 'from-red-100 to-red-200 text-red-600',
    'indigo' => 'from-indigo-100 to-indigo-200 text-indigo-600',
    default => 'from-gray-100 to-gray-200 text-gray-600',
};

$badgeClasses = match($badgeColor) {
    'green' => 'bg-green-100 text-green-800',
    'red' => 'bg-red-100 text-red-800',
    'blue' => 'bg-blue-100 text-blue-800',
    'purple' => 'bg-purple-100 text-purple-800',
    'amber' => 'bg-amber-100 text-amber-800',
    default => 'bg-gray-100 text-gray-800',
};

$gradientClasses = match($color) {
    'blue' => 'from-blue-50',
    'green' => 'from-green-50',
    'purple' => 'from-purple-50',
    'amber' => 'from-amber-50',
    'yellow' => 'from-yellow-50',
    'red' => 'from-red-50',
    'indigo' => 'from-indigo-50',
    default => 'from-gray-50',
};

// Map icon names to SVG paths
$iconPaths = match($icon) {
    'document-text' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />',
    'clock' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />',
    'check-circle' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
    'switch-horizontal' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />',
    'currency-dollar' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
    'users' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />',
    'chart-bar' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />',
    'shopping-cart' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />',
    'cube' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />',
    'trending-up' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />',
    'trending-down' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />',
    'exclamation' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
    'x-circle' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />',
    default => $icon, // Allow raw SVG path to be passed
};
@endphp

<div {{ $attributes->merge(['class' => 'group relative bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100']) }}>
    <div class="absolute inset-0 bg-gradient-to-br {{ $gradientClasses }} to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
    <div class="relative p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="p-3 bg-gradient-to-br {{ $colorClasses }} rounded-xl group-hover:scale-110 transition-transform flex-shrink-0 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $iconPaths !!}
                </svg>
            </div>
            @if($badge)
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $badgeClasses }} flex-shrink-0">
                    {{ $badge }}
                </span>
            @endif
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">{{ $title }}</p>
            <p class="text-xl font-bold text-gray-900 leading-tight">{{ $value }}</p>
            @if($trend)
                <div class="flex items-center text-xs mt-2">
                    @if($trendUp)
                        <svg class="w-3.5 h-3.5 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <span class="text-green-600 font-medium">{{ $trend }}</span>
                    @else
                        <svg class="w-3.5 h-3.5 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                        </svg>
                        <span class="text-red-600 font-medium">{{ $trend }}</span>
                    @endif
                </div>
            @else
                @if($slot->isNotEmpty())
                    <div class="flex items-center text-xs text-gray-500 mt-2">
                        {{ $slot }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
