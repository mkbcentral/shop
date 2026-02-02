<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Repositories\SaleRepository;
use App\Services\Sale\SaleCreationService;
use App\Services\Sale\SaleUpdateService;
use App\Services\Sale\SalePaymentService;
use App\Services\Sale\SaleRefundService;
use App\Services\Sale\SaleAnalyticsService;
use App\Traits\ResolvesStoreContext;

/**
 * Main Sale Service - Facade pattern
 * 
 * This service acts as the main entry point for sale operations
 * and delegates to specialized services for better code organization.
 * 
 * @deprecated Consider using specialized services directly:
 * - SaleCreationService for creating sales
 * - SaleUpdateService for updating sales
 * - SalePaymentService for payment operations
 * - SaleRefundService for refund operations
 * - SaleAnalyticsService for statistics and reporting
 */
class SaleService
{
    use ResolvesStoreContext;

    public function __construct(
        private SaleRepository $saleRepository,
        private SaleCreationService $creationService,
        private SaleUpdateService $updateService,
        private SalePaymentService $paymentService,
        private SaleRefundService $refundService,
        private SaleAnalyticsService $analyticsService
    ) {}

    /**
     * Create a new sale with items
     * Delegates to SaleCreationService
     */
    public function createSale(array $data): Sale
    {
        return $this->creationService->createSale($data);
    }

    /**
     * Update a sale
     * Delegates to SaleUpdateService
     */
    public function updateSale(int $saleId, array $data): Sale
    {
        return $this->updateService->updateSale($saleId, $data);
    }

    /**
     * Complete a sale
     * Delegates to SaleCreationService
     */
    public function completeSale(int $saleId): Sale
    {
        return $this->creationService->completeSale($saleId);
    }

    /**
     * Cancel a sale
     * Delegates to SaleUpdateService
     */
    public function cancelSale(int $saleId, string $reason = null): Sale
    {
        return $this->updateService->cancelSale($saleId, $reason);
    }

    /**
     * Add item to a sale
     * Delegates to SaleCreationService
     */
    public function addItem(Sale $sale, array $itemData): SaleItem
    {
        return $this->creationService->addItem($sale, $itemData);
    }

    /**
     * Add item to existing sale
     * Delegates to SaleCreationService
     */
    public function addItemToSale(int $saleId, array $itemData): Sale
    {
        return $this->creationService->addItemToSale($saleId, $itemData);
    }

    /**
     * Remove item from sale
     * Delegates to SaleUpdateService
     */
    public function removeItemFromSale(int $saleId, int $itemId): Sale
    {
        return $this->updateService->removeItemFromSale($saleId, $itemId);
    }

    /**
     * Record a payment
     * Delegates to SalePaymentService
     */
    public function recordPayment(int $saleId, array $paymentData): Sale
    {
        return $this->paymentService->recordPayment($saleId, $paymentData);
    }

    /**
     * Refund a sale
     * Delegates to SaleRefundService
     */
    public function refundSale(int $saleId, string $reason, bool $restoreStock = true): Sale
    {
        return $this->refundService->refundSale($saleId, $reason, $restoreStock);
    }

    /**
     * Get sales statistics
     * Delegates to SaleAnalyticsService
     */
    public function getSalesStatistics(string $startDate, string $endDate): array
    {
        return $this->analyticsService->getSalesStatistics($startDate, $endDate);
    }

    /**
     * Get today's summary
     * Delegates to SaleAnalyticsService
     */
    public function getTodaySummary(): array
    {
        return $this->analyticsService->getTodaySummary();
    }

    // Additional convenience methods

    /**
     * Mark sale as paid
     * Delegates to SalePaymentService
     */
    public function markAsPaid(int $saleId): Sale
    {
        return $this->paymentService->markAsPaid($saleId);
    }

    /**
     * Get remaining balance
     * Delegates to SalePaymentService
     */
    public function getRemainingBalance(int $saleId): float
    {
        return $this->paymentService->getRemainingBalance($saleId);
    }

    /**
     * Update discount
     * Delegates to SaleUpdateService
     */
    public function updateDiscount(int $saleId, float $discount): Sale
    {
        return $this->updateService->updateDiscount($saleId, $discount);
    }

    /**
     * Update tax
     * Delegates to SaleUpdateService
     */
    public function updateTax(int $saleId, float $tax): Sale
    {
        return $this->updateService->updateTax($saleId, $tax);
    }

    /**
     * Partial refund
     * Delegates to SaleRefundService
     */
    public function partialRefund(int $saleId, array $items, string $reason, bool $restoreStock = true): Sale
    {
        return $this->refundService->partialRefund($saleId, $items, $reason, $restoreStock);
    }

    /**
     * Get top selling products
     * Delegates to SaleAnalyticsService
     */
    public function getTopSellingProducts(string $startDate, string $endDate, int $limit = 10): array
    {
        return $this->analyticsService->getTopSellingProducts($startDate, $endDate, $limit);
    }
}
