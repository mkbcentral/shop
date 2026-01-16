<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 relative overflow-hidden">
    {{-- Background Elements --}}
    <x-auth.background />

    <div class="relative z-10 grid lg:grid-cols-2 gap-0 min-h-screen">
        {{-- Left Column - Form --}}
        <div class="p-6 sm:p-8 lg:p-10 flex flex-col justify-center">
            <div class="max-w-2xl mx-auto w-full space-y-6">
                {{-- Logo & Header --}}
                <div class="text-center lg:text-left">
                    <x-auth.logo class="mb-6 justify-center lg:justify-start" />
                    <h2 class="text-3xl font-bold text-white mb-2">
                        Créer un compte <span class="inline-block animate-wave">🚀</span>
                    </h2>
                    <p class="text-slate-400">Rejoignez-nous pour gérer votre commerce</p>
                </div>

                {{-- Progress Steps --}}
                <div class="flex items-center justify-center space-x-2 mb-8">
                    {{-- Step 1 --}}
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full transition-all
                            {{ $currentStep >= 1 ? 'bg-indigo-600 text-white' : 'bg-slate-700 text-slate-400' }}">
                            @if($currentStep > 1)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                1
                            @endif
                        </div>
                        <span class="ml-2 text-sm font-medium {{ $currentStep >= 1 ? 'text-white' : 'text-slate-400' }}">
                            Informations
                        </span>
                    </div>

                    <div class="w-12 h-0.5 {{ $currentStep >= 2 ? 'bg-indigo-600' : 'bg-slate-700' }}"></div>

                    {{-- Step 2 --}}
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full transition-all
                            {{ $currentStep >= 2 ? 'bg-indigo-600 text-white' : 'bg-slate-700 text-slate-400' }}">
                            @if($currentStep > 2)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                2
                            @endif
                        </div>
                        <span class="ml-2 text-sm font-medium {{ $currentStep >= 2 ? 'text-white' : 'text-slate-400' }}">
                            Organisation
                        </span>
                    </div>

                    <div class="w-12 h-0.5 {{ $currentStep >= 3 ? 'bg-indigo-600' : 'bg-slate-700' }}"></div>

                    {{-- Step 3 --}}
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full transition-all
                            {{ $currentStep >= 3 ? 'bg-indigo-600 text-white' : 'bg-slate-700 text-slate-400' }}">
                            3
                        </div>
                        <span class="ml-2 text-sm font-medium {{ $currentStep >= 3 ? 'text-white' : 'text-slate-400' }}">
                            Confirmation
                        </span>
                    </div>
                </div>

                {{-- Step 1: User Information --}}
                @if($currentStep === 1)
                    <div wire:key="step-{{ $currentStep }}">
                        <livewire:auth.register-steps.step-one :key="'step-one-' . now()->timestamp" />
                    </div>
                @endif

                {{-- Step 2: Organization & Plan --}}
                @if($currentStep === 2)
                    <div wire:key="step-{{ $currentStep }}">
                        <livewire:auth.register-steps.step-two :key="'step-two-' . now()->timestamp" />
                    </div>
                @endif

                {{-- Step 3: Confirmation --}}
                @if($currentStep === 3)
                    <div wire:key="step-{{ $currentStep }}">
                        <livewire:auth.register-steps.step-three :key="'step-three-' . now()->timestamp" />
                    </div>
                @endif

                {{-- Login Link --}}
                <p class="text-center text-sm text-slate-400">
                    Déjà un compte ?
                    <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-medium transition">
                        Se connecter
                    </a>
                </p>

                {{-- Security Badges --}}
                <x-auth.security-badges />
            </div>
        </div>

        {{-- Right Column - Benefits --}}
        <div class="hidden lg:flex relative bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-600 p-8 text-white overflow-hidden">
            {{-- Background effects --}}
            <div class="absolute inset-0">
                <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(at 40% 20%, hsla(160,100%,70%,0.4) 0px, transparent 50%),radial-gradient(at 80% 0%, hsla(180,100%,70%,0.3) 0px, transparent 50%);"></div>
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/4"></div>
            </div>

            <div class="relative z-10 flex flex-col justify-between w-full h-full">
                {{-- Status badge --}}
                <div class="flex justify-end">
                    <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur rounded-full px-4 py-2 text-sm border border-white/20">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-300 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-300"></span>
                        </span>
                        <span>Inscription gratuite</span>
                    </div>
                </div>

                {{-- Main content --}}
                <div class="space-y-6">
                    <div>
                        <h3 class="text-3xl font-bold mb-3">
                            Commencez<br>
                            <span class="text-emerald-200">gratuitement</span>
                        </h3>
                        <p class="text-emerald-100 max-w-md">
                            Profitez de toutes les fonctionnalités pour gérer votre commerce comme un pro.
                        </p>
                    </div>

                    {{-- Benefits --}}
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur rounded-xl border border-white/10">
                            <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-sm">Configuration rapide</h4>
                                <p class="text-emerald-200 text-xs">Démarrez en quelques minutes</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur rounded-xl border border-white/10">
                            <div class="w-10 h-10 bg-cyan-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-sm">Sécurisé et fiable</h4>
                                <p class="text-emerald-200 text-xs">Chiffrement avancé</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur rounded-xl border border-white/10">
                            <div class="w-10 h-10 bg-teal-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-sm">Multi-utilisateurs</h4>
                                <p class="text-emerald-200 text-xs">Collaborez en équipe</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-3">
                    <div class="text-center p-3 bg-white/10 backdrop-blur rounded-xl border border-white/20">
                        <div class="text-xl font-bold">500+</div>
                        <div class="text-emerald-200 text-xs">Entreprises</div>
                    </div>
                    <div class="text-center p-3 bg-white/10 backdrop-blur rounded-xl border border-white/20">
                        <div class="text-xl font-bold">24/7</div>
                        <div class="text-emerald-200 text-xs">Support</div>
                    </div>
                    <div class="text-center p-3 bg-white/10 backdrop-blur rounded-xl border border-white/20">
                        <div class="text-xl font-bold">99%</div>
                        <div class="text-emerald-200 text-xs">Satisfaction</div>
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
        @keyframes wave { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(20deg); } 75% { transform: rotate(-15deg); } }
        .animate-wave { animation: wave 1.5s ease-in-out infinite; transform-origin: 70% 70%; }
        [x-cloak] { display: none !important; }
    </style>
</div>
