<div>
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

$__html = app('livewire')->mount($__name, $__params, 'lw-3161072720-0', $__slots ?? [], get_defined_vars());

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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
        ['label' => 'Accueil']
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['label' => 'Accueil']
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

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Tableau de bord</h1>
            <p class="text-gray-500 mt-1"><?php echo e(now()->translatedFormat('l d F Y')); ?></p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('stock.overview')); ?>" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Vue Stock
            </a>
        </div>
    </div>
 <?php $__env->endSlot(); ?>

<div class="space-y-6">
    <!-- Stats Grid with Modern Cards -->
    <?php if (isset($component)) { $__componentOriginal436eb3e054605506a954ec2fdb9412f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal436eb3e054605506a954ec2fdb9412f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stats-grid','data' => ['todaySales' => $today_sales,'salesGrowth' => $sales_growth,'totalProducts' => $total_products,'totalStockValue' => $total_stock_value,'lowStockAlerts' => $low_stock_alerts,'outOfStockAlerts' => $out_of_stock_alerts,'monthSales' => $month_sales,'totalSales' => $total_sales]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stats-grid'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['todaySales' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($today_sales),'salesGrowth' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sales_growth),'totalProducts' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($total_products),'totalStockValue' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($total_stock_value),'lowStockAlerts' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($low_stock_alerts),'outOfStockAlerts' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($out_of_stock_alerts),'monthSales' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($month_sales),'totalSales' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($total_sales)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal436eb3e054605506a954ec2fdb9412f6)): ?>
<?php $attributes = $__attributesOriginal436eb3e054605506a954ec2fdb9412f6; ?>
<?php unset($__attributesOriginal436eb3e054605506a954ec2fdb9412f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal436eb3e054605506a954ec2fdb9412f6)): ?>
<?php $component = $__componentOriginal436eb3e054605506a954ec2fdb9412f6; ?>
<?php unset($__componentOriginal436eb3e054605506a954ec2fdb9412f6); ?>
<?php endif; ?>

    <!-- Charts and Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Sales Chart -->
        <?php if (isset($component)) { $__componentOriginal643ccfe2aae87c3fbab9c1e803e83705 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643ccfe2aae87c3fbab9c1e803e83705 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.sales-chart','data' => ['chartData' => $sales_chart_data]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.sales-chart'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['chartData' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sales_chart_data)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643ccfe2aae87c3fbab9c1e803e83705)): ?>
<?php $attributes = $__attributesOriginal643ccfe2aae87c3fbab9c1e803e83705; ?>
<?php unset($__attributesOriginal643ccfe2aae87c3fbab9c1e803e83705); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643ccfe2aae87c3fbab9c1e803e83705)): ?>
<?php $component = $__componentOriginal643ccfe2aae87c3fbab9c1e803e83705; ?>
<?php unset($__componentOriginal643ccfe2aae87c3fbab9c1e803e83705); ?>
<?php endif; ?>

        <!-- Top Products -->
        <?php if (isset($component)) { $__componentOriginal7c77607f777be634de3390b11df06b52 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7c77607f777be634de3390b11df06b52 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.top-products','data' => ['products' => $top_products]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.top-products'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['products' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($top_products)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7c77607f777be634de3390b11df06b52)): ?>
<?php $attributes = $__attributesOriginal7c77607f777be634de3390b11df06b52; ?>
<?php unset($__attributesOriginal7c77607f777be634de3390b11df06b52); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7c77607f777be634de3390b11df06b52)): ?>
<?php $component = $__componentOriginal7c77607f777be634de3390b11df06b52; ?>
<?php unset($__componentOriginal7c77607f777be634de3390b11df06b52); ?>
<?php endif; ?>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick Actions - Takes 2 columns -->
        <div class="lg:col-span-2">
            <?php if (isset($component)) { $__componentOriginal5bca592398d23eb9b8270d3d32c25077 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bca592398d23eb9b8270d3d32c25077 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.quick-actions','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.quick-actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bca592398d23eb9b8270d3d32c25077)): ?>
<?php $attributes = $__attributesOriginal5bca592398d23eb9b8270d3d32c25077; ?>
<?php unset($__attributesOriginal5bca592398d23eb9b8270d3d32c25077); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bca592398d23eb9b8270d3d32c25077)): ?>
<?php $component = $__componentOriginal5bca592398d23eb9b8270d3d32c25077; ?>
<?php unset($__componentOriginal5bca592398d23eb9b8270d3d32c25077); ?>
<?php endif; ?>
        </div>

        <!-- Recent Activity & Stock Movements -->
        <?php if (isset($component)) { $__componentOriginale4ecc214a1a9440ea46ca47a7f66a6e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale4ecc214a1a9440ea46ca47a7f66a6e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stock-movements','data' => ['movements' => $recent_movements]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.stock-movements'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['movements' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($recent_movements)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale4ecc214a1a9440ea46ca47a7f66a6e2)): ?>
<?php $attributes = $__attributesOriginale4ecc214a1a9440ea46ca47a7f66a6e2; ?>
<?php unset($__attributesOriginale4ecc214a1a9440ea46ca47a7f66a6e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale4ecc214a1a9440ea46ca47a7f66a6e2)): ?>
<?php $component = $__componentOriginale4ecc214a1a9440ea46ca47a7f66a6e2; ?>
<?php unset($__componentOriginale4ecc214a1a9440ea46ca47a7f66a6e2); ?>
<?php endif; ?>
    </div>

    <!-- Recent Sales -->
    <?php if (isset($component)) { $__componentOriginal63457914689907f4ecab02f655ca7163 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal63457914689907f4ecab02f655ca7163 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.recent-sales','data' => ['sales' => $recent_sales]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard.recent-sales'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['sales' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($recent_sales)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal63457914689907f4ecab02f655ca7163)): ?>
<?php $attributes = $__attributesOriginal63457914689907f4ecab02f655ca7163; ?>
<?php unset($__attributesOriginal63457914689907f4ecab02f655ca7163); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal63457914689907f4ecab02f655ca7163)): ?>
<?php $component = $__componentOriginal63457914689907f4ecab02f655ca7163; ?>
<?php unset($__componentOriginal63457914689907f4ecab02f655ca7163); ?>
<?php endif; ?>
</div>
</div>
<?php /**PATH D:\stk\stk-back\resources\views/livewire/dashboard.blade.php ENDPATH**/ ?>