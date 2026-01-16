@props([
    'show' => false,
    'maxWidth' => '2xl',
    'title' => null,
    'showHeader' => true,
    'headerGradient' => 'from-indigo-600 to-purple-600',
    'closeable' => true,
    'name' => 'showModal',
    'showBackdrop' => true,
])

@php
$maxWidthClass = [
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
][$maxWidth];
@endphp

@if($$name ?? false)
<div
    x-data="{ show: true }"
    x-init="document.body.style.overflow = 'hidden'"
    x-on:keydown.escape.window="$wire.set('{{ $name }}', false)"
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
>
    <!-- Backdrop -->
    @if($showBackdrop)
    <div
        x-on:click="$wire.set('{{ $name }}', false)"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"
    ></div>
    @endif

    <!-- Modal Container -->
    <div class="flex min-h-full items-center justify-center">
        <div
            x-on:click.stop
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative bg-white rounded-2xl overflow-hidden shadow-2xl transform transition-all w-full {{ $maxWidthClass }}"
        >
            @if($showHeader)
                <!-- Header -->
                <div class="bg-gradient-to-r {{ $headerGradient }} px-6 py-4 flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-white">
                        @if($title)
                            {{ $title }}
                        @elseif(isset($header))
                            {{ $header }}
                        @endif
                    </h3>

                    @if($closeable)
                        <button
                            type="button"
                            x-on:click="$wire.set('{{ $name }}', false)"
                            class="text-white/80 hover:text-white transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>
            @endif

            <!-- Body -->
            <div>
                {{ $slot }}
            </div>

            @isset($footer)
                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>
@endif
