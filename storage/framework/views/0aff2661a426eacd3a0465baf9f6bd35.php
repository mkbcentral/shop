<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title',
    'show' => 'showSection',
    'gradientFrom' => 'green-500',
    'gradientTo' => 'teal-600',
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
    'show' => 'showSection',
    'gradientFrom' => 'green-500',
    'gradientTo' => 'teal-600',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center space-x-2">
            <div class="h-6 w-1 bg-gradient-to-b from-<?php echo e($gradientFrom); ?> to-<?php echo e($gradientTo); ?> rounded-full"></div>
            <label class="block text-base font-semibold text-gray-800"><?php echo e($title); ?></label>
            <span class="text-xs text-gray-500">(optionnel)</span>
        </div>
        <label class="inline-flex items-center cursor-pointer">
            <input type="checkbox" x-model="<?php echo e($show); ?>" class="sr-only peer">
            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
        </label>
    </div>

    <div x-show="<?php echo e($show); ?>"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         x-cloak>
        <?php echo e($slot); ?>

    </div>
</div>
<?php /**PATH D:\stk\stk-back\resources\views/components/optional-section.blade.php ENDPATH**/ ?>