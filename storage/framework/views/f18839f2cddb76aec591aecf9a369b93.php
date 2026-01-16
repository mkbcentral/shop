<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => '',
    'id' => '',
    'checked' => false,
    'disabled' => false,
    'size' => 'md', // 'sm', 'md', 'lg'
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
    'name' => '',
    'id' => '',
    'checked' => false,
    'disabled' => false,
    'size' => 'md', // 'sm', 'md', 'lg'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $sizeClasses = [
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
    ];

    $checkboxSize = $sizeClasses[$size] ?? $sizeClasses['md'];
?>

<label class="relative inline-flex items-center group cursor-pointer <?php echo e($disabled ? 'opacity-50 cursor-not-allowed' : ''); ?>">
    <input
        type="checkbox"
        name="<?php echo e($name); ?>"
        id="<?php echo e($id ?: $name); ?>"
        <?php if($checked): ?> checked <?php endif; ?>
        <?php if($disabled): ?> disabled <?php endif; ?>
        <?php echo e($attributes->merge([
            'class' => "peer sr-only"
        ])); ?>

    >
    <div class="<?php echo e($checkboxSize); ?> rounded-md border-2 border-gray-300 bg-white transition-all duration-200 ease-in-out
                peer-checked:bg-indigo-600 peer-checked:border-indigo-600
                peer-focus:ring-4 peer-focus:ring-indigo-100 peer-focus:ring-offset-0
                <?php echo e(!$disabled ? 'peer-hover:border-indigo-400 group-hover:border-indigo-400 peer-active:scale-95' : ''); ?>

                flex items-center justify-center shadow-sm hover:shadow-md">
        <!-- Checkmark Icon -->
        <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-all duration-200 transform peer-checked:scale-100 scale-0"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path>
        </svg>
    </div>
</label>

<style>
    /* Smooth animation for checkmark */
    input[type="checkbox"]:checked ~ div svg {
        animation: checkmarkAppear 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    @keyframes checkmarkAppear {
        0% {
            transform: scale(0) rotate(-45deg);
            opacity: 0;
        }
        50% {
            transform: scale(1.2) rotate(5deg);
        }
        100% {
            transform: scale(1) rotate(0deg);
            opacity: 1;
        }
    }

    /* Background color transition */
    input[type="checkbox"]:checked ~ div {
        animation: checkboxFill 0.2s ease-in-out;
    }

    @keyframes checkboxFill {
        0% {
            background-color: transparent;
        }
        100% {
            background-color: rgb(79 70 229);
        }
    }
</style>
<?php /**PATH D:\stk\stk-back\resources\views/components/form/checkbox.blade.php ENDPATH**/ ?>