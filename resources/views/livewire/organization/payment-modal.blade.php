<div>
    @if($showModal && $organization)
    <!-- Payment Overlay Modal - Blocks entire page -->
    <div class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-slate-900/95 backdrop-blur-sm transition-opacity"></div>

        <!-- Modal content -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800 to-slate-900 border border-slate-700 shadow-2xl transition-all w-full max-w-2xl">
                
                <!-- Header -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-8 text-center">
                    <div class="mx-auto w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white">Finalisez votre inscription</h2>
                    <p class="text-indigo-200 mt-2">Procédez au paiement pour activer votre organisation</p>
                </div>

                <!-- Body -->
                <div class="px-6 py-8">
                    <!-- Plan Summary -->
                    <div class="bg-slate-700/50 rounded-xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-white mb-4">Récapitulatif de votre commande</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-slate-600">
                                <span class="text-slate-400">Organisation</span>
                                <span class="text-white font-medium">{{ $organization->name }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center py-2 border-b border-slate-600">
                                <span class="text-slate-400">Plan choisi</span>
                                <span class="text-indigo-400 font-medium">
                                    {{ $planData['name'] ?? $organization->subscription_plan->label() }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center py-2">
                                <span class="text-slate-400">Montant à payer</span>
                                <span class="text-2xl font-bold text-emerald-400">
                                    {{ number_format($planData['price'] ?? 0, 0, ',', ' ') }} {{ $currency }}/mois
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Features -->
                    @if(!empty($planData['features']))
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-slate-400 mb-3">Ce qui est inclus :</h4>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach(array_slice($planData['features'] ?? [], 0, 6) as $feature)
                            <div class="flex items-center text-sm text-slate-300">
                                <svg class="w-4 h-4 text-emerald-400 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="truncate">{{ $feature }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Payment Button -->
                    <button
                        wire:click="processPayment"
                        wire:loading.attr="disabled"
                        class="w-full px-6 py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700
                            text-white font-bold rounded-xl transition-all transform hover:scale-[1.02] active:scale-[0.98]
                            shadow-lg shadow-emerald-500/30 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="processPayment" class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            Procéder au paiement - {{ number_format($planData['price'] ?? 0, 0, ',', ' ') }} {{ $currency }}
                        </span>
                        <span wire:loading wire:target="processPayment" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Traitement en cours...
                        </span>
                    </button>

                    <!-- Security Badge -->
                    <div class="flex items-center justify-center mt-4 text-sm text-slate-400">
                        <svg class="w-4 h-4 mr-2 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Paiement 100% sécurisé
                    </div>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-slate-600"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-slate-800 text-slate-400">ou</span>
                        </div>
                    </div>

                    <!-- Free Plan Option -->
                    <button
                        wire:click="useFreePlan"
                        wire:loading.attr="disabled"
                        class="w-full px-6 py-3 bg-slate-700 hover:bg-slate-600
                            text-slate-300 font-medium rounded-xl transition-all
                            border border-slate-600 hover:border-slate-500">
                        <span wire:loading.remove wire:target="useFreePlan">
                            Continuer avec le plan gratuit
                        </span>
                        <span wire:loading wire:target="useFreePlan">
                            Chargement...
                        </span>
                    </button>
                    
                    <p class="text-center text-xs text-slate-500 mt-3">
                        Le plan gratuit inclut : 1 magasin, 2 utilisateurs, 100 produits
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
