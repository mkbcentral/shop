<?php

declare(strict_types=1);

namespace App\Livewire\Pos\Components;

use App\Services\Pos\StatsService;
use Livewire\Component;
use Livewire\Attributes\On;

/**
 * Composant Historique des Transactions POS
 * Affiche un modal avec les factures du jour
 */
class PosTransactionHistory extends Component
{
    public array $transactions = [];
    public bool $showModal = false;

    private StatsService $statsService;

    public function boot(StatsService $statsService): void
    {
        $this->statsService = $statsService;
    }

    public function mount(): void
    {
        $this->loadTransactions();
    }

    /**
     * Obtient l'ID de l'utilisateur authentifié
     */
    private function getUserId(): int
    {
        $userId = auth()->id();
        if (!$userId) {
            throw new \RuntimeException('Utilisateur non authentifié');
        }
        return (int) $userId;
    }

    /**
     * Charge l'historique des transactions
     */
    public function loadTransactions(): void
    {
        $this->transactions = $this->statsService->loadTransactionHistory($this->getUserId());
    }

    /**
     * Ouvre le modal
     */
    public function openModal(): void
    {
        $this->loadTransactions();
        $this->showModal = true;
    }

    /**
     * Ferme le modal
     */
    public function closeModal(): void
    {
        $this->showModal = false;
    }

    /**
     * Réimprime une transaction
     */
    public function reprintTransaction(int $saleId): void
    {
        $this->dispatch('reprint-receipt', saleId: $saleId);
        $this->showModal = false;
    }

    /**
     * Voir les détails d'une transaction (sans impression)
     */
    public function viewTransaction(int $saleId): void
    {
        $this->dispatch('view-transaction-details', saleId: $saleId);
        $this->showModal = false;
    }

    /**
     * Écoute le rafraîchissement après paiement
     */
    #[On('stats-refresh')]
    #[On('sale-completed')]
    public function refreshTransactions(): void
    {
        $this->loadTransactions();
    }

    /**
     * Écoute l'ouverture du modal depuis l'extérieur
     */
    #[On('open-transaction-history')]
    public function onOpenModal(): void
    {
        $this->openModal();
    }

    public function render()
    {
        return view('livewire.pos.components.pos-transaction-history');
    }
}
