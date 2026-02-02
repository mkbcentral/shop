<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;

class UserGrowthChart extends Component
{
    public array $labels;
    public array $values;
    public string $title;

    public function mount(array $labels = [], array $values = [], string $title = 'Croissance des utilisateurs')
    {
        $this->labels = $labels;
        $this->values = $values;
        $this->title = $title;
    }

    public function render()
    {
        return view('livewire.admin.components.user-growth-chart');
    }
}
