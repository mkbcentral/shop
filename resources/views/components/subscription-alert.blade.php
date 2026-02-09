@auth
    @php
        $currentOrg = app()->bound('current_organization') ? app('current_organization') : null;
    @endphp

    @if($currentOrg && $currentOrg->subscription_plan->value !== 'free')
        {{-- Abonnement expiré --}}
        @if(!$currentOrg->hasActiveSubscription())
            <div class="bg-red-600 text-white px-4 py-2 shadow-md">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium">
                            <strong>⚠️ Abonnement expiré !</strong>
                            L'abonnement de <strong>{{ $currentOrg->name }}</strong> a expiré{{ $currentOrg->subscription_ends_at ? ' le ' . $currentOrg->subscription_ends_at->format('d/m/Y') : '' }}.
                            Certaines fonctionnalités sont désactivées.
                        </span>
                    </div>
                    <button
                        type="button"
                        x-data
                        @click="$dispatch('open-renewal-modal', { organizationId: {{ $currentOrg->id }} })"
                        class="flex-shrink-0 inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-md bg-white text-red-600 hover:bg-red-50 transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Renouveler
                    </button>
                </div>
            </div>

        {{-- Abonnement expire aujourd'hui --}}
        @elseif($currentOrg->isSubscriptionExpiringToday())
            <div class="bg-orange-500 text-white px-4 py-2 shadow-md">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium">
                            <strong>⏰ Attention !</strong>
                            L'abonnement de <strong>{{ $currentOrg->name }}</strong> expire <strong>AUJOURD'HUI</strong>{{ $currentOrg->subscription_ends_at ? ' à ' . $currentOrg->subscription_ends_at->format('H:i') : '' }}.
                        </span>
                    </div>
                    <button
                        type="button"
                        x-data
                        @click="$dispatch('open-renewal-modal', { organizationId: {{ $currentOrg->id }} })"
                        class="flex-shrink-0 inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-md bg-white text-orange-600 hover:bg-orange-50 transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Prolonger
                    </button>
                </div>
            </div>

        {{-- Abonnement expire bientôt (dans les 3 jours) --}}
        @elseif($currentOrg->isSubscriptionExpiringSoon() && $currentOrg->remaining_days <= 3)
            <div class="bg-yellow-500 text-white px-4 py-2 shadow-md">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium">
                            <strong>📅 Rappel :</strong>
                            L'abonnement de <strong>{{ $currentOrg->name }}</strong> expire dans <strong>{{ $currentOrg->remaining_days }} jour(s)</strong>{{ $currentOrg->subscription_ends_at ? ' (le ' . $currentOrg->subscription_ends_at->format('d/m/Y') . ')' : '' }}.
                        </span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button
                            type="button"
                            x-data
                            @click="$dispatch('open-renewal-modal', { organizationId: {{ $currentOrg->id }} })"
                            class="flex-shrink-0 inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-md bg-white text-yellow-600 hover:bg-yellow-50 transition">
                            Renouveler
                        </button>
                        <button
                            x-data
                            @click="$el.closest('.bg-yellow-500').remove()"
                            class="text-white/80 hover:text-white transition"
                            title="Masquer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @endif
@endauth
