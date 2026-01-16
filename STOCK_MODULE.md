# Module de Gestion du Stock

Ce module fournit une interface complète pour gérer les mouvements de stock de votre système d'inventaire.

## Fonctionnalités

### 1. Tableau de Bord Stock (`/stock/dashboard`)
- Vue d'ensemble des statistiques de stock
- Suivi des entrées et sorties
- Valeur totale des mouvements
- Liste des produits en rupture de stock
- Liste des produits avec stock bas
- Mouvements récents

### 2. Gestion des Mouvements (`/stock`)
- Liste complète des mouvements de stock
- Filtres avancés :
  - Par date (début/fin)
  - Par type (entrée/sortie)
  - Par type de mouvement (achat, vente, ajustement, transfert, retour)
  - Recherche par produit ou référence
- Pagination personnalisable

#### Actions disponibles :
- **Ajouter du Stock** : Enregistrer une entrée de stock
- **Retirer du Stock** : Enregistrer une sortie de stock
- **Ajuster le Stock** : Corriger le stock actuel (inventaire)

### 3. Alertes de Stock (`/stock/alerts`)
- Surveillance des produits en rupture de stock
- Surveillance des produits avec stock bas
- Filtres par type d'alerte
- Statistiques en temps réel

### 4. Historique de Stock (`/stock/history/{variantId}`)
- Historique complet des mouvements d'une variante de produit
- Chronologie détaillée avec :
  - Date et heure
  - Type de mouvement
  - Quantité
  - Raison
  - Utilisateur responsable

## Architecture

### Composants Livewire

#### `App\Livewire\Stock\StockIndex`
Composant principal pour la gestion des mouvements de stock.

**Propriétés :**
- `$search` : Recherche par produit/référence
- `$type` : Filtre par type (in/out)
- `$movementType` : Filtre par type de mouvement
- `$dateFrom` / `$dateTo` : Filtre par période
- `$perPage` : Nombre d'éléments par page

**Méthodes :**
- `openAddModal()` : Ouvre le modal d'ajout de stock
- `openRemoveModal()` : Ouvre le modal de retrait de stock
- `openAdjustModal()` : Ouvre le modal d'ajustement de stock
- `addStock(AddStockAction)` : Ajoute du stock
- `removeStock(RemoveStockAction)` : Retire du stock
- `adjustStock(AdjustStockAction)` : Ajuste le stock

#### `App\Livewire\Stock\StockDashboard`
Tableau de bord avec statistiques et vue d'ensemble.

#### `App\Livewire\Stock\StockAlerts`
Composant pour surveiller les alertes de stock.

#### `App\Livewire\Stock\StockHistory`
Composant pour afficher l'historique d'un produit spécifique.

### Formulaires

#### `App\Livewire\Forms\StockMovementForm`
Formulaire Livewire pour gérer les données des mouvements de stock.

**Champs :**
- `product_variant_id` : ID de la variante de produit
- `quantity` : Quantité
- `movement_type` : Type de mouvement
- `reference` : Référence (optionnelle)
- `reason` : Raison (optionnelle)
- `unit_price` : Prix unitaire (optionnel)
- `date` : Date du mouvement
- `new_quantity` : Nouvelle quantité (pour ajustements)

### Actions

#### `App\Actions\Stock\AddStockAction`
Action pour ajouter du stock (mouvement IN).

**Utilisation :**
```php
$action->execute([
    'product_variant_id' => 1,
    'quantity' => 50,
    'movement_type' => 'purchase',
    'reference' => 'BC-2024-001',
    'unit_price' => 25.99,
    'date' => '2024-01-02',
    'user_id' => auth()->id(),
]);
```

#### `App\Actions\Stock\RemoveStockAction`
Action pour retirer du stock (mouvement OUT).

**Utilisation :**
```php
$action->execute([
    'product_variant_id' => 1,
    'quantity' => 10,
    'movement_type' => 'sale',
    'reason' => 'Vente client',
    'date' => '2024-01-02',
    'user_id' => auth()->id(),
]);
```

#### `App\Actions\Stock\AdjustStockAction`
Action pour ajuster le stock à une quantité précise.

**Utilisation :**
```php
$action->execute([
    'product_variant_id' => 1,
    'new_quantity' => 100,
    'reason' => 'Inventaire physique',
    'user_id' => auth()->id(),
]);
```

### Services

#### `App\Services\StockService`
Service principal pour la logique métier du stock.

**Méthodes principales :**
- `addStock(array $data)` : Ajoute du stock
- `removeStock(array $data)` : Retire du stock
- `adjustStock(int $variantId, int $newQuantity, int $userId, string $reason)` : Ajuste le stock
- `getVariantStock(int $variantId)` : Récupère les informations de stock d'une variante

### Repositories

#### `App\Repositories\StockMovementRepository`
Repository pour les mouvements de stock.

**Méthodes principales :**
- `all()` : Tous les mouvements
- `find(int $id)` : Trouver un mouvement
- `create(array $data)` : Créer un mouvement
- `byProductVariant(int $variantId)` : Mouvements d'une variante
- `byDateRange(string $startDate, string $endDate)` : Mouvements par période
- `entries()` : Entrées de stock
- `exits()` : Sorties de stock
- `statistics(string $startDate, string $endDate)` : Statistiques

#### `App\Repositories\ProductVariantRepository`
Repository pour les variantes de produits (avec méthodes liées au stock).

**Méthodes principales :**
- `all()` : Toutes les variantes
- `find(int $id)` : Trouver une variante
- `inStock()` : Variantes en stock
- `outOfStock()` : Variantes en rupture
- `lowStock(int $threshold)` : Variantes avec stock bas

## Vues Blade

### Composants Principaux
- `resources/views/livewire/stock/index.blade.php` : Page principale
- `resources/views/livewire/stock/dashboard.blade.php` : Tableau de bord
- `resources/views/livewire/stock/alerts.blade.php` : Alertes
- `resources/views/livewire/stock/history.blade.php` : Historique

### Modals
- `resources/views/livewire/stock/modals/add-stock.blade.php` : Modal ajout
- `resources/views/livewire/stock/modals/remove-stock.blade.php` : Modal retrait
- `resources/views/livewire/stock/modals/adjust-stock.blade.php` : Modal ajustement

## Routes

```php
Route::prefix('stock')->name('stock.')->group(function () {
    Route::get('/', StockIndex::class)->name('index');
    Route::get('/dashboard', StockDashboard::class)->name('dashboard');
    Route::get('/alerts', StockAlerts::class)->name('alerts');
    Route::get('/history/{variantId}', StockHistory::class)->name('history');
});
```

## Types de Mouvements

### Types de flux (type)
- `in` : Entrée de stock
- `out` : Sortie de stock

### Types de mouvements (movement_type)
- `purchase` : Achat fournisseur
- `sale` : Vente client
- `adjustment` : Ajustement/Correction
- `transfer` : Transfert entre entrepôts
- `return` : Retour (client ou fournisseur)

## Validation

Toutes les opérations de stock sont validées :
- Quantité requise et > 0
- Vérification du stock disponible pour les retraits
- Raison obligatoire pour les retraits et ajustements
- Traçabilité complète (utilisateur, date, référence)

## Événements et Jobs

### Événements
- `LowStockAlert` : Déclenché quand le stock atteint le seuil
- `OutOfStockAlert` : Déclenché quand le stock est épuisé

### Listeners
- `LogLowStockAlert` : Enregistre les alertes de stock bas
- `LogOutOfStockAlert` : Enregistre les ruptures de stock

### Jobs
- `CheckStockLevels` : Vérifie les niveaux de stock (planifiable)

## Utilisation

### Ajouter du stock via l'interface
1. Accéder à `/stock`
2. Cliquer sur "Ajouter Stock"
3. Remplir le formulaire
4. Soumettre

### Consulter les alertes
1. Accéder à `/stock/alerts`
2. Filtrer par type d'alerte
3. Prendre les actions nécessaires

### Voir l'historique d'un produit
1. Accéder à un produit
2. Cliquer sur "Historique"
3. Ou directement via `/stock/history/{variantId}`

## Composants Réutilisables

Le module utilise des composants Blade réutilisables :
- `x-form.button` : Boutons avec variantes (primary, success, danger, warning)
- `x-form.label` : Labels de formulaire
- `x-form.error` : Messages d'erreur
- `x-form.alert` : Alertes de notification
- `x-table.*` : Composants de tableau
- `x-modal` : Modal réutilisable
- `x-stat-card-gradient` : Cartes de statistiques

## Sécurité

- Toutes les routes sont protégées par le middleware `auth`
- Les opérations enregistrent l'utilisateur responsable
- Traçabilité complète de tous les mouvements
- Validation des quantités et du stock disponible
