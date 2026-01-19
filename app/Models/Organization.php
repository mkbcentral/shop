<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\SubscriptionPlan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Organization extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'legal_name',
        'type',
        'tax_id',
        'registration_number',
        'legal_form',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'logo',
        'website',
        'owner_id',
        'subscription_plan',
        'subscription_starts_at',
        'subscription_ends_at',
        'payment_status',
        'payment_method',
        'payment_reference',
        'payment_completed_at',
        'is_trial',
        'trial_days',
        'max_stores',
        'max_users',
        'max_products',
        'settings',
        'currency',
        'timezone',
        'is_active',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'is_trial' => 'boolean',
        'subscription_plan' => SubscriptionPlan::class,
        'payment_status' => PaymentStatus::class,
        'subscription_starts_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'payment_completed_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Get the owner of the organization
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get all members of the organization
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_user')
            ->withPivot('role', 'invited_at', 'accepted_at', 'invited_by', 'is_active')
            ->withTimestamps();
    }

    /**
     * Get active members only
     */
    public function activeMembers(): BelongsToMany
    {
        return $this->members()->wherePivot('is_active', true);
    }

    /**
     * Get members by role
     */
    public function membersByRole(string $role): BelongsToMany
    {
        return $this->members()->wherePivot('role', $role);
    }

    /**
     * Get admins (owner + admin role)
     */
    public function admins(): BelongsToMany
    {
        return $this->members()->wherePivotIn('role', ['owner', 'admin']);
    }

    /**
     * Get all stores in this organization
     */
    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    /**
     * Get active stores only
     */
    public function activeStores(): HasMany
    {
        return $this->stores()->where('is_active', true);
    }

    /**
     * Get pending invitations
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(OrganizationInvitation::class);
    }

    /**
     * Get subscription history
     */
    public function subscriptionHistories(): HasMany
    {
        return $this->hasMany(SubscriptionHistory::class);
    }

    /**
     * Get subscription payments
     */
    public function subscriptionPayments(): HasMany
    {
        return $this->hasMany(SubscriptionPayment::class);
    }

    /**
     * Get pending (not accepted, not expired) invitations
     */
    public function pendingInvitations(): HasMany
    {
        return $this->invitations()
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now());
    }

    /**
     * Get all taxes for this organization
     */
    public function taxes(): HasMany
    {
        return $this->hasMany(OrganizationTax::class);
    }

    /**
     * Get active taxes only
     */
    public function activeTaxes(): HasMany
    {
        return $this->taxes()->where('is_active', true)->ordered();
    }

    /**
     * Get the default tax
     */
    public function defaultTax(): HasMany
    {
        return $this->taxes()->where('is_default', true);
    }

    /**
     * Get valid taxes at current date
     */
    public function validTaxes(): HasMany
    {
        return $this->taxes()->active()->validAt()->ordered();
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Check if organization is on a paid plan
     */
    public function isPaid(): bool
    {
        return $this->subscription_plan !== 'free';
    }

    /**
     * Check if subscription is active
     */
    public function hasActiveSubscription(): bool
    {
        if ($this->subscription_plan === 'free') {
            return true;
        }

        return $this->subscription_ends_at && $this->subscription_ends_at->isFuture();
    }

    /**
     * Check if subscription is expiring soon (within 7 days)
     */
    public function isSubscriptionExpiringSoon(): bool
    {
        if (!$this->subscription_ends_at) {
            return false;
        }

        return $this->subscription_ends_at->diffInDays(now()) <= 7
            && $this->subscription_ends_at->isFuture();
    }

    /**
     * Check if organization can add more stores
     */
    public function canAddStore(): bool
    {
        return $this->stores()->count() < $this->max_stores;
    }

    /**
     * Check if organization can add more users
     */
    public function canAddUser(): bool
    {
        return $this->members()->count() < $this->max_users;
    }

    /**
     * Check if organization can add more products
     */
    public function canAddProduct(): bool
    {
        $currentProductCount = Product::whereIn('store_id', $this->stores()->pluck('id'))->count();
        return $currentProductCount < $this->max_products;
    }

    /**
     * Get products count across all stores
     */
    public function getProductsCount(): int
    {
        return Product::whereIn('store_id', $this->stores()->pluck('id'))->count();
    }

    /**
     * Get products usage info
     */
    public function getProductsUsage(): array
    {
        $current = $this->getProductsCount();
        return [
            'current' => $current,
            'max' => $this->max_products,
            'remaining' => max(0, $this->max_products - $current),
            'percentage' => $this->max_products > 0 ? round(($current / $this->max_products) * 100) : 0,
        ];
    }

    /**
     * Get stores usage info
     */
    public function getStoresUsage(): array
    {
        $current = $this->stores()->count();
        return [
            'current' => $current,
            'max' => $this->max_stores,
            'remaining' => max(0, $this->max_stores - $current),
            'percentage' => $this->max_stores > 0 ? round(($current / $this->max_stores) * 100) : 0,
        ];
    }

    /**
     * Get users usage info
     */
    public function getUsersUsage(): array
    {
        $current = $this->members()->count();
        return [
            'current' => $current,
            'max' => $this->max_users,
            'remaining' => max(0, $this->max_users - $current),
            'percentage' => $this->max_users > 0 ? round(($current / $this->max_users) * 100) : 0,
        ];
    }

    /**
     * Get remaining days of subscription
     */
    public function getRemainingDaysAttribute(): ?int
    {
        if (!$this->subscription_ends_at) {
            return null;
        }

        return max(0, (int) now()->diffInDays($this->subscription_ends_at, false));
    }

    /**
     * Check if user is the owner
     */
    public function isOwner(User $user): bool
    {
        return $this->owner_id === $user->id;
    }

    /**
     * Check if user is admin (owner or admin role)
     */
    public function isAdmin(User $user): bool
    {
        if ($this->isOwner($user)) {
            return true;
        }

        $member = $this->members()->where('user_id', $user->id)->first();
        return $member && in_array($member->pivot->role, ['owner', 'admin']);
    }

    /**
     * Check if user is a manager or higher
     */
    public function isManagerOrHigher(User $user): bool
    {
        if ($this->isOwner($user)) {
            return true;
        }

        $member = $this->members()->where('user_id', $user->id)->first();
        return $member && in_array($member->pivot->role, ['owner', 'admin', 'manager']);
    }

    /**
     * Get user's role in organization
     */
    public function getUserRole(User $user): ?string
    {
        if ($this->isOwner($user)) {
            return 'owner';
        }

        $member = $this->members()->where('user_id', $user->id)->first();
        return $member?->pivot->role;
    }

    /**
     * Check if user is a member
     */
    public function hasMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Get organization type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'individual' => 'Entrepreneur individuel',
            'company' => 'Entreprise',
            'franchise' => 'Franchise',
            'cooperative' => 'Coopérative',
            'group' => 'Groupe commercial',
            default => $this->type,
        };
    }

    /**
     * Get subscription plan label
     */
    public function getPlanLabelAttribute(): string
    {
        // Si subscription_plan est un enum, utiliser sa méthode label()
        if ($this->subscription_plan instanceof SubscriptionPlan) {
            return $this->subscription_plan->label();
        }

        // Fallback si c'est une string (ne devrait pas arriver)
        return match($this->subscription_plan) {
            'free' => 'Gratuit',
            'starter' => 'Starter',
            'professional' => 'Professionnel',
            'enterprise' => 'Entreprise',
            default => (string) $this->subscription_plan,
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByPlan($query, string $plan)
    {
        return $query->where('subscription_plan', $plan);
    }

    public function scopeWithActiveSubscription($query)
    {
        return $query->where(function ($q) {
            $q->where('subscription_plan', 'free')
              ->orWhere('subscription_ends_at', '>', now());
        });
    }

    /**
     * Check if organization is accessible (paid or free plan)
     */
    public function isAccessible(): bool
    {
        // Free plan is always accessible
        if ($this->subscription_plan === SubscriptionPlan::FREE) {
            return true;
        }

        // Paid plans require payment completion
        return $this->payment_status === PaymentStatus::COMPLETED;
    }

    /**
     * Check if payment is pending
     */
    public function isPaymentPending(): bool
    {
        return $this->payment_status === PaymentStatus::PENDING;
    }

    /**
     * Mark payment as completed
     */
    public function markPaymentCompleted(string $paymentReference, string $paymentMethod): void
    {
        $this->update([
            'payment_status' => PaymentStatus::COMPLETED,
            'payment_reference' => $paymentReference,
            'payment_method' => $paymentMethod,
            'payment_completed_at' => now(),
            'is_active' => true,
        ]);

        // Assigner les rôles admin et manager au propriétaire
        $this->assignOwnerRolesAndMenus();
    }

    /**
     * Assign admin and manager roles to the owner, and sync menu permissions
     */
    public function assignOwnerRolesAndMenus(): void
    {
        $owner = $this->owner;

        if (!$owner) {
            return;
        }

        // Assigner les rôles admin et manager
        $owner->syncRoles(['admin', 'manager']);

        // Menus réservés EXCLUSIVEMENT au super-admin (plateforme)
        // Ces menus sont pour la gestion globale de la plateforme, pas pour les organisations
        $superAdminOnlyMenuCodes = [
            'admin-dashboard',        // Tableau de bord Super Admin (gestion plateforme)
            'menu-permissions',       // Gestion des menus (configuration plateforme)
            'subscriptions',          // Paramètres d'abonnement (configuration plateforme)
            'subscription-settings',  // Paramètres d'abonnement (autre code)
            'roles',                  // Rôles (gestion plateforme)
            'roles.index',            // Liste des rôles
            'roles.create',           // Création rôle
            'roles.edit',             // Édition rôle
        ];

        // Récupérer les IDs des rôles admin et manager
        $adminRole = \App\Models\Role::where('slug', 'admin')->first();
        $managerRole = \App\Models\Role::where('slug', 'manager')->first();

        // Récupérer les IDs des menus réservés au super-admin (pour les détacher)
        $superAdminMenuIds = \App\Models\MenuItem::whereIn('code', $superAdminOnlyMenuCodes)
            ->pluck('id')
            ->toArray();

        if ($adminRole) {
            // D'abord, détacher explicitement les menus super-admin du rôle admin
            $adminRole->menus()->detach($superAdminMenuIds);

            // Récupérer tous les menus actifs SAUF ceux réservés au super-admin
            $accessibleMenus = \App\Models\MenuItem::active()
                ->whereNotIn('code', $superAdminOnlyMenuCodes)
                ->get();

            $menuIds = $accessibleMenus->pluck('id')->toArray();

            // Synchroniser les menus pour le rôle admin
            $adminRole->menus()->syncWithoutDetaching($menuIds);
        }

        if ($managerRole) {
            // Détacher les menus super-admin du rôle manager également
            $managerRole->menus()->detach($superAdminMenuIds);

            // Récupérer les menus accessibles au manager (même liste)
            $accessibleMenus = \App\Models\MenuItem::active()
                ->whereNotIn('code', $superAdminOnlyMenuCodes)
                ->get();

            $menuIds = $accessibleMenus->pluck('id')->toArray();

            // Synchroniser les menus pour le rôle manager
            $managerRole->menus()->syncWithoutDetaching($menuIds);
        }

        // Vider le cache des menus pour cet utilisateur
        \Illuminate\Support\Facades\Cache::forget("user_menus_{$owner->id}");
    }

    /**
     * Get trial end date
     */
    public function getTrialEndsAt(): ?\Carbon\Carbon
    {
        if (!$this->is_trial || !$this->subscription_starts_at) {
            return null;
        }

        return $this->subscription_starts_at->addDays($this->trial_days);
    }

    /**
     * Check if trial is active
     */
    public function isTrialActive(): bool
    {
        if (!$this->is_trial) {
            return false;
        }

        $trialEnd = $this->getTrialEndsAt();
        return $trialEnd && $trialEnd->isFuture();
    }


    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('legal_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }
}
