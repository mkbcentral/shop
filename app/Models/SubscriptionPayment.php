<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SubscriptionPayment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'organization_id',
        'user_id',
        'reference',
        'plan',
        'duration_months',
        'amount',
        'discount',
        'tax',
        'total',
        'currency',
        'promo_code',
        'payment_method',
        'payment_provider',
        'transaction_id',
        'status',
        'paid_at',
        'refunded_at',
        'period_starts_at',
        'period_ends_at',
        'invoice_number',
        'receipt_path',
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
            'amount' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
            'paid_at' => 'datetime',
            'refunded_at' => 'datetime',
            'period_starts_at' => 'datetime',
            'period_ends_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    /**
     * Statuts disponibles
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_REFUNDED = 'refunded';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Méthodes de paiement
     */
    public const METHOD_CASH = 'cash';
    public const METHOD_BANK_TRANSFER = 'bank_transfer';
    public const METHOD_MOBILE_MONEY = 'mobile_money';
    public const METHOD_CARD = 'card';
    public const METHOD_STRIPE = 'stripe';
    public const METHOD_PAYPAL = 'paypal';
    public const METHOD_OTHER = 'other';

    /**
     * Labels des statuts
     */
    public const STATUS_LABELS = [
        self::STATUS_PENDING => 'En attente',
        self::STATUS_PROCESSING => 'En cours',
        self::STATUS_COMPLETED => 'Terminé',
        self::STATUS_FAILED => 'Échoué',
        self::STATUS_REFUNDED => 'Remboursé',
        self::STATUS_CANCELLED => 'Annulé',
    ];

    /**
     * Labels des méthodes de paiement
     */
    public const METHOD_LABELS = [
        self::METHOD_CASH => 'Espèces',
        self::METHOD_BANK_TRANSFER => 'Virement bancaire',
        self::METHOD_MOBILE_MONEY => 'Mobile Money',
        self::METHOD_CARD => 'Carte bancaire',
        self::METHOD_STRIPE => 'Stripe',
        self::METHOD_PAYPAL => 'PayPal',
        self::METHOD_OTHER => 'Autre',
    ];

    /**
     * Prix par plan (en CDF)
     */
    public const PLAN_PRICES = [
        'free' => 0,
        'starter' => 9900,
        'professional' => 24900,
        'enterprise' => 49900,
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
     * Utilisateur ayant effectué le paiement
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Entrées d'historique associées
     */
    public function histories(): HasMany
    {
        return $this->hasMany(SubscriptionHistory::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Libellé du statut
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    /**
     * Couleur du statut
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_PROCESSING => 'blue',
            self::STATUS_COMPLETED => 'green',
            self::STATUS_FAILED => 'red',
            self::STATUS_REFUNDED => 'purple',
            self::STATUS_CANCELLED => 'gray',
            default => 'gray',
        };
    }

    /**
     * Libellé de la méthode de paiement
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        return self::METHOD_LABELS[$this->payment_method] ?? $this->payment_method;
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

        return $labels[$this->plan] ?? $this->plan;
    }

    /**
     * Montant formaté
     */
    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total, 0, ',', ' ') . ' ' . $this->currency;
    }

    /**
     * Vérifie si le paiement est complété
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Vérifie si le paiement est en attente
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Vérifie si le paiement a échoué
     */
    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
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
     * Filtrer par statut
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Paiements complétés
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Paiements en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Paiements récents
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
     * Générer une référence unique
     */
    public static function generateReference(): string
    {
        $prefix = 'PAY';
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(6));
        
        return "{$prefix}-{$date}-{$random}";
    }

    /**
     * Générer un numéro de facture
     */
    public static function generateInvoiceNumber(Organization $organization): string
    {
        $prefix = 'INV';
        $year = now()->format('Y');
        $orgCode = str_pad($organization->id, 4, '0', STR_PAD_LEFT);
        
        $lastInvoice = self::where('organization_id', $organization->id)
            ->whereYear('created_at', now()->year)
            ->whereNotNull('invoice_number')
            ->orderByDesc('id')
            ->first();
        
        $sequence = 1;
        if ($lastInvoice && preg_match('/(\d+)$/', $lastInvoice->invoice_number, $matches)) {
            $sequence = (int) $matches[1] + 1;
        }
        
        $seq = str_pad($sequence, 5, '0', STR_PAD_LEFT);
        
        return "{$prefix}-{$year}-{$orgCode}-{$seq}";
    }

    /**
     * Calculer le prix pour un plan et une durée
     */
    public static function calculatePrice(string $plan, int $durationMonths = 1): array
    {
        $monthlyPrice = self::PLAN_PRICES[$plan] ?? 0;
        $amount = $monthlyPrice * $durationMonths;
        
        // Réductions pour paiements multi-mois
        $discount = 0;
        if ($durationMonths >= 12) {
            $discount = $amount * 0.20; // 20% de réduction pour 12 mois
        } elseif ($durationMonths >= 6) {
            $discount = $amount * 0.10; // 10% de réduction pour 6 mois
        } elseif ($durationMonths >= 3) {
            $discount = $amount * 0.05; // 5% de réduction pour 3 mois
        }
        
        $total = $amount - $discount;
        
        return [
            'amount' => $amount,
            'discount' => $discount,
            'tax' => 0, // TVA non applicable actuellement
            'total' => $total,
            'monthly_equivalent' => $durationMonths > 0 ? $total / $durationMonths : 0,
        ];
    }

    /**
     * Créer un paiement pour une organisation
     */
    public static function createForOrganization(
        Organization $organization,
        string $plan,
        int $durationMonths = 1,
        string $paymentMethod = self::METHOD_MOBILE_MONEY,
        ?string $promoCode = null
    ): self {
        $pricing = self::calculatePrice($plan, $durationMonths);
        
        return self::create([
            'organization_id' => $organization->id,
            'user_id' => auth()->id(),
            'reference' => self::generateReference(),
            'plan' => $plan,
            'duration_months' => $durationMonths,
            'amount' => $pricing['amount'],
            'discount' => $pricing['discount'],
            'tax' => $pricing['tax'],
            'total' => $pricing['total'],
            'currency' => $organization->currency ?? 'CDF',
            'promo_code' => $promoCode,
            'payment_method' => $paymentMethod,
            'status' => self::STATUS_PENDING,
            'period_starts_at' => now(),
            'period_ends_at' => now()->addMonths($durationMonths),
            'invoice_number' => self::generateInvoiceNumber($organization),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Marquer comme complété
     */
    public function markAsCompleted(?string $transactionId = null): self
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'paid_at' => now(),
            'transaction_id' => $transactionId,
        ]);

        return $this;
    }

    /**
     * Marquer comme échoué
     */
    public function markAsFailed(?string $reason = null): self
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'notes' => $reason,
        ]);

        return $this;
    }

    /**
     * Rembourser le paiement
     */
    public function refund(?string $reason = null): self
    {
        $this->update([
            'status' => self::STATUS_REFUNDED,
            'refunded_at' => now(),
            'notes' => $reason,
        ]);

        return $this;
    }
}
