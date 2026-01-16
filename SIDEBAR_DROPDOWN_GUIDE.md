# Sidebar Dropdown Component - Guide d'utilisation

## Vue d'ensemble

Le composant `sidebar-dropdown` ajoute des menus déroulants fluides et animés au sidebar de votre application Laravel. Les dropdowns incluent :

- ✅ Animations fluides d'ouverture/fermeture
- ✅ Sauvegarde automatique de l'état (localStorage)
- ✅ Support des badges
- ✅ Icônes personnalisables
- ✅ Détection automatique de l'état actif
- ✅ Design responsive

## Utilisation de base

### Exemple simple

```blade
<x-sidebar-dropdown title="Produits">
    <x-sidebar-item href="{{ route('products.index') }}" :active="request()->routeIs('products.index')">
        Liste des produits
    </x-sidebar-item>
    <x-sidebar-item href="{{ route('products.create') }}" :active="request()->routeIs('products.create')">
        Nouveau produit
    </x-sidebar-item>
</x-sidebar-dropdown>
```

### Avec icône

```blade
<x-sidebar-dropdown 
    title="Produits"
    :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4\'/>'">
    
    <x-sidebar-item href="{{ route('products.index') }}">
        Liste des produits
    </x-sidebar-item>
    <x-sidebar-item href="{{ route('products.create') }}">
        Nouveau produit
    </x-sidebar-item>
    <x-sidebar-item href="{{ route('products.categories') }}">
        Catégories
    </x-sidebar-item>
</x-sidebar-dropdown>
```

### Avec badge

```blade
<x-sidebar-dropdown 
    title="Notifications"
    badge="5"
    badgeColor="red"
    :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9\'/>'">
    
    <x-sidebar-item href="{{ route('notifications.unread') }}" badge="3" badgeColor="red">
        Non lues
    </x-sidebar-item>
    <x-sidebar-item href="{{ route('notifications.all') }}">
        Toutes
    </x-sidebar-item>
</x-sidebar-dropdown>
```

### Ouvert par défaut

```blade
<x-sidebar-dropdown 
    title="Gestion"
    :open="true"
    :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z\'/><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15 12a3 3 0 11-6 0 3 3 0 016 0z\'/>'">
    
    <x-sidebar-item href="{{ route('settings.general') }}">
        Général
    </x-sidebar-item>
    <x-sidebar-item href="{{ route('settings.users') }}">
        Utilisateurs
    </x-sidebar-item>
</x-sidebar-dropdown>
```

## Exemple complet d'un sidebar

```blade
<x-sidebar logo="STK" appName="Mon Application">
    <!-- Dashboard -->
    <x-sidebar-item 
        href="{{ route('dashboard') }}" 
        :active="request()->routeIs('dashboard')"
        :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6\'/>'"
    >
        Tableau de bord
    </x-sidebar-item>

    <!-- Section Gestion -->
    <x-sidebar-section title="Gestion">
        <!-- Produits avec dropdown -->
        <x-sidebar-dropdown 
            title="Produits"
            :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4\'/>'">
            
            <x-sidebar-item href="{{ route('products.index') }}" :active="request()->routeIs('products.*')">
                Liste des produits
            </x-sidebar-item>
            <x-sidebar-item href="{{ route('products.create') }}">
                Nouveau produit
            </x-sidebar-item>
            <x-sidebar-item href="{{ route('categories.index') }}">
                Catégories
            </x-sidebar-item>
        </x-sidebar-dropdown>

        <!-- Ventes avec dropdown -->
        <x-sidebar-dropdown 
            title="Ventes"
            badge="12"
            badgeColor="green"
            :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z\'/>'"
        >
            <x-sidebar-item href="{{ route('sales.index') }}" :active="request()->routeIs('sales.*')">
                Liste des ventes
            </x-sidebar-item>
            <x-sidebar-item href="{{ route('sales.create') }}">
                Nouvelle vente
            </x-sidebar-item>
            <x-sidebar-item href="{{ route('pos.index') }}">
                Point de vente
            </x-sidebar-item>
        </x-sidebar-dropdown>

        <!-- Achats avec dropdown -->
        <x-sidebar-dropdown 
            title="Achats"
            :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z\'/>'">
            
            <x-sidebar-item href="{{ route('purchases.index') }}" :active="request()->routeIs('purchases.*')">
                Liste des achats
            </x-sidebar-item>
            <x-sidebar-item href="{{ route('purchases.create') }}">
                Nouvel achat
            </x-sidebar-item>
            <x-sidebar-item href="{{ route('suppliers.index') }}">
                Fournisseurs
            </x-sidebar-item>
        </x-sidebar-dropdown>
    </x-sidebar-section>

    <!-- Section Rapports -->
    <x-sidebar-section title="Rapports">
        <x-sidebar-dropdown 
            title="Statistiques"
            :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z\'/>'">
            
            <x-sidebar-item href="{{ route('reports.sales') }}">
                Rapport des ventes
            </x-sidebar-item>
            <x-sidebar-item href="{{ route('reports.stock') }}">
                État du stock
            </x-sidebar-item>
            <x-sidebar-item href="{{ route('reports.finance') }}">
                Finances
            </x-sidebar-item>
        </x-sidebar-dropdown>
    </x-sidebar-section>

    <!-- Section Paramètres -->
    <x-sidebar-section title="Système">
        <x-sidebar-dropdown 
            title="Paramètres"
            :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z\'/><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15 12a3 3 0 11-6 0 3 3 0 016 0z\'/>'">
            
            <x-sidebar-item href="{{ route('settings.general') }}">
                Général
            </x-sidebar-item>
            <x-sidebar-item href="{{ route('settings.users') }}">
                Utilisateurs
            </x-sidebar-item>
            <x-sidebar-item href="{{ route('settings.backup') }}">
                Sauvegarde
            </x-sidebar-item>
        </x-sidebar-dropdown>
    </x-sidebar-section>
</x-sidebar>
```

## Props disponibles

### sidebar-dropdown

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `title` | string | **required** | Titre du dropdown |
| `icon` | string | null | SVG path pour l'icône |
| `active` | boolean | false | État actif du dropdown |
| `badge` | string/number | null | Texte du badge |
| `badgeColor` | string | 'indigo' | Couleur du badge (indigo, green, purple, red, amber) |
| `open` | boolean | false | Ouvert par défaut |
| `activePattern` | string | null | Pattern pour détecter l'état actif (ex: 'products/*') |

## Fonctionnalités

### 1. Sauvegarde de l'état
Les dropdowns sauvegardent automatiquement leur état (ouvert/fermé) dans le localStorage du navigateur.

### 2. Animations fluides
- Transition de hauteur avec `max-height`
- Rotation de la flèche à 180°
- Opacity pour un effet de fondu

### 3. Détection de l'état actif
Le dropdown peut automatiquement détecter si l'une de ses sous-pages est active via le prop `activePattern`.

### 4. Responsive
Les dropdowns fonctionnent parfaitement sur mobile avec le sidebar qui se transforme en menu overlay.

## Icônes courantes

Voici quelques icônes SVG courantes à utiliser :

```php
// Dashboard
'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'

// Produits
'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>'

// Ventes
'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>'

// Statistiques
'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>'

// Paramètres
'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>'
```

## Notes de performance

- Les états des dropdowns sont sauvegardés localement pour éviter de faire des requêtes serveur
- Les animations utilisent `transform` et `opacity` pour de meilleures performances
- Le JavaScript utilise la délégation d'événements pour optimiser la gestion des clics

## Compatibilité

- ✅ Laravel 10+
- ✅ Livewire 3+
- ✅ Tailwind CSS 3+
- ✅ Tous les navigateurs modernes
