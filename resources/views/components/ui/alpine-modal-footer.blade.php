@props([
    'submitText' => 'Enregistrer',
    'editSubmitText' => 'Mettre à jour',
    'cancelText' => 'Annuler',
    'loadingText' => 'Enregistrement...',
    'target' => 'save',
])

<div {{ $attributes->merge(['class' => 'flex-shrink-0 flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl']) }}>
    <button type="button" @click="showModal = false"
        class="inline-flex items-center px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
        {{ $cancelText }}
    </button>
    <button type="submit" wire:loading.attr="disabled"
        class="inline-flex items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 transition">
        <svg wire:loading.remove wire:target="{{ $target }}" class="w-5 h-5 mr-2" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 13l4 4L19 7" />
        </svg>
        <svg wire:loading wire:target="{{ $target }}" class="animate-spin w-5 h-5 mr-2"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
        </svg>
        <span wire:loading.remove wire:target="{{ $target }}" x-text="isEditing ? '{{ $editSubmitText }}' : '{{ $submitText }}'"></span>
        <span wire:loading wire:target="{{ $target }}">{{ $loadingText }}</span>
    </button>
</div>
