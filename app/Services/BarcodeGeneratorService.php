<?php

namespace App\Services;

use App\Models\Product;

class BarcodeGeneratorService
{
    /**
     * Generate a unique EAN-13 barcode.
     * Format: 13 digits with check digit
     */
    public function generateEAN13(): string
    {
        do {
            // Generate 12 random digits
            $barcode = '2'; // Start with 2 for internal use
            for ($i = 0; $i < 11; $i++) {
                $barcode .= random_int(0, 9);
            }

            // Calculate and append check digit
            $barcode .= $this->calculateEAN13CheckDigit($barcode);

            // Check if it already exists
            $exists = Product::where('barcode', $barcode)->exists();
        } while ($exists);

        return $barcode;
    }

    /**
     * Generate a unique Code-128 barcode.
     * Format: Alphanumeric with prefix
     */
    public function generateCode128(string $prefix = 'BC'): string
    {
        do {
            $barcode = strtoupper($prefix);
            for ($i = 0; $i < 10; $i++) {
                $barcode .= random_int(0, 9);
            }

            $exists = Product::where('barcode', $barcode)->exists();
        } while ($exists);

        return $barcode;
    }

    /**
     * Generate a simple numeric barcode.
     * Format: 13 digits
     */
    public function generateNumeric(int $length = 13): string
    {
        do {
            $barcode = '';
            for ($i = 0; $i < $length; $i++) {
                $barcode .= random_int(0, 9);
            }

            $exists = Product::where('barcode', $barcode)->exists();
        } while ($exists);

        return $barcode;
    }

    /**
     * Calculate EAN-13 check digit.
     */
    private function calculateEAN13CheckDigit(string $barcode): int
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $digit = (int) $barcode[$i];
            // Multiply odd positions by 1, even positions by 3
            $sum += ($i % 2 === 0) ? $digit : $digit * 3;
        }

        $checkDigit = (10 - ($sum % 10)) % 10;
        return $checkDigit;
    }

    /**
     * Validate EAN-13 barcode format and check digit.
     */
    public function isValidEAN13(string $barcode): bool
    {
        if (strlen($barcode) !== 13 || !ctype_digit($barcode)) {
            return false;
        }

        $checkDigit = (int) substr($barcode, -1);
        $calculatedCheckDigit = $this->calculateEAN13CheckDigit(substr($barcode, 0, 12));

        return $checkDigit === $calculatedCheckDigit;
    }

    /**
     * Generate barcode based on product reference.
     */
    public function generateFromReference(string $reference): string
    {
        // Extract numbers from reference
        $numbers = preg_replace('/[^0-9]/', '', $reference);

        // Pad or truncate to 12 digits
        if (strlen($numbers) < 12) {
            $numbers = '2' . $numbers . str_repeat('0', 11 - strlen($numbers));
        } else {
            $numbers = '2' . substr($numbers, 0, 11);
        }

        // Add check digit
        $barcode = $numbers . $this->calculateEAN13CheckDigit($numbers);

        // If it exists, generate a random one
        if (Product::where('barcode', $barcode)->exists()) {
            return $this->generateEAN13();
        }

        return $barcode;
    }
}
