<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\ProductType;
use App\Models\Category;
use App\Models\Store;
use App\Services\ProductService;
use App\Repositories\ProductRepository;
use App\Repositories\ProductVariantRepository;
use App\Services\SkuGeneratorService;
use App\Services\QRCodeGeneratorService;
use App\Services\VariantGeneratorService;

echo "\n=== TEST PRODUCT TYPES SYSTEM ===\n\n";

// Get product types
$productTypes = ProductType::with('attributes')->get();
echo "✓ Product Types disponibles:\n";
foreach ($productTypes as $type) {
    echo "  {$type->icon} {$type->name} - {$type->attributes->count()} attributs\n";
}

// Get first category and store
$category = Category::first();
$store = Store::first();

if (!$category || !$store) {
    echo "\n❌ Erreur: Catégorie ou Magasin manquant\n";
    exit(1);
}

echo "\n✓ Catégorie: {$category->name}\n";
echo "✓ Magasin: {$store->name}\n";

// Initialize services
$productRepository = new ProductRepository();
$variantRepository = new ProductVariantRepository();
$skuGenerator = new SkuGeneratorService();
$qrCodeGenerator = new QRCodeGeneratorService();
$variantGeneratorService = new VariantGeneratorService();

$productService = new ProductService(
    $productRepository,
    $variantRepository,
    $skuGenerator,
    $qrCodeGenerator,
    $variantGeneratorService
);

echo "\n=== TEST 1: Création Produit Vêtement (T-shirt) ===\n";

$clothingType = ProductType::where('slug', 'vetements')->first();
if (!$clothingType) {
    echo "❌ Type 'Vêtements' non trouvé\n";
} else {
    echo "✓ Type trouvé: {$clothingType->icon} {$clothingType->name}\n";

    // Get variant attributes (Taille, Couleur)
    $sizeAttr = $clothingType->attributes->where('code', 'size')->first();
    $colorAttr = $clothingType->attributes->where('code', 'color')->first();

    if ($sizeAttr && $colorAttr) {
        echo "✓ Attributs variants: Taille, Couleur\n";

        // Create product with attributes
        $productData = [
            'name' => 'T-shirt Test',
            'reference' => 'TST-' . time(),
            'description' => 'T-shirt de test pour système multi-types',
            'category_id' => $category->id,
            'product_type_id' => $clothingType->id,
            'price' => 15000,
            'cost_price' => 8000,
            'status' => 'active',
            'stock_alert_threshold' => 5,
            'attributes' => [
                $sizeAttr->id => 'M, L, XL',
                $colorAttr->id => 'Rouge, Bleu, Noir',
            ]
        ];

        try {
            $product = $productService->createProduct($productData);
            echo "✓ Produit créé: #{$product->id} - {$product->name}\n";
            echo "✓ Type: {$product->productType->icon} {$product->productType->name}\n";
            echo "✓ Variants générés: {$product->variants->count()}\n";

            // Check attribute values
            $attributeValues = $product->attributeValues()->with('attribute')->get();
            echo "✓ Attributs sauvegardés: {$attributeValues->count()}\n";
            foreach ($attributeValues as $attrValue) {
                echo "  - {$attrValue->attribute->name}: {$attrValue->value}\n";
            }

            // Check variants
            echo "\n  Variants créés:\n";
            foreach ($product->variants as $variant) {
                $variantAttrs = $variant->attributeValues()->with('attribute')->get();
                $attrs = $variantAttrs->map(fn($av) => "{$av->attribute->name}: {$av->value}")->join(', ');
                echo "  - {$variant->sku}: {$attrs}\n";
            }
        } catch (\Exception $e) {
            echo "❌ Erreur création: {$e->getMessage()}\n";
            echo "   {$e->getFile()}:{$e->getLine()}\n";
        }
    }
}

echo "\n=== TEST 2: Création Produit Alimentaire (Yogurt) ===\n";

$foodType = ProductType::where('slug', 'alimentaire')->first();
if (!$foodType) {
    echo "❌ Type 'Alimentaire' non trouvé\n";
} else {
    echo "✓ Type trouvé: {$foodType->icon} {$foodType->name}\n";

    // Get attributes
    $weightAttr = $foodType->attributes->where('code', 'net_weight')->first();
    $allergensAttr = $foodType->attributes->where('code', 'allergens')->first();

    if ($weightAttr && $allergensAttr) {
        echo "✓ Attributs: Poids Net, Allergènes\n";

        $productData = [
            'name' => 'Yogurt Nature',
            'reference' => 'YOG-' . time(),
            'description' => 'Yogurt nature 125g',
            'category_id' => $category->id,
            'product_type_id' => $foodType->id,
            'price' => 500,
            'cost_price' => 300,
            'status' => 'active',
            'stock_alert_threshold' => 20,
            'attributes' => [
                $weightAttr->id => '125',
                $allergensAttr->id => 'Lactose',
            ]
        ];

        try {
            $product = $productService->createProduct($productData);
            echo "✓ Produit créé: #{$product->id} - {$product->name}\n";
            echo "✓ Type: {$product->productType->icon} {$product->productType->name}\n";
            echo "✓ Variants: {$product->variants->count()} (pas de variants attendus)\n";

            $attributeValues = $product->attributeValues()->with('attribute')->get();
            echo "✓ Attributs sauvegardés: {$attributeValues->count()}\n";
            foreach ($attributeValues as $attrValue) {
                echo "  - {$attrValue->attribute->name}: {$attrValue->value}\n";
            }
        } catch (\Exception $e) {
            echo "❌ Erreur création: {$e->getMessage()}\n";
            echo "   {$e->getFile()}:{$e->getLine()}\n";
        }
    }
}

echo "\n=== TEST 3: Création Produit Électronique (Smartphone) ===\n";

$electronicsType = ProductType::where('slug', 'electronique')->first();
if (!$electronicsType) {
    echo "❌ Type 'Électronique' non trouvé\n";
} else {
    echo "✓ Type trouvé: {$electronicsType->icon} {$electronicsType->name}\n";

    // Get attributes
    $storageAttr = $electronicsType->attributes->where('code', 'storage_capacity')->first();
    $warrantyAttr = $electronicsType->attributes->where('code', 'warranty')->first();
    $ramAttr = $electronicsType->attributes->where('code', 'ram')->first();

    if ($storageAttr && $warrantyAttr && $ramAttr) {
        echo "✓ Attributs: Capacité de stockage, Garantie, RAM\n";

        $productData = [
            'name' => 'Smartphone Test',
            'reference' => 'PHN-' . time(),
            'description' => 'Smartphone avec variants de stockage',
            'category_id' => $category->id,
            'product_type_id' => $electronicsType->id,
            'price' => 500000,
            'cost_price' => 350000,
            'status' => 'active',
            'stock_alert_threshold' => 3,
            'attributes' => [
                $storageAttr->id => '128GB, 256GB, 512GB',
                $warrantyAttr->id => '2 ans',
                $ramAttr->id => '8GB, 12GB',
            ]
        ];

        try {
            $product = $productService->createProduct($productData);
            echo "✓ Produit créé: #{$product->id} - {$product->name}\n";
            echo "✓ Type: {$product->productType->icon} {$product->productType->name}\n";
            echo "✓ Variants générés: {$product->variants->count()}\n";

            $attributeValues = $product->attributeValues()->with('attribute')->get();
            echo "✓ Attributs sauvegardés: {$attributeValues->count()}\n";
            foreach ($attributeValues as $attrValue) {
                echo "  - {$attrValue->attribute->name}: {$attrValue->value}\n";
            }

            echo "\n  Variants créés:\n";
            foreach ($product->variants as $variant) {
                $variantAttrs = $variant->attributeValues()->with('attribute')->get();
                $attrs = $variantAttrs->map(fn($av) => "{$av->attribute->name}: {$av->value}")->join(', ');
                echo "  - {$variant->sku}: {$attrs}\n";
            }
        } catch (\Exception $e) {
            echo "❌ Erreur création: {$e->getMessage()}\n";
            echo "   {$e->getFile()}:{$e->getLine()}\n";
        }
    }
}

echo "\n=== TEST 4: Vérification Affichage ===\n";

$products = Product::with(['productType', 'category', 'variants'])
    ->whereNotNull('product_type_id')
    ->latest()
    ->take(5)
    ->get();

echo "✓ Produits récents avec types:\n";
foreach ($products as $product) {
    $type = $product->productType ? "{$product->productType->icon} {$product->productType->name}" : "Sans type";
    echo "  #{$product->id} - {$product->name}\n";
    echo "    Type: {$type}\n";
    echo "    Catégorie: {$product->category->name}\n";
    echo "    Variants: {$product->variants->count()}\n";
}

echo "\n=== TESTS TERMINÉS ===\n\n";
