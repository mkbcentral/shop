<div class="min-h-screen flex flex-col bg-gradient-to-br from-gray-50 to-gray-100"
    x-data="cashRegisterAlpine()"
    x-init="$nextTick(() => initStore())"
    x-on:livewire:navigated.window="$nextTick(() => initStore())"
    wire:ignore.self>

    <!-- Toast Notifications Alpine -->
    <x-toast-alpine />

    <!-- Top Bar -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white px-6 py-3 shadow-xl relative overflow-hidden flex-shrink-0">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h1 @click("alert('test')") class="text-xl font-bold tracking-tight">Point de Vente
                        <span class="text-xs bg-green-400/30 px-2 py-0.5 rounded-full ml-2">⚡ Moderne</span>
                    </h1>
                    <p class="text-xs text-indigo-100 flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-white/20 rounded-full text-xs font-semibold">Caisse #{{ auth()->id() }}</span>
                        <span>{{ auth()->user()->name }}</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Statistiques du jour -->
                <button wire:click="toggleStats" @click="showStatsPanel = !showStatsPanel"
                    class="flex items-center gap-2 px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-xl transition-all duration-200 backdrop-blur-sm hover:scale-105"
                    wire:key="stats-button-{{ $todayStats['revenue'] }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <div class="text-left">
                        <div class="text-xs opacity-80">CA Aujourd'hui</div>
                        <div class="text-sm font-bold">@currency($todayStats['revenue'])</div>
                    </div>
                </button>

                <!-- Bouton Factures du jour -->
                <livewire:pos.components.pos-transaction-history />

                <div class="text-right">
                    <div class="text-xs text-indigo-100 font-medium">{{ now()->format('d/m/Y') }}</div>
                    <div class="text-lg font-bold tabular-nums" x-ref="clock">{{ now()->format('H:i:s') }}</div>
                    </div>

                <a href="{{ route('dashboard') }}" wire:navigate
                    class="p-2 bg-white/20 hover:bg-white/30 rounded-xl transition-all duration-200 backdrop-blur-sm hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Panel (Collapsible) -->
    <div x-cloak x-show="showStatsPanel" x-collapse class="bg-white border-b shadow-sm" wire:key="stats-panel-{{ $todayStats['revenue'] }}-{{ $todayStats['sales_count'] }}">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="grid grid-cols-4 gap-4">
                <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl p-4 border border-green-200">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-500 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-green-600 font-medium">Chiffre d'affaires</p>
                            <p class="text-xl font-black text-green-700">{{ number_format($todayStats['revenue'], 0, ',', ' ') }} <span class="text-sm">{{ current_currency() }}</span></p>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl p-4 border border-blue-200">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-500 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-blue-600 font-medium">Ventes</p>
                            <p class="text-xl font-black text-blue-700">{{ $todayStats['sales_count'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-pink-100 rounded-xl p-4 border border-purple-200">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-purple-500 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-purple-600 font-medium">Transactions</p>
                            <p class="text-xl font-black text-purple-700">{{ $todayStats['transactions'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-orange-50 to-amber-100 rounded-xl p-4 border border-orange-200">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-orange-500 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-orange-600 font-medium">Panier moyen</p>
                            <p class="text-xl font-black text-orange-700">
                                @if($todayStats['sales_count'] > 0)
                                    {{ number_format($todayStats['revenue'] / $todayStats['sales_count'], 0, ',', ' ') }} <span class="text-sm">{{ current_currency() }}</span>
                                @else
                                    0 <span class="text-sm">{{ current_currency() }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex overflow-hidden" style="height: calc(100vh - 64px);">
        <!-- Left: Products Grid -->
        <div class="flex-1 flex flex-col overflow-hidden bg-gray-50">
            <div class="p-3 bg-white border-b shadow-sm">
                <!-- Recherche et Filtre catégorie sur la même ligne -->
                <div class="flex items-center gap-2">
                    <!-- Recherche de produits -->
                    <div class="relative flex-1">
                        <input type="text"
                            x-model="searchQuery"
                            @input.debounce.300ms="filterProducts()"
                            placeholder="Rechercher..."
                            class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-200 transition text-sm">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <!-- Select Catégorie -->
                    <div class="relative min-w-[160px]">
                        <select x-model="selectedCategory"
                            @change="filterProducts()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-200 transition appearance-none bg-white pr-8 text-sm text-gray-700">
                            <option value="">Toutes catégories</option>
                            @forelse($categories as $category)
                                <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                            @empty
                                <option value="" disabled>Aucune catégorie</option>
                            @endforelse
                        </select>
                        <svg class="w-4 h-4 absolute right-2.5 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>

                    <!-- Product count -->
                    <span class="text-xs text-gray-500 whitespace-nowrap" x-text="filteredProducts.length + ' produits'"></span>
                </div>
            </div>

            <!-- Grille de produits -->
            <div class="flex-1 overflow-y-auto p-4 custom-scrollbar">
                <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-200 cursor-pointer relative overflow-hidden group"
                            :class="{
                                'ring-2 ring-red-400 bg-red-50': !product.is_service && getTotalStock(product) === 0,
                                'ring-2 ring-orange-400 bg-orange-50': !product.is_service && getTotalStock(product) > 0 && getTotalStock(product) <= (product.stock_alert_threshold || 10),
                                'ring-1 ring-gray-200 hover:ring-2 hover:ring-blue-400': product.is_service || getTotalStock(product) > (product.stock_alert_threshold || 10)
                            }"
                            @click="addProductToCart(product)">

                            <!-- Badge stock en haut (seulement pour produits physiques avec problème de stock) -->
                            <template x-if="!product.is_service && getTotalStock(product) === 0">
                                <div class="absolute top-0 right-0 z-10">
                                    <span class="inline-flex items-center gap-1 text-xs bg-red-500 text-white px-2 py-0.5 rounded-bl-lg font-semibold">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        Rupture
                                    </span>
                                </div>
                            </template>
                            <template x-if="!product.is_service && getTotalStock(product) > 0 && getTotalStock(product) <= (product.stock_alert_threshold || 10)">
                                <div class="absolute top-0 right-0 z-10">
                                    <span class="inline-flex items-center gap-1 text-xs bg-orange-500 text-white px-2 py-0.5 rounded-bl-lg font-semibold">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Faible
                                    </span>
                                </div>
                            </template>

                            <!-- Contenu de la carte -->
                            <div class="p-4">
                                <!-- Badge Service en haut du contenu -->
                                <template x-if="product.is_service">
                                    <span class="inline-flex items-center gap-1 text-[10px] bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full font-semibold mb-2">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Service
                                    </span>
                                </template>

                                <!-- Nom du produit -->
                                <h3 class="font-bold text-gray-800 text-sm leading-snug line-clamp-2 mb-2 group-hover:text-blue-600 transition-colors min-h-[2.5rem]" x-text="product.name"></h3>

                                <!-- Catégorie -->
                                <p class="text-xs text-gray-400 mb-3 truncate" x-text="product.category || 'Sans catégorie'"></p>

                                <!-- Prix -->
                                <div class="flex items-end justify-between">
                                    <span class="text-xl font-bold text-blue-600" x-text="formatPrice(product.price)"></span>

                                    <!-- Stock badge -->
                                    <template x-if="!product.is_service">
                                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold"
                                            :class="{
                                                'bg-red-100 text-red-700': getTotalStock(product) === 0,
                                                'bg-orange-100 text-orange-700': getTotalStock(product) > 0 && getTotalStock(product) <= (product.stock_alert_threshold || 10),
                                                'bg-green-100 text-green-700': getTotalStock(product) > (product.stock_alert_threshold || 10)
                                            }"
                                            x-text="getTotalStock(product) + ' unités'"></span>
                                    </template>
                                </div>

                                <!-- Variantes -->
                                <p class="text-xs text-gray-400 mt-2" x-show="product.variants && product.variants.length > 1">
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        <span x-text="product.variants.length + ' variantes'"></span>
                                    </span>
                                </p>
                            </div>

                            <!-- Hover overlay -->
                            <div class="absolute inset-0 bg-blue-500/5 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                        </div>
                    </template>
                </div>

                <!-- Message si aucun produit -->
                <div x-show="filteredProducts.length === 0" class="flex flex-col items-center justify-center h-full text-gray-400">
                    <svg class="w-12 h-12 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="text-sm font-medium">Aucun produit trouvé</p>
                    <p class="text-xs mt-1 text-gray-300">Modifiez votre recherche ou vérifiez le stock</p>
                </div>
            </div>
        </div>

        <!-- Right: Cart + Payment -->
        <div class="w-[480px] min-w-[480px] bg-white border-l border-gray-200 shadow-lg flex flex-col overflow-hidden"
            style="height: calc(100vh - 64px);"
            wire:ignore
            x-data="{
                storeReady: false,
                checkStore() {
                    this.storeReady = !!(Alpine.store('posCart') && Alpine.store('toast'));
                    if (!this.storeReady) {
                        console.log('[Cart] Stores not ready, retrying...');
                        setTimeout(() => this.checkStore(), 50);
                    } else {
                        console.log('[Cart] Stores ready!');
                    }
                }
            }"
            x-init="$nextTick(() => checkStore())"
            x-on:livewire:navigated.window="$nextTick(() => { storeReady = false; checkStore(); })">

            <!-- Loading state -->
            <div x-show="!storeReady" class="flex items-center justify-center h-full">
                <div class="text-center">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
                    <p class="mt-4 text-sm text-gray-600">Chargement du panier...</p>
                </div>
            </div>

            <!-- Cart + Payment content -->
            <template x-if="storeReady">
                <div class="flex flex-col h-full">
                    <!-- Vue.js POS Components -->
                    @include('livewire.pos.components.partials.pos-cart-vue')
                </div>
            </template>
        </div>
    </div>

    <!-- Keyboard Shortcuts Handler -->
    <div x-data @keydown.window="handleKeyboard($event)"></div>

    <!-- Event Listener pour rafraîchir les stats et produits après une vente -->
    <div x-data="{
        init() {
            window.addEventListener('sale-completed', () => {
                console.log('[POS Alpine] Vente complétée, rafraîchissement des stats...');
                @this.call('refreshStats');
            });

            // Écouter l'événement Livewire pour mettre à jour les produits
            Livewire.on('products-updated', (data) => {
                console.log('[POS Alpine] Mise à jour des produits après vente:', data.products?.length || 0);
                if (data.products) {
                    // Mettre à jour les produits via la méthode updateProducts du composant Alpine
                    const alpineRoot = document.querySelector('[x-data*=\"cashRegisterAlpine\"]');
                    if (alpineRoot && alpineRoot._x_dataStack) {
                        const component = alpineRoot._x_dataStack[0];
                        component.updateProducts(data.products);
                        console.log('[POS Alpine] Stock mis à jour après vente');
                    }
                }
            });
        }
    }"></div>

    <!-- Alpine.js Component Script -->
    <script>
        function cashRegisterAlpine() {
            return {
                showStatsPanel: false,
                searchQuery: '',
                selectedCategory: '',
                filteredProducts: [],
                allProducts: [], // Tous les produits (pour les filtres et mise à jour)
                componentId: null,

                initStore() {
                    console.log('[POS Alpine] Initialisation du store...');

                    // Vérifier que le store est disponible
                    if (!Alpine.store('posCart')) {
                        console.error('[POS Alpine] Store posCart non disponible !');
                        // Réessayer après un court délai
                        setTimeout(() => this.initStore(), 100);
                        return;
                    }

                    // Initialiser le store Alpine avec les données du serveur
                    Alpine.store('posCart').init({
                        selectedClientId: {{ $defaultClientId ?? 'null' }}
                    });

                    // Charger les produits
                    const products = @json($products);
                    console.log('[POS Alpine] Produits chargés:', products.length, products);
                    this.allProducts = products;
                    this.filteredProducts = products;

                    // Stocker l'ID du composant Livewire
                    this.componentId = this.$wire.__instance.id;

                    // Horloge en temps réel
                    this.updateClock();
                    setInterval(() => this.updateClock(), 1000);

                    // Log du panier après initialisation
                    console.log('[POS Alpine] Panier après init:', Alpine.store('posCart').cart);
                    console.log('[POS Alpine] Nombre items panier:', Object.keys(Alpine.store('posCart').cart).length);
                },

                updateProducts(newProducts) {
                    console.log('[POS Alpine] updateProducts appelé avec', newProducts.length, 'produits');
                    this.allProducts = newProducts;
                    // Réappliquer les filtres en cours
                    this.filterProducts();
                },

                updateClock() {
                    const clock = this.$refs.clock;
                    if (clock) {
                        const now = new Date();
                        clock.textContent = now.toLocaleTimeString('fr-FR');
                    }
                },

                filterProducts() {
                    const query = this.searchQuery.toLowerCase().trim();
                    const category = this.selectedCategory;

                    let filtered = this.allProducts;

                    // Filtre par catégorie (par ID comme PosProductGrid)
                    if (category) {
                        const categoryId = parseInt(category);
                        filtered = filtered.filter(product => product.category_id === categoryId);
                    }

                    // Filtre par recherche
                    if (query) {
                        filtered = filtered.filter(product => {
                            return product.name.toLowerCase().includes(query) ||
                                   (product.reference && product.reference.toLowerCase().includes(query)) ||
                                   (product.barcode && product.barcode.toLowerCase().includes(query)) ||
                                   (product.category && product.category.toLowerCase().includes(query));
                        });
                    }

                    this.filteredProducts = filtered;
                },

                addProductToCart(product) {
                    console.log('[POS] Ajout au panier - product:', product);
                    console.log('[POS] Ajout au panier - variant[0]:', product.variants[0]);
                    console.log('[POS] Ajout au panier - variant[0].product:', product.variants[0]?.product);
                    console.log('[POS] Ajout au panier - max_discount_amount:', product.variants[0]?.product?.max_discount_amount);

                    // Vérifier que le produit a des variantes
                    if (!product.variants || product.variants.length === 0) {
                        console.error('[POS] Produit sans variantes:', product);
                        if (Alpine.store('toast')) {
                            Alpine.store('toast').error('Ce produit n\'a pas de variantes disponibles');
                        }
                        return;
                    }

                    // Ajouter au store Vue via Pinia
                    if (window.__VUE_POS_STORE__) {
                        window.__VUE_POS_STORE__.addItem(product.variants[0]);
                    }
                    // Fallback Alpine (au cas où)
                    else if (Alpine.store('posCart')) {
                        Alpine.store('posCart').addItem(product.variants[0]);
                    }
                },

                getTotalStock(product) {
                    return product.variants.reduce((sum, v) => sum + v.stock_quantity, 0);
                },

                formatPrice(price) {
                    const currency = '{{ current_currency() }}';
                    // CDF n'utilise pas de décimales
                    if (currency === 'CDF') {
                        return Math.round(parseFloat(price)).toLocaleString('fr-FR') + ' ' + currency;
                    }
                    return parseFloat(price).toFixed(2).replace('.', ',') + ' ' + currency;
                },

                handleKeyboard(event) {
                    // F2 - Focus recherche
                    if (event.key === 'F2') {
                        event.preventDefault();
                        this.$el.querySelector('input[type="text"]')?.focus();
                    }
                    // F4 - Vider panier
                    if (event.key === 'F4') {
                        event.preventDefault();
                        Alpine.store('posCart').clear();
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c7c7c7;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>

    <!-- QZ Tray Thermal Printer Scripts -->
    @include('livewire.pos.partials.printer-scripts')
</div>
