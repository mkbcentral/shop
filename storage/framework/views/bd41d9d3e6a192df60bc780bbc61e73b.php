<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'value', 'subtitle', 'color' => 'blue', 'icon']));

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

foreach (array_filter((['title', 'value', 'subtitle', 'color' => 'blue', 'icon']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
$colorClasses = [
    'blue' => ['text' => 'text-blue-600', 'bg' => 'bg-blue-50'],
    'green' => ['text' => 'text-green-600', 'bg' => 'bg-green-50'],
    'red' => ['text' => 'text-red-600', 'bg' => 'bg-red-50'],
    'orange' => ['text' => 'text-orange-600', 'bg' => 'bg-orange-50'],
];
$colors = $colorClasses[$color] ?? $colorClasses['blue'];
?>

<?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['padding' => false,'class' => 'p-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'class' => 'p-4']); ?>
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600"><?php echo e($title); ?></p>
            <p class="text-xl font-bold <?php echo e($colors['text']); ?> mt-1"><?php echo e($value); ?></p>
            <!--[if BLOCK]><![endif]--><?php if($subtitle): ?>
                <p class="text-xs text-gray-500 mt-1"><?php echo e($subtitle); ?></p>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        <div class="p-2 <?php echo e($colors['bg']); ?> rounded-lg">
            <svg class="w-5 h-5 <?php echo e($colors['text']); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <?php echo $icon; ?>

            </svg>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $attributes = $__attributesOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__attributesOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $component = $__componentOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__componentOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
<?php /**PATH D:\stk\stk-back\resources\views/components/stock/kpi-card.blade.php ENDPATH**/ ?>