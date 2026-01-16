<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title',
    'value',
    'color' => 'indigo',
    'icon' => null,
    'clickable' => false,
    'wireClick' => null
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
    'title',
    'value',
    'color' => 'indigo',
    'icon' => null,
    'clickable' => false,
    'wireClick' => null
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $colorClasses = [
        'indigo' => [
            'bg' => 'bg-indigo-100',
            'text' => 'text-indigo-600',
            'value' => 'text-gray-900'
        ],
        'green' => [
            'bg' => 'bg-green-100',
            'text' => 'text-green-600',
            'value' => 'text-green-600'
        ],
        'orange' => [
            'bg' => 'bg-orange-100',
            'text' => 'text-orange-600',
            'value' => 'text-orange-600'
        ],
        'red' => [
            'bg' => 'bg-red-100',
            'text' => 'text-red-600',
            'value' => 'text-red-600'
        ],
        'purple' => [
            'bg' => 'bg-purple-100',
            'text' => 'text-purple-600',
            'value' => 'text-purple-600'
        ],
    ];

    $colors = $colorClasses[$color] ?? $colorClasses['indigo'];
    $cursorClass = $clickable ? 'cursor-pointer' : '';
    $wireClickAttr = $wireClick ? "wire:click=\"{$wireClick}\"" : '';
?>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-2.5 hover:shadow-md transition-shadow duration-200 <?php echo e($cursorClass); ?>"
     <?php echo $wireClickAttr; ?>>
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-medium text-gray-600"><?php echo e($title); ?></p>
            <p class="text-xl font-bold <?php echo e($colors['value']); ?> mt-0.5"><?php echo e($value); ?></p>
        </div>
        <div class="<?php echo e($colors['bg']); ?> rounded-full p-1.5">
            <!--[if BLOCK]><![endif]--><?php if($icon): ?>
                <?php echo $icon; ?>

            <?php else: ?>
                <?php echo e($slot); ?>

            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
</div>
<?php /**PATH D:\stk\stk-back\resources\views/components/kpi-card.blade.php ENDPATH**/ ?>