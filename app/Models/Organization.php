<?php

namespace App\Models;

use App\Enums\BusinessActivityType;
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
        'business_activity',
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
        'business_activity' => BusinessActivityType::class,
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

        // L'abonnement est actif si la date de fin est aujourd'hui ou dans le futur
        return $this->subscription_ends_at && $this->subscription_ends_at->endOfDay()->isFuture();
    }

    /**
     * Check if subscription is expiring soon (within 7 days)
     */
    public function isSubscriptionExpiringSoon(): bool
    {
        if (!$this->subscription_ends_at) {
            return false;
        }

        $daysRemaining = now()->diffInDays($this->subscription_ends_at->endOfDay(), false);
        return $daysRemaining >= 0 && $daysRemaining <= 7;
    }

    /**
     * Check if subscription expires today
     */
    public function isSubscriptionExpiringToday(): bool
    {
        if (!$this->subscription_ends_at) {
            return false;
        }

        return $this->subscription_ends_at->isToday();
    }

    /**
     * Check if organization can add more stores
     */
    public function canAddStore(): bool
    {
        // -1 signifie illimité
        if ($this->max_stores === -1) {
            return true;
        }
        return $this->stores()->count() < $this->max_stores;
    }

    /**
     * Check if organization can add more users
     */
    public function canAddUser(): bool
    {
        // -1 signifie illimité
        if ($this->max_users === -1) {
            return true;
        }
        return $this->members()->count() < $this->max_users;
    }

    /**
     * Check if organization can add more products
     */
    public function canAddProduct(): bool
    {
        // -1 signifie illimité
        if ($this->max_products === -1) {
            return true;
        }
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
        $isUnlimited = $this->max_products === -1;
        return [
            'current' => $current,
            'max' => $isUnlimited ? '∞' : $this->max_products,
            'remaining' => $isUnlimited ? '∞' : max(0, $this->max_products - $current),
            'percentage' => $isUnlimited ? 0 : ($this->max_products > 0 ? round(($current / $this->max_products) * 100) : 0),
            'unlimited' => $isUnlimited,
        ];
    }

    /**
     * Get stores usage info
     */
    public function getStoresUsage(): array
    {
        $current = $this->stores()->count();
        $isUnlimited = $this->max_stores === -1;
        return [
            'current' => $current,
            'max' => $isUnlimited ? '∞' : $this->max_stores,
            'remaining' => $isUnlimited ? '∞' : max(0, $this->max_stores - $current),
            'percentage' => $isUnlimited ? 0 : ($this->max_stores > 0 ? round(($current / $this->max_stores) * 100) : 0),
            'unlimited' => $isUnlimited,
        ];
    }

    /**
     * Get users usage info
     */
    public function getUsersUsage(): array
    {
        $current = $this->members()->count();
        $isUnlimited = $this->max_users === -1;
        return [
            'current' => $current,
            'max' => $isUnlimited ? '∞' : $this->max_users,
            'remaining' => $isUnlimited ? '∞' : max(0, $this->max_users - $current),
            'percentage' => $isUnlimited ? 0 : ($this->max_users > 0 ? round(($current / $this->max_users) * 100) : 0),
            'unlimited' => $isUnlimited,
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
     * Get business activity label
     */
    public function getBusinessActivityLabelAttribute(): string
    {
        if ($this->business_activity instanceof BusinessActivityType) {
            return $this->business_activity->label();
        }

        return match($this->business_activity) {
            'retail' => 'Commerce de détail',
            'food' => 'Alimentaire',
            'services' => 'Services',
            'mixed' => 'Mixte (Produits & Services)',
            default => (string) $this->business_activity,
        };
    }

    /**
     * Check if the organization can sell physical products
     */
    public function canSellPhysicalProducts(): bool
    {
        if ($this->business_activity instanceof BusinessActivityType) {
            return $this->business_activity->canSellPhysicalProducts();
        }
        return in_array($this->business_activity, ['retail', 'food', 'mixed']);
    }

    /**
     * Check if the organization can sell services
     */
    public function canSellServices(): bool
    {
        if ($this->business_activity instanceof BusinessActivityType) {
            return $this->business_activity->canSellServices();
        }
        return in_array($this->business_activity, ['services', 'mixed']);
    }

    /**
     * Check if stock management is available for this organization.
     *
     * Service-only organizations don't need stock management since they sell
     * services, not physical products.
     *
     * This returns false for service organizations regardless of their
     * subscription plan's features.
     */
    public function hasStockManagement(): bool
    {
        // Service-only organizations don't need stock management
        if ($this->business_activity instanceof BusinessActivityType) {
            return $this->business_activity !== BusinessActivityType::SERVICES;
        }

        return $this->business_activity !== 'services';
    }

    /**
     * Check if this organization is a service-only organization.
     */
    public function isServiceOrganization(): bool
    {
        if ($this->business_activity instanceof BusinessActivityType) {
            return $this->business_activity === BusinessActivityType::SERVICES;
        }

        return $this->business_activity === 'services';
    }

    /**
     * Get available product types for this organization based on business activity.
     * Returns global types (organization_id = null) that are compatible with this org's activity.
     */
    public function getAvailableProductTypes()
    {
        $query = ProductType::where(function ($q) {
            // Global types (no organization_id) OR types created by this organization
            $q->whereNull('organization_id')
              ->orWhere('organization_id', $this->id);
        })->where('is_active', true);

        // Filter by business activity compatibility
        if ($this->business_activity instanceof BusinessActivityType) {
            $activity = $this->business_activity;
        } else {
            $activity = BusinessActivityType::tryFrom($this->business_activity) ?? BusinessActivityType::MIXED;
        }

        // If not MIXED, filter by compatible types
        if ($activity !== BusinessActivityType::MIXED) {
            $query->where(function ($q) use ($activity) {
                // Types with null compatible_activities are available to all
                $q->whereNull('compatible_activities')
                  // Or types that have this activity in their compatible list
                  ->orWhereJsonContains('compatible_activities', $activity->value);
            });
        }

        return $query->ordered()->get();
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
     *
     * Pour les plans payants, l'organisation est accessible si:
     * - Le paiement a été complété
     * - ET l'abonnement n'est pas expiré (ou les dates ne sont pas encore définies)
     */
    public function isAccessible(): bool
    {
        // Free plan is always accessible
        if ($this->subscription_plan === SubscriptionPlan::FREE) {
            return true;
        }

        // Paid plans require payment completion
        if ($this->payment_status !== PaymentStatus::COMPLETED) {
            return false;
        }

        // Si les dates d'abonnement sont définies, vérifier que l'abonnement n'est pas expiré
        if ($this->subscription_ends_at) {
            return $this->subscription_ends_at->endOfDay()->isFuture();
        }

        // Si pas de date de fin (nouveau paiement), accessible
        return true;
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
     *
     * @param string $paymentReference Référence de paiement (ex: transaction_id Shwary)
     * @param string $paymentMethod Méthode de paiement (mobile_money, card, etc.)
     * @param float|null $amount Montant payé (optionnel, utilise le prix du plan si non fourni)
     * @param array $metadata Données supplémentaires (shwary_transaction_id, etc.)
     */
    public function markPaymentCompleted(
        string $paymentReference,
        string $paymentMethod,
        ?float $amount = null,
        array $metadata = []
    ): void
    {
        $oldPlan = $this->subscription_plan->value;

        // Calculer le montant si non fourni
        if ($amount === null) {
            $plans = \App\Services\SubscriptionService::getPlansFromCache();
            $planData = $plans[$this->subscription_plan->value] ?? [];
            $amount = $planData['price'] ?? 0;
        }

        // Définir les dates d'abonnement si elles ne sont pas encore définies
        // (cas d'un premier paiement après inscription)
        $subscriptionStartsAt = $this->subscription_starts_at ?? now();
        $subscriptionEndsAt = $this->subscription_ends_at ?? now()->addMonth();

        // Mettre à jour l'organisation
        $this->update([
            'payment_status' => PaymentStatus::COMPLETED,
            'payment_reference' => $paymentReference,
            'payment_method' => $paymentMethod,
            'payment_completed_at' => now(),
            'subscription_starts_at' => $subscriptionStartsAt,
            'subscription_ends_at' => $subscriptionEndsAt,
            'is_active' => true,
        ]);

        // Créer l'enregistrement SubscriptionPayment
        $subscriptionPayment = SubscriptionPayment::create([
            'organization_id' => $this->id,
            'user_id' => $this->owner_id,
            'reference' => 'PAY-' . strtoupper(\Illuminate\Support\Str::random(10)),
            'plan' => $this->subscription_plan->value,
            'duration_months' => 1,
            'amount' => $amount,
            'discount' => 0,
            'tax' => 0,
            'total' => $amount,
            'currency' => $this->currency ?? 'CDF',
            'payment_method' => $paymentMethod,
            'payment_provider' => $paymentMethod === 'mobile_money' ? 'shwary' : $paymentMethod,
            'transaction_id' => $paymentReference,
            'status' => SubscriptionPayment::STATUS_COMPLETED,
            'paid_at' => now(),
            'period_starts_at' => $subscriptionStartsAt,
            'period_ends_at' => $subscriptionEndsAt,
            'invoice_number' => 'INV-' . date('Ymd') . '-' . str_pad($this->id, 5, '0', STR_PAD_LEFT),
            'metadata' => array_merge($metadata, [
                'shwary_reference' => $paymentReference,
                'payment_method' => $paymentMethod,
            ]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Créer l'entrée dans SubscriptionHistory
        SubscriptionHistory::record(
            organization: $this,
            action: SubscriptionHistory::ACTION_CREATED,
            oldPlan: $oldPlan,
            payment: $subscriptionPayment,
            notes: "Paiement initial via {$paymentMethod} - Référence: {$paymentReference}"
        );

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
            'organizations.create',   // Création d'organisation (super-admin uniquement)
            'configuration',          // Menu Configuration parent
            'admin-categories',       // Catégories (configuration plateforme)
            'admin-product-types',    // Types de produits (configuration plateforme)
            'admin-product-attributes', // Attributs (configuration plateforme)
            'roles',                  // Rôles (gestion plateforme)
            'roles.index',            // Liste des rôles
            'menu-permissions',       // Gestion des menus (configuration plateforme)
            'subscription-settings',  // Paramètres d'abonnement (configuration plateforme)
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
