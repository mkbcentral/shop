<div>
    <!-- Modal -->
    <x-modal :show="$showModal" @close="closeModal" maxWidth="2xl">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Modifier le Magasin</h3>
                <button @click="$dispatch('close')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit="update">
                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nom du magasin <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="edit_name" wire:model="name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Code -->
                    <div>
                        <label for="edit_code" class="block text-sm font-medium text-gray-700 mb-1">
                            Code
                        </label>
                        <input type="text" id="edit_code" wire:model="code"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address & City -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_address" class="block text-sm font-medium text-gray-700 mb-1">
                                Adresse
                            </label>
                            <input type="text" id="edit_address" wire:model="address"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="edit_city" class="block text-sm font-medium text-gray-700 mb-1">
                                Ville
                            </label>
                            <input type="text" id="edit_city" wire:model="city"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Phone & Email -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_phone" class="block text-sm font-medium text-gray-700 mb-1">
                                Téléphone
                            </label>
                            <input type="text" id="edit_phone" wire:model="phone"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email
                            </label>
                            <input type="email" id="edit_email" wire:model="email"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-1">
                            Description
                        </label>
                        <textarea id="edit_description" wire:model="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Checkboxes -->
                    <div class="space-y-3 pt-2 border-t border-gray-200">
                        <div class="flex items-center">
                            <input type="checkbox" id="edit_is_main" wire:model="is_main"
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="edit_is_main" class="ml-2 block text-sm text-gray-900">
                                Magasin principal
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="edit_is_active" wire:model="is_active"
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="edit_is_active" class="ml-2 block text-sm text-gray-900">
                                Actif
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" @click="closeModal"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Annuler
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</div>
