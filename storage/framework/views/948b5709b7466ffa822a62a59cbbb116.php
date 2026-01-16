<div x-data="{ showModal: false, showDeleteModal: false, categoryToDelete: null, categoryName: '', isEditing: false }"
     @open-category-modal.window="showModal = true"
     @open-edit-modal.window="isEditing = true; showModal = true"
     @close-category-modal.window="showModal = false; isEditing = false">
     <?php $__env->slot('header', null, []); ?> 
        <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [['label' => 'Accueil', 'url' => route('dashboard')], ['label' => 'Catégories']]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'Accueil', 'url' => route('dashboard')], ['label' => 'Catégories']])]); ?>
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
            <h1 class="text-3xl font-bold text-gray-900">Catégories</h1>
            <p class="text-gray-500 mt-1">Gérez les catégories de produits</p>
        </div>
        <button @click="isEditing = false; showModal = true; $wire.openCreateModal()"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition duration-150">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Nouvelle Catégorie
        </button>
    </div>

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

    <!-- Search and Filters -->
    <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Search -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search"
                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition"
                    placeholder="Rechercher une catégorie...">
            </div>

            <!-- Per Page Selector -->
            <div class="flex items-center space-x-2">
                <label for="perPage" class="text-sm font-medium text-gray-700 whitespace-nowrap">
                    Afficher :
                </label>
                <select id="perPage" wire:model.live="perPage"
                    class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value="5">5 catégories</option>
                    <option value="10">10 catégories</option>
                    <option value="25">25 catégories</option>
                    <option value="50">50 catégories</option>
                    <option value="100">100 catégories</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Nom <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes([]); ?>Description <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes([]); ?>Nombre de Produits <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes([]); ?>Date de création <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.header','data' => ['align' => 'center']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'center']); ?>Actions <?php echo $__env->renderComponent(); ?>
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
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php if (isset($component)) { $__componentOriginalce497eb0b465689d7cb385400a2cd821 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce497eb0b465689d7cb385400a2cd821 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.row','data' => ['wire:key' => 'category-'.e($category->id).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:key' => 'category-'.e($category->id).'']); ?>
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
                                <div
                                    class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <span
                                        class="text-white font-bold text-sm"><?php echo e(strtoupper(substr($category->name, 0, 2))); ?></span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($category->name); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo e($category->slug); ?></div>
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
                            <span class="text-sm text-gray-600">
                                <?php echo e($category->description ? Str::limit($category->description, 50) : '—'); ?>

                            </span>
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
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                <?php echo e($category->products_count); ?>

                                <?php echo e($category->products_count > 1 ? 'produits' : 'produit'); ?>

                            </span>
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
                            <span class="text-sm text-gray-600"><?php echo e($category->created_at->format('d/m/Y')); ?></span>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.cell','data' => ['align' => 'center']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'center']); ?>
                            <div class="flex items-center justify-center space-x-3">
                                <button @click="$wire.openEditModal(<?php echo e($category->id); ?>)"
                                    wire:loading.attr="disabled"
                                    wire:target="openEditModal(<?php echo e($category->id); ?>)"
                                    class="text-indigo-600 hover:text-indigo-900 transition-colors p-2 rounded-lg hover:bg-indigo-50 disabled:opacity-50"
                                    title="Modifier">
                                    <svg wire:loading.remove wire:target="openEditModal(<?php echo e($category->id); ?>)" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <svg wire:loading wire:target="openEditModal(<?php echo e($category->id); ?>)" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </button>
                                <!--[if BLOCK]><![endif]--><?php if($category->products_count == 0): ?>
                                    <button type="button"
                                        @click="showDeleteModal = true; categoryToDelete = <?php echo e($category->id); ?>; categoryName = '<?php echo e(addslashes($category->name)); ?>'"
                                        class="text-red-600 hover:text-red-900 transition-colors p-2 rounded-lg hover:bg-red-50"
                                        title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.empty-state','data' => ['colspan' => '5','title' => 'Aucune catégorie trouvée','description' => 'Commencez par créer votre première catégorie de produits.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['colspan' => '5','title' => 'Aucune catégorie trouvée','description' => 'Commencez par créer votre première catégorie de produits.']); ?>
                         <?php $__env->slot('action', null, []); ?> 
                            <?php if (isset($component)) { $__componentOriginal8a31ff0802d1df0c26bb607f30439b3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a31ff0802d1df0c26bb607f30439b3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.button','data' => ['@click' => 'isEditing = false; showModal = true; $wire.openCreateModal()','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['@click' => 'isEditing = false; showModal = true; $wire.openCreateModal()','size' => 'sm']); ?>
                                <svg class="w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Créer une catégorie
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

        <!-- Pagination -->
        <!--[if BLOCK]><![endif]--><?php if($categories->hasPages()): ?>
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <!-- Results Info -->
                    <div class="text-sm text-gray-700">
                        Affichage de
                        <span class="font-semibold text-indigo-600"><?php echo e($categories->firstItem() ?? 0); ?></span>
                        à
                        <span class="font-semibold text-indigo-600"><?php echo e($categories->lastItem() ?? 0); ?></span>
                        sur
                        <span class="font-semibold text-indigo-600"><?php echo e($categories->total()); ?></span>
                        résultats
                    </div>

                    <!-- Pagination Links -->
                    <div>
                        <?php echo e($categories->links('vendor.livewire.tailwind')); ?>

                    </div>
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <!-- Modal utilisant le composant réutilisable -->
    <?php if (isset($component)) { $__componentOriginal290e498d3a9be9ca04daade6fb284d0b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal290e498d3a9be9ca04daade6fb284d0b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.alpine-modal','data' => ['name' => 'category','maxWidth' => 'lg','title' => 'Nouvelle catégorie','editTitle' => 'Modifier la catégorie','iconBg' => 'from-indigo-500 to-purple-600']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.alpine-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'category','max-width' => 'lg','title' => 'Nouvelle catégorie','edit-title' => 'Modifier la catégorie','icon-bg' => 'from-indigo-500 to-purple-600']); ?>
         <?php $__env->slot('icon', null, []); ?> 
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            </svg>
         <?php $__env->endSlot(); ?>

        <form wire:submit.prevent="save" wire:key="category-form-<?php echo e($form->categoryId ?? 'new'); ?>">
            <?php if (isset($component)) { $__componentOriginal136d40145c4a8a37773366d184b33c29 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal136d40145c4a8a37773366d184b33c29 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.alpine-modal-body','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.alpine-modal-body'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <div class="space-y-5">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom de la catégorie <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            <input type="text" id="name" wire:model="form.name"
                                placeholder="Ex: Électronique, Vêtements, Alimentation..."
                                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" />
                        </div>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea id="description" wire:model.live="form.description" rows="4"
                            class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"
                            placeholder="Décrivez brièvement cette catégorie..."></textarea>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <!-- Info Box -->
                    <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-indigo-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-indigo-700">
                                    Les catégories vous permettent d'organiser vos produits et de faciliter la
                                    navigation dans votre inventaire.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal136d40145c4a8a37773366d184b33c29)): ?>
<?php $attributes = $__attributesOriginal136d40145c4a8a37773366d184b33c29; ?>
<?php unset($__attributesOriginal136d40145c4a8a37773366d184b33c29); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal136d40145c4a8a37773366d184b33c29)): ?>
<?php $component = $__componentOriginal136d40145c4a8a37773366d184b33c29; ?>
<?php unset($__componentOriginal136d40145c4a8a37773366d184b33c29); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal8e8eaa045f8a4c30dce91be3944639da = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e8eaa045f8a4c30dce91be3944639da = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.alpine-modal-footer','data' => ['submitText' => 'Créer','editSubmitText' => 'Mettre à jour','target' => 'save']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.alpine-modal-footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['submit-text' => 'Créer','edit-submit-text' => 'Mettre à jour','target' => 'save']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8e8eaa045f8a4c30dce91be3944639da)): ?>
<?php $attributes = $__attributesOriginal8e8eaa045f8a4c30dce91be3944639da; ?>
<?php unset($__attributesOriginal8e8eaa045f8a4c30dce91be3944639da); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e8eaa045f8a4c30dce91be3944639da)): ?>
<?php $component = $__componentOriginal8e8eaa045f8a4c30dce91be3944639da; ?>
<?php unset($__componentOriginal8e8eaa045f8a4c30dce91be3944639da); ?>
<?php endif; ?>
        </form>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal290e498d3a9be9ca04daade6fb284d0b)): ?>
<?php $attributes = $__attributesOriginal290e498d3a9be9ca04daade6fb284d0b; ?>
<?php unset($__attributesOriginal290e498d3a9be9ca04daade6fb284d0b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal290e498d3a9be9ca04daade6fb284d0b)): ?>
<?php $component = $__componentOriginal290e498d3a9be9ca04daade6fb284d0b; ?>
<?php unset($__componentOriginal290e498d3a9be9ca04daade6fb284d0b); ?>
<?php endif; ?>

    <!-- Delete Confirmation Modal -->
    <?php if (isset($component)) { $__componentOriginal8b7b112f0fae85419ee5abf8337434ab = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8b7b112f0fae85419ee5abf8337434ab = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.delete-confirmation-modal','data' => ['show' => 'showDeleteModal','itemName' => 'categoryName','itemType' => 'la catégorie','onConfirm' => '$wire.delete(categoryToDelete); showDeleteModal = false; categoryToDelete = null; categoryName = \'\'','onCancel' => 'showDeleteModal = false; categoryToDelete = null; categoryName = \'\'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('delete-confirmation-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['show' => 'showDeleteModal','itemName' => 'categoryName','itemType' => 'la catégorie','onConfirm' => '$wire.delete(categoryToDelete); showDeleteModal = false; categoryToDelete = null; categoryName = \'\'','onCancel' => 'showDeleteModal = false; categoryToDelete = null; categoryName = \'\'']); ?>
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
<?php /**PATH D:\stk\stk-back\resources\views/livewire/category/index.blade.php ENDPATH**/ ?>