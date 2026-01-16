<?php

namespace App\Services\Pos;

class CalculationService
{
    /**
     * Calcule les totaux du panier
     */
    public function calculateTotals(array $cart, float $discount = 0, float $tax = 0): array
    {
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Calculer automatiquement la TVA à 16% du subtotal après remise
        $subtotalAfterDiscount = $subtotal - $discount;
        $calculatedTax = $subtotalAfterDiscount * 0.16;

        $total = $subtotal - $discount + $calculatedTax;

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $calculatedTax,
            'total' => max(0, $total),
        ];
    }

    /**
     * Calcule la monnaie rendue
     */
    public function calculateChange(float $paidAmount, float $total): float
    {
        return max(0, $paidAmount - $total);
    }

    /**
     * Décompose la monnaie rendue en billets et pièces (CDF)
     */
    public function calculateChangeBreakdown(float $change): array
    {
        $changeAmount = (int) $change;
        $breakdown = [];

        if ($changeAmount <= 0) {
            return $breakdown;
        }

        $denominations = [
            20000 => '20,000 CDF',
            10000 => '10,000 CDF',
            5000 => '5,000 CDF',
            1000 => '1,000 CDF',
            500 => '500 CDF',
            200 => '200 CDF',
            100 => '100 CDF',
            50 => '50 CDF',
            20 => '20 CDF',
            10 => '10 CDF',
            5 => '5 CDF',
            1 => '1 CDF',
        ];

        foreach ($denominations as $value => $label) {
            if ($changeAmount >= $value) {
                $count = floor($changeAmount / $value);
                $breakdown[] = [
                    'label' => $label,
                    'count' => $count,
                    'total' => $count * $value,
                    'value' => $value,
                ];
                $changeAmount = $changeAmount % $value;
            }
        }

        return $breakdown;
    }

    /**
     * Calcule les montants suggérés pour faciliter le paiement
     */
    public function calculateSuggestedAmounts(float $total): array
    {
        $totalRounded = (int) ceil($total);
        $suggested = [];

        // Montant exact
        $suggested[] = $totalRounded;

        // Arrondi au millier supérieur
        $roundedThousand = ceil($totalRounded / 1000) * 1000;
        if ($roundedThousand != $totalRounded) {
            $suggested[] = $roundedThousand;
        }

        // Montants ronds courants
        $commonAmounts = [5000, 10000, 20000, 50000];
        foreach ($commonAmounts as $amount) {
            if ($amount > $totalRounded && !in_array($amount, $suggested)) {
                $suggested[] = $amount;
            }
        }

        // Limiter à 5 suggestions
        return array_slice($suggested, 0, 5);
    }

    /**
     * Valide que le montant payé est suffisant
     */
    public function validatePayment(float $paidAmount, float $total): array
    {
        if ($paidAmount < $total) {
            return [
                'valid' => false,
                'message' => 'Montant payé insuffisant. Manque: ' . number_format($total - $paidAmount, 2) . ' CDF',
            ];
        }

        return ['valid' => true];
    }

    /**
     * Calcule les statistiques du jour
     */
    public function calculateDailyStats($sales): array
    {
        return [
            'sales_count' => $sales->count(),
            'revenue' => $sales->sum('total'),
            'transactions' => $sales->count(),
            'average_sale' => $sales->count() > 0 ? $sales->average('total') : 0,
        ];
    }

    /**
     * Formate les items pour l'impression
     */
    public function formatItemsForPrint($items): array
    {
        return $items->map(function($item) {
            return [
                'name' => $item->productVariant->product->name,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total' => $item->total_price,
            ];
        })->toArray();
    }

    /**
     * Calcule le pourcentage de réduction
     */
    public function calculateDiscountPercentage(float $subtotal, float $discount): float
    {
        if ($subtotal <= 0) {
            return 0;
        }

        return ($discount / $subtotal) * 100;
    }

    /**
     * Applique un pourcentage de réduction
     */
    public function applyDiscountPercentage(float $subtotal, float $percentage): float
    {
        return ($subtotal * $percentage) / 100;
    }
}
