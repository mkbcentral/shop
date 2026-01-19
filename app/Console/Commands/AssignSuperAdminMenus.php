<?php

namespace App\Console\Commands;

use App\Models\MenuItem;
use App\Models\Role;
use Illuminate\Console\Command;

class AssignSuperAdminMenus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'superadmin:assign-menus {--force : Forcer la réassignation même si les menus existent}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assigner les menus essentiels au rôle super-admin';

    /**
     * Les menus essentiels pour le super-admin (doit correspondre à MenuService::SUPER_ADMIN_MENU_CODES)
     */
    protected array $menuCodes = [
        'admin-dashboard',
        'menu-permissions',
        'subscriptions',
        'subscription-settings',
        'roles',
        'roles.index',
        'users',
        'users.index',
        'organizations',
        'organizations.index',
        'organizations.create',
        'printer-config',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('');
        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info('║         ASSIGNATION DES MENUS AU SUPER-ADMIN               ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');
        $this->info('');

        // Trouver le rôle super-admin
        $superAdminRole = Role::where('name', 'super-admin')
            ->orWhere('slug', 'super-admin')
            ->first();

        if (!$superAdminRole) {
            $this->error('❌ Le rôle super-admin n\'existe pas !');
            $this->warn('   Exécutez d\'abord: php artisan superadmin:create');
            return self::FAILURE;
        }

        $this->info("🔍 Rôle super-admin trouvé: {$superAdminRole->name} (ID: {$superAdminRole->id})");
        $this->info('');

        // Récupérer les menus actuels du super-admin
        $currentMenuIds = $superAdminRole->menus()->pluck('menu_items.id')->toArray();
        $currentMenuCodes = $superAdminRole->menus()->pluck('code')->toArray();

        $this->info('📋 Menus actuellement assignés: ' . count($currentMenuCodes));
        
        // Trouver les menus manquants
        $missingMenuCodes = array_diff($this->menuCodes, $currentMenuCodes);

        if (empty($missingMenuCodes) && !$this->option('force')) {
            $this->info('');
            $this->info('✅ Tous les menus essentiels sont déjà assignés au super-admin !');
            $this->info('');
            $this->table(
                ['Code', 'Statut'],
                collect($this->menuCodes)->map(fn($code) => [$code, '✓ Assigné'])->toArray()
            );
            return self::SUCCESS;
        }

        // Récupérer les menus à assigner
        $menusToAssign = MenuItem::whereIn('code', $this->menuCodes)->get();

        if ($menusToAssign->isEmpty()) {
            $this->error('❌ Aucun menu trouvé dans la base de données !');
            $this->warn('   Exécutez d\'abord: php artisan db:seed --class=MenuItemSeeder');
            return self::FAILURE;
        }

        // Afficher les menus trouvés vs manquants
        $this->info('');
        $this->info('📊 Analyse des menus:');
        
        $tableData = [];
        foreach ($this->menuCodes as $code) {
            $menu = $menusToAssign->firstWhere('code', $code);
            $isAssigned = in_array($code, $currentMenuCodes);
            
            if ($menu) {
                $status = $isAssigned ? '✓ Déjà assigné' : '⚠ À assigner';
                $tableData[] = [$code, $menu->name, $status];
            } else {
                $tableData[] = [$code, '(non trouvé)', '❌ Menu inexistant'];
            }
        }
        
        $this->table(['Code', 'Nom', 'Statut'], $tableData);
        $this->info('');

        // Confirmer l'action
        if (!$this->option('force') && !empty($missingMenuCodes)) {
            if (!$this->confirm('Voulez-vous assigner les menus manquants au super-admin ?', true)) {
                $this->info('Opération annulée.');
                return self::SUCCESS;
            }
        }

        // Assigner les menus (sync sans détacher les existants)
        $menuIds = $menusToAssign->pluck('id')->toArray();
        $superAdminRole->menus()->syncWithoutDetaching($menuIds);

        $this->info('');
        $this->info('✅ Menus assignés avec succès au super-admin !');
        $this->info('');

        // Afficher le résultat final
        $finalMenus = $superAdminRole->menus()->orderBy('section')->orderBy('order')->get();
        $this->info("📋 Total des menus du super-admin: {$finalMenus->count()}");
        
        $this->table(
            ['Section', 'Menu', 'Code'],
            $finalMenus->map(fn($m) => [$m->section ?? '-', $m->name, $m->code])->toArray()
        );

        return self::SUCCESS;
    }
}
