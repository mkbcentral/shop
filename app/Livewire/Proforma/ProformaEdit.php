<?php

namespace App\Livewire\Proforma;

use App\Models\ProformaInvoice;
use App\Repositories\ProductVariantRepository;
use App\Services\ProformaService;
use Livewire\Component;

class ProformaEdit extends Component
{
    public ProformaInvoice $proforma;

    // Client info
    public $client_name = '';
    public $client_phone = '';
    public $client_email = '';
    public $client_address = '';

    // Proforma info
    public $proforma_date;
    public $valid_until;
    public $notes = '';
    public $terms_conditions = '';

    // Items
    public $items = [];
    public $selectedVariant = '';
    public $selectedQuantity = 1;
    public $selectedPrice = 0;
    public $selectedDiscount = 0;
    public $selectedDescription = '';

    // Product search
    public $productSearch = '';
    public $searchResults = [];
    public $showSearchResults = false;

    // Totals
    public $subtotal = 0;
    public $total = 0;

    protected $rules = [
        'client_name' => 'required|string|max:255',
        'client_phone' => 'nullable|string|max:50',
        'client_email' => 'nullable|email|max:255',
        'client_address' => 'nullable|string|max:500',
        'proforma_date' => 'required|date',
        'valid_until' => 'required|date|after_or_equal:proforma_date',
        'items' => 'required|array|min:1',
    ];

    protected $messages = [
        'client_name.required' => 'Le nom du client est obligatoire.',
        'proforma_date.required' => 'La date est obligatoire.',
        'valid_until.required' => 'La date de validité est obligatoire.',
        'valid_until.after_or_equal' => 'La date de validité doit être après la date de création.',
        'items.required' => 'Ajoutez au moins un article.',
        'items.min' => 'Ajoutez au moins un article.',
    ];

    public function mount(ProformaInvoice $proforma)
    {
        $this->proforma = $proforma->load('items.productVariant');

        if (!$proforma->canBeEdited()) {
            session()->flash('error', 'Cette proforma ne peut plus être modifiée.');
            return redirect()->route('proformas.show', $proforma);
        }

        // Fill form with existing data
        $this->client_name = $proforma->client_name;
        $this->client_phone = $proforma->client_phone;
        $this->client_email = $proforma->client_email;
        $this->client_address = $proforma->client_address;
        $this->proforma_date = $proforma->proforma_date->format('Y-m-d');
        $this->valid_until = $proforma->valid_until?->format('Y-m-d');
        $this->notes = $proforma->notes;
        $this->terms_conditions = $proforma->terms_conditions;

        // Load existing items
        foreach ($proforma->items as $item) {
            $this->items[] = [
                'product_variant_id' => $item->product_variant_id,
                'name' => $item->name,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'discount' => $item->discount,
                'tax_rate' => $item->tax_rate,
                'total' => $item->total,
            ];
        }

        $this->calculateTotals();
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
        $variant = $variantRepository->find($variantId);

        if (!$variant) {
            $this->dispatch('show-toast', message: 'Produit introuvable.', type: 'error');
            return;
        }

        $this->selectedVariant = $variant->id;
        $this->selectedPrice = $variant->final_price;
        $this->selectedQuantity = 1;
        $this->selectedDiscount = 0;
        $this->selectedDescription = $variant->full_name;
        $this->productSearch = $variant->full_name;
        $this->showSearchResults = false;
    }

    public function addItem(ProductVariantRepository $variantRepository)
    {
        if ($this->selectedQuantity <= 0) {
            $this->dispatch('show-toast', message: 'La quantité doit être supérieure à 0.', type: 'error');
            return;
        }

        if ($this->selectedPrice <= 0) {
            $this->dispatch('show-toast', message: 'Le prix doit être supérieur à 0.', type: 'error');
            return;
        }

        // Check if item already exists
        foreach ($this->items as $key => $item) {
            if ($item['product_variant_id'] == $this->selectedVariant && $this->selectedVariant) {
                $this->items[$key]['quantity'] += $this->selectedQuantity;
                $this->items[$key]['total'] = ($this->items[$key]['quantity'] * $this->items[$key]['unit_price']) - $this->items[$key]['discount'];
                $this->calculateTotals();
                $this->resetItemForm();
                return;
            }
        }

        $name = $this->selectedDescription;
        if ($this->selectedVariant) {
            $variant = $variantRepository->find($this->selectedVariant);
            $name = $variant ? $variant->full_name : $this->selectedDescription;
        }

        $this->items[] = [
            'product_variant_id' => $this->selectedVariant ?: null,
            'name' => $name ?: 'Article personnalisé',
            'description' => $this->selectedDescription,
            'quantity' => $this->selectedQuantity,
            'unit_price' => $this->selectedPrice,
            'discount' => $this->selectedDiscount,
            'tax_rate' => 0,
            'total' => ($this->selectedPrice * $this->selectedQuantity) - $this->selectedDiscount,
        ];

        $this->calculateTotals();
        $this->resetItemForm();
    }

    public function addCustomItem()
    {
        if (empty($this->selectedDescription)) {
            $this->dispatch('show-toast', message: 'Veuillez saisir une description pour l\'article.', type: 'error');
            return;
        }

        if ($this->selectedQuantity <= 0 || $this->selectedPrice <= 0) {
            $this->dispatch('show-toast', message: 'Quantité et prix doivent être supérieurs à 0.', type: 'error');
            return;
        }

        $this->items[] = [
            'product_variant_id' => null,
            'name' => $this->selectedDescription,
            'description' => $this->selectedDescription,
            'quantity' => $this->selectedQuantity,
            'unit_price' => $this->selectedPrice,
            'discount' => $this->selectedDiscount,
            'tax_rate' => 0,
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

    protected function resetItemForm()
    {
        $this->selectedVariant = '';
        $this->selectedQuantity = 1;
        $this->selectedPrice = 0;
        $this->selectedDiscount = 0;
        $this->selectedDescription = '';
        $this->productSearch = '';
        $this->searchResults = [];
        $this->showSearchResults = false;
    }

    protected function calculateTotals()
    {
        $this->subtotal = collect($this->items)->sum(fn($item) => $item['quantity'] * $item['unit_price']);
        $discount = collect($this->items)->sum('discount');
        $this->total = $this->subtotal - $discount;
    }

    public function save(ProformaService $service)
    {
        $this->validate();

        try {
            $service->update($this->proforma, [
                'client_name' => $this->client_name,
                'client_phone' => $this->client_phone,
                'client_email' => $this->client_email,
                'client_address' => $this->client_address,
                'proforma_date' => $this->proforma_date,
                'valid_until' => $this->valid_until,
                'notes' => $this->notes,
                'terms_conditions' => $this->terms_conditions,
            ], $this->items);

            session()->flash('success', 'Proforma mise à jour avec succès.');
            return redirect()->route('proformas.show', $this->proforma);
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.proforma.proforma-edit');
    }
}
