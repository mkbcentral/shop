# Génération Automatique de QR Codes Uniques

## Vue d'ensemble

Le système génère automatiquement des codes QR uniques pour chaque produit lors de sa création. Ces codes peuvent être utilisés pour l'identification rapide, le suivi d'inventaire et l'intégration avec des scanners de codes-barres.

## Format du QR Code

**Format standard:** `QR-XXXXXXXX`
- Préfixe: `QR-`
- 8 caractères alphanumériques aléatoires (A-Z, 0-9)
- Exemple: `QR-A3F7K9M2`

## Utilisation

### 1. Génération Automatique

Lors de la création d'un produit, un QR code unique est automatiquement généré si aucun n'est fourni :

```php
// Dans ProductService::createProduct()
$data['qr_code'] = $this->qrCodeGenerator->generateForProduct();
```

### 2. Méthodes Disponibles

Le service `QRCodeGeneratorService` offre plusieurs méthodes :

#### Génération Standard
```php
$qrCodeGenerator = app(QRCodeGeneratorService::class);
$qrCode = $qrCodeGenerator->generateForProduct();
// Retourne: QR-A3F7K9M2
```

#### Génération avec Préfixe Personnalisé
```php
$qrCode = $qrCodeGenerator->generateWithPrefix('PROD', 10);
// Retourne: PROD-AB12CD34EF
```

#### Génération Numérique
```php
$qrCode = $qrCodeGenerator->generateNumeric(8);
// Retourne: QR-12345678
```

#### Génération Basée sur la Référence
```php
$qrCode = $qrCodeGenerator->generateFromReference('TSH-001');
// Retourne: QR-TSH001-A3F7
```

#### Validation du Format
```php
$isValid = $qrCodeGenerator->isValid('QR-A3F7K9M2');
// Retourne: true ou false
```

## Intégration dans l'Application

### Base de Données

Le champ `qr_code` a été ajouté à la table `products` :
- Type: `VARCHAR(50)`
- Unique: Oui
- Index: Oui
- Nullable: Oui (pour les produits existants)

### Modèle Product

```php
// Le champ est ajouté dans $fillable
protected $fillable = [
    // ...
    'qr_code',
    // ...
];
```

### Affichage dans les Vues

Pour afficher le QR code dans une vue :

```blade
<div>
    <strong>QR Code:</strong> {{ $product->qr_code }}
</div>
```

### Génération d'Image QR Code

Pour générer une image de QR code scannable, installez un package comme `simple-qrcode` :

```bash
composer require simplesoftwareio/simple-qrcode
```

Puis dans votre vue :

```blade
{!! QrCode::size(200)->generate($product->qr_code) !!}
```

## Caractéristiques

✅ **Unicité Garantie** - Vérification en base de données avant génération  
✅ **Format Cohérent** - Tous les codes suivent le même format  
✅ **Indexé** - Recherche rapide en base de données  
✅ **Personnalisable** - Plusieurs méthodes de génération disponibles  
✅ **Validable** - Méthode pour vérifier le format  

## Cas d'Usage

1. **Inventaire** - Scan rapide pour identifier les produits
2. **Point de Vente** - Ajout rapide au panier via scan
3. **Traçabilité** - Suivi du mouvement des produits
4. **Étiquettes** - Impression sur les étiquettes produits
5. **Application Mobile** - Scan via smartphone

## Migration des Produits Existants

Pour générer des QR codes pour les produits existants sans QR code :

```bash
php artisan tinker
```

```php
use App\Services\QRCodeGeneratorService;
use App\Models\Product;

$generator = app(QRCodeGeneratorService::class);

Product::whereNull('qr_code')->chunk(100, function($products) use ($generator) {
    foreach ($products as $product) {
        $product->update(['qr_code' => $generator->generateForProduct()]);
    }
});
```

## Exemple Complet

```php
// Créer un produit avec QR code automatique
$product = Product::create([
    'name' => 'T-shirt Bleu',
    'reference' => 'TSH-001',
    'price' => 2500,
    'cost_price' => 1500,
    'category_id' => 1,
    // qr_code sera généré automatiquement
]);

// Le QR code est maintenant disponible
echo $product->qr_code; // QR-A3F7K9M2

// Rechercher un produit par QR code
$product = Product::where('qr_code', 'QR-A3F7K9M2')->first();
```

## Notes Importantes

- Le QR code est généré **uniquement lors de la création** du produit
- Si vous fournissez un `qr_code` personnalisé, il doit être unique
- La modification d'un produit ne régénère **pas** le QR code
- Le champ est **nullable** pour supporter les produits existants
