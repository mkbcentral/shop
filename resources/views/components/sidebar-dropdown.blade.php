@props(['title', 'icon' => null, 'active' => false, 'badge' => null, 'badgeColor' => 'indigo', 'open' => false])

@php
$dropdownId = 'dropdown-' . Str::slug($title);

// Vérifier si un pattern d'URL est défini pour détecter l'état actif
$activePattern = $attributes->get('activePattern', '');
$isActive = $active || ($activePattern && request()->is($activePattern));

$headerClasses = $isActive
    ? 'flex items-center justify-between w-full px-4 py-3 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-md group sidebar-item relative'
    : 'flex items-center justify-between w-full px-4 py-3 text-sm font-medium text-gray-700 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-all group sidebar-item relative';

$badgeClasses = match($badgeColor) {
    'indigo' => 'bg-indigo-100 text-indigo-600',
    'green' => 'bg-green-100 text-green-600',
    'purple' => 'bg-purple-100 text-purple-600',
    'red' => 'bg-red-100 text-red-600',
    'amber' => 'bg-amber-100 text-amber-600',
    default => 'bg-gray-100 text-gray-600',
};

$iconClasses = $isActive
    ? 'w-5 h-5 mr-3 flex-shrink-0'
    : 'w-5 h-5 mr-3 text-gray-400 group-hover:text-indigo-600 flex-shrink-0';

// Determine initial open state
$shouldBeOpen = $open || $isActive;
@endphp

<div 
    x-data="{
        open: {{ $shouldBeOpen ? 'true' : 'false' }},
        id: '{{ $dropdownId }}',
        init() {
            // Check localStorage for saved state (only if not forced open by active state)
            @if(!$shouldBeOpen)
            const saved = localStorage.getItem('dropdown-' + this.id);
            if (saved === 'open') {
                this.open = true;
            }
            @endif
        },
        toggle() {
            this.open = !this.open;
            localStorage.setItem('dropdown-' + this.id, this.open ? 'open' : 'closed');
        }
    }"
    class="relative" 
    data-dropdown-container
>
    <!-- Dropdown Header -->
    <button
        type="button"
        @click="toggle()"
        class="{{ $headerClasses }}"
        :aria-expanded="open.toString()"
    >
        <div class="flex items-center flex-1 min-w-0">
            @if($icon)
                <svg class="{{ $iconClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $icon !!}
                </svg>
            @endif
            <span class="truncate">{{ $title }}</span>
        </div>

        <div class="flex items-center space-x-2 flex-shrink-0">
            @if($badge !== null)
                <span class="{{ $badgeClasses }} px-2 py-1 rounded-full text-xs font-semibold">
                    {{ $badge }}
                </span>
            @endif

            <!-- Arrow Icon -->
            <svg
                class="w-4 h-4 transition-transform duration-300 {{ $isActive ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600' }} flex-shrink-0"
                :class="{ 'rotate-180': open }"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </button>

    <!-- Dropdown Content -->
    <div
        x-show="open"
        x-collapse
        x-cloak
        class="dropdown-content"
    >
        <div class="mt-2 ml-6 space-y-1 border-l-2 border-indigo-100 pl-3">
            {{ $slot }}
        </div>
    </div>
</div>
