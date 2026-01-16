# Guide de Migration - Module Category Refactorisé

## 🚀 Démarrage Rapide

Le refactoring est **100% rétrocompatible**. Votre code existant continue de fonctionner sans modifications.

---

## ✅ Étapes Post-Refactoring

### 1. Vérifier que tout fonctionne

```bash
# Test rapide
php artisan test --filter=Category

# Si vous n'avez pas configuré PHPUnit, testez manuellement via l'interface
```

### 2. Enregistrer les Listeners (Déjà fait ✓)

Les événements sont déjà enregistrés dans `EventServiceProvider.php` :

```php
protected $listen = [
    CategoryCreated::class => [LogCategoryCreated::class],
    CategoryUpdated::class => [LogCategoryUpdated::class],
    CategoryDeleted::class => [LogCategoryDeleted::class],
];
```

### 3. Optionnel : Activer l'Observer

Si vous voulez activer le cache clearing automatique, dans `EventServiceProvider::boot()` :

```php
public function boot(): void
{
    // Existing code...
    
    \App\Models\Category::observe(\App\Observers\Category\CategoryObserver::class);
}
```

---

## 📖 Migration Progressive vers les DTOs

### Approche 1 : Continuer avec Arrays (Aucun changement requis)

```php
// Votre code existant fonctionne tel quel
$action->execute([
    'name' => 'New Category',
    'description' => 'Description'
]);
```

### Approche 2 : Adopter progressivement les DTOs

#### Dans vos contrôleurs/composants :

**Avant :**
```php
$createAction->execute($request->all());
```

**Après :**
```php
use App\Dtos\Category\CreateCategoryDto;

$dto = CreateCategoryDto::fromArray($request->validated());
$createAction->execute($dto);
```

**Avantages des DTOs :**
- ✅ Type safety
- ✅ Auto-completion dans l'IDE
- ✅ Validation des types à la compilation
- ✅ Documentation inline

---

## 🧪 Exécuter les Tests

### Tests Unitaires

```bash
# Tous les tests Category
php artisan test --filter=Category

# Tests spécifiques
php artisan test tests/Unit/Services/CategoryServiceTest.php
php artisan test tests/Unit/Models/CategoryTest.php
php artisan test tests/Unit/Actions/Category/

# Avec verbosité
php artisan test --filter=Category --testdox
```

### Configuration PHPUnit (si nécessaire)

Assurez-vous que `phpunit.xml` est configuré :

```xml
<phpunit>
    <testsuites>
        <testsuite name="Unit">
            <directory suffix="Test.php">./tests/Unit</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

---

## 🔧 Utilisation des Nouvelles Fonctionnalités

### 1. Scopes du Modèle

```php
use App\Models\Category;

// Catégories avec produits
$withProducts = Category::withProducts()->get();

// Catégories sans produits
$empty = Category::withoutProducts()->get();

// Recherche
$results = Category::search('electronics')->get();

// Top 10 catégories populaires
$popular = Category::popular(10)->get();

// Tri par nombre de produits
$sorted = Category::orderByProductCount('desc')->get();

// Combinaisons
$results = Category::search('phone')
    ->withProducts()
    ->orderByProductCount()
    ->get();
```

### 2. Méthodes Métiers du Modèle

```php
$category = Category::find(1);

// Vérifier si elle a des produits
if ($category->hasProducts()) {
    echo "Contains products";
}

// Nombre de produits
$count = $category->getProductsCount();

// Peut être supprimée ?
if ($category->canBeDeleted()) {
    $category->delete();
}

// Produits actifs uniquement
$activeProducts = $category->getActiveProducts();

// URL de la catégorie
$url = $category->getUrl();
```

### 3. Accessors

```php
$category = Category::find(1);

// Nom formaté (première lettre en majuscule)
echo $category->formatted_name;

// Description courte (100 caractères max)
echo $category->short_description;
```

### 4. Gestion des Exceptions

```php
use App\Exceptions\Category\CategoryNotFoundException;
use App\Exceptions\Category\CategoryHasProductsException;

try {
    $deleteAction->execute($categoryId);
} catch (CategoryNotFoundException $e) {
    // Catégorie introuvable
    return back()->with('error', 'Catégorie introuvable');
} catch (CategoryHasProductsException $e) {
    // A des produits associés
    return back()->with('error', $e->getMessage());
}
```

### 5. Écouter les Événements

Créez vos propres listeners :

```php
// app/Listeners/Category/SendCategoryCreatedNotification.php
namespace App\Listeners\Category;

use App\Events\Category\CategoryCreated;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCategoryCreatedNotification implements ShouldQueue
{
    public function handle(CategoryCreated $event): void
    {
        // Envoyer une notification aux admins
        // Notification::send($admins, new CategoryCreatedNotification($event->category));
    }
}
```

Enregistrez-le dans `EventServiceProvider` :

```php
protected $listen = [
    CategoryCreated::class => [
        LogCategoryCreated::class,
        SendCategoryCreatedNotification::class, // Nouveau
    ],
];
```

---

## 🎯 API REST (Optionnel)

Un contrôleur API a été créé. Pour l'activer, ajoutez dans `routes/api.php` :

```php
use App\Http\Controllers\Api\CategoryController;

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::get('/popular', [CategoryController::class, 'popular']);
    Route::get('/{id}', [CategoryController::class, 'show']);
    Route::put('/{id}', [CategoryController::class, 'update']);
    Route::delete('/{id}', [CategoryController::class, 'destroy']);
});
```

**Utilisation :**

```bash
# Lister les catégories
GET /api/categories

# Rechercher
GET /api/categories?search=electronic&per_page=20

# Catégories populaires
GET /api/categories/popular?limit=5

# Créer
POST /api/categories
{
    "name": "Electronics",
    "description": "Electronic devices"
}

# Voir une catégorie
GET /api/categories/1

# Mettre à jour
PUT /api/categories/1
{
    "name": "Updated Name"
}

# Supprimer
DELETE /api/categories/1
```

---

## 📊 Monitoring et Logs

### Vérifier les logs

```bash
# Logs de catégories
tail -f storage/logs/laravel.log | grep -i category

# Logs en temps réel
tail -f storage/logs/laravel.log
```

### Ce qui est logué automatiquement :

- ✅ Création de catégorie (ID, nom)
- ✅ Mise à jour (ID, champs modifiés)
- ✅ Suppression (ID, nom)
- ✅ Tentatives de suppression avec produits
- ✅ Erreurs de validation

---

## 🔄 FormRequests (Optionnel)

Des FormRequests ont été créés pour validation. Pour les utiliser :

**Dans votre contrôleur API :**

```php
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;

public function store(StoreCategoryRequest $request, CreateCategoryAction $action)
{
    $dto = CreateCategoryDto::fromArray($request->validated());
    $category = $action->execute($dto);
    
    return response()->json($category, 201);
}

public function update(UpdateCategoryRequest $request, int $id, UpdateCategoryAction $action)
{
    $dto = UpdateCategoryDto::fromArray($request->validated());
    $category = $action->execute($id, $dto);
    
    return response()->json($category);
}
```

---

## 🚨 Gestion des Erreurs Avancée

### Dans vos Controllers/Livewire :

```php
use App\Exceptions\Category\{
    CategoryNotFoundException,
    CategoryHasProductsException
};

try {
    $action->execute($data);
    
} catch (CategoryNotFoundException $e) {
    Log::warning('Category not found', ['id' => $id]);
    session()->flash('error', 'Catégorie introuvable');
    
} catch (CategoryHasProductsException $e) {
    Log::info('Cannot delete category', ['error' => $e->getMessage()]);
    session()->flash('error', $e->getMessage());
    
} catch (\Exception $e) {
    Log::error('Unexpected error', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    session()->flash('error', 'Une erreur inattendue est survenue');
}
```

---

## 📈 Améliorations Futures Recommandées

### 1. Cache

```php
// app/Services/CategoryCacheService.php
class CategoryCacheService
{
    public function remember(string $key, callable $callback, int $minutes = 60)
    {
        return Cache::tags(['categories'])->remember($key, $minutes * 60, $callback);
    }
    
    public function flush(): void
    {
        Cache::tags(['categories'])->flush();
    }
}

// Utilisation
$popular = $cacheService->remember('categories.popular', fn() => 
    Category::popular(10)->get()
);
```

### 2. Resources API

```php
// app/Http/Resources/CategoryResource.php
class CategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'products_count' => $this->whenLoaded('products', fn() => 
                $this->products->count()
            ),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
```

### 3. Feature Tests

```php
// tests/Feature/Category/CategoryManagementTest.php
class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function user_can_create_category()
    {
        $this->actingAs($user = User::factory()->create());
        
        Livewire::test(CategoryIndex::class)
            ->set('form.name', 'Test Category')
            ->call('save')
            ->assertHasNoErrors();
            
        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category'
        ]);
    }
}
```

---

## ❓ FAQ

### Q: Dois-je modifier mon code existant ?
**R:** Non ! Le refactoring est 100% rétrocompatible. Tout continue de fonctionner.

### Q: Comment migrer progressivement vers les DTOs ?
**R:** Commencez par les nouvelles fonctionnalités, puis migrez l'existant module par module.

### Q: Les événements ralentissent-ils l'application ?
**R:** Non, et les listeners implémentent `ShouldQueue` pour exécution asynchrone.

### Q: Puis-je désactiver certaines fonctionnalités ?
**R:** Oui ! Commentez les listeners dans `EventServiceProvider` ou n'utilisez pas les nouvelles classes.

### Q: Comment débugger les événements ?
**R:** Vérifiez `storage/logs/laravel.log` - tous les événements sont loggés.

---

## 📞 Support

Pour toute question ou problème :

1. Vérifiez les logs : `storage/logs/laravel.log`
2. Exécutez les tests : `php artisan test --filter=Category`
3. Consultez la documentation : `REFACTORING_CATEGORY.md`

---

**Bonne utilisation ! 🎉**
