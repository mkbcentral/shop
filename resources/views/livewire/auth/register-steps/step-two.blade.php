<div>
    <form wire:submit.prevent="nextStep" class="space-y-6">
        {{-- Organization Name --}}
        <x-auth.input
            wire:model.live="organization_name"
            type="text"
            name="organization_name"
            label="Nom de votre organisation"
            placeholder="Ma Boutique SARL"
            icon="building"
            :error="$errors->first('organization_name')"
        />

        {{-- Subscription Plans --}}
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-4">
                Choisissez votre plan <span class="text-red-400">*</span>
            </label>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($plans as $slug => $plan)
                    <label class="relative cursor-pointer group" wire:key="plan-{{ $slug }}">
                        <input
                            type="radio"
                            wire:model.live="subscription_plan"
                            value="{{ $slug }}"
                            name="subscription_plan"
                            class="peer sr-only"
                            @if($subscription_plan === $slug) checked @endif
                        >

                        {{-- Card with enhanced selection state --}}
                        <div class="relative p-5 rounded-xl border-2 transition-all duration-300
                            @if($subscription_plan === $slug)
                                border-indigo-500 bg-gradient-to-br from-indigo-500/20 to-purple-500/10 shadow-lg shadow-indigo-500/20 scale-[1.02]
                            @else
                                border-slate-700 bg-slate-800/50 hover:border-indigo-400/50 hover:bg-slate-800/80 hover:shadow-md group-hover:scale-[1.01]
                            @endif">

                            {{-- Selection indicator --}}
                            <div class="absolute top-3 right-3 transition-opacity duration-300 @if($subscription_plan === $slug) opacity-100 @else opacity-0 @endif">
                                <div class="w-6 h-6 rounded-full bg-indigo-500 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-white mb-1 group-hover:text-indigo-300 transition-colors">
                                        {{ $plan['name'] }}
                                    </h3>
                                    @if($plan['price'] == 0)
                                        <div class="flex items-center gap-2">
                                            <p class="text-sm text-emerald-400 font-medium">Gratuit à vie</p>
                                            <span class="px-2 py-0.5 text-xs font-semibold bg-emerald-500/20 text-emerald-300 rounded-full border border-emerald-500/30">
                                                Sans engagement
                                            </span>
                                        </div>
                                    @else
                                        <div class="mt-2">
                                            <span class="text-3xl font-bold text-white">{{ number_format($plan['price'], 0, ',', ' ') }}</span>
                                            <span class="text-sm font-normal text-slate-400 ml-1">{{ $currency }}/mois</span>
                                        </div>
                                    @endif
                                </div>
                                @if($plan['is_popular'] ?? false)
                                    <span class="px-3 py-1 text-xs font-bold bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full shadow-lg">
                                        ⭐ Populaire
                                    </span>
                                @endif
                                @if($slug === 'enterprise')
                                    <span class="px-3 py-1 text-xs font-bold bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-full shadow-lg">
                                        👑 Premium
                                    </span>
                                @endif
                            </div>

                            {{-- Features list --}}
                            <div class="mt-4 pt-4 border-t border-slate-700/50">
                                <ul class="space-y-2 text-sm text-slate-300">
                                    @foreach(array_slice($plan['features'] ?? [], 0, 4) as $feature)
                                        <li class="flex items-start">
                                            <svg class="w-4 h-4 text-indigo-400 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="leading-tight">{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
            @error('subscription_plan')
                <p class="mt-3 text-sm text-red-400 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Navigation Buttons --}}
        <div class="flex gap-4 pt-2">
            <button
                type="button"
                wire:click="previousStep"
                class="flex-1 px-6 py-3.5 rounded-xl border-2 border-slate-700 text-white font-semibold
                    hover:border-slate-600 hover:bg-slate-800/50 transition-all duration-200
                    focus:outline-none focus:ring-2 focus:ring-slate-600"
            >
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Retour
                </span>
            </button>
            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="nextStep"
                class="flex-1 px-6 py-3.5 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600
                    text-white font-bold shadow-lg hover:shadow-xl hover:from-indigo-500 hover:to-purple-500
                    transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed
                    focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-900"
            >
                <span wire:loading.remove wire:target="nextStep" class="flex items-center justify-center gap-2">
                    Continuer
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
                <span wire:loading wire:target="nextStep" class="flex items-center justify-center gap-2">
                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Validation...
                </span>
            </button>
        </div>
    </form>
</div>
