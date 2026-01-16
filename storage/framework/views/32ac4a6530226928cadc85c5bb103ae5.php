<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'align' => 'right',
    'width' => '48',
    'contentClasses' => 'py-1 bg-white'
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
    'align' => 'right',
    'width' => '48',
    'contentClasses' => 'py-1 bg-white'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
$alignmentClasses = match($align) {
    'left' => 'origin-top-left left-0',
    'right' => 'origin-top-right right-0',
    'center' => 'origin-top left-1/2 -translate-x-1/2',
    default => 'origin-top-right right-0',
};

$widthClasses = match($width) {
    '48' => 'w-48',
    '56' => 'w-56',
    '64' => 'w-64',
    'auto' => 'w-auto',
    default => 'w-48',
};
?>

<div class="relative inline-block" x-data="{ open: false }" @click.away="open = false">
    <div @click="open = !open">
        <?php echo e($trigger); ?>

    </div>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 mt-2 <?php echo e($widthClasses); ?> <?php echo e($alignmentClasses); ?> rounded-md shadow-lg ring-1 ring-black ring-opacity-5"
        style="display: none;"
        @click="open = false"
    >
        <div class="<?php echo e($contentClasses); ?> rounded-md">
            <?php echo e($slot); ?>

        </div>
    </div>
</div>
<?php /**PATH D:\stk\stk-back\resources\views/components/dropdown.blade.php ENDPATH**/ ?>