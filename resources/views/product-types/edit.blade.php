<x-layouts.app>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Modifier le Type de Produit') }} : {{ $productType->name }}
            </h2>
            <a href="{{ route('product-types.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Informations de base -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Informations de base</h3>

                    <form action="{{ route('product-types.update', $productType) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nom <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $productType->name) }}" required
                                    class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Slug
                                </label>
                                <input type="text" name="slug" id="slug" value="{{ old('slug', $productType->slug) }}"
                                    class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200">
                                @error('slug')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="icon" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Icône (emoji)
                                </label>
                                <input type="text" name="icon" id="icon" value="{{ old('icon', $productType->icon) }}" maxlength="10"
                                    class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200">
                                @error('icon')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="display_order" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Ordre d'affichage
                                </label>
                                <input type="number" name="display_order" id="display_order" value="{{ old('display_order', $productType->display_order) }}"
                                    class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="description" id="description" rows="3"
                                class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200 resize-none">{{ old('description', $productType->description) }}</textarea>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Fonctionnalités
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="has_variants" value="1" {{ old('has_variants', $productType->has_variants) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Support des variants</span>
                                </label>

                                <label class="flex items-center">
                                    <input type="checkbox" name="has_expiry_date" value="1" {{ old('has_expiry_date', $productType->has_expiry_date) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Date d'expiration</span>
                                </label>

                                <label class="flex items-center">
                                    <input type="checkbox" name="has_weight" value="1" {{ old('has_weight', $productType->has_weight) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Gestion du poids</span>
                                </label>

                                <label class="flex items-center">
                                    <input type="checkbox" name="has_dimensions" value="1" {{ old('has_dimensions', $productType->has_dimensions) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Dimensions (L x l x h)</span>
                                </label>

                                <label class="flex items-center">
                                    <input type="checkbox" name="has_serial_number" value="1" {{ old('has_serial_number', $productType->has_serial_number) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Numéro de série</span>
                                </label>

                                <label class="flex items-center">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $productType->is_active) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Actif</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Gestion des attributs -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @livewire('product-type.attribute-manager', ['productTypeId' => $productType->id])
                </div>
            </div>

            <!-- Actions de suppression -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4 text-red-600">Zone de danger</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        La suppression de ce type de produit est irréversible. Tous les attributs associés seront également supprimés.
                    </p>
                    <form action="{{ route('product-types.destroy', $productType) }}" method="POST" onsubmit="return confirm('Êtes-vous absolument sûr de vouloir supprimer ce type de produit ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                            {{ $productType->products()->count() > 0 || $productType->categories()->count() > 0 ? 'disabled title="Ce type de produit ne peut pas être supprimé car il est utilisé"' : '' }}>
                            <i class="fas fa-trash mr-2"></i>Supprimer ce type de produit
                        </button>
                        @if($productType->products()->count() > 0 || $productType->categories()->count() > 0)
                            <p class="text-sm text-red-600 mt-2">
                                Ce type de produit ne peut pas être supprimé car il a {{ $productType->products()->count() }} produits et {{ $productType->categories()->count() }} catégories associés.
                            </p>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-generate slug from name
        document.getElementById('name').addEventListener('input', function(e) {
            const slug = document.getElementById('slug');
            if (!slug.value || slug.value === '') {
                slug.value = e.target.value
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }
        });
    </script>
    @endpush
</x-layouts.app>
