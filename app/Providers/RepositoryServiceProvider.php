<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        // Repositories are bound as singletons for performance
        \App\Repositories\UserRepository::class => \App\Repositories\UserRepository::class,
        \App\Repositories\CategoryRepository::class => \App\Repositories\CategoryRepository::class,
        \App\Repositories\ProductRepository::class => \App\Repositories\ProductRepository::class,
        \App\Repositories\ProductVariantRepository::class => \App\Repositories\ProductVariantRepository::class,
        \App\Repositories\ClientRepository::class => \App\Repositories\ClientRepository::class,
        \App\Repositories\SupplierRepository::class => \App\Repositories\SupplierRepository::class,
        \App\Repositories\PurchaseRepository::class => \App\Repositories\PurchaseRepository::class,
        \App\Repositories\PurchaseItemRepository::class => \App\Repositories\PurchaseItemRepository::class,
        \App\Repositories\SaleRepository::class => \App\Repositories\SaleRepository::class,
        \App\Repositories\SaleItemRepository::class => \App\Repositories\SaleItemRepository::class,
        \App\Repositories\StockMovementRepository::class => \App\Repositories\StockMovementRepository::class,
        \App\Repositories\InvoiceRepository::class => \App\Repositories\InvoiceRepository::class,
        \App\Repositories\StoreRepository::class => \App\Repositories\StoreRepository::class,
        \App\Repositories\StoreTransferRepository::class => \App\Repositories\StoreTransferRepository::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind repositories as singletons
        foreach ($this->bindings as $abstract => $concrete) {
            $this->app->singleton($abstract, $concrete);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
