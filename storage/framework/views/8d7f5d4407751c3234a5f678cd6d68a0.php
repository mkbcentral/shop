<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['kpis']));

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

foreach (array_filter((['kpis']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <?php if (isset($component)) { $__componentOriginal032ff718c65cd947b9f503f201517db5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal032ff718c65cd947b9f503f201517db5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stock.kpi-card','data' => ['title' => 'Valeur du Stock','value' => number_format($kpis['total_stock_value'], 0, ',', ' ') . ' CDF','subtitle' => number_format($kpis['total_units'], 0, ',', ' ') . ' unités','color' => 'blue']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stock.kpi-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Valeur du Stock','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($kpis['total_stock_value'], 0, ',', ' ') . ' CDF'),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($kpis['total_units'], 0, ',', ' ') . ' unités'),'color' => 'blue']); ?>
         <?php $__env->slot('icon', null, []); ?> 
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal032ff718c65cd947b9f503f201517db5)): ?>
<?php $attributes = $__attributesOriginal032ff718c65cd947b9f503f201517db5; ?>
<?php unset($__attributesOriginal032ff718c65cd947b9f503f201517db5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal032ff718c65cd947b9f503f201517db5)): ?>
<?php $component = $__componentOriginal032ff718c65cd947b9f503f201517db5; ?>
<?php unset($__componentOriginal032ff718c65cd947b9f503f201517db5); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal032ff718c65cd947b9f503f201517db5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal032ff718c65cd947b9f503f201517db5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stock.kpi-card','data' => ['title' => 'Produits en Stock','value' => $kpis['in_stock_count'],'subtitle' => 'Sur ' . $kpis['total_products'] . ' total','color' => 'green']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stock.kpi-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Produits en Stock','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($kpis['in_stock_count']),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Sur ' . $kpis['total_products'] . ' total'),'color' => 'green']); ?>
         <?php $__env->slot('icon', null, []); ?> 
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal032ff718c65cd947b9f503f201517db5)): ?>
<?php $attributes = $__attributesOriginal032ff718c65cd947b9f503f201517db5; ?>
<?php unset($__attributesOriginal032ff718c65cd947b9f503f201517db5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal032ff718c65cd947b9f503f201517db5)): ?>
<?php $component = $__componentOriginal032ff718c65cd947b9f503f201517db5; ?>
<?php unset($__componentOriginal032ff718c65cd947b9f503f201517db5); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal032ff718c65cd947b9f503f201517db5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal032ff718c65cd947b9f503f201517db5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stock.kpi-card','data' => ['title' => 'Rupture de Stock','value' => $kpis['out_of_stock_count'],'subtitle' => 'Nécessitent réappro','color' => 'red']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stock.kpi-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Rupture de Stock','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($kpis['out_of_stock_count']),'subtitle' => 'Nécessitent réappro','color' => 'red']); ?>
         <?php $__env->slot('icon', null, []); ?> 
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal032ff718c65cd947b9f503f201517db5)): ?>
<?php $attributes = $__attributesOriginal032ff718c65cd947b9f503f201517db5; ?>
<?php unset($__attributesOriginal032ff718c65cd947b9f503f201517db5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal032ff718c65cd947b9f503f201517db5)): ?>
<?php $component = $__componentOriginal032ff718c65cd947b9f503f201517db5; ?>
<?php unset($__componentOriginal032ff718c65cd947b9f503f201517db5); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal032ff718c65cd947b9f503f201517db5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal032ff718c65cd947b9f503f201517db5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stock.kpi-card','data' => ['title' => 'Stock Faible','value' => $kpis['low_stock_count'],'subtitle' => 'Sous le seuil d\'alerte','color' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stock.kpi-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Stock Faible','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($kpis['low_stock_count']),'subtitle' => 'Sous le seuil d\'alerte','color' => 'orange']); ?>
         <?php $__env->slot('icon', null, []); ?> 
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal032ff718c65cd947b9f503f201517db5)): ?>
<?php $attributes = $__attributesOriginal032ff718c65cd947b9f503f201517db5; ?>
<?php unset($__attributesOriginal032ff718c65cd947b9f503f201517db5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal032ff718c65cd947b9f503f201517db5)): ?>
<?php $component = $__componentOriginal032ff718c65cd947b9f503f201517db5; ?>
<?php unset($__componentOriginal032ff718c65cd947b9f503f201517db5); ?>
<?php endif; ?>
</div>

<!-- Potential Profit Card (Optional - Full Width) -->
<?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['class' => 'bg-gradient-to-r from-indigo-500 to-purple-600 mb-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'bg-gradient-to-r from-indigo-500 to-purple-600 mb-6']); ?>
    <div class="flex items-center justify-between text-white">
        <div>
            <p class="text-sm font-medium opacity-90">Valeur de Vente Potentielle</p>
            <p class="text-2xl font-bold mt-1"><?php echo e(number_format($kpis['total_retail_value'], 0, ',', ' ')); ?> CDF</p>
            <p class="text-sm opacity-90 mt-1">
                Profit potentiel : <?php echo e(number_format($kpis['potential_profit'], 0, ',', ' ')); ?> CDF
                (<?php echo e($kpis['profit_margin_percentage']); ?>%)
            </p>
        </div>
        <div class="p-3 bg-black/20 rounded-lg">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
        </div>
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
<?php /**PATH D:\stk\stk-back\resources\views/components/stock/kpi-dashboard.blade.php ENDPATH**/ ?>