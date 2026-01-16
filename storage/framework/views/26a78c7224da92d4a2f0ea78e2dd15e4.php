<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['color' => 'indigo', 'icon' => null, 'href' => null, 'wire:click' => null]));

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

foreach (array_filter((['color' => 'indigo', 'icon' => null, 'href' => null, 'wire:click' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $colorClasses = match($color) {
        'red' => 'text-red-600 hover:text-red-900',
        'green' => 'text-green-600 hover:text-green-900',
        'blue' => 'text-blue-600 hover:text-blue-900',
        'yellow' => 'text-yellow-600 hover:text-yellow-900',
        default => 'text-indigo-600 hover:text-indigo-900',
    };

    $tag = $href ? 'a' : 'button';
?>

<<?php echo e($tag); ?>

    <?php if($href): ?> href="<?php echo e($href); ?>" <?php endif; ?>
    <?php echo e($attributes->merge(['class' => "{$colorClasses} transition"])); ?>

>
    <!--[if BLOCK]><![endif]--><?php if($icon): ?>
        <?php echo e($icon); ?>

    <?php else: ?>
        <?php echo e($slot); ?>

    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</<?php echo e($tag); ?>>
<?php /**PATH D:\stk\stk-back\resources\views/components/table/action-button.blade.php ENDPATH**/ ?>