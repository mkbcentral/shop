<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-red-950 relative overflow-hidden">
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
                        Zone sécurisée <span class="inline-block">🔒</span>
                    </h2>
                    <p class="text-slate-400">
                        Veuillez confirmer votre mot de passe avant de continuer.
                    </p>
                </div>

                {{-- Confirm Password Form --}}
                <form wire:submit.prevent="confirmPassword" class="space-y-4" autocomplete="on">
                    {{-- Password Input --}}
                    <x-auth.input
                        wire:model="password"
                        type="password"
                        name="password"
                        label="Mot de passe"
                        placeholder="Entrez votre mot de passe"
                        icon="lock"
                        :showPasswordToggle="true"
                        autocomplete="current-password"
                        autofocus
                        :error="$errors->first('password')"
                    />

                    {{-- Submit Button --}}
                    <x-auth.submit-button
                        text="Confirmer"
                        loadingText="Vérification..."
                        loadingTarget="confirmPassword"
                    />
                </form>

                {{-- Security Badges --}}
                <x-auth.security-badges />
            </div>
        </div>

        {{-- Right Column - Info --}}
        <div class="hidden lg:flex relative bg-gradient-to-br from-red-600 via-rose-600 to-pink-600 p-8 text-white overflow-hidden">
            {{-- Background effects --}}
            <div class="absolute inset-0">
                <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(at 40% 20%, hsla(0,100%,70%,0.4) 0px, transparent 50%),radial-gradient(at 80% 0%, hsla(330,100%,70%,0.3) 0px, transparent 50%);"></div>
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/4"></div>
            </div>

            <div class="relative z-10 flex flex-col justify-between w-full h-full">
                {{-- Status badge --}}
                <div class="flex justify-end">
                    <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur rounded-full px-4 py-2 text-sm border border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span>Accès restreint</span>
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
                            Vérification<br>
                            <span class="text-rose-200">d'identité</span>
                        </h3>
                        <p class="text-rose-100 max-w-md">
                            Pour accéder à cette section sensible, nous devons vérifier votre identité.
                        </p>
                    </div>

                    {{-- Security features --}}
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur rounded-xl border border-white/10">
                            <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-sm">Protection renforcée</h4>
                                <p class="text-rose-200 text-xs">Double vérification de sécurité</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur rounded-xl border border-white/10">
                            <div class="w-10 h-10 bg-rose-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-sm">Sécurité maximale</h4>
                                <p class="text-rose-200 text-xs">Données sensibles protégées</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur rounded-xl border border-white/10">
                            <div class="w-10 h-10 bg-pink-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-sm">Session sécurisée</h4>
                                <p class="text-rose-200 text-xs">Connexion chiffrée</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Notice --}}
                <div class="bg-white/10 backdrop-blur rounded-xl p-4 border border-white/20">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-white/90 text-sm">Cette vérification expire après quelques minutes d'inactivité.</p>
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
