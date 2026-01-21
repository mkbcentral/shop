<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Gratuit',
                'slug' => 'free',
                'description' => 'Plan gratuit pour découvrir la plateforme',
                'price' => 0,
                'max_stores' => 1,
                'max_users' => 3,
                'max_products' => 100,
                'features' => [
                    'Jusqu\'à 1 magasin',
                    'Jusqu\'à 3 utilisateurs',
                    'Jusqu\'à 100 produits',
                    'Rapports de base',
                    'Support par email',
                ],
                'is_popular' => false,
                'color' => 'gray',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'Idéal pour les petites entreprises',
                'price' => 9900,
                'max_stores' => 3,
                'max_users' => 10,
                'max_products' => 1000,
                'features' => [
                    'Jusqu\'à 3 magasins',
                    'Jusqu\'à 10 utilisateurs',
                    'Jusqu\'à 1 000 produits',
                    'Rapports avancés',
                    'Support prioritaire',
                    'Exportation des données',
                ],
                'is_popular' => false,
                'color' => 'blue',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Professionnel',
                'slug' => 'professional',
                'description' => 'Pour les entreprises en croissance',
                'price' => 24900,
                'max_stores' => 10,
                'max_users' => 50,
                'max_products' => 10000,
                'features' => [
                    'Jusqu\'à 10 magasins',
                    'Jusqu\'à 50 utilisateurs',
                    'Jusqu\'à 10 000 produits',
                    'Rapports personnalisés',
                    'Support téléphonique',
                    'API access',
                    'Multi-devises',
                ],
                'is_popular' => true,
                'color' => 'purple',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Entreprise',
                'slug' => 'enterprise',
                'description' => 'Solution complète pour grandes organisations',
                'price' => 49900,
                'max_stores' => 100,
                'max_users' => 500,
                'max_products' => 100000,
                'features' => [
                    'Jusqu\'à 100 magasins',
                    'Jusqu\'à 500 utilisateurs',
                    'Jusqu\'à 100 000 produits',
                    'Rapports sur mesure',
                    'Support dédié 24/7',
                    'API illimité',
                    'Multi-devises',
                    'Formation personnalisée',
                    'SLA garanti',
                ],
                'is_popular' => false,
                'color' => 'amber',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
