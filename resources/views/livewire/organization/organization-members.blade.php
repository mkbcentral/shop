<div x-data="{ showRemoveModal: false, memberToRemove: null }">
    <x-slot name="header">
        <x-breadcrumb :items="[
            ['label' => 'Accueil', 'url' => route('dashboard')],
            ['label' => 'Organisations', 'url' => route('organizations.index')],
            ['label' => $organization->name, 'url' => route('organizations.show', $organization)],
            ['label' => 'Membres']
        ]" />
    </x-slot>

    <div class="flex items-center justify-between mt-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Membres de l'Organisation</h1>
            <p class="text-gray-500 mt-1">{{ $organization->name }} • {{ $members->count() }} membre(s)</p>
        </div>
        <x-form.button wire:click="openInviteModal" icon="plus">
            Inviter un membre
        </x-form.button>
    </div>

    <!-- Toast -->
    <x-toast />

    <!-- Search and Filters -->
    <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-200 p-4 mt-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Search -->
            <x-form.search-input
                wire:model.live.debounce.300ms="search"
                wireModel="search"
                placeholder="Rechercher un membre..."
            />

            <!-- Role Filter -->
            <div class="flex items-center space-x-2">
                <label for="roleFilter" class="text-sm font-medium text-gray-700 whitespace-nowrap">Rôle :</label>
                <select id="roleFilter" wire:model.live="roleFilter"
                    class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value="">Tous les rôles</option>
                    <option value="owner">Propriétaire</option>
                    <option value="admin">Administrateur</option>
                    <option value="manager">Manager</option>
                    <option value="accountant">Comptable</option>
                    <option value="member">Membre</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Members List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ajouté le</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($members as $member)
                        <tr wire:key="member-{{ $member->id }}" class="hover:bg-gray-50 transition">
                            <!-- Member Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <span class="text-indigo-600 font-medium text-sm">
                                                {{ strtoupper(substr($member->name, 0, 2)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Role -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($member->pivot->role === 'owner')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        Propriétaire
                                    </span>
                                @else
                                    <select wire:change="updateRole({{ $member->id }}, $event.target.value)"
                                        class="text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        @if($member->pivot->role === 'owner') disabled @endif>
                                        <option value="admin" {{ $member->pivot->role === 'admin' ? 'selected' : '' }}>Administrateur</option>
                                        <option value="manager" {{ $member->pivot->role === 'manager' ? 'selected' : '' }}>Manager</option>
                                        <option value="accountant" {{ $member->pivot->role === 'accountant' ? 'selected' : '' }}>Comptable</option>
                                        <option value="member" {{ $member->pivot->role === 'member' ? 'selected' : '' }}>Membre</option>
                                    </select>
                                @endif
                            </td>

                            <!-- Joined Date -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $member->pivot->created_at->format('d/m/Y') }}
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($member->pivot->invited_at && !$member->pivot->joined_at)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        En attente
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Actif
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    @if($member->pivot->role === 'owner' && auth()->id() === $organization->owner_id)
                                        <button @click="showTransferModal = true"
                                            class="text-indigo-600 hover:text-indigo-900 transition">
                                            Transférer
                                        </button>
                                    @elseif($member->pivot->role !== 'owner')
                                        <button @click="memberToRemove = {{ $member->id }}; showRemoveModal = true"
                                            class="text-red-600 hover:text-red-900 transition">
                                            Retirer
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">Aucun membre trouvé</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pending Invitations -->
    @if($pendingInvitations->isNotEmpty())
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Invitations en attente ({{ $pendingInvitations->count() }})</h2>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($pendingInvitations as $invitation)
                    <div wire:key="invitation-{{ $invitation->id }}" class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                        <div>
                            <p class="font-medium text-gray-900">{{ $invitation->email }}</p>
                            <div class="flex items-center space-x-3 mt-1 text-sm text-gray-500">
                                <span class="inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ ucfirst($invitation->role) }}
                                </span>
                                <span>•</span>
                                <span>Invité le {{ $invitation->created_at->format('d/m/Y') }}</span>
                                <span>•</span>
                                <span class="text-orange-600">Expire le {{ $invitation->expires_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button wire:click="resendInvitation({{ $invitation->id }})"
                                class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Renvoyer
                            </button>
                            <button wire:click="cancelInvitation({{ $invitation->id }})"
                                class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium rounded-lg transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Annuler
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Invite Modal -->
    <x-modal name="showInviteModal" maxWidth="lg" :showHeader="false">
        <div class="bg-white rounded-xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Inviter un membre</h3>
                </div>
                <button wire:click="closeInviteModal" type="button" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <form wire:submit="invite">
                <div class="p-6 space-y-4">
                    <!-- Email -->
                    <x-form.form-group label="Adresse email" for="inviteEmail" required>
                        <x-form.input wire:model="inviteEmail" id="inviteEmail" type="email" placeholder="membre@exemple.com" />
                        <x-form.input-error for="inviteEmail" />
                    </x-form.form-group>

                    <!-- Role -->
                    <x-form.form-group label="Rôle" for="inviteRole" required>
                        <x-form.select wire:model="inviteRole" id="inviteRole">
                            <option value="admin">Administrateur</option>
                            <option value="manager">Manager</option>
                            <option value="accountant">Comptable</option>
                            <option value="member">Membre</option>
                        </x-form.select>
                        <x-form.input-error for="inviteRole" />
                    </x-form.form-group>

                    <!-- Role Descriptions -->
                    <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3 text-sm text-indigo-700 space-y-1">
                                <p><strong>Administrateur :</strong> Tous les droits sauf transfert de propriété</p>
                                <p><strong>Manager :</strong> Gestion des magasins et utilisateurs</p>
                                <p><strong>Comptable :</strong> Accès financier et rapports</p>
                                <p><strong>Membre :</strong> Accès de base en lecture</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200">
                    <x-form.button variant="secondary" type="button" wire:click="closeInviteModal">
                        Annuler
                    </x-form.button>
                    <x-form.button type="submit" wire:loading.attr="disabled" wire:target="invite">
                        <span wire:loading.remove wire:target="invite">Envoyer l'invitation</span>
                        <span wire:loading wire:target="invite">Envoi...</span>
                    </x-form.button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Remove Member Confirmation Modal -->
    <div x-show="showRemoveModal"
         x-cloak
         @keydown.escape.window="showRemoveModal = false"
         class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showRemoveModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 @click="showRemoveModal = false">
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="showRemoveModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="bg-white px-6 pt-5 pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Retirer ce membre
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Êtes-vous sûr de vouloir retirer ce membre de l'organisation ? Cette action est irréversible.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3 flex items-center justify-end space-x-3">
                    <button type="button" @click="showRemoveModal = false"
                        class="px-4 py-2 text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 font-medium rounded-lg transition">
                        Annuler
                    </button>
                    <button type="button" @click="$wire.removeMember(memberToRemove); showRemoveModal = false"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition">
                        Retirer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
