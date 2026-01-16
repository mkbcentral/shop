<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Action Service Provider
 *
 * Registers all action classes in the container.
 * Actions are stateless and can be bound as singletons.
 */
class ActionServiceProvider extends ServiceProvider
{
    /**
     * All actions to register.
     *
     * @var array
     */
    protected $actions = [
        // Auth Actions
        \App\Actions\Auth\LoginAction::class,
        \App\Actions\Auth\LogoutAction::class,
        \App\Actions\Auth\EnableTwoFactorAction::class,
        \App\Actions\Auth\ConfirmTwoFactorAction::class,
        \App\Actions\Auth\DisableTwoFactorAction::class,

        // Category Actions
        \App\Actions\Category\CreateCategoryAction::class,
        \App\Actions\Category\UpdateCategoryAction::class,
        \App\Actions\Category\DeleteCategoryAction::class,

        // Product Actions
        \App\Actions\Product\CreateProductAction::class,
        \App\Actions\Product\UpdateProductAction::class,
        \App\Actions\Product\DeleteProductAction::class,
        \App\Actions\Product\CreateVariantAction::class,
        \App\Actions\Product\UpdateVariantAction::class,
        \App\Actions\Product\DeleteVariantAction::class,
        \App\Actions\Product\ImportProductsAction::class,

        // Client Actions
        \App\Actions\Client\CreateClientAction::class,
        \App\Actions\Client\UpdateClientAction::class,
        \App\Actions\Client\DeleteClientAction::class,

        // Supplier Actions
        \App\Actions\Supplier\CreateSupplierAction::class,
        \App\Actions\Supplier\UpdateSupplierAction::class,
        \App\Actions\Supplier\DeleteSupplierAction::class,

        // Purchase Actions
        \App\Actions\Purchase\CreatePurchaseAction::class,
        \App\Actions\Purchase\UpdatePurchaseAction::class,
        \App\Actions\Purchase\DeletePurchaseAction::class,

        // Sale Actions
        \App\Actions\Sale\CreateSaleAction::class,
        \App\Actions\Sale\UpdateSaleAction::class,
        \App\Actions\Sale\DeleteSaleAction::class,
        \App\Actions\Sale\ProcessSaleAction::class,
        \App\Actions\Sale\RefundSaleAction::class,

        // Stock Actions
        \App\Actions\Stock\AddStockAction::class,
        \App\Actions\Stock\RemoveStockAction::class,
        \App\Actions\Stock\AdjustStockAction::class,
        \App\Actions\Stock\BulkStockUpdateAction::class,
        \App\Actions\Stock\PerformInventoryAction::class,

        // Invoice Actions
        \App\Actions\Invoice\CreateInvoiceAction::class,
        \App\Actions\Invoice\UpdateInvoiceAction::class,
        \App\Actions\Invoice\DeleteInvoiceAction::class,
        \App\Actions\Invoice\MarkInvoiceAsPaidAction::class,
        \App\Actions\Invoice\SendInvoiceAction::class,
        \App\Actions\Invoice\CancelInvoiceAction::class,
        \App\Actions\Invoice\GenerateInvoicePdfAction::class,

        // Report Actions
        \App\Actions\Report\GenerateSalesReportAction::class,
        \App\Actions\Report\GenerateStockReportAction::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind all actions as singletons
        foreach ($this->actions as $action) {
            $this->app->singleton($action);
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
