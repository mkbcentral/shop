<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle pour les transactions Shwary Mobile Money
 *
 * @property int $id
 * @property string|null $transaction_id
 * @property string|null $reference
 * @property float $amount
 * @property string $currency
 * @property string $phone_number
 * @property string $country_code
 * @property string $status
 * @property string|null $failure_reason
 * @property array|null $metadata
 * @property array|null $response_data
 * @property \Carbon\Carbon|null $completed_at
 * @property \Carbon\Carbon|null $failed_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ShwaryTransaction extends Model
{
    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'transaction_id',
        'reference',
        'amount',
        'currency',
        'phone_number',
        'country_code',
        'status',
        'failure_reason',
        'metadata',
        'response_data',
        'completed_at',
        'failed_at',
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'response_data' => 'array',
        'completed_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    /**
     * Constantes de statut
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_EXPIRED = 'expired';

    /**
     * Statuts considérés comme "terminés avec succès"
     */
    public const SUCCESS_STATUSES = [self::STATUS_COMPLETED, self::STATUS_SUCCESS];

    /**
     * Statuts considérés comme "échoués"
     */
    public const FAILED_STATUSES = [self::STATUS_FAILED, self::STATUS_CANCELLED, self::STATUS_REJECTED, self::STATUS_EXPIRED];

    /**
     * Vérifier si la transaction est complétée avec succès
     */
    public function isCompleted(): bool
    {
        return in_array($this->status, self::SUCCESS_STATUSES);
    }

    /**
     * Vérifier si la transaction a échoué
     */
    public function isFailed(): bool
    {
        return in_array($this->status, self::FAILED_STATUSES);
    }

    /**
     * Vérifier si la transaction est en attente
     */
    public function isPending(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PROCESSING]);
    }

    /**
     * Obtenir le libellé du statut
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'En attente',
            self::STATUS_PROCESSING => 'En cours',
            self::STATUS_COMPLETED, self::STATUS_SUCCESS => 'Réussi',
            self::STATUS_FAILED => 'Échoué',
            self::STATUS_CANCELLED => 'Annulé',
            self::STATUS_REJECTED => 'Rejeté',
            self::STATUS_EXPIRED => 'Expiré',
            default => 'Inconnu',
        };
    }

    /**
     * Obtenir la couleur du badge de statut
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING, self::STATUS_PROCESSING => 'yellow',
            self::STATUS_COMPLETED, self::STATUS_SUCCESS => 'green',
            self::STATUS_FAILED, self::STATUS_CANCELLED, self::STATUS_REJECTED, self::STATUS_EXPIRED => 'red',
            default => 'gray',
        };
    }

    /**
     * Obtenir l'organisation liée (si dans les métadonnées)
     */
    public function getOrganization(): ?Organization
    {
        $organizationId = $this->metadata['organization_id'] ?? null;
        
        if ($organizationId) {
            return Organization::find($organizationId);
        }

        return null;
    }

    /**
     * Obtenir le montant formaté
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 0, ',', ' ') . ' ' . $this->currency;
    }

    /**
     * Scope pour les transactions en attente
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_PROCESSING]);
    }

    /**
     * Scope pour les transactions complétées
     */
    public function scopeCompleted($query)
    {
        return $query->whereIn('status', self::SUCCESS_STATUSES);
    }

    /**
     * Scope pour les transactions échouées
     */
    public function scopeFailed($query)
    {
        return $query->whereIn('status', self::FAILED_STATUSES);
    }

    /**
     * Scope par organisation
     */
    public function scopeForOrganization($query, int $organizationId)
    {
        return $query->whereJsonContains('metadata->organization_id', $organizationId);
    }
}
