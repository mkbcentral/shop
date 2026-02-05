<?php

namespace App\Providers;

use App\Models\Organization;
use App\Models\Sale;
use App\Observers\SaleObserver;
use App\Policies\OrganizationPolicy;
use App\Repositories\ClientRepository;
use App\Repositories\ProductRepository;
use App\Repositories\SupplierRepository;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enregistrer les policies
        Gate::policy(Organization::class, OrganizationPolicy::class);

        // Enregistrer les observateurs
        Sale::observe(SaleObserver::class);

        // Blade directive pour la devise
        // Usage: @currency(1000) => "1 000 FC" (or organization currency)
        Blade::directive('currency', function ($expression) {
            return "<?php echo format_currency($expression); ?>";
        });

        // Usage: @money(1000, 2) => "1 000,00 FC" (or organization currency)
        Blade::directive('money', function ($expression) {
            return "<?php echo format_currency($expression); ?>";
        });

        // Usage: @currencySymbol => "CDF" (or organization currency)
        Blade::directive('currencySymbol', function () {
            return "<?php echo current_currency(); ?>";
        });

        // ===== Authorization Blade Directives =====

        // @role('admin') ... @endrole
        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });

        // @hasanyrole(['admin', 'manager']) ... @endhasanyrole
        Blade::if('hasanyrole', function ($roles) {
            return auth()->check() && auth()->user()->hasAnyRole((array) $roles);
        });

        // @permission('products.create') ... @endpermission
        Blade::if('permission', function ($permission) {
            return auth()->check() && auth()->user()->hasPermission($permission);
        });

        // @hasanypermission(['products.create', 'products.edit']) ... @endhasanypermission
        Blade::if('hasanypermission', function ($permissions) {
            return auth()->check() && auth()->user()->hasAnyPermission((array) $permissions);
        });

        // @hasallpermissions(['products.create', 'products.edit']) ... @endhasallpermissions
        Blade::if('hasallpermissions', function ($permissions) {
            return auth()->check() && auth()->user()->hasAllPermissions((array) $permissions);
        });

        // ===== Plan Feature Blade Directives =====

        // @hasfeature('api_access') ... @endhasfeature
        Blade::if('hasfeature', function ($feature) {
            if (!auth()->check()) {
                return false;
            }
            
            $user = auth()->user();
            
            // Super-admin a accès à tout
            if ($user->hasRole('super-admin')) {
                return true;
            }
            
            $organization = app()->bound('current_organization') 
                ? app('current_organization') 
                : $user->defaultOrganization;
                
            if (!$organization) {
                return false;
            }
            
            $planLimitService = app(\App\Services\PlanLimitService::class);
            return $planLimitService->hasFeature($feature, $organization);
        });

        // @hasanyfeature(['api_access', 'export_pdf']) ... @endhasanyfeature
        Blade::if('hasanyfeature', function ($features) {
            if (!auth()->check()) {
                return false;
            }
            
            $user = auth()->user();
            
            // Super-admin a accès à tout
            if ($user->hasRole('super-admin')) {
                return true;
            }
            
            $organization = app()->bound('current_organization') 
                ? app('current_organization') 
                : $user->defaultOrganization;
                
            if (!$organization) {
                return false;
            }
            
            $planLimitService = app(\App\Services\PlanLimitService::class);
            
            foreach ((array) $features as $feature) {
                if ($planLimitService->hasFeature($feature, $organization)) {
                    return true;
                }
            }
            
            return false;
        });

        // Share navigation data with all views
        View::composer('components.navigation-dynamic', function ($view) {
            $productRepository = app(ProductRepository::class);
            $clientRepository = app(ClientRepository::class);
            $supplierRepository = app(SupplierRepository::class);

            $view->with([
                // Variables pour la navigation dynamique (format: {code}_count)
                'products_count' => $productRepository->count(),
                'clients_count' => $clientRepository->count(),
                'suppliers_count' => $supplierRepository->count(),

                // Anciens noms pour compatibilité
                'total_products' => $productRepository->count(),
                'total_clients' => $clientRepository->count(),
                'total_suppliers' => $supplierRepository->count(),
                'low_stock_alerts' => $productRepository->countLowStockAlerts(),
            ]);
        });
    }
}
