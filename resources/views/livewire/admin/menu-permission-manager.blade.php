<div x-data="{ expandedSections: {} }">
    <x-slot name="header">
        <x-breadcrumb :items="[['label' => 'Accueil', 'url' => route('dashboard')], ['label' => 'Permissions de Menu']]" />
    </x-slot>

    <div class="flex items-center justify-between mt-4">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Gestion des Permissions de Menu</h1>
            <p class="text-gray-600 mt-2 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Configurez les menus accessibles par rôle
            </p>
        </div>
        @if($selectedRoleId)
            <div class="flex items-center gap-2 bg-gradient-to-r from-indigo-50 to-purple-50 px-4 py-2 rounded-xl border border-indigo-100 shadow-sm">
                <span class="text-sm font-medium text-gray-700">Menus sélectionnés</span>
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-md animate-pulse">
                    {{ count($selectedMenus) }}
                </span>
            </div>
        @endif
    </div>

    <!-- Toast Notifications -->
    <x-toast />

    {{-- Sélection du rôle --}}
    <div class="mb-6 bg-gradient-to-br from-white to-indigo-50 rounded-xl shadow-md border border-indigo-100 p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div>
                <label for="role" class="block text-base font-bold text-gray-900">
                    Sélectionner un rôle
                </label>
                <p class="text-xs text-gray-500">Choisissez le rôle à configurer</p>
            </div>
        </div>
        <div class="relative">
            <select
                wire:model.live="selectedRoleId"
                id="role"
                class="w-full appearance-none px-4 py-3.5 pr-10 border-2 border-indigo-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 font-medium transition-all duration-200 hover:border-indigo-300 cursor-pointer shadow-sm"
            >
                <option value="" class="text-gray-500">-- Choisir un rôle --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" class="text-gray-900">{{ ucfirst($role->name) }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>
    </div>

    @if($selectedRoleId)
        {{-- Actions rapides --}}
        <div class="mb-6 bg-gradient-to-r from-white via-indigo-50 to-white rounded-xl shadow-md border border-indigo-100 p-5 hover:shadow-lg transition-shadow">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex gap-2">
                    <button
                        wire:click="selectAll"
                        type="button"
                        class="group inline-flex items-center px-4 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5"
                    >
                        <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Tout sélectionner
                    </button>
                    <button
                        wire:click="deselectAll"
                        type="button"
                        class="group inline-flex items-center px-4 py-2.5 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 border-2 border-gray-300 hover:border-gray-400 rounded-xl transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5"
                    >
                        <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Tout désélectionner
                    </button>
                </div>
                <button
                    wire:click="save"
                    wire:loading.attr="disabled"
                    type="button"
                    class="group inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 disabled:from-green-400 disabled:to-emerald-400 disabled:cursor-not-allowed text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5"
                >
                    <span wire:loading.remove wire:target="save" class="inline-flex items-center">
                        <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Enregistrer
                    </span>
                    <span wire:loading wire:target="save" class="inline-flex items-center">
                        <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        En cours...
                    </span>
                </button>
            </div>
        </div>

        {{-- Liste des menus par section --}}
        <div class="space-y-3">
            {{-- Menus sans section --}}
            @if(isset($menusBySection[null]) || isset($menusBySection['']))
                @php $noSectionMenus = $menusBySection[null] ?? $menusBySection[''] ?? collect(); @endphp
                @if($noSectionMenus->isNotEmpty())
                    <div class="bg-white border-2 border-indigo-200 rounded-xl shadow-md overflow-hidden hover:shadow-xl hover:border-indigo-300 transition-all">
                        <button 
                            @click="expandedSections['general'] = !expandedSections['general']"
                            type="button"
                            class="w-full bg-gradient-to-r from-indigo-50 via-purple-50 to-indigo-50 px-5 py-4 border-b-2 border-indigo-200 flex items-center justify-between hover:from-indigo-100 hover:via-purple-100 hover:to-indigo-100 transition-all group"
                        >
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform shadow-md">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-gray-900 text-base">
                                    Général
                                    <span class="ml-2 px-2 py-0.5 text-xs font-semibold bg-indigo-100 text-indigo-700 rounded-full">{{ $noSectionMenus->count() }}</span>
                                </h3>
                            </div>
                            <svg class="w-6 h-6 text-indigo-600 transition-transform duration-300" :class="expandedSections['general'] ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="expandedSections['general']" x-collapse class="p-4 space-y-2">
                            @foreach($noSectionMenus as $menu)
                                <div class="flex flex-col gap-2">
                                    {{-- Menu parent avec toggle switch --}}
                                    <label class="flex items-center justify-between cursor-pointer group p-3 rounded-xl hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 transition-all duration-200 border border-transparent hover:border-indigo-100">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br {{ in_array($menu->id, $selectedMenus) ? 'from-indigo-500 to-purple-600' : 'from-gray-200 to-gray-300' }} flex items-center justify-center transition-all duration-300">
                                                <svg class="w-4 h-4 {{ in_array($menu->id, $selectedMenus) ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                                </svg>
                                            </div>
                                            <span class="text-sm font-semibold {{ in_array($menu->id, $selectedMenus) ? 'text-indigo-700' : 'text-gray-700' }} group-hover:text-indigo-600 transition-colors">
                                                {{ $menu->name }}
                                            </span>
                                            @if($menu->children->isNotEmpty())
                                                <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full">{{ $menu->children->count() }} sous-menus</span>
                                            @endif
                                        </div>
                                        {{-- Toggle Switch --}}
                                        <div class="relative">
                                            <input
                                                type="checkbox"
                                                wire:click="toggleMenu({{ $menu->id }})"
                                                @checked(in_array($menu->id, $selectedMenus))
                                                class="sr-only peer"
                                                id="menu-toggle-general-{{ $menu->id }}"
                                            >
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-indigo-500 peer-checked:to-purple-600 shadow-inner"></div>
                                        </div>
                                    </label>

                                    {{-- Sous-menus --}}
                                    @if($menu->children->isNotEmpty())
                                        <div class="ml-6 pl-4 border-l-2 border-indigo-200 space-y-1.5 bg-gradient-to-r from-gray-50/50 to-transparent rounded-r-xl py-2">
                                            @foreach($menu->children as $child)
                                                <label class="flex items-center justify-between cursor-pointer group p-2.5 rounded-lg hover:bg-white hover:shadow-sm transition-all duration-200">
                                                    <div class="flex items-center gap-2.5">
                                                        <div class="w-6 h-6 rounded-md {{ in_array($child->id, $selectedMenus) ? 'bg-indigo-100' : 'bg-gray-100' }} flex items-center justify-center transition-colors">
                                                            <svg class="w-3 h-3 {{ in_array($child->id, $selectedMenus) ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                            </svg>
                                                        </div>
                                                        <span class="text-xs font-medium {{ in_array($child->id, $selectedMenus) ? 'text-indigo-700' : 'text-gray-600' }} group-hover:text-indigo-600 transition-colors">
                                                            {{ $child->name }}
                                                        </span>
                                                    </div>
                                                    {{-- Mini Toggle Switch --}}
                                                    <div class="relative">
                                                        <input
                                                            type="checkbox"
                                                            wire:click="toggleMenu({{ $child->id }})"
                                                            @checked(in_array($child->id, $selectedMenus))
                                                            class="sr-only peer"
                                                            id="menu-toggle-general-child-{{ $child->id }}"
                                                        >
                                                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-indigo-400 peer-checked:to-purple-500 shadow-inner"></div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif

            {{-- Menus avec section --}}
            @foreach($menusBySection as $section => $menus)
                @if($section && $menus->isNotEmpty())
                    <div class="bg-white border-2 border-indigo-200 rounded-xl shadow-md overflow-hidden hover:shadow-xl hover:border-indigo-300 transition-all">
                        <div class="w-full bg-gradient-to-r from-indigo-50 via-purple-50 to-indigo-50 px-5 py-4 border-b-2 border-indigo-200 flex items-center justify-between hover:from-indigo-100 hover:via-purple-100 hover:to-indigo-100 transition-all group">
                            <button
                                @click="expandedSections['{{ $section }}'] = !expandedSections['{{ $section }}']"
                                type="button"
                                class="flex items-center gap-3 flex-1"
                            >
                                <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform shadow-md">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-gray-900 text-base">
                                    {{ $section }}
                                    <span class="ml-2 px-2 py-0.5 text-xs font-semibold bg-indigo-100 text-indigo-700 rounded-full">{{ $menus->count() }}</span>
                                </h3>
                            </button>
                            <div class="flex items-center gap-3">
                                <button
                                    wire:click="toggleSection('{{ $section }}')"
                                    type="button"
                                    class="text-xs px-3 py-1.5 font-semibold text-indigo-700 bg-white hover:bg-indigo-100 border-2 border-indigo-300 hover:border-indigo-400 rounded-lg transition-all shadow-sm hover:shadow-md"
                                >
                                    Basculer
                                </button>
                                <button
                                    @click="expandedSections['{{ $section }}'] = !expandedSections['{{ $section }}']"
                                    type="button"
                                >
                                    <svg class="w-6 h-6 text-indigo-600 transition-transform duration-300" :class="expandedSections['{{ $section }}'] ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div x-show="expandedSections['{{ $section }}']" x-collapse class="p-4 space-y-2">
                            @foreach($menus as $menu)
                                <div class="flex flex-col gap-2">
                                    {{-- Menu parent avec toggle switch --}}
                                    <label class="flex items-center justify-between cursor-pointer group p-3 rounded-xl hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 transition-all duration-200 border border-transparent hover:border-indigo-100">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br {{ in_array($menu->id, $selectedMenus) ? 'from-indigo-500 to-purple-600' : 'from-gray-200 to-gray-300' }} flex items-center justify-center transition-all duration-300">
                                                <svg class="w-4 h-4 {{ in_array($menu->id, $selectedMenus) ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                                </svg>
                                            </div>
                                            <span class="text-sm font-semibold {{ in_array($menu->id, $selectedMenus) ? 'text-indigo-700' : 'text-gray-700' }} group-hover:text-indigo-600 transition-colors">
                                                {{ $menu->name }}
                                            </span>
                                            @if($menu->children->isNotEmpty())
                                                <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full">{{ $menu->children->count() }} sous-menus</span>
                                            @endif
                                        </div>
                                        {{-- Toggle Switch --}}
                                        <div class="relative">
                                            <input
                                                type="checkbox"
                                                wire:click="toggleMenu({{ $menu->id }})"
                                                @checked(in_array($menu->id, $selectedMenus))
                                                class="sr-only peer"
                                                id="menu-toggle-{{ $section }}-{{ $menu->id }}"
                                            >
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-indigo-500 peer-checked:to-purple-600 shadow-inner"></div>
                                        </div>
                                    </label>

                                    {{-- Sous-menus --}}
                                    @if($menu->children->isNotEmpty())
                                        <div class="ml-6 pl-4 border-l-2 border-indigo-200 space-y-1.5 bg-gradient-to-r from-gray-50/50 to-transparent rounded-r-xl py-2">
                                            @foreach($menu->children as $child)
                                                <label class="flex items-center justify-between cursor-pointer group p-2.5 rounded-lg hover:bg-white hover:shadow-sm transition-all duration-200">
                                                    <div class="flex items-center gap-2.5">
                                                        <div class="w-6 h-6 rounded-md {{ in_array($child->id, $selectedMenus) ? 'bg-indigo-100' : 'bg-gray-100' }} flex items-center justify-center transition-colors">
                                                            <svg class="w-3 h-3 {{ in_array($child->id, $selectedMenus) ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                            </svg>
                                                        </div>
                                                        <span class="text-xs font-medium {{ in_array($child->id, $selectedMenus) ? 'text-indigo-700' : 'text-gray-600' }} group-hover:text-indigo-600 transition-colors">
                                                            {{ $child->name }}
                                                        </span>
                                                    </div>
                                                    {{-- Mini Toggle Switch --}}
                                                    <div class="relative">
                                                        <input
                                                            type="checkbox"
                                                            wire:click="toggleMenu({{ $child->id }})"
                                                            @checked(in_array($child->id, $selectedMenus))
                                                            class="sr-only peer"
                                                            id="menu-toggle-{{ $section }}-child-{{ $child->id }}"
                                                        >
                                                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-indigo-400 peer-checked:to-purple-500 shadow-inner"></div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        {{-- Message si aucun rôle sélectionné --}}
        <div class="bg-gradient-to-br from-white via-indigo-50 to-purple-50 rounded-2xl shadow-lg border-2 border-indigo-100 p-16 hover:shadow-xl transition-all">
            <div class="text-center">
                <div class="relative inline-block mb-6">
                    <div class="absolute inset-0 bg-indigo-200 blur-xl opacity-50 rounded-full"></div>
                    <div class="relative w-24 h-24 mx-auto bg-gradient-to-br from-indigo-100 to-purple-100 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-14 h-14 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-3">Sélectionnez un rôle pour commencer</p>
                <p class="text-sm text-gray-600 max-w-md mx-auto">Vous pourrez ensuite définir les menus accessibles pour ce rôle et gérer finement les permissions d'accès.</p>
            </div>
        </div>
    @endif
</div>
