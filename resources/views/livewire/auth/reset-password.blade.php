<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-orange-950 relative overflow-hidden">
    {{-- Background Elements --}}
    <x-auth.background />

    <div class="relative z-10 grid lg:grid-cols-2 gap-0 min-h-screen">
        {{-- Left Column - Form --}}
        <div class="p-6 sm:p-8 lg:p-10 flex flex-col justify-center">
            <div class="max-w-sm mx-auto w-full space-y-6">
                {{-- Logo & Header --}}
                <div class="text-center lg:text-left">
                    <x-auth.logo class="mb-6 justify-center lg:justify-start" />
                    <h2 class="text-3xl font-bold text-white mb-2">
                        Nouveau mot de passe <span class="inline-block">🔐</span>
                    </h2>
                    <p class="text-slate-400">Créez un mot de passe fort pour sécuriser votre compte</p>
                </div>

                {{-- Reset Password Form --}}
                <form wire:submit.prevent="resetPassword" class="space-y-4" autocomplete="on">
                    {{-- Email Input --}}
                    <x-auth.input
                        wire:model="email"
                        type="email"
                        name="email"
                        label="Adresse e-mail"
                        placeholder="vous@exemple.com"
                        icon="email"
                        autocomplete="email"
                        autofocus
                        :error="$errors->first('email')"
                    />

                    {{-- Password Input --}}
                    <x-auth.input
                        wire:model="password"
                        type="password"
                        name="password"
                        label="Nouveau mot de passe"
                        placeholder="Minimum 8 caractères"
                        icon="lock"
                        :showPasswordToggle="true"
                        autocomplete="new-password"
                        :error="$errors->first('password')"
                    />

                    {{-- Confirm Password Input --}}
                    <x-auth.input
                        wire:model="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        label="Confirmer le mot de passe"
                        placeholder="Répétez votre mot de passe"
                        icon="lock"
                        :showPasswordToggle="true"
                        autocomplete="new-password"
                    />

                    {{-- Submit Button --}}
                    <x-auth.submit-button
                        text="Réinitialiser"
                        loadingText="Réinitialisation..."
                        loadingTarget="resetPassword"
                    />
                </form>

                {{-- Security Badges --}}
                <x-auth.security-badges />
            </div>
        </div>

        {{-- Right Column - Password Tips --}}
        <div class="hidden lg:flex relative bg-gradient-to-br from-amber-500 via-orange-500 to-red-500 p-8 text-white overflow-hidden">
            {{-- Background effects --}}
            <div class="absolute inset-0">
                <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(at 40% 20%, hsla(30,100%,70%,0.4) 0px, transparent 50%),radial-gradient(at 80% 0%, hsla(0,100%,70%,0.3) 0px, transparent 50%);"></div>
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/4"></div>
            </div>

            <div class="relative z-10 flex flex-col justify-between w-full h-full">
                {{-- Status badge --}}
                <div class="flex justify-end">
                    <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur rounded-full px-4 py-2 text-sm border border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Protection renforcée</span>
                    </div>
                </div>

                {{-- Main content --}}
                <div class="space-y-6">
                    <div>
                        <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-3xl font-bold mb-3">
                            Créez un<br>
                            <span class="text-amber-200">mot de passe fort</span>
                        </h3>
                        <p class="text-orange-100 max-w-md">
                            Un bon mot de passe est votre première ligne de défense.
                        </p>
                    </div>

                    {{-- Requirements --}}
                    <div class="space-y-3">
                        <h4 class="font-semibold text-lg flex items-center gap-2">
                            <span>✅</span> Votre mot de passe doit contenir :
                        </h4>

                        <div class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur rounded-xl border border-white/10">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="text-sm">Au moins 8 caractères</span>
                        </div>

                        <div class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur rounded-xl border border-white/10">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="text-sm">Lettres majuscules et minuscules</span>
                        </div>

                        <div class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur rounded-xl border border-white/10">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="text-sm">Chiffres et symboles (@, #, $...)</span>
                        </div>
                    </div>
                </div>

                {{-- Tips --}}
                <div class="bg-white/10 backdrop-blur rounded-xl p-4 border border-white/20">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-white/90 text-sm">N'utilisez jamais le même mot de passe sur plusieurs sites !</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom Styles --}}
    <style>
        @keyframes blob { 0%, 100% { transform: translate(0, 0) scale(1); } 50% { transform: translate(20px, -20px) scale(1.05); } }
        .animate-blob { animation: blob 15s ease-in-out infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        [x-cloak] { display: none !important; }
    </style>
</div>
