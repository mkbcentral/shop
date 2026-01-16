<div>
    <x-slot name="header">
        <x-breadcrumb :items="[
            ['label' => 'Accueil', 'url' => route('dashboard')],
            ['label' => 'Organisations', 'url' => route('organizations.index')],
            ['label' => $organization->name, 'url' => route('organizations.show', $organization)],
            ['label' => 'Modifier']
        ]" />
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600">
                <h1 class="text-xl font-bold text-white">Modifier l'Organisation</h1>
                <p class="text-indigo-100 text-sm mt-1">{{ $organization->name }}</p>
            </div>

            <form wire:submit="save" class="p-6 space-y-8">
                <!-- Toast -->
                <x-toast />

                <!-- Informations de base -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Informations de base
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                <!-- Informations légales -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Informations légales
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- NIF / RCCM -->
                        <x-form.form-group label="NIF / RCCM" name="form.tax_id">
                            <x-form.input wire:model="form.tax_id" id="tax_id" type="text" placeholder="Numéro d'identification fiscale" />
                            <x-form.input-error for="form.tax_id" />
                        </x-form.form-group>

                        <!-- Numéro d'immatriculation -->
                        <x-form.form-group label="N° Immatriculation" name="form.registration_number">
                            <x-form.input wire:model="form.registration_number" id="registration_number" type="text" placeholder="Numéro d'immatriculation" />
                            <x-form.input-error for="form.registration_number" />
                        </x-form.form-group>
                    </div>
                </div>

                <!-- Contact -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Contact
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email -->
                        <x-form.form-group label="Email" name="form.email">
                            <x-form.input wire:model="form.email" id="email" type="email" placeholder="contact@organisation.com" />
                            <x-form.input-error for="form.email" />
                        </x-form.form-group>

                        <!-- Téléphone -->
                        <x-form.form-group label="Téléphone" name="form.phone">
                            <x-form.input wire:model="form.phone" id="phone" type="text" placeholder="+243 XXX XXX XXX" />
                            <x-form.input-error for="form.phone" />
                        </x-form.form-group>

                        <!-- Adresse -->
                        <div class="md:col-span-2">
                            <x-form.form-group label="Adresse" name="form.address">
                                <x-form.textarea wire:model="form.address" id="address" rows="2" placeholder="Adresse complète" />
                                <x-form.input-error for="form.address" />
                            </x-form.form-group>
                        </div>

                        <!-- Ville -->
                        <x-form.form-group label="Ville" name="form.city">
                            <x-form.input wire:model="form.city" id="city" type="text" placeholder="Kinshasa" />
                            <x-form.input-error for="form.city" />
                        </x-form.form-group>

                        <!-- Pays -->
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

                <!-- Plan d'abonnement -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                        Plan d'abonnement
                    </h2>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <p class="text-sm text-blue-800">
                            <strong>Plan actuel :</strong> {{ $organization->plan_label }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        @foreach([
                            'free' => ['name' => 'Gratuit', 'stores' => 1, 'users' => 2, 'products' => 100],
                            'starter' => ['name' => 'Starter', 'stores' => 3, 'users' => 5, 'products' => 1000],
                            'professional' => ['name' => 'Professionnel', 'stores' => 10, 'users' => 20, 'products' => 10000],
                            'enterprise' => ['name' => 'Enterprise', 'stores' => '∞', 'users' => '∞', 'products' => '∞']
                        ] as $planKey => $planDetails)
                            <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition
                                {{ $organization->subscription_plan === $planKey ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200 hover:border-indigo-300' }}">
                                <input type="radio" name="subscription_plan" value="{{ $planKey }}"
                                    wire:model="form.subscription_plan"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-base font-semibold text-gray-900">{{ $planDetails['name'] }}</span>
                                        @if($organization->subscription_plan === $planKey)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                Actif
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $planDetails['stores'] }} magasin(s) •
                                        {{ $planDetails['users'] }} utilisateur(s) •
                                        {{ $planDetails['products'] }} produit(s)
                                    </p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Configuration -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Configuration
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Devise -->
                        <x-form.form-group label="Devise par défaut" name="form.currency">
                            <x-form.select wire:model="form.currency" id="currency">
                                @foreach($currencies as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </x-form.select>
                            <x-form.input-error for="form.currency" />
                        </x-form.form-group>

                        <!-- Fuseau horaire -->
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

                <!-- Branding -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Branding
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Logo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                            <div class="flex items-center space-x-4">
                                @if($form->logo)
                                    <img src="{{ $form->logo->temporaryUrl() }}" class="w-16 h-16 rounded-lg object-cover">
                                @elseif($organization->logo)
                                    <img src="{{ Storage::url($organization->logo) }}" class="w-16 h-16 rounded-lg object-cover">
                                @else
                                    <div class="w-16 h-16 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <input type="file" wire:model="form.logo" id="logo" class="hidden" accept="image/*">
                                    <label for="logo" class="cursor-pointer inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                                        Changer l'image
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPG jusqu'à 2Mo</p>
                                </div>
                            </div>
                            <x-form.input-error for="form.logo" />
                        </div>

                        <!-- Site web -->
                        <x-form.form-group label="Site web" name="form.website">
                            <x-form.input wire:model="form.website" id="website" type="url" placeholder="https://www.exemple.com" />
                            <x-form.input-error for="form.website" />
                        </x-form.form-group>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('organizations.show', $organization) }}" wire:navigate>
                        <x-form.button type="button" variant="secondary" size="sm">
                            Annuler
                        </x-form.button>
                    </a>
                    <x-form.button type="submit" size="sm" wire:loading.attr="disabled" wire:target="save">
                        <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="save">Enregistrer les modifications</span>
                        <span wire:loading wire:target="save">Enregistrement...</span>
                    </x-form.button>
                </div>
            </form>
        </div>
    </div>
</div>

