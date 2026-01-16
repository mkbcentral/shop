<div>
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <div class="mb-4 flex justify-between items-center">
        <h3 class="text-lg font-semibold">Attributs du Type de Produit</h3>
        @if(!$showForm)
            <button wire:click="addAttribute" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                <i class="fas fa-plus mr-1"></i>Ajouter un Attribut
            </button>
        @endif
    </div>

    @if($showForm)
        <div class="bg-gray-50 border rounded-lg p-6 mb-6">
            <h4 class="text-md font-semibold mb-4">
                {{ $editingIndex !== null ? 'Modifier l\'attribut' : 'Nouvel attribut' }}
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="name" class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200">
                    @error('name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="code" class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200">
                    <p class="text-xs text-gray-500 mt-1">Ex: size, color, weight (sans espaces)</p>
                    @error('code') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Type <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="type" class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200">
                        <option value="text">Texte</option>
                        <option value="number">Nombre</option>
                        <option value="select">Liste déroulante</option>
                        <option value="boolean">Oui/Non</option>
                        <option value="date">Date</option>
                        <option value="color">Couleur</option>
                    </select>
                    @error('type') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Unité
                    </label>
                    <input type="text" wire:model="unit" class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200">
                    <p class="text-xs text-gray-500 mt-1">Ex: kg, L, cm</p>
                </div>

                @if($type === 'select')
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Options (une par ligne)
                        </label>
                        <textarea wire:model="optionsString" rows="4" class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200 resize-none"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Ex: XS, S, M, L, XL (une option par ligne)</p>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Valeur par défaut
                    </label>
                    <input type="text" wire:model="default_value" class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200">
                </div>

                <div class="md:col-span-2">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="is_required" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Obligatoire</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" wire:model="is_variant_attribute" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Attribut variant</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" wire:model="is_filterable" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Filtrable</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" wire:model="is_visible" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Visible</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" wire:click="cancelForm" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded text-sm">
                    Annuler
                </button>
                <button type="button" wire:click="saveAttribute" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                    Enregistrer
                </button>
            </div>
        </div>
    @endif

    @if(count($attributes) > 0)
        <div class="space-y-3">
            @foreach($attributes as $index => $attribute)
                <div class="border rounded-lg p-4 bg-white hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <h4 class="font-semibold text-gray-900">{{ $attribute['name'] }}</h4>
                                <span class="ml-2 text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded">{{ $attribute['code'] }}</span>
                                <span class="ml-2 text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">{{ ucfirst($attribute['type']) }}</span>
                                @if($attribute['is_variant_attribute'] ?? false)
                                    <span class="ml-2 text-xs bg-green-100 text-green-700 px-2 py-1 rounded">Variant</span>
                                @endif
                            </div>

                            <div class="text-sm text-gray-600">
                                @if(!empty($attribute['unit']))
                                    <span class="mr-3"><strong>Unité:</strong> {{ $attribute['unit'] }}</span>
                                @endif
                                @if(!empty($attribute['options']))
                                    <span class="mr-3"><strong>Options:</strong> {{ implode(', ', array_slice($attribute['options'], 0, 5)) }}{{ count($attribute['options']) > 5 ? '...' : '' }}</span>
                                @endif
                            </div>

                            <div class="flex items-center mt-2 space-x-3 text-xs">
                                @if($attribute['is_required'] ?? false)
                                    <span class="text-red-600"><i class="fas fa-asterisk mr-1"></i>Obligatoire</span>
                                @endif
                                @if($attribute['is_filterable'] ?? false)
                                    <span class="text-blue-600"><i class="fas fa-filter mr-1"></i>Filtrable</span>
                                @endif
                                @if($attribute['is_visible'] ?? true)
                                    <span class="text-green-600"><i class="fas fa-eye mr-1"></i>Visible</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center space-x-2 ml-4">
                            <button wire:click="moveUp({{ $index }})" class="text-gray-600 hover:text-gray-800" {{ $index === 0 ? 'disabled' : '' }}>
                                <i class="fas fa-arrow-up"></i>
                            </button>
                            <button wire:click="moveDown({{ $index }})" class="text-gray-600 hover:text-gray-800" {{ $index === count($attributes) - 1 ? 'disabled' : '' }}>
                                <i class="fas fa-arrow-down"></i>
                            </button>
                            <button wire:click="editAttribute({{ $index }})" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="deleteAttribute({{ $index }})" class="text-red-600 hover:text-red-800" onclick="return confirm('Supprimer cet attribut ?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        @if(!$showForm)
            <div class="text-center py-8 bg-gray-50 rounded-lg">
                <p class="text-gray-500">Aucun attribut défini.</p>
                <button wire:click="addAttribute" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                    Ajouter le premier attribut
                </button>
            </div>
        @endif
    @endif
</div>
