<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ProductType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get product types for linking categories
        $habillement = ProductType::where('slug', 'habillement')->first();
        $alimentaire = ProductType::where('slug', 'alimentaire')->first();
        $electronique = ProductType::where('slug', 'electronique')->first();
        $coiffure = ProductType::where('slug', 'coiffure')->first();
        $esthetique = ProductType::where('slug', 'esthetique')->first();
        $photographie = ProductType::where('slug', 'photographie')->first();
        $consultation = ProductType::where('slug', 'consultation')->first();
        $reparation = ProductType::where('slug', 'reparation')->first();
        $serviceGenerique = ProductType::where('slug', 'service')->first();

        // Categories for Habillement (clothing)
        $clothingCategories = [
            [
                'name' => 'Hommes',
                'description' => 'Vêtements et accessoires pour hommes',
                'slug' => 'hommes',
                'product_type_id' => $habillement?->id,
            ],
            [
                'name' => 'Femmes',
                'description' => 'Vêtements et accessoires pour femmes',
                'slug' => 'femmes',
                'product_type_id' => $habillement?->id,
            ],
            [
                'name' => 'Enfants',
                'description' => 'Vêtements pour enfants et bébés',
                'slug' => 'enfants',
                'product_type_id' => $habillement?->id,
            ],
            [
                'name' => 'Chaussures',
                'description' => 'Chaussures pour tous',
                'slug' => 'chaussures',
                'product_type_id' => $habillement?->id,
            ],
            [
                'name' => 'Accessoires',
                'description' => 'Sacs, ceintures, bijoux, montres',
                'slug' => 'accessoires',
                'product_type_id' => $habillement?->id,
            ],
            [
                'name' => 'Sport',
                'description' => 'Vêtements et équipements sportifs',
                'slug' => 'sport',
                'product_type_id' => $habillement?->id,
            ],
            [
                'name' => 'Lingerie',
                'description' => 'Sous-vêtements et lingerie',
                'slug' => 'lingerie',
                'product_type_id' => $habillement?->id,
            ],
            [
                'name' => 'Jeans',
                'description' => 'Pantalons en denim et jeans',
                'slug' => 'jeans',
                'product_type_id' => $habillement?->id,
            ],
            [
                'name' => 'Vestes & Manteaux',
                'description' => 'Vestes, manteaux, blousons',
                'slug' => 'vestes-manteaux',
                'product_type_id' => $habillement?->id,
            ],
            [
                'name' => 'Grandes Tailles',
                'description' => 'Vêtements grandes tailles',
                'slug' => 'grandes-tailles',
                'product_type_id' => $habillement?->id,
            ],
        ];

        // Categories for Alimentaire (food)
        $foodCategories = [
            [
                'name' => 'Boissons',
                'description' => 'Boissons fraîches et chaudes',
                'slug' => 'boissons',
                'product_type_id' => $alimentaire?->id,
            ],
            [
                'name' => 'Snacks',
                'description' => 'Snacks et encas',
                'slug' => 'snacks',
                'product_type_id' => $alimentaire?->id,
            ],
            [
                'name' => 'Produits frais',
                'description' => 'Fruits, légumes et produits frais',
                'slug' => 'produits-frais',
                'product_type_id' => $alimentaire?->id,
            ],
        ];

        // Categories for Électronique
        $electronicCategories = [
            [
                'name' => 'Téléphones',
                'description' => 'Smartphones et téléphones mobiles',
                'slug' => 'telephones',
                'product_type_id' => $electronique?->id,
            ],
            [
                'name' => 'Ordinateurs',
                'description' => 'PC, laptops et tablettes',
                'slug' => 'ordinateurs',
                'product_type_id' => $electronique?->id,
            ],
            [
                'name' => 'Accessoires électroniques',
                'description' => 'Câbles, chargeurs, écouteurs',
                'slug' => 'accessoires-electroniques',
                'product_type_id' => $electronique?->id,
            ],
        ];

        // Categories for Coiffure (salon services)
        $coiffureCategories = [
            [
                'name' => 'Coupes Homme',
                'description' => 'Coupes et styles pour hommes',
                'slug' => 'coupes-homme',
                'product_type_id' => $coiffure?->id,
            ],
            [
                'name' => 'Coupes Femme',
                'description' => 'Coupes et coiffures pour femmes',
                'slug' => 'coupes-femme',
                'product_type_id' => $coiffure?->id,
            ],
            [
                'name' => 'Coloration',
                'description' => 'Coloration et mèches',
                'slug' => 'coloration',
                'product_type_id' => $coiffure?->id,
            ],
            [
                'name' => 'Soins capillaires',
                'description' => 'Traitements et soins des cheveux',
                'slug' => 'soins-capillaires',
                'product_type_id' => $coiffure?->id,
            ],
            [
                'name' => 'Coiffure Enfant',
                'description' => 'Coupes pour enfants',
                'slug' => 'coiffure-enfant',
                'product_type_id' => $coiffure?->id,
            ],
        ];

        // Categories for Esthétique (beauty services)
        $esthetiqueCategories = [
            [
                'name' => 'Manucure',
                'description' => 'Soins des ongles et mains',
                'slug' => 'manucure',
                'product_type_id' => $esthetique?->id,
            ],
            [
                'name' => 'Pédicure',
                'description' => 'Soins des pieds',
                'slug' => 'pedicure',
                'product_type_id' => $esthetique?->id,
            ],
            [
                'name' => 'Soins du visage',
                'description' => 'Traitements faciaux',
                'slug' => 'soins-visage',
                'product_type_id' => $esthetique?->id,
            ],
            [
                'name' => 'Épilation',
                'description' => 'Services d\'épilation',
                'slug' => 'epilation',
                'product_type_id' => $esthetique?->id,
            ],
            [
                'name' => 'Massage',
                'description' => 'Massages relaxants et thérapeutiques',
                'slug' => 'massage',
                'product_type_id' => $esthetique?->id,
            ],
        ];

        // Categories for Photographie
        $photoCategories = [
            [
                'name' => 'Portrait',
                'description' => 'Photos portrait et identité',
                'slug' => 'portrait',
                'product_type_id' => $photographie?->id,
            ],
            [
                'name' => 'Événements',
                'description' => 'Couverture d\'événements',
                'slug' => 'evenements',
                'product_type_id' => $photographie?->id,
            ],
            [
                'name' => 'Mariage',
                'description' => 'Photographie de mariage',
                'slug' => 'mariage',
                'product_type_id' => $photographie?->id,
            ],
        ];

        // Categories for Consultation
        $consultationCategories = [
            [
                'name' => 'Conseil',
                'description' => 'Services de conseil',
                'slug' => 'conseil',
                'product_type_id' => $consultation?->id,
            ],
            [
                'name' => 'Formation',
                'description' => 'Sessions de formation',
                'slug' => 'formation',
                'product_type_id' => $consultation?->id,
            ],
        ];

        // Categories for Réparation
        $reparationCategories = [
            [
                'name' => 'Réparation téléphone',
                'description' => 'Réparation de téléphones mobiles',
                'slug' => 'reparation-telephone',
                'product_type_id' => $reparation?->id,
            ],
            [
                'name' => 'Réparation ordinateur',
                'description' => 'Réparation d\'ordinateurs',
                'slug' => 'reparation-ordinateur',
                'product_type_id' => $reparation?->id,
            ],
            [
                'name' => 'Réparation électroménager',
                'description' => 'Réparation d\'appareils électroménagers',
                'slug' => 'reparation-electromenager',
                'product_type_id' => $reparation?->id,
            ],
        ];

        // Categories for Service générique
        $serviceCategories = [
            [
                'name' => 'Services divers',
                'description' => 'Autres services',
                'slug' => 'services-divers',
                'product_type_id' => $serviceGenerique?->id,
            ],
        ];

        // Merge all categories
        $allCategories = array_merge(
            $clothingCategories,
            $foodCategories,
            $electronicCategories,
            $coiffureCategories,
            $esthetiqueCategories,
            $photoCategories,
            $consultationCategories,
            $reparationCategories,
            $serviceCategories
        );

        foreach ($allCategories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
