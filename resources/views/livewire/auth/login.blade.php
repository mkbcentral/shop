<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 relative overflow-hidden">
    {{-- Background Elements --}}
    <x-auth.background />
    <x-toast/>  

    <div class="relative z-10 grid lg:grid-cols-2 gap-0 min-h-screen">
        {{-- Left Column - Form --}}
        <div class="p-6 sm:p-8 lg:p-10 flex flex-col justify-center">
            <div class="max-w-sm mx-auto w-full space-y-6">
                {{-- Logo & Header --}}
                <div class="text-center lg:text-left">
                    <x-auth.logo class="mb-6 justify-center lg:justify-start" />
                    <h2 class="text-3xl font-bold text-white mb-2">
                        Bon retour <span class="inline-block animate-wave">👋</span>
                    </h2>
                    <p class="text-slate-400">Connectez-vous à votre espace de gestion</p>
                </div>

                {{-- Success Message --}}
                @if ($successMessage)
                    <x-auth.alert type="success" :message="$successMessage" />
                @endif

                {{-- Status Message --}}
                @if (session('status'))
                    <x-auth.alert type="info" :message="session('status')" />
                @endif

                {{-- Error Message --}}
                @if ($errorMessage && !$errors->any())
                    <x-auth.alert
                        type="error"
                        title="Erreur de connexion"
                        :message="$errorMessage"
                        :dismissible="true"
                        dismiss-action="$set('errorMessage', null)"
                        :animate="true"
                    />
                @endif

                {{-- Attempts Warning --}}
                @if (!$isLocked)
                    <x-auth.attempts-warning :remaining="$remainingAttempts" :max="$maxAttempts" />
                @endif

                {{-- Lockout Timer --}}
                @if ($isLocked)
                    <x-auth.lockout-timer :seconds="$lockoutSeconds" />
                @endif

                {{-- Login Form --}}
                <form wire:submit="login" class="space-y-4" autocomplete="on">
                    {{-- Email Input --}}
                    <x-auth.input
                        wire:model.blur="form.email"
                        type="email"
                        name="email"
                        label="Adresse e-mail"
                        placeholder="vous@exemple.com"
                        icon="email"
                        autocomplete="email"
                        autofocus
                        :error="$errors->first('form.email')"
                    />

                    {{-- Password Input --}}
                    <x-auth.input
                        wire:model.blur="form.password"
                        type="password"
                        name="password"
                        label="Mot de passe"
                        placeholder="Votre mot de passe"
                        icon="lock"
                        :showPasswordToggle="true"
                        autocomplete="current-password"
                        :error="$errors->first('form.password')"
                    />

                    {{-- Remember Me & Forgot Password --}}
                    <div class="flex items-center justify-between">
                        <label for="remember" class="flex items-center cursor-pointer">
                            <input wire:model="remember" type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-indigo-500 focus:ring-indigo-500/20 focus:ring-offset-0">
                            <span class="ml-2 text-sm text-slate-400">Se souvenir de moi</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-indigo-400 hover:text-indigo-300 transition">Mot de passe oublié ?</a>
                        @endif
                    </div>

                    {{-- Submit Button --}}
                    <x-auth.submit-button
                        
                        text="Se connecter"
                        loadingText="Connexion..."
                        lockedText="Bloqué"
                    />

                    {{-- Register Link --}}
                    @if (Route::has('register'))
                        <p class="text-center text-sm text-slate-400">
                            Pas de compte ?
                            <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 font-medium transition">
                                Créer un compte
                            </a>
                        </p>
                    @endif
                </form>

                {{-- Security Badges --}}
                <x-auth.security-badges />
            </div>
        </div>

        {{-- Right Column - Info Sidebar --}}
        <x-auth.info-sidebar />
    </div>

    {{-- Custom Styles --}}
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
