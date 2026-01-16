<div x-data="{ showModal: false, showDeleteModal: false, supplierToDelete: null, supplierName: '' }"
     @open-supplier-modal.window="showModal = true"
     @open-edit-modal.window="showModal = true"
     @close-supplier-modal.window="showModal = false">
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

     <?php $__env->slot('header', null, []); ?> 
        <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
            ['label' => 'Accueil', 'url' => route('dashboard')],
            ['label' => 'Fournisseurs']
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['label' => 'Accueil', 'url' => route('dashboard')],
            ['label' => 'Fournisseurs']
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

    <div class="flex items-center justify-between mt-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Fournisseurs</h1>
            <p class="text-gray-500 mt-1">Gérez vos fournisseurs et leurs informations</p>
        </div>
        <div class="flex items-center space-x-3">
            <button @click="showModal = true; $wire.openCreateModal()"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nouveau Fournisseur
            </button>
        </div>
    </div>

    <div class="space-y-6 mt-6">
        <!-- Suppliers Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Liste des Fournisseurs (<?php echo e($suppliers->total()); ?>)</h2>
                    <div class="w-72">
                        <?php if (isset($component)) { $__componentOriginal894294112bf23c4166443c90d4833959 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal894294112bf23c4166443c90d4833959 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.search-input','data' => ['wire:model.live.debounce.300ms' => 'search','wireModel' => 'search','placeholder' => 'Rechercher...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.search-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live.debounce.300ms' => 'search','wireModel' => 'search','placeholder' => 'Rechercher...']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal894294112bf23c4166443c90d4833959)): ?>
<?php $attributes = $__attributesOriginal894294112bf23c4166443c90d4833959; ?>
<?php unset($__attributesOriginal894294112bf23c4166443c90d4833959); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal894294112bf23c4166443c90d4833959)): ?>
<?php $component = $__componentOriginal894294112bf23c4166443c90d4833959; ?>
<?php unset($__componentOriginal894294112bf23c4166443c90d4833959); ?>
<?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if (isset($component)) { $__componentOriginalce08cb48157c4a917fb06b4e6b178eb7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce08cb48157c4a917fb06b4e6b178eb7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.table','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <?php if (isset($component)) { $__componentOriginal187ec4d26e72d09ba1cb8caa8ea74439 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal187ec4d26e72d09ba1cb8caa8ea74439 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.head','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <tr>
                        <?php if (isset($component)) { $__componentOriginalbc087b098ac654841a8659b533bc9309 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbc087b098ac654841a8659b533bc9309 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.header','data' => ['sortable' => true,'wire:click' => 'sortBy(\'name\')','sorted' => $sortField === 'name','direction' => $sortDirection]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['sortable' => true,'wire:click' => 'sortBy(\'name\')','sorted' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField === 'name'),'direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection)]); ?>
                            Nom
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbc087b098ac654841a8659b533bc9309)): ?>
<?php $attributes = $__attributesOriginalbc087b098ac654841a8659b533bc9309; ?>
<?php unset($__attributesOriginalbc087b098ac654841a8659b533bc9309); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbc087b098ac654841a8659b533bc9309)): ?>
<?php $component = $__componentOriginalbc087b098ac654841a8659b533bc9309; ?>
<?php unset($__componentOriginalbc087b098ac654841a8659b533bc9309); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalbc087b098ac654841a8659b533bc9309 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbc087b098ac654841a8659b533bc9309 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Téléphone <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbc087b098ac654841a8659b533bc9309)): ?>
<?php $attributes = $__attributesOriginalbc087b098ac654841a8659b533bc9309; ?>
<?php unset($__attributesOriginalbc087b098ac654841a8659b533bc9309); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbc087b098ac654841a8659b533bc9309)): ?>
<?php $component = $__componentOriginalbc087b098ac654841a8659b533bc9309; ?>
<?php unset($__componentOriginalbc087b098ac654841a8659b533bc9309); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalbc087b098ac654841a8659b533bc9309 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbc087b098ac654841a8659b533bc9309 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Email <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbc087b098ac654841a8659b533bc9309)): ?>
<?php $attributes = $__attributesOriginalbc087b098ac654841a8659b533bc9309; ?>
<?php unset($__attributesOriginalbc087b098ac654841a8659b533bc9309); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbc087b098ac654841a8659b533bc9309)): ?>
<?php $component = $__componentOriginalbc087b098ac654841a8659b533bc9309; ?>
<?php unset($__componentOriginalbc087b098ac654841a8659b533bc9309); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalbc087b098ac654841a8659b533bc9309 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbc087b098ac654841a8659b533bc9309 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Adresse <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbc087b098ac654841a8659b533bc9309)): ?>
<?php $attributes = $__attributesOriginalbc087b098ac654841a8659b533bc9309; ?>
<?php unset($__attributesOriginalbc087b098ac654841a8659b533bc9309); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbc087b098ac654841a8659b533bc9309)): ?>
<?php $component = $__componentOriginalbc087b098ac654841a8659b533bc9309; ?>
<?php unset($__componentOriginalbc087b098ac654841a8659b533bc9309); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalbc087b098ac654841a8659b533bc9309 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbc087b098ac654841a8659b533bc9309 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.header','data' => ['sortable' => true,'wire:click' => 'sortBy(\'created_at\')','sorted' => $sortField === 'created_at','direction' => $sortDirection]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['sortable' => true,'wire:click' => 'sortBy(\'created_at\')','sorted' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField === 'created_at'),'direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection)]); ?>
                            Date de création
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbc087b098ac654841a8659b533bc9309)): ?>
<?php $attributes = $__attributesOriginalbc087b098ac654841a8659b533bc9309; ?>
<?php unset($__attributesOriginalbc087b098ac654841a8659b533bc9309); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbc087b098ac654841a8659b533bc9309)): ?>
<?php $component = $__componentOriginalbc087b098ac654841a8659b533bc9309; ?>
<?php unset($__componentOriginalbc087b098ac654841a8659b533bc9309); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalbc087b098ac654841a8659b533bc9309 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbc087b098ac654841a8659b533bc9309 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.header','data' => ['align' => 'right']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right']); ?>Actions <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbc087b098ac654841a8659b533bc9309)): ?>
<?php $attributes = $__attributesOriginalbc087b098ac654841a8659b533bc9309; ?>
<?php unset($__attributesOriginalbc087b098ac654841a8659b533bc9309); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbc087b098ac654841a8659b533bc9309)): ?>
<?php $component = $__componentOriginalbc087b098ac654841a8659b533bc9309; ?>
<?php unset($__componentOriginalbc087b098ac654841a8659b533bc9309); ?>
<?php endif; ?>
                    </tr>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal187ec4d26e72d09ba1cb8caa8ea74439)): ?>
<?php $attributes = $__attributesOriginal187ec4d26e72d09ba1cb8caa8ea74439; ?>
<?php unset($__attributesOriginal187ec4d26e72d09ba1cb8caa8ea74439); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal187ec4d26e72d09ba1cb8caa8ea74439)): ?>
<?php $component = $__componentOriginal187ec4d26e72d09ba1cb8caa8ea74439; ?>
<?php unset($__componentOriginal187ec4d26e72d09ba1cb8caa8ea74439); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginala0e433bb3a1bca62138f9b63e3ac4221 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala0e433bb3a1bca62138f9b63e3ac4221 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.body','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.body'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php if (isset($component)) { $__componentOriginalce497eb0b465689d7cb385400a2cd821 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce497eb0b465689d7cb385400a2cd821 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.row','data' => ['wire:key' => 'supplier-'.e($supplier->id).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:key' => 'supplier-'.e($supplier->id).'']); ?>
                            <?php if (isset($component)) { $__componentOriginalc607f3dbbf983abb970b49dd6ee66681 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc607f3dbbf983abb970b49dd6ee66681 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                        <span class="text-white font-bold text-sm"><?php echo e(strtoupper(substr($supplier->name, 0, 2))); ?></span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900"><?php echo e($supplier->name); ?></div>
                                    </div>
                                </div>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc607f3dbbf983abb970b49dd6ee66681)): ?>
<?php $attributes = $__attributesOriginalc607f3dbbf983abb970b49dd6ee66681; ?>
<?php unset($__attributesOriginalc607f3dbbf983abb970b49dd6ee66681); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc607f3dbbf983abb970b49dd6ee66681)): ?>
<?php $component = $__componentOriginalc607f3dbbf983abb970b49dd6ee66681; ?>
<?php unset($__componentOriginalc607f3dbbf983abb970b49dd6ee66681); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginalc607f3dbbf983abb970b49dd6ee66681 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc607f3dbbf983abb970b49dd6ee66681 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                <span class="text-sm text-gray-900"><?php echo e($supplier->phone ?? '—'); ?></span>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc607f3dbbf983abb970b49dd6ee66681)): ?>
<?php $attributes = $__attributesOriginalc607f3dbbf983abb970b49dd6ee66681; ?>
<?php unset($__attributesOriginalc607f3dbbf983abb970b49dd6ee66681); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc607f3dbbf983abb970b49dd6ee66681)): ?>
<?php $component = $__componentOriginalc607f3dbbf983abb970b49dd6ee66681; ?>
<?php unset($__componentOriginalc607f3dbbf983abb970b49dd6ee66681); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginalc607f3dbbf983abb970b49dd6ee66681 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc607f3dbbf983abb970b49dd6ee66681 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                <span class="text-sm text-gray-900"><?php echo e($supplier->email ?? '—'); ?></span>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc607f3dbbf983abb970b49dd6ee66681)): ?>
<?php $attributes = $__attributesOriginalc607f3dbbf983abb970b49dd6ee66681; ?>
<?php unset($__attributesOriginalc607f3dbbf983abb970b49dd6ee66681); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc607f3dbbf983abb970b49dd6ee66681)): ?>
<?php $component = $__componentOriginalc607f3dbbf983abb970b49dd6ee66681; ?>
<?php unset($__componentOriginalc607f3dbbf983abb970b49dd6ee66681); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginalc607f3dbbf983abb970b49dd6ee66681 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc607f3dbbf983abb970b49dd6ee66681 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                <span class="text-sm text-gray-900"><?php echo e($supplier->address ? Str::limit($supplier->address, 50) : '—'); ?></span>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc607f3dbbf983abb970b49dd6ee66681)): ?>
<?php $attributes = $__attributesOriginalc607f3dbbf983abb970b49dd6ee66681; ?>
<?php unset($__attributesOriginalc607f3dbbf983abb970b49dd6ee66681); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc607f3dbbf983abb970b49dd6ee66681)): ?>
<?php $component = $__componentOriginalc607f3dbbf983abb970b49dd6ee66681; ?>
<?php unset($__componentOriginalc607f3dbbf983abb970b49dd6ee66681); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginalc607f3dbbf983abb970b49dd6ee66681 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc607f3dbbf983abb970b49dd6ee66681 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                <span class="text-sm text-gray-600"><?php echo e($supplier->created_at->format('d/m/Y')); ?></span>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc607f3dbbf983abb970b49dd6ee66681)): ?>
<?php $attributes = $__attributesOriginalc607f3dbbf983abb970b49dd6ee66681; ?>
<?php unset($__attributesOriginalc607f3dbbf983abb970b49dd6ee66681); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc607f3dbbf983abb970b49dd6ee66681)): ?>
<?php $component = $__componentOriginalc607f3dbbf983abb970b49dd6ee66681; ?>
<?php unset($__componentOriginalc607f3dbbf983abb970b49dd6ee66681); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginalc607f3dbbf983abb970b49dd6ee66681 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc607f3dbbf983abb970b49dd6ee66681 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.cell','data' => ['align' => 'right']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right']); ?>
                                <div class="flex items-center justify-end space-x-3">
                                    <button @click="$wire.openEditModal(<?php echo e($supplier->id); ?>)"
                                        wire:loading.attr="disabled"
                                        wire:target="openEditModal(<?php echo e($supplier->id); ?>)"
                                        class="text-indigo-600 hover:text-indigo-900 transition-colors p-2 rounded-lg hover:bg-indigo-50 disabled:opacity-50"
                                        title="Modifier">
                                        <svg wire:loading.remove wire:target="openEditModal(<?php echo e($supplier->id); ?>)" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <svg wire:loading wire:target="openEditModal(<?php echo e($supplier->id); ?>)" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </button>
                                    <button @click="showDeleteModal = true; supplierToDelete = <?php echo e($supplier->id); ?>; supplierName = '<?php echo e(addslashes($supplier->name)); ?>'"
                                        class="text-red-600 hover:text-red-900 transition-colors p-2 rounded-lg hover:bg-red-50"
                                        title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc607f3dbbf983abb970b49dd6ee66681)): ?>
<?php $attributes = $__attributesOriginalc607f3dbbf983abb970b49dd6ee66681; ?>
<?php unset($__attributesOriginalc607f3dbbf983abb970b49dd6ee66681); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc607f3dbbf983abb970b49dd6ee66681)): ?>
<?php $component = $__componentOriginalc607f3dbbf983abb970b49dd6ee66681; ?>
<?php unset($__componentOriginalc607f3dbbf983abb970b49dd6ee66681); ?>
<?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce497eb0b465689d7cb385400a2cd821)): ?>
<?php $attributes = $__attributesOriginalce497eb0b465689d7cb385400a2cd821; ?>
<?php unset($__attributesOriginalce497eb0b465689d7cb385400a2cd821); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce497eb0b465689d7cb385400a2cd821)): ?>
<?php $component = $__componentOriginalce497eb0b465689d7cb385400a2cd821; ?>
<?php unset($__componentOriginalce497eb0b465689d7cb385400a2cd821); ?>
<?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php if (isset($component)) { $__componentOriginal7feb35a4f8daba4e03c8e8875ba34147 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7feb35a4f8daba4e03c8e8875ba34147 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.empty-state','data' => ['colspan' => '6','title' => 'Aucun fournisseur trouvé','description' => 'Commencez par créer votre premier fournisseur.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['colspan' => '6','title' => 'Aucun fournisseur trouvé','description' => 'Commencez par créer votre premier fournisseur.']); ?>
                             <?php $__env->slot('action', null, []); ?> 
                                <?php if (isset($component)) { $__componentOriginal8a31ff0802d1df0c26bb607f30439b3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a31ff0802d1df0c26bb607f30439b3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.button','data' => ['wire:click' => 'openCreateModal','icon' => 'plus','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'openCreateModal','icon' => 'plus','size' => 'sm']); ?>
                                    Nouveau Fournisseur
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
                             <?php $__env->endSlot(); ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7feb35a4f8daba4e03c8e8875ba34147)): ?>
<?php $attributes = $__attributesOriginal7feb35a4f8daba4e03c8e8875ba34147; ?>
<?php unset($__attributesOriginal7feb35a4f8daba4e03c8e8875ba34147); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7feb35a4f8daba4e03c8e8875ba34147)): ?>
<?php $component = $__componentOriginal7feb35a4f8daba4e03c8e8875ba34147; ?>
<?php unset($__componentOriginal7feb35a4f8daba4e03c8e8875ba34147); ?>
<?php endif; ?>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala0e433bb3a1bca62138f9b63e3ac4221)): ?>
<?php $attributes = $__attributesOriginala0e433bb3a1bca62138f9b63e3ac4221; ?>
<?php unset($__attributesOriginala0e433bb3a1bca62138f9b63e3ac4221); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala0e433bb3a1bca62138f9b63e3ac4221)): ?>
<?php $component = $__componentOriginala0e433bb3a1bca62138f9b63e3ac4221; ?>
<?php unset($__componentOriginala0e433bb3a1bca62138f9b63e3ac4221); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce08cb48157c4a917fb06b4e6b178eb7)): ?>
<?php $attributes = $__attributesOriginalce08cb48157c4a917fb06b4e6b178eb7; ?>
<?php unset($__attributesOriginalce08cb48157c4a917fb06b4e6b178eb7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce08cb48157c4a917fb06b4e6b178eb7)): ?>
<?php $component = $__componentOriginalce08cb48157c4a917fb06b4e6b178eb7; ?>
<?php unset($__componentOriginalce08cb48157c4a917fb06b4e6b178eb7); ?>
<?php endif; ?>

            <!--[if BLOCK]><![endif]--><?php if($suppliers->hasPages()): ?>
                <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <!-- Results Info -->
                        <div class="text-sm text-gray-700">
                            Affichage de
                            <span class="font-semibold text-indigo-600"><?php echo e($suppliers->firstItem() ?? 0); ?></span>
                            à
                            <span class="font-semibold text-indigo-600"><?php echo e($suppliers->lastItem() ?? 0); ?></span>
                            sur
                            <span class="font-semibold text-indigo-600"><?php echo e($suppliers->total()); ?></span>
                            résultats
                        </div>

                        <!-- Pagination Links -->
                        <div>
                            <?php echo e($suppliers->links()); ?>

                        </div>
                    </div>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>

    <!-- Modal - Géré uniquement par Alpine.js -->
    <div x-show="showModal"
         x-cloak
         x-on:keydown.escape.window="showModal = false"
         x-init="$watch('showModal', value => { document.body.style.overflow = value ? 'hidden' : '' })"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">

        <!-- Backdrop -->
        <div @click="showModal = false"
             x-show="showModal"
             x-transition.opacity.duration.150ms
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm">
        </div>

        <!-- Modal Container -->
        <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
            <div x-show="showModal"
                 @click.stop
                 x-transition:enter="ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-100"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-white rounded-2xl shadow-2xl transform w-full sm:max-w-lg flex flex-col pointer-events-auto"
                 style="max-height: 90vh;">

                <!-- Modal Header -->
                <div class="flex-shrink-0 flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white rounded-t-2xl">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">
                            <?php echo e($isEditMode ? 'Modifier le Fournisseur' : 'Nouveau Fournisseur'); ?>

                        </h3>
                    </div>
                    <button @click="showModal = false" type="button"
                        class="rounded-lg p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" wire:key="supplier-form-<?php echo e($form->supplierId ?? 'new'); ?>">
                    <!-- Modal Body -->
                    <div class="flex-1 overflow-y-auto p-6">
                        <div class="space-y-5">
                            <!-- Name Field -->
                            <?php if (isset($component)) { $__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.form-group','data' => ['label' => 'Nom du fournisseur','for' => 'form.name','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Nom du fournisseur','for' => 'form.name','required' => true]); ?>
                                <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['wire:model.live' => 'form.name','id' => 'form.name','type' => 'text','placeholder' => 'Ex: Fournisseur ABC, Société XYZ...','icon' => 'building']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'form.name','id' => 'form.name','type' => 'text','placeholder' => 'Ex: Fournisseur ABC, Société XYZ...','icon' => 'building']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal8d499d75702cee5e9aae94bf7f660f12 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d499d75702cee5e9aae94bf7f660f12 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-error','data' => ['for' => 'form.name']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'form.name']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8d499d75702cee5e9aae94bf7f660f12)): ?>
<?php $attributes = $__attributesOriginal8d499d75702cee5e9aae94bf7f660f12; ?>
<?php unset($__attributesOriginal8d499d75702cee5e9aae94bf7f660f12); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8d499d75702cee5e9aae94bf7f660f12)): ?>
<?php $component = $__componentOriginal8d499d75702cee5e9aae94bf7f660f12; ?>
<?php unset($__componentOriginal8d499d75702cee5e9aae94bf7f660f12); ?>
<?php endif; ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8)): ?>
<?php $attributes = $__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8; ?>
<?php unset($__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8)): ?>
<?php $component = $__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8; ?>
<?php unset($__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8); ?>
<?php endif; ?>

                            <!-- Phone & Email Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Phone Field -->
                                <?php if (isset($component)) { $__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.form-group','data' => ['label' => 'Téléphone','for' => 'form.phone']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Téléphone','for' => 'form.phone']); ?>
                                    <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['wire:model.live' => 'form.phone','id' => 'form.phone','type' => 'text','placeholder' => 'Numéro de téléphone','icon' => 'phone']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'form.phone','id' => 'form.phone','type' => 'text','placeholder' => 'Numéro de téléphone','icon' => 'phone']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                                    <?php if (isset($component)) { $__componentOriginal8d499d75702cee5e9aae94bf7f660f12 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d499d75702cee5e9aae94bf7f660f12 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-error','data' => ['for' => 'form.phone']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'form.phone']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8d499d75702cee5e9aae94bf7f660f12)): ?>
<?php $attributes = $__attributesOriginal8d499d75702cee5e9aae94bf7f660f12; ?>
<?php unset($__attributesOriginal8d499d75702cee5e9aae94bf7f660f12); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8d499d75702cee5e9aae94bf7f660f12)): ?>
<?php $component = $__componentOriginal8d499d75702cee5e9aae94bf7f660f12; ?>
<?php unset($__componentOriginal8d499d75702cee5e9aae94bf7f660f12); ?>
<?php endif; ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8)): ?>
<?php $attributes = $__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8; ?>
<?php unset($__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8)): ?>
<?php $component = $__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8; ?>
<?php unset($__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8); ?>
<?php endif; ?>

                                <!-- Email Field -->
                                <?php if (isset($component)) { $__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.form-group','data' => ['label' => 'Email','for' => 'form.email']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Email','for' => 'form.email']); ?>
                                    <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['wire:model.live' => 'form.email','id' => 'form.email','type' => 'email','placeholder' => 'Adresse email','icon' => 'mail']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'form.email','id' => 'form.email','type' => 'email','placeholder' => 'Adresse email','icon' => 'mail']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                                    <?php if (isset($component)) { $__componentOriginal8d499d75702cee5e9aae94bf7f660f12 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d499d75702cee5e9aae94bf7f660f12 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-error','data' => ['for' => 'form.email']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'form.email']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8d499d75702cee5e9aae94bf7f660f12)): ?>
<?php $attributes = $__attributesOriginal8d499d75702cee5e9aae94bf7f660f12; ?>
<?php unset($__attributesOriginal8d499d75702cee5e9aae94bf7f660f12); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8d499d75702cee5e9aae94bf7f660f12)): ?>
<?php $component = $__componentOriginal8d499d75702cee5e9aae94bf7f660f12; ?>
<?php unset($__componentOriginal8d499d75702cee5e9aae94bf7f660f12); ?>
<?php endif; ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8)): ?>
<?php $attributes = $__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8; ?>
<?php unset($__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8)): ?>
<?php $component = $__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8; ?>
<?php unset($__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8); ?>
<?php endif; ?>
                            </div>

                            <!-- Address Field -->
                            <?php if (isset($component)) { $__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.form-group','data' => ['label' => 'Adresse','for' => 'form.address']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Adresse','for' => 'form.address']); ?>
                                <textarea id="form.address" wire:model.live="form.address" rows="2"
                                    class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"
                                    placeholder="Adresse complète du fournisseur..."></textarea>
                                <?php if (isset($component)) { $__componentOriginal8d499d75702cee5e9aae94bf7f660f12 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d499d75702cee5e9aae94bf7f660f12 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-error','data' => ['for' => 'form.address']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'form.address']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8d499d75702cee5e9aae94bf7f660f12)): ?>
<?php $attributes = $__attributesOriginal8d499d75702cee5e9aae94bf7f660f12; ?>
<?php unset($__attributesOriginal8d499d75702cee5e9aae94bf7f660f12); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8d499d75702cee5e9aae94bf7f660f12)): ?>
<?php $component = $__componentOriginal8d499d75702cee5e9aae94bf7f660f12; ?>
<?php unset($__componentOriginal8d499d75702cee5e9aae94bf7f660f12); ?>
<?php endif; ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8)): ?>
<?php $attributes = $__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8; ?>
<?php unset($__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8)): ?>
<?php $component = $__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8; ?>
<?php unset($__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8); ?>
<?php endif; ?>

                            <!-- Info Box -->
                            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-indigo-700">
                                            Les fournisseurs vous permettent de suivre vos achats et de gérer vos relations commerciales.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex-shrink-0 flex justify-end space-x-3 p-6 border-t border-gray-200 bg-gray-50/50 rounded-b-2xl">
                        <button type="button" @click="showModal = false"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Annuler
                        </button>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors disabled:opacity-50"
                            wire:loading.attr="disabled">
                            <svg wire:loading.remove wire:target="save" class="w-5 h-5 mr-2 inline-block" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <svg wire:loading wire:target="save" class="animate-spin w-5 h-5 mr-2 inline-block"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span wire:loading.remove wire:target="save">
                                <?php echo e($isEditMode ? 'Mettre à jour' : 'Enregistrer'); ?>

                            </span>
                            <span wire:loading wire:target="save">Enregistrement...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <?php if (isset($component)) { $__componentOriginal8b7b112f0fae85419ee5abf8337434ab = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8b7b112f0fae85419ee5abf8337434ab = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.delete-confirmation-modal','data' => ['show' => 'showDeleteModal','itemName' => 'supplierName','itemType' => 'le fournisseur','onConfirm' => '$wire.delete(supplierToDelete); showDeleteModal = false; supplierToDelete = null; supplierName = \'\'','onCancel' => 'showDeleteModal = false; supplierToDelete = null; supplierName = \'\'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('delete-confirmation-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['show' => 'showDeleteModal','itemName' => 'supplierName','itemType' => 'le fournisseur','onConfirm' => '$wire.delete(supplierToDelete); showDeleteModal = false; supplierToDelete = null; supplierName = \'\'','onCancel' => 'showDeleteModal = false; supplierToDelete = null; supplierName = \'\'']); ?>
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
<?php /**PATH D:\stk\stk-back\resources\views/livewire/supplier/supplier-index.blade.php ENDPATH**/ ?>