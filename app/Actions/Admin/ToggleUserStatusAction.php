<?php

namespace App\Actions\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class ToggleUserStatusAction
{
    public function execute(int $userId): bool
    {
        $user = User::find($userId);

        if (!$user) {
            Log::warning("Tentative de toggle statut sur utilisateur inexistant", ['user_id' => $userId]);
            return false;
        }

        // Prevent toggling super-admin status
        if ($user->hasRole('super-admin')) {
            Log::warning("Tentative de toggle statut super-admin", [
                'user_id' => $userId,
                'user_email' => $user->email
            ]);
            return false;
        }

        $newStatus = !$user->is_active;
        $user->update(['is_active' => $newStatus]);

        Log::info("Statut utilisateur modifié", [
            'user_id' => $userId,
            'user_email' => $user->email,
            'new_status' => $newStatus ? 'actif' : 'inactif'
        ]);

        return true;
    }
}
