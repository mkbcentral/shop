<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 relative overflow-hidden">
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
                        Vérifiez votre e-mail <span class="inline-block">📧</span>
                    </h2>
                    <p class="text-slate-400">
                        Merci de vous être inscrit ! Veuillez vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer.
                    </p>
                </div>

                {{-- Status Message --}}
                @if (session('status') == 'verification-link-sent')
                    <x-auth.alert type="success" message="Un nouveau lien de vérification a été envoyé à votre adresse e-mail." />
                @endif

                {{-- Actions --}}
                <div class="space-y-4">
                    <button
                        wire:click="resendVerification"
                        type="button"
                        class="w-full py-3 px-4 rounded-xl text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-lg shadow-indigo-500/30"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                    >
                        <span wire:loading.remove wire:target="resendVerification">Renvoyer l'e-mail</span>
                        <span wire:loading wire:target="resendVerification">Envoi en cours...</span>
                    </button>

                    <button
                        wire:click="logout"
                        type="button"
                        class="w-full py-2.5 px-4 rounded-xl text-sm font-medium text-slate-400 bg-slate-800/50 border border-slate-700 hover:bg-slate-800 hover:text-white transition-all duration-300"
                    >
                        Se déconnecter
                    </button>
                </div>

                {{-- Security Badges --}}
                <x-auth.security-badges />
            </div>
        </div>

        {{-- Right Column - Info --}}
        <div class="hidden lg:flex relative bg-gradient-to-br from-indigo-600 via-purple-600 to-fuchsia-600 p-8 text-white overflow-hidden">
            {{-- Background effects --}}
            <div class="absolute inset-0">
                <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(at 40% 20%, hsla(250,100%,70%,0.4) 0px, transparent 50%),radial-gradient(at 80% 0%, hsla(290,100%,70%,0.3) 0px, transparent 50%);"></div>
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/4"></div>
            </div>

            <div class="relative z-10 flex flex-col justify-between w-full h-full">
                {{-- Status badge --}}
                <div class="flex justify-end">
                    <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur rounded-full px-4 py-2 text-sm border border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>Vérification en attente</span>
                    </div>
                </div>

                {{-- Main content --}}
                <div class="space-y-6">
                    <div>
                        <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76" />
                            </svg>
                        </div>
                        <h3 class="text-3xl font-bold mb-3">
                            Sécurisez<br>
                            <span class="text-purple-200">votre compte</span>
                        </h3>
                        <p class="text-purple-100 max-w-md">
                            La vérification de votre adresse e-mail nous aide à garantir la sécurité de votre compte.
                        </p>
                    </div>

                    {{-- Benefits --}}
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur rounded-xl border border-white/10">
                            <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-sm">Protection du compte</h4>
                                <p class="text-purple-200 text-xs">Empêche les accès non autorisés</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur rounded-xl border border-white/10">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-sm">Récupération de mot de passe</h4>
                                <p class="text-purple-200 text-xs">En cas d'oubli de votre mot de passe</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur rounded-xl border border-white/10">
                            <div class="w-10 h-10 bg-fuchsia-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-sm">Notifications importantes</h4>
                                <p class="text-purple-200 text-xs">Restez informé des activités</p>
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
                            <p class="text-white/90 text-sm">Vérifiez vos <span class="font-semibold">spams</span> si vous ne trouvez pas l'e-mail.</p>
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
