<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductType;
use App\Models\ProductAttribute;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create "Vêtements" product type (for backward compatibility)
        $vetements = ProductType::create([
            'name' => 'Vêtements',
            'slug' => 'vetements',
            'icon' => '👕',
            'description' => 'Vêtements et accessoires de mode',
            'has_variants' => true,
            'has_expiry_date' => false,
            'has_weight' => false,
            'has_dimensions' => false,
            'has_serial_number' => false,
            'is_active' => true,
            'display_order' => 1,
        ]);

        // Create attributes for "Vêtements"
        ProductAttribute::create([
            'product_type_id' => $vetements->id,
            'name' => 'Taille',
            'code' => 'size',
            'type' => 'select',
            'options' => ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'],
            'unit' => null,
            'default_value' => null,
            'is_required' => true,
            'is_variant_attribute' => true,
            'is_filterable' => true,
            'is_visible' => true,
            'display_order' => 1,
        ]);

        ProductAttribute::create([
            'product_type_id' => $vetements->id,
            'name' => 'Couleur',
            'code' => 'color',
            'type' => 'color',
            'options' => ['Noir', 'Blanc', 'Rouge', 'Bleu', 'Vert', 'Jaune', 'Rose', 'Gris', 'Marron', 'Orange'],
            'unit' => null,
            'default_value' => null,
            'is_required' => true,
            'is_variant_attribute' => true,
            'is_filterable' => true,
            'is_visible' => true,
            'display_order' => 2,
        ]);

        ProductAttribute::create([
            'product_type_id' => $vetements->id,
            'name' => 'Matière',
            'code' => 'material',
            'type' => 'select',
            'options' => ['Coton', 'Polyester', 'Lin', 'Soie', 'Laine', 'Cuir', 'Jean', 'Viscose'],
            'unit' => null,
            'default_value' => null,
            'is_required' => false,
            'is_variant_attribute' => false,
            'is_filterable' => true,
            'is_visible' => true,
            'display_order' => 3,
        ]);

        ProductAttribute::create([
            'product_type_id' => $vetements->id,
            'name' => 'Genre',
            'code' => 'gender',
            'type' => 'select',
            'options' => ['Homme', 'Femme', 'Mixte', 'Enfant'],
            'unit' => null,
            'default_value' => null,
            'is_required' => false,
            'is_variant_attribute' => false,
            'is_filterable' => true,
            'is_visible' => true,
            'display_order' => 4,
        ]);

        // Create "Alimentaire" product type
        $alimentaire = ProductType::create([
            'name' => 'Alimentaire',
            'slug' => 'alimentaire',
            'icon' => '🍎',
            'description' => 'Produits alimentaires et boissons',
            'has_variants' => false,
            'has_expiry_date' => true,
            'has_weight' => true,
            'has_dimensions' => false,
            'has_serial_number' => false,
            'is_active' => true,
            'display_order' => 2,
        ]);

        ProductAttribute::create([
            'product_type_id' => $alimentaire->id,
            'name' => 'Poids Net',
            'code' => 'net_weight',
            'type' => 'number',
            'options' => null,
            'unit' => 'g',
            'default_value' => null,
            'is_required' => true,
            'is_variant_attribute' => false,
            'is_filterable' => false,
            'is_visible' => true,
            'display_order' => 1,
        ]);

        ProductAttribute::create([
            'product_type_id' => $alimentaire->id,
            'name' => 'Allergènes',
            'code' => 'allergens',
            'type' => 'select',
            'options' => ['Gluten', 'Lactose', 'Arachides', 'Fruits à coque', 'Œufs', 'Soja', 'Poisson', 'Crustacés', 'Aucun'],
            'unit' => null,
            'default_value' => 'Aucun',
            'is_required' => true,
            'is_variant_attribute' => false,
            'is_filterable' => true,
            'is_visible' => true,
            'display_order' => 2,
        ]);

        ProductAttribute::create([
            'product_type_id' => $alimentaire->id,
            'name' => 'Bio',
            'code' => 'is_organic',
            'type' => 'boolean',
            'options' => null,
            'unit' => null,
            'default_value' => 'false',
            'is_required' => false,
            'is_variant_attribute' => false,
            'is_filterable' => true,
            'is_visible' => true,
            'display_order' => 3,
        ]);

        ProductAttribute::create([
            'product_type_id' => $alimentaire->id,
            'name' => 'Origine',
            'code' => 'origin',
            'type' => 'text',
            'options' => null,
            'unit' => null,
            'default_value' => null,
            'is_required' => false,
            'is_variant_attribute' => false,
            'is_filterable' => true,
            'is_visible' => true,
            'display_order' => 4,
        ]);

        // Create "Électronique" product type
        $electronique = ProductType::create([
            'name' => 'Électronique',
            'slug' => 'electronique',
            'icon' => '📱',
            'description' => 'Appareils électroniques et accessoires',
            'has_variants' => true,
            'has_expiry_date' => false,
            'has_weight' => false,
            'has_dimensions' => true,
            'has_serial_number' => true,
            'is_active' => true,
            'display_order' => 3,
        ]);

        ProductAttribute::create([
            'product_type_id' => $electronique->id,
            'name' => 'Capacité de stockage',
            'code' => 'storage_capacity',
            'type' => 'select',
            'options' => ['16GB', '32GB', '64GB', '128GB', '256GB', '512GB', '1TB', '2TB'],
            'unit' => null,
            'default_value' => null,
            'is_required' => false,
            'is_variant_attribute' => true,
            'is_filterable' => true,
            'is_visible' => true,
            'display_order' => 1,
        ]);

        ProductAttribute::create([
            'product_type_id' => $electronique->id,
            'name' => 'Couleur',
            'code' => 'color',
            'type' => 'select',
            'options' => ['Noir', 'Blanc', 'Argent', 'Or', 'Bleu', 'Rouge', 'Vert', 'Rose'],
            'unit' => null,
            'default_value' => null,
            'is_required' => false,
            'is_variant_attribute' => true,
            'is_filterable' => true,
            'is_visible' => true,
            'display_order' => 2,
        ]);

        ProductAttribute::create([
            'product_type_id' => $electronique->id,
            'name' => 'RAM',
            'code' => 'ram',
            'type' => 'select',
            'options' => ['2GB', '4GB', '6GB', '8GB', '12GB', '16GB', '32GB'],
            'unit' => null,
            'default_value' => null,
            'is_required' => false,
            'is_variant_attribute' => true,
            'is_filterable' => true,
            'is_visible' => true,
            'display_order' => 3,
        ]);

        ProductAttribute::create([
            'product_type_id' => $electronique->id,
            'name' => 'Garantie',
            'code' => 'warranty',
            'type' => 'select',
            'options' => ['6 mois', '1 an', '2 ans', '3 ans'],
            'unit' => null,
            'default_value' => '1 an',
            'is_required' => true,
            'is_variant_attribute' => false,
            'is_filterable' => false,
            'is_visible' => true,
            'display_order' => 4,
        ]);

        ProductAttribute::create([
            'product_type_id' => $electronique->id,
            'name' => 'Tension d\'alimentation',
            'code' => 'voltage',
            'type' => 'select',
            'options' => ['110V', '220V', '110-240V'],
            'unit' => null,
            'default_value' => '220V',
            'is_required' => false,
            'is_variant_attribute' => false,
            'is_filterable' => false,
            'is_visible' => true,
            'display_order' => 5,
        ]);

        $this->command->info('Product types and attributes seeded successfully!');
    }
}
