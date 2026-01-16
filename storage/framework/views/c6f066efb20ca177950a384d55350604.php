<div>
     <?php $__env->slot('header', null, []); ?> 
        <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
            ['label' => 'Accueil', 'url' => route('dashboard')],
            ['label' => 'État du Stock']
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['label' => 'Accueil', 'url' => route('dashboard')],
            ['label' => 'État du Stock']
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

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">État du Stock</h1>
            <p class="text-gray-500 mt-1">Vue d'ensemble de votre inventaire et valorisation</p>
        </div>
        <div class="flex space-x-3">
            <!-- Link to Movements -->
            <?php if (isset($component)) { $__componentOriginal8a31ff0802d1df0c26bb607f30439b3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a31ff0802d1df0c26bb607f30439b3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.button','data' => ['href' => ''.e(route('stock.index')).'','variant' => 'secondary','icon' => 'clock']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('stock.index')).'','variant' => 'secondary','icon' => 'clock']); ?>
                Historique des Mouvements
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
    <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-green-800"><?php echo e(session('message')); ?></p>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <?php if(session()->has('error')): ?>
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-800"><?php echo e(session('error')); ?></p>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- KPI Dashboard / Loading Skeleton -->
    <div class="mb-6">
        <div wire:loading.remove wire:target="search,categoryId,stockLevel,sortField,sortDirection,perPage">
            <?php if (isset($component)) { $__componentOriginaldde0b6ae89b4ab933f06d14ecf478519 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldde0b6ae89b4ab933f06d14ecf478519 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stock.kpi-dashboard','data' => ['kpis' => $kpis]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stock.kpi-dashboard'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['kpis' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($kpis)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldde0b6ae89b4ab933f06d14ecf478519)): ?>
<?php $attributes = $__attributesOriginaldde0b6ae89b4ab933f06d14ecf478519; ?>
<?php unset($__attributesOriginaldde0b6ae89b4ab933f06d14ecf478519); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldde0b6ae89b4ab933f06d14ecf478519)): ?>
<?php $component = $__componentOriginaldde0b6ae89b4ab933f06d14ecf478519; ?>
<?php unset($__componentOriginaldde0b6ae89b4ab933f06d14ecf478519); ?>
<?php endif; ?>
        </div>

        <div wire:loading wire:target="search,categoryId,stockLevel,sortField,sortDirection,perPage">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!--[if BLOCK]><![endif]--><?php for($i = 0; $i < 4; $i++): ?>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="h-4 bg-gray-200 rounded w-2/3 mb-2 animate-pulse"></div>
                                <div class="h-6 bg-gray-200 rounded w-1/2 mb-1 animate-pulse"></div>
                                <div class="h-3 bg-gray-200 rounded w-1/3 animate-pulse"></div>
                            </div>
                            <div class="h-10 w-10 bg-gray-100 rounded-lg animate-pulse"></div>
                        </div>
                    </div>
                <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Skeleton for Profit Card -->
            <div class="bg-gradient-to-r from-gray-400 to-gray-500 rounded-lg shadow-sm p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="h-4 bg-white/30 rounded w-1/3 mb-2 animate-pulse"></div>
                        <div class="h-7 bg-white/30 rounded w-1/2 mb-2 animate-pulse"></div>
                        <div class="h-3 bg-white/30 rounded w-2/3 animate-pulse"></div>
                    </div>
                    <div class="h-12 w-12 bg-white/20 rounded-lg animate-pulse"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <?php if (isset($component)) { $__componentOriginal2455636c2a34ec7a3dac0ae3b9aca79d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2455636c2a34ec7a3dac0ae3b9aca79d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stock.filters','data' => ['categories' => $categories,'search' => $search,'categoryId' => $categoryId,'stockLevel' => $stockLevel]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stock.filters'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['categories' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($categories),'search' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($search),'categoryId' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($categoryId),'stockLevel' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stockLevel)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2455636c2a34ec7a3dac0ae3b9aca79d)): ?>
<?php $attributes = $__attributesOriginal2455636c2a34ec7a3dac0ae3b9aca79d; ?>
<?php unset($__attributesOriginal2455636c2a34ec7a3dac0ae3b9aca79d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2455636c2a34ec7a3dac0ae3b9aca79d)): ?>
<?php $component = $__componentOriginal2455636c2a34ec7a3dac0ae3b9aca79d; ?>
<?php unset($__componentOriginal2455636c2a34ec7a3dac0ae3b9aca79d); ?>
<?php endif; ?>

    <!-- Toolbar & Table Content -->
    <div wire:loading.remove wire:target="search,categoryId,stockLevel,sortField,sortDirection,perPage">
        <!-- Toolbar -->
        <?php if (isset($component)) { $__componentOriginal14e0d78b15b6e25a49dc7eed4078c999 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14e0d78b15b6e25a49dc7eed4078c999 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stock.toolbar','data' => ['total' => $variants->total(),'sortField' => $sortField,'sortDirection' => $sortDirection]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stock.toolbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['total' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($variants->total()),'sortField' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'sortDirection' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal14e0d78b15b6e25a49dc7eed4078c999)): ?>
<?php $attributes = $__attributesOriginal14e0d78b15b6e25a49dc7eed4078c999; ?>
<?php unset($__attributesOriginal14e0d78b15b6e25a49dc7eed4078c999); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal14e0d78b15b6e25a49dc7eed4078c999)): ?>
<?php $component = $__componentOriginal14e0d78b15b6e25a49dc7eed4078c999; ?>
<?php unset($__componentOriginal14e0d78b15b6e25a49dc7eed4078c999); ?>
<?php endif; ?>

        <!-- Inventory Table -->
        <?php if (isset($component)) { $__componentOriginalad93c17759702825ef20718a403b7a2f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad93c17759702825ef20718a403b7a2f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stock.inventory-table','data' => ['variants' => $variants]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stock.inventory-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variants' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($variants)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalad93c17759702825ef20718a403b7a2f)): ?>
<?php $attributes = $__attributesOriginalad93c17759702825ef20718a403b7a2f; ?>
<?php unset($__attributesOriginalad93c17759702825ef20718a403b7a2f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalad93c17759702825ef20718a403b7a2f)): ?>
<?php $component = $__componentOriginalad93c17759702825ef20718a403b7a2f; ?>
<?php unset($__componentOriginalad93c17759702825ef20718a403b7a2f); ?>
<?php endif; ?>

        <!-- Pagination -->
        <div class="mt-4">
            <?php echo e($variants->links()); ?>

        </div>
    </div>

    <!-- Loading Skeleton for Toolbar & Table -->
    <div wire:loading wire:target="search,categoryId,stockLevel,sortField,sortDirection,perPage">
        <!-- Toolbar Skeleton -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
            <div class="flex items-center justify-between">
                <div class="h-5 bg-gray-200 rounded w-32 animate-pulse"></div>
                <div class="flex space-x-2">
                    <div class="h-10 w-24 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-10 w-24 bg-gray-200 rounded animate-pulse"></div>
                </div>
            </div>
        </div>

        <!-- Table Skeleton -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left"><div class="h-4 bg-gray-200 rounded w-24 animate-pulse"></div></th>
                            <th class="px-6 py-3 text-left"><div class="h-4 bg-gray-200 rounded w-16 animate-pulse"></div></th>
                            <th class="px-6 py-3 text-center"><div class="h-4 bg-gray-200 rounded w-16 mx-auto animate-pulse"></div></th>
                            <th class="px-6 py-3 text-center"><div class="h-4 bg-gray-200 rounded w-16 mx-auto animate-pulse"></div></th>
                            <th class="px-6 py-3 text-right"><div class="h-4 bg-gray-200 rounded w-24 ml-auto animate-pulse"></div></th>
                            <th class="px-6 py-3 text-right"><div class="h-4 bg-gray-200 rounded w-24 ml-auto animate-pulse"></div></th>
                            <th class="px-6 py-3 text-center"><div class="h-4 bg-gray-200 rounded w-16 mx-auto animate-pulse"></div></th>
                            <th class="px-6 py-3 text-right"><div class="h-4 bg-gray-200 rounded w-20 ml-auto animate-pulse"></div></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!--[if BLOCK]><![endif]--><?php for($i = 0; $i < 10; $i++): ?>
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="h-4 bg-gray-200 rounded w-32 mb-2 animate-pulse"></div>
                                    <div class="h-3 bg-gray-200 rounded w-24 animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="h-4 bg-gray-200 rounded w-20 animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="h-6 bg-gray-200 rounded w-12 mx-auto animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="h-4 bg-gray-200 rounded w-8 mx-auto animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="h-4 bg-gray-200 rounded w-20 ml-auto animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="h-4 bg-gray-200 rounded w-24 ml-auto animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="h-5 bg-gray-200 rounded-full w-20 mx-auto animate-pulse"></div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                                        <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                                    </div>
                                </td>
                            </tr>
                        <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Adjust Modal -->
    <?php if (isset($component)) { $__componentOriginald4e1f955cac178c96b95346b7474b7b5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald4e1f955cac178c96b95346b7474b7b5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stock.adjust-modal','data' => ['adjustingVariant' => $adjustingVariant,'newQuantity' => $newQuantity]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stock.adjust-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['adjustingVariant' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($adjustingVariant),'newQuantity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($newQuantity)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald4e1f955cac178c96b95346b7474b7b5)): ?>
<?php $attributes = $__attributesOriginald4e1f955cac178c96b95346b7474b7b5; ?>
<?php unset($__attributesOriginald4e1f955cac178c96b95346b7474b7b5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald4e1f955cac178c96b95346b7474b7b5)): ?>
<?php $component = $__componentOriginald4e1f955cac178c96b95346b7474b7b5; ?>
<?php unset($__componentOriginald4e1f955cac178c96b95346b7474b7b5); ?>
<?php endif; ?>
</div>
<?php /**PATH D:\stk\stk-back\resources\views/livewire/stock/stock-overview.blade.php ENDPATH**/ ?>