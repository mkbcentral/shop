<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['colspan' => 1, 'icon' => null, 'title' => 'Aucune donnée', 'description' => 'Aucun élément trouvé.']));

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

foreach (array_filter((['colspan' => 1, 'icon' => null, 'title' => 'Aucune donnée', 'description' => 'Aucun élément trouvé.']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<tr>
    <td colspan="<?php echo e($colspan); ?>" class="px-6 py-12 text-center">
        <!--[if BLOCK]><![endif]--><?php if($icon): ?>
            <div class="mx-auto h-12 w-12 text-gray-400">
                <?php echo e($icon); ?>

            </div>
        <?php else: ?>
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <h3 class="mt-2 text-sm font-medium text-gray-900"><?php echo e($title); ?></h3>
        <p class="mt-1 text-sm text-gray-500"><?php echo e($description); ?></p>
        <!--[if BLOCK]><![endif]--><?php if(isset($action)): ?>
            <div class="mt-6">
                <?php echo e($action); ?>

            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </td>
</tr>
<?php /**PATH D:\stk\stk-back\resources\views/components/table/empty-state.blade.php ENDPATH**/ ?>