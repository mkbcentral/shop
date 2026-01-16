<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['variants']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['variants']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Produit
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        SKU
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Stock
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Seuil
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Valeur Unitaire
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Valeur Totale
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Statut
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition">
                        <!-- Product Name -->
                        <td class="px-6 py-4">
                            <div>
                                <p class="text-sm font-medium text-gray-900"><?php echo e($variant->product->name); ?></p>
                                <!--[if BLOCK]><![endif]--><?php if($variant->size || $variant->color): ?>
                                    <p class="text-xs text-gray-500">
                                        <?php echo e($variant->getVariantName()); ?>

                                    </p>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <!--[if BLOCK]><![endif]--><?php if($variant->product->category): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 mt-1">
                                        <?php echo e($variant->product->category->name); ?>

                                    </span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </td>

                        <!-- SKU -->
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-900 font-mono"><?php echo e($variant->sku); ?></p>
                            <!--[if BLOCK]><![endif]--><?php if($variant->barcode): ?>
                                <p class="text-xs text-gray-500 font-mono"><?php echo e($variant->barcode); ?></p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </td>

                        <!-- Stock Quantity -->
                        <td class="px-6 py-4 text-center">
                            <span class="text-lg font-bold <?php echo e($variant->stock_quantity > $variant->low_stock_threshold ? 'text-green-600' : ($variant->stock_quantity > 0 ? 'text-orange-600' : 'text-red-600')); ?>">
                                <?php echo e($variant->stock_quantity); ?>

                            </span>
                        </td>

                        <!-- Threshold -->
                        <td class="px-6 py-4 text-center">
                            <p class="text-sm text-gray-600"><?php echo e($variant->low_stock_threshold); ?></p>
                            <!--[if BLOCK]><![endif]--><?php if($variant->min_stock_threshold > 0): ?>
                                <p class="text-xs text-gray-400">(Min: <?php echo e($variant->min_stock_threshold); ?>)</p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </td>

                        <!-- Unit Value -->
                        <td class="px-6 py-4 text-right">
                            <p class="text-sm font-medium text-gray-900">
                                <?php echo e(number_format($variant->product->cost_price ?? 0, 0, ',', ' ')); ?> CDF
                            </p>
                            <!--[if BLOCK]><![endif]--><?php if($variant->product->selling_price): ?>
                                <p class="text-xs text-gray-500">
                                    PV: <?php echo e(number_format($variant->product->selling_price, 0, ',', ' ')); ?> CDF
                                </p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </td>

                        <!-- Total Value -->
                        <td class="px-6 py-4 text-right">
                            <p class="text-sm font-bold text-gray-900">
                                <?php echo e(number_format($variant->stock_quantity * ($variant->product->cost_price ?? 0), 0, ',', ' ')); ?> CDF
                            </p>
                        </td>

                        <!-- Status Badge -->
                        <td class="px-6 py-4 text-center">
                            <!--[if BLOCK]><![endif]--><?php if($variant->stock_quantity <= 0): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Rupture
                                </span>
                            <?php elseif($variant->isLowStock()): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    Stock faible
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    En stock
                                </span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <?php if (isset($component)) { $__componentOriginal265ea4f02634350cff010af59a980e8e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal265ea4f02634350cff010af59a980e8e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.actions-dropdown','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('actions-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                <?php if (isset($component)) { $__componentOriginal6b1d0d55421798f4a1c7b596bea6c022 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6b1d0d55421798f4a1c7b596bea6c022 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown-item','data' => ['wire:click' => 'openAdjustModal('.e($variant->id).')','icon' => 'pencil']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'openAdjustModal('.e($variant->id).')','icon' => 'pencil']); ?>
                                    Ajuster le stock
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6b1d0d55421798f4a1c7b596bea6c022)): ?>
<?php $attributes = $__attributesOriginal6b1d0d55421798f4a1c7b596bea6c022; ?>
<?php unset($__attributesOriginal6b1d0d55421798f4a1c7b596bea6c022); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6b1d0d55421798f4a1c7b596bea6c022)): ?>
<?php $component = $__componentOriginal6b1d0d55421798f4a1c7b596bea6c022; ?>
<?php unset($__componentOriginal6b1d0d55421798f4a1c7b596bea6c022); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal6b1d0d55421798f4a1c7b596bea6c022 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6b1d0d55421798f4a1c7b596bea6c022 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown-item','data' => ['wire:click' => 'viewHistory('.e($variant->id).')','icon' => 'clock']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'viewHistory('.e($variant->id).')','icon' => 'clock']); ?>
                                    Voir l'historique
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6b1d0d55421798f4a1c7b596bea6c022)): ?>
<?php $attributes = $__attributesOriginal6b1d0d55421798f4a1c7b596bea6c022; ?>
<?php unset($__attributesOriginal6b1d0d55421798f4a1c7b596bea6c022); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6b1d0d55421798f4a1c7b596bea6c022)): ?>
<?php $component = $__componentOriginal6b1d0d55421798f4a1c7b596bea6c022; ?>
<?php unset($__componentOriginal6b1d0d55421798f4a1c7b596bea6c022); ?>
<?php endif; ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal265ea4f02634350cff010af59a980e8e)): ?>
<?php $attributes = $__attributesOriginal265ea4f02634350cff010af59a980e8e; ?>
<?php unset($__attributesOriginal265ea4f02634350cff010af59a980e8e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal265ea4f02634350cff010af59a980e8e)): ?>
<?php $component = $__componentOriginal265ea4f02634350cff010af59a980e8e; ?>
<?php unset($__componentOriginal265ea4f02634350cff010af59a980e8e); ?>
<?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Aucun produit trouvé</p>
                        </td>
                    </tr>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </tbody>
        </table>
    </div>
</div>
<?php /**PATH D:\stk\stk-back\resources\views/components/stock/inventory-table.blade.php ENDPATH**/ ?>