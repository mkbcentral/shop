<div x-data="{ showRemoveModal: false, memberToRemove: null, memberName: '', showModal: false, isEditing: false }"
     @open-invite-modal.window="showModal = true; isEditing = false"
     @close-invite-modal.window="showModal = false">
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
                    @foreach($roleLabels as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Members List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <x-table.table>
            <x-table.head>
                <tr>
                    <x-table.header>Membre</x-table.header>
                    <x-table.header>Rôle</x-table.header>
                    <x-table.header>Ajouté le</x-table.header>
                    <x-table.header>Statut</x-table.header>
                    <x-table.header align="right">Actions</x-table.header>
                </tr>
            </x-table.head>
            <x-table.body>
                @forelse ($members as $member)
                    <x-table.row wire:key="member-{{ $member->id }}">
                        <!-- Member Info -->
                        <x-table.cell>
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
                        </x-table.cell>

                        <!-- Role -->
                        <x-table.cell>
                            @if($member->pivot->role === 'owner')
                                <x-table.badge color="yellow">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    Propriétaire
                                </x-table.badge>
                            @else
                                <x-form.select
                                    wire:change="updateRole({{ $member->id }}, $event.target.value)"
                                    :disabled="$member->pivot->role === 'owner'">
                                    @foreach($roles as $value => $label)
                                        <option value="{{ $value }}" {{ $member->pivot->role === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </x-form.select>
                            @endif
                        </x-table.cell>

                        <!-- Joined Date -->
                        <x-table.cell class="text-sm text-gray-500">
                            {{ $member->pivot->created_at->format('d/m/Y') }}
                        </x-table.cell>

                        <!-- Status -->
                        <x-table.cell>
                            @if($member->pivot->invited_at && !$member->pivot->joined_at)
                                <x-table.badge color="yellow">En attente</x-table.badge>
                            @else
                                <x-table.badge color="green">Actif</x-table.badge>
                            @endif
                        </x-table.cell>

                        <!-- Actions -->
                        <x-table.cell align="right">
                            <x-table.actions>
                                @if($member->pivot->role === 'owner' && auth()->id() === $organization->owner_id)
                                    <button @click="$dispatch('open-transfer-modal')"
                                        class="text-indigo-600 hover:text-indigo-900 transition">
                                        Transférer
                                    </button>
                                @elseif($member->pivot->role !== 'owner')
                                    <button @click="memberToRemove = {{ $member->id }}; memberName = '{{ addslashes($member->name) }}'; showRemoveModal = true"
                                        class="text-red-600 hover:text-red-900 transition">
                                        Retirer
                                    </button>
                                @endif
                            </x-table.actions>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.empty-state
                        colspan="5"
                        title="Aucun membre trouvé"
                        description="Aucun membre dans cette organisation.">
                        <x-slot name="icon">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </x-slot>
                    </x-table.empty-state>
                @endforelse
            </x-table.body>
        </x-table.table>
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
                                    {{ $roleLabels[$invitation->role] ?? ucfirst($invitation->role) }}
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

    <!-- Invite Modal (Alpine) -->
    <x-ui.alpine-modal name="invite" max-width="lg" title="Inviter un membre" icon-bg="from-indigo-500 to-purple-600">
        <x-slot name="icon">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </x-slot>

        <form wire:submit.prevent="invite">
            <x-ui.alpine-modal-body>
                <div class="space-y-4">
                    <!-- Email -->
                    <x-form.form-group label="Adresse email" for="inviteEmail" required>
                        <x-form.input wire:model="inviteEmail" id="inviteEmail" type="email" placeholder="membre@exemple.com" />
                        <x-form.input-error for="inviteEmail" />
                    </x-form.form-group>

                    <!-- Role -->
                    <x-form.form-group label="Rôle" for="inviteRole" required>
                        <x-form.select wire:model="inviteRole" id="inviteRole">
                            @foreach($roles as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
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
            </x-ui.alpine-modal-body>

            <x-ui.alpine-modal-footer submit-text="Envoyer l'invitation" target="invite" />
        </form>
    </x-ui.alpine-modal>

    <!-- Remove Member Confirmation Modal -->
    <x-delete-confirmation-modal
        show="showRemoveModal"
        item-name="memberName"
        item-type="ce membre"
        title="Retirer ce membre"
        wire-target="removeMember"
        on-confirm="$wire.removeMember(memberToRemove); showRemoveModal = false; memberToRemove = null; memberName = ''"
        on-cancel="showRemoveModal = false; memberToRemove = null; memberName = ''" />

    <!-- Transfer Ownership Modal -->
    <div x-data="{ showModal: false }" @open-transfer-modal.window="showModal = true" @close-transfer-modal.window="showModal = false">
        <x-ui.alpine-modal name="transfer" max-width="lg" title="Transférer la propriété" icon-bg="from-indigo-500 to-purple-600">
            <x-slot name="icon">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
            </x-slot>

            <form wire:submit.prevent="transferOwnership">
                <x-ui.alpine-modal-body>
                    <div class="space-y-4">
                        <p class="text-sm text-gray-500">
                            Sélectionnez le membre à qui vous souhaitez transférer la propriété de l'organisation. Vous deviendrez administrateur.
                        </p>

                        <x-form.form-group label="Nouveau propriétaire" for="newOwnerId" required>
                            <x-form.select wire:model="newOwnerId" id="newOwnerId">
                                <option value="">Sélectionnez un membre</option>
                                @foreach($members as $member)
                                    @if($member->pivot->role !== 'owner')
                                        <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->email }})</option>
                                    @endif
                                @endforeach
                            </x-form.select>
                        </x-form.form-group>

                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                            <div class="flex">
                                <svg class="h-5 w-5 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <p class="ml-2 text-sm text-amber-700">
                                    <strong>Attention :</strong> Cette action est irréversible. Le nouveau propriétaire aura tous les droits sur l'organisation.
                                </p>
                            </div>
                        </div>
                    </div>
                </x-ui.alpine-modal-body>

                <x-ui.alpine-modal-footer submit-text="Transférer" target="transferOwnership" />
            </form>
        </x-ui.alpine-modal>
    </div>
</div>
