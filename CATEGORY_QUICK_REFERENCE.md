# 📋 Aide-Mémoire Rapide - Module Category

## 🚀 Quick Start

```bash
# Tester le refactoring
php artisan test --filter=Category

# Voir les logs en temps réel
tail -f storage/logs/laravel.log | grep category
```

---

## 🔥 Exemples d'Utilisation Courants

### Créer une Catégorie

```php
use App\Actions\Category\CreateCategoryAction;
use App\Dtos\Category\CreateCategoryDto;

// Avec DTO (recommandé)
$dto = new CreateCategoryDto(
    name: 'Electronics',
    description: 'Electronic devices'
);
$category = app(CreateCategoryAction::class)->execute($dto);

// Avec array (rétrocompatible)
$category = app(CreateCategoryAction::class)->execute([
    'name' => 'Electronics',
    'description' => 'Electronic devices'
]);
```

### Mettre à Jour une Catégorie

```php
use App\Actions\Category\UpdateCategoryAction;
use App\Dtos\Category\UpdateCategoryDto;

$dto = new UpdateCategoryDto(
    name: 'Updated Name',
    description: 'New description'
);
$category = app(UpdateCategoryAction::class)->execute($categoryId, $dto);
```

### Supprimer une Catégorie

```php
use App\Actions\Category\DeleteCategoryAction;
use App\Exceptions\Category\{CategoryNotFoundException, CategoryHasProductsException};

try {
    app(DeleteCategoryAction::class)->execute($categoryId);
} catch (CategoryNotFoundException $e) {
    // Catégorie introuvable
} catch (CategoryHasProductsException $e) {
    // Catégorie contient des produits
}
```

---

## 🔍 Scopes Eloquent

```php
use App\Models\Category;

// Catégories avec produits
$categories = Category::withProducts()->get();

// Catégories sans produits
$empty = Category::withoutProducts()->get();

// Recherche
$results = Category::search('electronics')->get();

// Top 10 populaires
$popular = Category::popular(10)->get();

// Tri par nombre de produits
$sorted = Category::orderByProductCount('desc')->get();

// Combinaisons
$results = Category::search('phone')
    ->withProducts()
    ->orderByProductCount()
    ->limit(20)
    ->get();
```

---

## 🛠️ Méthodes du Modèle

```php
$category = Category::find(1);

// Vérifications
$hasProducts = $category->hasProducts();          // bool
$canDelete = $category->canBeDeleted();           // bool

// Compteurs
$count = $category->getProductsCount();           // int

// Relations
$activeProducts = $category->getActiveProducts(); // Collection

// Accessors
echo $category->formatted_name;                   // "Electronics"
echo $category->short_description;                // "Description tronquée..."
```

---

## 🎯 Gestion des Erreurs

```php
use App\Exceptions\Category\{
    CategoryNotFoundException,
    CategoryHasProductsException
};

try {
    // Votre code
    $action->execute($data);
    
} catch (CategoryNotFoundException $e) {
    session()->flash('error', 'Catégorie introuvable');
    
} catch (CategoryHasProductsException $e) {
    session()->flash('error', 'Cette catégorie contient des produits');
    
} catch (\Exception $e) {
    session()->flash('error', 'Une erreur est survenue');
}
```

---

## 📡 Événements

```php
use App\Events\Category\{CategoryCreated, CategoryUpdated, CategoryDeleted};

// Les événements sont dispatchés automatiquement !
// Créez vos listeners dans app/Listeners/Category/

// Exemple de listener personnalisé :
class NotifyAdminOnCategoryCreated implements ShouldQueue
{
    public function handle(CategoryCreated $event): void
    {
        // Notification aux admins
        Mail::to($admins)->send(new CategoryCreatedMail($event->category));
    }
}

// Enregistrez dans EventServiceProvider :
protected $listen = [
    CategoryCreated::class => [
        LogCategoryCreated::class,
        NotifyAdminOnCategoryCreated::class, // Votre listener
    ],
];
```

---

## 🧪 Tests

```bash
# Tous les tests
php artisan test --filter=Category

# Test spécifique
php artisan test tests/Unit/Services/CategoryServiceTest.php

# Avec détails
php artisan test --filter=Category --testdox

# Avec couverture
php artisan test --filter=Category --coverage
```

---

## 📊 Logs

```bash
# Voir les logs Category en temps réel
tail -f storage/logs/laravel.log | grep -i category

# Voir les logs d'événements
tail -f storage/logs/laravel.log | grep "Category created\|Category updated\|Category deleted"
```

---

## 🌐 API REST (si activée)

```bash
# Lister
GET /api/categories?search=electronics&per_page=20

# Populaires
GET /api/categories/popular?limit=10

# Détails
GET /api/categories/1

# Créer
POST /api/categories
{
    "name": "New Category",
    "description": "Description"
}

# Modifier
PUT /api/categories/1
{
    "name": "Updated Name"
}

# Supprimer
DELETE /api/categories/1
```

---

## 🔧 Commandes Utiles

```bash
# Vider le cache
php artisan cache:clear

# Vider les événements en queue
php artisan queue:work --once

# Regénérer l'autoload
composer dump-autoload

# Exécuter les migrations
php artisan migrate

# Seed des données de test
php artisan db:seed --class=CategorySeeder
```

---

## 📝 Fichiers Importants

| Fichier | Description |
|---------|-------------|
| [app/Models/Category.php](app/Models/Category.php) | Modèle avec scopes et méthodes |
| [app/Services/CategoryService.php](app/Services/CategoryService.php) | Logique métier |
| [app/Actions/Category/](app/Actions/Category/) | Actions CRUD |
| [app/Exceptions/Category/](app/Exceptions/Category/) | Exceptions personnalisées |
| [app/Events/Category/](app/Events/Category/) | Événements |
| [tests/Unit/](tests/Unit/) | Tests unitaires |

---

## ⚡ Raccourcis IDE (VS Code)

```
Ctrl+P → Category         # Rechercher fichiers Category
Ctrl+Shift+F → "Category" # Rechercher dans tous les fichiers
Ctrl+Click → Nom de classe # Aller à la définition
```

---

## 🐛 Debugging

```php
// Dans CategoryService
Log::debug('Category data', ['data' => $dto->toArray()]);

// Dans CategoryIndex Livewire
dd($this->form->toArray());

// Dans les tests
dump($category->toArray());
```

---

## 📖 Documentation Complète

- **[REFACTORING_CATEGORY.md](REFACTORING_CATEGORY.md)** - Documentation technique
- **[CATEGORY_MIGRATION_GUIDE.md](CATEGORY_MIGRATION_GUIDE.md)** - Guide de migration
- **[CATEGORY_REFACTORING_SUMMARY.md](CATEGORY_REFACTORING_SUMMARY.md)** - Récapitulatif complet

---

## ✅ Checklist Quotidienne

- [ ] Les tests passent : `php artisan test --filter=Category`
- [ ] Pas d'erreurs dans les logs
- [ ] Les événements sont dispatchés correctement
- [ ] La création/modification/suppression fonctionne
- [ ] Les exceptions sont bien gérées

---

**Imprimez cette page et gardez-la près de vous ! 📌**
