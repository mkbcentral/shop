{{--
    Legacy Delete Confirmation Modal
    Pure Alpine.js version - does NOT use Livewire entangle
    Use this for Alpine-controlled modals within x-data scope

    Usage: <x-delete-confirmation-modal
        show="showDeleteModal"
        item-name="itemName"
        item-type="ce produit"
        on-confirm="$wire.delete(); showDeleteModal = false"
        on-cancel="showDeleteModal = false"
    />
--}}
@props([
    'show' => 'showDeleteModal',
    'itemName' => '',
    'itemType' => 'élément',
    'onConfirm' => '',
    'onCancel' => '',
    'title' => 'Confirmer la suppression',
    'wireTarget' => 'delete',
])

<div x-show="{{ $show }}" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div x-show="{{ $show }}"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @if($onCancel)
            @click="{{ $onCancel }}"
        @else
            @click="{{ $show }} = false"
        @endif
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>

    <!-- Modal -->
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div x-show="{{ $show }}"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
            @click.stop
            @keydown.escape.window="{{ $show }} = false"
            class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md pointer-events-auto p-6">

            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-100 mb-5">
                <svg class="h-7 w-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <!-- Content -->
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $title }}</h3>
                <p class="text-sm text-gray-600 mb-3">Voulez-vous vraiment supprimer {{ $itemType }} ?</p>
                @if($itemName)
                    <p class="text-base font-bold text-red-600" x-text="{{ $itemName }}"></p>
                @endif
                <p class="text-xs text-gray-500 mt-2">Cette action est irréversible.</p>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 justify-center mt-6">
                <button type="button"
                    wire:loading.attr="disabled"
                    wire:target="{{ $wireTarget }}"
                    @if($onCancel)
                        @click="{{ $onCancel }}"
                    @else
                        @click="{{ $show }} = false"
                    @endif
                    class="px-5 py-2.5 bg-white text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    Annuler
                </button>
                <button type="button"
                    wire:loading.attr="disabled"
                    wire:target="{{ $wireTarget }}"
                    @if($onConfirm)
                        @click="{{ $onConfirm }}"
                    @endif
                    class="inline-flex items-center px-5 py-2.5 text-white font-medium rounded-lg bg-red-600 hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg wire:loading wire:target="{{ $wireTarget }}" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="{{ $wireTarget }}">Supprimer</span>
                    <span wire:loading wire:target="{{ $wireTarget }}">Suppression...</span>
                </button>
            </div>
        </div>
    </div>
</div>
