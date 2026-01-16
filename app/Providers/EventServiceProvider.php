<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;

/**
 * Event Service Provider
 *
 * Registers model events and observers for business logic.
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\LowStockAlert::class => [
            \App\Listeners\LogLowStockAlert::class,
        ],
        \App\Events\OutOfStockAlert::class => [
            \App\Listeners\LogOutOfStockAlert::class,
        ],
        // Category Events
        \App\Events\Category\CategoryCreated::class => [
            \App\Listeners\Category\LogCategoryCreated::class,
        ],
        \App\Events\Category\CategoryUpdated::class => [
            \App\Listeners\Category\LogCategoryUpdated::class,
        ],
        \App\Events\Category\CategoryDeleted::class => [
            \App\Listeners\Category\LogCategoryDeleted::class,
        ],
        // Sales Events
        \App\Events\SaleCompleted::class => [
            \App\Listeners\NotifyManagersOnSale::class,
        ],
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Prevent lazy loading in development to catch N+1 queries
        if ($this->app->environment('local')) {
            Model::preventLazyLoading();
        }

        // Prevent silently discarding attributes
        Model::preventSilentlyDiscardingAttributes();

        // Additional model events can be registered here
        // Example:
        // Product::observe(ProductObserver::class);
    }
}
