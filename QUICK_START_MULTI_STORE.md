# Guide Rapide - Filtrage Multi-Magasin

## 🚀 TL;DR

Toutes les données sont maintenant automatiquement filtrées par magasin pour les utilisateurs **cashier** et **staff**. Les **admins** et **managers** voient tous les magasins.

## 🔥 Quick Start

### Ajouter le filtrage à un nouveau repository

```php
public function getSomething()
{
    $query = Model::query();
    
    // ✨ Ajouter cette ligne magique ✨
    if (!user_can_access_all_stores() && current_store_id()) {
        $query->where('store_id', current_store_id());
    }
    
    return $query->get();
}
```

### Pour les relations

```php
public function getSomethingWithRelation()
{
    $query = ProductVariant::query();
    
    // ✨ Pour les relations ✨
    if (!user_can_access_all_stores() && current_store_id()) {
        $query->whereHas('product', function($q) {
            $q->where('store_id', current_store_id());
        });
    }
    
    return $query->get();
}
```

### Pour les jointures

```php
public function getSomethingWithJoin()
{
    $query = DB::table('items')
        ->join('sales', 'items.sale_id', '=', 'sales.id');
    
    // ✨ Pour les jointures ✨
    if (!user_can_access_all_stores() && current_store_id()) {
        $query->where('sales.store_id', current_store_id());
    }
    
    return $query->get();
}
```

## 🎯 Helpers disponibles

```php
// ID du magasin actuel
$storeId = current_store_id(); // 1, 2, 3, etc.

// Objet Store complet
$store = current_store(); // Store model instance

// L'utilisateur peut voir tous les magasins? (admin/manager)
$canSeeAll = user_can_access_all_stores(); // true/false

// Rôle dans le magasin actuel
$role = user_role_in_current_store(); // 'admin', 'manager', 'cashier', 'staff'

// Est cashier ou staff?
$isCashierOrStaff = user_is_cashier_or_staff(); // true/false
```

## 📦 Repositories déjà filtrés

✅ **DashboardRepository** - Toutes les stats (15 méthodes)  
✅ **ProductRepository** - Liste des produits (5 méthodes)  
✅ **SaleRepository** - Ventes (2 méthodes)  
✅ **StockMovementRepository** - Mouvements (2 méthodes)  
✅ **InvoiceRepository** - Factures (1 méthode)  
✅ **PurchaseRepository** - Achats (1 méthode)

## 🧪 Tester votre filtrage

```bash
# Script de test rapide
php test-dashboard-filter.php

# Audit des données
php artisan store:audit

# Corriger les orphelins
php artisan store:fix-orphans --dry-run
php artisan store:fix-orphans
```

## 👥 Utilisateurs de test

```bash
php artisan db:seed --class=TestUsersSeeder
```

| Email | Rôle | Magasin | Mot de passe |
|-------|------|---------|--------------|
| admin@stk.com | Admin | Tous | Password123! |
| manager@stk.com | Manager | Magasin 1 | Password123! |
| cashier1@stk.com | Cashier | Magasin 1 | Password123! |
| staff1@stk.com | Staff | Magasin 1 | Password123! |

## ❓ Questions fréquentes

### Q: Comment tester si un utilisateur peut voir tous les magasins?
```php
if (user_can_access_all_stores()) {
    // Code pour admin/manager
} else {
    // Code pour cashier/staff
}
```

### Q: Comment obtenir le magasin actuel?
```php
$storeId = current_store_id();
$store = current_store(); // Pour l'objet complet
```

### Q: Dois-je modifier mes composants Livewire?
Non! Le filtrage est transparent. Vos composants appellent juste les méthodes des repositories.

### Q: Et si je veux forcer un magasin spécifique?
```php
// Temporairement changer de magasin
session(['current_store_id' => 2]);

// Faire votre requête
$data = $repo->getSomething();

// Restaurer l'ancien magasin (optionnel)
session(['current_store_id' => $oldStoreId]);
```

### Q: Comment vérifier les données orphelines?
```bash
php artisan store:audit
```

### Q: Comment corriger les données orphelines?
```bash
# Voir ce qui serait fait
php artisan store:fix-orphans --dry-run

# Appliquer
php artisan store:fix-orphans
```

## 🐛 Dépannage rapide

### Le filtrage ne fonctionne pas
```bash
# 1. Recharger les helpers
composer dump-autoload

# 2. Vider les caches
php artisan clear-compiled
php artisan config:clear
php artisan cache:clear

# 3. Vérifier en PHP
php artisan tinker
>>> current_store_id()
>>> user_can_access_all_stores()
```

### Erreur "Call to undefined function"
```json
// Dans composer.json
"autoload": {
    "files": [
        "app/Helpers/StoreHelper.php"
    ]
}
```
Puis: `composer dump-autoload`

## 📚 Documentation complète

| Document | Description |
|----------|-------------|
| STORE_FILTERING_GUIDE.md | Guide détaillé du filtrage |
| STORE_ROLES_GUIDE.md | Rôles et permissions |
| DASHBOARD_STORE_FILTERING.md | Filtrage du dashboard |
| MULTI_STORE_COMPLETE_SUMMARY.md | Résumé complet |
| STORE_AUDIT_COMMANDS.md | Commandes d'audit |

## 💡 Pattern complet avec exemple

```php
<?php

namespace App\Repositories;

use App\Models\YourModel;
use Illuminate\Database\Eloquent\Collection;

class YourRepository
{
    /**
     * Get all items (filtered by store for cashier/staff)
     */
    public function all(): Collection
    {
        $query = YourModel::query();
        
        // 🔥 LE PATTERN MAGIQUE 🔥
        if (!user_can_access_all_stores() && current_store_id()) {
            $query->where('store_id', current_store_id());
        }
        
        return $query->get();
    }
    
    /**
     * Get paginated items (filtered by store for cashier/staff)
     */
    public function paginate(int $perPage = 15)
    {
        $query = YourModel::query();
        
        // 🔥 LE PATTERN MAGIQUE 🔥
        if (!user_can_access_all_stores() && current_store_id()) {
            $query->where('store_id', current_store_id());
        }
        
        return $query->paginate($perPage);
    }
    
    /**
     * Search items (filtered by store for cashier/staff)
     */
    public function search(string $term): Collection
    {
        $query = YourModel::where('name', 'like', "%{$term}%");
        
        // 🔥 LE PATTERN MAGIQUE 🔥
        if (!user_can_access_all_stores() && current_store_id()) {
            $query->where('store_id', current_store_id());
        }
        
        return $query->get();
    }
}
```

## 🎯 Checklist pour un nouveau repository

- [ ] Identifier toutes les méthodes qui retournent des données
- [ ] Ajouter le pattern de filtrage à chaque méthode
- [ ] Tester avec un utilisateur admin (doit voir tout)
- [ ] Tester avec un utilisateur cashier (doit voir uniquement son magasin)
- [ ] Vérifier les performances (index sur store_id?)
- [ ] Documenter si comportement spécial

## 🔗 Liens utiles

- **Middleware:** `app/Http/Middleware/EnsureUserHasStoreAccess.php`
- **Helpers:** `app/Helpers/StoreHelper.php`
- **StoreSwitcher:** `app/Livewire/Store/StoreSwitcher.php`
- **Test Script:** `test-dashboard-filter.php`

## ⚡ Commandes utiles

```bash
# Tester le filtrage
php test-dashboard-filter.php

# Auditer les données
php artisan store:audit
php artisan store:audit --products
php artisan store:audit --sales
php artisan store:audit --stock

# Corriger les orphelins
php artisan store:fix-orphans --dry-run
php artisan store:fix-orphans

# Créer des utilisateurs de test
php artisan db:seed --class=TestUsersSeeder

# Vider les caches
php artisan optimize:clear
composer dump-autoload
```

## 🎓 Bonnes pratiques

1. **Toujours** ajouter le filtrage dans le repository, pas dans le controller
2. **Toujours** utiliser les helpers (ne pas accéder directement à la session)
3. **Toujours** tester avec différents rôles
4. **Toujours** vérifier qu'il n'y a pas de requêtes N+1
5. **Ne jamais** modifier directement session('current_store_id') dans le code métier

## 🚨 Pièges à éviter

❌ **Ne pas faire:**
```php
// Accès direct à la session
if (session('current_store_id')) { ... }

// Hardcoder des rôles
if (auth()->user()->role === 'cashier') { ... }

// Filtrer dans le controller
$products = Product::where('store_id', $storeId)->get();
```

✅ **À faire:**
```php
// Utiliser les helpers
if (current_store_id()) { ... }

// Utiliser les fonctions de rôle
if (user_is_cashier_or_staff()) { ... }

// Filtrer dans le repository
$products = $this->productRepo->all(); // Déjà filtré!
```

---

**Need help?** Consultez les documents détaillés dans le dossier racine du projet.

**Found a bug?** Vérifiez d'abord avec `php artisan store:audit`.
