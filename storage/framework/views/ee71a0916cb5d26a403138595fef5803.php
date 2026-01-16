<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'for' => '',
    'required' => false,
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
    'for' => '',
    'required' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<label
    <?php if($for): ?> for="<?php echo e($for); ?>" <?php endif; ?>
    <?php echo e($attributes->merge(['class' => 'block text-sm font-medium text-slate-700 mb-2'])); ?>

>
    <?php echo e($slot); ?>

    <!--[if BLOCK]><![endif]--><?php if($required): ?>
        <span class="text-red-500 ml-1">*</span>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</label>
<?php /**PATH D:\stk\stk-back\resources\views/components/form/label.blade.php ENDPATH**/ ?>