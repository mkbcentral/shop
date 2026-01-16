<div x-data="{ showDeleteModal: false, productToDelete: null, productName: '' }">
    <!-- Toast Notifications -->
    <?php if (isset($component)) { $__componentOriginal7cfab914afdd05940201ca0b2cbc009b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7cfab914afdd05940201ca0b2cbc009b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.toast','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('toast'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7cfab914afdd05940201ca0b2cbc009b)): ?>
<?php $attributes = $__attributesOriginal7cfab914afdd05940201ca0b2cbc009b; ?>
<?php unset($__attributesOriginal7cfab914afdd05940201ca0b2cbc009b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7cfab914afdd05940201ca0b2cbc009b)): ?>
<?php $component = $__componentOriginal7cfab914afdd05940201ca0b2cbc009b; ?>
<?php unset($__componentOriginal7cfab914afdd05940201ca0b2cbc009b); ?>
<?php endif; ?>

    <!-- Include Product Modal -->
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('product.product-modal', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3795979281-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

     <?php $__env->slot('header', null, []); ?> 
        <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [['label' => 'Accueil', 'url' => route('dashboard')], ['label' => 'Produits']]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'Accueil', 'url' => route('dashboard')], ['label' => 'Produits']])]); ?>
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

    <div class="flex items-center justify-between mt-2">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Produits</h1>
            <p class="text-gray-500 mt-1">Gérez votre catalogue de produits</p>
        </div>
        <div class="flex items-center space-x-3">
            <?php if (isset($component)) { $__componentOriginal8a31ff0802d1df0c26bb607f30439b3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a31ff0802d1df0c26bb607f30439b3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.button','data' => ['wire:click' => '$dispatch(\'openProductModal\')','icon' => 'plus']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => '$dispatch(\'openProductModal\')','icon' => 'plus']); ?>
                Nouveau Produit
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
    </div>

    <!-- KPI Dashboard -->
    <?php if (isset($component)) { $__componentOriginal3b56c23f5d7b03ac9feff2f3b0430fa8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b56c23f5d7b03ac9feff2f3b0430fa8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product.kpi-dashboard','data' => ['kpis' => $kpis]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product.kpi-dashboard'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['kpis' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($kpis)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3b56c23f5d7b03ac9feff2f3b0430fa8)): ?>
<?php $attributes = $__attributesOriginal3b56c23f5d7b03ac9feff2f3b0430fa8; ?>
<?php unset($__attributesOriginal3b56c23f5d7b03ac9feff2f3b0430fa8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3b56c23f5d7b03ac9feff2f3b0430fa8)): ?>
<?php $component = $__componentOriginal3b56c23f5d7b03ac9feff2f3b0430fa8; ?>
<?php unset($__componentOriginal3b56c23f5d7b03ac9feff2f3b0430fa8); ?>
<?php endif; ?>

    <div class="space-y-6">
        <!-- Filters -->
        <?php if (isset($component)) { $__componentOriginale05f6cba76b77c5fd6b1a8d1235779d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale05f6cba76b77c5fd6b1a8d1235779d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product.filters','data' => ['categories' => $categories,'search' => $search,'categoryFilter' => $categoryFilter,'statusFilter' => $statusFilter,'stockLevelFilter' => $stockLevelFilter]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product.filters'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['categories' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($categories),'search' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($search),'categoryFilter' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($categoryFilter),'statusFilter' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($statusFilter),'stockLevelFilter' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stockLevelFilter)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale05f6cba76b77c5fd6b1a8d1235779d1)): ?>
<?php $attributes = $__attributesOriginale05f6cba76b77c5fd6b1a8d1235779d1; ?>
<?php unset($__attributesOriginale05f6cba76b77c5fd6b1a8d1235779d1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale05f6cba76b77c5fd6b1a8d1235779d1)): ?>
<?php $component = $__componentOriginale05f6cba76b77c5fd6b1a8d1235779d1; ?>
<?php unset($__componentOriginale05f6cba76b77c5fd6b1a8d1235779d1); ?>
<?php endif; ?>

        <!-- Products List -->
        <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
             <?php $__env->slot('header', null, []); ?> 
                <div class="flex items-center justify-between">
                    <?php if (isset($component)) { $__componentOriginal69d325773055bdef057fbaa2d9bf67ac = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69d325773055bdef057fbaa2d9bf67ac = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card-title','data' => ['title' => 'Liste des Produits ('.e($products->total()).')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card-title'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Liste des Produits ('.e($products->total()).')']); ?>
                         <?php $__env->slot('action', null, []); ?> 
                            <?php if (isset($component)) { $__componentOriginal09f4adbb5528503e740acd505dd80de7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal09f4adbb5528503e740acd505dd80de7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product.toolbar','data' => ['selected' => $selected,'viewMode' => $viewMode,'densityMode' => $densityMode,'categoryFilter' => $categoryFilter,'statusFilter' => $statusFilter,'perPage' => $perPage]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product.toolbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($selected),'viewMode' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($viewMode),'densityMode' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($densityMode),'categoryFilter' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($categoryFilter),'statusFilter' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($statusFilter),'perPage' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($perPage)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal09f4adbb5528503e740acd505dd80de7)): ?>
<?php $attributes = $__attributesOriginal09f4adbb5528503e740acd505dd80de7; ?>
<?php unset($__attributesOriginal09f4adbb5528503e740acd505dd80de7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal09f4adbb5528503e740acd505dd80de7)): ?>
<?php $component = $__componentOriginal09f4adbb5528503e740acd505dd80de7; ?>
<?php unset($__componentOriginal09f4adbb5528503e740acd505dd80de7); ?>
<?php endif; ?>
                         <?php $__env->endSlot(); ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69d325773055bdef057fbaa2d9bf67ac)): ?>
<?php $attributes = $__attributesOriginal69d325773055bdef057fbaa2d9bf67ac; ?>
<?php unset($__attributesOriginal69d325773055bdef057fbaa2d9bf67ac); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69d325773055bdef057fbaa2d9bf67ac)): ?>
<?php $component = $__componentOriginal69d325773055bdef057fbaa2d9bf67ac; ?>
<?php unset($__componentOriginal69d325773055bdef057fbaa2d9bf67ac); ?>
<?php endif; ?>
                </div>
             <?php $__env->endSlot(); ?>

            <!-- Loading Skeleton -->
            <div wire:loading.delay.long>
                <!--[if BLOCK]><![endif]--><?php if($viewMode === 'table'): ?>
                    <?php if (isset($component)) { $__componentOriginal84ef0a20be0372c7a4e50cadf25c2b38 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal84ef0a20be0372c7a4e50cadf25c2b38 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product.table-skeleton','data' => ['rows' => $perPage,'densityMode' => $densityMode]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product.table-skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['rows' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($perPage),'densityMode' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($densityMode)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal84ef0a20be0372c7a4e50cadf25c2b38)): ?>
<?php $attributes = $__attributesOriginal84ef0a20be0372c7a4e50cadf25c2b38; ?>
<?php unset($__attributesOriginal84ef0a20be0372c7a4e50cadf25c2b38); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal84ef0a20be0372c7a4e50cadf25c2b38)): ?>
<?php $component = $__componentOriginal84ef0a20be0372c7a4e50cadf25c2b38; ?>
<?php unset($__componentOriginal84ef0a20be0372c7a4e50cadf25c2b38); ?>
<?php endif; ?>
                <?php else: ?>
                    <?php if (isset($component)) { $__componentOriginala7309d6278b30add3acdd90074989f0d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala7309d6278b30add3acdd90074989f0d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product.grid-skeleton','data' => ['count' => $perPage]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product.grid-skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['count' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($perPage)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala7309d6278b30add3acdd90074989f0d)): ?>
<?php $attributes = $__attributesOriginala7309d6278b30add3acdd90074989f0d; ?>
<?php unset($__attributesOriginala7309d6278b30add3acdd90074989f0d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala7309d6278b30add3acdd90074989f0d)): ?>
<?php $component = $__componentOriginala7309d6278b30add3acdd90074989f0d; ?>
<?php unset($__componentOriginala7309d6278b30add3acdd90074989f0d); ?>
<?php endif; ?>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Content -->
            <div wire:loading.remove.delay.long>
                <!--[if BLOCK]><![endif]--><?php if($viewMode === 'table'): ?>
                    <?php if (isset($component)) { $__componentOriginalc48aa0977cf3ce6151a86622a39ea9c1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc48aa0977cf3ce6151a86622a39ea9c1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product.table-view','data' => ['products' => $products,'densityMode' => $densityMode,'selectAll' => $selectAll]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product.table-view'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['products' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($products),'densityMode' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($densityMode),'selectAll' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($selectAll)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc48aa0977cf3ce6151a86622a39ea9c1)): ?>
<?php $attributes = $__attributesOriginalc48aa0977cf3ce6151a86622a39ea9c1; ?>
<?php unset($__attributesOriginalc48aa0977cf3ce6151a86622a39ea9c1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc48aa0977cf3ce6151a86622a39ea9c1)): ?>
<?php $component = $__componentOriginalc48aa0977cf3ce6151a86622a39ea9c1; ?>
<?php unset($__componentOriginalc48aa0977cf3ce6151a86622a39ea9c1); ?>
<?php endif; ?>
                <?php else: ?>
                    <?php if (isset($component)) { $__componentOriginal9ef9f0d77136235909f04a01f5cef600 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ef9f0d77136235909f04a01f5cef600 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product.grid-view','data' => ['products' => $products]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product.grid-view'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['products' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($products)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ef9f0d77136235909f04a01f5cef600)): ?>
<?php $attributes = $__attributesOriginal9ef9f0d77136235909f04a01f5cef600; ?>
<?php unset($__attributesOriginal9ef9f0d77136235909f04a01f5cef600); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ef9f0d77136235909f04a01f5cef600)): ?>
<?php $component = $__componentOriginal9ef9f0d77136235909f04a01f5cef600; ?>
<?php unset($__componentOriginal9ef9f0d77136235909f04a01f5cef600); ?>
<?php endif; ?>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <!--[if BLOCK]><![endif]--><?php if($products->hasPages()): ?>
                    <div class="mt-4">
                        <?php echo e($products->links()); ?>

                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $attributes = $__attributesOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__attributesOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $component = $__componentOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__componentOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>

        <!-- Delete Confirmation Modal -->
        <?php if (isset($component)) { $__componentOriginal8b7b112f0fae85419ee5abf8337434ab = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8b7b112f0fae85419ee5abf8337434ab = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.delete-confirmation-modal','data' => ['show' => 'showDeleteModal','itemName' => 'productName','itemType' => 'le produit','onConfirm' => '$wire.set(\'productToDelete\', productToDelete); $wire.call(\'delete\'); showDeleteModal = false','onCancel' => 'showDeleteModal = false; productToDelete = null; productName = \'\'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('delete-confirmation-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['show' => 'showDeleteModal','itemName' => 'productName','itemType' => 'le produit','onConfirm' => '$wire.set(\'productToDelete\', productToDelete); $wire.call(\'delete\'); showDeleteModal = false','onCancel' => 'showDeleteModal = false; productToDelete = null; productName = \'\'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8b7b112f0fae85419ee5abf8337434ab)): ?>
<?php $attributes = $__attributesOriginal8b7b112f0fae85419ee5abf8337434ab; ?>
<?php unset($__attributesOriginal8b7b112f0fae85419ee5abf8337434ab); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8b7b112f0fae85419ee5abf8337434ab)): ?>
<?php $component = $__componentOriginal8b7b112f0fae85419ee5abf8337434ab; ?>
<?php unset($__componentOriginal8b7b112f0fae85419ee5abf8337434ab); ?>
<?php endif; ?>
    </div>
</div>
<?php /**PATH D:\stk\stk-back\resources\views/livewire/product/product-index.blade.php ENDPATH**/ ?>