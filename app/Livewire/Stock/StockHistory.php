<?php

namespace App\Livewire\Stock;

use App\Repositories\StockMovementRepository;
use App\Repositories\ProductVariantRepository;
use Livewire\Component;

class StockHistory extends Component
{
    public $variantId;
    public $limit = 20;

    public function mount($variantId)
    {
        $this->variantId = $variantId;
    }

    public function render(StockMovementRepository $movementRepository, ProductVariantRepository $variantRepository)
    {
        $variant = $variantRepository->find($this->variantId);

        if (!$variant) {
            abort(404, 'Produit non trouvé');
        }

        $movements = $movementRepository->byProductVariant($this->variantId);

        if ($this->limit) {
            $movements = $movements->take($this->limit);
        }

        return view('livewire.stock.history', [
            'variant' => $variant,
            'movements' => $movements,
        ]);
    }

    public function loadMore()
    {
        $this->limit += 20;
    }
}
