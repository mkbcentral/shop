<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';
    case CANCELLED = 'cancelled';

    /**
     * Get the display label for the status
     */
    public function label(): string
    {
        return match($this) {
            self::PENDING => 'En attente',
            self::COMPLETED => 'Complété',
            self::FAILED => 'Échoué',
            self::REFUNDED => 'Remboursé',
            self::CANCELLED => 'Annulé',
        };
    }

    /**
     * Get the badge color for the status
     */
    public function color(): string
    {
        return match($this) {
            self::PENDING => 'yellow',
            self::COMPLETED => 'green',
            self::FAILED => 'red',
            self::REFUNDED => 'gray',
            self::CANCELLED => 'gray',
        };
    }

    /**
     * Check if payment is successful
     */
    public function isSuccessful(): bool
    {
        return $this === self::COMPLETED;
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    /**
     * Check if organization should be accessible
     */
    public function allowsAccess(): bool
    {
        return $this === self::COMPLETED;
    }
}
