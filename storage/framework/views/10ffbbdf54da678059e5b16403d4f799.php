<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['padding' => true, 'shadow' => 'sm', 'hover' => false]));

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

foreach (array_filter((['padding' => true, 'shadow' => 'sm', 'hover' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
$classes = 'bg-white rounded-2xl border border-gray-100';
$classes .= $padding ? ' p-6' : '';
$classes .= ' shadow-' . $shadow;
$classes .= $hover ? ' hover:shadow-xl transition-all duration-300' : '';
?>

<div <?php echo e($attributes->merge(['class' => $classes])); ?>>
    <!--[if BLOCK]><![endif]--><?php if(isset($header)): ?>
        <div class="mb-6">
            <?php echo e($header); ?>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <?php echo e($slot); ?>


    <!--[if BLOCK]><![endif]--><?php if(isset($footer)): ?>
        <div class="mt-6 pt-6 border-t border-gray-100">
            <?php echo e($footer); ?>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH D:\stk\stk-back\resources\views/components/card.blade.php ENDPATH**/ ?>