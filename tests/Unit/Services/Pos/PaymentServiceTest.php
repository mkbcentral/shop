<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Pos;

use App\Services\Pos\PaymentService;
use App\Services\Pos\PaymentData;
use App\Services\Pos\CalculationService;
use App\Actions\Sale\CreateSaleAction;
use App\Actions\Invoice\CreateInvoiceAction;
use App\Exceptions\Pos\CartEmptyException;
use App\Exceptions\Pos\InsufficientPaymentException;
use App\Exceptions\Pos\InsufficientStockException;
use App\Models\Sale;
use App\Models\Invoice;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    private PaymentService $paymentService;
    private CreateSaleAction $createSaleAction;
    private CreateInvoiceAction $createInvoiceAction;
    private CalculationService $calculationService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createSaleAction = app(CreateSaleAction::class);
        $this->createInvoiceAction = app(CreateInvoiceAction::class);
        $this->calculationService = app(CalculationService::class);

        $this->paymentService = new PaymentService(
            $this->createSaleAction,
            $this->createInvoiceAction,
            $this->calculationService
        );
    }

    /** @test */
    public function it_throws_exception_when_cart_is_empty(): void
    {
        $this->expectException(CartEmptyException::class);

        $paymentData = new PaymentData(
            userId: 1,
            clientId: null,
            storeId: 1,
            paymentMethod: 'cash',
            items: [], // Panier vide
            discount: 0,
            tax: 0,
            paidAmount: 1000,
            total: 1000,
            notes: '',
            stockValidation: ['valid' => true]
        );

        $this->paymentService->process($paymentData);
    }

    /** @test */
    public function it_throws_exception_when_payment_is_insufficient(): void
    {
        $this->expectException(InsufficientPaymentException::class);
        $this->expectExceptionMessage('Montant payé insuffisant');

        $paymentData = new PaymentData(
            userId: 1,
            clientId: null,
            storeId: 1,
            paymentMethod: 'cash',
            items: [
                ['product_variant_id' => 1, 'quantity' => 1, 'unit_price' => 1000]
            ],
            discount: 0,
            tax: 0,
            paidAmount: 500, // Insuffisant
            total: 1000,
            notes: '',
            stockValidation: ['valid' => true]
        );

        $this->paymentService->process($paymentData);
    }

    /** @test */
    public function it_throws_exception_when_stock_is_insufficient(): void
    {
        $this->expectException(InsufficientStockException::class);

        $paymentData = new PaymentData(
            userId: 1,
            clientId: null,
            storeId: 1,
            paymentMethod: 'cash',
            items: [
                ['product_variant_id' => 1, 'quantity' => 10, 'unit_price' => 1000]
            ],
            discount: 0,
            tax: 0,
            paidAmount: 10000,
            total: 10000,
            notes: '',
            stockValidation: [
                'valid' => false,
                'product_name' => 'Test Product',
                'requested' => 10,
                'available' => 2
            ]
        );

        $this->paymentService->process($paymentData);
    }

    /** @test */
    public function it_rolls_back_transaction_on_error(): void
    {
        DB::beginTransaction();

        $initialSalesCount = Sale::count();
        $initialInvoicesCount = Invoice::count();

        try {
            $paymentData = new PaymentData(
                userId: 1,
                clientId: null,
                storeId: 1,
                paymentMethod: 'cash',
                items: [], // Panier vide pour forcer une erreur
                discount: 0,
                tax: 0,
                paidAmount: 1000,
                total: 1000,
                notes: '',
                stockValidation: ['valid' => true]
            );

            $this->paymentService->process($paymentData);
        } catch (CartEmptyException $e) {
            // Exception attendue
        }

        DB::rollBack();

        // Vérifier qu'aucune vente ou facture n'a été créée
        $this->assertEquals($initialSalesCount, Sale::count());
        $this->assertEquals($initialInvoicesCount, Invoice::count());
    }
}
