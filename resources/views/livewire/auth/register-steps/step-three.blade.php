<div>
    <div class="space-y-6">
        {{-- Success Message --}}
        <div class="p-4 rounded-xl bg-indigo-500/10 border border-indigo-500/20">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white">Presque terminé !</h3>
                    <p class="text-sm text-slate-300">Vérifiez vos informations avant de continuer</p>
                </div>
            </div>
        </div>

        {{-- User Information Summary --}}
        <div class="p-4 rounded-xl bg-slate-800/50 border border-slate-700">
            <h4 class="text-sm font-semibold text-slate-300 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
                Informations personnelles
            </h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-400">Nom :</span>
                    <span class="text-white font-medium">{{ $userData['name'] ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">E-mail :</span>
                    <span class="text-white font-medium">{{ $userData['email'] ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        {{-- Organization Summary --}}
        <div class="p-4 rounded-xl bg-slate-800/50 border border-slate-700">
            <h4 class="text-sm font-semibold text-slate-300 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                </svg>
                Organisation
            </h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-400">Nom :</span>
                    <span class="text-white font-medium">{{ $organizationData['organization_name'] ?? 'N/A' }}</span>
                </div>
                @if(!empty($businessActivity))
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Type d'activité :</span>
                        <div class="flex items-center gap-2">
                            <span class="text-lg">{{ $businessActivity['icon'] }}</span>
                            <span class="text-white font-medium">{{ $businessActivity['label'] }}</span>
                        </div>
                    </div>
                @endif
                @if(!empty($plan))
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Plan :</span>
                        <div class="flex items-center gap-2">
                            <span class="text-white font-medium">{{ $plan['name'] ?? 'N/A' }}</span>
                            @if(($plan['price'] ?? 0) > 0)
                                <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-600 text-white">
                                    {{ number_format($plan['price'], 0, ',', ' ') }} {{ $currency }}/mois
                                </span>
                            @else
                                <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-600 text-white">
                                    Gratuit
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Features Summary --}}
        @if(!empty($plan) && !empty($plan['features']))
            <div class="p-4 rounded-xl bg-slate-800/50 border border-slate-700">
                <h4 class="text-sm font-semibold text-slate-300 mb-3">Fonctionnalités incluses</h4>
                <ul class="space-y-2 text-sm text-slate-300">
                    @foreach($plan['features'] as $feature)
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-indigo-400 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $feature }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Navigation Buttons --}}
        <div class="flex gap-4">
            <button
                type="button"
                wire:click="previousStep"
                class="flex-1 px-6 py-3 rounded-xl border border-slate-700 text-white
                    hover:border-slate-600 transition-colors"
            >
                Retour
            </button>
            <button
                type="button"
                wire:click="complete"
                wire:loading.attr="disabled"
                class="flex-1 inline-flex justify-center items-center px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600
                    text-white font-semibold hover:from-indigo-500 hover:to-purple-500
                    transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <svg wire:loading wire:target="complete" class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="complete">Créer mon compte</span>
                <span wire:loading wire:target="complete">Création en cours...</span>
                <svg wire:loading.remove wire:target="complete" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </button>
        </div>
    </div>
</div>
