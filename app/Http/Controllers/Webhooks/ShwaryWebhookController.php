<?php

declare(strict_types=1);

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\ShwaryTransaction;
use App\Services\ShwaryPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Contrôleur pour gérer les webhooks de Shwary Mobile Money
 */
class ShwaryWebhookController extends Controller
{
    public function __construct(
        private ShwaryPaymentService $shwaryService
    ) {}

    /**
     * Recevoir et traiter le callback de Shwary
     *
     * POST /webhooks/shwary
     */
    public function handleCallback(Request $request): JsonResponse
    {
        Log::info('Shwary webhook received', [
            'payload' => $request->all(),
            'headers' => $request->headers->all(),
        ]);

        try {
            $payload = $request->all();

            // Traiter le callback via le service
            $transaction = $this->shwaryService->handleCallback($payload);

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found',
                ], 404);
            }

            // Si le paiement est réussi, exécuter les actions post-paiement
            if ($transaction->isCompleted()) {
                $this->handleSuccessfulPayment($transaction);
            } elseif ($transaction->isFailed()) {
                $this->handleFailedPayment($transaction);
            }

            return response()->json([
                'success' => true,
                'message' => 'Webhook processed successfully',
                'transaction_id' => $transaction->id,
                'status' => $transaction->status,
            ]);
        } catch (\Exception $e) {
            Log::error('Shwary webhook error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Webhook processing failed',
            ], 500);
        }
    }

    /**
     * Vérifier manuellement le statut d'une transaction
     *
     * GET /webhooks/shwary/status/{transactionId}
     */
    public function checkStatus(string $transactionId): JsonResponse
    {
        $result = $this->shwaryService->getTransaction($transactionId);

        return response()->json($result);
    }

    /**
     * Gérer un paiement réussi
     */
    private function handleSuccessfulPayment(ShwaryTransaction $transaction): void
    {
        Log::info('Processing successful Shwary payment', [
            'transaction_id' => $transaction->id,
            'metadata' => $transaction->metadata,
        ]);

        $metadata = $transaction->metadata ?? [];

        // Si c'est un paiement d'abonnement d'organisation
        if (isset($metadata['organization_id'])) {
            $organization = Organization::find($metadata['organization_id']);

            if ($organization) {
                $organization->markPaymentCompleted(
                    paymentReference: $transaction->reference ?? $transaction->transaction_id,
                    paymentMethod: 'mobile_money',
                    amount: (float) $transaction->amount,
                    metadata: [
                        'shwary_transaction_id' => $transaction->transaction_id,
                        'shwary_local_id' => $transaction->id,
                        'phone_number' => $transaction->phone_number,
                        'country_code' => $transaction->country_code,
                        'webhook_received' => true,
                    ]
                );

                Log::info('Organization payment completed via webhook', [
                    'organization_id' => $organization->id,
                    'transaction_id' => $transaction->id,
                ]);
            }
        }

        // Déclencher l'événement de paiement réussi
        // event(new ShwaryPaymentCompleted($transaction));
    }

    /**
     * Gérer un paiement échoué
     */
    private function handleFailedPayment(ShwaryTransaction $transaction): void
    {
        Log::warning('Shwary payment failed', [
            'transaction_id' => $transaction->id,
            'metadata' => $transaction->metadata,
        ]);

        // Déclencher l'événement de paiement échoué
        // event(new ShwaryPaymentFailed($transaction));
    }
}
