<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Client;
use App\Models\Supplier;
use App\Models\Sale;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use Livewire\Component;

class GlobalSearch extends Component
{
    public string $query = '';
    public string $selectedCategory = 'all';
    public array $recentSearches = [];

    protected $listeners = ['openSearch', 'closeSearch'];

    public function mount()
    {
        // Charger les recherches récentes depuis la session
        $this->recentSearches = session()->get('recent_searches', []);
    }

    public function updatedQuery()
    {
        if (strlen($this->query) >= 2) {
            $this->saveToRecent();
        }
    }

    public function saveToRecent()
    {
        if (strlen($this->query) < 2) {
            return;
        }

        $recent = session()->get('recent_searches', []);

        // Retirer si déjà présent
        $recent = array_filter($recent, fn($item) => $item !== $this->query);

        // Ajouter au début
        array_unshift($recent, $this->query);

        // Garder seulement les 5 dernières
        $recent = array_slice($recent, 0, 5);

        session()->put('recent_searches', $recent);
        $this->recentSearches = $recent;
    }

    public function setCategory(string $category)
    {
        $this->selectedCategory = $category;
    }

    public function clearSearch()
    {
        $this->query = '';
        $this->selectedCategory = 'all';
    }

    public function clearRecent()
    {
        session()->forget('recent_searches');
        $this->recentSearches = [];
    }

    /**
     * Get search results
     */
    public function getResultsProperty(): array
    {
        if (strlen($this->query) < 2) {
            return [];
        }

        $results = [];
        $searchTerm = '%' . $this->query . '%';

        // Recherche de produits
        if ($this->selectedCategory === 'all' || $this->selectedCategory === 'products') {
            $results['products'] = Product::with(['variants'])
                ->where('status', 'active')
                ->where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', $searchTerm)
                      ->orWhere('reference', 'like', $searchTerm)
                      ->orWhere('barcode', 'like', $searchTerm);
                })
                ->limit(5)
                ->get();
        }

        // Recherche de clients
        if ($this->selectedCategory === 'all' || $this->selectedCategory === 'clients') {
            $results['clients'] = Client::where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', $searchTerm)
                      ->orWhere('email', 'like', $searchTerm)
                      ->orWhere('phone', 'like', $searchTerm);
                })
                ->limit(5)
                ->get();
        }

        // Recherche de fournisseurs
        if ($this->selectedCategory === 'all' || $this->selectedCategory === 'suppliers') {
            $results['suppliers'] = Supplier::where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', $searchTerm)
                      ->orWhere('email', 'like', $searchTerm)
                      ->orWhere('phone', 'like', $searchTerm);
                })
                ->limit(5)
                ->get();
        }

        // Recherche de ventes
        if ($this->selectedCategory === 'all' || $this->selectedCategory === 'sales') {
            $results['sales'] = Sale::with(['client'])
                ->where(function($q) use ($searchTerm) {
                    $q->where('sale_number', 'like', $searchTerm)
                      ->orWhereHas('client', function($q) use ($searchTerm) {
                          $q->where('name', 'like', $searchTerm);
                      });
                })
                ->limit(5)
                ->get();
        }

        return $results;
    }

    /**
     * Get total results count
     */
    public function getTotalResultsProperty(): int
    {
        $total = 0;
        foreach ($this->results as $categoryResults) {
            $total += $categoryResults->count();
        }
        return $total;
    }

    /**
     * Check if has results
     */
    public function getHasResultsProperty(): bool
    {
        return $this->totalResults > 0;
    }

    public function render()
    {
        return view('livewire.global-search');
    }
}
