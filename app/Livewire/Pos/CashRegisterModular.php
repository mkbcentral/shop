<?php

declare(strict_types=1);

namespace App\Livewire\Pos;

use App\Services\Pos\StatsService;
use Livewire\Component;
use Livewire\Attributes\On;

/**
 * CashRegisterModular - Orchestrateur POS Modulaire
 *
 * Version simplifiée qui délègue aux sous-composants:
 * - PosProductGrid : Recherche et affichage des produits
 * - PosCart : Gestion du panier et sélection client
 * - PosPaymentPanel : Paiement et reçus
 * - PosTransactionHistory : Historique des factures du jour
 *
 * Ce composant ne gère que:
 * - Les statistiques du jour
 * - Les raccourcis clavier
 * - La coordination globale
 */
class CashRegisterModular extends Component
{
    // Statistiques
    public array $todayStats = [
        'sales_count' => 0,
        'revenue' => 0,
        'transactions' => 0
    ];
    public bool $showStats = false;

    // Services
    private StatsService $statsService;

    public function boot(StatsService $statsService): void
    {
        $this->statsService = $statsService;
    }

    public function mount(): void
    {
        $this->loadTodayStats();
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
     * Charge les statistiques du jour
     */
    public function loadTodayStats(): void
    {
        $this->todayStats = $this->statsService->loadTodayStats($this->getUserId());
    }

    /**
     * Bascule l'affichage des statistiques
     */
    public function toggleStats(): void
    {
        $this->showStats = !$this->showStats;
    }

    /**
     * Écoute le rafraîchissement des stats (émis par PosPaymentPanel après paiement)
     */
    #[On('stats-refresh')]
    public function refreshStats(): void
    {
        $this->statsService->invalidateStatsCache($this->getUserId());
        $this->loadTodayStats();
    }

    /**
     * Écoute la complétion d'une vente
     */
    #[On('sale-completed')]
    public function onSaleCompleted(): void
    {
        $this->refreshStats();
    }

    /**
     * Raccourci clavier : Valider la vente (F9)
     */
    #[On('keyboard-shortcut-f9')]
    public function keyboardValidateSale(): void
    {
        $this->dispatch('trigger-payment');
    }

    /**
     * Raccourci clavier : Vider le panier (F4)
     */
    #[On('keyboard-shortcut-f4')]
    public function keyboardClearCart(): void
    {
        $this->dispatch('trigger-clear-cart');
    }

    /**
     * Raccourci clavier : Focus sur la recherche (F2)
     */
    #[On('keyboard-shortcut-f2')]
    public function keyboardFocusSearch(): void
    {
        $this->dispatch('focus-search');
    }

    public function render()
    {
        return view('livewire.pos.cash-register-modular');
    }
}
