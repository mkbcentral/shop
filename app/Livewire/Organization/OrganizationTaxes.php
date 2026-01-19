<?php

namespace App\Livewire\Organization;

use App\Models\Organization;
use App\Models\OrganizationTax;
use App\Services\TaxService;
use Livewire\Component;
use Livewire\WithPagination;

class OrganizationTaxes extends Component
{
    use WithPagination;

    public Organization $organization;

    // Formulaire de taxe
    public ?int $editingTaxId = null;
    public string $name = '';
    public string $code = '';
    public string $description = '';
    public float $rate = 0;
    public string $type = 'percentage';
    public ?float $fixedAmount = null;
    public bool $isCompound = false;
    public bool $isIncludedInPrice = false;
    public int $priority = 0;
    public bool $applyToAllProducts = true;
    public bool $isDefault = false;
    public bool $isActive = true;
    public ?string $validFrom = null;
    public ?string $validUntil = null;
    public ?string $taxNumber = null;
    public ?string $authority = null;

    // Recherche et filtres
    public string $search = '';

    protected $listeners = ['tax-saved' => '$refresh'];

    protected function rules(): array
    {
        $uniqueRule = $this->editingTaxId
            ? 'unique:organization_taxes,code,' . $this->editingTaxId . ',id,organization_id,' . $this->organization->id
            : 'unique:organization_taxes,code,NULL,id,organization_id,' . $this->organization->id;

        return [
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', $uniqueRule],
            'description' => 'nullable|string',
            'rate' => 'required|numeric|min:0|max:100',
            'type' => 'required|in:percentage,fixed',
            'fixedAmount' => 'nullable|numeric|min:0',
            'isCompound' => 'boolean',
            'isIncludedInPrice' => 'boolean',
            'priority' => 'integer|min:0',
            'applyToAllProducts' => 'boolean',
            'isDefault' => 'boolean',
            'isActive' => 'boolean',
            'validFrom' => 'nullable|date',
            'validUntil' => 'nullable|date|after_or_equal:validFrom',
            'taxNumber' => 'nullable|string|max:100',
            'authority' => 'nullable|string|max:255',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Le nom de la taxe est obligatoire',
            'code.required' => 'Le code de la taxe est obligatoire',
            'code.unique' => 'Ce code est déjà utilisé pour une autre taxe',
            'rate.required' => 'Le taux est obligatoire',
            'rate.max' => 'Le taux ne peut pas dépasser 100%',
            'type.required' => 'Le type de calcul est obligatoire',
        ];
    }

    public function mount(Organization $organization): void
    {
        $this->authorize('view', $organization);
        $this->organization = $organization;
    }

    public function openModal(): void
    {
        $this->resetForm();
        $this->dispatch('open-tax-modal');
    }

    public function closeModal(): void
    {
        $this->resetForm();
        $this->dispatch('close-tax-modal');
    }

    public function resetForm(): void
    {
        $this->editingTaxId = null;
        $this->name = '';
        $this->code = '';
        $this->description = '';
        $this->rate = 0;
        $this->type = 'percentage';
        $this->fixedAmount = null;
        $this->isCompound = false;
        $this->isIncludedInPrice = false;
        $this->priority = 0;
        $this->applyToAllProducts = true;
        $this->isDefault = false;
        $this->isActive = true;
        $this->validFrom = null;
        $this->validUntil = null;
        $this->taxNumber = null;
        $this->authority = null;
        $this->resetValidation();
    }

    public function editTax(int $taxId): void
    {
        $tax = $this->organization->taxes()->find($taxId);

        if (!$tax) {
            $this->dispatch('show-toast', message: 'Taxe non trouvée', type: 'error');
            return;
        }

        $this->editingTaxId = $tax->id;
        $this->name = $tax->name;
        $this->code = $tax->code;
        $this->description = $tax->description ?? '';
        $this->rate = (float) $tax->rate;
        $this->type = $tax->type;
        $this->fixedAmount = $tax->fixed_amount;
        $this->isCompound = $tax->is_compound;
        $this->isIncludedInPrice = $tax->is_included_in_price;
        $this->priority = $tax->priority;
        $this->applyToAllProducts = $tax->apply_to_all_products;
        $this->isDefault = $tax->is_default;
        $this->isActive = $tax->is_active;
        $this->validFrom = $tax->valid_from?->format('Y-m-d');
        $this->validUntil = $tax->valid_until?->format('Y-m-d');
        $this->taxNumber = $tax->tax_number;
        $this->authority = $tax->authority;

        $this->dispatch('open-tax-modal');
    }

    public function saveTax(TaxService $taxService): void
    {
        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'code' => $this->code,
                'description' => $this->description ?: null,
                'rate' => $this->rate,
                'type' => $this->type,
                'fixed_amount' => $this->type === 'fixed' ? $this->fixedAmount : null,
                'is_compound' => $this->isCompound,
                'is_included_in_price' => $this->isIncludedInPrice,
                'priority' => $this->priority,
                'apply_to_all_products' => $this->applyToAllProducts,
                'is_default' => $this->isDefault,
                'is_active' => $this->isActive,
                'valid_from' => $this->validFrom ?: null,
                'valid_until' => $this->validUntil ?: null,
                'tax_number' => $this->taxNumber ?: null,
                'authority' => $this->authority ?: null,
            ];

            if ($this->editingTaxId) {
                $tax = $this->organization->taxes()->find($this->editingTaxId);
                $taxService->updateTax($tax, $data);
                $message = 'Taxe modifiée avec succès';
            } else {
                $taxService->createTax($this->organization, $data);
                $message = 'Taxe créée avec succès';
            }

            $this->closeModal();
            $this->dispatch('show-toast', message: $message, type: 'success');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur : ' . $e->getMessage(), type: 'error');
        }
    }

    public function deleteTax(int $taxId, TaxService $taxService): void
    {
        try {
            $tax = $this->organization->taxes()->find($taxId);

            if (!$tax) {
                $this->dispatch('show-toast', message: 'Taxe non trouvée', type: 'error');
                return;
            }

            $taxService->deleteTax($tax);
            $this->dispatch('show-toast', message: 'Taxe supprimée avec succès', type: 'success');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur : ' . $e->getMessage(), type: 'error');
        }
    }

    public function setAsDefault(int $taxId): void
    {
        try {
            $tax = $this->organization->taxes()->find($taxId);

            if (!$tax) {
                $this->dispatch('show-toast', message: 'Taxe non trouvée', type: 'error');
                return;
            }

            $tax->setAsDefault();
            $this->dispatch('show-toast', message: 'Taxe définie par défaut', type: 'success');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur : ' . $e->getMessage(), type: 'error');
        }
    }

    public function toggleActive(int $taxId): void
    {
        try {
            $tax = $this->organization->taxes()->find($taxId);

            if (!$tax) {
                $this->dispatch('show-toast', message: 'Taxe non trouvée', type: 'error');
                return;
            }

            $tax->update(['is_active' => !$tax->is_active]);
            $status = $tax->is_active ? 'activée' : 'désactivée';
            $this->dispatch('show-toast', message: "Taxe {$status}", type: 'success');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Erreur : ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        $taxes = $this->organization->taxes()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('priority')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.organization.organization-taxes', [
            'taxes' => $taxes,
        ]);
    }
}
