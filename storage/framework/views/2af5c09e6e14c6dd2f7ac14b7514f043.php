<div>
    <!-- Bouton Trigger (pour la top bar) -->
    <button wire:click="openModal"
        class="flex items-center gap-2 px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-xl transition-all duration-200 backdrop-blur-sm hover:scale-105">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <div class="text-left">
            <div class="text-xs opacity-80">Factures</div>
            <div class="text-sm font-bold"><?php echo e(count($transactions)); ?> vente(s)</div>
        </div>
    </button>

    <!-- Modal avec x-modal -->
    <?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['name' => 'showModal','maxWidth' => 'lg','showHeader' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'showModal','maxWidth' => 'lg','showHeader' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
        <!-- Header personnalisé sans couleur de fond -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-100 rounded-xl">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Factures du jour</h3>
                    <p class="text-gray-500 text-xs mt-0.5">Cliquez pour réimprimer</p>
                </div>
            </div>
            <button type="button" @click="livewireShow = false" class="text-gray-400 hover:text-gray-600 transition-colors p-2 hover:bg-gray-100 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Transactions List -->
        <!--[if BLOCK]><![endif]--><?php if(count($transactions) > 0): ?>
            <div class="max-h-[55vh] overflow-y-auto custom-scrollbar divide-y divide-gray-100/80">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('livewire.pos.components.partials.transaction-list-item', ['transaction' => $transaction], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-5 py-4 border-t border-gray-200 rounded-b-2xl">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow">
                            <span class="text-white font-bold"><?php echo e(count($transactions)); ?></span>
                        </div>
                        <span class="text-sm text-gray-600 font-medium">facture(s) aujourd'hui</span>
                    </div>
                    <button wire:click="loadTransactions"
                        class="px-4 py-2.5 bg-white hover:bg-indigo-50 border border-gray-200 hover:border-indigo-300 text-indigo-700 rounded-xl font-medium text-sm flex items-center gap-2 transition-all duration-200 shadow-sm hover:shadow">
                        <svg class="w-4 h-4" wire:loading.class="animate-spin" wire:target="loadTransactions" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span wire:loading.remove wire:target="loadTransactions">Actualiser</span>
                        <span wire:loading wire:target="loadTransactions">Chargement...</span>
                    </button>
                </div>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="px-8 py-16 text-center">
                <div class="relative w-28 h-28 mx-auto mb-6">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full animate-pulse"></div>
                    <div class="relative w-full h-full bg-gradient-to-br from-gray-50 to-gray-100 rounded-full flex items-center justify-center shadow-inner">
                        <svg class="w-14 h-14 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <h4 class="text-gray-800 font-bold text-lg">Aucune facture aujourd'hui</h4>
                <p class="text-gray-400 mt-2 text-sm">Les factures apparaîtront ici après chaque vente</p>
                <div class="mt-6">
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-600 rounded-full text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Commencez à vendre pour voir l'historique
                    </span>
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $attributes = $__attributesOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__attributesOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $component = $__componentOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__componentOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
</div>
<?php /**PATH D:\stk\stk-back\resources\views/livewire/pos/components/pos-transaction-history.blade.php ENDPATH**/ ?>