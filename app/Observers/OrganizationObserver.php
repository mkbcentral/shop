<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use App\Notifications\NewOrganizationNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

/**
 * Observateur pour le modèle Organization
 * Notifie les super-admins lors de la création d'une nouvelle organisation
 */
class OrganizationObserver
{
    /**
     * Appelé après la création d'une organisation
     */
    public function created(Organization $organization): void
    {
        // Notifier tous les super-admins
        $this->notifySuperAdmins($organization);

        Log::info('Organisation créée', [
            'organization_id' => $organization->id,
            'name' => $organization->name,
            'owner_id' => $organization->owner_id,
            'plan' => $organization->subscription_plan->value,
        ]);
    }

    /**
     * Notifie tous les super-admins de la création d'une nouvelle organisation
     */
    protected function notifySuperAdmins(Organization $organization): void
    {
        try {
            // Récupérer le propriétaire de l'organisation
            $owner = $organization->owner;

            if (!$owner) {
                Log::warning('Impossible de notifier: propriétaire non trouvé', [
                    'organization_id' => $organization->id,
                ]);
                return;
            }

            // Récupérer tous les super-admins
            $superAdminRole = Role::where('slug', 'super-admin')
                ->orWhere('name', 'super-admin')
                ->first();

            if (!$superAdminRole) {
                Log::warning('Rôle super-admin non trouvé');
                return;
            }

            $superAdmins = User::whereHas('roles', function ($query) use ($superAdminRole) {
                $query->where('roles.id', $superAdminRole->id);
            })->get();

            if ($superAdmins->isEmpty()) {
                Log::info('Aucun super-admin à notifier');
                return;
            }

            // Envoyer la notification à tous les super-admins
            Notification::send($superAdmins, new NewOrganizationNotification($organization, $owner));

            Log::info('Super-admins notifiés de la nouvelle organisation', [
                'organization_id' => $organization->id,
                'super_admins_count' => $superAdmins->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la notification des super-admins', [
                'organization_id' => $organization->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Appelé après la mise à jour d'une organisation
     */
    public function updated(Organization $organization): void
    {
        // Notifier si le statut de paiement passe à completed
        if ($organization->isDirty('payment_status') && $organization->payment_status->value === 'completed') {
            Log::info('Paiement d\'organisation complété', [
                'organization_id' => $organization->id,
                'name' => $organization->name,
            ]);
        }
    }

    /**
     * Appelé avant la suppression d'une organisation
     */
    public function deleting(Organization $organization): void
    {
        Log::warning('Organisation en cours de suppression', [
            'organization_id' => $organization->id,
            'name' => $organization->name,
        ]);
    }
}
