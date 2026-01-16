<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => 'modal',
    'maxWidth' => 'lg',
    'title' => '',
    'editTitle' => '',
    'icon' => null,
    'iconBg' => 'from-indigo-500 to-purple-600',
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
    'name' => 'modal',
    'maxWidth' => 'lg',
    'title' => '',
    'editTitle' => '',
    'icon' => null,
    'iconBg' => 'from-indigo-500 to-purple-600',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
$maxWidthClasses = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    '3xl' => 'sm:max-w-3xl',
    '4xl' => 'sm:max-w-4xl',
    '5xl' => 'sm:max-w-5xl',
];
$maxWidthClass = $maxWidthClasses[$maxWidth] ?? $maxWidthClasses['lg'];
?>



<div x-show="showModal"
     x-cloak
     x-on:keydown.escape.window="showModal = false"
     x-init="$watch('showModal', value => { document.body.style.overflow = value ? 'hidden' : '' })"
     class="fixed inset-0 z-50 overflow-y-auto"
     aria-labelledby="modal-<?php echo e($name); ?>-title"
     role="dialog"
     aria-modal="true">

    <!-- Backdrop -->
    <div @click="showModal = false"
         x-show="showModal"
         x-transition.opacity.duration.100ms
         class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm">
    </div>

    <!-- Modal Container -->
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div x-show="showModal"
             @click.stop
             x-transition:enter="ease-out duration-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-75"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-white rounded-2xl shadow-2xl transform w-full <?php echo e($maxWidthClass); ?> flex flex-col pointer-events-auto"
             style="max-height: 90vh;">

            <!-- Modal Header -->
            <div class="flex-shrink-0 flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white rounded-t-2xl">
                <div class="flex items-center space-x-3">
                    <!--[if BLOCK]><![endif]--><?php if($icon): ?>
                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br <?php echo e($iconBg); ?> rounded-lg flex items-center justify-center">
                        <?php echo e($icon); ?>

                    </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <h3 class="text-xl font-bold text-gray-900"
                        id="modal-<?php echo e($name); ?>-title"
                        x-text="isEditing ? '<?php echo e($editTitle ?: "Modifier"); ?>' : '<?php echo e($title ?: "Nouveau"); ?>'">
                    </h3>
                </div>
                <button @click="showModal = false" type="button"
                    class="rounded-lg p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content (slot) -->
            <?php echo e($slot); ?>

        </div>
    </div>
</div>
<?php /**PATH D:\stk\stk-back\resources\views/components/ui/alpine-modal.blade.php ENDPATH**/ ?>