<div
    x-data="{ open: false }"
    @click.away="open = false"
    class="relative"
>
    <!-- Notification Button -->
    <button
        @click="open = !open"
        class="relative p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>

        <!--[if BLOCK]><![endif]--><?php if($this->totalAlertsCount > 0): ?>
            <span class="absolute -top-1 -right-1 flex items-center justify-center min-w-[20px] h-5 px-1 text-xs font-bold text-white bg-red-500 rounded-full animate-pulse">
                <?php echo e($this->totalAlertsCount > 99 ? '99+' : $this->totalAlertsCount); ?>

            </span>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </button>

    <!-- Dropdown Panel -->
    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-96 bg-white rounded-xl shadow-2xl ring-1 ring-black ring-opacity-5 z-50 overflow-hidden"
    >
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-4 py-3">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Alertes de Stock</h3>
                <div class="flex items-center space-x-2">
                    <!--[if BLOCK]><![endif]--><?php if($this->outOfStockCount > 0): ?>
                        <span class="px-2 py-1 text-xs font-medium bg-red-500 text-white rounded-full">
                            <?php echo e($this->outOfStockCount); ?> rupture<?php echo e($this->outOfStockCount > 1 ? 's' : ''); ?>

                        </span>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <!--[if BLOCK]><![endif]--><?php if($this->lowStockCount > 0): ?>
                        <span class="px-2 py-1 text-xs font-medium bg-amber-500 text-white rounded-full">
                            <?php echo e($this->lowStockCount); ?> bas
                        </span>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </div>

        <div class="max-h-[400px] overflow-y-auto">
            <!--[if BLOCK]><![endif]--><?php if($this->totalAlertsCount === 0): ?>
                <!-- Empty State -->
                <div class="p-8 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900">Tout est en ordre !</h4>
                    <p class="mt-1 text-sm text-gray-500">Aucune alerte de stock pour le moment.</p>
                </div>
            <?php else: ?>
                <!-- Store Summary Section -->
                <!--[if BLOCK]><![endif]--><?php if($this->storeSummary->isNotEmpty()): ?>
                    <div class="px-4 py-3 bg-gray-50 border-b">
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            Résumé par magasin
                        </h4>
                        <div class="space-y-2">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->storeSummary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $summary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between p-2 bg-white rounded-lg border">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        <span class="font-medium text-gray-900"><?php echo e($summary['store']->name); ?></span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <!--[if BLOCK]><![endif]--><?php if($summary['out_of_stock'] > 0): ?>
                                            <span class="px-2 py-0.5 text-xs font-medium bg-red-100 text-red-700 rounded-full">
                                                <?php echo e($summary['out_of_stock']); ?> rupture
                                            </span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <!--[if BLOCK]><![endif]--><?php if($summary['low_stock'] > 0): ?>
                                            <span class="px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-700 rounded-full">
                                                <?php echo e($summary['low_stock']); ?> bas
                                            </span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <!-- Recent Alerts List -->
                <div class="divide-y divide-gray-100">
                    <div class="px-4 py-2 bg-gray-50">
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Dernières alertes
                        </h4>
                    </div>

                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->stockAlerts->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="px-4 py-3 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start space-x-3">
                                <!-- Status Icon -->
                                <div class="flex-shrink-0">
                                    <!--[if BLOCK]><![endif]--><?php if($alert->stock_quantity <= 0): ?>
                                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                        </div>
                                    <?php else: ?>
                                        <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            <?php echo e($alert->product?->name ?? 'Produit inconnu'); ?>

                                        </p>
                                        <span class="ml-2 text-xs font-semibold <?php echo e($alert->stock_quantity <= 0 ? 'text-red-600' : 'text-amber-600'); ?>">
                                            <?php echo e($alert->stock_quantity); ?> unité<?php echo e($alert->stock_quantity != 1 ? 's' : ''); ?>

                                        </span>
                                    </div>
                                    <div class="flex items-center mt-1 text-xs text-gray-500 space-x-2">
                                        <!--[if BLOCK]><![endif]--><?php if($alert->sku): ?>
                                            <span>SKU: <?php echo e($alert->sku); ?></span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                    <div class="mt-1">
                                        <!--[if BLOCK]><![endif]--><?php if($alert->stock_quantity <= 0): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                Rupture de stock
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">
                                                Stock bas (seuil: <?php echo e($alert->low_stock_threshold); ?>)
                                            </span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!--[if BLOCK]><![endif]--><?php if($this->totalAlertsCount > 10): ?>
                    <div class="px-4 py-2 bg-gray-50 text-center">
                        <span class="text-xs text-gray-500">
                            Et <?php echo e($this->totalAlertsCount - 10); ?> autre(s) alerte(s)...
                        </span>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 bg-gray-50 border-t">
            <a
                href="<?php echo e(route('stock.alerts')); ?>"
                class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors"
            >
                <span>Voir toutes les alertes</span>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>
<?php /**PATH D:\stk\stk-back\resources\views/livewire/stock/stock-notifications.blade.php ENDPATH**/ ?>