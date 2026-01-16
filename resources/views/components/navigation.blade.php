 <x-sidebar>
     <div class="space-y-1">
         <!-- Dashboard -->
         <x-sidebar-item href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6\'/>'">
             Tableau de bord
         </x-sidebar-item>

         <!-- Inventaire Section -->
         <x-sidebar-section title="Inventaire">
             <x-sidebar-dropdown title="Produits" :badge="$total_products ?? 0" badgeColor="green" activePattern="products/*"
                 :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4\'/>'">

                 <x-sidebar-item href="{{ route('products.index') }}" :active="request()->routeIs('products.index')" :isDropdownItem="true">
                     Liste des produits
                 </x-sidebar-item>

                 <x-sidebar-item href="{{ route('categories.index') }}" :active="request()->routeIs('categories.index')" :isDropdownItem="true">
                     Catégories
                 </x-sidebar-item>

                 <x-sidebar-item href="{{ route('product-types.index') }}" :active="request()->routeIs('product-types.*')" :isDropdownItem="true">
                     Types de produits
                 </x-sidebar-item>
             </x-sidebar-dropdown>

             <x-sidebar-dropdown title="Stock" activePattern="stock/*" :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2\'/>'">

                 <x-sidebar-item href="{{ route('stock.overview') }}" :active="request()->routeIs('stock.overview')" :isDropdownItem="true">
                     État du stock
                 </x-sidebar-item>
                 <x-sidebar-item href="{{ route('stock.index') }}" :active="request()->routeIs('stock.index')" :isDropdownItem="true">
                     Mouvements
                 </x-sidebar-item>
                 <x-sidebar-item href="{{ route('stock.dashboard') }}" :active="request()->routeIs('stock.dashboard')" :isDropdownItem="true">
                     Statistiques
                 </x-sidebar-item>
                 <x-sidebar-item href="{{ route('stock.alerts') }}" :active="request()->routeIs('stock.alerts')" :badge="($low_stock_alerts ?? 0) > 0 ? $low_stock_alerts : null"
                     badgeColor="red" :animate="($low_stock_alerts ?? 0) > 0" :isDropdownItem="true">
                     Alertes Stock
                 </x-sidebar-item>
             </x-sidebar-dropdown>
         </x-sidebar-section>

         <!-- Transactions Section -->
         <x-sidebar-section title="Transactions">
             <x-sidebar-item href="{{ route('pos.cash-register') }}" :active="request()->routeIs('pos.*')" badge="POS" badgeColor="red"
                 :animate="true" :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z\'/>'">
                 Caisse (POS)
             </x-sidebar-item>

             <x-sidebar-dropdown title="Ventes" activePattern="sales/*" :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z\'/>'">

                 <x-sidebar-item href="{{ route('sales.index') }}" :active="request()->routeIs('sales.index')" :isDropdownItem="true">
                     Liste des ventes
                 </x-sidebar-item>
                 <x-sidebar-item href="{{ route('sales.create') }}" :active="request()->routeIs('sales.create')" :isDropdownItem="true">
                     Nouvelle vente
                 </x-sidebar-item>
             </x-sidebar-dropdown>

             <x-sidebar-dropdown title="Achats" activePattern="purchases/*" :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z\'/>'">

                 <x-sidebar-item href="{{ route('purchases.index') }}" :active="request()->routeIs('purchases.index')" :isDropdownItem="true">
                     Liste des achats
                 </x-sidebar-item>
                 <x-sidebar-item href="{{ route('purchases.create') }}" :active="request()->routeIs('purchases.create')" :isDropdownItem="true">
                     Nouvel achat
                 </x-sidebar-item>
             </x-sidebar-dropdown>

             <x-sidebar-dropdown title="Factures" activePattern="invoices/*" :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'/>'">

                 <x-sidebar-item href="{{ route('invoices.index') }}" :active="request()->routeIs('invoices.index')" :isDropdownItem="true">
                     Liste des factures
                 </x-sidebar-item>
                 <x-sidebar-item href="{{ route('invoices.create') }}" :active="request()->routeIs('invoices.create')" :isDropdownItem="true">
                     Nouvelle facture
                 </x-sidebar-item>
             </x-sidebar-dropdown>

             <x-sidebar-dropdown title="Proformas" activePattern="proformas/*" :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'/>'">

                 <x-sidebar-item href="{{ route('proformas.index') }}" :active="request()->routeIs('proformas.index')" :isDropdownItem="true">
                     Liste des proformas
                 </x-sidebar-item>
                 <x-sidebar-item href="{{ route('proformas.create') }}" :active="request()->routeIs('proformas.create')" :isDropdownItem="true">
                     Nouvelle proforma
                 </x-sidebar-item>
             </x-sidebar-dropdown>
         </x-sidebar-section>

         <!-- Contacts Section -->
         <x-sidebar-section title="Contacts">
             <x-sidebar-dropdown title="Clients" :badge="$total_clients ?? 0" badgeColor="green" activePattern="clients/*"
                 :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z\'/>'">

                 <x-sidebar-item href="{{ route('clients.index') }}" :active="request()->routeIs('clients.index')" :isDropdownItem="true">
                     Liste des clients
                 </x-sidebar-item>
             </x-sidebar-dropdown>

             <x-sidebar-dropdown title="Fournisseurs" :badge="$total_suppliers ?? 0" badgeColor="purple" activePattern="suppliers/*"
                 :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4\'/>'">

                 <x-sidebar-item href="{{ route('suppliers.index') }}" :active="request()->routeIs('suppliers.index')" :isDropdownItem="true">
                     Liste des fournisseurs
                 </x-sidebar-item>
             </x-sidebar-dropdown>
         </x-sidebar-section>

         <!-- Multi-Magasins Section -->
         <x-sidebar-section title="Multi-Magasins">
             <x-sidebar-dropdown title="Magasins" activePattern="stores/*" :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4\'/>'">

                 <x-sidebar-item href="{{ route('stores.index') }}" :active="request()->routeIs('stores.index')" :isDropdownItem="true">
                     Liste des magasins
                 </x-sidebar-item>
             </x-sidebar-dropdown>

             <x-sidebar-dropdown title="Transferts" activePattern="transfers/*" :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4\'/>'">

                 <x-sidebar-item href="{{ route('transfers.index') }}" :active="request()->routeIs('transfers.index')" :isDropdownItem="true">
                     Liste des transferts
                 </x-sidebar-item>
             </x-sidebar-dropdown>

             <x-sidebar-dropdown title="Organisations" activePattern="organizations/*" :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4\'/>'">

                 <x-sidebar-item href="{{ route('organizations.index') }}" :active="request()->routeIs('organizations.index')" :isDropdownItem="true">
                     Mes organisations
                 </x-sidebar-item>
                 <x-sidebar-item href="{{ route('organizations.create') }}" :active="request()->routeIs('organizations.create')" :isDropdownItem="true">
                     Créer une organisation
                 </x-sidebar-item>
             </x-sidebar-dropdown>
         </x-sidebar-section>

         <!-- Administration Section -->
         <x-sidebar-section title="Administration">
             <x-sidebar-dropdown title="Utilisateurs" activePattern="users/*" :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z\'/>'">

                 <x-sidebar-item href="{{ route('users.index') }}" :active="request()->routeIs('users.index')" :isDropdownItem="true">
                     Liste des utilisateurs
                 </x-sidebar-item>
             </x-sidebar-dropdown>

             <x-sidebar-dropdown title="Rôles" activePattern="roles/*" :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z\'/>'">

                 <x-sidebar-item href="{{ route('roles.index') }}" :active="request()->routeIs('roles.index')" :isDropdownItem="true">
                     Liste des rôles
                 </x-sidebar-item>
             </x-sidebar-dropdown>

             {{-- Menu Permissions Management --}}
             <x-sidebar-item href="{{ route('menu-permissions.index') }}" :active="request()->routeIs('menu-permissions.*')" :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 6h16M4 12h16M4 18h7\'/>'">
                 Gestion des menus
             </x-sidebar-item>
         </x-sidebar-section>
     </div>
 </x-sidebar>
