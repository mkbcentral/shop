<div>
     <?php $__env->slot('header', null, []); ?> 
        <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
            ['label' => 'Accueil', 'url' => route('dashboard')],
            ['label' => 'Stock', 'url' => route('stock.index')],
            ['label' => 'Historique']
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['label' => 'Accueil', 'url' => route('dashboard')],
            ['label' => 'Stock', 'url' => route('stock.index')],
            ['label' => 'Historique']
        ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $attributes = $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $component = $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
     <?php $__env->endSlot(); ?>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Historique des Mouvements</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        <?php echo e($variant->product->name); ?>

                        <!--[if BLOCK]><![endif]--><?php if($variant->size || $variant->color): ?>
                            - <?php echo e($variant->size); ?> <?php echo e($variant->color); ?>

                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Stock actuel</p>
                    <p class="text-2xl font-bold text-indigo-600"><?php echo e($variant->stock_quantity); ?></p>
                </div>
            </div>
        </div>

    <!-- Timeline -->
    <div class="p-6">
        <!--[if BLOCK]><![endif]--><?php if($movements->count() > 0): ?>
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $movements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $movement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li wire:key="movement-<?php echo e($movement->id); ?>">
                            <div class="relative pb-8">
                                <!--[if BLOCK]><![endif]--><?php if(!$loop->last): ?>
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                            <?php echo e($movement->type === 'in' ? 'bg-green-500' : 'bg-red-500'); ?>">
                                            <!--[if BLOCK]><![endif]--><?php if($movement->type === 'in'): ?>
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                                </svg>
                                            <?php else: ?>
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                                </svg>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </span>
                                    </div>
                                    <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                        <div>
                                            <p class="text-sm text-gray-900">
                                                <span class="font-semibold <?php echo e($movement->type === 'in' ? 'text-green-600' : 'text-red-600'); ?>">
                                                    <?php echo e($movement->type === 'in' ? '+' : '-'); ?><?php echo e($movement->quantity); ?>

                                                </span>
                                                <span class="text-gray-600">
                                                    - <?php echo e(ucfirst(str_replace('_', ' ', $movement->movement_type))); ?>

                                                </span>
                                            </p>
                                            <!--[if BLOCK]><![endif]--><?php if($movement->reference): ?>
                                                <p class="mt-1 text-xs text-gray-500">
                                                    Réf: <?php echo e($movement->reference); ?>

                                                </p>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            <!--[if BLOCK]><![endif]--><?php if($movement->reason): ?>
                                                <p class="mt-1 text-sm text-gray-600">
                                                    <?php echo e($movement->reason); ?>

                                                </p>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            <p class="mt-1 text-xs text-gray-500">
                                                Par <?php echo e($movement->user->name); ?>

                                            </p>
                                        </div>
                                        <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                            <time datetime="<?php echo e($movement->date->format('Y-m-d')); ?>">
                                                <?php echo e($movement->date->format('d/m/Y')); ?>

                                            </time>
                                            <p class="text-xs text-gray-400">
                                                <?php echo e($movement->created_at->format('H:i')); ?>

                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </ul>
            </div>

            <!-- Load More Button -->
            <?php if($movements->count() >= $limit): ?>
                <div class="mt-6 text-center">
                    <?php if (isset($component)) { $__componentOriginal8a31ff0802d1df0c26bb607f30439b3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a31ff0802d1df0c26bb607f30439b3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.button','data' => ['wire:click' => 'loadMore','variant' => 'secondary','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'loadMore','variant' => 'secondary','size' => 'sm']); ?>
                        Voir plus de mouvements
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
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <?php else: ?>
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun mouvement</h3>
                <p class="mt-1 text-sm text-gray-500">Ce produit n'a pas encore de mouvements de stock.</p>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
    </div>
</div>
<?php /**PATH D:\stk\stk-back\resources\views/livewire/stock/history.blade.php ENDPATH**/ ?>