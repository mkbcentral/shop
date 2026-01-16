<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'organization_id',
        'user_id',
        'action',
        'old_plan',
        'new_plan',
        'subscription_starts_at',
        'subscription_ends_at',
        'max_stores',
        'max_users',
        'max_products',
        'subscription_payment_id',
        'amount',
        'currency',
        'notes',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'subscription_starts_at' => 'datetime',
            'subscription_ends_at' => 'datetime',
            'amount' => 'decimal:2',
            'metadata' => 'array',
        ];
    }

    /**
     * Actions disponibles
     */
    public const ACTION_CREATED = 'created';
    public const ACTION_UPGRADED = 'upgraded';
    public const ACTION_DOWNGRADED = 'downgraded';
    public const ACTION_RENEWED = 'renewed';
    public const ACTION_CANCELLED = 'cancelled';
    public const ACTION_EXPIRED = 'expired';
    public const ACTION_REACTIVATED = 'reactivated';
    public const ACTION_TRIAL_STARTED = 'trial_started';
    public const ACTION_TRIAL_ENDED = 'trial_ended';

    /**
     * Labels des actions
     */
    public const ACTION_LABELS = [
        self::ACTION_CREATED => 'Création',
        self::ACTION_UPGRADED => 'Mise à niveau',
        self::ACTION_DOWNGRADED => 'Rétrogradation',
        self::ACTION_RENEWED => 'Renouvellement',
        self::ACTION_CANCELLED => 'Annulation',
        self::ACTION_EXPIRED => 'Expiration',
        self::ACTION_REACTIVATED => 'Réactivation',
        self::ACTION_TRIAL_STARTED => 'Début essai',
        self::ACTION_TRIAL_ENDED => 'Fin essai',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Organisation concernée
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Utilisateur ayant effectué l'action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Paiement associé
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPayment::class, 'subscription_payment_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Libellé de l'action
     */
    public function getActionLabelAttribute(): string
    {
        return self::ACTION_LABELS[$this->action] ?? $this->action;
    }

    /**
     * Libellé du plan
     */
    public function getPlanLabelAttribute(): string
    {
        $labels = [
            'free' => 'Gratuit',
            'starter' => 'Starter',
            'professional' => 'Professionnel',
            'enterprise' => 'Entreprise',
        ];

        return $labels[$this->new_plan] ?? $this->new_plan;
    }

    /**
     * Couleur selon l'action
     */
    public function getActionColorAttribute(): string
    {
        return match ($this->action) {
            self::ACTION_CREATED, self::ACTION_TRIAL_STARTED => 'blue',
            self::ACTION_UPGRADED, self::ACTION_REACTIVATED => 'green',
            self::ACTION_RENEWED => 'indigo',
            self::ACTION_DOWNGRADED => 'yellow',
            self::ACTION_CANCELLED, self::ACTION_EXPIRED => 'red',
            self::ACTION_TRIAL_ENDED => 'gray',
            default => 'gray',
        };
    }

    /**
     * Icône selon l'action
     */
    public function getActionIconAttribute(): string
    {
        return match ($this->action) {
            self::ACTION_CREATED => 'plus-circle',
            self::ACTION_UPGRADED => 'arrow-up-circle',
            self::ACTION_DOWNGRADED => 'arrow-down-circle',
            self::ACTION_RENEWED => 'refresh',
            self::ACTION_CANCELLED => 'x-circle',
            self::ACTION_EXPIRED => 'clock',
            self::ACTION_REACTIVATED => 'check-circle',
            self::ACTION_TRIAL_STARTED => 'play',
            self::ACTION_TRIAL_ENDED => 'stop',
            default => 'information-circle',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Filtrer par organisation
     */
    public function scopeForOrganization($query, int $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    /**
     * Filtrer par action
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Historique récent
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /*
    |--------------------------------------------------------------------------
    | Static Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Créer une entrée d'historique
     */
    public static function record(
        Organization $organization,
        string $action,
        ?string $oldPlan = null,
        ?SubscriptionPayment $payment = null,
        ?string $notes = null,
        ?User $user = null
    ): self {
        return self::create([
            'organization_id' => $organization->id,
            'user_id' => $user?->id ?? auth()->id(),
            'action' => $action,
            'old_plan' => $oldPlan,
            'new_plan' => $organization->subscription_plan,
            'subscription_starts_at' => $organization->subscription_starts_at,
            'subscription_ends_at' => $organization->subscription_ends_at,
            'max_stores' => $organization->max_stores,
            'max_users' => $organization->max_users,
            'max_products' => $organization->max_products,
            'subscription_payment_id' => $payment?->id,
            'amount' => $payment?->total ?? 0,
            'currency' => $organization->currency ?? 'CDF',
            'notes' => $notes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
