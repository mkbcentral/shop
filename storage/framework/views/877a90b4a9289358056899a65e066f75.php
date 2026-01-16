<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'loading' => false,
    'disabled' => false,
    'loadingTarget' => 'login',
    'text' => 'Soumettre',
    'loadingText' => 'Chargement...',
    'lockedText' => 'Bloqué',
    'showArrow' => true
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
    'loading' => false,
    'disabled' => false,
    'loadingTarget' => 'login',
    'text' => 'Soumettre',
    'loadingText' => 'Chargement...',
    'lockedText' => 'Bloqué',
    'showArrow' => true
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<button
    type="submit"
    wire:loading.attr="disabled"
    <?php echo e($disabled ? 'disabled' : ''); ?>

    <?php echo e($attributes->merge(['class' => 'w-full inline-flex justify-center items-center py-2.5 px-4 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 shadow-lg shadow-indigo-500/25'])); ?>

>
    
    <svg wire:loading wire:target="<?php echo e($loadingTarget); ?>" class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>

    <!--[if BLOCK]><![endif]--><?php if($disabled): ?>
        
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
        <span><?php echo e($lockedText); ?></span>
    <?php else: ?>
        
        <span wire:loading.remove wire:target="<?php echo e($loadingTarget); ?>"><?php echo e($text); ?></span>
        <span wire:loading wire:target="<?php echo e($loadingTarget); ?>"><?php echo e($loadingText); ?></span>

        <!--[if BLOCK]><![endif]--><?php if($showArrow): ?>
            <svg wire:loading.remove wire:target="<?php echo e($loadingTarget); ?>" class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</button>
<?php /**PATH D:\stk\stk-back\resources\views/components/auth/submit-button.blade.php ENDPATH**/ ?>