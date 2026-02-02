<?php

namespace App\Console\Commands;

use App\Models\ShwaryTransaction;
use App\Services\ShwaryPaymentService;
use Illuminate\Console\Command;

class TestShwaryStatus extends Command
{
    protected $signature = 'shwary:check-status {transactionId?} {--complete : Marquer comme complété}';
    protected $description = 'Vérifier le statut d\'une transaction Shwary';

    public function handle(ShwaryPaymentService $shwaryService)
    {
        $transactionId = $this->argument('transactionId');

        $transaction = $transactionId
            ? ShwaryTransaction::where('transaction_id', $transactionId)->first()
            : ShwaryTransaction::latest()->first();

        if (!$transaction) {
            $this->error('Aucune transaction trouvée');
            return 1;
        }

        $this->info("Transaction locale: ID={$transaction->id}, Status={$transaction->status}");
        $this->info("Transaction ID Shwary: {$transaction->transaction_id}");

        // Option pour marquer comme complété manuellement
        if ($this->option('complete')) {
            $transaction->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
            $this->info("✓ Transaction marquée comme complétée !");
            return 0;
        }

        $this->info("Vérification du statut via l'API Shwary...");

        $result = $shwaryService->getTransaction($transaction->transaction_id);

        $this->info("Résultat de l'API:");
        $this->line(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Recharger la transaction locale
        $transaction->refresh();
        $this->info("Statut local après mise à jour: {$transaction->status}");

        return 0;
    }
}
