<?php

namespace App\Livewire\Sale;

use App\Actions\Sale\CreateSaleAction;
use App\Livewire\Forms\SaleForm;
use App\Repositories\ClientRepository;
use App\Repositories\ProductVariantRepository;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class SaleCreate extends Component
{
    public SaleForm $form;

    // Sale items
    public $items = [];
    public $selectedVariant = '';
    public $selectedQuantity = 1;
    public $selectedPrice = 0;
    public $selectedDiscount = 0;

    // Product search
    public $productSearch = '';

    // Calculated totals
    public $subtotal = 0;
    public $total = 0;

    protected $listeners = ['productSelected'];

    public function mount()
    {
        // Initialize form with default values
        $this->form->sale_date = now()->format('Y-m-d');
        $this->form->payment_method = 'cash';
        $this->form->payment_status = 'pending';
        $this->form->status = 'pending';
    }

    /**
     * Computed property pour les résultats de recherche (comme GlobalSearch)
     */
    public function getSearchResultsProperty(): array
    {
        if (strlen($this->productSearch) < 2) {
            return [];
        }

        $variantRepository = app(ProductVariantRepository::class);
        
        return $variantRepository->query()
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
    }

    /**
     * Computed property pour vérifier si on a des résultats
     */
    public function getHasResultsProperty(): bool
    {
        return count($this->searchResults) > 0;
    }

    public function selectProduct($variantId, ProductVariantRepository $variantRepository)
    {
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
    }

    public function addItem(ProductVariantRepository $variantRepository)
    {
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

        // Check if item already exists
        foreach ($this->items as $key => $item) {
            if ($item['product_variant_id'] == $this->selectedVariant) {
                // Update existing item
                $this->items[$key]['quantity'] += $this->selectedQuantity;
                $this->calculateItemTotal($key);
                $this->calculateTotals();
                $this->resetItemForm();
                return;
            }
        }

        // Get variant details
        $variant = $variantRepository->find($this->selectedVariant);

        if (!$variant) {
            session()->flash('error', 'Produit introuvable.');
            return;
        }

        // Check stock availability
        if (!$variant->hasStock($this->selectedQuantity)) {
            session()->flash('error', "Stock insuffisant pour {$variant->full_name}. Stock disponible: {$variant->stock_quantity}");
            return;
        }

        // Add new item
        $this->items[] = [
            'product_variant_id' => $variant->id,
            'name' => $variant->full_name,
            'quantity' => $this->selectedQuantity,
            'unit_price' => $this->selectedPrice,
            'discount' => $this->selectedDiscount,
            'total' => ($this->selectedPrice * $this->selectedQuantity) - $this->selectedDiscount,
        ];

        $this->calculateTotals();
        $this->resetItemForm();
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotals();
    }

    public function updateItemQuantity($index)
    {
        if (isset($this->items[$index])) {
            $this->calculateItemTotal($index);
            $this->calculateTotals();
        }
    }

    public function updateItemPrice($index)
    {
        if (isset($this->items[$index])) {
            $this->calculateItemTotal($index);
            $this->calculateTotals();
        }
    }

    public function updateItemDiscount($index)
    {
        if (isset($this->items[$index])) {
            $this->calculateItemTotal($index);
            $this->calculateTotals();
        }
    }

    private function calculateItemTotal($index)
    {
        $item = $this->items[$index];
        $subtotal = $item['unit_price'] * $item['quantity'];
        $this->items[$index]['total'] = $subtotal - $item['discount'];
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

    public function updatedFormPaymentStatus()
    {
        // Auto-fill paid_amount when status changes to 'paid'
        if ($this->form->payment_status === 'paid') {
            $this->form->paid_amount = $this->total;
        } elseif ($this->form->payment_status === 'pending') {
            $this->form->paid_amount = 0;
        }
    }

    private function resetItemForm()
    {
        $this->selectedVariant = '';
        $this->selectedQuantity = 1;
        $this->selectedPrice = 0;
        $this->selectedDiscount = 0;
        $this->productSearch = '';
    }

    public function save(CreateSaleAction $action)
    {
        try {
            // Validate form
            $this->form->validate();

            // Validate items
            if (empty($this->items)) {
                session()->flash('error', 'Veuillez ajouter au moins un article à la vente.');
                return;
            }

            // Prepare data
            $data = $this->form->all();
            $data['user_id'] = Auth::id();
            $data['items'] = $this->items;
            $data['subtotal'] = $this->subtotal;
            $data['total'] = $this->total;
            
            // Convert empty client_id to null
            if (empty($data['client_id'])) {
                $data['client_id'] = null;
            }

            // Create sale
            $sale = $action->execute($data);

            session()->flash('success', 'Vente créée avec succès.');

            return redirect()->route('sales.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function render(ClientRepository $clientRepository)
    {
        $clients = $clientRepository->all();

        return view('livewire.sale.sale-create', [
            'clients' => $clients,
        ]);
    }
}
