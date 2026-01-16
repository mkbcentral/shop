<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'href' => '#',
    'title',
    'icon',
    'color' => 'indigo',
    'description' => null,
    'navigate' => true
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
    'href' => '#',
    'title',
    'icon',
    'color' => 'indigo',
    'description' => null,
    'navigate' => true
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
$gradientClasses = match($color) {
    'indigo' => 'from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700',
    'green' => 'from-green-500 to-green-600 hover:from-green-600 hover:to-green-700',
    'purple' => 'from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700',
    'blue' => 'from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700',
    'red' => 'from-red-500 to-red-600 hover:from-red-600 hover:to-red-700',
    'amber' => 'from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700',
    default => 'from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700',
};

$bgPattern = match($color) {
    'indigo' => 'bg-indigo-400/20',
    'green' => 'bg-green-400/20',
    'purple' => 'bg-purple-400/20',
    'blue' => 'bg-blue-400/20',
    'red' => 'bg-red-400/20',
    'amber' => 'bg-amber-400/20',
    default => 'bg-gray-400/20',
};
?>

<a href="<?php echo e($href); ?>" <?php if($navigate): ?> wire:navigate <?php endif; ?> <?php echo e($attributes->merge(['class' => 'group relative overflow-hidden bg-gradient-to-br ' . $gradientClasses . ' rounded-2xl p-6 text-left transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:-translate-y-2 hover:scale-105 block'])); ?>>
    <!-- Decorative circles -->
    <div class="absolute top-0 right-0 w-32 h-32 <?php echo e($bgPattern); ?> rounded-full -mr-16 -mt-16 transition-transform duration-300 group-hover:scale-150"></div>
    <div class="absolute bottom-0 left-0 w-24 h-24 <?php echo e($bgPattern); ?> rounded-full -ml-12 -mb-12 transition-transform duration-300 group-hover:scale-125"></div>

    <!-- Shine effect on hover -->
    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>

    <div class="relative z-10">
        <div class="w-14 h-14 bg-white/25 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-4 transition-all duration-300 group-hover:scale-110 group-hover:bg-white/30 group-hover:rotate-3 shadow-lg">
            <div class="w-7 h-7 text-white">
                <?php echo e($icon); ?>

            </div>
        </div>
        <h4 class="text-white font-bold text-lg mb-2 transition-transform duration-300 group-hover:translate-x-1"><?php echo e($title); ?></h4>
        <!--[if BLOCK]><![endif]--><?php if($description): ?>
            <p class="text-white/90 text-sm font-medium flex items-center gap-1 transition-all duration-300 group-hover:gap-2">
                <?php echo e($description); ?>

                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </p>
        <?php else: ?>
            <p class="text-white/90 text-sm font-medium"><?php echo e($slot); ?></p>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</a>
<?php /**PATH D:\stk\stk-back\resources\views/components/action-card.blade.php ENDPATH**/ ?>