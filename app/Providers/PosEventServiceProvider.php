<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\Pos\SaleCompleted;
use App\Events\Pos\PaymentReceived;
use App\Events\Pos\CartCleared;
use App\Events\Pos\ItemAddedToCart;
use App\Listeners\Pos\UpdateDailyStatsListener;
use App\Listeners\Pos\LogSaleActivityListener;
use App\Listeners\Pos\NotifyLowStockListener;
use App\Listeners\Pos\NotifyManagersOnPosSale;
use App\Listeners\Pos\LogPaymentListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Service Provider pour les événements POS
 */
class PosEventServiceProvider extends ServiceProvider
{
    /**
     * Mapping événements => listeners
     */
    protected $listen = [
        SaleCompleted::class => [
            UpdateDailyStatsListener::class,
            LogSaleActivityListener::class,
            NotifyLowStockListener::class,
            NotifyManagersOnPosSale::class,
        ],

        PaymentReceived::class => [
            LogPaymentListener::class,
        ],

        // CartCleared et ItemAddedToCart peuvent avoir leurs listeners si nécessaire
    ];

    /**
     * Enregistre les services
     */
    public function boot(): void
    {
        parent::boot();
    }
}
