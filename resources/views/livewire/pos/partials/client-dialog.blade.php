<!-- Client Selection Dialog -->
<div x-show="showClientModal"
    x-cloak
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
    @click="showClientModal = false">

    <div @click.stop
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] flex flex-col overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-white/20 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-white">Sélectionner un client</h3>
                    <p class="text-xs text-indigo-100">Optionnel - Laissez vide pour vente comptant</p>
                </div>
            </div>
            <button @click="showClientModal = false" class="p-2 hover:bg-white/20 rounded-lg transition-colors flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Body with scroll -->
        <div class="p-6 space-y-4 overflow-y-auto flex-1">
            <!-- Client sélectionné actuel -->
            @if($this->selectedClient)
                <div class="bg-indigo-50 border-2 border-indigo-200 rounded-lg p-3 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-xs text-indigo-600 font-semibold">Client actuel</p>
                            <p class="text-sm font-bold text-indigo-900">
                                {{ $this->selectedClient->name }}
                            </p>
                        </div>
                    </div>
                    <button wire:click="$set('clientId', null)" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @else
                <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-3 text-center">
                    <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <p class="text-sm font-semibold text-gray-600">Aucun client sélectionné</p>
                    <p class="text-xs text-gray-500">Vente comptant (Walk-in)</p>
                </div>
            @endif

            <!-- Liste des clients -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Choisir un client existant</label>
                <div class="relative">
                    <select wire:model.live="clientId"
                        class="w-full px-4 py-3 text-sm border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all bg-white appearance-none cursor-pointer"
                        style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M5%207l5%205%205-5%22%20stroke%3D%22%23666%22%20stroke-width%3D%222%22%20fill%3D%22none%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 0.75rem center; padding-right: 2.5rem;">
                        <option value="">👤 Vente comptant (Walk-in)</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute left-3 top-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="px-2 bg-white text-gray-500 font-semibold">OU</span>
                </div>
            </div>

            <!-- Formulaire nouveau client (simple) -->
            <div x-data="{ showNewClientForm: false }" @client-created.window="showNewClientForm = false">
                <button @click="showNewClientForm = !showNewClientForm" type="button"
                    class="w-full px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold rounded-xl transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <span x-text="showNewClientForm ? 'Annuler' : 'Créer un nouveau client'"></span>
                </button>

                <!-- Formulaire inline -->
                <form wire:submit.prevent="createClient" x-show="showNewClientForm"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="mt-4 p-4 bg-gray-50 border-2 border-gray-200 rounded-xl space-y-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Nom complet *</label>
                        <input type="text" wire:model="newClientName" placeholder="Ex: Jean Dupont"
                            class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        @error('newClientName')
                            <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Téléphone (optionnel)</label>
                        <input type="tel" wire:model="newClientPhone" placeholder="Ex: +243 XXX XXX XXX"
                            class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        @error('newClientPhone')
                            <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit"
                        class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition-all flex items-center justify-center gap-2"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed">
                        <span wire:loading.remove wire:target="createClient">💾 Enregistrer le client</span>
                        <span wire:loading wire:target="createClient">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Enregistrement...
                        </span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 flex gap-3 flex-shrink-0 border-t border-gray-200">
            <button @click="showClientModal = false"
                class="flex-1 px-4 py-3 bg-white hover:bg-gray-50 text-gray-700 font-bold rounded-xl border-2 border-gray-300 transition-all">
                Fermer
            </button>
            <button @click="showClientModal = false"
                class="flex-1 px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl transition-all shadow-md">
                ✓ Confirmer
            </button>
        </div>
    </div>
</div>
