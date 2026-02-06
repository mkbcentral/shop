<?php

namespace App\Enums;

enum OrganizationRole: string
{
    case OWNER = 'owner';
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case ACCOUNTANT = 'accountant';
    case MEMBER = 'member';

    /**
     * Get the display label for the role.
     */
    public function label(): string
    {
        return match ($this) {
            self::OWNER => 'Propriétaire',
            self::ADMIN => 'Administrateur',
            self::MANAGER => 'Manager',
            self::ACCOUNTANT => 'Comptable',
            self::MEMBER => 'Membre',
        };
    }

    /**
     * Get the description for the role.
     */
    public function description(): string
    {
        return match ($this) {
            self::OWNER => 'Tous les droits, y compris le transfert de propriété',
            self::ADMIN => 'Tous les droits sauf transfert de propriété',
            self::MANAGER => 'Gestion des magasins et utilisateurs',
            self::ACCOUNTANT => 'Accès financier et rapports',
            self::MEMBER => 'Accès de base en lecture',
        };
    }

    /**
     * Get all roles as array [value => label].
     */
    public static function toArray(): array
    {
        $roles = [];
        foreach (self::cases() as $role) {
            $roles[$role->value] = $role->label();
        }
        return $roles;
    }

    /**
     * Get roles available for invitation (excludes owner).
     */
    public static function invitableRoles(): array
    {
        return array_filter(
            self::toArray(),
            fn($value, $key) => $key !== self::OWNER->value,
            ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * Get the role order for sorting.
     */
    public function order(): int
    {
        return match ($this) {
            self::OWNER => 1,
            self::ADMIN => 2,
            self::MANAGER => 3,
            self::ACCOUNTANT => 4,
            self::MEMBER => 5,
        };
    }
}
