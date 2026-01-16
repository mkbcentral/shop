<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['align' => 'left', 'sortable' => false, 'sortKey' => null]));

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

foreach (array_filter((['align' => 'left', 'sortable' => false, 'sortKey' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $alignClass = match($align) {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };
?>

<th <?php echo e($attributes->merge(['class' => "px-6 py-3 {$alignClass} text-xs font-medium text-gray-500 uppercase tracking-wider"])); ?>>
    <!--[if BLOCK]><![endif]--><?php if($sortable && $sortKey): ?>
        <button type="button" wire:click="sortBy('<?php echo e($sortKey); ?>')" class="group inline-flex items-center space-x-1 hover:text-gray-700 transition">
            <span><?php echo e($slot); ?></span>
            <!--[if BLOCK]><![endif]--><?php if(isset($this) && property_exists($this, 'sortField') && $this->sortField === $sortKey): ?>
                <?php if(isset($this) && property_exists($this, 'sortDirection') && $this->sortDirection === 'asc'): ?>
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                <?php else: ?>
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <?php else: ?>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                </svg>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </button>
    <?php else: ?>
        <?php echo e($slot); ?>

    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</th>
<?php /**PATH D:\stk\stk-back\resources\views/components/table/header.blade.php ENDPATH**/ ?>