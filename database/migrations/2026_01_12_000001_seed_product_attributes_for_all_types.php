<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Récupérer les IDs des types de produits
        $vetementId = DB::table('product_types')->where('name', 'Vetement')->value('id');
        $alimentaireId = DB::table('product_types')->where('name', 'Alimentaire')->value('id');
        $electroniqueId = DB::table('product_types')->where('name', 'Electronique')->value('id');

        // Helper function pour créer un attribut
        $createAttribute = function($data) {
            return DB::table('product_attributes')->insert([
                'product_type_id' => $data['product_type_id'],
                'name' => $data['name'],
                'code' => $data['code'],
                'type' => $data['type'],
                'options' => $data['options'] ?? null,
                'unit' => $data['unit'] ?? null,
                'default_value' => $data['default_value'] ?? null,
                'is_required' => $data['is_required'] ?? false,
                'is_variant_attribute' => $data['is_variant_attribute'] ?? false,
                'is_filterable' => $data['is_filterable'] ?? false,
                'is_visible' => $data['is_visible'] ?? true,
                'display_order' => $data['display_order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        };

        // ============================================================
        // VÊTEMENTS - Attributs
        // ============================================================
        if ($vetementId) {
            $createAttribute([
                'product_type_id' => $vetementId,
                'name' => 'Taille',
                'code' => 'taille',
                'type' => 'select',
                'options' => json_encode(['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL']),
                'is_required' => true,
                'is_variant_attribute' => true,
                'is_filterable' => true,
                'display_order' => 1,
            ]);

            $createAttribute([
                'product_type_id' => $vetementId,
                'name' => 'Couleur',
                'code' => 'couleur',
                'type' => 'color',
                'is_required' => true,
                'is_variant_attribute' => true,
                'is_filterable' => true,
                'display_order' => 2,
            ]);

            $createAttribute([
                'product_type_id' => $vetementId,
                'name' => 'Matière',
                'code' => 'matiere',
                'type' => 'select',
                'options' => json_encode(['Coton', 'Polyester', 'Laine', 'Soie', 'Lin', 'Synthétique', 'Mélange']),
                'is_filterable' => true,
                'display_order' => 3,
            ]);

            $createAttribute([
                'product_type_id' => $vetementId,
                'name' => 'Coupe',
                'code' => 'coupe',
                'type' => 'select',
                'options' => json_encode(['Slim', 'Regular', 'Loose', 'Oversize']),
                'display_order' => 4,
            ]);

            $createAttribute([
                'product_type_id' => $vetementId,
                'name' => 'Genre',
                'code' => 'genre',
                'type' => 'select',
                'options' => json_encode(['Homme', 'Femme', 'Unisexe', 'Enfant']),
                'is_filterable' => true,
                'display_order' => 5,
            ]);
        }

        // ============================================================
        // ALIMENTAIRE - Attributs
        // ============================================================
        if ($alimentaireId) {
            $createAttribute([
                'product_type_id' => $alimentaireId,
                'name' => 'Poids',
                'code' => 'poids',
                'type' => 'number',
                'unit' => 'kg',
                'is_required' => true,
                'display_order' => 1,
            ]);

            $createAttribute([
                'product_type_id' => $alimentaireId,
                'name' => 'Date d\'expiration',
                'code' => 'date_expiration',
                'type' => 'date',
                'is_required' => true,
                'is_filterable' => true,
                'display_order' => 2,
            ]);

            $createAttribute([
                'product_type_id' => $alimentaireId,
                'name' => 'Format',
                'code' => 'format',
                'type' => 'select',
                'options' => json_encode(['Petit (250g)', 'Moyen (500g)', 'Grand (1kg)', 'Familial (2kg)', 'Vrac']),
                'is_variant_attribute' => true,
                'is_filterable' => true,
                'display_order' => 3,
            ]);

            $createAttribute([
                'product_type_id' => $alimentaireId,
                'name' => 'Bio',
                'code' => 'bio',
                'type' => 'boolean',
                'default_value' => 'Produit biologique',
                'is_filterable' => true,
                'display_order' => 4,
            ]);

            $createAttribute([
                'product_type_id' => $alimentaireId,
                'name' => 'Origine',
                'code' => 'origine',
                'type' => 'select',
                'options' => json_encode(['Local', 'Importé', 'France', 'Europe', 'Afrique', 'Asie', 'Amérique']),
                'is_filterable' => true,
                'display_order' => 5,
            ]);

            $createAttribute([
                'product_type_id' => $alimentaireId,
                'name' => 'Conservation',
                'code' => 'conservation',
                'type' => 'select',
                'options' => json_encode(['Température ambiante', 'Réfrigéré', 'Congelé', 'Frais']),
                'display_order' => 6,
            ]);
        }

        // ============================================================
        // ÉLECTRONIQUE - Attributs
        // ============================================================
        if ($electroniqueId) {
            $createAttribute([
                'product_type_id' => $electroniqueId,
                'name' => 'Marque',
                'code' => 'marque',
                'type' => 'select',
                'options' => json_encode(['Samsung', 'Apple', 'LG', 'Sony', 'Philips', 'Huawei', 'Xiaomi', 'Autre']),
                'is_required' => true,
                'is_filterable' => true,
                'display_order' => 1,
            ]);

            $createAttribute([
                'product_type_id' => $electroniqueId,
                'name' => 'Capacité',
                'code' => 'capacite',
                'type' => 'select',
                'options' => json_encode(['32GB', '64GB', '128GB', '256GB', '512GB', '1TB']),
                'is_variant_attribute' => true,
                'is_filterable' => true,
                'display_order' => 2,
            ]);

            $createAttribute([
                'product_type_id' => $electroniqueId,
                'name' => 'Couleur',
                'code' => 'couleur',
                'type' => 'select',
                'options' => json_encode(['Noir', 'Blanc', 'Gris', 'Argent', 'Or', 'Bleu', 'Rouge']),
                'is_variant_attribute' => true,
                'is_filterable' => true,
                'display_order' => 3,
            ]);

            $createAttribute([
                'product_type_id' => $electroniqueId,
                'name' => 'Puissance',
                'code' => 'puissance',
                'type' => 'number',
                'unit' => 'W',
                'display_order' => 4,
            ]);

            $createAttribute([
                'product_type_id' => $electroniqueId,
                'name' => 'Tension',
                'code' => 'tension',
                'type' => 'select',
                'options' => json_encode(['220V', '110V', '12V', '5V', 'USB']),
                'display_order' => 5,
            ]);

            $createAttribute([
                'product_type_id' => $electroniqueId,
                'name' => 'Garantie',
                'code' => 'garantie',
                'type' => 'select',
                'options' => json_encode(['6 mois', '1 an', '2 ans', '3 ans', 'Aucune']),
                'is_filterable' => true,
                'display_order' => 6,
            ]);

            $createAttribute([
                'product_type_id' => $electroniqueId,
                'name' => 'Connectivité',
                'code' => 'connectivite',
                'type' => 'select',
                'options' => json_encode(['WiFi', 'Bluetooth', '4G', '5G', 'NFC', 'USB', 'HDMI']),
                'is_filterable' => true,
                'display_order' => 7,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer tous les attributs créés
        DB::table('product_attributes')->whereIn('product_type_id', function($query) {
            $query->select('id')
                ->from('product_types')
                ->whereIn('name', ['Vetement', 'Alimentaire', 'Electronique']);
        })->delete();
    }
};
