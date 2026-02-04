@props([
    'name' => 'modal',
    'maxWidth' => 'lg',
    'title' => '',
    'editTitle' => '',
    'icon' => null,
    'iconBg' => 'from-indigo-500 to-purple-600',
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
];
$maxWidthClass = $maxWidthClasses[$maxWidth] ?? $maxWidthClasses['lg'];
@endphp

{{--
    Ce composant modal est géré uniquement par Alpine.js pour des performances optimales.

    Usage:
    1. Ajouter x-data sur le parent avec: { showModal: false, isEditing: false }
    2. Écouter les événements: @open-XXX-modal.window="showModal = true" @close-XXX-modal.window="showModal = false; isEditing = false"
    3. Bouton création: @click="isEditing = false; showModal = true; $wire.openCreateModal()"
    4. Bouton édition: @click="$wire.openEditModal(id)" (Livewire dispatch 'open-XXX-modal' après chargement)
--}}

<div x-show="showModal"
     x-cloak
     x-on:keydown.escape.window="showModal = false"
     x-init="$watch('showModal', value => { document.body.style.overflow = value ? 'hidden' : '' })"
     class="fixed inset-0 z-50 overflow-y-auto"
     aria-labelledby="modal-{{ $name }}-title"
     role="dialog"
     aria-modal="true">

    <!-- Backdrop -->
    <div @click="showModal = false"
         x-show="showModal"
         x-transition.opacity.duration.100ms
         class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm">
    </div>

    <!-- Modal Container -->
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div x-show="showModal"
             @click.stop
             x-transition:enter="ease-out duration-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-75"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-white rounded-2xl shadow-2xl transform w-full {{ $maxWidthClass }} flex flex-col pointer-events-auto"
             style="max-height: 90vh;">

            <!-- Modal Header -->
            <div class="flex-shrink-0 flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white rounded-t-2xl">
                <div class="flex items-center space-x-3">
                    @if($icon)
                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br {{ $iconBg }} rounded-lg flex items-center justify-center">
                        {{ $icon }}
                    </div>
                    @endif
                    <h3 class="text-xl font-bold text-gray-900"
                        id="modal-{{ $name }}-title">
                        <span x-show="!isEditing">{{ $title ?: 'Nouveau' }}</span>
                        <span x-show="isEditing">{{ $editTitle ?: 'Modifier' }}</span>
                    </h3>
                </div>
                <button @click="showModal = false" type="button"
                    class="rounded-lg p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content (slot) -->
            {{ $slot }}
        </div>
    </div>
</div>
