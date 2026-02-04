<?php

namespace App\Livewire\Organization;

use App\Models\Organization;
use App\Models\SubscriptionHistory;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class SubscriptionHistoryList extends Component
{
    use WithPagination;

    public ?int $organizationId = null;
    public ?string $organizationName = null;
    public string $actionFilter = '';
    public string $dateFrom = '';
    public string $dateTo = '';

    public function mount(?int $organizationId = null): void
    {
        $this->organizationId = $organizationId;
        
        if ($organizationId) {
            $organization = Organization::find($organizationId);
            $this->organizationName = $organization?->name;
        }
    }

    /**
     * Ouvrir le modal d'historique pour une organisation
     */
    #[On('open-subscription-history')]
    public function openHistory(int $organizationId): void
    {
        $organization = Organization::find($organizationId);
        
        if (!$organization) {
            return;
        }

        $this->organizationId = $organizationId;
        $this->organizationName = $organization->name;
        $this->resetFilters();
        $this->resetPage();
        
        $this->dispatch('show-subscription-history-modal');
    }

    /**
     * Réinitialiser les filtres
     */
    public function resetFilters(): void
    {
        $this->actionFilter = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->resetPage();
    }

    /**
     * Fermer et réinitialiser
     */
    public function closeHistory(): void
    {
        $this->organizationId = null;
        $this->organizationName = null;
        $this->resetFilters();
        $this->dispatch('hide-subscription-history-modal');
    }

    public function updatingActionFilter(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $history = collect();
        
        if ($this->organizationId) {
            $query = SubscriptionHistory::query()
                ->where('organization_id', $this->organizationId)
                ->with(['user', 'payment'])
                ->when($this->actionFilter, fn($q) => $q->where('action', $this->actionFilter))
                ->when($this->dateFrom, fn($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
                ->when($this->dateTo, fn($q) => $q->whereDate('created_at', '<=', $this->dateTo))
                ->orderBy('created_at', 'desc');

            $history = $query->paginate(10);
        }

        return view('livewire.organization.subscription-history-list', [
            'history' => $history,
            'actions' => SubscriptionHistory::ACTION_LABELS,
        ]);
    }
}
