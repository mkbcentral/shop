<?php

namespace App\Livewire\Sale;

use App\Actions\Sale\UpdateSaleAction;
use App\Actions\Sale\RecordPaymentAction;
use App\Livewire\Forms\SaleForm;
use App\Repositories\ClientRepository;
use App\Repositories\SaleRepository;
use App\Services\SaleService;
use App\Repositories\ProductVariantRepository;
use Livewire\Component;

class SaleEdit extends Component
{
    public $saleId;
    public $sale;

    public SaleForm $form;

    // Sale items
    public $items = [];
    public $selectedVariant = '';
    public $selectedQuantity = 1;
    public $selectedPrice = 0;
    public $selectedDiscount = 0;

    // Product search
    public $productSearch = '';
    public $searchResults = [];
    public $showSearchResults = false;

    // Calculated totals
    public $subtotal = 0;
    public $total = 0;
    
    // Payment tracking
    public $salePaidAmount = 0;
    public $saleRemainingAmount = 0;

    // Payment management
    public $paymentAmount = 0;
    public $paymentMethod = 'cash';
    public $paymentDate;
    public $paymentNotes = '';
    public $showPaymentModal = false;

    // Edit mode
    public $canEditItems = true;

    public function mount($id, SaleRepository $repository)
    {
        $this->saleId = $id;
        $this->sale = $repository->find($id);

        if (!$this->sale) {
            session()->flash('error', 'Vente introuvable.');
            return redirect()->route('sales.index');
        }

        // Load form data
        $this->form->setSale($this->sale);

        // Load items
        foreach ($this->sale->items as $item) {
            $this->items[] = [
                'id' => $item->id,
                'product_variant_id' => $item->product_variant_id,
                'name' => $item->productVariant->full_name,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'discount' => $item->discount,
                'total' => $item->subtotal,
            ];
        }

        $this->calculateTotals();

        // Disable item editing if sale is not pending
        $this->canEditItems = $this->sale->status === 'pending';

        // Initialize payment fields
        $this->paymentDate = now()->format('Y-m-d');
        $this->salePaidAmount = $this->sale->paid_amount;
        $this->saleRemainingAmount = $this->sale->remaining_amount;
        $this->paymentAmount = $this->sale->remaining_amount;
    }

    public function updatedProductSearch(ProductVariantRepository $variantRepository)
    {
        if (!$this->canEditItems) {
            return;
        }

        if (strlen($this->productSearch) < 2) {
            $this->searchResults = [];
            $this->showSearchResults = false;
            return;
        }

        $this->searchResults = $variantRepository->query()
            ->with(['product.category'])
            ->whereHas('product', function($query) {
                $query->where('name', 'like', '%' . $this->productSearch . '%')
                      ->orWhere('reference', 'like', '%' . $this->productSearch . '%');
            })
            ->orWhere('sku', 'like', '%' . $this->productSearch . '%')
            ->limit(10)
            ->get()
            ->map(function($variant) {
                return [
                    'id' => $variant->id,
                    'name' => $variant->full_name,
                    'price' => $variant->final_price,
                    'stock' => $variant->stock_quantity,
                    'sku' => $variant->sku,
                ];
            })
            ->toArray();

        $this->showSearchResults = count($this->searchResults) > 0;
    }

    public function selectProduct($variantId, ProductVariantRepository $variantRepository)
    {
        if (!$this->canEditItems) {
            session()->flash('error', 'Impossible de modifier les articles d\'une vente complétée ou annulée.');
            return;
        }

        $variant = $variantRepository->find($variantId);

        if (!$variant) {
            session()->flash('error', 'Produit introuvable.');
            return;
        }

        $this->selectedVariant = $variant->id;
        $this->selectedPrice = $variant->final_price;
        $this->selectedQuantity = 1;
        $this->selectedDiscount = 0;
        $this->productSearch = $variant->full_name;
        $this->showSearchResults = false;
    }

    public function addItem(ProductVariantRepository $variantRepository, SaleService $service)
    {
        if (!$this->canEditItems) {
            session()->flash('error', 'Impossible de modifier les articles d\'une vente complétée ou annulée.');
            return;
        }

        // Validate inputs
        if (!$this->selectedVariant) {
            session()->flash('error', 'Veuillez sélectionner un produit.');
            return;
        }

        if ($this->selectedQuantity <= 0) {
            session()->flash('error', 'La quantité doit être supérieure à 0.');
            return;
        }

        if ($this->selectedPrice < 0) {
            session()->flash('error', 'Le prix doit être supérieur ou égal à 0.');
            return;
        }

        try {
            // Add item through service
            $itemData = [
                'product_variant_id' => $this->selectedVariant,
                'quantity' => $this->selectedQuantity,
                'unit_price' => $this->selectedPrice,
                'discount' => $this->selectedDiscount,
            ];

            $service->addItemToSale($this->saleId, $itemData);

            // Reload sale
            $this->sale = $this->sale->fresh('items.productVariant');

            // Reload items
            $this->items = [];
            foreach ($this->sale->items as $item) {
                $this->items[] = [
                    'id' => $item->id,
                    'product_variant_id' => $item->product_variant_id,
                    'name' => $item->productVariant->full_name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'discount' => $item->discount,
                    'total' => $item->total,
                ];
            }

            $this->calculateTotals();
            $this->resetItemForm();

            session()->flash('success', 'Article ajouté avec succès.');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function removeItem($index, SaleService $service)
    {
        if (!$this->canEditItems) {
            session()->flash('error', 'Impossible de modifier les articles d\'une vente complétée ou annulée.');
            return;
        }

        if (!isset($this->items[$index]) || !isset($this->items[$index]['id'])) {
            session()->flash('error', 'Article introuvable.');
            return;
        }

        try {
            $itemId = $this->items[$index]['id'];
            $service->removeItemFromSale($this->saleId, $itemId);

            // Reload sale
            $this->sale = $this->sale->fresh('items.productVariant');

            // Reload items
            $this->items = [];
            foreach ($this->sale->items as $item) {
                $this->items[] = [
                    'id' => $item->id,
                    'product_variant_id' => $item->product_variant_id,
                    'name' => $item->productVariant->full_name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'discount' => $item->discount,
                    'total' => $item->total,
                ];
            }

            $this->calculateTotals();

            session()->flash('success', 'Article supprimé avec succès.');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }
    }

    private function calculateTotals()
    {
        $this->subtotal = collect($this->items)->sum('total');
        $this->total = $this->subtotal - $this->form->discount + $this->form->tax;
    }

    public function updatedFormDiscount()
    {
        $this->calculateTotals();
    }

    public function updatedFormTax()
    {
        $this->calculateTotals();
    }

    private function resetItemForm()
    {
        $this->selectedVariant = '';
        $this->selectedQuantity = 1;
        $this->selectedPrice = 0;
        $this->selectedDiscount = 0;
        $this->productSearch = '';
        $this->searchResults = [];
        $this->showSearchResults = false;
    }

    public function save(UpdateSaleAction $action)
    {
        try {
            // Validate form
            $this->form->validate();

            // Update sale
            $data = [
                'client_id' => $this->form->client_id ?: null,
                'sale_date' => $this->form->sale_date,
                'payment_method' => $this->form->payment_method,
                'payment_status' => $this->form->payment_status,
                'status' => $this->form->status,
                'discount' => $this->form->discount,
                'tax' => $this->form->tax,
                'paid_amount' => $this->form->paid_amount,
                'notes' => $this->form->notes ?: null,
            ];

            $action->execute($this->saleId, $data);

            session()->flash('success', 'Vente mise à jour avec succès.');

            return redirect()->route('sales.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function openPaymentModal()
    {
        $this->paymentAmount = $this->saleRemainingAmount;
        $this->paymentMethod = 'cash';
        $this->paymentDate = now()->format('Y-m-d');
        $this->paymentNotes = '';
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
    }

    public function recordPayment(RecordPaymentAction $action)
    {
        try {
            $this->validate([
                'paymentAmount' => 'required|numeric|min:0.01|max:' . $this->saleRemainingAmount,
                'paymentMethod' => 'required|in:cash,card,transfer,cheque',
                'paymentDate' => 'required|date',
            ]);

            $action->execute($this->saleId, [
                'user_id' => auth()->id(),
                'amount' => $this->paymentAmount,
                'payment_method' => $this->paymentMethod,
                'payment_date' => $this->paymentDate,
                'notes' => $this->paymentNotes ?: null,
            ]);

            // Refresh sale and payment values
            $this->sale = app(SaleRepository::class)->find($this->saleId);
            $this->sale->load('payments'); // Force reload payments relation
            $this->salePaidAmount = $this->sale->paid_amount;
            $this->saleRemainingAmount = $this->sale->remaining_amount;
            $this->paymentAmount = $this->sale->remaining_amount;

            // Reset payment form
            $this->paymentNotes = '';

            session()->flash('success', 'Paiement enregistré avec succès.');
            
            // Dispatch event to close modal
            $this->dispatch('payment-recorded');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function render(ClientRepository $clientRepository)
    {
        $clients = $clientRepository->all();

        return view('livewire.sale.sale-edit', [
            'clients' => $clients,
        ]);
    }
}
