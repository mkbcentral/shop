<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;

class StatsCard extends Component
{
    public string $title;
    public string $value;
    public string $subtitle;
    public string $icon;
    public string $gradientFrom;
    public string $gradientTo;
    public ?string $footerLabel = null;
    public ?string $footerValue = null;
    public ?array $footerStats = null;

    public function render()
    {
        return view('livewire.admin.components.stats-card');
    }
}
