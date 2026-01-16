<div class="flex flex-col h-full"
    x-data="{ }"
    @focus-search-input.window="$refs.searchInput.focus()">
    <!-- Search & Filters -->
    <div class="bg-white/80 backdrop-blur-sm border-b shadow-sm px-4 py-3 flex-shrink-0">
        <div class="grid grid-cols-12 gap-3">
            <div class="col-span-7">
                <div class="relative group">
                    <input wire:model.live.debounce.300ms="search" type="text"
                        x-ref="searchInput"
                        placeholder="Rechercher un produit..."
                        class="w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200 shadow-sm text-sm">
                    <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <div wire:loading wire:target="search" class="absolute right-3 top-3">
                        <svg class="animate-spin h-4 w-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="col-span-5">
                <select wire:model.live="categoryFilter"
                    class="w-full px-3 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200 shadow-sm bg-white appearance-none cursor-pointer text-sm"
                    style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M5%207l5%205%205-5%22%20stroke%3D%22%23666%22%20stroke-width%3D%222%22%20fill%3D%22none%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 0.75rem center; padding-right: 2.5rem;">
                    <option value="">🏷️ Toutes les catégories</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="flex-1 overflow-y-auto p-4">
        <div class="grid gap-4" style="grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));">
            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $product->variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div wire:key="variant-card-<?php echo e($variant->id); ?>">
                        <!-- Product Card -->
                        <button wire:click="selectProduct(<?php echo e($variant->id); ?>)"
                            wire:loading.class="opacity-50 cursor-wait"
                            wire:loading.attr="disabled"
                            wire:target="selectProduct(<?php echo e($variant->id); ?>)"
                            class="w-full bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden group border-2 border-transparent hover:border-indigo-400 transform hover:-translate-y-2">
                            <div class="aspect-square bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 flex items-center justify-center p-6 relative overflow-hidden">
                                <!--[if BLOCK]><![endif]--><?php if($product->image): ?>
                                    <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>"
                                        class="w-full h-full object-cover rounded-xl group-hover:scale-110 transition-transform duration-300">
                                <?php else: ?>
                                    <svg class="w-28 h-28 text-gray-300 group-hover:text-indigo-400 group-hover:scale-110 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <div class="absolute top-3 right-3">
                                    <?php
                                        $storeStock = $variant->storeStocks->first()?->quantity ?? $variant->stock_quantity;
                                    ?>
                                    <span class="px-3 py-1.5 rounded-full text-sm font-bold shadow-lg backdrop-blur-sm <?php echo e($storeStock > 10 ? 'bg-green-500/90 text-white' : ($storeStock > 0 ? 'bg-orange-500/90 text-white' : 'bg-red-500/90 text-white')); ?>">
                                        <?php echo e($storeStock); ?>

                                    </span>
                                </div>
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold text-base text-gray-900 truncate mb-2 group-hover:text-indigo-600 transition-colors"><?php echo e($product->name); ?></h3>
                                <!--[if BLOCK]><![endif]--><?php if($variant->size || $variant->color): ?>
                                    <p class="text-sm text-gray-500 mb-3 flex items-center gap-2">
                                        <!--[if BLOCK]><![endif]--><?php if($variant->size): ?>
                                            <span class="px-2.5 py-1 bg-gray-100 rounded-md font-medium"><?php echo e($variant->size); ?></span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <!--[if BLOCK]><![endif]--><?php if($variant->color): ?>
                                            <span class="px-2.5 py-1 bg-gray-100 rounded-md font-medium"><?php echo e($variant->color); ?></span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </p>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <div class="flex items-center justify-between">
                                    <span class="text-xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">
                                        <?php echo e(number_format($product->price, 0, ',', ' ')); ?>

                                    </span>
                                    <span class="text-sm font-semibold text-gray-500"><?php echo e(current_currency()); ?></span>
                                </div>
                            </div>
                        </button>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full text-center py-16">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <p class="text-lg font-semibold text-gray-700 mb-1">Aucun produit trouvé</p>
                    <p class="text-sm text-gray-500">Essayez de modifier vos critères de recherche</p>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <!--[if BLOCK]><![endif]--><?php if($products->hasPages()): ?>
            <div class="mt-6">
                <?php echo e($products->links()); ?>

            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div>
<?php /**PATH D:\stk\stk-back\resources\views/livewire/pos/components/pos-product-grid.blade.php ENDPATH**/ ?>