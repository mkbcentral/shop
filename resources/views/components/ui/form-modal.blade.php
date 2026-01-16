{{--
    Form Modal Component

    Usage Livewire:
    <x-ui.form-modal
        show="showModal"
        title="Créer un élément"
        subtitle="Remplissez les informations"
        :icon-svg="'<svg>...</svg>'"
        submit-text="Enregistrer"
        :on-submit="'save'"
        :on-close="'closeModal'"
    >
        <!-- Form fields here -->
    </x-ui.form-modal>

    Usage Alpine:
    <x-ui.form-modal
        :show="false"
        x-model="isOpen"
        title="Éditer"
        :on-submit="'handleSubmit()'"
    >
        <!-- Form fields -->
    </x-ui.form-modal>
--}}

@props([
    'name' => 'formModal',
    'show' => false,
    'maxWidth' => '2xl',
    'title' => '',
    'subtitle' => null,
    'iconSvg' => null,
    'iconBg' => 'from-indigo-500 to-purple-600',
    'submitText' => 'Enregistrer',
    'cancelText' => 'Annuler',
    'onSubmit' => null,
    'onClose' => null,
    'loading' => true,
    'closeable' => true,
    'closeOnClickOutside' => true,
])

@php
$isLivewire = is_string($show) && !in_array($show, ['true', 'false']);
$closeAction = $onClose
    ? ($isLivewire ? "\$wire.{$onClose}()" : $onClose)
    : 'close()';
$submitAction = $onSubmit
    ? ($isLivewire ? "\$wire.{$onSubmit}()" : $onSubmit)
    : '';

// Default icon if none provided
$defaultIcon = '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
</svg>';
$icon = $iconSvg ?? $defaultIcon;
@endphp

<x-ui.modal
    :name="$name"
    :show="$show"
    :max-width="$maxWidth"
    :closeable="$closeable"
    :close-on-click-outside="$closeOnClickOutside"
    {{ $attributes->except(['class']) }}
>
    <!-- Header -->
    <x-ui.modal-header
        :title="$title"
        :subtitle="$subtitle"
        :icon-bg="$iconBg"
        :closeable="$closeable"
    >
        <x-slot:icon>
            {!! $icon !!}
        </x-slot:icon>
    </x-ui.modal-header>

    <!-- Form -->
    <form
        @if($onSubmit)
            @if($isLivewire)
                wire:submit="{{ $onSubmit }}"
            @else
                @submit.prevent="{{ $submitAction }}"
            @endif
        @endif
        class="flex flex-col flex-1 min-h-0"
    >
        <!-- Body -->
        <x-ui.modal-body {{ $attributes->only(['class']) }}>
            {{ $slot }}
        </x-ui.modal-body>

        <!-- Footer -->
        <x-ui.modal-footer>
            @isset($footerLeft)
                <div class="flex-1">
                    {{ $footerLeft }}
                </div>
            @endisset

            <div class="flex items-center gap-3">
                <button
                    type="button"
                    @click="{{ $closeAction }}"
                    class="px-5 py-2.5 bg-white text-gray-700 font-medium rounded-lg border border-gray-300
                           hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2"
                >
                    {{ $cancelText }}
                </button>

                <button
                    type="submit"
                    @if($loading && $isLivewire && $onSubmit)
                        wire:loading.attr="disabled"
                        wire:target="{{ $onSubmit }}"
                    @endif
                    class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700
                           text-white font-medium rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
                           disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    @if($loading && $isLivewire && $onSubmit)
                        <span wire:loading.remove wire:target="{{ $onSubmit }}" class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $submitText }}
                        </span>
                        <span wire:loading wire:target="{{ $onSubmit }}" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Enregistrement...
                        </span>
                    @else
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $submitText }}
                        </span>
                    @endif
                </button>
            </div>
        </x-ui.modal-footer>
    </form>
</x-ui.modal>
