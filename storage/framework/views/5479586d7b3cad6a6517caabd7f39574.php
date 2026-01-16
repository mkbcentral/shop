<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'remaining' => 5,
    'max' => 5
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
    'remaining' => 5,
    'max' => 5
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<!--[if BLOCK]><![endif]--><?php if($remaining < $max && $remaining > 0): ?>
<div <?php echo e($attributes->merge(['class' => 'relative rounded-xl bg-gradient-to-r from-amber-500/15 via-amber-500/5 to-transparent border border-amber-500/30 p-4 overflow-hidden'])); ?> role="alert">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_left,_var(--tw-gradient-stops))] from-amber-500/10 via-transparent to-transparent"></div>
    <div class="relative flex items-center gap-3">
        
        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-amber-500/20 flex items-center justify-center animate-pulse">
            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>

        
        <div class="flex-1">
            <p class="text-sm font-medium text-amber-300">Attention</p>
            <p class="text-sm text-amber-400/80 mt-0.5">
                <span class="inline-flex items-center gap-1.5">
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-500/30 text-xs font-bold text-amber-300"><?php echo e($remaining); ?></span>
                    tentative<?php echo e($remaining > 1 ? 's' : ''); ?> restante<?php echo e($remaining > 1 ? 's' : ''); ?> avant blocage
                </span>
            </p>
        </div>

        
        <div class="flex-shrink-0 w-16 h-1.5 bg-slate-700 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-amber-500 to-red-500 rounded-full transition-all duration-300" style="width: <?php echo e((($max - $remaining) / $max) * 100); ?>%"></div>
        </div>
    </div>
</div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->
<?php /**PATH D:\stk\stk-back\resources\views/components/auth/attempts-warning.blade.php ENDPATH**/ ?>