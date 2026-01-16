
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'show' => 'showDeleteModal',
    'itemName' => '',
    'itemType' => 'élément',
    'onConfirm' => '',
    'onCancel' => '',
    'title' => 'Confirmer la suppression',
]));

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

foreach (array_filter(([
    'show' => 'showDeleteModal',
    'itemName' => '',
    'itemType' => 'élément',
    'onConfirm' => '',
    'onCancel' => '',
    'title' => 'Confirmer la suppression',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div x-show="<?php echo e($show); ?>" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div x-show="<?php echo e($show); ?>"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        <?php if($onCancel): ?>
            @click="<?php echo e($onCancel); ?>"
        <?php else: ?>
            @click="<?php echo e($show); ?> = false"
        <?php endif; ?>
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>

    <!-- Modal -->
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div x-show="<?php echo e($show); ?>"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
            @click.stop
            @keydown.escape.window="<?php echo e($show); ?> = false"
            class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md pointer-events-auto p-6">

            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-100 mb-5">
                <svg class="h-7 w-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <!-- Content -->
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-2"><?php echo e($title); ?></h3>
                <p class="text-sm text-gray-600 mb-3">Voulez-vous vraiment supprimer <?php echo e($itemType); ?> ?</p>
                <!--[if BLOCK]><![endif]--><?php if($itemName): ?>
                    <p class="text-base font-bold text-red-600" x-text="<?php echo e($itemName); ?>"></p>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <p class="text-xs text-gray-500 mt-2">Cette action est irréversible.</p>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 justify-center mt-6">
                <button type="button"
                    <?php if($onCancel): ?>
                        @click="<?php echo e($onCancel); ?>"
                    <?php else: ?>
                        @click="<?php echo e($show); ?> = false"
                    <?php endif; ?>
                    class="px-5 py-2.5 bg-white text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                    Annuler
                </button>
                <button type="button"
                    <?php if($onConfirm): ?>
                        @click="<?php echo e($onConfirm); ?>"
                    <?php endif; ?>
                    class="px-5 py-2.5 text-white font-medium rounded-lg bg-red-600 hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    Supprimer
                </button>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\stk\stk-back\resources\views/components/delete-confirmation-modal.blade.php ENDPATH**/ ?>