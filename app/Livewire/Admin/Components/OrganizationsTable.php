<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;
use Livewire\WithPagination;

class OrganizationsTable extends Component
{
    use WithPagination;

    public string $searchOrganizations = '';
    public string $orgStatusFilter = 'all';

    public function toggleOrganizationStatus($orgId)
    {
        $this->dispatch('toggleOrganizationStatus', $orgId);
    }

    public function render()
    {
        return view('livewire.admin.components.organizations-table');
    }
}
