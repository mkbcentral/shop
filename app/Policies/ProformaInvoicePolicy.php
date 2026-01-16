<?php

namespace App\Policies;

use App\Models\ProformaInvoice;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProformaInvoicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any proforma invoices.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the proforma invoice.
     */
    public function view(User $user, ProformaInvoice $proforma): bool
    {
        // User must be in the same organization
        return $user->belongsToOrganization($proforma->organization_id);
    }

    /**
     * Determine whether the user can create proforma invoices.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the proforma invoice.
     */
    public function update(User $user, ProformaInvoice $proforma): bool
    {
        // Must be in same organization and proforma must be editable
        return $user->belongsToOrganization($proforma->organization_id)
            && $proforma->canBeEdited();
    }

    /**
     * Determine whether the user can delete the proforma invoice.
     */
    public function delete(User $user, ProformaInvoice $proforma): bool
    {
        // Only draft proformas can be deleted
        return $user->belongsToOrganization($proforma->organization_id)
            && $proforma->status === ProformaInvoice::STATUS_DRAFT;
    }

    /**
     * Determine whether the user can convert the proforma to invoice.
     */
    public function convert(User $user, ProformaInvoice $proforma): bool
    {
        return $user->belongsToOrganization($proforma->organization_id)
            && $proforma->canBeConverted();
    }

    /**
     * Determine whether the user can change the proforma status.
     */
    public function changeStatus(User $user, ProformaInvoice $proforma): bool
    {
        return $user->belongsToOrganization($proforma->organization_id);
    }

    /**
     * Determine whether the user can duplicate the proforma.
     */
    public function duplicate(User $user, ProformaInvoice $proforma): bool
    {
        return $user->belongsToOrganization($proforma->organization_id);
    }
}
