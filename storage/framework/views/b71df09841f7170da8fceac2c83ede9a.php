<?php extract((new \Illuminate\Support\Collection($attributes->getAttributes()))->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['class']));

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

foreach (array_filter((['class']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php if (isset($component)) { $__componentOriginal366cc9aca1adebfaa2d1eeeb0565a599 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal366cc9aca1adebfaa2d1eeeb0565a599 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons.minus','data' => ['class' => $class]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icons.minus'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($class)]); ?>

<?php echo e($slot ?? ""); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal366cc9aca1adebfaa2d1eeeb0565a599)): ?>
<?php $attributes = $__attributesOriginal366cc9aca1adebfaa2d1eeeb0565a599; ?>
<?php unset($__attributesOriginal366cc9aca1adebfaa2d1eeeb0565a599); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal366cc9aca1adebfaa2d1eeeb0565a599)): ?>
<?php $component = $__componentOriginal366cc9aca1adebfaa2d1eeeb0565a599; ?>
<?php unset($__componentOriginal366cc9aca1adebfaa2d1eeeb0565a599); ?>
<?php endif; ?><?php /**PATH D:\stk\stk-back\storage\framework\views/f4811e9bab67869db40f1cb93d39ae99.blade.php ENDPATH**/ ?>