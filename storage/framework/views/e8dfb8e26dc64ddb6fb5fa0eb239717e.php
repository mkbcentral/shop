<div class="min-h-screen flex flex-col bg-gradient-to-br from-gray-50 to-gray-100"
    x-data="cashRegisterModular()"
    wire:ignore.self>

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

    <!-- Top Bar -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white px-6 py-3 shadow-xl relative overflow-hidden flex-shrink-0">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-tight">Point de Vente <span class="text-xs bg-white/30 px-2 py-0.5 rounded-full ml-2">Modulaire</span></h1>
                    <p class="text-xs text-indigo-100 flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-white/20 rounded-full text-xs font-semibold">Caisse #<?php echo e(auth()->id()); ?></span>
                        <span><?php echo e(auth()->user()->name); ?></span>
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Statistiques du jour -->
                <button wire:click="toggleStats" @click="showStatsPanel = !showStatsPanel"
                    class="flex items-center gap-2 px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-xl transition-all duration-200 backdrop-blur-sm hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <div class="text-left">
                        <div class="text-xs opacity-80">CA Aujourd'hui</div>
                        <div class="text-sm font-bold"><?php echo format_currency($todayStats['revenue']); ?></div>
                    </div>
                </button>

                <!-- Bouton Factures du jour -->
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('pos.components.pos-transaction-history', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3630567882-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

                <div class="text-right">
                    <div class="text-xs text-indigo-100 font-medium"><?php echo e(now()->format('d/m/Y')); ?></div>
                    <div class="text-lg font-bold tabular-nums" x-ref="clock"><?php echo e(now()->format('H:i:s')); ?></div>
                </div>

                <!-- Lien vers version classique -->
                <a href="<?php echo e(route('pos.cash-register')); ?>" wire:navigate
                    class="px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-xl transition-all duration-200 text-xs font-semibold">
                    Version Classique
                </a>

                <a href="<?php echo e(route('dashboard')); ?>" wire:navigate
                    class="p-2 bg-white/20 hover:bg-white/30 rounded-xl transition-all duration-200 backdrop-blur-sm hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Panel (Collapsible) -->
    <div x-show="showStatsPanel" x-collapse class="bg-white border-b shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="grid grid-cols-4 gap-4">
                <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl p-4 border border-green-200">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-500 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-green-600 font-medium">Chiffre d'affaires</p>
                            <p class="text-xl font-black text-green-700"><?php echo e(number_format($todayStats['revenue'], 0, ',', ' ')); ?> <span class="text-sm"><?php echo e(current_currency()); ?></span></p>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl p-4 border border-blue-200">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-500 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-blue-600 font-medium">Ventes</p>
                            <p class="text-xl font-black text-blue-700"><?php echo e($todayStats['sales_count']); ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-pink-100 rounded-xl p-4 border border-purple-200">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-purple-500 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-purple-600 font-medium">Transactions</p>
                            <p class="text-xl font-black text-purple-700"><?php echo e($todayStats['transactions']); ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-orange-50 to-amber-100 rounded-xl p-4 border border-orange-200">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-orange-500 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-orange-600 font-medium">Panier moyen</p>
                            <p class="text-xl font-black text-orange-700">
                                <!--[if BLOCK]><![endif]--><?php if($todayStats['sales_count'] > 0): ?>
                                    <?php echo e(number_format($todayStats['revenue'] / $todayStats['sales_count'], 0, ',', ' ')); ?> <span class="text-sm"><?php echo e(current_currency()); ?></span>
                                <?php else: ?>
                                    0 <span class="text-sm"><?php echo e(current_currency()); ?></span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content - Modular Components -->
    <div class="flex-1 flex overflow-hidden" style="height: calc(100vh - 64px);">
        <!-- Left: Products Grid -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('pos.components.pos-product-grid', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3630567882-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        </div>

        <!-- Right: Cart + Payment -->
        <div class="w-[520px] bg-gradient-to-b from-white to-gray-50 border-l-2 border-gray-200 shadow-2xl overflow-y-auto custom-scrollbar" style="height: calc(100vh - 64px);">
            <!-- Cart Component -->
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('pos.components.pos-cart', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3630567882-2', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

            <!-- Payment Component -->
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('pos.components.pos-payment-panel', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3630567882-3', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        </div>
    </div>

    <!-- Flash Messages -->
    <div
        x-data="{
            successMessage: '',
            errorMessage: '',
            showSuccess: false,
            showError: false
        }"
        x-on:show-toast.window="
            if ($event.detail.type === 'success') {
                successMessage = $event.detail.message;
                showSuccess = true;
                setTimeout(() => { showSuccess = false }, 8000);
            } else if ($event.detail.type === 'error' || $event.detail.type === 'warning') {
                errorMessage = $event.detail.message;
                showError = true;
                setTimeout(() => { showError = false }, 10000);
            }
        "
        class="fixed bottom-6 right-6 z-50 space-y-2"
    >
        <!-- Success Message -->
        <div x-show="showSuccess" x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-x-full"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform translate-x-full"
            class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-bold" x-text="successMessage"></span>
        </div>

        <!-- Error Message -->
        <div x-show="showError" x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-x-full"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform translate-x-full"
            class="bg-gradient-to-r from-red-500 to-pink-500 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-bold" x-text="errorMessage"></span>
        </div>
    </div>

    <!-- Keyboard Shortcuts Handler -->
    <div x-data @keydown.window="handleKeyboard($event)"></div>

    <!-- Alpine.js Component -->
    <script>
        function cashRegisterModular() {
            return {
                showStatsPanel: false,

                init() {
                    // Horloge en temps réel
                    this.updateClock();
                    setInterval(() => this.updateClock(), 1000);
                },

                updateClock() {
                    const clock = this.$refs.clock;
                    if (clock) {
                        const now = new Date();
                        clock.textContent = now.toLocaleTimeString('fr-FR');
                    }
                },

                handleKeyboard(event) {
                    // F2 - Focus recherche
                    if (event.key === 'F2') {
                        event.preventDefault();
                        Livewire.dispatch('focus-search');
                    }
                    // F4 - Vider panier
                    if (event.key === 'F4') {
                        event.preventDefault();
                        Livewire.dispatch('trigger-clear-cart');
                    }
                    // F9 - Valider paiement
                    if (event.key === 'F9') {
                        event.preventDefault();
                        Livewire.dispatch('trigger-payment');
                    }
                }
            }
        }
    </script>

    <!-- Printer Scripts -->
    <?php echo $__env->make('livewire.pos.partials.printer-scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <style>
        [x-cloak] { display: none !important; }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c7c7c7;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</div>
<?php /**PATH D:\stk\stk-back\resources\views/livewire/pos/cash-register-modular.blade.php ENDPATH**/ ?>