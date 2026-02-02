<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;

class SubscriptionsOverview extends Component
{
    public array $subscriptionStats;

    public function mount(array $subscriptionStats = [])
    {
        $this->subscriptionStats = $subscriptionStats;
    }

    public function render()
    {
        return view('livewire.admin.components.subscriptions-overview');
    }
}
