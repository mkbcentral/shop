<?php

namespace App\Livewire\Purchase;

use App\Actions\Purchase\CreatePurchaseAction;
use App\Livewire\Forms\PurchaseForm;
use App\Repositories\SupplierRepository;
use App\Repositories\ProductVariantRepository;
use Livewire\Component;

class PurchaseCreate extends Component
{
    public PurchaseForm $form;

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

    protected $listeners = ['productSelected'];

    public function mount()
    {
        // Initialize form with default values
        $this->form->purchase_date = now()->format('Y-m-d');
        $this->form->status = 'pending';
        $this->form->payment_status = 'pending';
        $this->form->paid_amount = 0;
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
        // Validate inputs
        if (!$this->selectedVariant) {
            $this->dispatch('show-toast', message: 'Veuillez sélectionner un produit.', type: 'error');
            return;
        }

        if ($this->selectedQuantity <= 0) {
            $this->dispatch('show-toast', message: 'La quantité doit être supérieure à 0.', type: 'error');
            return;
        }

        if ($this->selectedPrice < 0) {
            $this->dispatch('show-toast', message: 'Le prix doit être supérieur ou égal à 0.', type: 'error');
            return;
        }

        // Get variant details
        $variant = $variantRepository->find($this->selectedVariant);

        // Check if item already exists
        $existingIndex = collect($this->items)->search(function ($item) {
            return $item['product_variant_id'] == $this->selectedVariant;
        });

        if ($existingIndex !== false) {
            // Update existing item
            $this->items[$existingIndex]['quantity'] += $this->selectedQuantity;
            $this->calculateItemTotal($existingIndex);
        } else {
            // Add new item
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

    public function save(CreatePurchaseAction $action)
    {
        try {
            // Validate form
            $this->form->validate();

            // Validate items
            if (empty($this->items)) {
                $this->dispatch('show-toast', message: 'Veuillez ajouter au moins un article à l\'achat.', type: 'error');
                return;
            }

            // Prepare data
            $data = $this->form->all();
            $data['items'] = $this->items;
            $data['total'] = $this->total;

            // Create purchase
            $purchase = $action->execute($data);

            $this->dispatch('show-toast', message: 'Achat créé avec succès.', type: 'success');

            return redirect()->route('purchases.index');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur : ' . $e->getMessage(), type: 'error');
        }
    }

    public function render(SupplierRepository $supplierRepository)
    {
        $suppliers = $supplierRepository->all();

        return view('livewire.purchase.purchase-create', [
            'suppliers' => $suppliers,
        ]);
    }
}
