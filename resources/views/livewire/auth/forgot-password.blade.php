<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950 relative overflow-hidden">
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
                        Mot de passe oublié ? <span class="inline-block">🔑</span>
                    </h2>
                    <p class="text-slate-400">Indiquez votre e-mail pour recevoir un lien de réinitialisation</p>
                </div>

                {{-- Status Message --}}
                @if (session('status'))
                    <x-auth.alert type="success" :message="session('status')" />
                @endif

                {{-- Forgot Password Form --}}
                <form wire:submit.prevent="sendResetLink" class="space-y-4" autocomplete="on">
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

                    {{-- Submit Button --}}
                    <x-auth.submit-button
                        text="Envoyer le lien"
                        loadingText="Envoi..."
                        loadingTarget="sendResetLink"
                    />

                    {{-- Back to Login --}}
                    <p class="text-center">
                        <a href="{{ route('login') }}" class="text-sm text-indigo-400 hover:text-indigo-300 font-medium transition inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Retour à la connexion
                        </a>
                    </p>
                </form>

                {{-- Security Badges --}}
                <x-auth.security-badges />
            </div>
        </div>

        {{-- Right Column - Info --}}
        <div class="hidden lg:flex relative bg-gradient-to-br from-blue-600 via-indigo-600 to-violet-600 p-8 text-white overflow-hidden">
            {{-- Background effects --}}
            <div class="absolute inset-0">
                <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(at 40% 20%, hsla(230,100%,70%,0.4) 0px, transparent 50%),radial-gradient(at 80% 0%, hsla(270,100%,70%,0.3) 0px, transparent 50%);"></div>
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/4"></div>
            </div>

            <div class="relative z-10 flex flex-col justify-between w-full h-full">
                {{-- Status badge --}}
                <div class="flex justify-end">
                    <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur rounded-full px-4 py-2 text-sm border border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span>Processus sécurisé</span>
                    </div>
                </div>

                {{-- Main content --}}
                <div class="space-y-6">
                    <div>
                        <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>
                        <h3 class="text-3xl font-bold mb-3">
                            Réinitialisation<br>
                            <span class="text-blue-200">sécurisée</span>
                        </h3>
                        <p class="text-blue-100 max-w-md">
                            Nous prenons la sécurité de votre compte très au sérieux.
                        </p>
                    </div>

                    {{-- Steps --}}
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur rounded-xl border border-white/10">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center font-bold">1</div>
                            <div>
                                <h4 class="font-semibold text-sm">Entrez votre e-mail</h4>
                                <p class="text-blue-200 text-xs">L'adresse associée à votre compte</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur rounded-xl border border-white/10">
                            <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center font-bold">2</div>
                            <div>
                                <h4 class="font-semibold text-sm">Vérifiez votre boîte mail</h4>
                                <p class="text-blue-200 text-xs">Un lien vous sera envoyé</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur rounded-xl border border-white/10">
                            <div class="w-10 h-10 bg-violet-500 rounded-lg flex items-center justify-center font-bold">3</div>
                            <div>
                                <h4 class="font-semibold text-sm">Créez un nouveau mot de passe</h4>
                                <p class="text-blue-200 text-xs">Choisissez un mot de passe fort</p>
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
                            <p class="text-white/90 text-sm">Le lien expire après <span class="font-semibold">60 minutes</span>. Pensez à vérifier vos spams.</p>
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
