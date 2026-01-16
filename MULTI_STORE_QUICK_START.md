# ⚡ Multi-Magasins - Quick Start

Guide ultra-rapide pour démarrer avec le module multi-magasins.

---

## 🚀 Installation (3 commandes)

```bash
# 1. Migrations
php artisan migrate

# 2. Créer les magasins
php artisan db:seed --class=StoreSeeder

# 3. Migrer les données
php artisan db:seed --class=StoreStockSeeder
php artisan db:seed --class=MigrateDataToMainStoreSeeder
```

**C'est tout ! ✅**

---

## 💡 Utilisation Basique

### Dans votre code Livewire/Controller

```php
use App\Services\StoreService;
use App\Services\StoreTransferService;

class YourComponent extends Component
{
    public function __construct(
        private StoreService $storeService,
        private StoreTransferService $transferService
    ) {}

    public function render()
    {
        // Magasin actuel de l'utilisateur
        $currentStore = auth()->user()->currentStore;
        
        // Tous les magasins
        $stores = $this->storeService->getAllStores();
        
        return view('...', compact('currentStore', 'stores'));
    }
}
```

---

## 🏪 Opérations Courantes

### Créer un magasin

```php
$store = $storeService->createStore([
    'name' => 'Boutique Centre-Ville',
    'address' => '123 Avenue',
    'phone' => '+243 XXX',
]);
```

### Changer de magasin

```php
$storeService->switchUserStore(auth()->id(), $newStoreId);
```

### Vérifier le stock dans un magasin

```php
$hasStock = $storeService->checkStockAvailability(
    storeId: $currentStoreId,
    variantId: $variantId,
    requiredQuantity: 10
);
```

### Créer un transfert

```php
$transfer = $transferService->createTransfer([
    'from_store_id' => 1,
    'to_store_id' => 2,
    'items' => [
        ['product_variant_id' => 10, 'quantity' => 50],
    ],
    'requested_by' => auth()->id(),
]);
```

### Approuver un transfert

```php
$transferService->approveTransfer($transferId, auth()->id());
```

### Recevoir un transfert

```php
$transferService->receiveTransfer(
    $transferId,
    [10 => 50], // variant_id => quantité reçue
    auth()->id()
);
```

---

## 📊 Affichage dans les Vues

### Blade - Magasin actuel

```blade
@if(auth()->user()->currentStore)
    <div>
        Magasin: {{ auth()->user()->currentStore->name }}
    </div>
@endif
```

### Blade - Liste des magasins

```blade
@foreach($stores as $store)
    <div>
        {{ $store->name }} - {{ $store->code }}
        @if($store->isMain())
            <span class="badge">Principal</span>
        @endif
    </div>
@endforeach
```

### Livewire - Sélecteur de magasin

```blade
<select wire:model="storeId">
    @foreach($stores as $store)
        <option value="{{ $store->id }}">{{ $store->name }}</option>
    @endforeach
</select>
```

---

## 🔄 Workflow Transfert (Résumé)

```
1. CRÉER
   └─> Status: pending

2. APPROUVER
   └─> Status: in_transit
   └─> Stock retiré du magasin source

3. RECEVOIR
   └─> Status: completed
   └─> Stock ajouté au magasin destination

OU ANNULER (à toute étape)
   └─> Status: cancelled
   └─> Stock restauré si déjà retiré
```

---

## 🎯 Routes Disponibles

```php
// Magasins
Route::get('/stores', ...)->name('stores.index');
Route::get('/stores/create', ...)->name('stores.create');
Route::get('/stores/{id}', ...)->name('stores.show');
Route::post('/stores/switch/{store}', ...)->name('stores.switch');

// Transferts
Route::get('/transfers', ...)->name('transfers.index');
Route::get('/transfers/create', ...)->name('transfers.create');
Route::get('/transfers/{id}', ...)->name('transfers.show');
Route::post('/transfers/{id}/approve', ...)->name('transfers.approve');
Route::post('/transfers/{id}/receive', ...)->name('transfers.receive');
Route::post('/transfers/{id}/cancel', ...)->name('transfers.cancel');
```

---

## 🔍 Debugging

### Vérifier l'installation

```bash
php artisan tinker
```

```php
// Compter les magasins
App\Models\Store::count(); // Devrait être >= 1

// Voir le magasin principal
App\Models\Store::where('is_main', true)->first();

// Vérifier les stocks
App\Models\StoreStock::count(); // Devrait être > 0

// Vérifier l'utilisateur
$user = App\Models\User::first();
$user->currentStore; // Ne devrait pas être null
$user->stores; // Devrait contenir au moins 1 magasin
```

### Problème : Utilisateur sans magasin

```php
use App\Services\StoreService;

$storeService = app(StoreService::class);
$mainStore = $storeService->getOrCreateMainStore();
$storeService->assignUserToStore($mainStore->id, $userId, 'staff', true);
```

---

## 📚 Accès Rapide Documentation

- **Installation complète:** `INSTALLATION_MULTI_STORE.md`
- **Guide implémentation:** `MULTI_STORE_IMPLEMENTATION.md`
- **API développeur:** `MULTI_STORE_API_GUIDE.md`

---

## ⚡ Commandes Utiles

```bash
# Voir les magasins
php artisan tinker --execute="App\Models\Store::all()->pluck('name')"

# Voir les transferts
php artisan tinker --execute="App\Models\StoreTransfer::count()"

# Réinitialiser (⚠️ Supprime tout)
php artisan migrate:fresh --seed
```

---

## 🎉 C'est Tout !

Vous êtes prêt à utiliser le module multi-magasins.

**Prochaine étape:** Créer les composants Livewire pour l'interface utilisateur.

---

**Quick Start Version 1.0**  
**5 janvier 2026**
