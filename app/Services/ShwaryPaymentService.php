<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ShwaryTransaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

/**
 * Service pour l'intégration de Shwary Mobile Money
 *
 * Documentation API: https://api.shwary.com
 *
 * Pays supportés:
 * - RDC (CD): +243, CDF
 * - Kenya (KE): +254, KES
 * - Uganda (UG): +256, UGX
 */
class ShwaryPaymentService
{
    private string $baseUrl;
    private string $merchantId;
    private string $merchantKey;
    private bool $sandbox;
    private int $timeout;

    /**
     * Mapping des codes ISO vers les codes Shwary API
     * Shwary utilise 'DRC' au lieu de 'CD' pour la RDC
     */
    private const COUNTRY_CODE_MAP = [
        'CD' => 'DRC',  // Congo -> DRC (format Shwary)
        'KE' => 'KE',   // Kenya
        'UG' => 'UG',   // Uganda
    ];

    public function __construct()
    {
        $this->baseUrl = config('shwary.base_url');
        $this->merchantId = config('shwary.merchant_id');
        $this->merchantKey = config('shwary.merchant_key');
        $this->sandbox = config('shwary.sandbox', true);
        $this->timeout = config('shwary.timeout', 30);
    }

    /**
     * Convertir le code ISO en code Shwary API
     */
    private function toShwaryCountryCode(string $isoCode): string
    {
        return self::COUNTRY_CODE_MAP[$isoCode] ?? $isoCode;
    }

    /**
     * Initier un paiement Mobile Money
     *
     * @param float $amount Montant à payer (minimum 100)
     * @param string $phoneNumber Numéro de téléphone du client (format international)
     * @param string|null $callbackUrl URL de callback pour les notifications
     * @param array $metadata Données additionnelles (organization_id, plan, etc.)
     * @return array Transaction response
     * @throws InvalidArgumentException
     */
    public function initiatePayment(
        float $amount,
        string $phoneNumber,
        ?string $callbackUrl = null,
        array $metadata = []
    ): array {
        // Détecter le pays à partir du numéro de téléphone
        $countryCode = $this->detectCountryFromPhone($phoneNumber);

        if (!$countryCode) {
            throw new InvalidArgumentException(
                'Numéro de téléphone invalide. Formats acceptés: +243 (RDC), +254 (Kenya), +256 (Uganda)'
            );
        }

        // Valider le montant minimum
        $minAmount = config("shwary.countries.{$countryCode}.min_amount", 100);
        if ($amount < $minAmount) {
            throw new InvalidArgumentException(
                "Le montant minimum est de {$minAmount} " . config("shwary.countries.{$countryCode}.currency")
            );
        }

        // Normaliser le numéro de téléphone
        $normalizedPhone = $this->normalizePhoneNumber($phoneNumber);

        // Convertir le code ISO en code Shwary API (CD -> DRC)
        $shwaryCountryCode = $this->toShwaryCountryCode($countryCode);

        // Construire l'URL de l'endpoint
        $endpoint = $this->sandbox
            ? "/merchants/payment/sandbox/{$shwaryCountryCode}"
            : "/merchants/payment/{$shwaryCountryCode}";

        // URL de callback (utilise la route API pour éviter CSRF et middleware web)
        $callback = $callbackUrl ?? config('shwary.callback_url') ?? url('/api/webhooks/shwary');

        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'x-merchant-id' => $this->merchantId,
                    'x-merchant-key' => $this->merchantKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($this->baseUrl . $endpoint, [
                    'amount' => $amount,
                    'clientPhoneNumber' => $normalizedPhone,
                    'callbackUrl' => $callback,
                ]);

            $data = $response->json();

            if ($response->successful()) {
                // Créer l'enregistrement de transaction locale
                $transaction = ShwaryTransaction::create([
                    'transaction_id' => $data['id'] ?? null,
                    'reference' => $data['reference'] ?? null,
                    'amount' => $amount,
                    'currency' => config("shwary.countries.{$countryCode}.currency"),
                    'phone_number' => $normalizedPhone,
                    'country_code' => $countryCode,
                    'status' => $data['status'] ?? 'pending',
                    'metadata' => $metadata,
                    'response_data' => $data,
                ]);

                return [
                    'success' => true,
                    'message' => 'Paiement initié avec succès. Veuillez confirmer sur votre téléphone.',
                    'transaction_id' => $transaction->id,
                    'shwary_transaction_id' => $data['id'] ?? null,
                    'reference' => $data['reference'] ?? null,
                    'status' => $data['status'] ?? 'pending',
                    'data' => $data,
                ];
            }

            Log::error('Shwary payment initiation failed', [
                'response' => $data,
                'status' => $response->status(),
            ]);

            // Traduire les codes d'erreur en messages utilisateur
            $errorMessage = $this->translateErrorMessage($data, $response->status());

            return [
                'success' => false,
                'message' => $errorMessage,
                'error_code' => $data['code'] ?? $data['errorCode'] ?? null,
                'error' => $data,
            ];
        } catch (\Exception $e) {
            Log::error('Shwary payment exception', [
                'message' => $e->getMessage(),
                'phone' => $normalizedPhone,
                'amount' => $amount,
            ]);

            return [
                'success' => false,
                'message' => 'Erreur de connexion au service de paiement: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Vérifier le statut d'une transaction
     *
     * @param string $transactionId ID de la transaction Shwary
     * @return array
     */
    public function getTransaction(string $transactionId): array
    {
        try {
            // L'endpoint pour récupérer une transaction (GET selon la doc)
            $endpoint = "/merchants/transactions/{$transactionId}";

            // Selon la doc Shwary: x-merchant-id et x-merchant-key
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'x-merchant-id' => $this->merchantId,
                    'x-merchant-key' => $this->merchantKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->get($this->baseUrl . $endpoint);

            $data = $response->json();

            Log::info('Shwary API getTransaction response', [
                'transaction_id' => $transactionId,
                'endpoint' => $endpoint,
                'method' => 'GET',
                'full_url' => $this->baseUrl . $endpoint,
                'http_status' => $response->status(),
                'response_successful' => $response->successful(),
                'data' => $data,
            ]);

            if ($response->successful()) {
                // Mettre à jour la transaction locale si elle existe
                $localTransaction = ShwaryTransaction::where('transaction_id', $transactionId)->first();
                if ($localTransaction) {
                    $newStatus = strtolower($data['status'] ?? $localTransaction->status);

                    Log::info('Shwary status update', [
                        'transaction_id' => $transactionId,
                        'old_status' => $localTransaction->status,
                        'new_status' => $newStatus,
                        'api_status_field' => $data['status'] ?? 'not_present',
                        'all_data_keys' => array_keys($data),
                    ]);

                    $updateData = [
                        'status' => $newStatus,
                        'response_data' => $data,
                    ];

                    // Mettre à jour completed_at si le statut est complété
                    if (in_array($newStatus, ['completed', 'success', 'paid']) && !$localTransaction->completed_at) {
                        $updateData['completed_at'] = now();
                    }

                    // Mettre à jour failed_at si le statut est échoué
                    if (in_array($newStatus, ['failed', 'cancelled', 'rejected', 'expired']) && !$localTransaction->failed_at) {
                        $updateData['failed_at'] = now();
                        $updateData['failure_reason'] = $data['failureReason'] ?? $data['error_message'] ?? $data['message'] ?? null;
                    }

                    $localTransaction->update($updateData);
                }

                return [
                    'success' => true,
                    'data' => $data,
                    'status' => $data['status'] ?? 'unknown',
                    'sandbox' => $this->sandbox,
                ];
            }

            return [
                'success' => false,
                'message' => $data['message'] ?? 'Transaction non trouvée',
                'sandbox' => $this->sandbox,
                'http_status' => $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('Shwary get transaction exception', [
                'message' => $e->getMessage(),
                'transaction_id' => $transactionId,
            ]);

            return [
                'success' => false,
                'message' => 'Erreur lors de la vérification: ' . $e->getMessage(),
                'sandbox' => $this->sandbox,
            ];
        }
    }

    /**
     * Traiter le callback de Shwary
     *
     * @param array $payload Données du callback
     * @return ShwaryTransaction|null
     */
    public function handleCallback(array $payload): ?ShwaryTransaction
    {
        $transactionId = $payload['id'] ?? $payload['transactionId'] ?? null;

        if (!$transactionId) {
            Log::warning('Shwary callback without transaction ID', $payload);
            return null;
        }

        $transaction = ShwaryTransaction::where('transaction_id', $transactionId)->first();

        if (!$transaction) {
            Log::warning('Shwary callback for unknown transaction', [
                'transaction_id' => $transactionId,
                'payload' => $payload,
            ]);
            return null;
        }

        $newStatus = strtolower($payload['status'] ?? 'unknown');
        $oldStatus = $transaction->status;

        // Extraire le message d'erreur si présent
        $failureReason = $payload['failureReason'] ?? $payload['error_message'] ?? $payload['message'] ?? null;

        $transaction->update([
            'status' => $newStatus,
            'failure_reason' => $failureReason,
            'response_data' => array_merge($transaction->response_data ?? [], ['callback' => $payload]),
            'completed_at' => in_array($newStatus, ['completed', 'success', 'paid']) ? now() : null,
            'failed_at' => in_array($newStatus, ['failed', 'cancelled', 'rejected', 'expired']) ? now() : null,
        ]);

        Log::info('Shwary callback processed', [
            'transaction_id' => $transactionId,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'failure_reason' => $failureReason,
        ]);

        return $transaction;
    }

    /**
     * Obtenir un message lisible pour le statut de la transaction
     *
     * @param ShwaryTransaction $transaction
     * @return string
     */
    public function getStatusMessage(ShwaryTransaction $transaction): string
    {
        $status = $transaction->status;
        $failureReason = $transaction->failure_reason ?? null;

        // Si on a une raison d'échec, essayer de la traduire
        if ($failureReason && $transaction->isFailed()) {
            return $this->translateErrorMessage([
                'message' => $failureReason,
                'code' => $status,
            ], 400);
        }

        return match ($status) {
            'pending' => 'En attente de votre confirmation sur le téléphone...',
            'processing' => 'Transaction en cours de traitement...',
            'completed', 'success' => 'Paiement effectué avec succès !',
            'failed' => 'Le paiement a échoué. Veuillez réessayer.',
            'cancelled' => 'Vous avez annulé la transaction.',
            'rejected' => 'La transaction a été refusée par l\'opérateur.',
            'expired' => 'La demande de paiement a expiré. Veuillez réessayer.',
            default => 'Statut inconnu: ' . $status,
        };
    }

    /**
     * Détecter le pays à partir du numéro de téléphone
     *
     * @param string $phoneNumber
     * @return string|null Code pays (CD, KE, UG)
     */
    public function detectCountryFromPhone(string $phoneNumber): ?string
    {
        $phone = preg_replace('/[^0-9+]/', '', $phoneNumber);

        $prefixes = [
            '+243' => 'CD',
            '+254' => 'KE',
            '+256' => 'UG',
        ];

        foreach ($prefixes as $prefix => $country) {
            if (str_starts_with($phone, $prefix)) {
                return $country;
            }
            // Check without + prefix
            $numericPrefix = ltrim($prefix, '+');
            if (str_starts_with($phone, $numericPrefix)) {
                return $country;
            }
        }

        return null;
    }

    /**
     * Normaliser le numéro de téléphone
     *
     * @param string $phoneNumber
     * @return string
     */
    public function normalizePhoneNumber(string $phoneNumber): string
    {
        // Supprimer tout sauf les chiffres et le +
        $phone = preg_replace('/[^0-9+]/', '', $phoneNumber);

        // S'assurer que le numéro commence par +
        if (!str_starts_with($phone, '+')) {
            // Détecter et ajouter le préfixe approprié
            if (str_starts_with($phone, '243')) {
                $phone = '+' . $phone;
            } elseif (str_starts_with($phone, '254')) {
                $phone = '+' . $phone;
            } elseif (str_starts_with($phone, '256')) {
                $phone = '+' . $phone;
            } elseif (str_starts_with($phone, '0')) {
                // Numéro local - utiliser le pays par défaut
                $defaultCountry = config('shwary.default_country', 'CD');
                $prefix = config("shwary.countries.{$defaultCountry}.phone_prefix");
                $phone = $prefix . substr($phone, 1);
            }
        }

        return $phone;
    }

    /**
     * Obtenir les pays supportés
     *
     * @return array
     */
    public function getSupportedCountries(): array
    {
        return config('shwary.countries', []);
    }

    /**
     * Vérifier si le service est configuré
     *
     * @return bool
     */
    public function isConfigured(): bool
    {
        return !empty($this->merchantId) && !empty($this->merchantKey);
    }

    /**
     * Obtenir les opérateurs pour un pays
     *
     * @param string $countryCode
     * @return array
     */
    public function getOperators(string $countryCode): array
    {
        return config("shwary.countries.{$countryCode}.operators", []);
    }

    /**
     * Traduire les codes d'erreur Shwary en messages utilisateur compréhensibles
     *
     * @param array $data Réponse de l'API
     * @param int $httpStatus Code HTTP de la réponse
     * @return string Message d'erreur traduit
     */
    private function translateErrorMessage(array $data, int $httpStatus): string
    {
        $errorCode = $data['code'] ?? $data['errorCode'] ?? $data['error_code'] ?? null;
        $originalMessage = $data['message'] ?? $data['error'] ?? null;

        // Mapping des codes d'erreur courants vers des messages utilisateur
        $errorMessages = [
            // Erreurs de solde
            'INSUFFICIENT_BALANCE' => 'Solde insuffisant sur votre compte Mobile Money. Veuillez recharger et réessayer.',
            'INSUFFICIENT_FUNDS' => 'Solde insuffisant sur votre compte Mobile Money. Veuillez recharger et réessayer.',
            'LOW_BALANCE' => 'Solde insuffisant sur votre compte Mobile Money. Veuillez recharger et réessayer.',

            // Erreurs de numéro
            'INVALID_PHONE' => 'Le numéro de téléphone saisi est invalide. Vérifiez et réessayez.',
            'INVALID_PHONE_NUMBER' => 'Le numéro de téléphone saisi est invalide. Vérifiez et réessayez.',
            'PHONE_NOT_REGISTERED' => 'Ce numéro n\'est pas enregistré pour le Mobile Money. Vérifiez votre numéro.',
            'INVALID_MSISDN' => 'Le format du numéro de téléphone est incorrect.',

            // Erreurs de transaction
            'TRANSACTION_FAILED' => 'La transaction a échoué. Veuillez réessayer.',
            'TRANSACTION_TIMEOUT' => 'Délai d\'attente dépassé. Veuillez réessayer.',
            'TRANSACTION_DECLINED' => 'La transaction a été refusée par l\'opérateur.',
            'DUPLICATE_TRANSACTION' => 'Une transaction similaire est déjà en cours. Patientez quelques instants.',

            // Erreurs utilisateur
            'USER_CANCELLED' => 'Vous avez annulé la transaction sur votre téléphone.',
            'USER_REJECTED' => 'Vous avez refusé la transaction.',
            'PIN_LOCKED' => 'Votre code PIN Mobile Money est bloqué. Contactez votre opérateur.',
            'WRONG_PIN' => 'Code PIN incorrect. Veuillez réessayer avec le bon code.',

            // Erreurs de limite
            'AMOUNT_TOO_LOW' => 'Le montant est inférieur au minimum autorisé.',
            'AMOUNT_TOO_HIGH' => 'Le montant dépasse la limite autorisée.',
            'DAILY_LIMIT_EXCEEDED' => 'Vous avez atteint votre limite de transactions journalière.',
            'MONTHLY_LIMIT_EXCEEDED' => 'Vous avez atteint votre limite de transactions mensuelle.',

            // Erreurs opérateur
            'OPERATOR_ERROR' => 'Erreur de l\'opérateur Mobile Money. Réessayez plus tard.',
            'SERVICE_UNAVAILABLE' => 'Le service Mobile Money est temporairement indisponible.',
            'NETWORK_ERROR' => 'Problème de connexion réseau. Vérifiez votre connexion et réessayez.',

            // Erreurs d'authentification merchant
            'INVALID_MERCHANT' => 'Erreur de configuration du service de paiement. Contactez le support.',
            'UNAUTHORIZED' => 'Erreur d\'authentification. Contactez le support.',
            'INVALID_API_KEY' => 'Clé API invalide. Contactez le support.',
        ];

        // Chercher le code d'erreur dans le mapping
        if ($errorCode && isset($errorMessages[strtoupper($errorCode)])) {
            return $errorMessages[strtoupper($errorCode)];
        }

        // Analyser le message original pour détecter des mots-clés
        if ($originalMessage) {
            $lowerMessage = strtolower($originalMessage);

            if (str_contains($lowerMessage, 'insufficient') || str_contains($lowerMessage, 'solde') || str_contains($lowerMessage, 'balance')) {
                return 'Solde insuffisant sur votre compte Mobile Money. Veuillez recharger et réessayer.';
            }

            if (str_contains($lowerMessage, 'invalid phone') || str_contains($lowerMessage, 'numéro invalide')) {
                return 'Le numéro de téléphone saisi est invalide. Vérifiez et réessayez.';
            }

            if (str_contains($lowerMessage, 'timeout') || str_contains($lowerMessage, 'délai')) {
                return 'Délai d\'attente dépassé. Veuillez réessayer.';
            }

            if (str_contains($lowerMessage, 'cancelled') || str_contains($lowerMessage, 'annulé')) {
                return 'La transaction a été annulée.';
            }

            if (str_contains($lowerMessage, 'rejected') || str_contains($lowerMessage, 'declined') || str_contains($lowerMessage, 'refusé')) {
                return 'La transaction a été refusée.';
            }

            // Retourner le message original s'il est compréhensible
            return $originalMessage;
        }

        // Messages par défaut basés sur le code HTTP
        return match ($httpStatus) {
            400 => 'Données de paiement invalides. Vérifiez les informations saisies.',
            401, 403 => 'Erreur d\'authentification. Contactez le support.',
            404 => 'Service de paiement non trouvé.',
            408 => 'Délai d\'attente dépassé. Veuillez réessayer.',
            429 => 'Trop de tentatives. Patientez quelques minutes avant de réessayer.',
            500, 502, 503 => 'Le service de paiement est temporairement indisponible. Réessayez plus tard.',
            default => 'Erreur lors de l\'initiation du paiement. Veuillez réessayer.',
        };
    }
}
