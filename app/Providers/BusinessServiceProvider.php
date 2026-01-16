<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BusinessServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        // Business Services are bound as singletons
        \App\Services\AuthService::class => \App\Services\AuthService::class,
        \App\Services\CategoryService::class => \App\Services\CategoryService::class,
        \App\Services\ProductService::class => \App\Services\ProductService::class,
        \App\Services\ClientService::class => \App\Services\ClientService::class,
        \App\Services\SupplierService::class => \App\Services\SupplierService::class,
        \App\Services\PurchaseService::class => \App\Services\PurchaseService::class,
        \App\Services\SaleService::class => \App\Services\SaleService::class,
        \App\Services\StockService::class => \App\Services\StockService::class,
        \App\Services\InvoiceService::class => \App\Services\InvoiceService::class,
        \App\Services\StockAlertService::class => \App\Services\StockAlertService::class,
        \App\Services\StoreService::class => \App\Services\StoreService::class,
        \App\Services\StoreTransferService::class => \App\Services\StoreTransferService::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind services as singletons for better performance
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
