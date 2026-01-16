@props([
    'isEditing' => false,
    'submitAction' => 'save',
    'cancelAction' => 'closeModal',
    'formPrefix' => 'form'
])

<div class="p-6">
    <!-- Contenu du formulaire en 2 colonnes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Colonne Gauche -->
        <div class="space-y-5">
            <!-- Informations principales -->
            <div class="bg-gray-50 rounded-xl p-4">
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Informations principales
                </h4>
                <div class="space-y-4">
                    <div>
                        <label for="storeName" class="block text-sm font-medium text-gray-700 mb-1">
                            Nom du magasin <span class="text-red-500">*</span>
                        </label>
                        <x-form.input
                            type="text"
                            wire:model="{{ $formPrefix }}.name"
                            id="storeName"
                            placeholder="Ex: Magasin Central"
                            required
                        />
                        <x-form.input-error for="{{ $formPrefix }}.name" />
                    </div>
                </div>
            </div>

            <!-- Contact -->
            <div class="bg-gray-50 rounded-xl p-4">
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Contact
                </h4>
                <div class="space-y-4">
                    <div>
                        <label for="storeEmail" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <x-form.input
                                type="email"
                                wire:model="{{ $formPrefix }}.email"
                                id="storeEmail"
                                placeholder="contact@magasin.com"
                                class="pl-10"
                            />
                        </div>
                        <x-form.input-error for="{{ $formPrefix }}.email" />
                    </div>
                    <div>
                        <label for="storePhone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <x-form.input
                                type="text"
                                wire:model="{{ $formPrefix }}.phone"
                                id="storePhone"
                                placeholder="+243 XXX XXX XXX"
                                class="pl-10"
                            />
                        </div>
                        <x-form.input-error for="{{ $formPrefix }}.phone" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne Droite -->
        <div class="space-y-5">
            <!-- Localisation -->
            <div class="bg-gray-50 rounded-xl p-4">
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Localisation
                </h4>
                <div class="space-y-4">
                    <div>
                        <label for="storeAddress" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                        <textarea wire:model="{{ $formPrefix }}.address" id="storeAddress" rows="4"
                            class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm resize-none"
                            placeholder="Numéro, rue, avenue, ville..."></textarea>
                        <x-form.input-error for="{{ $formPrefix }}.address" />
                    </div>
                </div>
            </div>

            <!-- Options -->
            <div class="bg-gray-50 rounded-xl p-4">
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    Options
                </h4>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 p-3 bg-white rounded-lg border-2 cursor-pointer transition-all"
                        :class="$wire.{{ $formPrefix }}.is_main ? 'border-indigo-400 bg-indigo-50' : 'border-gray-200 hover:border-indigo-200 hover:bg-indigo-50/50'">
                        <div class="flex-shrink-0">
                            <input type="checkbox" wire:model="{{ $formPrefix }}.is_main" id="storeIsMain"
                                class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="block text-sm font-medium text-gray-900">Magasin principal</span>
                            <span class="block text-xs text-gray-500">Siège de l'organisation</span>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full"
                                :class="$wire.{{ $formPrefix }}.is_main ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-400'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                            </span>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-3 bg-white rounded-lg border-2 cursor-pointer transition-all"
                        :class="$wire.{{ $formPrefix }}.is_active ? 'border-green-400 bg-green-50' : 'border-gray-200 hover:border-green-200 hover:bg-green-50/50'">
                        <div class="flex-shrink-0">
                            <input type="checkbox" wire:model="{{ $formPrefix }}.is_active" id="storeIsActive"
                                class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="block text-sm font-medium text-gray-900">Actif</span>
                            <span class="block text-xs text-gray-500">Visible et opérationnel</span>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full"
                                :class="$wire.{{ $formPrefix }}.is_active ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
