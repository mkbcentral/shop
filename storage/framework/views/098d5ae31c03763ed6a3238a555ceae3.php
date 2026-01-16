<div>
     <?php $__env->slot('header', null, []); ?> 
        <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
            ['label' => 'Accueil', 'url' => route('dashboard')],
            ['label' => 'Stock']
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['label' => 'Accueil', 'url' => route('dashboard')],
            ['label' => 'Stock']
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

    <!-- Page Header with Actions -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion du Stock</h1>
            <p class="text-gray-500 mt-1">Gérez les entrées et sorties de stock</p>
        </div>
        <div class="flex space-x-3">
            <?php if (isset($component)) { $__componentOriginal8a31ff0802d1df0c26bb607f30439b3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a31ff0802d1df0c26bb607f30439b3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.button','data' => ['wire:click' => 'openAddModal','variant' => 'success','icon' => 'plus']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'openAddModal','variant' => 'success','icon' => 'plus']); ?>
                Ajouter Stock
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.button','data' => ['wire:click' => 'openRemoveModal','variant' => 'danger','icon' => 'minus']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'openRemoveModal','variant' => 'danger','icon' => 'minus']); ?>
                Retirer Stock
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.button','data' => ['wire:click' => 'openAdjustModal','variant' => 'warning','icon' => 'adjustments']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'openAdjustModal','variant' => 'warning','icon' => 'adjustments']); ?>
                Ajuster Stock
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

    <!-- Flash Messages -->
    <?php if (isset($component)) { $__componentOriginal16179ef69504a2a295686efbe20fc6dc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16179ef69504a2a295686efbe20fc6dc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.alert','data' => ['type' => 'success','message' => session('message'),'class' => 'mb-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'success','message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('message')),'class' => 'mb-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal16179ef69504a2a295686efbe20fc6dc)): ?>
<?php $attributes = $__attributesOriginal16179ef69504a2a295686efbe20fc6dc; ?>
<?php unset($__attributesOriginal16179ef69504a2a295686efbe20fc6dc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal16179ef69504a2a295686efbe20fc6dc)): ?>
<?php $component = $__componentOriginal16179ef69504a2a295686efbe20fc6dc; ?>
<?php unset($__componentOriginal16179ef69504a2a295686efbe20fc6dc); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal16179ef69504a2a295686efbe20fc6dc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16179ef69504a2a295686efbe20fc6dc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.alert','data' => ['type' => 'error','message' => session('error'),'class' => 'mb-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'error','message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('error')),'class' => 'mb-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal16179ef69504a2a295686efbe20fc6dc)): ?>
<?php $attributes = $__attributesOriginal16179ef69504a2a295686efbe20fc6dc; ?>
<?php unset($__attributesOriginal16179ef69504a2a295686efbe20fc6dc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal16179ef69504a2a295686efbe20fc6dc)): ?>
<?php $component = $__componentOriginal16179ef69504a2a295686efbe20fc6dc; ?>
<?php unset($__componentOriginal16179ef69504a2a295686efbe20fc6dc); ?>
<?php endif; ?>

    <!-- Search and Filters -->
    <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Search -->
            <?php if (isset($component)) { $__componentOriginal894294112bf23c4166443c90d4833959 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal894294112bf23c4166443c90d4833959 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.search-input','data' => ['wire:model.live.debounce.300ms' => 'search','wireModel' => 'search','placeholder' => 'Rechercher produit ou référence...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.search-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live.debounce.300ms' => 'search','wireModel' => 'search','placeholder' => 'Rechercher produit ou référence...']); ?>
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

            <!-- Type Filter -->
            <?php if (isset($component)) { $__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.select','data' => ['wire:model.live' => 'type']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'type']); ?>
                <option value="">Tous les types</option>
                <option value="in">Entrées</option>
                <option value="out">Sorties</option>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36)): ?>
<?php $attributes = $__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36; ?>
<?php unset($__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36)): ?>
<?php $component = $__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36; ?>
<?php unset($__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36); ?>
<?php endif; ?>

            <!-- Movement Type Filter -->
            <?php if (isset($component)) { $__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.select','data' => ['wire:model.live' => 'movementType']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'movementType']); ?>
                <option value="">Tous les mouvements</option>
                <option value="purchase">Achat</option>
                <option value="sale">Vente</option>
                <option value="adjustment">Ajustement</option>
                <option value="transfer">Transfert</option>
                <option value="return">Retour</option>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36)): ?>
<?php $attributes = $__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36; ?>
<?php unset($__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36)): ?>
<?php $component = $__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36; ?>
<?php unset($__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36); ?>
<?php endif; ?>

            <!-- Per Page Selector -->
            <?php if (isset($component)) { $__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.select','data' => ['wire:model.live' => 'perPage']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'perPage']); ?>
                <option value="10">10 par page</option>
                <option value="25">25 par page</option>
                <option value="50">50 par page</option>
                <option value="100">100 par page</option>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36)): ?>
<?php $attributes = $__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36; ?>
<?php unset($__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36)): ?>
<?php $component = $__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36; ?>
<?php unset($__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36); ?>
<?php endif; ?>

            <!-- View Mode Toggle -->
            <?php if (isset($component)) { $__componentOriginal8a31ff0802d1df0c26bb607f30439b3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a31ff0802d1df0c26bb607f30439b3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.button','data' => ['wire:click' => 'toggleViewMode','variant' => 'secondary','class' => 'w-full justify-center']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'toggleViewMode','variant' => 'secondary','class' => 'w-full justify-center']); ?>
                <!--[if BLOCK]><![endif]--><?php if($viewMode === 'grouped'): ?>
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    Vue détaillée
                <?php else: ?>
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Vue groupée
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
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

        <!-- Date Range -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            <div>
                <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['for' => 'dateFrom']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'dateFrom']); ?>Date début <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['type' => 'date','wire:model.live' => 'dateFrom','id' => 'dateFrom']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'date','wire:model.live' => 'dateFrom','id' => 'dateFrom']); ?>
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
            </div>
            <div>
                <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['for' => 'dateTo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'dateTo']); ?>Date fin <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['type' => 'date','wire:model.live' => 'dateTo','id' => 'dateTo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'date','wire:model.live' => 'dateTo','id' => 'dateTo']); ?>
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
            </div>
            <div>
                <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Exports <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                <!-- Export PDF Dropdown -->
                <?php if (isset($component)) { $__componentOriginaldf8083d4a852c446488d8d384bbc7cbe = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf8083d4a852c446488d8d384bbc7cbe = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown','data' => ['align' => 'right','width' => '56']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right','width' => '56']); ?>
                     <?php $__env->slot('trigger', null, []); ?> 
                        <button class="inline-flex items-center justify-center w-full px-4 py-2.5 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-medium rounded-lg shadow-sm transition duration-150">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Exports PDF
                            <svg class="w-4 h-4 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                     <?php $__env->endSlot(); ?>

                    <?php if (isset($component)) { $__componentOriginal6b1d0d55421798f4a1c7b596bea6c022 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6b1d0d55421798f4a1c7b596bea6c022 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown-item','data' => ['href' => ''.e(route('reports.stock-movements', ['date_from' => $dateFrom, 'date_to' => $dateTo, 'type' => $type, 'movement_type' => $movementType])).'','icon' => 'clipboard-list','iconColor' => 'text-blue-500']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('reports.stock-movements', ['date_from' => $dateFrom, 'date_to' => $dateTo, 'type' => $type, 'movement_type' => $movementType])).'','icon' => 'clipboard-list','iconColor' => 'text-blue-500']); ?>
                        Mouvements de stock
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown-item','data' => ['href' => ''.e(route('reports.stock')).'','icon' => 'cube','iconColor' => 'text-green-500']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('reports.stock')).'','icon' => 'cube','iconColor' => 'text-green-500']); ?>
                        État du stock
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown-item','data' => ['href' => ''.e(route('reports.inventory')).'','icon' => 'calculator','iconColor' => 'text-purple-500']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('reports.inventory')).'','icon' => 'calculator','iconColor' => 'text-purple-500']); ?>
                        Inventaire valorisé
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown-item','data' => ['href' => ''.e(route('reports.stock-alerts')).'','icon' => 'exclamation-triangle','iconColor' => 'text-red-500']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('reports.stock-alerts')).'','icon' => 'exclamation-triangle','iconColor' => 'text-red-500']); ?>
                        Alertes de stock
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
<?php if (isset($__attributesOriginaldf8083d4a852c446488d8d384bbc7cbe)): ?>
<?php $attributes = $__attributesOriginaldf8083d4a852c446488d8d384bbc7cbe; ?>
<?php unset($__attributesOriginaldf8083d4a852c446488d8d384bbc7cbe); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldf8083d4a852c446488d8d384bbc7cbe)): ?>
<?php $component = $__componentOriginaldf8083d4a852c446488d8d384bbc7cbe; ?>
<?php unset($__componentOriginaldf8083d4a852c446488d8d384bbc7cbe); ?>
<?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Table - Grouped View -->
    <!--[if BLOCK]><![endif]--><?php if($viewMode === 'grouped'): ?>
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
<?php $component->withAttributes([]); ?>Produit <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes(['align' => 'center']); ?>Nb Mouvements <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes(['align' => 'center']); ?>Total Entrées <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes(['align' => 'center']); ?>Total Sorties <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes(['align' => 'center']); ?>Variation Nette <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes([]); ?>Dernier Mouvement <?php echo $__env->renderComponent(); ?>
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
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $groupedMovements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php if (isset($component)) { $__componentOriginalce497eb0b465689d7cb385400a2cd821 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce497eb0b465689d7cb385400a2cd821 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.row','data' => ['wire:key' => 'group-'.e($group->product_variant_id).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:key' => 'group-'.e($group->product_variant_id).'']); ?>
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
                                <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($group->productVariant->product->name); ?></div>
                                    <div class="text-xs text-gray-500">
                                        SKU: <?php echo e($group->productVariant->sku); ?>

                                        <!--[if BLOCK]><![endif]--><?php if($group->productVariant->size || $group->productVariant->color): ?>
                                            - <?php echo e($group->productVariant->size); ?> <?php echo e($group->productVariant->color); ?>

                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.cell','data' => ['align' => 'center']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'center']); ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                <?php echo e($group->movement_count); ?>

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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.cell','data' => ['align' => 'center']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'center']); ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                </svg>
                                +<?php echo e($group->total_in); ?>

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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.cell','data' => ['align' => 'center']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'center']); ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                </svg>
                                -<?php echo e($group->total_out); ?>

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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.cell','data' => ['align' => 'center']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'center']); ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold <?php echo e($group->net_change >= 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-orange-100 text-orange-800'); ?>">
                                <?php echo e($group->net_change >= 0 ? '+' : ''); ?><?php echo e($group->net_change); ?>

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
                            <span class="text-sm text-gray-900"><?php echo e(\Carbon\Carbon::parse($group->last_date)->format('d/m/Y')); ?></span>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown-item','data' => ['wireClick' => 'openDetailsModal('.e($group->product_variant_id).')','icon' => 'eye']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wireClick' => 'openDetailsModal('.e($group->product_variant_id).')','icon' => 'eye']); ?>
                                    Voir les détails
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown-item','data' => ['href' => ''.e(route('stock.history', $group->product_variant_id)).'','wireNavigate' => true,'icon' => 'clock']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('stock.history', $group->product_variant_id)).'','wireNavigate' => true,'icon' => 'clock']); ?>
                                    Historique complet
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.empty-state','data' => ['colspan' => '7','title' => 'Aucun mouvement de stock trouvé','description' => 'Commencez par ajouter des mouvements de stock.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['colspan' => '7','title' => 'Aucun mouvement de stock trouvé','description' => 'Commencez par ajouter des mouvements de stock.']); ?>
                         <?php $__env->slot('action', null, []); ?> 
                            <?php if (isset($component)) { $__componentOriginal8a31ff0802d1df0c26bb607f30439b3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a31ff0802d1df0c26bb607f30439b3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.button','data' => ['wire:click' => 'openAddModal','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'openAddModal','size' => 'sm']); ?>
                                <svg class="w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Ajouter Stock
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
        <!--[if BLOCK]><![endif]--><?php if($groupedMovements->hasPages()): ?>
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-t border-gray-200">
                <?php echo e($groupedMovements->links()); ?>

            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
    <?php else: ?>
    <!-- Table - Detailed View -->
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
<?php $component->withAttributes([]); ?>Date <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes([]); ?>Produit <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes([]); ?>Type <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes([]); ?>Mouvement <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes([]); ?>Quantité <?php echo $__env->renderComponent(); ?>
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
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $movements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php if (isset($component)) { $__componentOriginalce497eb0b465689d7cb385400a2cd821 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce497eb0b465689d7cb385400a2cd821 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.row','data' => ['wire:key' => 'movement-'.e($movement->id).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:key' => 'movement-'.e($movement->id).'']); ?>
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
                            <span class="text-sm text-gray-900 font-medium"><?php echo e($movement->date->format('d/m/Y')); ?></span>
                            <span class="text-xs text-gray-500 block"><?php echo e($movement->created_at->format('H:i')); ?></span>
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
                            <div class="flex items-center">
                                <div class="ml-0">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($movement->productVariant->product->name); ?></div>
                                    <div class="text-xs text-gray-500">
                                        SKU: <?php echo e($movement->productVariant->sku); ?>

                                        <!--[if BLOCK]><![endif]--><?php if($movement->productVariant->size || $movement->productVariant->color): ?>
                                            - <?php echo e($movement->productVariant->size); ?> <?php echo e($movement->productVariant->color); ?>

                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
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
                            <div>
                                <!--[if BLOCK]><![endif]--><?php if($movement->type === 'in'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                        </svg>
                                        Entrée
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                        </svg>
                                        Sortie
                                    </span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <div class="text-xs text-gray-500 mt-1"><?php echo e($movement->reference ?: '—'); ?></div>
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
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $movement->movement_type))); ?>

                                </span>
                                <div class="text-xs text-gray-500 mt-1"><?php echo e($movement->user->name); ?></div>
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
                            <span class="text-sm font-semibold <?php echo e($movement->type === 'in' ? 'text-green-600' : 'text-red-600'); ?>">
                                <?php echo e($movement->type === 'in' ? '+' : '-'); ?><?php echo e($movement->quantity); ?>

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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.cell','data' => ['align' => 'center']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'center']); ?>
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
                                <!--[if BLOCK]><![endif]--><?php if($movement->movement_type !== 'sale'): ?>
                                    <?php if (isset($component)) { $__componentOriginal6b1d0d55421798f4a1c7b596bea6c022 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6b1d0d55421798f4a1c7b596bea6c022 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown-item','data' => ['wireClick' => 'openEditModal('.e($movement->id).')','icon' => 'edit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wireClick' => 'openEditModal('.e($movement->id).')','icon' => 'edit']); ?>
                                        Modifier
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
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <?php if (isset($component)) { $__componentOriginal6b1d0d55421798f4a1c7b596bea6c022 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6b1d0d55421798f4a1c7b596bea6c022 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown-item','data' => ['href' => ''.e(route('stock.history', $movement->product_variant_id)).'','wireNavigate' => true,'icon' => 'clock']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('stock.history', $movement->product_variant_id)).'','wireNavigate' => true,'icon' => 'clock']); ?>
                                    Historique complet
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.empty-state','data' => ['colspan' => '8','title' => 'Aucun mouvement de stock trouvé','description' => 'Commencez par ajouter des mouvements de stock.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['colspan' => '8','title' => 'Aucun mouvement de stock trouvé','description' => 'Commencez par ajouter des mouvements de stock.']); ?>
                         <?php $__env->slot('action', null, []); ?> 
                            <?php if (isset($component)) { $__componentOriginal8a31ff0802d1df0c26bb607f30439b3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a31ff0802d1df0c26bb607f30439b3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.button','data' => ['wire:click' => 'openAddModal','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'openAddModal','size' => 'sm']); ?>
                                <svg class="w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Ajouter Stock
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
        <!--[if BLOCK]><![endif]--><?php if($movements->hasPages()): ?>
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-t border-gray-200">
                <?php echo e($movements->links()); ?>

            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Add Stock Modal -->
    <?php echo $__env->make('livewire.stock.modals.add-stock-new', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Remove Stock Modal -->
    <?php echo $__env->make('livewire.stock.modals.remove-stock-new', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Adjust Stock Modal -->
    <?php echo $__env->make('livewire.stock.modals.adjust-stock-new', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Edit Movement Modal -->
    <?php echo $__env->make('livewire.stock.modals.edit-movement', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Product Movements Details Modal -->
    <!--[if BLOCK]><![endif]--><?php if($showDetailsModal): ?>
    <div x-show="$wire.showDetailsModal"
         x-cloak
         x-on:keydown.escape.window="$wire.closeDetailsModal()"
         x-init="$watch('$wire.showDetailsModal', value => { document.body.style.overflow = value ? 'hidden' : '' })"
         class="fixed inset-0 z-50 overflow-hidden"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">

        <!-- Backdrop -->
        <div x-show="$wire.showDetailsModal"
             x-on:click="$wire.closeDetailsModal()"
             x-transition.opacity.duration.150ms
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm">
        </div>

        <!-- Modal Container -->
        <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
            <div x-show="$wire.showDetailsModal"
                 x-on:click.stop
                 x-transition:enter="ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-100"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-white rounded-2xl shadow-2xl w-full sm:max-w-4xl flex flex-col pointer-events-auto"
                 style="max-height: 90vh;">

                <div class="bg-white rounded-xl shadow-xl flex flex-col min-h-0 flex-1">
                    <!-- Modal Header -->
                    <div class="flex-shrink-0 bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-white">
                            Détails des mouvements - <?php echo e($selectedProductName); ?>

                        </h3>
                        <button type="button" wire:click="closeDetailsModal"
                            class="text-white/80 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Summary Stats -->
                    <?php
                        $totalIn = collect($selectedProductMovements)->where('type', 'in')->sum('quantity');
                        $totalOut = collect($selectedProductMovements)->where('type', 'out')->sum('quantity');
                        $netChange = $totalIn - $totalOut;
                    ?>
                    <div class="flex-shrink-0 bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="grid grid-cols-4 gap-4">
                <div class="text-center">
                    <p class="text-sm text-gray-500">Total Mouvements</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e(count($selectedProductMovements)); ?></p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-500">Total Entrées</p>
                    <p class="text-2xl font-bold text-green-600">+<?php echo e($totalIn); ?></p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-500">Total Sorties</p>
                    <p class="text-2xl font-bold text-red-600">-<?php echo e($totalOut); ?></p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-500">Variation Nette</p>
                    <p class="text-2xl font-bold <?php echo e($netChange >= 0 ? 'text-emerald-600' : 'text-orange-600'); ?>">
                        <?php echo e($netChange >= 0 ? '+' : ''); ?><?php echo e($netChange); ?>

                    </p>
                </div>
                        </div>

                        <!-- Movements List -->
                        <div class="flex-1 overflow-y-auto px-6 py-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 sticky top-0">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mouvement</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $selectedProductMovements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="text-sm text-gray-900"><?php echo e(\Carbon\Carbon::parse($movement['date'])->format('d/m/Y')); ?></span>
                                <span class="text-xs text-gray-500 block"><?php echo e(\Carbon\Carbon::parse($movement['created_at'])->format('H:i')); ?></span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <!--[if BLOCK]><![endif]--><?php if($movement['type'] === 'in'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                        </svg>
                                        Entrée
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                        </svg>
                                        Sortie
                                    </span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $movement['movement_type']))); ?>

                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <span class="text-sm font-semibold <?php echo e($movement['type'] === 'in' ? 'text-green-600' : 'text-red-600'); ?>">
                                    <?php echo e($movement['type'] === 'in' ? '+' : '-'); ?><?php echo e($movement['quantity']); ?>

                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="text-sm text-gray-500"><?php echo e($movement['reference'] ?: '—'); ?></span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="text-sm text-gray-900"><?php echo e($movement['user']['name'] ?? '—'); ?></span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="flex-shrink-0 bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200">
            <button type="button" wire:click="closeDetailsModal"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Fermer
            </button>
        </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH D:\stk\stk-back\resources\views/livewire/stock/index.blade.php ENDPATH**/ ?>