@props(['active' => false, 'icon' => null, 'badge' => null, 'badgeColor' => 'indigo', 'animate' => false, 'isDropdownItem' => false, 'title' => '', 'navigate' => true])

@php
// Styles pour les items dans un dropdown (sous-menu)
if ($isDropdownItem) {
    $classes = $active
        ? 'flex items-center px-3 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg group'
        : 'flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition-all group';

    $iconClasses = $active
        ? 'w-4 h-4 mr-2 text-indigo-600 flex-shrink-0'
        : 'w-4 h-4 mr-2 text-gray-400 group-hover:text-indigo-600 flex-shrink-0';
} else {
    // Styles pour les items normaux
    $classes = $active
        ? 'flex items-center px-4 py-3 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-md group sidebar-item relative'
        : 'flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-all group sidebar-item relative';

    $iconClasses = $active
        ? 'w-5 h-5 mr-3 flex-shrink-0'
        : 'w-5 h-5 mr-3 text-gray-400 group-hover:text-indigo-600 flex-shrink-0';
}

$badgeClasses = match($badgeColor) {
    'indigo' => 'bg-indigo-100 text-indigo-600',
    'green' => 'bg-green-100 text-green-600',
    'purple' => 'bg-purple-100 text-purple-600',
    'red' => 'bg-red-100 text-red-600',
    'amber' => 'bg-amber-100 text-amber-600',
    default => 'bg-gray-100 text-gray-600',
};
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} @if($navigate) wire:navigate.hover @endif>
    @if($icon)
        <svg class="{{ $iconClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {!! $icon !!}
        </svg>
    @endif

    <span class="flex-1 truncate text-sm">{{ $slot }}</span>

    @if($badge !== null)
        <span class="ml-auto {{ $badgeClasses }} px-2 py-1 rounded-full text-xs font-semibold {{ $animate ? 'animate-pulse' : '' }}">
            {{ $badge }}
        </span>
    @endif
</a>
