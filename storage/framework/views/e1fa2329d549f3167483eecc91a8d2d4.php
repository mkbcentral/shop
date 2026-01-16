<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 relative overflow-hidden">
    
    <?php if (isset($component)) { $__componentOriginalca9358d04f57dd5b62c18b22a812fbb5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalca9358d04f57dd5b62c18b22a812fbb5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth.background','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth.background'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalca9358d04f57dd5b62c18b22a812fbb5)): ?>
<?php $attributes = $__attributesOriginalca9358d04f57dd5b62c18b22a812fbb5; ?>
<?php unset($__attributesOriginalca9358d04f57dd5b62c18b22a812fbb5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalca9358d04f57dd5b62c18b22a812fbb5)): ?>
<?php $component = $__componentOriginalca9358d04f57dd5b62c18b22a812fbb5; ?>
<?php unset($__componentOriginalca9358d04f57dd5b62c18b22a812fbb5); ?>
<?php endif; ?>

    <div class="relative z-10 grid lg:grid-cols-2 gap-0 min-h-screen">
        
        <div class="p-6 sm:p-8 lg:p-10 flex flex-col justify-center">
            <div class="max-w-sm mx-auto w-full space-y-6">
                
                <div class="text-center lg:text-left">
                    <?php if (isset($component)) { $__componentOriginala78585284581daae177d448ae16c046d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala78585284581daae177d448ae16c046d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth.logo','data' => ['class' => 'mb-6 justify-center lg:justify-start']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth.logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-6 justify-center lg:justify-start']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala78585284581daae177d448ae16c046d)): ?>
<?php $attributes = $__attributesOriginala78585284581daae177d448ae16c046d; ?>
<?php unset($__attributesOriginala78585284581daae177d448ae16c046d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala78585284581daae177d448ae16c046d)): ?>
<?php $component = $__componentOriginala78585284581daae177d448ae16c046d; ?>
<?php unset($__componentOriginala78585284581daae177d448ae16c046d); ?>
<?php endif; ?>
                    <h2 class="text-3xl font-bold text-white mb-2">
                        Bon retour <span class="inline-block animate-wave">👋</span>
                    </h2>
                    <p class="text-slate-400">Connectez-vous à votre espace de gestion</p>
                </div>

                
                <!--[if BLOCK]><![endif]--><?php if($successMessage): ?>
                    <?php if (isset($component)) { $__componentOriginalec0b2479ca7af9061a97e3944b92d264 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalec0b2479ca7af9061a97e3944b92d264 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth.alert','data' => ['type' => 'success','message' => $successMessage]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth.alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'success','message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($successMessage)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalec0b2479ca7af9061a97e3944b92d264)): ?>
<?php $attributes = $__attributesOriginalec0b2479ca7af9061a97e3944b92d264; ?>
<?php unset($__attributesOriginalec0b2479ca7af9061a97e3944b92d264); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalec0b2479ca7af9061a97e3944b92d264)): ?>
<?php $component = $__componentOriginalec0b2479ca7af9061a97e3944b92d264; ?>
<?php unset($__componentOriginalec0b2479ca7af9061a97e3944b92d264); ?>
<?php endif; ?>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                
                <!--[if BLOCK]><![endif]--><?php if(session('status')): ?>
                    <?php if (isset($component)) { $__componentOriginalec0b2479ca7af9061a97e3944b92d264 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalec0b2479ca7af9061a97e3944b92d264 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth.alert','data' => ['type' => 'info','message' => session('status')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth.alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'info','message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('status'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalec0b2479ca7af9061a97e3944b92d264)): ?>
<?php $attributes = $__attributesOriginalec0b2479ca7af9061a97e3944b92d264; ?>
<?php unset($__attributesOriginalec0b2479ca7af9061a97e3944b92d264); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalec0b2479ca7af9061a97e3944b92d264)): ?>
<?php $component = $__componentOriginalec0b2479ca7af9061a97e3944b92d264; ?>
<?php unset($__componentOriginalec0b2479ca7af9061a97e3944b92d264); ?>
<?php endif; ?>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                
                <!--[if BLOCK]><![endif]--><?php if($errorMessage && !$errors->any()): ?>
                    <?php if (isset($component)) { $__componentOriginalec0b2479ca7af9061a97e3944b92d264 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalec0b2479ca7af9061a97e3944b92d264 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth.alert','data' => ['type' => 'error','title' => 'Erreur de connexion','message' => $errorMessage,'dismissible' => true,'dismissAction' => '$set(\'errorMessage\', null)','animate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth.alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'error','title' => 'Erreur de connexion','message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errorMessage),'dismissible' => true,'dismiss-action' => '$set(\'errorMessage\', null)','animate' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalec0b2479ca7af9061a97e3944b92d264)): ?>
<?php $attributes = $__attributesOriginalec0b2479ca7af9061a97e3944b92d264; ?>
<?php unset($__attributesOriginalec0b2479ca7af9061a97e3944b92d264); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalec0b2479ca7af9061a97e3944b92d264)): ?>
<?php $component = $__componentOriginalec0b2479ca7af9061a97e3944b92d264; ?>
<?php unset($__componentOriginalec0b2479ca7af9061a97e3944b92d264); ?>
<?php endif; ?>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                
                <!--[if BLOCK]><![endif]--><?php if(!$isLocked): ?>
                    <?php if (isset($component)) { $__componentOriginal6a7afb30980a5bfb5ccd7a4e016d5191 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6a7afb30980a5bfb5ccd7a4e016d5191 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth.attempts-warning','data' => ['remaining' => $remainingAttempts,'max' => $maxAttempts]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth.attempts-warning'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['remaining' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($remainingAttempts),'max' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($maxAttempts)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6a7afb30980a5bfb5ccd7a4e016d5191)): ?>
<?php $attributes = $__attributesOriginal6a7afb30980a5bfb5ccd7a4e016d5191; ?>
<?php unset($__attributesOriginal6a7afb30980a5bfb5ccd7a4e016d5191); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6a7afb30980a5bfb5ccd7a4e016d5191)): ?>
<?php $component = $__componentOriginal6a7afb30980a5bfb5ccd7a4e016d5191; ?>
<?php unset($__componentOriginal6a7afb30980a5bfb5ccd7a4e016d5191); ?>
<?php endif; ?>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                
                <!--[if BLOCK]><![endif]--><?php if($isLocked): ?>
                    <?php if (isset($component)) { $__componentOriginal31c72d2af47e99ded6a9dff3c304b673 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal31c72d2af47e99ded6a9dff3c304b673 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth.lockout-timer','data' => ['seconds' => $lockoutSeconds]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth.lockout-timer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['seconds' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($lockoutSeconds)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal31c72d2af47e99ded6a9dff3c304b673)): ?>
<?php $attributes = $__attributesOriginal31c72d2af47e99ded6a9dff3c304b673; ?>
<?php unset($__attributesOriginal31c72d2af47e99ded6a9dff3c304b673); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal31c72d2af47e99ded6a9dff3c304b673)): ?>
<?php $component = $__componentOriginal31c72d2af47e99ded6a9dff3c304b673; ?>
<?php unset($__componentOriginal31c72d2af47e99ded6a9dff3c304b673); ?>
<?php endif; ?>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                
                <form wire:submit.prevent="login" class="space-y-4" autocomplete="on">
                    
                    <?php if (isset($component)) { $__componentOriginal4fe80e9d239d0b60843c2b8ddd36eccb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4fe80e9d239d0b60843c2b8ddd36eccb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth.input','data' => ['wire:model.live' => 'email','type' => 'email','name' => 'email','label' => 'Adresse e-mail','placeholder' => 'vous@exemple.com','icon' => 'email','autocomplete' => 'email','autofocus' => true,'error' => $errors->first('email')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'email','type' => 'email','name' => 'email','label' => 'Adresse e-mail','placeholder' => 'vous@exemple.com','icon' => 'email','autocomplete' => 'email','autofocus' => true,'error' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->first('email'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4fe80e9d239d0b60843c2b8ddd36eccb)): ?>
<?php $attributes = $__attributesOriginal4fe80e9d239d0b60843c2b8ddd36eccb; ?>
<?php unset($__attributesOriginal4fe80e9d239d0b60843c2b8ddd36eccb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4fe80e9d239d0b60843c2b8ddd36eccb)): ?>
<?php $component = $__componentOriginal4fe80e9d239d0b60843c2b8ddd36eccb; ?>
<?php unset($__componentOriginal4fe80e9d239d0b60843c2b8ddd36eccb); ?>
<?php endif; ?>

                    
                    <?php if (isset($component)) { $__componentOriginal4fe80e9d239d0b60843c2b8ddd36eccb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4fe80e9d239d0b60843c2b8ddd36eccb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth.input','data' => ['wire:model.live' => 'password','type' => 'password','name' => 'password','label' => 'Mot de passe','placeholder' => 'Votre mot de passe','icon' => 'lock','showPasswordToggle' => false,'autocomplete' => 'current-password','error' => $errors->first('password')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'password','type' => 'password','name' => 'password','label' => 'Mot de passe','placeholder' => 'Votre mot de passe','icon' => 'lock','showPasswordToggle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'autocomplete' => 'current-password','error' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->first('password'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4fe80e9d239d0b60843c2b8ddd36eccb)): ?>
<?php $attributes = $__attributesOriginal4fe80e9d239d0b60843c2b8ddd36eccb; ?>
<?php unset($__attributesOriginal4fe80e9d239d0b60843c2b8ddd36eccb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4fe80e9d239d0b60843c2b8ddd36eccb)): ?>
<?php $component = $__componentOriginal4fe80e9d239d0b60843c2b8ddd36eccb; ?>
<?php unset($__componentOriginal4fe80e9d239d0b60843c2b8ddd36eccb); ?>
<?php endif; ?>

                    
                    <div class="flex items-center justify-between">
                        <label for="remember" class="flex items-center cursor-pointer">
                            <input wire:model="remember" type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-indigo-500 focus:ring-indigo-500/20 focus:ring-offset-0">
                            <span class="ml-2 text-sm text-slate-400">Se souvenir de moi</span>
                        </label>
                        <!--[if BLOCK]><![endif]--><?php if(Route::has('password.request')): ?>
                            <a href="<?php echo e(route('password.request')); ?>" class="text-sm text-indigo-400 hover:text-indigo-300 transition">Mot de passe oublié ?</a>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    
                    <?php if (isset($component)) { $__componentOriginal15a15053a35cef5a0af1d2ea8afb55eb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal15a15053a35cef5a0af1d2ea8afb55eb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth.submit-button','data' => ['disabled' => $isLocked,'text' => 'Se connecter','loadingText' => 'Connexion...','lockedText' => 'Bloqué']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth.submit-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isLocked),'text' => 'Se connecter','loadingText' => 'Connexion...','lockedText' => 'Bloqué']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal15a15053a35cef5a0af1d2ea8afb55eb)): ?>
<?php $attributes = $__attributesOriginal15a15053a35cef5a0af1d2ea8afb55eb; ?>
<?php unset($__attributesOriginal15a15053a35cef5a0af1d2ea8afb55eb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal15a15053a35cef5a0af1d2ea8afb55eb)): ?>
<?php $component = $__componentOriginal15a15053a35cef5a0af1d2ea8afb55eb; ?>
<?php unset($__componentOriginal15a15053a35cef5a0af1d2ea8afb55eb); ?>
<?php endif; ?>

                    
                    <!--[if BLOCK]><![endif]--><?php if(Route::has('register')): ?>
                        <p class="text-center text-sm text-slate-400">
                            Pas de compte ?
                            <a href="<?php echo e(route('register')); ?>" class="text-indigo-400 hover:text-indigo-300 font-medium transition">
                                Créer un compte
                            </a>
                        </p>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </form>

                
                <?php if (isset($component)) { $__componentOriginal88b35301cf9225e508665aec7f094084 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal88b35301cf9225e508665aec7f094084 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth.security-badges','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth.security-badges'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal88b35301cf9225e508665aec7f094084)): ?>
<?php $attributes = $__attributesOriginal88b35301cf9225e508665aec7f094084; ?>
<?php unset($__attributesOriginal88b35301cf9225e508665aec7f094084); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal88b35301cf9225e508665aec7f094084)): ?>
<?php $component = $__componentOriginal88b35301cf9225e508665aec7f094084; ?>
<?php unset($__componentOriginal88b35301cf9225e508665aec7f094084); ?>
<?php endif; ?>
            </div>
        </div>

        
        <?php if (isset($component)) { $__componentOriginal61720fcd8173bea4b44715d48a8ea486 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal61720fcd8173bea4b44715d48a8ea486 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth.info-sidebar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth.info-sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal61720fcd8173bea4b44715d48a8ea486)): ?>
<?php $attributes = $__attributesOriginal61720fcd8173bea4b44715d48a8ea486; ?>
<?php unset($__attributesOriginal61720fcd8173bea4b44715d48a8ea486); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal61720fcd8173bea4b44715d48a8ea486)): ?>
<?php $component = $__componentOriginal61720fcd8173bea4b44715d48a8ea486; ?>
<?php unset($__componentOriginal61720fcd8173bea4b44715d48a8ea486); ?>
<?php endif; ?>
    </div>

    
    <style>
        @keyframes blob { 0%, 100% { transform: translate(0, 0) scale(1); } 50% { transform: translate(20px, -20px) scale(1.05); } }
        .animate-blob { animation: blob 15s ease-in-out infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        @keyframes wave { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(20deg); } 75% { transform: rotate(-15deg); } }
        .animate-wave { animation: wave 1.5s ease-in-out infinite; transform-origin: 70% 70%; }
        @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
        .animate-shake { animation: shake 0.5s ease-in-out; }
        [x-cloak] { display: none !important; }
    </style>
</div>
<?php /**PATH D:\stk\stk-back\resources\views/livewire/auth/login.blade.php ENDPATH**/ ?>