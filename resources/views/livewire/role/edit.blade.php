<div>
    <x-slot name="header">
        <x-breadcrumb
            :items="[
                ['label' => 'Accueil', 'url' => route('dashboard')],
                ['label' => 'Rôles', 'url' => route('roles.index')],
                ['label' => 'Modifier Rôle'],
            ]" />
    </x-slot>

    <div class="mt-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Modifier le Rôle</h1>
                <p class="text-gray-500 mt-1">Modifiez le nom, la description et les permissions du rôle</p>
            </div>
            <button wire:click="cancel"
                class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour
            </button>
        </div>

        <!-- Toast Notifications -->
        <x-toast />

        @if ($role->slug === 'super-admin')
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Attention :</strong> Le rôle Super Admin est un rôle système. Le slug ne peut pas être
                            modifié.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <form wire:submit.prevent="update">
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations de base</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom du rôle <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" wire:model.live="name"
                            class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                            Slug <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="slug" wire:model="slug"
                            @if ($role->slug === 'super-admin') readonly @endif
                            class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('slug') border-red-500 @enderror @if ($role->slug === 'super-admin') bg-gray-100 @endif">
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Lettres minuscules, chiffres et tirets uniquement</p>
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="description" wire:model="description" rows="3"
                        class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror"></textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="mt-6">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="is_active"
                            @if ($role->slug === 'super-admin') disabled @endif
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 @if ($role->slug === 'super-admin') opacity-50 cursor-not-allowed @endif">
                        <span class="ml-2 text-sm text-gray-700">Rôle actif</span>
                    </label>
                </div>

                <!-- Role Info -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Utilisateurs assignés :</span>
                        <span class="font-semibold text-gray-900">{{ $role->users()->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Permissions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Permissions</h2>
                    <div class="flex space-x-2">
                        <button type="button" wire:click="selectAllPermissions"
                            class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                            Tout sélectionner
                        </button>
                        <span class="text-gray-300">|</span>
                        <button type="button" wire:click="deselectAllPermissions"
                            class="text-sm text-gray-600 hover:text-gray-700 font-medium">
                            Tout désélectionner
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($permissionCategories as $categoryKey => $category)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition">
                            <!-- Category Header -->
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-2">
                                    <div class="p-2 bg-indigo-100 rounded-lg">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $category['icon'] }}" />
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-gray-900">{{ $category['label'] }}</h3>
                                </div>
                                <button type="button" wire:click="togglePermissionCategory('{{ $categoryKey }}')"
                                    class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">
                                    Tout
                                </button>
                            </div>

                            <!-- Permissions in Category -->
                            <div class="space-y-2">
                                @foreach ($category['permissions'] as $permission)
                                    <label class="flex items-center group cursor-pointer">
                                        <input type="checkbox" wire:model="selectedPermissions"
                                            value="{{ $permission['value'] }}"
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span
                                            class="ml-2 text-sm text-gray-700 group-hover:text-gray-900">{{ $permission['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                @error('selectedPermissions')
                    <p class="mt-4 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <!-- Selected Count -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <span class="font-semibold">{{ count($selectedPermissions) }}</span>
                        permission(s) sélectionnée(s)
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 mt-6">
                <x-form.button variant="secondary" wire:click="cancel">
                    Annuler
                </x-form.button>
                <x-form.button type="submit">
                    Enregistrer les Modifications
                </x-form.button>
            </div>
        </form>
    </div>
</div>
