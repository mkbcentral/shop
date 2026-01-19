<div>
    @if($showModal && $organization)
    <!-- Payment Overlay Modal - Blocks entire page -->
    <div class="fixed inset-0 z-[9999] overflow-y-auto px-4 py-4 sm:px-0" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background overlay with transparency -->
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>

        <!-- Modal content -->
        <div class="flex min-h-full items-center justify-center p-2">
            <div class="relative bg-white rounded-2xl overflow-hidden shadow-2xl transform transition-all w-full max-w-3xl">

                <!-- Header compact -->
                <div class="px-6 py-4 border-b border-gray-200 flex items-center">
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Finalisez votre inscription</h2>
                        <p class="text-gray-500 text-xs">Procédez au paiement pour activer votre organisation</p>
                    </div>
                </div>

                <!-- Body - Two columns layout -->
                <div class="px-6 py-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column: Plan Summary & Features -->
                        <div>
                            <!-- Plan Summary -->
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-sm font-medium text-gray-500">Organisation</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $organization->name }}</span>
                                </div>
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-sm font-medium text-gray-500">Plan choisi</span>
                                    <span class="text-sm font-semibold text-indigo-600">
                                        {{ $planData['name'] ?? $organization->subscription_plan->label() }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                                    <span class="text-sm font-medium text-gray-500">Total</span>
                                    <span class="text-lg font-bold text-emerald-600">
                                        {{ number_format($planData['price'] ?? 0, 0, ',', ' ') }} {{ $currency }}/mois
                                    </span>
                                </div>
                            </div>

                            <!-- Features -->
                            @if(!empty($planData['features']))
                            <div class="mt-4">
                                <h4 class="text-xs font-medium text-gray-500 mb-2 uppercase tracking-wide">Inclus dans votre plan</h4>
                                <div class="grid grid-cols-1 gap-1.5">
                                    @foreach(array_slice($planData['features'] ?? [], 0, 4) as $feature)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-3.5 h-3.5 text-emerald-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ $feature }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Right Column: Payment Methods & Actions -->
                        <div>
                            <!-- Payment Methods -->
                            <h4 class="text-xs font-medium text-gray-500 mb-2 uppercase tracking-wide">Mode de paiement</h4>
                            <div class="flex gap-2 mb-4">
                                <!-- Credit Card Option -->
                                <button
                                    wire:click="$set('paymentMethod', 'card')"
                                    class="flex-1 relative flex items-center p-2.5 border-2 rounded-lg transition-all
                                        {{ $paymentMethod === 'card' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50' }}">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <span class="font-medium text-gray-900 text-xs">Carte Bancaire</span>
                                        <span class="block text-xs text-gray-400">Visa, Mastercard</span>
                                    </div>
                                    @if($paymentMethod === 'card')
                                    <svg class="w-4 h-4 text-indigo-600 absolute top-1.5 right-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    @endif
                                </button>

                                <!-- Mobile Money Option -->
                                <button
                                    wire:click="$set('paymentMethod', 'mobile_money')"
                                    class="flex-1 relative flex items-center p-2.5 border-2 rounded-lg transition-all
                                        {{ $paymentMethod === 'mobile_money' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50' }}">
                                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-2">
                                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <span class="font-medium text-gray-900 text-xs">Mobile Money</span>
                                        <span class="block text-xs text-gray-400">Airtel, Orange, Vodacom</span>
                                    </div>
                                    @if($paymentMethod === 'mobile_money')
                                    <svg class="w-4 h-4 text-indigo-600 absolute top-1.5 right-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    @endif
                                </button>
                            </div>

                            <!-- Payment Button -->
                            <button
                                wire:click="processPayment"
                                wire:loading.attr="disabled"
                                class="w-full px-4 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700
                                    text-white font-bold rounded-lg transition-all transform hover:scale-[1.01] active:scale-[0.99]
                                    shadow-md shadow-emerald-500/25 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="processPayment" class="flex items-center justify-center text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                    Payer {{ number_format($planData['price'] ?? 0, 0, ',', ' ') }} {{ $currency }}
                                </span>
                                <span wire:loading wire:target="processPayment" class="flex items-center justify-center text-sm">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Traitement...
                                </span>
                            </button>

                            <!-- Security Badge -->
                            <div class="flex items-center justify-center mt-2 text-xs text-gray-400">
                                <svg class="w-3.5 h-3.5 mr-1.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Paiement 100% sécurisé
                            </div>

                            <!-- Divider -->
                            <div class="relative my-4">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-200"></div>
                                </div>
                                <div class="relative flex justify-center text-xs">
                                    <span class="px-3 bg-white text-gray-400">ou</span>
                                </div>
                            </div>

                            <!-- Free Plan Option -->
                            <button
                                wire:click="useFreePlan"
                                wire:loading.attr="disabled"
                                class="w-full px-4 py-2.5 bg-gray-50 hover:bg-gray-100
                                    text-gray-600 font-medium text-sm rounded-lg transition-all
                                    border border-gray-200 hover:border-gray-300">
                                <span wire:loading.remove wire:target="useFreePlan">
                                    Continuer avec le plan gratuit
                                </span>
                                <span wire:loading wire:target="useFreePlan">
                                    Chargement...
                                </span>
                            </button>
                            <p class="text-center text-xs text-gray-400 mt-2">
                                1 magasin • 2 utilisateurs • 100 produits
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
