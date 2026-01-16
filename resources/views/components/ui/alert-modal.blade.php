{{--
    Alert/Info Modal Component

    Usage:
    <x-ui.alert-modal
        show="showSuccessModal"
        type="success"
        title="Opération réussie"
        message="L'élément a été créé avec succès."
        button-text="Fermer"
    />
--}}

@props([
    'name' => 'alertModal',
    'show' => false,
    'type' => 'info', // success, error, warning, info
    'title' => '',
    'message' => '',
    'buttonText' => 'Fermer',
    'onClose' => null,
    'autoClose' => false,
    'autoCloseDelay' => 3000,
])

@php
$typeConfig = [
    'success' => [
        'iconBg' => 'bg-green-100',
        'iconColor' => 'text-green-600',
        'buttonBg' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />',
    ],
    'error' => [
        'iconBg' => 'bg-red-100',
        'iconColor' => 'text-red-600',
        'buttonBg' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />',
    ],
    'warning' => [
        'iconBg' => 'bg-yellow-100',
        'iconColor' => 'text-yellow-600',
        'buttonBg' => 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
    ],
    'info' => [
        'iconBg' => 'bg-blue-100',
        'iconColor' => 'text-blue-600',
        'buttonBg' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
    ],
];
$config = $typeConfig[$type] ?? $typeConfig['info'];

$isLivewire = is_string($show) && !in_array($show, ['true', 'false']);
$closeAction = $onClose
    ? ($isLivewire ? "\$wire.{$onClose}()" : $onClose)
    : 'close()';
@endphp

<x-ui.modal
    :name="$name"
    :show="$show"
    max-width="sm"
    @if($autoClose)
        x-init="setTimeout(() => { {{ $closeAction }} }, {{ $autoCloseDelay }})"
    @endif
    {{ $attributes }}
>
    <div class="p-6 text-center">
        <!-- Animated Icon -->
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full {{ $config['iconBg'] }} mb-5"
             x-show="show"
             x-transition:enter="ease-out duration-500 delay-100"
             x-transition:enter-start="scale-0"
             x-transition:enter-end="scale-100">
            <svg class="h-8 w-8 {{ $config['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 x-show="show"
                 x-transition:enter="ease-out duration-300 delay-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100">
                {!! $config['icon'] !!}
            </svg>
        </div>

        <!-- Title -->
        <h3 class="text-xl font-bold text-gray-900 mb-2">
            {{ $title }}
        </h3>

        <!-- Message -->
        <p class="text-sm text-gray-600 mb-6">
            {{ $message }}
        </p>

        @if($slot->isNotEmpty())
            <div class="mb-6">
                {{ $slot }}
            </div>
        @endif

        <!-- Button -->
        <button
            type="button"
            @click="{{ $closeAction }}"
            class="w-full px-5 py-2.5 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2
                   {{ $config['buttonBg'] }}"
        >
            {{ $buttonText }}
        </button>
    </div>
</x-ui.modal>
