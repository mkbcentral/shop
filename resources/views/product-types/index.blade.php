<x-layouts.app>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Types de Produits') }}
            </h2>
            <a href="{{ route('product-types.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus mr-2"></i>Nouveau Type
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($productTypes->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500 text-lg mb-4">Aucun type de produit trouvé.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($productTypes as $productType)
                                <div class="border rounded-lg p-6 hover:shadow-lg transition-shadow {{ $productType->is_active ? 'bg-white' : 'bg-gray-50' }}">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-center">
                                            <span class="text-4xl mr-3">{{ $productType->icon ?? '📦' }}</span>
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900">
                                                    {{ $productType->name }}
                                                </h3>
                                                <span class="text-xs text-gray-500">{{ $productType->slug }}</span>
                                            </div>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full {{ $productType->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $productType->is_active ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </div>

                                    @if($productType->description)
                                        <p class="text-sm text-gray-600 mb-4">{{ Str::limit($productType->description, 100) }}</p>
                                    @endif

                                    <div class="grid grid-cols-2 gap-2 mb-4 text-xs">
                                        <div class="flex items-center {{ $productType->has_variants ? 'text-green-600' : 'text-gray-400' }}">
                                            <i class="fas fa-{{ $productType->has_variants ? 'check' : 'times' }} mr-1"></i>
                                            Variants
                                        </div>
                                        <div class="flex items-center {{ $productType->has_expiry_date ? 'text-green-600' : 'text-gray-400' }}">
                                            <i class="fas fa-{{ $productType->has_expiry_date ? 'check' : 'times' }} mr-1"></i>
                                            Expiration
                                        </div>
                                        <div class="flex items-center {{ $productType->has_weight ? 'text-green-600' : 'text-gray-400' }}">
                                            <i class="fas fa-{{ $productType->has_weight ? 'check' : 'times' }} mr-1"></i>
                                            Poids
                                        </div>
                                        <div class="flex items-center {{ $productType->has_dimensions ? 'text-green-600' : 'text-gray-400' }}">
                                            <i class="fas fa-{{ $productType->has_dimensions ? 'check' : 'times' }} mr-1"></i>
                                            Dimensions
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pt-4 border-t">
                                        <div class="text-sm text-gray-600">
                                            <span class="font-semibold">{{ $productType->products_count ?? 0 }}</span> produits
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('product-types.edit', $productType) }}" class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('product-types.destroy', $productType) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce type de produit ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800" {{ $productType->products_count > 0 ? 'disabled' : '' }}>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
