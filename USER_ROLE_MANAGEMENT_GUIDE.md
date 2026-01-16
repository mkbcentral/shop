# Gestion des Utilisateurs et Rôles

## Vue d'ensemble

Le système de gestion des utilisateurs et des rôles permet de :
- Assigner des rôles avec permissions spécifiques aux utilisateurs
- Affecter des utilisateurs à un ou plusieurs magasins
- Gérer les permissions granulaires par rôle
- Contrôler l'accès aux fonctionnalités selon le rôle

## Structure de la base de données

### Table `roles`
```sql
- id: bigint (PK)
- name: string (nom d'affichage)
- slug: string (identifiant unique)
- description: text (description du rôle)
- permissions: json (liste des permissions)
- is_active: boolean
- timestamps
```

### Table `role_user` (pivot)
```sql
- id: bigint (PK)
- user_id: bigint (FK)
- role_id: bigint (FK)
- timestamps
- unique(user_id, role_id)
```

## Rôles par défaut

### 1. Super Admin
**Slug**: `super-admin`
**Description**: Accès complet à toutes les fonctionnalités du système

**Permissions**: Toutes (90+ permissions)

### 2. Admin
**Slug**: `admin`
**Description**: Administrateur avec accès à la plupart des fonctionnalités

**Permissions principales**:
- Gestion utilisateurs (limité)
- Gestion magasins
- Gestion produits
- Gestion ventes et achats
- Gestion clients et fournisseurs
- Gestion transferts
- Tous les rapports

### 3. Manager
**Slug**: `manager`
**Description**: Gérant de magasin avec accès aux opérations quotidiennes

**Permissions principales**:
- Voir magasins et statistiques
- Gestion produits
- Gestion ventes et achats
- Gestion clients et fournisseurs
- Créer et recevoir transferts
- Rapports opérationnels

### 4. Cashier
**Slug**: `cashier`
**Description**: Caissier avec accès aux ventes et clients

**Permissions principales**:
- Voir produits
- Créer et voir ventes
- Gestion clients
- Rapport ventes

### 5. Staff
**Slug**: `staff`
**Description**: Employé avec accès limité aux fonctionnalités de base

**Permissions principales**:
- Voir produits
- Gestion stock
- Voir clients
- Voir transferts

## Catégories de permissions

### Système
- `system.settings` - Configuration système
- `system.backup` - Sauvegarde et restauration
- `system.logs` - Consulter les logs

### Utilisateurs
- `users.view` - Voir utilisateurs
- `users.create` - Créer utilisateurs
- `users.edit` - Modifier utilisateurs
- `users.delete` - Supprimer utilisateurs
- `users.assign-role` - Assigner rôles
- `users.assign-store` - Affecter magasins

### Magasins
- `stores.view` - Voir magasins
- `stores.create` - Créer magasins
- `stores.edit` - Modifier magasins
- `stores.delete` - Supprimer magasins
- `stores.manage-users` - Gérer utilisateurs
- `stores.view-statistics` - Voir statistiques

### Rôles
- `roles.view` - Voir rôles
- `roles.create` - Créer rôles
- `roles.edit` - Modifier rôles
- `roles.delete` - Supprimer rôles

### Produits
- `products.view` - Voir produits
- `products.create` - Créer produits
- `products.edit` - Modifier produits
- `products.delete` - Supprimer produits
- `products.manage-stock` - Gérer stock

### Catégories
- `categories.view` - Voir catégories
- `categories.create` - Créer catégories
- `categories.edit` - Modifier catégories
- `categories.delete` - Supprimer catégories

### Ventes
- `sales.view` - Voir ventes
- `sales.create` - Créer ventes
- `sales.edit` - Modifier ventes
- `sales.delete` - Supprimer ventes
- `sales.refund` - Rembourser ventes

### Achats
- `purchases.view` - Voir achats
- `purchases.create` - Créer achats
- `purchases.edit` - Modifier achats
- `purchases.delete` - Supprimer achats

### Clients
- `clients.view` - Voir clients
- `clients.create` - Créer clients
- `clients.edit` - Modifier clients
- `clients.delete` - Supprimer clients

### Fournisseurs
- `suppliers.view` - Voir fournisseurs
- `suppliers.create` - Créer fournisseurs
- `suppliers.edit` - Modifier fournisseurs
- `suppliers.delete` - Supprimer fournisseurs

### Transferts
- `transfers.view` - Voir transferts
- `transfers.create` - Créer transferts
- `transfers.approve` - Approuver transferts
- `transfers.receive` - Recevoir transferts
- `transfers.cancel` - Annuler transferts

### Rapports
- `reports.sales` - Rapport ventes
- `reports.purchases` - Rapport achats
- `reports.stock` - Rapport stock
- `reports.financial` - Rapport financier

## Utilisation

### Migration et seeding

```bash
# Exécuter les migrations
php artisan migrate

# Exécuter le seeder des rôles
php artisan db:seed --class=RoleSeeder
```

### Modèle User

#### Relations
```php
// Obtenir les rôles d'un utilisateur
$user->roles;

// Obtenir les magasins d'un utilisateur
$user->stores;

// Obtenir le magasin actuel
$user->currentStore;

// Obtenir les magasins gérés
$user->managedStores;
```

#### Méthodes de vérification des rôles
```php
// Vérifier si l'utilisateur a un rôle
$user->hasRole('admin');
$user->hasRole(['admin', 'manager']);

// Vérifier si l'utilisateur a au moins un des rôles
$user->hasAnyRole(['admin', 'manager']);

// Vérifier si l'utilisateur a tous les rôles
$user->hasAllRoles(['admin', 'manager']);
```

#### Méthodes de vérification des permissions
```php
// Vérifier si l'utilisateur a une permission
$user->hasPermission('products.create');

// Vérifier si l'utilisateur a au moins une des permissions
$user->hasAnyPermission(['products.create', 'products.edit']);

// Vérifier si l'utilisateur a toutes les permissions
$user->hasAllPermissions(['products.create', 'products.edit']);
```

#### Assigner/Retirer des rôles
```php
// Assigner un rôle
$user->assignRole('manager');
$user->assignRole(1); // Par ID
$user->assignRole($role); // Par instance

// Assigner plusieurs rôles
$user->assignRoles(['manager', 'cashier']);

// Retirer un rôle
$user->removeRole('manager');

// Synchroniser les rôles (remplace tous)
$user->syncRoles(['manager', 'cashier']);
```

### Modèle Role

#### Méthodes de gestion des permissions
```php
// Vérifier si le rôle a une permission
$role->hasPermission('products.create');

// Donner une permission
$role->givePermission('products.create');

// Donner plusieurs permissions
$role->givePermissions(['products.create', 'products.edit']);

// Révoquer une permission
$role->revokePermission('products.create');

// Synchroniser les permissions
$role->syncPermissions(['products.create', 'products.edit']);
```

### Service UserService

#### Créer un utilisateur avec rôles et magasins
```php
use App\Services\UserService;

$userService = app(UserService::class);

$user = $userService->createUser([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'password123',
    'roles' => ['manager'], // Slugs des rôles
    'stores' => [
        1 => ['role' => 'manager', 'is_default' => true],
        2 => ['role' => 'staff', 'is_default' => false],
    ],
]);
```

#### Mettre à jour un utilisateur
```php
$user = $userService->updateUser($userId, [
    'name' => 'John Smith',
    'roles' => ['manager', 'cashier'],
    'stores' => [
        1 => ['role' => 'manager', 'is_default' => true],
    ],
]);
```

#### Assigner un rôle
```php
$user = $userService->assignRole($userId, 'manager');
```

#### Assigner à un magasin
```php
$user = $userService->assignToStore($userId, $storeId, 'manager', true);
```

#### Retirer d'un magasin
```php
$user = $userService->removeFromStore($userId, $storeId);
```

#### Définir le magasin par défaut
```php
$user = $userService->setDefaultStore($userId, $storeId);
```

#### Obtenir les utilisateurs filtrés
```php
// Avec filtres
$users = $userService->getAllUsers(
    search: 'john',
    role: 'manager',
    storeId: 1,
    sortBy: 'name',
    sortDirection: 'asc',
    perPage: 10
);

// Par rôle
$managers = $userService->getUsersByRole('manager');

// Par magasin
$storeUsers = $userService->getUsersByStore($storeId);

// Recherche
$results = $userService->searchUsers('john', 10);
```

#### Statistiques utilisateur
```php
$stats = $userService->getUserStatistics($userId);
// Retourne: total_stores, total_roles, managed_stores, last_login, account_status
```

## Middleware de permissions (À créer)

Pour protéger les routes basées sur les permissions :

```php
// Dans app/Http/Middleware/CheckPermission.php
public function handle($request, Closure $next, string $permission)
{
    if (!auth()->user()->hasPermission($permission)) {
        abort(403, 'Unauthorized action.');
    }
    
    return $next($request);
}

// Dans routes/web.php
Route::middleware(['auth', 'permission:products.create'])
    ->post('/products', [ProductController::class, 'store']);
```

## Blade Directives (À créer)

Pour vérifier les permissions dans les vues :

```php
// Dans app/Providers/AppServiceProvider.php
Blade::if('role', function ($role) {
    return auth()->check() && auth()->user()->hasRole($role);
});

Blade::if('permission', function ($permission) {
    return auth()->check() && auth()->user()->hasPermission($permission);
});

// Dans les vues
@role('admin')
    <a href="/admin">Admin Panel</a>
@endrole

@permission('products.create')
    <button>Create Product</button>
@endpermission
```

## Sécurité

- Les mots de passe sont hashés avec bcrypt
- La table pivot role_user empêche les doublons
- Le Super Admin ne peut pas être supprimé
- Validation stricte sur l'affectation des rôles et magasins
- Transactions DB pour garantir l'intégrité des données

## Prochaines étapes

1. Créer le middleware `CheckPermission`
2. Créer les Blade directives `@role` et `@permission`
3. Créer l'interface d'administration des utilisateurs
4. Créer l'interface de gestion des rôles
5. Ajouter des tests unitaires pour les permissions
6. Implémenter l'audit log des changements de rôles

## Exemples d'utilisation complète

### Créer un manager pour un magasin

```php
$userService = app(UserService::class);

// Créer l'utilisateur
$user = $userService->createUser([
    'name' => 'Marie Dupont',
    'email' => 'marie@store.com',
    'password' => 'secure123',
    'roles' => ['manager'],
    'stores' => [
        $storeId => [
            'role' => 'manager',
            'is_default' => true
        ]
    ],
]);

// Vérifications
if ($user->hasRole('manager')) {
    echo "Manager créé avec succès";
}

if ($user->hasPermission('sales.create')) {
    echo "Peut créer des ventes";
}
```

### Promouvoir un cashier en manager

```php
$user = User::find($userId);

// Retirer l'ancien rôle
$user->removeRole('cashier');

// Assigner le nouveau rôle
$user->assignRole('manager');

// Mettre à jour le rôle dans le magasin
$userService->updateStoreRole($userId, $storeId, 'manager');
```
