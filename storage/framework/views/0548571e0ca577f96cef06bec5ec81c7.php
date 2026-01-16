

<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'showAppName' => true,
    'size' => 'default' // 'small', 'default', 'large'
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
    'showAppName' => true,
    'size' => 'default' // 'small', 'default', 'large'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $sizes = [
        'small' => [
            'container' => 'w-10 h-10',
            'text' => 'text-base',
            'badge' => 'w-3 h-3',
            'badgeIcon' => 'w-2 h-2',
            'appName' => 'text-xl'
        ],
        'default' => [
            'container' => 'w-12 h-12',
            'text' => 'text-lg',
            'badge' => 'w-4 h-4',
            'badgeIcon' => 'w-2.5 h-2.5',
            'appName' => 'text-2xl'
        ],
        'large' => [
            'container' => 'w-16 h-16',
            'text' => 'text-2xl',
            'badge' => 'w-5 h-5',
            'badgeIcon' => 'w-3 h-3',
            'appName' => 'text-3xl'
        ],
    ];
    $s = $sizes[$size] ?? $sizes['default'];
?>

<div <?php echo e($attributes->merge(['class' => 'inline-flex items-center space-x-3'])); ?>>
    <div class="relative">
        <div class="<?php echo e($s['container']); ?> bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
            <span class="text-white font-black <?php echo e($s['text']); ?>">SF</span>
        </div>
        <div class="absolute -bottom-1 -right-1 <?php echo e($s['badge']); ?> bg-emerald-500 rounded-full border-2 border-slate-900 flex items-center justify-center">
            <svg class="<?php echo e($s['badgeIcon']); ?> text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
        </div>
    </div>
    <!--[if BLOCK]><![endif]--><?php if($showAppName): ?>
        <span class="<?php echo e($s['appName']); ?> font-bold text-white"><?php echo e(config('app.name', 'ShopFlow')); ?></span>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH D:\stk\stk-back\resources\views/components/auth/logo.blade.php ENDPATH**/ ?>