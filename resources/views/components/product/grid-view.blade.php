@props(['products'])

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-6">
    @forelse($products as $product)
        <x-product-card :product="$product" />
    @empty
        <div class="col-span-full text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun produit</h3>
            <p class="mt-1 text-sm text-gray-500">Commencez par créer un nouveau produit.</p>
        </div>
    @endforelse
</div>
