{{--
    Confirmation Modal Component

    Usage:
    <x-ui.confirm-modal
        name="showDeleteModal"
        show="showDeleteModal"
        type="danger"
        title="Supprimer l'élément"
        message="Êtes-vous sûr de vouloir supprimer cet élément ?"
        confirm-text="Supprimer"
        cancel-text="Annuler"
        :on-confirm="'deleteItem'"
    />

    Or with Alpine.js:
    <x-ui.confirm-modal
        name="deleteModal"
        :show="false"
        x-model="showDeleteModal"
        type="warning"
        title="Attention"
        message="Cette action est irréversible."
        :on-confirm="'handleConfirm()'"
    />
--}}

@props([
    'name' => 'confirmModal',
    'show' => false,
    'type' => 'danger', // danger, warning, info, success
    'title' => 'Confirmation',
    'message' => 'Êtes-vous sûr de vouloir continuer ?',
    'details' => null,
    'detailsIsVariable' => false, // Si true, details est une variable Alpine.js à utiliser avec x-text
    'confirmText' => 'Confirmer',
    'cancelText' => 'Annuler',
    'onConfirm' => null,
    'onCancel' => null,
    'loading' => false,
])

@php
$typeConfig = [
    'danger' => [
        'iconBg' => 'bg-red-100',
        'iconColor' => 'text-red-600',
        'confirmBg' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
    ],
    'warning' => [
        'iconBg' => 'bg-yellow-100',
        'iconColor' => 'text-yellow-600',
        'confirmBg' => 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
    ],
    'info' => [
        'iconBg' => 'bg-blue-100',
        'iconColor' => 'text-blue-600',
        'confirmBg' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
    ],
    'success' => [
        'iconBg' => 'bg-green-100',
        'iconColor' => 'text-green-600',
        'confirmBg' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
    ],
];
$config = $typeConfig[$type] ?? $typeConfig['danger'];

$isLivewire = is_string($show) && !in_array($show, ['true', 'false']);
$confirmAction = $onConfirm
    ? ($isLivewire ? "\$wire.{$onConfirm}" : $onConfirm)
    : 'close()';
$cancelAction = $onCancel
    ? ($isLivewire ? "\$wire.{$onCancel}" : $onCancel)
    : 'close()';
@endphp

<x-ui.modal
    :name="$name"
    :show="$show"
    max-width="md"
    {{ $attributes }}
>
    <div class="p-6">
        <!-- Icon -->
        <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full {{ $config['iconBg'] }} mb-5">
            <svg class="h-7 w-7 {{ $config['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $config['icon'] !!}
            </svg>
        </div>

        <!-- Content -->
        <div class="text-center">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                {{ $title }}
            </h3>
            <p class="text-sm text-gray-600 mb-3">
                {{ $message }}
            </p>
            @if($details)
                @if($detailsIsVariable)
                    <p class="text-base font-bold {{ $type === 'danger' ? 'text-red-600' : 'text-gray-900' }}" x-text="{{ $details }}"></p>
                @else
                    <p class="text-base font-bold {{ $type === 'danger' ? 'text-red-600' : 'text-gray-900' }}">
                        {{ $details }}
                    </p>
                @endif
                <p class="text-xs text-gray-500 mt-2">Cette action est irréversible.</p>
            @endif
            @if($slot->isNotEmpty())
                <div class="mt-4">
                    {{ $slot }}
                </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="flex gap-3 justify-center mt-6">
            <button
                type="button"
                @click="{{ $cancelAction }}"
                class="px-5 py-2.5 bg-white text-gray-700 font-medium rounded-lg border border-gray-300
                       hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2"
            >
                {{ $cancelText }}
            </button>
            <button
                type="button"
                @click="{{ $confirmAction }}"
                @if($loading)
                    wire:loading.attr="disabled"
                    wire:target="{{ $onConfirm }}"
                @endif
                class="px-5 py-2.5 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2
                       {{ $config['confirmBg'] }} disabled:opacity-50 disabled:cursor-not-allowed"
            >
                @if($loading)
                    <span wire:loading.remove wire:target="{{ $onConfirm }}">{{ $confirmText }}</span>
                    <span wire:loading wire:target="{{ $onConfirm }}" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Chargement...
                    </span>
                @else
                    {{ $confirmText }}
                @endif
            </button>
        </div>
    </div>
</x-ui.modal>
