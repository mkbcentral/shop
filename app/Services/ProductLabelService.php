<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Collection;

/**
 * Service pour générer des étiquettes de produits avec codes-barres et QR codes
 */
class ProductLabelService
{
    private BarcodeGeneratorPNG $barcodeGenerator;

    public function __construct()
    {
        $this->barcodeGenerator = new BarcodeGeneratorPNG();
    }

    /**
     * Génère un PDF d'étiquettes pour une liste d'IDs de produits
     *
     * @param array $productIds Liste des IDs de produits
     * @param string $format Format des étiquettes (small, medium, large)
     * @param int $columns Nombre de colonnes par page
     * @param array $options Options supplémentaires
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generateLabelsFromIds(array $productIds, string $format = 'medium', int $columns = 2, array $options = []): \Barryvdh\DomPDF\PDF
    {
        // Récupérer les produits par leurs IDs
        $products = Product::with('category')->whereIn('id', $productIds)->get();

        // Merger les options avec les paramètres
        $allOptions = array_merge($options, [
            'format' => $format,
            'columns' => $columns,
        ]);

        return $this->generateLabelsPDF($products, $allOptions);
    }

    /**
     * Génère un PDF d'étiquettes pour une liste de produits
     *
     * @param Collection $products Collection de Product ou ProductVariant
     * @param array $options Options de génération (format, colonnes, etc.)
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generateLabelsPDF(Collection $products, array $options = []): \Barryvdh\DomPDF\PDF
    {
        $labelData = $products->map(function ($item) {
            return $this->prepareLabelData($item);
        });

        $format = $options['format'] ?? 'small'; // small, medium, large
        $columns = $options['columns'] ?? 3;
        $showPrice = $options['show_price'] ?? true;
        $showQrCode = $options['show_qr_code'] ?? true;
        $showBarcode = $options['show_barcode'] ?? true;

        $pdf = Pdf::loadView('pdf.product-labels', [
            'labels' => $labelData,
            'format' => $format,
            'columns' => $columns,
            'showPrice' => $showPrice,
            'showQrCode' => $showQrCode,
            'showBarcode' => $showBarcode,
        ]);

        // Configuration du PDF selon le format
        $paperSize = match($format) {
            'small' => [0, 0, 226.77, 141.73], // 80mm x 50mm en points
            'medium' => [0, 0, 283.46, 198.43], // 100mm x 70mm
            'large' => 'a4',
            default => [0, 0, 226.77, 141.73],
        };

        $pdf->setPaper($paperSize, 'landscape');

        return $pdf;
    }

    /**
     * Génère un PDF d'étiquettes pour un seul produit avec ses variantes
     */
    public function generateProductLabelsPDF(Product $product, array $options = []): \Barryvdh\DomPDF\PDF
    {
        $variants = $product->variants;

        if ($variants->isEmpty()) {
            // Si pas de variantes, créer une étiquette pour le produit principal
            $items = collect([$product]);
        } else {
            $items = $variants;
        }

        return $this->generateLabelsPDF($items, $options);
    }

    /**
     * Prépare les données pour une étiquette
     */
    private function prepareLabelData($item): array
    {
        if ($item instanceof ProductVariant) {
            return $this->prepareVariantLabelData($item);
        }

        return $this->prepareProductLabelData($item);
    }

    /**
     * Prépare les données d'étiquette pour un produit
     */
    private function prepareProductLabelData(Product $product): array
    {
        $barcode = $product->barcode ?? $product->reference;
        $qrData = $this->generateQrCodeData($product);
        $price = (float) ($product->price ?? 0);

        return [
            'type' => 'product',
            'id' => $product->id,
            'name' => $product->name,
            'reference' => $product->reference,
            'barcode' => $barcode,
            'barcode_image' => $this->generateBarcodeImage($barcode),
            'qr_code_image' => $this->generateQrCodeImage($qrData),
            'qr_code_data' => $qrData,
            'price' => $price,
            'price_formatted' => format_currency($price),
            'category' => $product->category?->name ?? 'N/A',
            'sku' => $product->reference,
        ];
    }

    /**
     * Prépare les données d'étiquette pour une variante
     */
    private function prepareVariantLabelData(ProductVariant $variant): array
    {
        $barcode = $variant->sku;
        $qrData = $this->generateQrCodeData($variant);
        $price = (float) ($variant->price ?? $variant->product->price ?? 0);

        return [
            'type' => 'variant',
            'id' => $variant->id,
            'name' => $variant->product->name,
            'variant_name' => $variant->full_name ?? $variant->sku,
            'reference' => $variant->product->reference,
            'barcode' => $barcode,
            'barcode_image' => $this->generateBarcodeImage($barcode),
            'qr_code_image' => $this->generateQrCodeImage($qrData),
            'qr_code_data' => $qrData,
            'price' => $price,
            'price_formatted' => format_currency($price),
            'category' => $variant->product->category?->name ?? 'N/A',
            'sku' => $variant->sku,
        ];
    }

    /**
     * Génère l'image du code-barres en base64
     */
    private function generateBarcodeImage(string $code): string
    {
        try {
            // Génère un code-barres de type Code128
            $barcodeImage = $this->barcodeGenerator->getBarcode(
                $code,
                $this->barcodeGenerator::TYPE_CODE_128,
                2,
                50
            );

            return 'data:image/png;base64,' . base64_encode($barcodeImage);
        } catch (\Exception $e) {
            // En cas d'erreur, retourner une image vide
            return '';
        }
    }

    /**
     * Génère l'image du QR code en base64
     * Utilise une API externe ou génère un QR code simple
     */
    private function generateQrCodeImage(string $data): string
    {
        // Utilisation de l'API QR Server (gratuite)
        $url = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($data);

        try {
            $imageData = @file_get_contents($url);
            if ($imageData !== false) {
                return 'data:image/png;base64,' . base64_encode($imageData);
            }
        } catch (\Exception $e) {
            // En cas d'erreur, continuer sans QR code
        }

        // Alternative: générer un QR code basique en SVG (peut être amélioré)
        return $this->generateSimpleQrCodeSvg($data);
    }

    /**
     * Génère les données pour le QR code
     */
    private function generateQrCodeData($item): string
    {
        if ($item instanceof ProductVariant) {
            return json_encode([
                'type' => 'variant',
                'id' => $item->id,
                'sku' => $item->sku,
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'variant' => $item->full_name,
                'price' => $item->price ?? $item->product->price,
            ]);
        }

        return json_encode([
            'type' => 'product',
            'id' => $item->id,
            'reference' => $item->reference,
            'barcode' => $item->barcode,
            'name' => $item->name,
            'price' => $item->price,
        ]);
    }

    /**
     * Génère un QR code SVG simple (fallback)
     */
    private function generateSimpleQrCodeSvg(string $data): string
    {
        // Pour une meilleure qualité, il faudrait utiliser une bibliothèque dédiée
        // Pour l'instant, on retourne une image placeholder
        $placeholder = '<svg width="150" height="150" xmlns="http://www.w3.org/2000/svg"><rect width="150" height="150" fill="#f0f0f0"/><text x="75" y="75" text-anchor="middle" font-size="12" fill="#666">QR Code</text></svg>';
        return 'data:image/svg+xml;base64,' . base64_encode($placeholder);
    }

    /**
     * Génère des étiquettes pour plusieurs produits sélectionnés
     */
    public function generateBulkLabelsPDF(array $productIds, array $options = []): \Barryvdh\DomPDF\PDF
    {
        $products = Product::with(['category', 'variants'])
            ->whereIn('id', $productIds)
            ->get();

        // Si on veut inclure les variantes
        if ($options['include_variants'] ?? false) {
            $allItems = collect();
            foreach ($products as $product) {
                if ($product->variants->isNotEmpty()) {
                    $allItems = $allItems->merge($product->variants);
                } else {
                    $allItems->push($product);
                }
            }
            return $this->generateLabelsPDF($allItems, $options);
        }

        return $this->generateLabelsPDF($products, $options);
    }

    /**
     * Génère une étiquette unique pour un produit/variante
     */
    public function generateSingleLabel($item, array $options = []): \Barryvdh\DomPDF\PDF
    {
        return $this->generateLabelsPDF(collect([$item]), $options);
    }
}
