<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 relative overflow-hidden">
    {{-- Background Elements --}}
    <x-auth.background />

    <div class="relative z-10 flex items-center justify-center min-h-screen p-6">
        <div class="max-w-4xl w-full">
            {{-- Header --}}
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white mb-3">
                    Finalisez votre inscription
                </h1>
                <p class="text-slate-400 text-lg">
                    Procédez au paiement pour activer votre organisation
                </p>
            </div>

            <div class="grid lg:grid-cols-2 gap-6">
                {{-- Plan Summary --}}
                <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-8">
                    <h2 class="text-2xl font-bold text-white mb-6">Récapitulatif</h2>

                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between py-3 border-b border-slate-700">
                            <span class="text-slate-400">Organisation</span>
                            <span class="text-white font-medium">{{ $organization->name }}</span>
                        </div>

                        <div class="flex justify-between py-3 border-b border-slate-700">
                            <span class="text-slate-400">Plan</span>
                            <span class="text-white font-medium">
                                {{ $planData['name'] ?? $organization->subscription_plan->label() }}
                            </span>
                        </div>

                        <div class="flex justify-between py-3 border-b border-slate-700">
                            <span class="text-slate-400">Montant</span>
                            <span class="text-2xl font-bold text-indigo-400">
                                {{ number_format($planData['price'] ?? 0, 0, ',', ' ') }} {{ $currency }}
                            </span>
                        </div>

                        <div class="flex justify-between py-3">
                            <span class="text-slate-400">Période</span>
                            <span class="text-white font-medium">Mensuel</span>
                        </div>
                    </div>

                    {{-- Plan Features --}}
                    <div class="bg-slate-900/50 rounded-xl p-6">
                        <h3 class="text-lg font-bold text-white mb-4">Ce qui est inclus :</h3>
                        <ul class="space-y-3 text-sm text-slate-300">
                            @foreach(($planData['features'] ?? []) as $feature)
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-indigo-400 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Payment Form --}}
                <div class="space-y-6">
                    <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-8">
                        <h2 class="text-2xl font-bold text-white mb-6">Méthode de paiement</h2>

                        <form wire:submit.prevent="processPayment" class="space-y-6">
                            <button
                                type="submit"
                                class="w-full px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700
                                    text-white font-bold rounded-xl transition-all transform hover:scale-[1.02] active:scale-[0.98]
                                    shadow-lg shadow-indigo-500/50">
                                <span wire:loading.remove wire:target="processPayment">
                                    Procéder au paiement - {{ number_format($planData['price'] ?? 0, 0, ',', ' ') }} {{ $currency }}
                                </span>
                                <span wire:loading wire:target="processPayment">
                                    Traitement en cours...
                                </span>
                            </button>

                            {{-- Security Badge --}}
                            <div class="flex items-center justify-center text-sm text-slate-400">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Paiement 100% sécurisé et crypté
                            </div>
                        </form>
                    </div>

                    {{-- Alternative: Free Plan --}}
                    <div class="bg-slate-800/30 border border-slate-700/50 rounded-2xl p-6 text-center">
                        <p class="text-slate-400 text-sm mb-4">
                            Vous préférez commencer gratuitement ?
                        </p>
                        <button
                            wire:click="useFreePlan"
                            class="text-indigo-400 hover:text-indigo-300 font-medium text-sm underline">
                            Passer au plan gratuit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
