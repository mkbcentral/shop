<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;

class TabNavigation extends Component
{
    public string $activeTab;
    public array $tabs;

    public function mount(string $activeTab = 'overview', array $tabs = [])
    {
        $this->activeTab = $activeTab;
        $this->tabs = $tabs;
    }

    public function setActiveTab(string $tab)
    {
        $this->activeTab = $tab;
        $this->dispatch('tabChanged', $tab)->to('admin.super-admin-dashboard');
    }

    public function render()
    {
        return view('livewire.admin.components.tab-navigation');
    }
}
