{{--
    Slide-Over Panel Component

    For side panels/drawers instead of centered modals.

    Usage:
    <x-ui.slide-over
        show="showPanel"
        title="Détails"
        position="right"
    >
        <!-- Content -->
    </x-ui.slide-over>
--}}

@props([
    'name' => 'slideOver',
    'show' => false,
    'title' => '',
    'subtitle' => null,
    'position' => 'right', // left, right
    'width' => 'md', // sm, md, lg, xl, 2xl
    'closeable' => true,
    'onClose' => null,
])

@php
$widthClasses = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
];
$widthClass = $widthClasses[$width] ?? $widthClasses['md'];

$positionClasses = [
    'left' => [
        'container' => 'justify-start',
        'panel' => 'rounded-r-2xl',
        'enter' => '-translate-x-full',
        'leave' => '-translate-x-full',
    ],
    'right' => [
        'container' => 'justify-end',
        'panel' => 'rounded-l-2xl',
        'enter' => 'translate-x-full',
        'leave' => 'translate-x-full',
    ],
];
$pos = $positionClasses[$position] ?? $positionClasses['right'];

$isLivewire = is_string($show) && !in_array($show, ['true', 'false']);
$closeAction = $onClose
    ? ($isLivewire ? "\$wire.{$onClose}()" : $onClose)
    : 'close()';
@endphp

<div
    x-data="{
        show: {{ $isLivewire ? "@entangle('{$show}')" : ($show ? 'true' : 'false') }},
        close() {
            @if($closeable)
                @if($isLivewire)
                    $wire.set('{{ $show }}', false);
                @else
                    this.show = false;
                @endif
            @endif
        }
    }"
    x-show="show"
    x-cloak
    @keydown.escape.window="close()"
    x-on:livewire:navigating.window="document.body.style.overflow = ''"
    class="fixed inset-0 z-50 overflow-hidden"
    aria-labelledby="slide-over-{{ $name }}-title"
    role="dialog"
    aria-modal="true"
    {{ $attributes }}
>
    <!-- Backdrop -->
    <div
        @click="close()"
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"
    ></div>

    <!-- Panel Container -->
    <div class="fixed inset-y-0 {{ $position === 'left' ? 'left-0' : 'right-0' }} flex {{ $pos['container'] }} max-w-full">
        <div
            x-show="show"
            @click.stop
            x-transition:enter="transform transition ease-in-out duration-300"
            x-transition:enter-start="{{ $pos['enter'] }}"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="{{ $pos['leave'] }}"
            class="relative w-screen {{ $widthClass }}"
        >
            <div class="flex h-full flex-col bg-white shadow-2xl {{ $pos['panel'] }}">
                <!-- Header -->
                <div class="flex-shrink-0 flex items-center justify-between px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <div>
                        <h2 id="slide-over-{{ $name }}-title" class="text-lg font-bold text-gray-900">
                            {{ $title }}
                        </h2>
                        @if($subtitle)
                            <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
                        @endif
                    </div>
                    @if($closeable)
                        <button
                            @click="close()"
                            type="button"
                            class="text-gray-400 hover:text-gray-600 transition-all duration-200 hover:scale-110 hover:bg-gray-100 rounded-lg p-1"
                        >
                            <span class="sr-only">Fermer</span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    @endif
                </div>

                <!-- Body -->
                <div class="flex-1 overflow-y-auto px-6 py-5">
                    {{ $slot }}
                </div>

                <!-- Footer -->
                @isset($footer)
                    <div class="flex-shrink-0 border-t border-gray-200 px-6 py-4 bg-gray-50">
                        {{ $footer }}
                    </div>
                @endisset
            </div>
        </div>
    </div>
</div>
