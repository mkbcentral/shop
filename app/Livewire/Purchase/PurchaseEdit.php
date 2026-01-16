<?php

namespace App\Livewire\Purchase;

use App\Actions\Purchase\UpdatePurchaseAction;
use App\Livewire\Forms\PurchaseForm;
use App\Repositories\PurchaseRepository;
use App\Repositories\SupplierRepository;
use App\Repositories\ProductVariantRepository;
use Livewire\Component;

class PurchaseEdit extends Component
{
    public PurchaseForm $form;
    public $purchaseId;
    public $purchase;
    public $canEditItems = true;

    // Purchase items
    public $items = [];
    public $selectedVariant = '';
    public $selectedQuantity = 1;
    public $selectedPrice = 0;

    // Product search
    public $productSearch = '';
    public $searchResults = [];
    public $showSearchResults = false;

    // Calculated totals
    public $total = 0;

    public function mount($id, PurchaseRepository $repository)
    {
        $this->purchaseId = $id;

        $this->purchase = $repository->find($id);

        if (!$this->purchase) {
            $this->dispatch('show-toast', message: 'Achat introuvable.', type: 'error');
            return redirect()->route('purchases.index');
        }

        if ($this->purchase->status === 'received') {
            $this->dispatch('show-toast', message: 'Impossible de modifier un achat déjà réceptionné.', type: 'error');
            return redirect()->route('purchases.index');
        }

        // Check if items can be edited
        $this->canEditItems = !in_array($this->purchase->status, ['completed', 'cancelled', 'received']);

        // Load form data
        $purchaseDate = $this->purchase->purchase_date;
        $this->form->fill([
            'supplier_id' => $this->purchase->supplier_id,
            'purchase_date' => $purchaseDate instanceof \Carbon\Carbon ? $purchaseDate->format('Y-m-d') : (string) $purchaseDate,
            'status' => $this->purchase->status,
            'payment_status' => $this->purchase->payment_status ?? 'pending',
            'paid_amount' => $this->purchase->paid_amount ?? 0,
            'notes' => $this->purchase->notes ?? '',
        ]);

        // Load items
        foreach ($this->purchase->items as $item) {
            $this->items[] = [
                'id' => $item->id,
                'product_variant_id' => $item->product_variant_id,
                'name' => $item->productVariant->full_name,
                'sku' => $item->productVariant->sku,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total' => $item->subtotal,
            ];
        }

        $this->calculateTotal();
    }

    public function updatedProductSearch(ProductVariantRepository $variantRepository)
    {
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
                    'price' => $variant->cost_price ?? $variant->final_price,
                    'cost_price' => $variant->cost_price ?? $variant->final_price,
                    'sku' => $variant->sku,
                    'stock' => $variant->stock_quantity ?? 0,
                ];
            })
            ->toArray();

        $this->showSearchResults = count($this->searchResults) > 0;
    }

    public function selectProduct($variantId, ProductVariantRepository $variantRepository)
    {
        $variant = $variantRepository->find($variantId);

        if (!$variant) {
            $this->dispatch('show-toast', message: 'Produit introuvable.', type: 'error');
            return;
        }

        $this->selectedVariant = $variant->id;
        $this->selectedPrice = $variant->cost_price ?? $variant->final_price;
        $this->selectedQuantity = 1;
        $this->productSearch = $variant->full_name;
        $this->showSearchResults = false;
    }

    public function addItem(ProductVariantRepository $variantRepository)
    {
        if (!$this->selectedVariant) {
            $this->dispatch('show-toast', message: 'Veuillez sélectionner un produit.', type: 'error');
            return;
        }

        if ($this->selectedQuantity <= 0) {
            $this->dispatch('show-toast', message: 'La quantité doit être supérieure à 0.', type: 'error');
            return;
        }

        $variant = $variantRepository->find($this->selectedVariant);

        $existingIndex = collect($this->items)->search(function ($item) {
            return $item['product_variant_id'] == $this->selectedVariant;
        });

        if ($existingIndex !== false) {
            $this->items[$existingIndex]['quantity'] += $this->selectedQuantity;
            $this->calculateItemTotal($existingIndex);
        } else {
            $this->items[] = [
                'product_variant_id' => $variant->id,
                'name' => $variant->full_name,
                'sku' => $variant->sku,
                'quantity' => $this->selectedQuantity,
                'unit_price' => $this->selectedPrice,
                'total' => $this->selectedQuantity * $this->selectedPrice,
            ];
        }

        $this->calculateTotal();
        $this->resetItemForm();
        $this->dispatch('show-toast', message: 'Article ajouté.', type: 'success');
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotal();
    }

    public function updateItemQuantity($index)
    {
        if ($this->items[$index]['quantity'] <= 0) {
            $this->items[$index]['quantity'] = 1;
        }
        $this->calculateItemTotal($index);
        $this->calculateTotal();
    }

    public function updateItemPrice($index)
    {
        if ($this->items[$index]['unit_price'] < 0) {
            $this->items[$index]['unit_price'] = 0;
        }
        $this->calculateItemTotal($index);
        $this->calculateTotal();
    }

    private function calculateItemTotal($index)
    {
        $item = $this->items[$index];
        $this->items[$index]['total'] = $item['unit_price'] * $item['quantity'];
    }

    private function calculateTotal()
    {
        $this->total = collect($this->items)->sum('total');
    }

    private function resetItemForm()
    {
        $this->selectedVariant = '';
        $this->selectedQuantity = 1;
        $this->selectedPrice = 0;
        $this->productSearch = '';
        $this->searchResults = [];
        $this->showSearchResults = false;
    }

    public function update(UpdatePurchaseAction $action)
    {
        try {
            $this->form->validate();

            if (empty($this->items)) {
                $this->dispatch('show-toast', message: 'Veuillez ajouter au moins un article à l\'achat.', type: 'error');
                return;
            }

            $data = $this->form->all();
            $data['items'] = $this->items;
            $data['total'] = $this->total;

            $action->execute($this->purchaseId, $data);

            $this->dispatch('show-toast', message: 'Achat modifié avec succès.', type: 'success');

            return redirect()->route('purchases.index');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur : ' . $e->getMessage(), type: 'error');
        }
    }

    public function render(SupplierRepository $supplierRepository)
    {
        $suppliers = $supplierRepository->all();

        // Calculate paid and remaining amounts
        $purchasePaidAmount = $this->purchase->payments ? $this->purchase->payments->sum('amount') : ($this->purchase->paid_amount ?? 0);
        $purchaseRemainingAmount = max(0, $this->total - $purchasePaidAmount);

        return view('livewire.purchase.purchase-edit', [
            'suppliers' => $suppliers,
            'purchasePaidAmount' => $purchasePaidAmount,
            'purchaseRemainingAmount' => $purchaseRemainingAmount,
        ]);
    }
}
