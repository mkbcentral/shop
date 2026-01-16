<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-purple-950 relative overflow-hidden">
    {{-- Background Elements --}}
    <x-auth.background />

    <div class="relative z-10 grid lg:grid-cols-2 gap-0 min-h-screen">
        {{-- Left Column - Form --}}
        <div class="p-6 sm:p-8 lg:p-10 flex flex-col justify-center">
            <div class="max-w-sm mx-auto w-full space-y-6">
                {{-- Logo & Header --}}
                <div class="text-center lg:text-left">
                    <x-auth.logo class="mb-6 justify-center lg:justify-start" />

                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500/20 to-purple-500/20 rounded-2xl flex items-center justify-center mb-4 mx-auto lg:mx-0 border border-indigo-500/30">
                        <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>

                    <h2 class="text-3xl font-bold text-white mb-2">
                        Vérification 2FA
                    </h2>
                    <p class="text-slate-400">
                        @if ($useRecoveryCode)
                            Entrez l'un de vos codes de récupération d'urgence
                        @else
                            Entrez le code à 6 chiffres depuis votre application
                        @endif
                    </p>
                </div>

                {{-- Two Factor Form --}}
                <form wire:submit.prevent="authenticate" class="space-y-4" autocomplete="on">
                    @if (!$useRecoveryCode)
                        {{-- Authentication Code --}}
                        <div>
                            <label for="code" class="block text-sm font-medium text-slate-300 mb-1.5">Code d'authentification</label>
                            <input
                                wire:model="code"
                                type="text"
                                id="code"
                                name="code"
                                inputmode="numeric"
                                maxlength="6"
                                placeholder="000000"
                                autocomplete="one-time-code"
                                autofocus
                                class="block w-full px-4 py-3 bg-slate-800/50 border border-slate-700 text-white placeholder-slate-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-150 text-center text-2xl font-mono tracking-[0.5em] {{ $errors->has('code') ? 'border-red-500 focus:border-red-500' : '' }}"
                            />
                            <p class="mt-1.5 text-xs text-slate-500">Ouvrez votre application d'authentification</p>
                            <x-auth.field-error for="code" />
                        </div>
                    @else
                        {{-- Recovery Code --}}
                        <div>
                            <label for="recovery_code" class="block text-sm font-medium text-slate-300 mb-1.5">Code de récupération</label>
                            <input
                                wire:model="recovery_code"
                                type="text"
                                id="recovery_code"
                                name="recovery_code"
                                placeholder="XXXXX-XXXXX"
                                autocomplete="one-time-code"
                                autofocus
                                class="block w-full px-4 py-3 bg-slate-800/50 border border-slate-700 text-white placeholder-slate-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition duration-150 text-center text-xl font-mono {{ $errors->has('recovery_code') ? 'border-red-500 focus:border-red-500' : '' }}"
                            />
                            <p class="mt-1.5 text-xs text-slate-500">Utilisez l'un de vos codes de récupération sauvegardés</p>
                            <x-auth.field-error for="recovery_code" />
                        </div>
                    @endif

                    {{-- Submit Button --}}
                    <x-auth.submit-button
                        text="Vérifier et continuer"
                        loadingText="Vérification..."
                        loadingTarget="authenticate"
                    />

                    {{-- Toggle Recovery Code --}}
                    <div class="text-center">
                        <button
                            type="button"
                            wire:click="toggleRecoveryCode"
                            class="text-sm text-indigo-400 hover:text-indigo-300 font-medium transition inline-flex items-center gap-2"
                        >
                            @if ($useRecoveryCode)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Utiliser un code d'authentification
                            @else
                                Utiliser un code de récupération
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            @endif
                        </button>
                    </div>
                </form>

                {{-- Security Badges --}}
                <x-auth.security-badges />
            </div>
        </div>

        {{-- Right Column - 2FA Info --}}
        <div class="hidden lg:flex relative bg-gradient-to-br from-purple-600 via-fuchsia-600 to-pink-600 p-8 text-white overflow-hidden">
            {{-- Background effects --}}
            <div class="absolute inset-0">
                <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(at 40% 20%, hsla(280,100%,70%,0.4) 0px, transparent 50%),radial-gradient(at 80% 0%, hsla(320,100%,70%,0.3) 0px, transparent 50%);"></div>
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/4"></div>
            </div>

            <div class="relative z-10 flex flex-col justify-between w-full h-full">
                {{-- Status badge --}}
                <div class="flex justify-end">
                    <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur rounded-full px-4 py-2 text-sm border border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>2FA activé</span>
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
                            Sécurité<br>
                            <span class="text-fuchsia-200">renforcée</span>
                        </h3>
                        <p class="text-purple-100 max-w-md">
                            L'authentification à deux facteurs ajoute une couche de protection supplémentaire.
                        </p>
                    </div>

                    {{-- Benefits --}}
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur rounded-xl border border-white/10">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-sm">Protection maximale</h4>
                                <p class="text-purple-200 text-xs">Même si votre mot de passe est compromis</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur rounded-xl border border-white/10">
                            <div class="w-10 h-10 bg-fuchsia-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-sm">Code temporaire unique</h4>
                                <p class="text-purple-200 text-xs">Nouveau code toutes les 30 secondes</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur rounded-xl border border-white/10">
                            <div class="w-10 h-10 bg-pink-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.658a9 9 0 01-2.167-9.238m7.824 2.167a1 1 0 111.414 1.414m-1.414-1.414L3 3m8.293 8.293l1.414 1.414" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-sm">Fonctionne hors ligne</h4>
                                <p class="text-purple-200 text-xs">Pas besoin d'Internet</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tips --}}
                <div class="bg-white/10 backdrop-blur rounded-xl p-4 border border-white/20">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-white/90 text-sm">Gardez vos <span class="font-semibold">codes de récupération</span> en lieu sûr !</p>
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
