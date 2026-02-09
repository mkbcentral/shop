<?php

namespace App\Livewire\Organization;

use App\Enums\PaymentStatus;
use App\Enums\SubscriptionPlan;
use App\Models\Organization;
use App\Models\ShwaryTransaction;
use App\Models\SubscriptionHistory;
use App\Services\ShwaryPaymentService;
use App\Services\SubscriptionService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;

class PaymentModal extends Component
{
    public ?Organization $organization = null;
    public bool $showModal = false;
    public string $paymentMethod = 'mobile_money';
    public array $planData = [];
    public string $currency = '€';
    public bool $isRenewal = false; // Indique si c'est un renouvellement
    public bool $isUpgrade = false; // Indique si c'est un upgrade de plan
    public ?string $targetPlan = null; // Le plan cible pour l'upgrade
    public ?string $currentPlan = null; // Le plan actuel

    // Shwary Mobile Money
    public string $phoneNumber = '';
    public string $selectedCountry = 'CD';
    public bool $isProcessing = false;
    public ?string $paymentStatus = null;
    public ?string $paymentMessage = null;
    public ?int $pendingTransactionId = null;
    public int $checkStatusInterval = 5000; // 5 secondes

    protected $rules = [
        'phoneNumber' => ['required', 'string', 'min:9', 'max:12', 'regex:/^[0-9\s]+$/'],
    ];

    protected $messages = [
        'phoneNumber.required' => 'Le numéro de téléphone est requis',
        'phoneNumber.min' => 'Le numéro de téléphone doit avoir au moins 9 chiffres',
        'phoneNumber.max' => 'Le numéro de téléphone ne doit pas dépasser 12 chiffres',
        'phoneNumber.regex' => 'Le numéro de téléphone ne doit contenir que des chiffres',
    ];

    public function mount()
    {
        $user = Auth::user();

        if (!$user) {
            return;
        }

        $this->organization = $user->defaultOrganization;

        if (!$this->organization) {
            return;
        }

        // Check if payment is needed
        if (!$this->organization->isAccessible()) {
            $this->showModal = true;

            // Load plan data from cache
            $allPlans = SubscriptionService::getPlansFromCache();
            $planSlug = $this->organization->subscription_plan->value;
            $this->planData = $allPlans[$planSlug] ?? [];
            $this->currency = SubscriptionService::getCurrencyFromCache();
        }

        // Vérifier si une transaction est en attente
        $this->checkPendingTransaction();
    }

    /**
     * Vérifier si une transaction Shwary est en attente (non expirée)
     */
    public function checkPendingTransaction(): void
    {
        if (!$this->organization) {
            return;
        }

        // Expirer automatiquement les transactions de plus de 10 minutes
        $expiredCount = ShwaryTransaction::pending()
            ->forOrganization($this->organization->id)
            ->where('created_at', '<', now()->subMinutes(10))
            ->update([
                'status' => ShwaryTransaction::STATUS_EXPIRED,
                'failed_at' => now(),
                'failure_reason' => 'Transaction expirée (délai dépassé)',
            ]);

        if ($expiredCount > 0) {
            Log::info('Expired stale pending transactions', ['count' => $expiredCount]);
        }

        // Charger uniquement les transactions récentes (< 10 min)
        $pendingTransaction = ShwaryTransaction::pending()
            ->forOrganization($this->organization->id)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->latest()
            ->first();

        if ($pendingTransaction) {
            $this->pendingTransactionId = $pendingTransaction->id;
            $this->paymentStatus = 'pending';
            $this->paymentMessage = 'Un paiement est en attente de confirmation. Veuillez valider sur votre téléphone.';
        }
    }

    /**
     * Initier un paiement Mobile Money via Shwary
     */
    public function processShwaryPayment(): void
    {
        $this->validate();

        if (!$this->organization) {
            $this->paymentStatus = 'error';
            $this->paymentMessage = 'Organisation non trouvée';
            return;
        }

        $this->isProcessing = true;
        $this->paymentStatus = null;
        $this->paymentMessage = null;

        try {
            $shwaryService = app(ShwaryPaymentService::class);

            // Vérifier que le service est configuré
            if (!$shwaryService->isConfigured()) {
                $this->paymentStatus = 'error';
                $this->paymentMessage = 'Le service de paiement Mobile Money n\'est pas configuré. Veuillez contacter l\'administrateur.';
                $this->isProcessing = false;
                return;
            }

            $amount = $this->planData['price'] ?? 0;

            if ($amount <= 0) {
                $this->paymentStatus = 'error';
                $this->paymentMessage = 'Montant invalide';
                $this->isProcessing = false;
                return;
            }

            // Construire le numéro complet avec le préfixe du pays
            $phonePrefix = $this->phonePrefix;
            $phoneNumber = $this->phoneNumber;

            // Nettoyer le numéro (enlever espaces, tirets, etc.)
            $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

            // Enlever le 0 initial si présent
            if (str_starts_with($phoneNumber, '0')) {
                $phoneNumber = substr($phoneNumber, 1);
            }

            // Construire le numéro complet
            $fullPhoneNumber = $phonePrefix . $phoneNumber;

            // Initier le paiement
            $result = $shwaryService->initiatePayment(
                amount: (float) $amount,
                phoneNumber: $fullPhoneNumber,
                metadata: [
                    'organization_id' => $this->organization->id,
                    'plan' => $this->organization->subscription_plan->value,
                    'user_id' => Auth::id(),
                ]
            );

            if ($result['success']) {
                $this->paymentStatus = 'pending';
                $this->paymentMessage = $result['message'];
                $this->pendingTransactionId = $result['transaction_id'];

                $this->dispatch('show-toast', message: 'Paiement initié ! Veuillez valider sur votre téléphone.', type: 'info');
                $this->dispatch('payment-initiated', [
                    'transactionId' => $result['transaction_id'],
                ]);
            } else {
                $this->paymentStatus = 'error';
                $this->paymentMessage = $result['message'];
                $this->dispatch('show-toast', message: $result['message'] ?? 'Erreur lors de l\'initiation du paiement', type: 'error');
            }
        } catch (\Exception $e) {
            $this->paymentStatus = 'error';
            $this->paymentMessage = 'Erreur: ' . $e->getMessage();
            $this->dispatch('show-toast', message: 'Une erreur est survenue. Veuillez réessayer.', type: 'error');
        } finally {
            $this->isProcessing = false;
        }
    }

    /**
     * Vérifier le statut du paiement en attente
     */
    public function checkPaymentStatus()
    {
        if (!$this->pendingTransactionId) {
            return;
        }

        $transaction = ShwaryTransaction::find($this->pendingTransactionId);

        if (!$transaction) {
            $this->paymentStatus = 'error';
            $this->paymentMessage = 'Transaction non trouvée';
            $this->dispatch('show-toast', message: 'Transaction non trouvée', type: 'error');
            return;
        }

        $shwaryService = app(ShwaryPaymentService::class);

        // Récupérer le statut actuel depuis l'API Shwary (polling)
        // Ceci met à jour automatiquement la transaction locale
        if ($transaction->transaction_id && $transaction->isPending()) {
            $result = $shwaryService->getTransaction($transaction->transaction_id);

            Log::info('Shwary status check result', [
                'transaction_id' => $transaction->transaction_id,
                'local_id' => $transaction->id,
                'result' => $result,
                'status_before' => $transaction->status,
            ]);

            // Gérer les erreurs API - vérifier si le webhook a mis à jour le statut
            if (!($result['success'] ?? false)) {
                $httpStatus = $result['http_status'] ?? null;
                
                Log::warning('Shwary API error during polling, checking local status', [
                    'http_status' => $httpStatus,
                    'transaction_id' => $transaction->transaction_id,
                ]);
                
                // Recharger la transaction - le webhook pourrait l'avoir mise à jour
                $transaction->refresh();
                
                // Si le webhook a mis à jour le statut, on traite
                if ($transaction->isCompleted()) {
                    $this->handlePaymentSuccess($transaction, ['verified_via_webhook' => true]);
                    return;
                }
                
                if ($transaction->isFailed()) {
                    $this->paymentStatus = 'failed';
                    $this->paymentMessage = $transaction->failure_reason ?: 'Le paiement a échoué.';
                    $this->pendingTransactionId = null;
                    $this->dispatch('show-toast', message: $this->paymentMessage, type: 'error');
                    $this->dispatch('payment-completed');
                    return;
                }
                
                // Toujours en attente, continuer le polling silencieusement
                return;
            }

            // Recharger la transaction après la mise à jour
            $transaction->refresh();

            Log::info('Shwary status after refresh', [
                'status_after' => $transaction->status,
                'is_completed' => $transaction->isCompleted(),
                'is_pending' => $transaction->isPending(),
            ]);
        }

        if ($transaction->isCompleted()) {
            $this->handlePaymentSuccess($transaction);
            return;
        }

        if ($transaction->isFailed()) {
            $this->paymentStatus = 'failed';
            $this->paymentMessage = $shwaryService->getStatusMessage($transaction);
            $this->pendingTransactionId = null;

            // Dispatch toast d'erreur
            $this->dispatch('show-toast', 
                message: $this->paymentMessage ?: 'Le paiement a échoué. Veuillez réessayer.', 
                type: 'error'
            );

            // Dispatch un événement pour arrêter la vérification
            $this->dispatch('payment-completed');
        }

        // Si toujours en attente, on continue de vérifier
        if ($transaction->isPending()) {
            $this->paymentStatus = 'pending';
            $this->paymentMessage = $shwaryService->getStatusMessage($transaction);
        }
    }

    /**
     * Confirmation manuelle du paiement (pour les cas où le webhook ne fonctionne pas)
     *
     * Cette méthode tente de vérifier le statut via l'API Shwary.
     * Si l'API est indisponible (401/404), elle vérifie si le webhook a mis à jour le statut.
     */
    public function confirmPaymentManually(): void
    {
        if (!$this->pendingTransactionId) {
            return;
        }

        $transaction = ShwaryTransaction::find($this->pendingTransactionId);

        if (!$transaction) {
            $this->paymentStatus = 'error';
            $this->paymentMessage = 'Transaction non trouvée';
            $this->dispatch('show-toast', message: 'Transaction non trouvée', type: 'error');
            return;
        }

        // Vérifier d'abord si la transaction est déjà complétée localement
        if ($transaction->isCompleted()) {
            Log::info('Transaction already completed locally, processing success', [
                'transaction_id' => $transaction->id,
            ]);
            $this->handlePaymentSuccess($transaction, ['verified_locally' => true]);
            return;
        }

        $shwaryService = app(ShwaryPaymentService::class);

        if (!$transaction->transaction_id) {
            $this->paymentStatus = 'error';
            $this->paymentMessage = 'Impossible de vérifier la transaction. Veuillez réessayer.';
            $this->dispatch('show-toast', message: 'Impossible de vérifier la transaction', type: 'error');
            return;
        }

        // Appeler l'API Shwary pour obtenir le statut réel
        $result = $shwaryService->getTransaction($transaction->transaction_id);

        // Recharger la transaction après la mise à jour par getTransaction()
        $transaction->refresh();

        // Si l'API a échoué, vérifier quand même si le webhook a mis à jour le statut
        if (!($result['success'] ?? false)) {
            $httpStatus = $result['http_status'] ?? null;
            $errorMessage = $result['message'] ?? 'Erreur de vérification';
            
            Log::warning('Shwary API check failed during manual confirmation', [
                'transaction_id' => $transaction->id,
                'http_status' => $httpStatus,
                'error' => $errorMessage,
                'sandbox' => $result['sandbox'] ?? false,
            ]);

            // Recharger la transaction - le webhook pourrait l'avoir mise à jour
            $transaction->refresh();
            
            // Si le webhook a mis à jour le statut, on peut continuer
            if ($transaction->isCompleted()) {
                Log::info('Transaction completed via webhook', ['transaction_id' => $transaction->id]);
                $this->handlePaymentSuccess($transaction, ['verified_via_webhook' => true]);
                return;
            }
            
            if ($transaction->isFailed()) {
                $this->paymentStatus = 'failed';
                $failureReason = $transaction->failure_reason ?: 'Le paiement a échoué (solde insuffisant, transaction annulée, etc.)';
                $this->paymentMessage = $failureReason;
                $this->pendingTransactionId = null;
                $this->dispatch('show-toast', message: $failureReason, type: 'error');
                $this->dispatch('payment-completed');
                return;
            }

            // L'API est indisponible et le webhook n'a pas encore mis à jour
            // Afficher un message informatif
            $this->paymentStatus = 'pending';
            $this->paymentMessage = 'La vérification automatique est temporairement indisponible. Le statut sera mis à jour automatiquement dès que l\'opérateur confirmera la transaction.';
            $this->dispatch('show-toast', message: 'Vérification en cours... Veuillez patienter ou vérifier sur votre téléphone.', type: 'info');
            return;
        }

        // Vérifier si le paiement a réellement réussi côté Shwary
        if (!$transaction->isCompleted()) {
            // Le paiement n'a PAS réussi côté Mobile Money
            if ($transaction->isFailed()) {
                $this->paymentStatus = 'failed';
                $this->paymentMessage = $shwaryService->getStatusMessage($transaction)
                    ?: 'Le paiement a échoué. Veuillez réessayer.';
                $this->pendingTransactionId = null;
                $this->dispatch('show-toast', message: $this->paymentMessage, type: 'error');
                // Arrêter le polling car le paiement a échoué
                $this->dispatch('payment-completed');
            } else {
                // Toujours en attente - ne pas arrêter le polling
                $this->paymentStatus = 'pending';
                $this->paymentMessage = 'Le paiement n\'a pas encore été confirmé par votre opérateur Mobile Money. Veuillez valider sur votre téléphone.';
                $this->dispatch('show-toast', message: 'Paiement en attente de validation sur votre téléphone', type: 'info');
            }
            return;
        }

        // Le paiement est confirmé par l'API Shwary
        $this->handlePaymentSuccess($transaction, ['verified_via_api' => true]);
    }

    /**
     * Annuler la transaction en attente et recommencer
     */
    public function cancelPendingPayment(): void
    {
        if ($this->pendingTransactionId) {
            $transaction = ShwaryTransaction::find($this->pendingTransactionId);

            if ($transaction && $transaction->isPending()) {
                $transaction->update([
                    'status' => ShwaryTransaction::STATUS_CANCELLED,
                    'failed_at' => now(),
                    'failure_reason' => 'Annulé par l\'utilisateur',
                ]);

                Log::info('Transaction cancelled by user', ['transaction_id' => $transaction->id]);
            }
        }

        // Réinitialiser l'état
        $this->pendingTransactionId = null;
        $this->paymentStatus = null;
        $this->paymentMessage = null;
        $this->phoneNumber = '';
    }

    /**
     * Forcer la confirmation du paiement (ADMIN UNIQUEMENT)
     *
     * ATTENTION: Cette méthode doit être utilisée uniquement par un administrateur
     * après vérification manuelle que l'argent est bien arrivé sur le compte Shwary.
     * Elle n'est PAS exposée dans l'UI utilisateur.
     */
    public function forceConfirmPayment(): void
    {
        // Seuls les admins peuvent forcer une confirmation
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            Log::warning('Unauthorized attempt to force confirm payment', [
                'user_id' => $user?->id,
            ]);
            $this->paymentStatus = 'error';
            $this->paymentMessage = 'Action non autorisée';
            $this->dispatch('show-toast', message: 'Action non autorisée', type: 'error');
            return;
        }

        if (!$this->pendingTransactionId) {
            return;
        }

        $transaction = ShwaryTransaction::find($this->pendingTransactionId);

        if (!$transaction) {
            $this->paymentStatus = 'error';
            $this->paymentMessage = 'Transaction non trouvée';
            return;
        }

        // Marquer la transaction comme complétée manuellement par admin
        $transaction->update([
            'status' => ShwaryTransaction::STATUS_COMPLETED,
            'completed_at' => now(),
            'response_data' => array_merge($transaction->response_data ?? [], [
                'manual_force_confirmation' => true,
                'confirmed_by_admin' => Auth::id(),
                'confirmed_at' => now()->toIso8601String(),
                'reason' => 'Admin manual confirmation - API verification bypassed',
            ]),
        ]);

        Log::warning('Payment force confirmed by ADMIN (API bypassed)', [
            'transaction_id' => $transaction->id,
            'shwary_transaction_id' => $transaction->transaction_id,
            'admin_id' => Auth::id(),
            'amount' => $transaction->amount,
        ]);

        // Le paiement est maintenant confirmé
        $this->handlePaymentSuccess($transaction, ['admin_force_confirmation' => true]);
    }

    /**
     * Gérer le timeout du paiement (appelé depuis JavaScript)
     */
    public function handlePaymentTimeout(): void
    {
        if ($this->pendingTransactionId) {
            $transaction = ShwaryTransaction::find($this->pendingTransactionId);

            if ($transaction && $transaction->isPending()) {
                $transaction->update([
                    'status' => ShwaryTransaction::STATUS_EXPIRED,
                    'failed_at' => now(),
                    'failure_reason' => 'Délai d\'attente dépassé (timeout client)',
                ]);

                Log::info('Transaction timed out (client-side)', ['transaction_id' => $transaction->id]);
            }
        }

        $this->paymentStatus = 'failed';
        $this->paymentMessage = 'Le délai d\'attente a été dépassé. Veuillez réessayer ou contacter le support si le montant a été débité.';
        $this->pendingTransactionId = null;
        $this->dispatch('show-toast', message: 'Délai d\'attente dépassé. Veuillez réessayer.', type: 'error');
        $this->dispatch('payment-completed');
    }

    /**
     * Traiter le succès du paiement (méthode centralisée pour éviter la duplication)
     *
     * @param ShwaryTransaction $transaction La transaction complétée
     * @param array $additionalMetadata Métadonnées supplémentaires à inclure
     */
    protected function handlePaymentSuccess(ShwaryTransaction $transaction, array $additionalMetadata = []): void
    {
        $this->paymentStatus = 'success';
        $this->paymentMessage = 'Paiement confirmé avec succès !';

        // Traiter selon le type (upgrade, renouvellement ou nouveau paiement)
        if ($this->organization) {
            if ($this->isUpgrade && $this->targetPlan) {
                $this->processUpgradeSuccess($transaction);
            } elseif ($this->isRenewal) {
                $this->processRenewalSuccess($transaction);
            } else {
                $this->organization->markPaymentCompleted(
                    paymentReference: $transaction->reference ?? $transaction->transaction_id,
                    paymentMethod: 'mobile_money',
                    amount: (float) $transaction->amount,
                    metadata: array_merge([
                        'shwary_transaction_id' => $transaction->transaction_id,
                        'shwary_local_id' => $transaction->id,
                        'phone_number' => $transaction->phone_number,
                        'country_code' => $transaction->country_code,
                    ], $additionalMetadata)
                );
            }
        }

        // Arrêter la vérification et rediriger
        $this->dispatch('payment-completed');

        $successMessage = $this->isUpgrade
            ? 'Upgrade effectué avec succès ! Vous êtes maintenant sur le plan ' . ucfirst($this->targetPlan) . ' 🎉'
            : ($this->isRenewal
                ? 'Renouvellement effectué avec succès ! Votre abonnement a été prolongé de 30 jours 🎉'
                : 'Paiement Mobile Money confirmé avec succès ! Bienvenue à bord 🎉');

        // Dispatch toast de succès
        $this->dispatch('show-toast', message: $successMessage, type: 'success');

        session()->flash('success', $successMessage);

        $this->showModal = false;
        $this->isRenewal = false;
        $this->isUpgrade = false;
        $this->targetPlan = null;

        $this->redirectRoute('dashboard');
    }

    /**
     * Ouvrir le modal pour un renouvellement d'abonnement
     */
    #[On('open-renewal-modal')]
    public function openForRenewal(?int $organizationId = null): void
    {
        $user = Auth::user();

        if (!$user) {
            return;
        }

        // Utiliser l'organisation passée ou l'organisation courante
        if ($organizationId) {
            $this->organization = Organization::find($organizationId);
        } else {
            $this->organization = app()->bound('current_organization')
                ? app('current_organization')
                : $user->defaultOrganization;
        }

        if (!$this->organization) {
            return;
        }

        $this->isRenewal = true;
        $this->showModal = true;

        // Charger les données du plan actuel
        $allPlans = SubscriptionService::getPlansFromCache();
        $planSlug = $this->organization->subscription_plan->value;
        $this->planData = $allPlans[$planSlug] ?? [];
        $this->currency = SubscriptionService::getCurrencyFromCache();

        // Réinitialiser les états
        $this->paymentStatus = null;
        $this->paymentMessage = null;
        $this->pendingTransactionId = null;
        $this->isProcessing = false;

        // Vérifier si une transaction est en attente
        $this->checkPendingTransaction();
    }

    /**
     * Ouvrir le modal pour un upgrade de plan
     */
    #[On('open-upgrade-modal')]
    public function openForUpgrade(int $organizationId, string $targetPlan): void
    {
        $user = Auth::user();

        if (!$user) {
            return;
        }

        $this->organization = Organization::find($organizationId);

        if (!$this->organization) {
            return;
        }

        // Vérifier que c'est bien un upgrade
        $subscriptionService = app(SubscriptionService::class);
        $currentPlanSlug = $this->organization->subscription_plan->value;

        if (!$subscriptionService->isUpgrade($currentPlanSlug, $targetPlan)) {
            $this->dispatch('show-toast', message: 'Ce n\'est pas un upgrade de plan valide.', type: 'error');
            return;
        }

        $this->isUpgrade = true;
        $this->isRenewal = false;
        $this->targetPlan = $targetPlan;
        $this->currentPlan = $currentPlanSlug;
        $this->showModal = true;

        // Charger les données du plan cible
        $allPlans = SubscriptionService::getPlansFromDatabase();
        $this->planData = $allPlans[$targetPlan] ?? [];
        $this->currency = SubscriptionService::getCurrencyFromCache();

        // Réinitialiser les états
        $this->paymentStatus = null;
        $this->paymentMessage = null;
        $this->pendingTransactionId = null;
        $this->isProcessing = false;

        // Vérifier si une transaction est en attente
        $this->checkPendingTransaction();
    }

    /**
     * Fermer le modal
     */
    public function closeModal(): void
    {
        // Ne pas permettre la fermeture si ce n'est pas un renouvellement ou upgrade
        // (cas du premier paiement obligatoire)
        if (!$this->isRenewal && !$this->isUpgrade && $this->organization && !$this->organization->isAccessible()) {
            return;
        }

        $this->showModal = false;
        $this->isRenewal = false;
        $this->isUpgrade = false;
        $this->targetPlan = null;
        $this->currentPlan = null;
        $this->paymentStatus = null;
        $this->paymentMessage = null;
    }

    /**
     * Process payment (pour carte bancaire - simulation)
     */
    public function processPayment()
    {
        if (!$this->organization) {
            return;
        }

        if ($this->paymentMethod === 'mobile_money') {
            $this->processShwaryPayment();
            return;
        }

        // Paiement par carte (simulation)
        $paymentReference = 'PAY_' . strtoupper(uniqid());
        $amount = $this->planData['price'] ?? 0;

        $this->organization->markPaymentCompleted(
            paymentReference: $paymentReference,
            paymentMethod: $this->paymentMethod,
            amount: (float) $amount,
            metadata: ['source' => 'payment_modal_card']
        );

        $this->showModal = false;

        session()->flash('success', 'Paiement effectué avec succès ! Bienvenue à bord 🎉');

        return redirect()->route('dashboard');
    }

    /**
     * Cancel and use free plan instead
     */
    public function useFreePlan()
    {
        if (!$this->organization) {
            return;
        }

        // Récupérer les limites du plan gratuit depuis le cache/DB
        $plans = SubscriptionService::getPlansFromCache();
        $freePlan = $plans['free'] ?? [];

        $this->organization->update([
            'subscription_plan' => SubscriptionPlan::FREE,
            'payment_status' => PaymentStatus::COMPLETED,
            'max_stores' => $freePlan['max_stores'] ?? 1,
            'max_users' => $freePlan['max_users'] ?? 3,
            'max_products' => $freePlan['max_products'] ?? 100,
        ]);

        // Assigner les rôles admin et manager au propriétaire
        $this->organization->assignOwnerRolesAndMenus();

        $this->showModal = false;

        session()->flash('success', 'Vous utilisez maintenant le plan gratuit. Vous pouvez passer à un plan supérieur à tout moment.');

        return redirect()->route('dashboard');
    }

    /**
     * Obtenir les pays supportés pour Mobile Money
     */
    public function getSupportedCountries(): array
    {
        return config('shwary.countries', []);
    }

    /**
     * Obtenir les opérateurs pour le pays sélectionné
     */
    public function getOperatorsProperty(): array
    {
        return config("shwary.countries.{$this->selectedCountry}.operators", []);
    }

    /**
     * Obtenir le préfixe téléphonique pour le pays sélectionné
     */
    public function getPhonePrefixProperty(): string
    {
        return config("shwary.countries.{$this->selectedCountry}.phone_prefix", '+243');
    }

    /**
     * Traiter le succès d'un renouvellement
     */
    protected function processRenewalSuccess($transaction): void
    {
        if (!$this->organization) {
            return;
        }

        $oldStartsAt = $this->organization->subscription_starts_at;
        $oldEndsAt = $this->organization->subscription_ends_at;

        // Calculer la nouvelle date de fin
        // Si l'abonnement est expiré, partir d'aujourd'hui
        // Sinon, ajouter 30 jours à la date de fin actuelle
        $baseDate = $this->organization->hasActiveSubscription() && $oldEndsAt
            ? $oldEndsAt
            : now();

        $newEndsAt = $baseDate->copy()->addDays(30)->endOfDay();
        $newStartsAt = $this->organization->hasActiveSubscription() && $oldStartsAt
            ? $oldStartsAt
            : now();

        // Mettre à jour l'organisation
        $this->organization->update([
            'subscription_starts_at' => $newStartsAt,
            'subscription_ends_at' => $newEndsAt,
            'payment_status' => PaymentStatus::COMPLETED,
            'payment_method' => 'mobile_money',
            'payment_reference' => $transaction->reference ?? $transaction->transaction_id,
            'payment_completed_at' => now(),
        ]);

        // Recharger l'organisation pour avoir les nouvelles valeurs
        $this->organization->refresh();

        // Enregistrer dans l'historique
        SubscriptionHistory::record(
            organization: $this->organization,
            action: SubscriptionHistory::ACTION_RENEWED,
            notes: sprintf(
                'Renouvellement via Mobile Money. Référence: %s. Période: %s → %s',
                $transaction->reference ?? $transaction->transaction_id,
                $newStartsAt->format('d/m/Y'),
                $newEndsAt->format('d/m/Y')
            )
        );
    }

    /**
     * Traiter le succès d'un upgrade de plan
     */
    protected function processUpgradeSuccess($transaction): void
    {
        if (!$this->organization || !$this->targetPlan) {
            return;
        }

        $subscriptionService = app(SubscriptionService::class);
        $oldPlan = $this->organization->subscription_plan->value;

        try {
            // Utiliser le service d'abonnement pour effectuer l'upgrade
            $subscriptionService->upgrade(
                organization: $this->organization,
                newPlan: $this->targetPlan,
                durationMonths: 1,
                paymentMethod: 'mobile_money',
                transactionId: $transaction->reference ?? $transaction->transaction_id
            );

            // Recharger l'organisation
            $this->organization->refresh();

            // Enregistrer dans l'historique
            SubscriptionHistory::record(
                organization: $this->organization,
                action: SubscriptionHistory::ACTION_UPGRADED,
                notes: sprintf(
                    'Upgrade %s → %s via Mobile Money. Référence: %s',
                    ucfirst($oldPlan),
                    ucfirst($this->targetPlan),
                    $transaction->reference ?? $transaction->transaction_id
                )
            );
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'upgrade: ' . $e->getMessage(), [
                'organization_id' => $this->organization->id,
                'target_plan' => $this->targetPlan,
                'transaction_id' => $transaction->id,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.organization.payment-modal', [
            'supportedCountries' => $this->getSupportedCountries(),
            'operators' => $this->operators,
            'phonePrefix' => $this->phonePrefix,
        ]);
    }
}
