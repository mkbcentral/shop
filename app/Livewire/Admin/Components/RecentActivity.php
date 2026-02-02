<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;

class RecentActivity extends Component
{
    public array $activities;
    public string $title;

    public function mount(array $activities = [], string $title = 'Activité récente')
    {
        $this->activities = $activities;
        $this->title = $title;
    }

    public function render()
    {
        return view('livewire.admin.components.recent-activity');
    }
}
