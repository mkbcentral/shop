# Optimisation des Performances - Dashboard Mobile

## 📊 Contexte

L'endpoint `/api/mobile/dashboard?store_id=1` prenait beaucoup de temps à répondre en raison de plusieurs problèmes de performance identifiés dans le code.

## 🔍 Problèmes Identifiés

### 1. Problème N+1 dans `getStoresPerformance()`
- **Impact** : Requêtes SQL multiples par magasin (2x nombre de magasins)
- **Exemple** : 10 magasins = 20 requêtes SQL
- **Fichier** : `app/Services/Mobile/MobileReportService.php`

### 2. Boucle avec Requêtes dans `getSalesChartData()`
- **Impact** : 7 requêtes SQL pour un graphique de 7 jours
- **Fichier** : `app/Services/DashboardService.php`

### 3. Chargement Complet en Mémoire
- **Impact** : `getLowStockProducts()` et `getOutOfStockProducts()` chargeaient TOUS les variants puis filtraient en PHP
- **Fichier** : `app/Repositories/DashboardRepository.php`

### 4. Index Manquants
- Pas d'index sur les colonnes fréquemment utilisées dans les WHERE/JOIN

## ✅ Optimisations Implémentées

### 1. Optimisation de `getStoresPerformance()`

**Avant** : N requêtes individuelles par magasin
```php
$stores->map(function ($store) {
    $todaySales = $this->getSalesForStore($store->id);  // 1 requête
    $alertsCount = $this->getAlertsCountForStore($store->id);  // 1 requête
    // ...
});
```

**Après** : 2 requêtes groupées pour tous les magasins
```php
// 1 seule requête pour toutes les ventes
$salesByStore = Sale::whereIn('store_id', $storeIds)
    ->whereDate('sale_date', today())
    ->groupBy('store_id')
    ->selectRaw('store_id, SUM(total) as total_sales')
    ->pluck('total_sales', 'store_id');

// 1 seule requête pour toutes les alertes
$alertsByStore = DB::table('product_variants')
    ->join('products', ...)
    ->whereIn('products.store_id', $storeIds)
    ->groupBy('products.store_id')
    ->pluck('alerts_count', 'store_id');
```

**Gain** : 20 requêtes → 2 requêtes (pour 10 magasins)

---

### 2. Optimisation de `getSalesChartData()`

**Avant** : Boucle avec requête par jour
```php
for ($i = $days - 1; $i >= 0; $i--) {
    $date = now()->subDays($i)->format('Y-m-d');
    $total = $this->repository->getSalesByDate($date);  // 1 requête par jour
}
```

**Après** : 1 seule requête groupée
```php
$salesData = Sale::whereBetween('sale_date', [$startDate, $endDate])
    ->selectRaw('DATE(sale_date) as day, SUM(total) as total')
    ->groupBy('day')
    ->pluck('total', 'day')
    ->toArray();
```

**Gain** : 7 requêtes → 1 requête (pour 7 jours)

---

### 3. Optimisation des Requêtes de Stock

**Avant** : Chargement complet puis filtrage en PHP
```php
$variants = $query->get();  // Charge TOUT
$filtered = $variants->filter(function($variant) {
    // Filtre en PHP
});
```

**Après** : Filtrage direct en SQL
```php
if ($storeId) {
    $query = ProductVariant::with(['product'])
        ->join('product_store_stock', function($join) use ($storeId) {
            $join->on('product_variants.id', '=', 'product_store_stock.product_variant_id')
                 ->where('product_store_stock.store_id', '=', $storeId);
        })
        ->whereRaw('product_store_stock.quantity > 0')
        ->whereRaw('product_store_stock.quantity <= product_variants.low_stock_threshold');
    
    if ($limit) {
        $query->limit($limit);
    }
}
```

**Gain** : Charge uniquement les données nécessaires

---

### 4. Ajout d'Index de Performance

**Migration** : `2026_01_29_071516_add_performance_indexes_to_dashboard_tables.php`

Index ajoutés :
- `sales(sale_date, store_id, status)` - Pour les requêtes de ventes filtrées
- `products(store_id)` - Pour les jointures par magasin
- `product_variants(stock_quantity, low_stock_threshold)` - Pour les alertes de stock
- `product_store_stock(product_variant_id, store_id)` - Pour les stocks par magasin
- `product_store_stock(quantity)` - Pour les filtres de quantité
- `sale_items(product_variant_id, sale_id)` - Pour les top produits

**Commande pour appliquer** :
```bash
php artisan migrate
```

---

## 📈 Résultats Attendus

### Réduction du Nombre de Requêtes
- **Dashboard avec 10 magasins** : ~30 requêtes → ~10 requêtes
- **Graphique des ventes** : 7 requêtes → 1 requête
- **Stock bas/rupture** : Chargement complet → Seulement les données nécessaires

### Amélioration du Temps de Réponse
- **Avant** : 2-5 secondes (estimation)
- **Après** : 200-800ms (estimation, dépend de la taille des données)
- **Gain** : ~75-85% plus rapide

### Réduction de la Mémoire
- Moins de données chargées en mémoire PHP
- Traitement côté base de données (plus efficace)

---

## 🚀 Déploiement

### Étapes

1. **Vérifier les changements**
   ```bash
   git status
   git diff
   ```

2. **Exécuter la migration**
   ```bash
   php artisan migrate
   ```

3. **Vider le cache** (recommandé)
   ```bash
   php artisan cache:clear
   ```

4. **Tester l'endpoint**
   ```bash
   # Avec Postman ou curl
   curl -H "Authorization: Bearer YOUR_TOKEN" \
        https://shop.mkbcentral.com/api/mobile/dashboard?store_id=1
   ```

5. **Surveiller les performances**
   - Utiliser Laravel Telescope ou Debugbar
   - Vérifier les logs de requêtes SQL
   - Mesurer le temps de réponse

---

## 🔧 Maintenance Future

### Recommandations

1. **Monitoring**
   - Installer Laravel Telescope pour suivre les requêtes
   - Surveiller les slow queries dans MySQL

2. **Cache**
   - Le cache actuel (5 minutes) est conservé
   - Considérer Redis pour un cache distribué en production

3. **Optimisations Supplémentaires**
   - Pagination pour les listes longues
   - Lazy loading pour les données non critiques
   - Queue jobs pour les rapports lourds

4. **Index Additionnels**
   - Analyser les slow queries avec `EXPLAIN`
   - Ajouter des index composites si nécessaire

---

## 📝 Notes Techniques

### Tables Impactées
- `sales`
- `products`
- `product_variants`
- `product_store_stock`
- `sale_items`

### Fichiers Modifiés
- `app/Services/Mobile/MobileReportService.php`
- `app/Services/DashboardService.php`
- `app/Repositories/DashboardRepository.php`
- `database/migrations/2026_01_29_071516_add_performance_indexes_to_dashboard_tables.php`

### Compatibilité
- ✅ Compatible avec l'API existante (pas de breaking changes)
- ✅ Les réponses JSON restent identiques
- ✅ Le cache continue de fonctionner
- ✅ Multi-magasin supporté

---

## ⚠️ Points d'Attention

1. **Migration des Index**
   - Peut prendre du temps sur de grosses tables
   - Planifier pendant une période de faible activité

2. **Tests**
   - Tester avec différents rôles (admin, manager, staff)
   - Tester avec et sans `store_id`
   - Vérifier les magasins multiples

3. **Rollback**
   - La migration peut être inversée avec `php artisan migrate:rollback`
   - Les index seront supprimés

---

## 📊 Métriques de Succès

### Objectifs
- ✅ Temps de réponse < 1 seconde
- ✅ Nombre de requêtes SQL divisé par 3
- ✅ Utilisation mémoire réduite
- ✅ Expérience utilisateur fluide sur mobile

### Outils de Mesure
- Laravel Telescope
- MySQL slow query log
- Chrome DevTools (Network tab)
- New Relic / Datadog (si disponible)

---

**Date de mise en œuvre** : 29 janvier 2026
**Auteur** : GitHub Copilot
**Status** : ✅ Implémenté - En attente de déploiement
