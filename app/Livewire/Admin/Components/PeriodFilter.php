<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;

class PeriodFilter extends Component
{
    public int $periodFilter;

    public function mount(int $periodFilter = 30)
    {
        $this->periodFilter = $periodFilter;
    }

    public function updatedPeriodFilter()
    {
        $this->dispatch('periodChanged', $this->periodFilter)->to('admin.super-admin-dashboard');
    }

    public function render()
    {
        return view('livewire.admin.components.period-filter');
    }
}
