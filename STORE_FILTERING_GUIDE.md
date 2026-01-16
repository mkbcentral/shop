# 🏪 Guide: Filtrage par Magasin - Isolation des Données

## 📋 Vue d'ensemble

Ce système garantit que chaque utilisateur ne voit que les données de son magasin assigné, sauf pour les administrateurs qui ont accès à tous les magasins.

---

## 🔒 Fonctionnement Automatique

### 1. **Middleware `EnsureUserHasStoreAccess`**

Activé automatiquement sur toutes les routes web :

```php
// bootstrap/app.php
$middleware->appendToGroup('web', \App\Http\Middleware\EnsureUserHasStoreAccess::class);
```

**Comportement :**
- ✅ Vérifie que l'utilisateur a un `current_store_id`
- ✅ Si aucun magasin : assigne automatiquement le magasin principal
- ✅ Vérifie que l'utilisateur a accès au magasin actuel
- ✅ Si pas d'accès : réassigne à un magasin accessible

### 2. **Helpers Globaux**

Trois fonctions disponibles partout dans l'application :

```php
// Obtenir l'ID du magasin actuel
$storeId = current_store_id(); // int|null

// Obtenir le modèle Store actuel
$store = current_store(); // Store|null

// Vérifier si l'utilisateur est admin (accès à tous les magasins)
$isAdmin = user_can_access_all_stores(); // bool
```

**Fichier :** `app/Helpers/StoreHelper.php`

### 3. **Filtrage Automatique dans les Repositories**

Tous les repositories importants filtrent automatiquement par magasin :

```php
// ❌ AVANT - Pas de filtrage
public function getTodaySales(): float
{
    return Sale::whereDate('sale_date', today())->sum('total') ?? 0;
}

// ✅ APRÈS - Filtrage automatique
public function getTodaySales(): float
{
    $query = Sale::whereDate('sale_date', today());
    
    // Filtre uniquement si l'utilisateur n'est pas admin
    if (!user_can_access_all_stores() && current_store_id()) {
        $query->where('store_id', current_store_id());
    }
    
    return $query->sum('total') ?? 0;
}
```

---

## 🎯 Modèles avec `store_id`

Les modèles suivants sont filtrés par magasin :

| Modèle | Table | Champ |
|--------|-------|-------|
| Sale | sales | store_id |
| Purchase | purchases | store_id |
| StockMovement | stock_movements | store_id |
| Invoice | invoices | store_id |
| Product | products | store_id |
| StoreStock | store_stocks | store_id |

---

## 👨‍💼 Gestion des Utilisateurs

### Assigner un Utilisateur à un Magasin

```php
use App\Services\StoreService;

$storeService = app(StoreService::class);

// Assigner l'utilisateur au magasin
$storeService->assignUserToStore(
    storeId: 1,
    userId: 5,
    role: 'staff', // staff, manager, admin
    isDefault: true // Définir comme magasin par défaut
);
```

### Changer le Magasin Actif

```php
// Via le Service
$storeService->switchUserStore(userId: 5, storeId: 2);

// Ou directement
auth()->user()->update(['current_store_id' => 2]);
```

### Vérifier l'Accès

```php
$user = auth()->user();

// Vérifier l'accès à un magasin
$hasAccess = $user->hasAccessToStore($storeId);

// Obtenir le rôle dans un magasin
$role = $user->getRoleInStore($storeId); // 'staff', 'manager', etc.

// Obtenir tous les magasins accessibles
$stores = $user->stores; // Collection de magasins
```

---

## 🔧 Utilisation dans les Composants Livewire

### Exemple : Filtrage Automatique

```php
namespace App\Livewire\Sales;

use App\Models\Sale;
use Livewire\Component;

class SaleIndex extends Component
{
    public function render()
    {
        // ✅ Filtrage automatique si le Repository le fait
        $sales = Sale::query();
        
        // Ajout manuel du filtre si nécessaire
        if (!user_can_access_all_stores() && current_store_id()) {
            $sales->where('store_id', current_store_id());
        }
        
        return view('livewire.sales.index', [
            'sales' => $sales->paginate(10)
        ]);
    }
}
```

### Créer une Vente pour le Magasin Actuel

```php
public function createSale()
{
    Sale::create([
        'store_id' => current_store_id(), // ✅ Magasin automatique
        'client_id' => $this->clientId,
        'total' => $this->total,
        'sale_date' => now(),
    ]);
}
```

---

## 🎨 Interface : Sélecteur de Magasin

### Composant `StoreSwitcher`

Disponible dans la navbar pour changer de magasin :

```blade
{{-- resources/views/components/header.blade.php --}}
@livewire('store.store-switcher')
```

**Comportement :**
- Liste tous les magasins accessibles par l'utilisateur
- Admins voient tous les magasins
- Utilisateurs normaux ne voient que leurs magasins assignés
- Changement de magasin → rechargement de la page → nouvelles données

---

## 📊 Repositories Modifiés

### DashboardRepository

Méthodes filtrées :
- ✅ `getTodaySales()` - Ventes du jour
- ✅ `getMonthSales()` - Ventes du mois
- ✅ `getRecentSales()` - Ventes récentes
- ✅ `getRecentStockMovements()` - Mouvements de stock récents
- ✅ `getTopSellingProducts()` - Produits les plus vendus
- ✅ `getSalesGroupedByDate()` - Ventes par période

### À Modifier (Si Nécessaire)

Si vous créez de nouveaux repositories, pensez à ajouter le filtrage :

```php
public function myCustomQuery()
{
    $query = MyModel::query();
    
    // Toujours ajouter cette vérification
    if (!user_can_access_all_stores() && current_store_id()) {
        $query->where('store_id', current_store_id());
    }
    
    return $query->get();
}
```

---

## 🚀 Migration : Ajouter `store_id` à une Table

Si vous créez une nouvelle table qui doit être filtrée par magasin :

```php
Schema::create('my_table', function (Blueprint $table) {
    $table->id();
    $table->foreignId('store_id')->constrained()->cascadeOnDelete();
    // ... autres colonnes
    $table->timestamps();
    
    // Index pour performance
    $table->index('store_id');
});
```

---

## 🧪 Tester le Système

### 1. Créer un Utilisateur Non-Admin

```php
use App\Models\User;
use App\Services\StoreService;

$user = User::create([
    'name' => 'Employé Magasin 1',
    'email' => 'employe@magasin1.com',
    'password' => bcrypt('password'),
]);

$storeService = app(StoreService::class);
$storeService->assignUserToStore(
    storeId: 1,
    userId: $user->id,
    role: 'staff',
    isDefault: true
);
```

### 2. Se Connecter avec cet Utilisateur

- Aller sur `/login`
- Se connecter avec l'email et mot de passe
- Vérifier que seules les données du Magasin 1 sont visibles

### 3. Vérifier le Dashboard

Le dashboard devrait afficher uniquement :
- Ventes du magasin 1
- Stock du magasin 1
- Mouvements du magasin 1

### 4. Tester le Changement de Magasin

Si l'utilisateur a accès à plusieurs magasins :
- Cliquer sur le sélecteur de magasin dans la navbar
- Choisir un autre magasin
- La page se recharge avec les nouvelles données

---

## 🔑 Rôles et Permissions

### Types de Rôles dans un Magasin

| Rôle | Description | Accès |
|------|-------------|-------|
| `staff` | Employé simple | Lecture/création basique |
| `manager` | Gérant du magasin | Gestion complète du magasin |
| `admin` | Administrateur système | **TOUS LES MAGASINS** |

### Vérifier le Rôle

```php
$user = auth()->user();

// Vérifier si admin (accès global)
if ($user->isAdmin()) {
    // Accès à tous les magasins
}

// Vérifier le rôle dans le magasin actuel
$role = $user->getRoleInStore(current_store_id());

if ($role === 'manager') {
    // Actions de manager
}
```

---

## ⚠️ Points Importants

### ✅ À FAIRE

1. **Toujours utiliser les helpers** : `current_store_id()`, `user_can_access_all_stores()`
2. **Filtrer dans les repositories** : Ajouter le check `if (!user_can_access_all_stores())`
3. **Assigner `store_id` lors de la création** : `'store_id' => current_store_id()`
4. **Tester avec un utilisateur non-admin** : Vérifier l'isolation des données

### ❌ À ÉVITER

1. **Ne pas bypasser le filtrage** : Sauf si vous êtes sûr de la raison
2. **Ne pas oublier `store_id`** : Lors de la création d'enregistrements
3. **Ne pas hardcoder les IDs** : Toujours utiliser `current_store_id()`
4. **Ne pas donner accès global** : Sauf pour les vrais admins

---

## 🔄 Workflow Complet

### 1. Connexion Utilisateur
```
Utilisateur se connecte
    ↓
Middleware EnsureUserHasStoreAccess
    ↓
Vérifie current_store_id
    ↓
Si null → Assigne magasin principal
    ↓
Vérifie l'accès au magasin
    ↓
Utilisateur accède à l'application
```

### 2. Consultation des Données
```
Utilisateur va sur Dashboard
    ↓
Dashboard appelle DashboardRepository
    ↓
Repository vérifie user_can_access_all_stores()
    ↓
Si false → Ajoute WHERE store_id = current_store_id()
    ↓
Retourne uniquement les données du magasin
```

### 3. Création de Données
```
Utilisateur crée une vente
    ↓
Sale::create([
    'store_id' => current_store_id(), ← Automatique
    ...
])
    ↓
Vente enregistrée avec le bon magasin
```

---

## 📚 Commandes Utiles

### Vérifier les Magasins

```bash
php artisan tinker
```

```php
// Voir tous les magasins
App\Models\Store::all()->pluck('name', 'id');

// Voir les magasins d'un utilisateur
$user = App\Models\User::find(1);
$user->stores->pluck('name');

// Voir le magasin actuel d'un utilisateur
$user->currentStore->name;
```

### Changer le Magasin d'un Utilisateur

```bash
php artisan tinker
```

```php
$user = App\Models\User::find(1);
$user->update(['current_store_id' => 2]);
```

---

## 🎉 Résumé

✅ **Middleware automatique** : Assigne et vérifie le magasin  
✅ **Helpers globaux** : Faciles à utiliser partout  
✅ **Filtrage automatique** : Dans les repositories  
✅ **Interface utilisateur** : Sélecteur de magasin dans la navbar  
✅ **Sécurité** : Isolation complète des données  
✅ **Flexibilité** : Admins ont accès à tout  

**Le système garantit que chaque utilisateur travaille uniquement dans son magasin assigné ! 🔒**

---

**Version:** 1.0.0  
**Date:** 7 janvier 2026  
**Status:** ✅ Production Ready
