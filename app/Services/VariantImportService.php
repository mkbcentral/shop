<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductAttributeValue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VariantImportService
{
    /**
     * Import variants from CSV data
     *
     * Expected CSV format:
     * Référence_Produit, Attribut1, Attribut2, ..., Stock_Initial, Prix_Supplementaire, Code_Barres
     *
     * @param Product $product
     * @param array $csvData Array of rows with headers as keys
     * @return array ['success' => int, 'errors' => array]
     */
    public function importFromArray(Product $product, array $csvData): array
    {
        $successCount = 0;
        $errors = [];

        if (!$product->productType || !$product->productType->has_variants) {
            return [
                'success' => 0,
                'errors' => ['Le produit ne supporte pas les variantes']
            ];
        }

        $variantAttributes = $product->productType->variantAttributes;

        if ($variantAttributes->isEmpty()) {
            return [
                'success' => 0,
                'errors' => ['Aucun attribut variante défini pour ce type de produit']
            ];
        }

        DB::beginTransaction();

        try {
            foreach ($csvData as $index => $row) {
                try {
                    // Validate row
                    $validator = Validator::make($row, [
                        'Stock_Initial' => 'nullable|integer|min:0',
                        'Prix_Supplementaire' => 'nullable|numeric',
                        'Code_Barres' => 'nullable|string|unique:product_variants,barcode',
                    ]);

                    if ($validator->fails()) {
                        $errors[] = "Ligne " . ($index + 2) . ": " . implode(', ', $validator->errors()->all());
                        continue;
                    }

                    // Build attribute values
                    $attributeValues = [];
                    foreach ($variantAttributes as $attr) {
                        if (isset($row[$attr->name]) && !empty($row[$attr->name])) {
                            $attributeValues[$attr->id] = $row[$attr->name];
                        }
                    }

                    if (empty($attributeValues)) {
                        $errors[] = "Ligne " . ($index + 2) . ": Aucun attribut variante trouvé";
                        continue;
                    }

                    // Generate SKU
                    $skuParts = [$product->reference];
                    foreach ($attributeValues as $value) {
                        $skuParts[] = strtoupper(substr($value, 0, 3));
                    }
                    $sku = implode('-', $skuParts);

                    // Check if variant already exists
                    $existingVariant = $product->variants()
                        ->where('sku', $sku)
                        ->first();

                    if ($existingVariant) {
                        $errors[] = "Ligne " . ($index + 2) . ": Variante déjà existante (SKU: $sku)";
                        continue;
                    }

                    // Create variant
                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'organization_id' => $product->organization_id,
                        'sku' => $sku,
                        'barcode' => $row['Code_Barres'] ?? null,
                        'stock_quantity' => $row['Stock_Initial'] ?? 0,
                        'additional_price' => $row['Prix_Supplementaire'] ?? 0,
                        'low_stock_threshold' => 10,
                        'min_stock_threshold' => 0,
                    ]);

                    // Save attribute values
                    foreach ($attributeValues as $attrId => $value) {
                        ProductAttributeValue::create([
                            'product_variant_id' => $variant->id,
                            'product_attribute_id' => $attrId,
                            'value' => $value,
                        ]);
                    }

                    $successCount++;

                } catch (\Exception $e) {
                    $errors[] = "Ligne " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            DB::commit();

            return [
                'success' => $successCount,
                'errors' => $errors
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => 0,
                'errors' => ['Erreur générale: ' . $e->getMessage()]
            ];
        }
    }

    /**
     * Import variants from CSV file
     *
     * @param Product $product
     * @param string $filePath
     * @return array
     */
    public function importFromCSV(Product $product, string $filePath): array
    {
        if (!file_exists($filePath)) {
            return [
                'success' => 0,
                'errors' => ['Fichier non trouvé']
            ];
        }

        $csvData = [];
        $handle = fopen($filePath, 'r');

        if ($handle === false) {
            return [
                'success' => 0,
                'errors' => ['Impossible d\'ouvrir le fichier']
            ];
        }

        // Read header
        $headers = fgetcsv($handle);

        if ($headers === false) {
            fclose($handle);
            return [
                'success' => 0,
                'errors' => ['Fichier CSV vide ou invalide']
            ];
        }

        // Read rows
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) === count($headers)) {
                $csvData[] = array_combine($headers, $row);
            }
        }

        fclose($handle);

        return $this->importFromArray($product, $csvData);
    }

    /**
     * Generate CSV template for a product
     *
     * @param Product $product
     * @return string CSV content
     */
    public function generateTemplate(Product $product): string
    {
        if (!$product->productType || !$product->productType->has_variants) {
            return '';
        }

        $variantAttributes = $product->productType->variantAttributes;

        // Build headers
        $headers = ['Référence_Produit'];

        foreach ($variantAttributes as $attr) {
            $headers[] = $attr->name;
        }

        $headers[] = 'Stock_Initial';
        $headers[] = 'Prix_Supplementaire';
        $headers[] = 'Code_Barres';

        // Build example row
        $exampleRow = [$product->reference];

        foreach ($variantAttributes as $attr) {
            if ($attr->type === 'select' && !empty($attr->options)) {
                $exampleRow[] = $attr->options[0] ?? 'Exemple';
            } else {
                $exampleRow[] = 'Exemple';
            }
        }

        $exampleRow[] = '10';
        $exampleRow[] = '0';
        $exampleRow[] = '';

        // Generate CSV
        $output = fopen('php://temp', 'r+');
        fputcsv($output, $headers);
        fputcsv($output, $exampleRow);
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }

    /**
     * Download template as file
     *
     * @param Product $product
     * @param string $filename
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadTemplate(Product $product, string $filename = null)
    {
        $filename = $filename ?? 'template_variantes_' . $product->reference . '.csv';
        $csv = $this->generateTemplate($product);

        return response()->streamDownload(function() use ($csv) {
            echo $csv;
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
