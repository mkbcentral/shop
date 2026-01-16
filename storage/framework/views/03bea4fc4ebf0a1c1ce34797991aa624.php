<!--[if BLOCK]><![endif]--><?php if($showAddModal): ?>
<div x-show="$wire.showAddModal"
     x-cloak
     x-on:keydown.escape.window="$wire.closeAddModal()"
     x-init="$watch('$wire.showAddModal', value => { document.body.style.overflow = value ? 'hidden' : '' })"
     class="fixed inset-0 z-50 overflow-hidden"
     aria-labelledby="modal-title"
     role="dialog"
     aria-modal="true">

    <!-- Backdrop -->
    <div x-show="$wire.showAddModal"
         x-on:click="$wire.closeAddModal()"
         x-transition.opacity.duration.150ms
         class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm">
    </div>

    <!-- Modal Container -->
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div x-show="$wire.showAddModal"
             x-on:click.stop
             x-transition:enter="ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-100"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-white rounded-2xl shadow-2xl w-full sm:max-w-2xl flex flex-col pointer-events-auto"
             style="max-height: 90vh;">

            <div class="bg-white rounded-xl shadow-xl flex flex-col min-h-0 flex-1">
                    <!-- Modal Header -->
                    <div class="flex-shrink-0 flex items-center justify-between p-6 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div
                                class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Ajouter du Stock</h3>
                        </div>
                        <button wire:click="closeAddModal" type="button"
                            class="text-gray-400 hover:text-gray-500 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <form wire:submit.prevent="addStock" class="flex flex-col flex-1 min-h-0">
                        <div class="p-6 space-y-4 overflow-y-auto flex-1" x-data="{
                            selectedVariantId: <?php if ((object) ('form.product_variant_id') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('form.product_variant_id'->value()); ?>')<?php echo e('form.product_variant_id'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('form.product_variant_id'); ?>')<?php endif; ?>,
                            variants: <?php echo e(Js::from($variants->mapWithKeys(fn($v) => [$v->id => ['stock' => $v->stock_quantity, 'cost' => $v->product->cost_price ?? 0]]))); ?>,
                            get currentStock() {
                                return this.selectedVariantId ? (this.variants[this.selectedVariantId]?.stock ?? 0) : 0;
                            },
                            get productCost() {
                                return this.selectedVariantId ? (this.variants[this.selectedVariantId]?.cost ?? 0) : 0;
                            }
                        }">
                            <!-- Product Variant Selection - Full Width -->
                            <div>
                                <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['for' => 'add-product-variant']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'add-product-variant']); ?>Produit * <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                                <select id="add-product-variant" x-model="selectedVariantId"
                                    wire:model.live="form.product_variant_id"
                                    class="mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                                    <option value="">Sélectionnez un produit</option>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($variant->id); ?>">
                                            <?php echo e($variant->product->name); ?> - <?php echo e($variant->sku); ?>

                                            <!--[if BLOCK]><![endif]--><?php if($variant->size || $variant->color): ?>
                                                (<?php echo e($variant->size); ?> <?php echo e($variant->color); ?>)
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            - Stock actuel: <?php echo e($variant->stock_quantity); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </select>
                                <?php if (isset($component)) { $__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.error','data' => ['name' => 'form.product_variant_id']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'form.product_variant_id']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f)): ?>
<?php $attributes = $__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f; ?>
<?php unset($__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f)): ?>
<?php $component = $__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f; ?>
<?php unset($__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f); ?>
<?php endif; ?>

                                <template x-if="selectedVariantId">
                                    <p class="mt-2 text-sm text-gray-600">
                                        Stock actuel:
                                        <span class="font-semibold text-indigo-600"
                                            x-text="currentStock + ' unités'"></span>
                                    </p>
                                </template>
                            </div>

                            <!-- Row: Quantity & Movement Type -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['for' => 'add-quantity']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'add-quantity']); ?>Quantité * <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                                    <input type="number" id="add-quantity" wire:model="form.quantity" min="1"
                                        class="mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                        placeholder="Ex: 50">
                                    <?php if (isset($component)) { $__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.error','data' => ['name' => 'form.quantity']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'form.quantity']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f)): ?>
<?php $attributes = $__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f; ?>
<?php unset($__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f)): ?>
<?php $component = $__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f; ?>
<?php unset($__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f); ?>
<?php endif; ?>
                                </div>

                                <div>
                                    <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['for' => 'add-movement-type']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'add-movement-type']); ?>Type de mouvement <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                                    <select id="add-movement-type" wire:model.live="form.movement_type"
                                        class="mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                                        <option value="purchase">Achat</option>
                                        <option value="return">Retour client</option>
                                        <option value="adjustment">Ajustement</option>
                                        <option value="transfer">Transfert</option>
                                    </select>
                                    <?php if (isset($component)) { $__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.error','data' => ['name' => 'form.movement_type']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'form.movement_type']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f)): ?>
<?php $attributes = $__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f; ?>
<?php unset($__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f)): ?>
<?php $component = $__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f; ?>
<?php unset($__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f); ?>
<?php endif; ?>
                                </div>
                            </div>

                            <!-- Row: Reference & Date -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['for' => 'add-reference']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'add-reference']); ?>Référence (Bon de commande...) <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                                    <input type="text" id="add-reference" wire:model="form.reference"
                                        class="mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                        placeholder="Ex: BC-2024-001">
                                    <?php if (isset($component)) { $__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.error','data' => ['name' => 'form.reference']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'form.reference']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f)): ?>
<?php $attributes = $__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f; ?>
<?php unset($__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f)): ?>
<?php $component = $__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f; ?>
<?php unset($__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f); ?>
<?php endif; ?>
                                </div>

                                <div>
                                    <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['for' => 'add-date']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'add-date']); ?>Date <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                                    <input type="date" id="add-date" wire:model="form.date"
                                        class="mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                                    <?php if (isset($component)) { $__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.error','data' => ['name' => 'form.date']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'form.date']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f)): ?>
<?php $attributes = $__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f; ?>
<?php unset($__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f)): ?>
<?php $component = $__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f; ?>
<?php unset($__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f); ?>
<?php endif; ?>
                                </div>
                            </div>

                            <!-- Row: Unit Price & Reason -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['for' => 'add-unit-price']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'add-unit-price']); ?>Prix unitaire <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                                    <input type="number" id="add-unit-price" wire:model="form.unit_price"
                                        step="0.01" min="0"
                                        class="mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                        placeholder="Ex: 25.50">
                                    <?php if (isset($component)) { $__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.error','data' => ['name' => 'form.unit_price']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'form.unit_price']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f)): ?>
<?php $attributes = $__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f; ?>
<?php unset($__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f)): ?>
<?php $component = $__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f; ?>
<?php unset($__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f); ?>
<?php endif; ?>

                                    <template x-if="selectedVariantId && productCost > 0">
                                        <p class="mt-1 text-xs text-gray-500">
                                            Prix de revient du produit: <span x-text="productCost + ' €'"></span>
                                        </p>
                                    </template>
                                </div>

                                <div>
                                    <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['for' => 'add-reason']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'add-reason']); ?>Motif <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                                    <input type="text" id="add-reason" wire:model="form.reason"
                                        class="mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                        placeholder="Ex: Réapprovisionnement stock">
                                    <?php if (isset($component)) { $__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.error','data' => ['name' => 'form.reason']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'form.reason']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f)): ?>
<?php $attributes = $__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f; ?>
<?php unset($__attributesOriginal8a61cf4ce6144d9e2012fbc98db0155f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f)): ?>
<?php $component = $__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f; ?>
<?php unset($__componentOriginal8a61cf4ce6144d9e2012fbc98db0155f); ?>
<?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div
                            class="flex-shrink-0 bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200 rounded-b-xl">
                            <?php if (isset($component)) { $__componentOriginal8a31ff0802d1df0c26bb607f30439b3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a31ff0802d1df0c26bb607f30439b3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.button','data' => ['type' => 'button','wire:click' => 'closeAddModal','variant' => 'secondary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','wire:click' => 'closeAddModal','variant' => 'secondary']); ?>
                                Annuler
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a31ff0802d1df0c26bb607f30439b3a)): ?>
<?php $attributes = $__attributesOriginal8a31ff0802d1df0c26bb607f30439b3a; ?>
<?php unset($__attributesOriginal8a31ff0802d1df0c26bb607f30439b3a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a31ff0802d1df0c26bb607f30439b3a)): ?>
<?php $component = $__componentOriginal8a31ff0802d1df0c26bb607f30439b3a; ?>
<?php unset($__componentOriginal8a31ff0802d1df0c26bb607f30439b3a); ?>
<?php endif; ?>

                            <?php if (isset($component)) { $__componentOriginal8a31ff0802d1df0c26bb607f30439b3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a31ff0802d1df0c26bb607f30439b3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.button','data' => ['type' => 'submit','variant' => 'success','wire:loading.attr' => 'disabled']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'success','wire:loading.attr' => 'disabled']); ?>
                                <svg wire:loading.remove class="w-5 h-5 mr-2 inline-block" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <svg wire:loading class="animate-spin w-5 h-5 mr-2 inline-block"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Ajouter le Stock
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a31ff0802d1df0c26bb607f30439b3a)): ?>
<?php $attributes = $__attributesOriginal8a31ff0802d1df0c26bb607f30439b3a; ?>
<?php unset($__attributesOriginal8a31ff0802d1df0c26bb607f30439b3a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a31ff0802d1df0c26bb607f30439b3a)): ?>
<?php $component = $__componentOriginal8a31ff0802d1df0c26bb607f30439b3a; ?>
<?php unset($__componentOriginal8a31ff0802d1df0c26bb607f30439b3a); ?>
<?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->
<?php /**PATH D:\stk\stk-back\resources\views/livewire/stock/modals/add-stock-new.blade.php ENDPATH**/ ?>