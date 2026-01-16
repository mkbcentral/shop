<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => 'text',
    'name' => '',
    'id' => '',
    'placeholder' => '',
    'required' => false,
    'autofocus' => false,
    'autocomplete' => 'off',
    'value' => '',
    'disabled' => false,
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
    'type' => 'text',
    'name' => '',
    'id' => '',
    'placeholder' => '',
    'required' => false,
    'autofocus' => false,
    'autocomplete' => 'off',
    'value' => '',
    'disabled' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<input
    type="<?php echo e($type); ?>"
    name="<?php echo e($name); ?>"
    id="<?php echo e($id ?: $name); ?>"
    <?php if($placeholder): ?> placeholder="<?php echo e($placeholder); ?>" <?php endif; ?>
    <?php if($required): ?> required <?php endif; ?>
    <?php if($autofocus): ?> autofocus <?php endif; ?>
    <?php if($autocomplete !== 'off'): ?> autocomplete="<?php echo e($autocomplete); ?>" <?php endif; ?>
    <?php if($value): ?> value="<?php echo e($value); ?>" <?php endif; ?>
    <?php if($disabled): ?> disabled <?php endif; ?>
    <?php echo e($attributes->merge(['class' => 'block w-full px-4 py-3 border border-slate-300 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 disabled:opacity-50 disabled:cursor-not-allowed'])); ?>

/>
<?php /**PATH D:\stk\stk-back\resources\views/components/form/input.blade.php ENDPATH**/ ?>