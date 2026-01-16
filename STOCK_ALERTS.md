# Système d'Alertes de Stock

## Vue d'ensemble

Système complet de gestion des alertes de stock avec détection automatique des niveaux bas et ruptures de stock.

## Fonctionnalités

### 1. **Seuils de Stock Configurables**
Chaque variante de produit dispose de deux seuils :
- `low_stock_threshold` : Seuil de stock bas (par défaut : 10)
- `min_stock_threshold` : Seuil minimum/rupture (par défaut : 0)

### 2. **Détection Automatique**
Le système vérifie automatiquement l'état du stock :
- **In Stock** : `stock_quantity > low_stock_threshold`
- **Low Stock** : `stock_quantity <= low_stock_threshold && stock_quantity > 0`
- **Out of Stock** : `stock_quantity <= min_stock_threshold`

### 3. **Events & Listeners**
- `LowStockAlert` : Déclenché quand le stock est bas
- `OutOfStockAlert` : Déclenché quand le stock est épuisé
- Les listeners enregistrent les alertes dans les logs

## Utilisation

### Via Service

```php
use App\Services\StockAlertService;

$alertService = app(StockAlertService::class);

// Vérifier tous les niveaux de stock
$results = $alertService->checkStockLevels();
// Returns: ['low_stock_count', 'out_of_stock_count', 'low_stock_variants', 'out_of_stock_variants']

// Obtenir le résumé des alertes
$summary = $alertService->getAlertsSummary();

// Vérifier une variante spécifique
$status = $alertService->checkVariant($variantId);
// Returns: 'in_stock', 'low_stock', or 'out_of_stock'

// Mettre à jour les seuils
$variant = $alertService->updateThresholds($variantId, lowThreshold: 15, minThreshold: 2);

// Mise à jour en masse
$results = $alertService->bulkUpdateThresholds([
    ['variant_id' => 1, 'low_threshold' => 20, 'min_threshold' => 5],
    ['variant_id' => 2, 'low_threshold' => 15, 'min_threshold' => 3],
]);
```

### Via Commande Artisan

```bash
# Vérifier et afficher les alertes (mode détaillé)
php artisan stock:check-alerts

# Afficher uniquement le résumé
php artisan stock:check-alerts --summary
```

### Via Job (Automatisation)

```php
use App\Jobs\CheckStockLevels;

// Dispatcher le job
CheckStockLevels::dispatch();

// Planifier dans app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    // Vérifier toutes les heures
    $schedule->job(new CheckStockLevels)->hourly();
    
    // Ou toutes les 30 minutes
    $schedule->job(new CheckStockLevels)->everyThirtyMinutes();
}
```

### Dans le Code (Automatic)

```php
use App\Models\ProductVariant;

$variant = ProductVariant::find(1);

// Vérifier l'état
if ($variant->isLowStock()) {
    // Stock faible
}

if ($variant->isOutOfStock()) {
    // Rupture de stock
}

// Obtenir le statut
$status = $variant->stock_status; // 'in_stock', 'low_stock', 'out_of_stock'

// Obtenir le pourcentage de stock
$percentage = $variant->stock_level_percentage; // 0-100
```

## Méthodes ProductVariant

```php
// Vérification
$variant->isLowStock(): bool
$variant->isOutOfStock(): bool
$variant->hasStock(int $quantity): bool

// Attributs calculés
$variant->stock_status // string
$variant->stock_level_percentage // float (0-100)

// Modification de stock (déclenche automatiquement les events)
$variant->increaseStock(10);
$variant->decreaseStock(5);
```

## Configuration des Events

### Personnaliser les Listeners

Dans `app/Providers/EventServiceProvider.php` :

```php
protected $listen = [
    LowStockAlert::class => [
        LogLowStockAlert::class,
        SendLowStockEmail::class,    // À créer
        SendLowStockSMS::class,       // À créer
        CreateInAppNotification::class, // À créer
    ],
    OutOfStockAlert::class => [
        LogOutOfStockAlert::class,
        SendUrgentStockEmail::class,  // À créer
        NotifyManagers::class,         // À créer
        DisableProductOnWebsite::class, // À créer
    ],
];
```

## Automatisation

### Ajouter au Scheduler

Dans `app/Console/Kernel.php` :

```php
protected function schedule(Schedule $schedule)
{
    // Vérifier les niveaux de stock toutes les heures
    $schedule->job(new \App\Jobs\CheckStockLevels)->hourly();
    
    // Rapport quotidien
    $schedule->command('stock:check-alerts --summary')
        ->dailyAt('08:00')
        ->emailOutputTo('manager@example.com');
}
```

## Logs

Les alertes sont enregistrées dans `storage/logs/laravel.log` :

```
[2025-12-18 15:30:00] local.WARNING: Low Stock Alert {"variant_id":5,"product":"T-Shirt","variant":"T-Shirt - Rouge - M","current_stock":8,"threshold":10}

[2025-12-18 15:30:00] local.CRITICAL: Out of Stock Alert {"variant_id":12,"product":"Pantalon","variant":"Pantalon - Bleu - L","current_stock":0}
```

## API Response Examples

```json
// GET /api/stock/alerts
{
  "total_alerts": 5,
  "low_stock": {
    "count": 3,
    "variants": [
      {
        "id": 5,
        "product": "T-Shirt",
        "variant": "T-Shirt - Rouge - M",
        "current_stock": 8,
        "threshold": 10,
        "status": "low_stock"
      }
    ]
  },
  "out_of_stock": {
    "count": 2,
    "variants": [
      {
        "id": 12,
        "product": "Pantalon",
        "variant": "Pantalon - Bleu - L",
        "current_stock": 0,
        "status": "out_of_stock"
      }
    ]
  }
}
```

## Migration

Pour appliquer les nouveaux champs :

```bash
php artisan migrate
```

Cela ajoute :
- `low_stock_threshold` (default: 10)
- `min_stock_threshold` (default: 0)

## Prochaines Étapes

1. **Notifications Email** : Créer listener pour envoyer emails
2. **Notifications SMS** : Intégrer service SMS (Twilio, etc.)
3. **Dashboard** : Interface admin pour voir les alertes
4. **Notifications In-App** : Système de notifications dans l'application
5. **Historique** : Table pour stocker l'historique des alertes
6. **Configuration** : Interface pour configurer les seuils globaux

## Tests

```php
use App\Services\StockAlertService;
use App\Models\ProductVariant;

class StockAlertTest extends TestCase
{
    public function test_low_stock_alert()
    {
        $variant = ProductVariant::factory()->create([
            'stock_quantity' => 5,
            'low_stock_threshold' => 10,
        ]);
        
        $this->assertTrue($variant->isLowStock());
        $this->assertEquals('low_stock', $variant->stock_status);
    }
    
    public function test_check_stock_levels()
    {
        $service = app(StockAlertService::class);
        $results = $service->checkStockLevels();
        
        $this->assertArrayHasKey('low_stock_count', $results);
        $this->assertArrayHasKey('out_of_stock_count', $results);
    }
}
```
