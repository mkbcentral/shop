@props([
    'name' => 'modal',
    'show' => false,
    'maxWidth' => '2xl',
    'closeable' => true,
    'closeOnClickOutside' => true,
    'closeOnEscape' => true,
    'persistent' => false,
])

@php
$maxWidthClasses = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    '3xl' => 'sm:max-w-3xl',
    '4xl' => 'sm:max-w-4xl',
    '5xl' => 'sm:max-w-5xl',
    '6xl' => 'sm:max-w-6xl',
    '7xl' => 'sm:max-w-7xl',
    'full' => 'sm:max-w-full sm:m-4',
];
$maxWidthClass = $maxWidthClasses[$maxWidth] ?? $maxWidthClasses['2xl'];

// Determine the Livewire property name to entangle
$showProperty = is_string($show) && !in_array($show, ['true', 'false', '1', '0', '']) ? $show : 'showModal';
@endphp

<div
    x-data="{ show: @entangle($showProperty).live }"
    x-show="show"
    x-cloak
    x-on:livewire:navigating.window="document.body.style.overflow = ''"
    x-init="$watch('show', value => { document.body.style.overflow = value ? 'hidden' : '' })"
    @if($closeOnEscape && $closeable && !$persistent)
        x-on:keydown.escape.window="show = false"
    @endif
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-{{ $name }}-title"
    role="dialog"
    aria-modal="true"
    {{ $attributes }}
>
    <!-- Backdrop -->
    <div
        @if($closeOnClickOutside && $closeable && !$persistent)
            x-on:click="show = false"
        @endif
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"
    ></div>

    <!-- Modal Container -->
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div
            x-show="show"
            x-on:click.stop
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative bg-white rounded-2xl shadow-2xl transform transition-all w-full {{ $maxWidthClass }} flex flex-col pointer-events-auto"
            style="max-height: 90vh;"
        >
            {{ $slot }}
        </div>
    </div>
</div>
