  <header class="bg-white border-b border-gray-200 shadow-sm">
      <div class="flex items-center justify-between h-16 px-6">
          <button id="openSidebar" class="lg:hidden text-gray-500 hover:text-gray-700">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
          </button>

          <!-- Global Search Bar -->
          <div class="flex-1 px-6 max-w-xl">
              <button onclick="Livewire.dispatch('openSearch')"
                  class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-xl border border-gray-200 hover:border-gray-300 transition-all">
                  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                  <span class="flex-1 text-left">Rechercher produits, clients, ventes...</span>
                  <kbd
                      class="hidden sm:inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-gray-500 bg-white border border-gray-300 rounded-md shadow-sm">
                      <span class="text-xs">Ctrl</span>
                      <span>K</span>
                  </kbd>
              </button>
          </div>

          <!-- Notifications and Store Switcher -->
          <div class="flex items-center space-x-4">
              <!-- Store Switcher ou nom du magasin -->
              @php
                  $user = auth()->user();
                  $canSwitchStore =
                      $user->isAdmin() ||
                      $user->hasAnyRole(['super-admin', 'manager']) ||
                      $user->stores()->where('is_active', true)->count() > 1;
                  $currentStore = $user->currentStore;
              @endphp

              @if ($canSwitchStore)
                  @livewire('store.store-switcher')
              @else
                  {{-- Affichage du nom du magasin pour les utilisateurs affectés à un seul magasin --}}
                  <div
                      class="flex items-center space-x-2 px-3 py-2 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg border border-gray-200">
                      <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                      </svg>
                      <span class="hidden md:inline font-medium text-gray-900">
                          {{ $currentStore?->name ?? 'Aucun magasin' }}
                      </span>
                  </div>
              @endif

              <div class="h-6 w-px bg-gray-300"></div>

              <!-- Admin Notifications (nouvelles organisations - super-admin only) -->
              @if (auth()->user()?->hasRole('super-admin'))
                  @livewire('notifications.admin-notification-bell')
              @endif

              <!-- Sales Notifications (for managers/admins - not super-admin) -->
              @if (auth()->user()?->hasAnyRole(['admin', 'manager']) && !auth()->user()?->hasRole('super-admin'))
                  @livewire('notifications.sales-notification-bell')
              @endif

              <!-- Stock Notifications (not for super-admin and only for orgs with stock management) -->
              @if (!auth()->user()?->hasRole('super-admin') && has_stock_management())
                  @livewire('stock.stock-notifications')
              @endif
          </div>
      </div>
  </header>
