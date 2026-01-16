<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => null,
    'id' => null,
    'name' => null,
    'type' => 'text',
    'placeholder' => '',
    'icon' => null,
    'error' => null,
    'showPasswordToggle' => false
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
    'label' => null,
    'id' => null,
    'name' => null,
    'type' => 'text',
    'placeholder' => '',
    'icon' => null,
    'error' => null,
    'showPasswordToggle' => false
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $hasError = $error || ($name && $errors->has($name));
    $inputId = $id ?? $name;

    // Predefined icons
    $icons = [
        'email' => '<svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>',
        'lock' => '<svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>',
        'user' => '<svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>',
        'building' => '<svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>',
        'phone' => '<svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>',
        'search' => '<svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>',
    ];

    $iconSvg = isset($icons[$icon]) ? $icons[$icon] : $icon;
    $hasIcon = !empty($icon);
?>

<div>
    <!--[if BLOCK]><![endif]--><?php if($label): ?>
        <label for="<?php echo e($inputId); ?>" class="block text-sm font-medium text-slate-300 mb-1.5"><?php echo e($label); ?></label>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <div class="relative" <?php if($showPasswordToggle): ?> x-data="{ showPassword: false }" <?php endif; ?>>
        
        <!--[if BLOCK]><![endif]--><?php if($hasIcon): ?>
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <?php echo $iconSvg; ?>

            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        
        <!--[if BLOCK]><![endif]--><?php if($showPasswordToggle): ?>
            <input
                id="<?php echo e($inputId); ?>"
                name="<?php echo e($name); ?>"
                placeholder="<?php echo e($placeholder); ?>"
                x-bind:type="showPassword ? 'text' : 'password'"
                <?php echo e($attributes->merge(['class' => 'block w-full ' . ($hasIcon ? 'pl-10' : 'pl-4') . ' pr-10 py-2.5 bg-slate-800/50 border border-slate-700 text-white placeholder-slate-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-150 ' . ($hasError ? 'border-red-500 focus:border-red-500' : '')])); ?>

            />
            
            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-indigo-400 transition-colors">
                <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
            </button>
        <?php else: ?>
            <input
                id="<?php echo e($inputId); ?>"
                name="<?php echo e($name); ?>"
                type="<?php echo e($type); ?>"
                placeholder="<?php echo e($placeholder); ?>"
                <?php echo e($attributes->merge(['class' => 'block w-full ' . ($hasIcon ? 'pl-10' : 'pl-4') . ' pr-4 py-2.5 bg-slate-800/50 border border-slate-700 text-white placeholder-slate-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-150 ' . ($hasError ? 'border-red-500 focus:border-red-500' : '')])); ?>

            />
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    
    <!--[if BLOCK]><![endif]--><?php if($error): ?>
        <?php if (isset($component)) { $__componentOriginal71862e4c667609610d6c6a18123d162b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal71862e4c667609610d6c6a18123d162b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth.field-error','data' => ['message' => $error]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth.field-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($error)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal71862e4c667609610d6c6a18123d162b)): ?>
<?php $attributes = $__attributesOriginal71862e4c667609610d6c6a18123d162b; ?>
<?php unset($__attributesOriginal71862e4c667609610d6c6a18123d162b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal71862e4c667609610d6c6a18123d162b)): ?>
<?php $component = $__componentOriginal71862e4c667609610d6c6a18123d162b; ?>
<?php unset($__componentOriginal71862e4c667609610d6c6a18123d162b); ?>
<?php endif; ?>
    <?php elseif($name): ?>
        <?php if (isset($component)) { $__componentOriginal71862e4c667609610d6c6a18123d162b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal71862e4c667609610d6c6a18123d162b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth.field-error','data' => ['for' => $name]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth.field-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($name)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal71862e4c667609610d6c6a18123d162b)): ?>
<?php $attributes = $__attributesOriginal71862e4c667609610d6c6a18123d162b; ?>
<?php unset($__attributesOriginal71862e4c667609610d6c6a18123d162b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal71862e4c667609610d6c6a18123d162b)): ?>
<?php $component = $__componentOriginal71862e4c667609610d6c6a18123d162b; ?>
<?php unset($__componentOriginal71862e4c667609610d6c6a18123d162b); ?>
<?php endif; ?>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH D:\stk\stk-back\resources\views/components/auth/input.blade.php ENDPATH**/ ?>