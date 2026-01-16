# Dashboard Store Filtering - Completed

## Vue d'ensemble

Toutes les statistiques du dashboard sont maintenant filtrées automatiquement par magasin pour les utilisateurs avec rôle `cashier` ou `staff`. Les administrateurs continuent de voir les données de tous les magasins.

## Repositories modifiés

### 1. DashboardRepository.php
**Méthodes filtrées (15 au total):**

#### Statistiques de base
- `getTotalProducts()` - Nombre total de produits
- `getTotalSalesCount()` - Nombre total de ventes
- `getTodaySales()` - Ventes du jour
- `getMonthSales()` - Ventes du mois
- `getSalesByDate()` - Ventes par date
- `getSalesBetweenDates()` - Ventes entre deux dates

#### Statistiques de stock
- `getLowStockCount()` - Nombre de produits en stock faible
- `getOutOfStockCount()` - Nombre de produits en rupture
- `getTotalStockValue()` - Valeur totale du stock
- `getLowStockProducts()` - Liste des produits en stock faible
- `getOutOfStockProducts()` - Liste des produits en rupture

#### Données récentes et groupées
- `getRecentSales()` - Ventes récentes
- `getRecentStockMovements()` - Mouvements de stock récents
- `getTopSellingProducts()` - Top des produits les plus vendus
- `getSalesGroupedByDate()` - Ventes groupées par date (pour les graphiques)

### 2. InvoiceRepository.php
**Méthode filtrée:**
- `all()` - Liste de toutes les factures

### 3. PurchaseRepository.php
**Méthode filtrée:**
- `all()` - Liste de tous les achats

### 4. ProductRepository.php (déjà fait précédemment)
**Méthodes filtrées:**
- `all()` - Tous les produits
- `paginate()` - Produits paginés
- `active()` - Produits actifs
- `search()` - Recherche de produits
- `paginateWithFilters()` - Produits paginés avec filtres

### 5. SaleRepository.php (déjà fait précédemment)
**Méthodes filtrées:**
- `all()` - Toutes les ventes
- `paginate()` - Ventes paginées

### 6. StockMovementRepository.php (déjà fait précédemment)
**Méthodes filtrées:**
- `all()` - Tous les mouvements
- `byDateRange()` - Mouvements par période

## Logique de filtrage

Toutes les méthodes utilisent le même pattern :

```php
// Filter by current store if user is not admin
if (!user_can_access_all_stores() && current_store_id()) {
    $query->where('store_id', current_store_id());
}
```

**Pour les relations (ProductVariant -> Product):**
```php
if (!user_can_access_all_stores() && current_store_id()) {
    $query->whereHas('product', function($q) {
        $q->where('store_id', current_store_id());
    });
}
```

## Fonctions helper utilisées

Ces fonctions sont définies dans `app/Helpers/StoreHelper.php` :

1. **`current_store_id()`**
   - Retourne l'ID du magasin actuel depuis la session
   - Utilisé pour filtrer les données

2. **`user_can_access_all_stores()`**
   - Vérifie si l'utilisateur est admin ou manager
   - Les admin/manager voient tous les magasins
   - Les cashier/staff voient uniquement leur magasin assigné

3. **`current_store()`**
   - Retourne l'objet Store complet du magasin actuel

4. **`user_role_in_current_store()`**
   - Retourne le rôle de l'utilisateur dans le magasin actuel

5. **`user_is_cashier_or_staff()`**
   - Vérifie si l'utilisateur est cashier ou staff dans le magasin actuel

## Comportement par rôle

### Administrateur / Manager
- ✅ Voit les données de **tous les magasins**
- ✅ Peut changer de magasin avec le dropdown
- ✅ Voit les statistiques globales ou filtrées selon le magasin sélectionné

### Cashier / Staff
- ✅ Voit uniquement les données de **leur magasin assigné**
- ❌ Ne peut pas voir les données des autres magasins
- ✅ Les statistiques sont automatiquement filtrées
- ✅ Le dropdown de magasin (si visible) ne change que l'affichage du nom

## Test du filtrage

### Test manuel avec le script
```bash
php test-dashboard-filter.php
```

**Résultat attendu:**
```
=== Test du filtrage Dashboard ===

Store ID: 1
Current Store ID: 2
User can access all stores: No

Total Products: 2
Total Sales Count: 0
Today Sales: 0.00
Month Sales: 0.00
Low Stock Count: 0
Out of Stock Count: 0
```

### Test avec utilisateur test
```bash
# Se connecter avec cashier1@stk.com (password: Password123!)
# Vérifier que le dashboard affiche uniquement les données du Magasin 1
```

## État des données

Selon l'audit (`php artisan store:audit`):

| Type | Magasin Principal | Boutique Gombe | Boutique Limete | Sans magasin |
|------|------------------|----------------|-----------------|--------------|
| Products | 1 | 0 | 0 | 6 orphelins |
| Sales | 0 | 0 | 0 | 40 orphelines |
| Stock Movements | 0 | 0 | 0 | 59 orphelins |

### Corriger les données orphelines
```bash
# Voir les corrections qui seraient appliquées
php artisan store:fix-orphans --dry-run

# Appliquer les corrections
php artisan store:fix-orphans
```

## Dashboard Livewire

Si vous utilisez des composants Livewire pour le dashboard, assurez-vous qu'ils appellent ces méthodes du repository. Les filtres seront appliqués automatiquement.

**Exemple de composant Livewire:**
```php
use App\Repositories\DashboardRepository;

class DashboardStats extends Component
{
    protected $dashboardRepo;

    public function boot(DashboardRepository $dashboardRepo)
    {
        $this->dashboardRepo = $dashboardRepo;
    }

    public function render()
    {
        return view('livewire.dashboard-stats', [
            'totalProducts' => $this->dashboardRepo->getTotalProducts(),
            'todaySales' => $this->dashboardRepo->getTodaySales(),
            'monthSales' => $this->dashboardRepo->getMonthSales(now()),
            'lowStockCount' => $this->dashboardRepo->getLowStockCount(),
            // Toutes ces méthodes sont maintenant filtrées automatiquement
        ]);
    }
}
```

## Vérification complète

### ✅ Filtrage implémenté pour:
- [x] Dashboard - Toutes les statistiques
- [x] Produits - Liste et recherche
- [x] Ventes - Liste et détails
- [x] Mouvements de stock - Liste et historique
- [x] Factures - Liste
- [x] Achats - Liste
- [x] Graphiques - Ventes par date

### ⚠️ À vérifier selon votre application:
- [ ] Rapports personnalisés (si existants)
- [ ] Exports CSV/Excel
- [ ] API endpoints (si utilisés par des apps externes)

## Notes importantes

1. **Session requise**: Le filtrage utilise `session('current_store_id')`, assurez-vous que la session est correctement configurée

2. **Middleware actif**: Le middleware `EnsureUserHasStoreAccess` doit être actif pour assigner automatiquement le magasin

3. **Données cohérentes**: Assurez-vous que toutes les tables ont bien une colonne `store_id` avec des valeurs valides

4. **Performance**: Les filtres utilisent des index sur `store_id` - vérifiez que ces index existent en production

## Prochaines étapes recommandées

1. ✅ Tester avec différents rôles (admin, manager, cashier, staff)
2. ✅ Vérifier que tous les graphiques du dashboard se mettent à jour
3. ⏳ Corriger les données orphelines avec `php artisan store:fix-orphans`
4. ⏳ Tester les exports et rapports (si existants)
5. ⏳ Vérifier les performances avec de grosses bases de données

## Dépannage

### Les statistiques ne se filtrent pas
1. Vérifier que `session('current_store_id')` est défini:
   ```php
   dd(session('current_store_id'), current_store_id());
   ```

2. Vérifier le rôle de l'utilisateur:
   ```php
   dd(user_can_access_all_stores(), user_role_in_current_store());
   ```

3. Vérifier que le helper est chargé:
   ```bash
   composer dump-autoload
   ```

### Erreur "Call to undefined function"
- Assurez-vous que `composer.json` inclut le helper:
  ```json
  "autoload": {
      "files": [
          "app/Helpers/StoreHelper.php"
      ]
  }
  ```
- Lancez `composer dump-autoload`

## Conclusion

✅ **Toutes les statistiques du dashboard sont maintenant filtrées par magasin**
✅ **Les utilisateurs cashier/staff voient uniquement les données de leur magasin**
✅ **Les administrateurs conservent l'accès à tous les magasins**
✅ **Le filtrage est automatique et transparent pour les développeurs**
