<?php

use App\Models\Sale;

// Find sales with payments
$sales = Sale::with('payments')->whereHas('payments')->get();

echo "=== Analyse des paiements ===" . PHP_EOL . PHP_EOL;

foreach ($sales as $sale) {
    $totalPayments = $sale->payments->sum('amount');
    $paidAmountInDb = $sale->paid_amount;
    $total = $sale->total;
    
    echo "Vente: {$sale->sale_number}" . PHP_EOL;
    echo "  Total: " . number_format($total, 0, ',', ' ') . " CDF" . PHP_EOL;
    echo "  paid_amount (DB): " . number_format($paidAmountInDb, 0, ',', ' ') . " CDF" . PHP_EOL;
    echo "  Somme payments: " . number_format($totalPayments, 0, ',', ' ') . " CDF" . PHP_EOL;
    echo "  payment_status: {$sale->payment_status}" . PHP_EOL;
    echo "  Paiements enregistrés: {$sale->payments->count()}" . PHP_EOL;
    
    // Check inconsistency
    if ($paidAmountInDb != $totalPayments) {
        echo "  ⚠️  INCOHÉRENCE DÉTECTÉE!" . PHP_EOL;
        echo "  Correction: {$paidAmountInDb} -> {$totalPayments}" . PHP_EOL;
        
        // Fix it
        $sale->paid_amount = $totalPayments;
        $sale->updatePaymentStatus();
        
        echo "  ✅ Corrigé! Nouveau statut: {$sale->payment_status}" . PHP_EOL;
    } else {
        echo "  ✅ Cohérent" . PHP_EOL;
    }
    
    echo "  ---" . PHP_EOL;
    echo "  Détails des paiements:" . PHP_EOL;
    foreach ($sale->payments as $payment) {
        echo "    - " . number_format($payment->amount, 0, ',', ' ') . " CDF";
        echo " ({$payment->payment_method})";
        echo " le " . $payment->payment_date->format('d/m/Y H:i');
        if ($payment->notes) {
            echo " - {$payment->notes}";
        }
        echo PHP_EOL;
    }
    
    echo PHP_EOL;
}

echo "=== Analyse terminée ===" . PHP_EOL;
