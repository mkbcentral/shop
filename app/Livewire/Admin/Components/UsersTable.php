<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;
use Livewire\WithPagination;

class UsersTable extends Component
{
    use WithPagination;

    public string $searchUsers = '';
    public string $userStatusFilter = 'all';

    public function toggleUserStatus($userId)
    {
        $this->dispatch('toggleUserStatus', $userId);
    }

    public function render()
    {
        return view('livewire.admin.components.users-table');
    }
}
