# Service Providers Documentation

## Vue d'ensemble

Le système utilise plusieurs Service Providers pour organiser l'enregistrement des dépendances dans le conteneur IoC de Laravel.

## Service Providers

### 1. **RepositoryServiceProvider**
Enregistre tous les Repository comme **singletons** pour optimiser les performances.

**Repositories enregistrés:**
- CategoryRepository
- ProductRepository
- ProductVariantRepository
- ClientRepository
- SupplierRepository
- PurchaseRepository
- SaleRepository
- StockMovementRepository
- InvoiceRepository

**Pourquoi Singleton?**
Les repositories ne maintiennent pas d'état entre les requêtes et peuvent être réutilisés, ce qui réduit l'overhead de création d'objets.

---

### 2. **BusinessServiceProvider**
Enregistre tous les Services métier comme **singletons**.

**Services enregistrés:**
- CategoryService
- ProductService
- ClientService
- SupplierService
- PurchaseService
- SaleService
- StockService
- InvoiceService

**Avantages:**
- Injection automatique des dépendances
- Performance optimale avec les singletons
- Facilite les tests unitaires (mocking)

---

### 3. **ActionServiceProvider**
Enregistre toutes les Actions comme **singletons**.

**Categories d'Actions:**
- **Category:** Create, Update, Delete
- **Product:** Create, Update, Delete, CreateVariant, UpdateVariant, DeleteVariant, Import
- **Client:** Create, Update, Delete
- **Supplier:** Create, Update, Delete
- **Purchase:** Create, Update, Delete
- **Sale:** Create, Update, Delete, Process, Refund
- **Stock:** Add, Remove, Adjust, BulkUpdate, PerformInventory
- **Invoice:** Create, Update, Delete
- **Report:** GenerateSales, GenerateStock

**Note:** Les Actions sont stateless et peuvent être réutilisées entre requêtes.

---

### 4. **EventServiceProvider** (Optionnel)
Configure les observers et événements Eloquent.

**Fonctionnalités:**
- `Model::preventLazyLoading()` en développement (détecte les N+1 queries)
- `Model::preventSilentlyDiscardingAttributes()` (sécurité)
- Point d'entrée pour enregistrer des Observers

---

## Configuration

Les Service Providers sont enregistrés dans `bootstrap/providers.php`:

```php
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\VoltServiceProvider::class,
    App\Providers\RepositoryServiceProvider::class,
    App\Providers\BusinessServiceProvider::class,
    App\Providers\ActionServiceProvider::class,
    // App\Providers\EventServiceProvider::class, // Optionnel
];
```

## Utilisation

### Dans les Controllers

```php
use App\Actions\Sale\ProcessSaleAction;
use App\Services\ProductService;

class SaleController extends Controller
{
    public function __construct(
        private ProcessSaleAction $processSaleAction,
        private ProductService $productService
    ) {}

    public function store(Request $request)
    {
        // Les dépendances sont automatiquement injectées
        $result = $this->processSaleAction->execute($request->validated());
        return response()->json($result);
    }
}
```

### Dans les Tests

```php
use App\Services\SaleService;

class SaleServiceTest extends TestCase
{
    public function test_create_sale()
    {
        $service = $this->app->make(SaleService::class);
        // ou
        $service = app(SaleService::class);
        
        // Test...
    }
}
```

## Avantages de cette Architecture

### 1. **Séparation des Responsabilités**
- Repositories: Accès aux données
- Services: Logique métier
- Actions: Orchestration des cas d'usage

### 2. **Testabilité**
```php
// Mock facile dans les tests
$this->mock(ProductRepository::class, function ($mock) {
    $mock->shouldReceive('find')->once()->andReturn($product);
});
```

### 3. **Performance**
- Singletons réduisent l'overhead de création d'objets
- Résolution automatique des dépendances par Laravel

### 4. **Maintenabilité**
- Point central pour voir toutes les dépendances
- Facilite les refactorings
- Documentation vivante de l'architecture

## Commandes Utiles

```bash
# Vider le cache des services
php artisan optimize:clear

# Reconstruire le cache (production)
php artisan optimize

# Lister tous les bindings
php artisan tinker
>>> app()->getBindings()
```

## Notes Importantes

1. **Les Repositories et Services sont Singletons**: Assurez-vous qu'ils ne maintiennent pas d'état mutable entre les requêtes.

2. **Les Actions sont Stateless**: Elles doivent recevoir toutes les données via les paramètres de `execute()`.

3. **Ajout de Nouvelles Classes**: N'oubliez pas de les enregistrer dans le Service Provider correspondant.

4. **Tests**: Les Service Providers sont automatiquement chargés dans les tests, facilitant le mocking.
