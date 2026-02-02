<?php

namespace App\Actions\Admin;

use App\Models\Organization;
use Illuminate\Support\Facades\Log;

class ToggleOrganizationStatusAction
{
    public function execute(int $organizationId): bool
    {
        $organization = Organization::find($organizationId);

        if (!$organization) {
            Log::warning("Tentative de toggle statut sur organisation inexistante", [
                'organization_id' => $organizationId
            ]);
            return false;
        }

        $newStatus = !$organization->is_active;
        $organization->update(['is_active' => $newStatus]);

        Log::info("Statut organisation modifié", [
            'organization_id' => $organizationId,
            'organization_name' => $organization->name,
            'new_status' => $newStatus ? 'active' : 'inactive'
        ]);

        return true;
    }
}
