<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Str;

class QRCodeGeneratorService
{
    /**
     * Generate a unique QR code for a product.
     * Format: QR-XXXXXXXX (8 alphanumeric characters)
     */
    public function generateForProduct(): string
    {
        do {
            // Generate QR code with format: QR-XXXXXXXX
            $qrCode = 'QR-' . strtoupper(Str::random(8));

            // Check if it already exists
            $exists = Product::where('qr_code', $qrCode)->exists();
        } while ($exists);

        return $qrCode;
    }

    /**
     * Generate a unique QR code with custom prefix.
     */
    public function generateWithPrefix(string $prefix, int $length = 8): string
    {
        do {
            $qrCode = strtoupper($prefix) . '-' . strtoupper(Str::random($length));
            $exists = Product::where('qr_code', $qrCode)->exists();
        } while ($exists);

        return $qrCode;
    }

    /**
     * Generate a numeric QR code.
     * Format: QR-12345678
     */
    public function generateNumeric(int $length = 8): string
    {
        do {
            $numbers = '';
            for ($i = 0; $i < $length; $i++) {
                $numbers .= random_int(0, 9);
            }
            $qrCode = 'QR-' . $numbers;
            $exists = Product::where('qr_code', $qrCode)->exists();
        } while ($exists);

        return $qrCode;
    }

    /**
     * Generate a QR code based on product reference.
     * Format: QR-REF-XXXX
     */
    public function generateFromReference(string $reference): string
    {
        // Clean reference (remove special characters)
        $cleanRef = preg_replace('/[^A-Za-z0-9]/', '', $reference);

        do {
            $suffix = strtoupper(Str::random(4));
            $qrCode = 'QR-' . strtoupper($cleanRef) . '-' . $suffix;
            $exists = Product::where('qr_code', $qrCode)->exists();
        } while ($exists);

        return $qrCode;
    }

    /**
     * Validate QR code format.
     */
    public function isValid(string $qrCode): bool
    {
        return preg_match('/^QR-[A-Z0-9]{4,}/', $qrCode) === 1;
    }
}
