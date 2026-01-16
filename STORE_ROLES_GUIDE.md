# 👥 Guide: Système de Rôles et Filtrage par Magasin

## 📋 Vue d'ensemble

Le système utilise **deux niveaux de rôles** :
1. **Rôle Global** (table `users`, colonne `role`) : admin, user, etc.
2. **Rôle par Magasin** (table `store_user`, colonne `role`) : admin, manager, cashier, staff

---

## 🎭 Types de Rôles par Magasin

### Table `store_user`

| Rôle | Description | Accès aux Données |
|------|-------------|-------------------|
| **admin** | Administrateur du magasin | Peut voir les données de TOUS les magasins |
| **manager** | Gérant du magasin | Peut voir toutes les données de SON magasin |
| **cashier** | Caissier | **FILTRE**: Ne voit QUE les données de SON magasin |
| **staff** | Employé | **FILTRE**: Ne voit QUE les données de SON magasin |

---

## 🔒 Logique de Filtrage

### Règles Automatiques

```php
// Les utilisateurs avec les rôles suivants voient UNIQUEMENT leur magasin :
- cashier (caissier)
- staff (employé)

// Les utilisateurs avec les rôles suivants peuvent voir tous les magasins :
- admin (administrateur global)
- admin (rôle dans le magasin)
```

### Fonction Helper Principale

```php
// Vérifie si l'utilisateur peut accéder à tous les magasins
user_can_access_all_stores(); // bool

// ✅ Retourne TRUE si :
- L'utilisateur a le rôle global 'admin'

// ❌ Retourne FALSE si :
- L'utilisateur est 'cashier' ou 'staff' dans son magasin
- L'utilisateur est 'manager' dans son magasin
```

### Nouvelles Fonctions Helpers

```php
// Obtenir le rôle de l'utilisateur dans le magasin actuel
$role = user_role_in_current_store(); // 'admin', 'manager', 'cashier', 'staff', ou null

// Vérifier si l'utilisateur est cashier ou staff
$isCashierOrStaff = user_is_cashier_or_staff(); // bool
```

---

## 📊 Filtrage Automatique dans les Repositories

### Exemple : DashboardRepository

```php
public function getTodaySales(): float
{
    $query = Sale::whereDate('sale_date', today());
    
    // ✅ Filtre automatique si l'utilisateur n'est pas admin
    if (!user_can_access_all_stores() && current_store_id()) {
        $query->where('store_id', current_store_id());
    }
    
    return $query->sum('total') ?? 0;
}
```

**Comportement :**
- 👤 **Cashier/Staff** → Voit uniquement les ventes de son magasin
- 👤 **Manager** → Voit toutes les ventes de son magasin
- 👤 **Admin** → Voit les ventes de TOUS les magasins

---

## 🛠️ Configuration des Utilisateurs

### 1. Créer un Utilisateur "Cashier"

```php
use App\Models\User;
use App\Services\StoreService;

// Créer l'utilisateur
$cashier = User::create([
    'name' => 'Jean Caissier',
    'email' => 'jean.caissier@example.com',
    'password' => bcrypt('password'),
    'role' => 'user', // Rôle global
]);

// Assigner au magasin avec le rôle 'cashier'
$storeService = app(StoreService::class);
$storeService->assignUserToStore(
    storeId: 1,           // ID du magasin
    userId: $cashier->id,
    role: 'cashier',      // ✅ Rôle dans le magasin
    isDefault: true       // Magasin par défaut
);
```

### 2. Créer un Utilisateur "Staff"

```php
$staff = User::create([
    'name' => 'Marie Employée',
    'email' => 'marie.staff@example.com',
    'password' => bcrypt('password'),
    'role' => 'user',
]);

$storeService->assignUserToStore(
    storeId: 1,
    userId: $staff->id,
    role: 'staff',        // ✅ Rôle dans le magasin
    isDefault: true
);
```

### 3. Créer un Utilisateur "Manager"

```php
$manager = User::create([
    'name' => 'Paul Gérant',
    'email' => 'paul.manager@example.com',
    'password' => bcrypt('password'),
    'role' => 'user',
]);

$storeService->assignUserToStore(
    storeId: 1,
    userId: $manager->id,
    role: 'manager',      // Manager du magasin
    isDefault: true
);
```

### 4. Créer un Administrateur Global

```php
$admin = User::create([
    'name' => 'Admin Système',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'role' => 'admin',    // ✅ Rôle GLOBAL admin
]);

// Optionnel : assigner à un magasin
$storeService->assignUserToStore(
    storeId: 1,
    userId: $admin->id,
    role: 'admin',        // Rôle admin dans le magasin aussi
    isDefault: true
);
```

---

## 🧪 Tester le Système

### Test 1 : Cashier ne voit que son magasin

```bash
# Créer un cashier
php artisan tinker
```

```php
use App\Models\User;
use App\Services\StoreService;

$cashier = User::create([
    'name' => 'Test Cashier',
    'email' => 'cashier@test.com',
    'password' => bcrypt('password'),
    'role' => 'user',
]);

$storeService = app(StoreService::class);
$storeService->assignUserToStore(1, $cashier->id, 'cashier', true);
```

**Résultat attendu :**
- ✅ Se connecte et voit uniquement les données du Magasin 1
- ✅ Ne peut pas changer de magasin dans le dropdown (s'il n'a qu'un seul magasin)
- ✅ Dashboard affiche uniquement les ventes/stocks du Magasin 1

### Test 2 : Staff ne voit que son magasin

Même processus avec `role: 'staff'`

### Test 3 : Manager voit tout son magasin

Même processus avec `role: 'manager'`

### Test 4 : Admin voit tous les magasins

```php
$admin = User::create([
    'name' => 'Admin',
    'email' => 'admin@test.com',
    'password' => bcrypt('password'),
    'role' => 'admin', // Rôle global
]);
```

**Résultat attendu :**
- ✅ Voit les données de TOUS les magasins
- ✅ Peut changer de magasin dans le dropdown
- ✅ Peut gérer tous les magasins

---

## 🔄 Workflow Complet

### Connexion d'un Cashier

```
1. Cashier se connecte
   ↓
2. Middleware EnsureUserHasStoreAccess
   ↓
3. Vérifie current_store_id (Magasin 1)
   ↓
4. Charge le Dashboard
   ↓
5. DashboardRepository appelle getTodaySales()
   ↓
6. user_can_access_all_stores() retourne FALSE (cashier)
   ↓
7. WHERE store_id = 1 est ajouté à la requête
   ↓
8. ✅ Cashier voit uniquement les ventes du Magasin 1
```

### Connexion d'un Admin

```
1. Admin se connecte
   ↓
2. Middleware EnsureUserHasStoreAccess
   ↓
3. Vérifie current_store_id (peut choisir n'importe quel magasin)
   ↓
4. Charge le Dashboard
   ↓
5. DashboardRepository appelle getTodaySales()
   ↓
6. user_can_access_all_stores() retourne TRUE (admin global)
   ↓
7. AUCUN filtre WHERE store_id n'est ajouté
   ↓
8. ✅ Admin voit les ventes de TOUS les magasins
```

---

## 📝 Vérifications dans les Composants

### Exemple : Vérifier le rôle dans un composant Livewire

```php
namespace App\Livewire\Sales;

use Livewire\Component;

class SaleIndex extends Component
{
    public function mount()
    {
        // Vérifier le rôle
        $role = user_role_in_current_store();
        
        if (user_is_cashier_or_staff()) {
            // Logique spécifique pour cashier/staff
            $this->restrictedMode = true;
        }
    }
    
    public function render()
    {
        $query = Sale::query();
        
        // Filtrage automatique
        if (!user_can_access_all_stores() && current_store_id()) {
            $query->where('store_id', current_store_id());
        }
        
        return view('livewire.sales.index', [
            'sales' => $query->paginate(10)
        ]);
    }
}
```

---

## 🎯 Matrice des Permissions

| Action | Cashier | Staff | Manager | Admin |
|--------|---------|-------|---------|-------|
| Voir les données de son magasin | ✅ | ✅ | ✅ | ✅ |
| Voir les données d'autres magasins | ❌ | ❌ | ❌ | ✅ |
| Créer une vente dans son magasin | ✅ | ✅ | ✅ | ✅ |
| Modifier le stock de son magasin | ❌ | ❌ | ✅ | ✅ |
| Gérer les utilisateurs | ❌ | ❌ | ❌ | ✅ |
| Créer des transferts | ❌ | ❌ | ✅ | ✅ |
| Changer de magasin | ❌* | ❌* | ✅** | ✅ |

\* Seulement si assigné à plusieurs magasins  
\** Peut changer entre les magasins dont il est manager

---

## 🔍 Debugging

### Vérifier le rôle d'un utilisateur

```bash
php artisan tinker
```

```php
$user = User::find(1);

// Rôle global
echo $user->role; // 'user', 'admin', etc.

// Rôle dans le magasin actuel
echo $user->getRoleInStore($user->current_store_id); // 'cashier', 'staff', 'manager', 'admin'

// Vérifier l'accès
echo user_can_access_all_stores() ? 'Accès global' : 'Accès restreint';

// Vérifier si cashier/staff
echo user_is_cashier_or_staff() ? 'Cashier/Staff' : 'Manager/Admin';
```

### Tester le filtrage

```php
auth()->loginUsingId(1); // Se connecter en tant qu'utilisateur 1

// Récupérer les ventes (devrait être filtré automatiquement)
$sales = app(\App\Repositories\DashboardRepository::class)->getTodaySales();

echo "Ventes du jour: " . $sales;
```

---

## ⚠️ Points Importants

### ✅ À FAIRE

1. **Toujours utiliser les helpers** :
   ```php
   if (!user_can_access_all_stores() && current_store_id()) {
       $query->where('store_id', current_store_id());
   }
   ```

2. **Assigner le bon rôle** lors de la création d'utilisateur :
   ```php
   $storeService->assignUserToStore($storeId, $userId, 'cashier', true);
   ```

3. **Tester avec différents rôles** avant de déployer

### ❌ À ÉVITER

1. ❌ Ne pas confondre rôle global (`users.role`) et rôle magasin (`store_user.role`)
2. ❌ Ne pas hardcoder les vérifications de rôles
3. ❌ Ne pas oublier le filtrage dans les nouveaux repositories
4. ❌ Ne pas donner le rôle 'admin' sans raison

---

## 📚 Résumé

| Helper | Description | Retour |
|--------|-------------|--------|
| `current_store_id()` | ID du magasin actuel | int\|null |
| `current_store()` | Modèle Store actuel | Store\|null |
| `user_can_access_all_stores()` | Accès à tous les magasins ? | bool |
| `user_role_in_current_store()` | Rôle dans le magasin actuel | string\|null |
| `user_is_cashier_or_staff()` | Est cashier ou staff ? | bool |

---

## 🎉 Conclusion

✅ **Cashiers et Staff** : Ne voient QUE les données de leur magasin assigné  
✅ **Managers** : Voient toutes les données de leur magasin  
✅ **Admins** : Voient les données de TOUS les magasins  
✅ **Filtrage automatique** : Appliqué dans tous les repositories  
✅ **Sécurité** : Isolation complète des données par magasin  

**Les utilisateurs travaillent maintenant dans un environnement isolé par magasin selon leur rôle ! 🔒**

---

**Version:** 1.1.0  
**Date:** 7 janvier 2026  
**Status:** ✅ Production Ready
