<?php

namespace App\Livewire\Product;

use App\Services\ProductLabelService;
use Livewire\Component;

class LabelModal extends Component
{
    public $isOpen = false;
    public $productIds = [];

    // Label options
    public $format = 'medium';
    public $columns = 2;
    public $showPrice = true;
    public $showBarcode = true;
    public $showQrCode = true;

    protected $listeners = [
        'openLabelModal' => 'open',
    ];

    public function open($productIds = [])
    {
        // Livewire peut passer soit un array directement, soit un array associatif
        if (isset($productIds['productIds'])) {
            $this->productIds = $productIds['productIds'];
        } else {
            $this->productIds = is_array($productIds) ? $productIds : [$productIds];
        }
        $this->isOpen = true;
    }

    public function close()
    {
        $this->isOpen = false;
        $this->reset(['productIds', 'format', 'columns', 'showPrice', 'showBarcode', 'showQrCode']);
    }

    public function generate()
    {
        $this->validate([
            'format' => 'required|in:small,medium,large',
            'columns' => 'required|integer|min:1|max:4',
            'productIds' => 'required|array|min:1',
        ]);

        try {
            $labelService = app(ProductLabelService::class);

            $options = [
                'show_price' => $this->showPrice,
                'show_barcode' => $this->showBarcode,
                'show_qr_code' => $this->showQrCode,
            ];

            // Generate PDF using product IDs
            $pdf = $labelService->generateLabelsFromIds($this->productIds, $this->format, $this->columns, $options);

            // Create filename
            $filename = 'product-labels-' . date('Y-m-d-His') . '.pdf';

            // Store PDF temporarily
            $path = storage_path('app/temp/' . $filename);
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }
            file_put_contents($path, $pdf->output());

            // Dispatch event to download
            $this->dispatch('downloadPdf', url: route('download.temp.file', ['filename' => $filename]));
            $this->dispatch('show-toast', message: 'Étiquettes générées avec succès!', type: 'success');

            $this->close();

        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.product.label-modal');
    }
}
