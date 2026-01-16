# 📚 API Multi-Magasins - Guide du Développeur

## Vue d'ensemble

Ce guide présente les services, actions et méthodes disponibles pour travailler avec le module multi-magasins.

---

## 🏪 StoreService

### Injection du service

```php
use App\Services\StoreService;

$storeService = app(StoreService::class);
// ou
$storeService = resolve(StoreService::class);
// ou injection de dépendance
public function __construct(private StoreService $storeService) {}
```

### Méthodes disponibles

#### Récupération de magasins

```php
// Tous les magasins
$stores = $storeService->getAllStores();

// Magasins actifs uniquement
$activeStores = $storeService->getActiveStores();

// Magasins d'un utilisateur
$userStores = $storeService->getStoresForUser($userId);

// Magasin spécifique
$store = $storeService->findStore($storeId);

// Magasin principal
$mainStore = $storeService->getOrCreateMainStore();
```

#### Gestion de magasins

```php
// Créer un magasin
$store = $storeService->createStore([
    'name' => 'Nouvelle Boutique',
    'code' => 'MAG-004', // Optionnel, auto-généré si absent
    'address' => '123 Avenue',
    'phone' => '+243...',
    'email' => 'email@boutique.com',
    'manager_id' => 5,
    'is_active' => true,
    'is_main' => false,
]);

// Mettre à jour un magasin
$store = $storeService->updateStore($storeId, [
    'name' => 'Nom modifié',
    'is_active' => false,
]);

// Supprimer un magasin
$deleted = $storeService->deleteStore($storeId);
// Exception si magasin principal ou contient des données
```

#### Gestion des utilisateurs

```php
// Assigner un utilisateur à un magasin
$storeService->assignUserToStore(
    storeId: 1,
    userId: 5,
    role: 'manager', // admin, manager, cashier, staff
    isDefault: true
);

// Retirer un utilisateur d'un magasin
$storeService->removeUserFromStore($storeId, $userId);

// Changer le magasin actuel d'un utilisateur
$storeService->switchUserStore($userId, $storeId);
// Exception si utilisateur n'a pas accès
```

#### Gestion du stock par magasin

```php
// Obtenir ou créer le stock d'une variante dans un magasin
$stock = $storeService->getOrCreateStoreStock($storeId, $variantId);

// Ajouter du stock
$stock = $storeService->addStockToStore($storeId, $variantId, 100);

// Retirer du stock
$stock = $storeService->removeStockFromStore($storeId, $variantId, 50);
// Exception si stock insuffisant

// Définir une quantité exacte
$stock = $storeService->setStoreStock($storeId, $variantId, 75);

// Vérifier la disponibilité
$available = $storeService->checkStockAvailability($storeId, $variantId, 20);
// Retourne true/false
```

#### Statistiques

```php
$stats = $storeService->getStoreStatistics($storeId);
// Retourne:
// [
//     'total_products' => 150,
//     'total_sales' => 450,
//     'total_sales_amount' => 2500000,
//     'total_stock_value' => 1800000,
//     'low_stock_count' => 12,
//     'out_of_stock_count' => 5,
// ]
```

---

## 🔄 StoreTransferService

### Injection du service

```php
use App\Services\StoreTransferService;

$transferService = app(StoreTransferService::class);
```

### Méthodes disponibles

#### Créer un transfert

```php
$transfer = $transferService->createTransfer([
    'from_store_id' => 1,
    'to_store_id' => 2,
    'items' => [
        [
            'product_variant_id' => 10,
            'quantity' => 50,
            'notes' => 'Notes optionnelles',
        ],
        [
            'product_variant_id' => 15,
            'quantity' => 30,
        ],
    ],
    'expected_arrival_date' => '2026-01-15',
    'notes' => 'Réassort urgent',
    'requested_by' => auth()->id(),
]);

// Retourne: StoreTransfer avec relations chargées
// Status initial: 'pending'
```

#### Approuver un transfert

```php
$transfer = $transferService->approveTransfer($transferId, $userId);

// Actions effectuées:
// 1. Vérifie stock disponible dans magasin source
// 2. Change statut à 'in_transit'
// 3. Retire stock du magasin source
// 4. Crée mouvements de stock OUT
// 5. Définit quantities_sent

// Exception si:
// - Statut != 'pending'
// - Stock insuffisant
```

#### Recevoir un transfert

```php
$transfer = $transferService->receiveTransfer(
    transferId: $transferId,
    receivedQuantities: [
        10 => 48, // variant_id => quantité reçue
        15 => 30,
    ],
    userId: $userId,
    notes: 'Notes de réception optionnelles'
);

// Actions effectuées:
// 1. Vérifie statut 'in_transit'
// 2. Ajoute stock au magasin destination
// 3. Crée mouvements de stock IN
// 4. Définit quantities_received
// 5. Change statut à 'completed'
// 6. Définit actual_arrival_date

// Si quantité reçue < quantité envoyée:
// → Écart enregistré mais pas d'exception
```

#### Annuler un transfert

```php
$transfer = $transferService->cancelTransfer(
    transferId: $transferId,
    userId: $userId,
    reason: 'Produits endommagés'
);

// Actions effectuées si statut = 'in_transit':
// 1. Restaure stock au magasin source
// 2. Crée mouvements de stock IN (ajustement)
// 3. Change statut à 'cancelled'
// 4. Ajoute raison aux notes

// Possible uniquement si statut = 'pending' ou 'in_transit'
// Exception sinon
```

#### Récupération

```php
// Transfert spécifique
$transfer = $transferService->findTransfer($transferId);

// Transferts en attente d'un magasin
$pending = $transferService->getPendingTransfers($storeId);

// Statistiques des transferts
$stats = $transferService->getTransferStatistics($storeId);
// Retourne:
// [
//     'pending_outgoing' => 3,
//     'pending_incoming' => 2,
//     'in_transit' => 5,
//     'completed_this_month' => 45,
// ]
```

---

## 🎯 Actions

### Store Actions

```php
use App\Actions\Store\{CreateStoreAction, UpdateStoreAction, DeleteStoreAction};
use App\Dtos\Store\{CreateStoreDto, UpdateStoreDto};

// Avec DTO (recommandé)
$dto = CreateStoreDto::fromArray([...]);
$store = app(CreateStoreAction::class)->execute($dto);

// Avec array (rétrocompatible)
$store = app(CreateStoreAction::class)->execute([...]);

// Autres actions
app(UpdateStoreAction::class)->execute($storeId, $data);
app(DeleteStoreAction::class)->execute($storeId);
app(AssignUserToStoreAction::class)->execute($storeId, $userId, 'manager');
app(SwitchUserStoreAction::class)->execute($userId, $storeId);
```

### Transfer Actions

```php
use App\Actions\StoreTransfer\{
    CreateTransferAction,
    ApproveTransferAction,
    ReceiveTransferAction,
    CancelTransferAction
};

$transfer = app(CreateTransferAction::class)->execute([...]);
app(ApproveTransferAction::class)->execute($transferId, $userId);
app(ReceiveTransferAction::class)->execute($transferId, $quantities, $userId);
app(CancelTransferAction::class)->execute($transferId, $userId, $reason);
```

---

## 📦 Modèles

### Store

```php
use App\Models\Store;

// Relations
$store->manager; // BelongsTo User
$store->users; // BelongsToMany User
$store->stock; // HasMany StoreStock
$store->products; // HasMany Product
$store->sales; // HasMany Sale
$store->purchases; // HasMany Purchase
$store->outgoingTransfers; // HasMany StoreTransfer
$store->incomingTransfers; // HasMany StoreTransfer

// Scopes
Store::active()->get();
Store::main()->first();

// Méthodes
$store->isMain(); // bool
$store->isActive(); // bool
$store->getTotalStockValue(); // float
$store->getLowStockCount(); // int
$store->getOutOfStockCount(); // int

// Statique
Store::mainStore(); // ?Store
```

### StoreStock

```php
use App\Models\StoreStock;

// Relations
$stock->store; // BelongsTo Store
$stock->variant; // BelongsTo ProductVariant

// Méthodes
$stock->isLowStock(); // bool
$stock->isOutOfStock(); // bool
$stock->hasSufficientStock($quantity); // bool
$stock->getStockStatus(); // 'in_stock'|'low_stock'|'out_of_stock'
$stock->getStockLevelPercentage(); // float 0-100

$stock->increaseStock($quantity);
$stock->decreaseStock($quantity);
$stock->setStock($quantity);

// Scopes
StoreStock::lowStock()->get();
StoreStock::outOfStock()->get();
StoreStock::inStock()->get();
```

### StoreTransfer

```php
use App\Models\StoreTransfer;

// Relations
$transfer->fromStore; // BelongsTo Store
$transfer->toStore; // BelongsTo Store
$transfer->items; // HasMany StoreTransferItem
$transfer->requester; // BelongsTo User
$transfer->approver; // BelongsTo User
$transfer->receiver; // BelongsTo User

// Méthodes
$transfer->isPending(); // bool
$transfer->isApproved(); // bool
$transfer->isInTransit(); // bool
$transfer->isCompleted(); // bool
$transfer->isCancelled(); // bool
$transfer->canBeCancelled(); // bool
$transfer->canBeApproved(); // bool
$transfer->canBeReceived(); // bool
$transfer->getTotalItemsCount(); // int
$transfer->getTotalItemsReceivedCount(); // int

// Scopes
StoreTransfer::pending()->get();
StoreTransfer::approved()->get();
StoreTransfer::inTransit()->get();
StoreTransfer::completed()->get();
StoreTransfer::cancelled()->get();
StoreTransfer::fromStore($storeId)->get();
StoreTransfer::toStore($storeId)->get();
```

### User (ajouts)

```php
use App\Models\User;

// Nouvelles relations
$user->stores; // BelongsToMany Store
$user->currentStore; // BelongsTo Store
$user->managedStores; // HasMany Store

// Nouvelles méthodes
$user->hasAccessToStore($storeId); // bool
$user->getRoleInStore($storeId); // ?string
```

---

## 🔔 Events

```php
use App\Events\Store\{
    StoreCreated,
    TransferCreated,
    TransferApproved,
    TransferCompleted,
    TransferCancelled
};

// Écouter un événement
Event::listen(TransferCompleted::class, function ($event) {
    $transfer = $event->transfer;
    // Logique métier
});

// Les événements sont dispatched automatiquement
// par les services/actions
```

---

## ⚠️ Exceptions

```php
use App\Exceptions\Store\{
    StoreNotFoundException,
    InsufficientStockForTransferException,
    InvalidTransferStatusException,
    SameStoreTransferException
};

try {
    $storeService->findStore(999);
} catch (StoreNotFoundException $e) {
    // Magasin introuvable
}

try {
    $transferService->approveTransfer($id, $userId);
} catch (InsufficientStockForTransferException $e) {
    // Stock insuffisant
} catch (InvalidTransferStatusException $e) {
    // Statut invalide pour cette action
}
```

---

## 🔍 Repositories

Pour des requêtes personnalisées:

```php
use App\Repositories\{StoreRepository, StoreTransferRepository};

$storeRepo = app(StoreRepository::class);
$transferRepo = app(StoreTransferRepository::class);

// Exemples
$stores = $storeRepo->paginate(15);
$store = $storeRepo->findByCode('MAG-001');
$nextCode = $storeRepo->generateNextCode();
$transfers = $transferRepo->paginate(
    perPage: 15,
    fromStoreId: 1,
    status: 'pending'
);
```

---

## 💡 Bonnes Pratiques

### 1. Toujours utiliser les Services

```php
// ✅ BON
$storeService->addStockToStore($storeId, $variantId, 100);

// ❌ MAUVAIS
$stock = StoreStock::where(...)->first();
$stock->quantity += 100;
$stock->save();
```

### 2. Gérer les Exceptions

```php
try {
    $transfer = $transferService->approveTransfer($id, $userId);
    return redirect()->back()->with('success', 'Transfert approuvé');
} catch (\Exception $e) {
    return redirect()->back()->with('error', $e->getMessage());
}
```

### 3. Utiliser les DTOs pour la validation

```php
use App\Dtos\Store\CreateStoreDto;

$dto = CreateStoreDto::fromArray($request->validated());
$store = app(CreateStoreAction::class)->execute($dto);
```

### 4. Charger les relations nécessaires

```php
// ✅ BON - Eager loading
$transfers = StoreTransfer::with(['fromStore', 'toStore', 'items'])->get();

// ❌ MAUVAIS - N+1 queries
$transfers = StoreTransfer::all();
foreach ($transfers as $transfer) {
    echo $transfer->fromStore->name; // N+1
}
```

---

## 📊 Exemples Complets

### Workflow complet de transfert

```php
use App\Services\StoreTransferService;

$transferService = app(StoreTransferService::class);

// 1. Créer le transfert
$transfer = $transferService->createTransfer([
    'from_store_id' => 1,
    'to_store_id' => 2,
    'items' => [
        ['product_variant_id' => 10, 'quantity' => 50],
        ['product_variant_id' => 15, 'quantity' => 30],
    ],
    'requested_by' => auth()->id(),
]);

// 2. Approuver (manager magasin source)
$transfer = $transferService->approveTransfer($transfer->id, $managerId);

// 3. Recevoir (staff magasin destination)
$transfer = $transferService->receiveTransfer(
    $transfer->id,
    [10 => 50, 15 => 30], // Toutes les quantités reçues
    $receiverId
);

// Le transfert est maintenant 'completed'
// Les stocks sont mis à jour automatiquement
```

### Créer un magasin et assigner du personnel

```php
use App\Services\StoreService;

$storeService = app(StoreService::class);

// Créer le magasin
$store = $storeService->createStore([
    'name' => 'Boutique Nouvelle',
    'address' => 'Adresse',
    'is_active' => true,
]);

// Assigner le manager
$storeService->assignUserToStore($store->id, $managerId, 'manager', true);

// Assigner des caissiers
$storeService->assignUserToStore($store->id, $cashier1Id, 'cashier');
$storeService->assignUserToStore($store->id, $cashier2Id, 'cashier');

// Assigner du personnel
$storeService->assignUserToStore($store->id, $staffId, 'staff');
```

---

**Documentation API Version 1.0**  
**Date:** 5 janvier 2026  
**Auteur:** GitHub Copilot
