<?php

declare(strict_types=1);

namespace App\Livewire\Pos\Components;

use App\Exceptions\Pos\CartEmptyException;
use App\Exceptions\Pos\InsufficientPaymentException;
use App\Exceptions\Pos\InsufficientStockException;
use App\Models\Sale;
use App\Models\Invoice;
use App\Services\Pos\CartStateManager;
use App\Services\Pos\CalculationService;
use App\Services\Pos\PaymentService;
use App\Services\Pos\PaymentData;
use App\Services\Pos\PrinterService;
use App\Services\Pos\StatsService;
use Livewire\Component;
use Livewire\Attributes\On;

/**
 * Composant Panneau de Paiement POS
 * Gère le processus de paiement et l'affichage des totaux
 */
class PosPaymentPanel extends Component
{
    // État reçu du panier
    public array $cart = [];
    public float $subtotal = 0;
    public float $total = 0;
    public float $discount = 0;
    public float $tax = 0;
    public ?int $clientId = null;

    // Paiement
    public string $paymentMethod = 'cash';
    public float $paidAmount = 0;
    public float $change = 0;
    public string $notes = '';

    // Monnaie
    public array $changeBreakdown = [];
    public array $suggestedAmounts = [];

    // Reçu
    public bool $showReceipt = false;
    public ?int $lastSaleId = null;
    public ?int $lastInvoiceId = null;

    // Messages
    public string $errorMessage = '';
    public string $successMessage = '';

    // Services
    private CartStateManager $cartManager;
    private CalculationService $calculationService;
    private PaymentService $paymentService;
    private PrinterService $printerService;
    private StatsService $statsService;

    public function boot(
        CartStateManager $cartManager,
        CalculationService $calculationService,
        PaymentService $paymentService,
        PrinterService $printerService,
        StatsService $statsService
    ): void {
        $this->cartManager = $cartManager;
        $this->calculationService = $calculationService;
        $this->paymentService = $paymentService;
        $this->printerService = $printerService;
        $this->statsService = $statsService;
    }

    public function mount(): void
    {
        $this->syncCartFromSession();
        $this->recalculateTotals();
    }

    public function hydrate(): void
    {
        $this->syncCartFromSession();
    }

    /**
     * Synchronise le panier depuis la session
     */
    private function syncCartFromSession(): void
    {
        $this->cart = session()->get('pos_cart', []);
        $this->cartManager->initialize($this->cart);
    }

    /**
     * Écoute les changements d'état du panier
     */
    #[On('cart-state-changed')]
    public function handleCartStateChange(array $state): void
    {
        $this->cart = $state['cart'] ?? [];
        $this->subtotal = $state['subtotal'] ?? 0;
        $this->tax = $state['tax'] ?? 0;
        $this->total = $state['total'] ?? 0;
        $this->discount = $state['discount'] ?? 0;
        $this->clientId = $state['clientId'] ?? null;

        $this->cartManager->initialize($this->cart);
        $this->paidAmount = $this->total;
        $this->calculateChange();
        $this->calculateSuggestedAmounts();
    }

    /**
     * Écoute la demande d'aperçu de reçu depuis le panier
     */
    #[On('show-receipt-preview')]
    public function handleReceiptPreviewRequest(int $saleId): void
    {
        $this->lastSaleId = $saleId;
        $this->showReceipt = true;
    }

    /**
     * Recalcule les totaux localement
     */
    private function recalculateTotals(): void
    {
        $totals = $this->calculationService->calculateTotals(
            $this->cart,
            $this->discount,
            $this->tax
        );

        $this->subtotal = $totals['subtotal'];
        $this->tax = $totals['tax'];
        $this->total = $totals['total'];
        $this->paidAmount = $this->total;

        $this->calculateChange();
        $this->calculateSuggestedAmounts();
    }

    /**
     * Met à jour le montant payé
     */
    public function updatedPaidAmount(): void
    {
        $this->calculateChange();
    }

    /**
     * Calcule la monnaie rendue
     */
    public function calculateChange(): void
    {
        $this->change = $this->calculationService->calculateChange(
            $this->paidAmount,
            $this->total
        );
        $this->changeBreakdown = $this->calculationService->calculateChangeBreakdown($this->change);
    }

    /**
     * Calcule les montants suggérés
     */
    public function calculateSuggestedAmounts(): void
    {
        $this->suggestedAmounts = $this->calculationService->calculateSuggestedAmounts($this->total);
    }

    /**
     * Utilise un montant suggéré
     */
    public function useSuggestedAmount(float $amount): void
    {
        $this->paidAmount = $amount;
        $this->calculateChange();
    }

    /**
     * Écoute le trigger de paiement (raccourci clavier)
     */
    #[On('trigger-payment')]
    public function onTriggerPayment(): void
    {
        if (!empty($this->cart) && $this->paidAmount >= $this->total) {
            $this->processPayment();
        }
    }

    /**
     * Traite le paiement avec impression
     */
    public function processPayment(): void
    {
        $this->processPaymentInternal(autoPrint: true);
    }

    /**
     * Traite le paiement sans impression
     */
    public function processPaymentOnly(): void
    {
        $this->processPaymentInternal(autoPrint: false);
    }

    /**
     * Logique de traitement du paiement
     */
    private function processPaymentInternal(bool $autoPrint): void
    {
        $this->successMessage = '';
        $this->errorMessage = '';

        try {
            $items = $this->cartManager->getItemsForSale();
            $stockValidation = $this->cartManager->validateStock();

            $paymentData = new PaymentData(
                userId: $this->getUserId(),
                clientId: $this->clientId,
                storeId: current_store_id(),
                paymentMethod: $this->paymentMethod,
                items: $items,
                discount: $this->discount,
                tax: $this->tax,
                paidAmount: $this->paidAmount,
                total: $this->total,
                notes: $this->notes,
                stockValidation: $stockValidation
            );

            $result = $this->paymentService->process($paymentData);

            // Succès
            $this->handleSuccessfulPayment($result, $autoPrint);

        } catch (CartEmptyException | InsufficientPaymentException | InsufficientStockException $e) {
            $this->errorMessage = $e->getMessage();
            $this->dispatch('show-toast', message: $e->getMessage(), type: 'warning');

        } catch (\Exception $e) {
            $this->errorMessage = 'Erreur lors du traitement du paiement.';
            $this->dispatch('show-toast', message: 'Erreur technique: ' . $e->getMessage(), type: 'error');
        }
    }

    /**
     * Gère le succès du paiement
     */
    private function handleSuccessfulPayment($result, bool $autoPrint): void
    {
        $this->lastSaleId = $result->sale->id;
        $this->lastInvoiceId = $result->invoice->id;
        $this->change = $result->change;
        $this->successMessage = 'Vente enregistrée avec succès !';

        // Vider le panier
        $this->cartManager->clear();
        $this->cart = [];
        session()->forget('pos_cart');

        // Dispatch pour notifier le panier
        $this->dispatch('payment-completed', saleId: $this->lastSaleId);

        // Impression automatique
        if ($autoPrint) {
            $this->dispatchPrintReceipt();
        }

        // Mettre à jour les stats
        $this->statsService->invalidateStatsCache($this->getUserId());
        $this->dispatch('stats-refresh');

        // Réinitialiser
        $this->reset(['discount', 'tax', 'notes', 'paidAmount']);
        $this->recalculateTotals();

        $this->dispatch('show-toast', message: 'Vente complétée avec succès!', type: 'success');
    }

    /**
     * Prévisualise le reçu
     */
    public function previewReceipt(): void
    {
        if ($this->lastSaleId && $this->lastInvoiceId) {
            $this->showReceipt = true;
        } else {
            $this->dispatch('show-toast', message: 'Aucune vente à prévisualiser.', type: 'warning');
        }
    }

    /**
     * Ferme le reçu
     */
    public function closeReceipt(): void
    {
        $this->showReceipt = false;
    }

    /**
     * Imprime le reçu
     */
    public function printReceipt(): void
    {
        $this->dispatchPrintReceipt();
    }

    /**
     * Dispatch l'événement d'impression
     */
    private function dispatchPrintReceipt(): void
    {
        if (!$this->lastSaleId || !$this->lastInvoiceId) {
            return;
        }

        $lastSale = Sale::with(['items.productVariant.product'])->find($this->lastSaleId);
        $lastInvoice = Invoice::with('organization')->find($this->lastInvoiceId);

        if ($lastSale && $lastInvoice) {
            $receiptData = $this->printerService->prepareReceiptData(
                $lastSale,
                $lastInvoice,
                $this->change
            );
            $this->dispatch('print-thermal-receipt', $receiptData);
        }
    }

    /**
     * Obtient l'ID utilisateur
     */
    private function getUserId(): int
    {
        $userId = auth()->id();
        if (!$userId) {
            throw new \RuntimeException('Utilisateur non authentifié');
        }
        return $userId;
    }

    /**
     * Computed: Dernière vente
     */
    public function getLastSaleProperty()
    {
        if (!$this->lastSaleId) {
            return null;
        }

        return Sale::with(['items.productVariant.product', 'client', 'store', 'user'])
            ->find($this->lastSaleId);
    }

    /**
     * Computed: Dernière facture
     */
    public function getLastInvoiceProperty()
    {
        if (!$this->lastInvoiceId) {
            return null;
        }

        return Invoice::with('organization')->find($this->lastInvoiceId);
    }

    /**
     * Vérifie si le panier est vide
     */
    public function getIsCartEmptyProperty(): bool
    {
        return empty($this->cart);
    }

    /**
     * Vérifie si le paiement est valide
     */
    public function getCanProcessPaymentProperty(): bool
    {
        return !empty($this->cart) && $this->paidAmount >= $this->total;
    }

    public function render()
    {
        return view('livewire.pos.components.pos-payment-panel');
    }
}
