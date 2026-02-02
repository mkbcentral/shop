<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;

class QuickStatsGrid extends Component
{
    public array $stats;

    public function mount(array $stats = [])
    {
        $this->stats = $stats;
    }

    public function render()
    {
        return view('livewire.admin.components.quick-stats-grid');
    }
}
