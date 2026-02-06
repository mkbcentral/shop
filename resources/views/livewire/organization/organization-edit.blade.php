<div>
    <x-slot name="header">
        <x-breadcrumb :items="[
            ['label' => 'Accueil', 'url' => route('dashboard')],
            ['label' => 'Organisations', 'url' => route('organizations.index')],
            ['label' => $organization->name, 'url' => route('organizations.show', $organization)],
            ['label' => 'Modifier']
        ]" />
    </x-slot>

    <!-- Toast -->
    <x-toast />

    <div class="max-w-7xl mx-auto mt-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Modifier l'Organisation</h1>
                    <p class="text-gray-500 mt-1">{{ $organization->name }}</p>
                </div>
                <a href="{{ route('organizations.show', $organization) }}" wire:navigate
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour
                </a>
            </div>
        </div>

        <form wire:submit="save">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Colonne gauche: Informations principales -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Informations de base -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-base font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Informations de base
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Nom -->
                                <div class="md:col-span-2">
                                    <x-form.form-group label="Nom de l'organisation" name="form.name" :required="true">
                                        <x-form.input wire:model="form.name" id="name" type="text" placeholder="Ex: Ma Société SARL" />
                                        <x-form.input-error for="form.name" />
                                    </x-form.form-group>
                                </div>

                                <!-- Type -->
                                <x-form.form-group label="Type d'organisation" name="form.type" :required="true">
                                    <x-form.select wire:model="form.type" id="type">
                                        @foreach($types as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </x-form.select>
                                    <x-form.input-error for="form.type" />
                                </x-form.form-group>

                                <!-- Forme juridique -->
                                <x-form.form-group label="Forme juridique" name="form.legal_form">
                                    <x-form.select wire:model="form.legal_form" id="legal_form">
                                        @foreach($legalForms as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </x-form.select>
                                    <x-form.input-error for="form.legal_form" />
                                </x-form.form-group>

                                <!-- Raison sociale -->
                                <div class="md:col-span-2">
                                    <x-form.form-group label="Raison sociale (si différente)" name="form.legal_name">
                                        <x-form.input wire:model="form.legal_name" id="legal_name" type="text" placeholder="Raison sociale complète" />
                                        <x-form.input-error for="form.legal_name" />
                                    </x-form.form-group>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informations légales & Configuration -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Informations légales -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h2 class="text-base font-semibold text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Informations légales
                                </h2>
                            </div>
                            <div class="p-6 space-y-4">
                                <x-form.form-group label="NIF / RCCM" name="form.tax_id">
                                    <x-form.input wire:model="form.tax_id" id="tax_id" type="text" placeholder="Numéro fiscal" />
                                    <x-form.input-error for="form.tax_id" />
                                </x-form.form-group>

                                <x-form.form-group label="N° Immatriculation" name="form.registration_number">
                                    <x-form.input wire:model="form.registration_number" id="registration_number" type="text" placeholder="N° immatriculation" />
                                    <x-form.input-error for="form.registration_number" />
                                </x-form.form-group>
                            </div>
                        </div>

                        <!-- Configuration -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h2 class="text-base font-semibold text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Configuration
                                </h2>
                            </div>
                            <div class="p-6 space-y-4">
                                <x-form.form-group label="Devise par défaut" name="form.currency">
                                    <x-form.select wire:model="form.currency" id="currency">
                                        @foreach($currencies as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </x-form.select>
                                    <x-form.input-error for="form.currency" />
                                </x-form.form-group>

                                <x-form.form-group label="Fuseau horaire" name="form.timezone">
                                    <x-form.select wire:model="form.timezone" id="timezone">
                                        @foreach($timezones as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </x-form.select>
                                    <x-form.input-error for="form.timezone" />
                                </x-form.form-group>
                            </div>
                        </div>
                    </div>

                    <!-- Contact -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-base font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Contact & Localisation
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <x-form.form-group label="Email" name="form.email">
                                    <x-form.input wire:model="form.email" id="email" type="email" placeholder="contact@organisation.com" />
                                    <x-form.input-error for="form.email" />
                                </x-form.form-group>

                                <x-form.form-group label="Téléphone" name="form.phone">
                                    <x-form.input wire:model="form.phone" id="phone" type="text" placeholder="+243 XXX XXX XXX" />
                                    <x-form.input-error for="form.phone" />
                                </x-form.form-group>

                                <div class="md:col-span-2">
                                    <x-form.form-group label="Adresse" name="form.address">
                                        <x-form.textarea wire:model="form.address" id="address" rows="2" placeholder="Adresse complète" />
                                        <x-form.input-error for="form.address" />
                                    </x-form.form-group>
                                </div>

                                <x-form.form-group label="Ville" name="form.city">
                                    <x-form.input wire:model="form.city" id="city" type="text" placeholder="Kinshasa" />
                                    <x-form.input-error for="form.city" />
                                </x-form.form-group>

                                <x-form.form-group label="Pays" name="form.country">
                                    <x-form.select wire:model="form.country" id="country">
                                        <option value="CD">République Démocratique du Congo</option>
                                        <option value="CG">République du Congo</option>
                                        <option value="RW">Rwanda</option>
                                        <option value="BI">Burundi</option>
                                        <option value="UG">Ouganda</option>
                                    </x-form.select>
                                    <x-form.input-error for="form.country" />
                                </x-form.form-group>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colonne droite: Plan & Branding -->
                <div class="space-y-6">

                    <!-- Plan d'abonnement -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-500 to-purple-500">
                            <h2 class="text-base font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                                Plan d'abonnement
                            </h2>
                        </div>
                        <div class="p-4">
                            <!-- Plan actuel -->
                            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-3 mb-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-indigo-600 font-medium">Plan actuel</p>
                                        <p class="text-lg font-bold text-indigo-900">{{ $organization->plan_label }}</p>
                                    </div>
                                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                @if($organization->subscription_ends_at)
                                    <p class="text-xs text-indigo-500 mt-2">
                                        Expire le {{ $organization->subscription_ends_at->format('d/m/Y') }}
                                    </p>
                                @endif
                            </div>

                            @php
                                $currentPlanOrder = ['free' => 0, 'starter' => 1, 'professional' => 2, 'enterprise' => 3];
                                $currentOrder = $currentPlanOrder[$organization->subscription_plan->value] ?? 0;
                            @endphp

                            <!-- Liste des plans -->
                            <div class="space-y-2">
                                @foreach($subscriptionPlans as $plan)
                                    @php
                                        $planOrder = $currentPlanOrder[$plan->slug] ?? 0;
                                        $isCurrentPlan = $organization->subscription_plan->value === $plan->slug;
                                        $isUpgrade = $planOrder > $currentOrder;
                                        $isDowngrade = $planOrder < $currentOrder;
                                    @endphp

                                    <div class="relative p-3 border rounded-lg transition
                                        {{ $isCurrentPlan ? 'border-indigo-500 bg-indigo-50/50' : ($isUpgrade ? 'border-gray-200 hover:border-purple-300 cursor-pointer' : 'border-gray-100 opacity-50') }}">

                                        <div class="flex items-center justify-between">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm font-semibold text-gray-900 truncate">{{ $plan->name }}</span>
                                                    @if($isCurrentPlan)
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-indigo-100 text-indigo-700">
                                                            Actif
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-gray-500 mt-0.5">
                                                    {{ $plan->max_stores === -1 ? '∞' : $plan->max_stores }} mag. •
                                                    {{ $plan->max_users === -1 ? '∞' : $plan->max_users }} util. •
                                                    {{ $plan->max_products === -1 ? '∞' : $plan->max_products }} prod.
                                                </p>
                                            </div>
                                            <div class="flex-shrink-0 ml-2">
                                                @if($isCurrentPlan)
                                                    <span class="text-xs font-bold text-indigo-600">
                                                        {{ $plan->price > 0 ? number_format($plan->price, 0, ',', ' ') . ' CDF' : 'Gratuit' }}
                                                    </span>
                                                @elseif($isUpgrade)
                                                    <button
                                                        type="button"
                                                        x-data
                                                        @click="$dispatch('open-upgrade-modal', { organizationId: {{ $organization->id }}, targetPlan: '{{ $plan->slug }}' })"
                                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-600 text-white hover:bg-purple-700 transition"
                                                    >
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                                        </svg>
                                                        {{ number_format($plan->price, 0, ',', ' ') }} CDF
                                                    </button>
                                                @else
                                                    <span class="text-xs text-gray-400">
                                                        {{ $plan->price > 0 ? number_format($plan->price, 0, ',', ' ') . ' CDF' : 'Gratuit' }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <p class="text-[10px] text-gray-400 mt-3 text-center">
                                Cliquez sur un plan supérieur pour procéder au paiement
                            </p>
                        </div>
                    </div>

                    <!-- Branding -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-base font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Branding
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <!-- Logo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                                <div class="flex items-center space-x-4">
                                    <div class="relative">
                                        @if($organization->logo)
                                            <img src="{{ Storage::url($organization->logo) }}" class="w-14 h-14 rounded-lg object-cover border">
                                        @else
                                            <div class="w-14 h-14 rounded-lg bg-gray-100 flex items-center justify-center border border-dashed border-gray-300">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <!-- Loading overlay -->
                                        <div wire:loading wire:target="form.logo" class="absolute inset-0 bg-white/80 rounded-lg flex items-center justify-center">
                                            <svg class="animate-spin h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" wire:model="form.logo" id="logo" class="hidden" accept="image/*">
                                        <label for="logo" class="cursor-pointer inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-lg transition">
                                            <span wire:loading.remove wire:target="form.logo">Changer</span>
                                            <span wire:loading wire:target="form.logo" class="inline-flex items-center">
                                                <svg class="animate-spin -ml-1 mr-1.5 h-3 w-3" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Chargement...
                                            </span>
                                        </label>
                                        @if($organization->logo)
                                            <button type="button" wire:click="removeLogo" wire:loading.attr="disabled" wire:target="removeLogo"
                                                class="ml-2 inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-medium rounded-lg transition">
                                                <span wire:loading.remove wire:target="removeLogo">Supprimer</span>
                                                <span wire:loading wire:target="removeLogo">Suppression...</span>
                                            </button>
                                        @endif
                                        <p class="text-[10px] text-gray-400 mt-1">PNG, JPG max 2Mo</p>
                                    </div>
                                </div>
                                <x-form.input-error for="form.logo" />
                            </div>

                            <!-- Site web -->
                            <x-form.form-group label="Site web" name="form.website">
                                <x-form.input wire:model="form.website" id="website" type="url" placeholder="https://..." />
                                <x-form.input-error for="form.website" />
                            </x-form.form-group>
                        </div>
                    </div>

                    <!-- Actions (sticky) -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 lg:sticky lg:top-4">
                        <div class="space-y-3">
                            <x-form.button type="submit" class="w-full justify-center" size="sm" wire:loading.attr="disabled" wire:target="save">
                                <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <svg wire:loading.remove wire:target="save" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span wire:loading.remove wire:target="save">Enregistrer</span>
                                <span wire:loading wire:target="save">Enregistrement...</span>
                            </x-form.button>

                            <a href="{{ route('organizations.show', $organization) }}" wire:navigate class="block">
                                <x-form.button type="button" variant="secondary" size="sm" class="w-full justify-center">
                                    Annuler
                                </x-form.button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

