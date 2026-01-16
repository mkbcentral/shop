<div x-data="{ showDeleteModal: false, saleToDelete: null, saleNumber: '', showCompleteModal: false, saleToComplete: null, completeNumber: '', showRestoreModal: false, saleToRestore: null, restoreNumber: '', showForceDeleteModal: false, saleToForceDelete: null, forceDeleteNumber: '' }">
 <?php $__env->slot('header', null, []); ?> 
    <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
        ['label' => 'Accueil', 'url' => route('dashboard')],
        ['label' => 'Ventes']
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['label' => 'Accueil', 'url' => route('dashboard')],
        ['label' => 'Ventes']
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

<div class="space-y-6">
    <div class="flex items-center justify-between mt-2">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Ventes</h1>
            <p class="text-gray-500 mt-1">Gérez vos ventes et transactions</p>
        </div>
        <div class="flex items-center space-x-3">
            <?php if (isset($component)) { $__componentOriginal8a31ff0802d1df0c26bb607f30439b3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a31ff0802d1df0c26bb607f30439b3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.button','data' => ['href' => ''.e(route('sales.create')).'','wire:navigate' => true,'icon' => 'plus']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('sales.create')).'','wire:navigate' => true,'icon' => 'plus']); ?>
                Nouvelle Vente
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
    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php if (isset($component)) { $__componentOriginala4ae059936bc185e758290466e2179c1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala4ae059936bc185e758290466e2179c1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.kpi-card','data' => ['title' => 'Ventes Complétées','value' => $stats['total_sales'],'color' => 'green']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('kpi-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Ventes Complétées','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stats['total_sales']),'color' => 'green']); ?>
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala4ae059936bc185e758290466e2179c1)): ?>
<?php $attributes = $__attributesOriginala4ae059936bc185e758290466e2179c1; ?>
<?php unset($__attributesOriginala4ae059936bc185e758290466e2179c1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala4ae059936bc185e758290466e2179c1)): ?>
<?php $component = $__componentOriginala4ae059936bc185e758290466e2179c1; ?>
<?php unset($__componentOriginala4ae059936bc185e758290466e2179c1); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginala4ae059936bc185e758290466e2179c1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala4ae059936bc185e758290466e2179c1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.kpi-card','data' => ['title' => 'Montant Total','value' => number_format($stats['total_amount'], 0, ',', ' ') . ' CDF','color' => 'indigo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('kpi-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Montant Total','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($stats['total_amount'], 0, ',', ' ') . ' CDF'),'color' => 'indigo']); ?>
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala4ae059936bc185e758290466e2179c1)): ?>
<?php $attributes = $__attributesOriginala4ae059936bc185e758290466e2179c1; ?>
<?php unset($__attributesOriginala4ae059936bc185e758290466e2179c1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala4ae059936bc185e758290466e2179c1)): ?>
<?php $component = $__componentOriginala4ae059936bc185e758290466e2179c1; ?>
<?php unset($__componentOriginala4ae059936bc185e758290466e2179c1); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginala4ae059936bc185e758290466e2179c1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala4ae059936bc185e758290466e2179c1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.kpi-card','data' => ['title' => 'Ventes en Attente','value' => $stats['pending_sales'],'color' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('kpi-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Ventes en Attente','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stats['pending_sales']),'color' => 'orange']); ?>
            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala4ae059936bc185e758290466e2179c1)): ?>
<?php $attributes = $__attributesOriginala4ae059936bc185e758290466e2179c1; ?>
<?php unset($__attributesOriginala4ae059936bc185e758290466e2179c1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala4ae059936bc185e758290466e2179c1)): ?>
<?php $component = $__componentOriginala4ae059936bc185e758290466e2179c1; ?>
<?php unset($__componentOriginala4ae059936bc185e758290466e2179c1); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginala4ae059936bc185e758290466e2179c1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala4ae059936bc185e758290466e2179c1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.kpi-card','data' => ['title' => 'Montant en Attente','value' => number_format($stats['pending_amount'], 0, ',', ' ') . ' CDF','color' => 'purple']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('kpi-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Montant en Attente','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($stats['pending_amount'], 0, ',', ' ') . ' CDF'),'color' => 'purple']); ?>
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala4ae059936bc185e758290466e2179c1)): ?>
<?php $attributes = $__attributesOriginala4ae059936bc185e758290466e2179c1; ?>
<?php unset($__attributesOriginala4ae059936bc185e758290466e2179c1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala4ae059936bc185e758290466e2179c1)): ?>
<?php $component = $__componentOriginala4ae059936bc185e758290466e2179c1; ?>
<?php unset($__componentOriginala4ae059936bc185e758290466e2179c1); ?>
<?php endif; ?>
    </div>

    <!-- Filters Card -->
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
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Filtres</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <?php if (isset($component)) { $__componentOriginal894294112bf23c4166443c90d4833959 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal894294112bf23c4166443c90d4833959 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.search-input','data' => ['wire:model.live.debounce.300ms' => 'search','wireModel' => 'search','placeholder' => 'Rechercher par numéro ou client...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.search-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live.debounce.300ms' => 'search','wireModel' => 'search','placeholder' => 'Rechercher par numéro ou client...']); ?>
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

            <!-- Client Filter -->
            <div>
                <?php if (isset($component)) { $__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.select','data' => ['wire:model.live' => 'clientFilter']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'clientFilter']); ?>
                    <option value="">Tous les clients</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($client->id); ?>"><?php echo e($client->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
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
            </div>

            <!-- Status Filter -->
            <div>
                <?php if (isset($component)) { $__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.select','data' => ['wire:model.live' => 'statusFilter']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'statusFilter']); ?>
                    <option value="">Tous les statuts</option>
                    <option value="pending">En attente</option>
                    <option value="completed">Complétée</option>
                    <option value="cancelled">Annulée</option>
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
            </div>

            <!-- Payment Status Filter -->
            <div>
                <?php if (isset($component)) { $__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.select','data' => ['wire:model.live' => 'paymentStatusFilter']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'paymentStatusFilter']); ?>
                    <option value="">Tous les paiements</option>
                    <option value="pending">En attente</option>
                    <option value="paid">Payé</option>
                    <option value="partial">Partiel</option>
                    <option value="refunded">Remboursé</option>
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
            </div>
        </div>

        <!-- Date Range Filter -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <?php if (isset($component)) { $__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.form-group','data' => ['label' => 'Date de début','for' => 'dateFrom']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Date de début','for' => 'dateFrom']); ?>
                <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['wire:model.live' => 'dateFrom','type' => 'date','id' => 'dateFrom']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'dateFrom','type' => 'date','id' => 'dateFrom']); ?>
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

            <?php if (isset($component)) { $__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.form-group','data' => ['label' => 'Date de fin','for' => 'dateTo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Date de fin','for' => 'dateTo']); ?>
                <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['wire:model.live' => 'dateTo','type' => 'date','id' => 'dateTo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'dateTo','type' => 'date','id' => 'dateTo']); ?>
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

    <!-- Sales List -->
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
            <?php if (isset($component)) { $__componentOriginal69d325773055bdef057fbaa2d9bf67ac = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69d325773055bdef057fbaa2d9bf67ac = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card-title','data' => ['title' => 'Liste des Ventes ('.e($sales->total()).')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card-title'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Liste des Ventes ('.e($sales->total()).')']); ?>
                 <?php $__env->slot('action', null, []); ?> 
                    <?php if (isset($component)) { $__componentOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8cee41e4af1fe2df52d1d5acd06eed36 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.select','data' => ['wire:model.live' => 'perPage','class' => 'text-sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'perPage','class' => 'text-sm']); ?>
                        <option value="15">15 par page</option>
                        <option value="25">25 par page</option>
                        <option value="50">50 par page</option>
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
         <?php $__env->endSlot(); ?>

        <!-- Success/Error Messages -->
        <!--[if BLOCK]><![endif]--><?php if(session()->has('success')): ?>
            <div class="mb-4" wire:key="success-<?php echo e(now()); ?>">
                <?php if (isset($component)) { $__componentOriginal16179ef69504a2a295686efbe20fc6dc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16179ef69504a2a295686efbe20fc6dc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.alert','data' => ['type' => 'success','message' => session('success')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'success','message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('success'))]); ?>
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
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <?php if(session()->has('error')): ?>
            <div class="mb-4" wire:key="error-<?php echo e(now()); ?>">
                <?php if (isset($component)) { $__componentOriginal16179ef69504a2a295686efbe20fc6dc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16179ef69504a2a295686efbe20fc6dc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.alert','data' => ['type' => 'error','message' => session('error')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'error','message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('error'))]); ?>
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
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.header','data' => ['sortable' => true,'sortKey' => 'sale_number']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['sortable' => true,'sortKey' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('sale_number')]); ?>Numéro <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.header','data' => ['sortable' => true,'sortKey' => 'sale_date']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['sortable' => true,'sortKey' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('sale_date')]); ?>Date <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes([]); ?>Client <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes([]); ?>Articles <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.header','data' => ['sortable' => true,'sortKey' => 'total']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['sortable' => true,'sortKey' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('total')]); ?>Montant <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes([]); ?>Paiement <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.header','data' => ['sortable' => true,'sortKey' => 'status']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['sortable' => true,'sortKey' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('status')]); ?>Statut <?php echo $__env->renderComponent(); ?>
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
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php if (isset($component)) { $__componentOriginalce497eb0b465689d7cb385400a2cd821 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce497eb0b465689d7cb385400a2cd821 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.row','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
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
                            <span class="text-sm font-medium text-gray-900"><?php echo e($sale->sale_number); ?></span>
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
                            <span class="text-sm text-gray-900"><?php echo e($sale->sale_date->format('d/m/Y')); ?></span>
                            <div class="text-xs text-gray-500"><?php echo e($sale->sale_date->format('H:i')); ?></div>
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
                            <!--[if BLOCK]><![endif]--><?php if($sale->client): ?>
                                <div class="text-sm font-medium text-gray-900"><?php echo e($sale->client->name); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e($sale->client->phone); ?></div>
                            <?php else: ?>
                                <span class="text-sm text-gray-500">Client anonyme</span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
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
                            <span class="text-sm text-gray-900"><?php echo e($sale->items->count()); ?> article(s)</span>
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
                            <div class="text-sm font-semibold text-gray-900">
                                <?php echo e(number_format($sale->total, 0, ',', ' ')); ?> CDF
                            </div>
                            <!--[if BLOCK]><![endif]--><?php if($sale->discount > 0): ?>
                                <div class="text-xs text-gray-500">
                                    Remise: <?php echo e(number_format($sale->discount, 0, ',', ' ')); ?> CDF
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php if($sale->payment_status === 'partial'): ?>
                                <div class="text-xs text-blue-600 font-medium">
                                    Payé: <?php echo e(number_format($sale->paid_amount, 0, ',', ' ')); ?> CDF
                                </div>
                                <div class="text-xs text-red-600">
                                    Reste: <?php echo e(number_format($sale->remaining_amount, 0, ',', ' ')); ?> CDF
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
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
                            <?php
                                $paymentColors = [
                                    'pending' => 'yellow',
                                    'paid' => 'green',
                                    'partial' => 'blue',
                                    'refunded' => 'red',
                                ];
                                $paymentLabels = [
                                    'pending' => 'En attente',
                                    'paid' => 'Payé',
                                    'partial' => 'Partiel',
                                    'refunded' => 'Remboursé',
                                ];
                            ?>
                            <?php if (isset($component)) { $__componentOriginal67da32da77738e740be7d0ce014e8f92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal67da32da77738e740be7d0ce014e8f92 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.badge','data' => ['color' => $paymentColors[$sale->payment_status],'dot' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($paymentColors[$sale->payment_status]),'dot' => true]); ?>
                                <?php echo e($paymentLabels[$sale->payment_status]); ?>

                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal67da32da77738e740be7d0ce014e8f92)): ?>
<?php $attributes = $__attributesOriginal67da32da77738e740be7d0ce014e8f92; ?>
<?php unset($__attributesOriginal67da32da77738e740be7d0ce014e8f92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal67da32da77738e740be7d0ce014e8f92)): ?>
<?php $component = $__componentOriginal67da32da77738e740be7d0ce014e8f92; ?>
<?php unset($__componentOriginal67da32da77738e740be7d0ce014e8f92); ?>
<?php endif; ?>
                            <div class="text-xs text-gray-500 mt-1">
                                <?php echo e(ucfirst($sale->payment_method)); ?>

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
                            <?php
                                $statusColors = [
                                    'pending' => 'yellow',
                                    'completed' => 'green',
                                    'cancelled' => 'red',
                                ];
                                $statusLabels = [
                                    'pending' => 'En attente',
                                    'completed' => 'Complétée',
                                    'cancelled' => 'Annulée',
                                ];
                            ?>
                            <?php if (isset($component)) { $__componentOriginal67da32da77738e740be7d0ce014e8f92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal67da32da77738e740be7d0ce014e8f92 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.badge','data' => ['color' => $statusColors[$sale->status],'dot' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($statusColors[$sale->status]),'dot' => true]); ?>
                                <?php echo e($statusLabels[$sale->status]); ?>

                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal67da32da77738e740be7d0ce014e8f92)): ?>
<?php $attributes = $__attributesOriginal67da32da77738e740be7d0ce014e8f92; ?>
<?php unset($__attributesOriginal67da32da77738e740be7d0ce014e8f92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal67da32da77738e740be7d0ce014e8f92)): ?>
<?php $component = $__componentOriginal67da32da77738e740be7d0ce014e8f92; ?>
<?php unset($__componentOriginal67da32da77738e740be7d0ce014e8f92); ?>
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
                            <?php if (isset($component)) { $__componentOriginalf6c151271844b557d2fb19dbc1eaf516 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf6c151271844b557d2fb19dbc1eaf516 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.actions','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                <!--[if BLOCK]><![endif]--><?php if($sale->status === 'pending'): ?>
                                    <?php if (isset($component)) { $__componentOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.action-button','data' => ['type' => 'button','@click' => 'showCompleteModal = true; saleToComplete = '.e($sale->id).'; completeNumber = \''.e($sale->sale_number).'\'','color' => 'green','title' => 'Compléter la vente']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.action-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','@click' => 'showCompleteModal = true; saleToComplete = '.e($sale->id).'; completeNumber = \''.e($sale->sale_number).'\'','color' => 'green','title' => 'Compléter la vente']); ?>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4)): ?>
<?php $attributes = $__attributesOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4; ?>
<?php unset($__attributesOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4)): ?>
<?php $component = $__componentOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4; ?>
<?php unset($__componentOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4); ?>
<?php endif; ?>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <!--[if BLOCK]><![endif]--><?php if($sale->status === 'cancelled'): ?>
                                    <?php if (isset($component)) { $__componentOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.action-button','data' => ['type' => 'button','@click' => 'showRestoreModal = true; saleToRestore = '.e($sale->id).'; restoreNumber = \''.e($sale->sale_number).'\'','color' => 'blue','title' => 'Réactiver la vente']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.action-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','@click' => 'showRestoreModal = true; saleToRestore = '.e($sale->id).'; restoreNumber = \''.e($sale->sale_number).'\'','color' => 'blue','title' => 'Réactiver la vente']); ?>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4)): ?>
<?php $attributes = $__attributesOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4; ?>
<?php unset($__attributesOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4)): ?>
<?php $component = $__componentOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4; ?>
<?php unset($__componentOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4); ?>
<?php endif; ?>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <?php if (isset($component)) { $__componentOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.action-button','data' => ['href' => ''.e(route('sales.edit', $sale->id)).'','wire:navigate' => true,'color' => 'indigo','title' => 'Modifier']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.action-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('sales.edit', $sale->id)).'','wire:navigate' => true,'color' => 'indigo','title' => 'Modifier']); ?>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4)): ?>
<?php $attributes = $__attributesOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4; ?>
<?php unset($__attributesOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4)): ?>
<?php $component = $__componentOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4; ?>
<?php unset($__componentOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4); ?>
<?php endif; ?>
                                <!--[if BLOCK]><![endif]--><?php if($sale->status !== 'cancelled'): ?>
                                    <?php if (isset($component)) { $__componentOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.action-button','data' => ['type' => 'button','@click' => 'showDeleteModal = true; saleToDelete = '.e($sale->id).'; saleNumber = \''.e($sale->sale_number).'\'','color' => 'red','title' => 'Annuler']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.action-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','@click' => 'showDeleteModal = true; saleToDelete = '.e($sale->id).'; saleNumber = \''.e($sale->sale_number).'\'','color' => 'red','title' => 'Annuler']); ?>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4)): ?>
<?php $attributes = $__attributesOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4; ?>
<?php unset($__attributesOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4)): ?>
<?php $component = $__componentOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4; ?>
<?php unset($__componentOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4); ?>
<?php endif; ?>
                                <?php else: ?>
                                    <?php if (isset($component)) { $__componentOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.action-button','data' => ['type' => 'button','@click' => 'showForceDeleteModal = true; saleToForceDelete = '.e($sale->id).'; forceDeleteNumber = \''.e($sale->sale_number).'\'','color' => 'red','title' => 'Supprimer définitivement']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.action-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','@click' => 'showForceDeleteModal = true; saleToForceDelete = '.e($sale->id).'; forceDeleteNumber = \''.e($sale->sale_number).'\'','color' => 'red','title' => 'Supprimer définitivement']); ?>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4)): ?>
<?php $attributes = $__attributesOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4; ?>
<?php unset($__attributesOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4)): ?>
<?php $component = $__componentOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4; ?>
<?php unset($__componentOriginal25db5b2b4ed745b6b0fe0dc3034b2fb4); ?>
<?php endif; ?>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf6c151271844b557d2fb19dbc1eaf516)): ?>
<?php $attributes = $__attributesOriginalf6c151271844b557d2fb19dbc1eaf516; ?>
<?php unset($__attributesOriginalf6c151271844b557d2fb19dbc1eaf516); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf6c151271844b557d2fb19dbc1eaf516)): ?>
<?php $component = $__componentOriginalf6c151271844b557d2fb19dbc1eaf516; ?>
<?php unset($__componentOriginalf6c151271844b557d2fb19dbc1eaf516); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.empty-state','data' => ['colspan' => '8','title' => 'Aucune vente','description' => 'Commencez par créer une nouvelle vente.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['colspan' => '8','title' => 'Aucune vente','description' => 'Commencez par créer une nouvelle vente.']); ?>
                         <?php $__env->slot('icon', null, []); ?> 
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
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

        <!--[if BLOCK]><![endif]--><?php if($sales->hasPages()): ?>
            <div class="mt-4">
                <?php echo e($sales->links()); ?>

            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
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

    <!-- Complete Confirmation Modal -->
    <div
        x-show="showCompleteModal"
        style="display: none;"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm"
        @click.self="showCompleteModal = false; saleToComplete = null; completeNumber = ''"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div
            class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6"
            @click.stop
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <!-- Message -->
            <div class="text-center mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    Confirmer la complétion
                </h3>
                <p class="text-sm text-gray-600 mb-3">
                    Voulez-vous vraiment compléter la vente
                </p>
                <p class="text-lg font-bold text-green-600" x-text="completeNumber"></p>
                <p class="text-xs text-gray-500 mt-2">
                    Cette action va déduire le stock et marquer la vente comme complétée.
                </p>
            </div>

            <!-- Boutons -->
            <div class="flex gap-3 justify-center">
                <button
                    type="button"
                    @click="showCompleteModal = false; saleToComplete = null; completeNumber = ''"
                    class="px-6 py-2.5 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2"
                >
                    Annuler
                </button>
                <button
                    type="button"
                    @click="$wire.set('saleToComplete', saleToComplete); $wire.call('completeSale'); showCompleteModal = false; saleToComplete = null; completeNumber = ''"
                    class="px-6 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                >
                    Compléter
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <?php if (isset($component)) { $__componentOriginal8b7b112f0fae85419ee5abf8337434ab = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8b7b112f0fae85419ee5abf8337434ab = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.delete-confirmation-modal','data' => ['show' => 'showDeleteModal','itemName' => 'saleNumber','itemType' => 'la vente','onConfirm' => '$wire.set(\'saleToDelete\', saleToDelete); $wire.call(\'delete\'); showDeleteModal = false','onCancel' => 'showDeleteModal = false; saleToDelete = null; saleNumber = \'\'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('delete-confirmation-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['show' => 'showDeleteModal','itemName' => 'saleNumber','itemType' => 'la vente','onConfirm' => '$wire.set(\'saleToDelete\', saleToDelete); $wire.call(\'delete\'); showDeleteModal = false','onCancel' => 'showDeleteModal = false; saleToDelete = null; saleNumber = \'\'']); ?>
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

    <!-- Restore Confirmation Modal -->
    <div
        x-show="showRestoreModal"
        style="display: none;"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm"
        @click.self="showRestoreModal = false; saleToRestore = null; restoreNumber = ''"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div
            class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6"
            @click.stop
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </div>

            <!-- Message -->
            <div class="text-center mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    Réactiver la vente
                </h3>
                <p class="text-sm text-gray-600 mb-3">
                    Voulez-vous réactiver la vente annulée
                </p>
                <p class="text-lg font-bold text-blue-600" x-text="restoreNumber"></p>
                <p class="text-xs text-gray-500 mt-2">
                    La vente sera remise en statut "En attente".
                </p>
            </div>

            <!-- Boutons -->
            <div class="flex gap-3 justify-center">
                <button
                    type="button"
                    @click="showRestoreModal = false; saleToRestore = null; restoreNumber = ''"
                    class="px-6 py-2.5 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2"
                >
                    Annuler
                </button>
                <button
                    type="button"
                    @click="$wire.set('saleToRestore', saleToRestore); $wire.call('restoreSale'); showRestoreModal = false; saleToRestore = null; restoreNumber = ''"
                    class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Réactiver
                </button>
            </div>
        </div>
    </div>

    <!-- Force Delete Confirmation Modal -->
    <div
        x-show="showForceDeleteModal"
        style="display: none;"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm"
        @click.self="showForceDeleteModal = false; saleToForceDelete = null; forceDeleteNumber = ''"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div
            class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6"
            @click.stop
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>

            <!-- Message -->
            <div class="text-center mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    Supprimer définitivement
                </h3>
                <p class="text-sm text-gray-600 mb-3">
                    Êtes-vous sûr de vouloir supprimer définitivement la vente
                </p>
                <p class="text-lg font-bold text-red-600" x-text="forceDeleteNumber"></p>
                <p class="text-xs text-red-500 mt-2 font-medium">
                    ⚠️ Cette action est irréversible. La vente et tous ses éléments seront supprimés du système.
                </p>
            </div>

            <!-- Boutons -->
            <div class="flex gap-3 justify-center">
                <button
                    type="button"
                    @click="showForceDeleteModal = false; saleToForceDelete = null; forceDeleteNumber = ''"
                    class="px-6 py-2.5 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2"
                >
                    Annuler
                </button>
                <button
                    type="button"
                    @click="$wire.set('saleToForceDelete', saleToForceDelete); $wire.call('forceDelete'); showForceDeleteModal = false; saleToForceDelete = null; forceDeleteNumber = ''"
                    class="px-6 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                >
                    Supprimer
                </button>
            </div>
        </div>
    </div>
</div>
</div>
<?php /**PATH D:\stk\stk-back\resources\views/livewire/sale/sale-index.blade.php ENDPATH**/ ?>