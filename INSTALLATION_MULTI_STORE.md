# 🚀 Installation du Module Multi-Magasins

## Étapes d'installation

### 1. Exécuter les migrations

```bash
php artisan migrate
```

Cette commande va créer:
- ✅ Table `stores` (magasins)
- ✅ Table `store_user` (pivot utilisateurs-magasins)
- ✅ Table `store_stock` (stock par magasin)
- ✅ Table `store_transfers` (transferts)
- ✅ Table `store_transfer_items` (lignes de transfert)
- ✅ Colonne `store_id` dans les tables existantes
- ✅ Colonne `current_store_id` dans la table `users`

### 2. Exécuter les seeders dans l'ordre

```bash
# 1. Créer les magasins (Principal, Gombe, Limete)
php artisan db:seed --class=StoreSeeder

# 2. Migrer les stocks existants vers le magasin principal
php artisan db:seed --class=StoreStockSeeder

# 3. Migrer toutes les données vers le magasin principal
php artisan db:seed --class=MigrateDataToMainStoreSeeder
```

### 3. (Optionnel) Tout en une fois

```bash
php artisan migrate --seed
```

Puis exécuter les seeders individuellement si nécessaire.

---

## 🎯 Vérification

Après installation, vérifier:

```bash
# Vérifier les magasins créés
php artisan tinker
>>> App\Models\Store::all();

# Vérifier les stocks migrés
>>> App\Models\StoreStock::count();

# Vérifier les utilisateurs assignés
>>> App\Models\User::first()->stores;
```

---

## 🧪 Test rapide

```bash
php artisan tinker
```

```php
use App\Services\StoreService;
use App\Services\StoreTransferService;

$storeService = app(StoreService::class);

// Lister les magasins
$stores = $storeService->getAllStores();
$stores->pluck('name');

// Créer un nouveau magasin
$store = $storeService->createStore([
    'name' => 'Boutique Test',
    'address' => 'Adresse test',
    'is_active' => true,
]);

// Créer un transfert
$transferService = app(StoreTransferService::class);
$transfer = $transferService->createTransfer([
    'from_store_id' => 1,
    'to_store_id' => 2,
    'items' => [
        ['product_variant_id' => 1, 'quantity' => 10],
    ],
    'requested_by' => 1,
]);
```

---

## ⚠️ Notes Importantes

### Rétrocompatibilité

Le système est conçu avec une **migration douce**:
- Toutes les données existantes sont assignées au **Magasin Principal**
- Les `store_id` NULL sont automatiquement remplacés
- Les utilisateurs existants sont assignés au magasin principal
- Aucune perte de données

### Middleware

Le middleware `EnsureUserHasStoreAccess` est optionnel mais recommandé:
- Assigne automatiquement un magasin aux utilisateurs sans magasin
- Vérifie l'accès au magasin actuel
- Corrige automatiquement les incohérences

Pour l'activer globalement, ajouter dans `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->append(\App\Http\Middleware\EnsureUserHasStoreAccess::class);
})
```

---

## 📊 Après Installation

### Données créées:
- 🏪 **3 magasins** (Principal, Gombe, Limete)
- 📦 **Stock migré** vers le magasin principal
- 👥 **Utilisateurs assignés** au magasin principal
- 🔄 **Produits, ventes, achats** liés au magasin principal

### Prochaines étapes:
1. ✅ Backend complet - TERMINÉ
2. ⏳ Créer les composants Livewire (Phase 2)
3. ⏳ Créer les vues Blade (Phase 3)
4. ⏳ Tests unitaires (Phase 4)

---

## 🐛 En cas de problème

### Réinitialiser les migrations

```bash
php artisan migrate:fresh --seed
```

**⚠️ ATTENTION:** Cela supprimera toutes les données !

### Rollback uniquement le module stores

```bash
php artisan migrate:rollback --step=6
```

Cela annulera les 6 migrations du module multi-magasins.

---

## ✅ Checklist

- [ ] Migrations exécutées avec succès
- [ ] Seeders exécutés (3 seeders)
- [ ] Magasins créés (vérifier avec `Store::count()`)
- [ ] Stock migré (vérifier avec `StoreStock::count()`)
- [ ] Utilisateurs assignés (vérifier avec `User::first()->stores`)
- [ ] Données migrées (vérifier `store_id` dans `products`, `sales`, etc.)

---

**Installation estimée:** 2-3 minutes  
**État:** Prêt pour la production (Backend)
