<?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['hover' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['hover' => true]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <?php if (isset($component)) { $__componentOriginal69d325773055bdef057fbaa2d9bf67ac = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69d325773055bdef057fbaa2d9bf67ac = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card-title','data' => ['title' => 'Actions Rapides']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card-title'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Actions Rapides']); ?>
             <?php $__env->slot('action', null, []); ?> 
                <button class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Tout voir →</button>
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

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <?php if (isset($component)) { $__componentOriginal8231460c0b3d7f1f09144053dd154b39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8231460c0b3d7f1f09144053dd154b39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.action-card','data' => ['href' => ''.e(route('pos.cash-register')).'','title' => 'Point de Vente','description' => 'Ouvrir la caisse POS','color' => 'blue']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('action-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('pos.cash-register')).'','title' => 'Point de Vente','description' => 'Ouvrir la caisse POS','color' => 'blue']); ?>
             <?php $__env->slot('icon', null, []); ?> 
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8231460c0b3d7f1f09144053dd154b39)): ?>
<?php $attributes = $__attributesOriginal8231460c0b3d7f1f09144053dd154b39; ?>
<?php unset($__attributesOriginal8231460c0b3d7f1f09144053dd154b39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8231460c0b3d7f1f09144053dd154b39)): ?>
<?php $component = $__componentOriginal8231460c0b3d7f1f09144053dd154b39; ?>
<?php unset($__componentOriginal8231460c0b3d7f1f09144053dd154b39); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal8231460c0b3d7f1f09144053dd154b39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8231460c0b3d7f1f09144053dd154b39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.action-card','data' => ['href' => ''.e(route('sales.create')).'','title' => 'Nouvelle Vente','description' => 'Enregistrer une vente','color' => 'indigo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('action-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('sales.create')).'','title' => 'Nouvelle Vente','description' => 'Enregistrer une vente','color' => 'indigo']); ?>
             <?php $__env->slot('icon', null, []); ?> 
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8231460c0b3d7f1f09144053dd154b39)): ?>
<?php $attributes = $__attributesOriginal8231460c0b3d7f1f09144053dd154b39; ?>
<?php unset($__attributesOriginal8231460c0b3d7f1f09144053dd154b39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8231460c0b3d7f1f09144053dd154b39)): ?>
<?php $component = $__componentOriginal8231460c0b3d7f1f09144053dd154b39; ?>
<?php unset($__componentOriginal8231460c0b3d7f1f09144053dd154b39); ?>
<?php endif; ?>

        <button
            @click="$dispatch('openProductModal')"
            class="group relative overflow-hidden bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 rounded-2xl p-6 text-left transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:-translate-y-2 hover:scale-105 block w-full">
            <!-- Decorative circles -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-green-400/20 rounded-full -mr-16 -mt-16 transition-transform duration-300 group-hover:scale-150"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-green-400/20 rounded-full -ml-12 -mb-12 transition-transform duration-300 group-hover:scale-125"></div>
            
            <!-- Shine effect on hover -->
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
            
            <div class="relative z-10">
                <div class="w-14 h-14 bg-white/25 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-4 transition-all duration-300 group-hover:scale-110 group-hover:bg-white/30 group-hover:rotate-3 shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <h4 class="text-white font-bold text-lg mb-2 transition-transform duration-300 group-hover:translate-x-1">Ajouter Produit</h4>
                <p class="text-white/90 text-sm font-medium flex items-center gap-1 transition-all duration-300 group-hover:gap-2">
                    Nouveau produit au stock
                    <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </p>
            </div>
        </button>

        <?php if (isset($component)) { $__componentOriginal8231460c0b3d7f1f09144053dd154b39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8231460c0b3d7f1f09144053dd154b39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.action-card','data' => ['href' => ''.e(route('stock.overview')).'','title' => 'Vue Stock','description' => 'Voir les statistiques','color' => 'purple']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('action-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('stock.overview')).'','title' => 'Vue Stock','description' => 'Voir les statistiques','color' => 'purple']); ?>
             <?php $__env->slot('icon', null, []); ?> 
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8231460c0b3d7f1f09144053dd154b39)): ?>
<?php $attributes = $__attributesOriginal8231460c0b3d7f1f09144053dd154b39; ?>
<?php unset($__attributesOriginal8231460c0b3d7f1f09144053dd154b39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8231460c0b3d7f1f09144053dd154b39)): ?>
<?php $component = $__componentOriginal8231460c0b3d7f1f09144053dd154b39; ?>
<?php unset($__componentOriginal8231460c0b3d7f1f09144053dd154b39); ?>
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
<?php /**PATH D:\stk\stk-back\resources\views/components/dashboard/quick-actions.blade.php ENDPATH**/ ?>