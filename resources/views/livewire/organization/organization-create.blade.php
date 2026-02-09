<div>
    <x-slot name="header">
        <x-breadcrumb :items="[
            ['label' => 'Accueil', 'url' => route('dashboard')],
            ['label' => 'Organisations', 'url' => route('organizations.index')],
            ['label' => 'Nouvelle']
        ]" />
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600">
                <h1 class="text-xl font-bold text-white">Créer une Organisation</h1>
                <p class="text-indigo-100 text-sm mt-1">Configurez votre nouvelle organisation pour gérer vos magasins</p>
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

                        <!-- Type d'activité -->
                        <x-form.form-group label="Type d'activité" name="form.business_activity" :required="true">
                            <x-form.select wire:model="form.business_activity" id="business_activity">
                                @foreach($businessActivities as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </x-form.select>
                            <x-form.input-error for="form.business_activity" />
                            <p class="text-xs text-gray-500 mt-1">Détermine les types de produits disponibles</p>
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
                        <x-form.form-group label="Raison sociale (si différente)" name="form.legal_name">
                            <x-form.input wire:model="form.legal_name" id="legal_name" type="text" placeholder="Raison sociale complète" />
                            <x-form.input-error for="form.legal_name" />
                        </x-form.form-group>
                    </div>
                </div>

                <!-- Informations légales -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Informations légales (optionnel)
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
                        Branding (optionnel)
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Logo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                            <div class="flex items-center space-x-4">
                                @if($form->logo)
                                    <img src="{{ $form->logo->temporaryUrl() }}" class="w-16 h-16 rounded-lg object-cover">
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
                                        Choisir une image
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
                    <a href="{{ route('organizations.index') }}" wire:navigate>
                        <x-form.button type="button" variant="secondary" size="sm">
                            Annuler
                        </x-form.button>
                    </a>
                    <x-form.button type="submit" size="sm" wire:loading.attr="disabled" wire:target="save">
                        <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="save">Créer l'organisation</span>
                        <span wire:loading wire:target="save">Création...</span>
                    </x-form.button>
                </div>
            </form>
        </div>
    </div>
</div>

