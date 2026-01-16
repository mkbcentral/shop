# 🚀 Guide d'Implémentation - Entité Organization

**Basé sur :** ORGANIZATION_ENTITY_PROPOSAL.md  
**Date :** 8 Janvier 2026  
**Durée estimée :** 11-14 jours

---

## 📋 Vue d'Ensemble

Ce guide détaille les étapes concrètes pour implémenter l'architecture multi-entités (Organization → Stores) dans l'application STK-Back.

---

## 🎯 Phase 1 : Base de Données (2 jours)

### Étape 1.1 - Créer la migration `organizations`

```bash
php artisan make:migration create_organizations_table
```

**Fichier :** `database/migrations/XXXX_XX_XX_XXXXXX_create_organizations_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            
            // Informations de base
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('legal_name')->nullable();
            $table->enum('type', [
                'individual',
                'company',
                'franchise',
                'cooperative',
                'group'
            ])->default('company');
            
            // Informations légales
            $table->string('tax_id')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('legal_form')->nullable();
            
            // Contact
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('CD');
            
            // Branding
            $table->string('logo')->nullable();
            $table->string('website')->nullable();
            
            // Propriétaire
            $table->foreignId('owner_id')->constrained('users');
            
            // Abonnement
            $table->enum('subscription_plan', [
                'free',
                'starter',
                'professional',
                'enterprise'
            ])->default('free');
            $table->timestamp('subscription_starts_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->boolean('is_trial')->default(true);
            
            // Limites
            $table->integer('max_stores')->default(1);
            $table->integer('max_users')->default(3);
            $table->integer('max_products')->default(100);
            
            // Configuration
            $table->json('settings')->nullable();
            $table->string('currency')->default('CDF');
            $table->string('timezone')->default('Africa/Kinshasa');
            
            // Statut
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index
            $table->index('owner_id');
            $table->index('subscription_plan');
            $table->index('is_active');
            $table->index(['type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
```

---

### Étape 1.2 - Créer la table pivot `organization_user`

```bash
php artisan make:migration create_organization_user_table
```

**Fichier :** `database/migrations/XXXX_XX_XX_XXXXXX_create_organization_user_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->enum('role', [
                'owner',
                'admin',
                'manager',
                'accountant',
                'member'
            ])->default('member');
            
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->foreignId('invited_by')->nullable()->constrained('users');
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            $table->unique(['organization_id', 'user_id']);
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_user');
    }
};
```

---

### Étape 1.3 - Ajouter `organization_id` à la table `stores`

```bash
php artisan make:migration add_organization_to_stores_table
```

**Fichier :** `database/migrations/XXXX_XX_XX_XXXXXX_add_organization_to_stores_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->foreignId('organization_id')
                  ->nullable()
                  ->after('id')
                  ->constrained()
                  ->cascadeOnDelete();
            
            $table->integer('store_number')->nullable()->after('code');
            
            $table->index('organization_id');
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropColumn(['organization_id', 'store_number']);
        });
    }
};
```

---

### Étape 1.4 - Ajouter `default_organization_id` à la table `users`

```bash
php artisan make:migration add_default_organization_to_users_table
```

**Fichier :** `database/migrations/XXXX_XX_XX_XXXXXX_add_default_organization_to_users_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('default_organization_id')
                  ->nullable()
                  ->after('current_store_id')
                  ->constrained('organizations')
                  ->nullOnDelete();
            
            $table->index('default_organization_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['default_organization_id']);
            $table->dropColumn('default_organization_id');
        });
    }
};
```

---

### Étape 1.5 - Créer la table `organization_invitations` (optionnel)

```bash
php artisan make:migration create_organization_invitations_table
```

**Fichier :** `database/migrations/XXXX_XX_XX_XXXXXX_create_organization_invitations_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('email');
            $table->enum('role', ['admin', 'manager', 'accountant', 'member'])->default('member');
            $table->string('token')->unique();
            $table->foreignId('invited_by')->constrained('users');
            $table->timestamp('expires_at');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
            
            $table->index(['email', 'organization_id']);
            $table->index('token');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_invitations');
    }
};
```

---

### ✅ Commandes Phase 1

```bash
# Exécuter toutes les migrations
php artisan migrate

# Vérifier les tables créées
php artisan db:show --counts
```

---

## 🎯 Phase 2 : Models et Relations (1-2 jours)

### Étape 2.1 - Créer le Model Organization

```bash
php artisan make:model Organization
```

**Fichier :** `app/Models/Organization.php`

```php
<?php

namespace App\Models;

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
        'is_trial',
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
        'subscription_starts_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_user')
            ->withPivot('role', 'invited_at', 'accepted_at', 'is_active')
            ->withTimestamps();
    }

    public function activeMembers(): BelongsToMany
    {
        return $this->members()->wherePivot('is_active', true);
    }

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    public function activeStores(): HasMany
    {
        return $this->stores()->where('is_active', true);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(OrganizationInvitation::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isPaid(): bool
    {
        return $this->subscription_plan !== 'free';
    }

    public function hasActiveSubscription(): bool
    {
        if ($this->subscription_plan === 'free') {
            return true;
        }
        return $this->subscription_ends_at && $this->subscription_ends_at->isFuture();
    }

    public function canAddStore(): bool
    {
        return $this->stores()->count() < $this->max_stores;
    }

    public function canAddUser(): bool
    {
        return $this->members()->count() < $this->max_users;
    }

    public function getRemainingDaysAttribute(): ?int
    {
        if (!$this->subscription_ends_at) {
            return null;
        }
        return max(0, now()->diffInDays($this->subscription_ends_at, false));
    }

    public function isOwner(User $user): bool
    {
        return $this->owner_id === $user->id;
    }

    public function isAdmin(User $user): bool
    {
        if ($this->isOwner($user)) {
            return true;
        }
        $member = $this->members()->where('user_id', $user->id)->first();
        return $member && in_array($member->pivot->role, ['owner', 'admin']);
    }

    public function getUserRole(User $user): ?string
    {
        if ($this->isOwner($user)) {
            return 'owner';
        }
        $member = $this->members()->where('user_id', $user->id)->first();
        return $member?->pivot->role;
    }

    public function hasMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }
}
```

---

### Étape 2.2 - Créer le Model OrganizationInvitation

```bash
php artisan make:model OrganizationInvitation
```

**Fichier :** `app/Models/OrganizationInvitation.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationInvitation extends Model
{
    protected $fillable = [
        'organization_id',
        'email',
        'role',
        'token',
        'invited_by',
        'expires_at',
        'accepted_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isAccepted(): bool
    {
        return $this->accepted_at !== null;
    }

    public function isPending(): bool
    {
        return !$this->isExpired() && !$this->isAccepted();
    }
}
```

---

### Étape 2.3 - Modifier le Model User

**Fichier :** `app/Models/User.php` - Ajouter les relations suivantes :

```php
// Ajouter ces imports en haut du fichier
use Illuminate\Database\Eloquent\Relations\HasMany;

// Ajouter dans $fillable
'default_organization_id',

// Ajouter ces relations dans la classe User

/**
 * Organizations where user is member
 */
public function organizations(): BelongsToMany
{
    return $this->belongsToMany(Organization::class, 'organization_user')
        ->withPivot('role', 'invited_at', 'accepted_at', 'is_active')
        ->withTimestamps();
}

/**
 * Organizations owned by user
 */
public function ownedOrganizations(): HasMany
{
    return $this->hasMany(Organization::class, 'owner_id');
}

/**
 * Default organization
 */
public function defaultOrganization(): BelongsTo
{
    return $this->belongsTo(Organization::class, 'default_organization_id');
}

/**
 * Check if user belongs to an organization
 */
public function belongsToOrganization(int $organizationId): bool
{
    return $this->organizations()->where('organizations.id', $organizationId)->exists();
}

/**
 * Get user's role in an organization
 */
public function getRoleInOrganization(Organization $organization): ?string
{
    $membership = $this->organizations()
        ->where('organizations.id', $organization->id)
        ->first();
    
    return $membership?->pivot->role;
}

/**
 * Check if user is admin in organization
 */
public function isOrganizationAdmin(Organization $organization): bool
{
    $role = $this->getRoleInOrganization($organization);
    return in_array($role, ['owner', 'admin']);
}
```

---

### Étape 2.4 - Modifier le Model Store

**Fichier :** `app/Models/Store.php` - Ajouter :

```php
// Ajouter dans $fillable
'organization_id',
'store_number',

// Ajouter cette relation
public function organization(): BelongsTo
{
    return $this->belongsTo(Organization::class);
}

// Ajouter ce scope
public function scopeForOrganization($query, ?int $organizationId = null)
{
    if ($organizationId) {
        return $query->where('organization_id', $organizationId);
    }
    
    if ($organization = app('current_organization')) {
        return $query->where('organization_id', $organization->id);
    }
    
    return $query;
}
```

---

### Étape 2.5 - Créer le Trait BelongsToOrganization

```bash
mkdir -p app/Traits
```

**Fichier :** `app/Traits/BelongsToOrganization.php`

```php
<?php

namespace App\Traits;

use App\Models\Organization;

trait BelongsToOrganization
{
    protected static function bootBelongsToOrganization(): void
    {
        // Filtre automatique par organization
        static::addGlobalScope('organization', function ($query) {
            if ($organization = app('current_organization')) {
                $query->where('organization_id', $organization->id);
            }
        });
        
        // Auto-assign organization_id lors de la création
        static::creating(function ($model) {
            if (!$model->organization_id && $organization = app('current_organization')) {
                $model->organization_id = $organization->id;
            }
        });
    }
    
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function scopeWithoutOrganizationScope($query)
    {
        return $query->withoutGlobalScope('organization');
    }
}
```

---

## 🎯 Phase 3 : Services et Actions (2-3 jours)

### Étape 3.1 - Créer le Repository

**Fichier :** `app/Repositories/OrganizationRepository.php`

```php
<?php

namespace App\Repositories;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class OrganizationRepository
{
    public function __construct(
        private Organization $model
    ) {}

    public function find(int $id): ?Organization
    {
        return $this->model->find($id);
    }

    public function findBySlug(string $slug): ?Organization
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function create(array $data): Organization
    {
        return $this->model->create($data);
    }

    public function update(Organization $organization, array $data): Organization
    {
        $organization->update($data);
        return $organization->fresh();
    }

    public function delete(Organization $organization): bool
    {
        return $organization->delete();
    }

    public function getForUser(User $user): Collection
    {
        return $user->organizations()->with('stores')->get();
    }

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('legal_name', 'like', "%{$filters['search']}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function getStatistics(Organization $organization): array
    {
        $storesCount = $organization->stores()->count();
        $membersCount = $organization->members()->count();
        
        $productsCount = $organization->stores()
            ->withCount('products')
            ->get()
            ->sum('products_count');

        return [
            'stores_count' => $storesCount,
            'members_count' => $membersCount,
            'products_count' => $productsCount,
        ];
    }
}
```

---

### Étape 3.2 - Créer le Service

**Fichier :** `app/Services/OrganizationService.php`

```php
<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\User;
use App\Repositories\OrganizationRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class OrganizationService
{
    public function __construct(
        private OrganizationRepository $repository
    ) {}

    /**
     * Créer une nouvelle organisation
     */
    public function create(array $data, User $owner): Organization
    {
        return DB::transaction(function () use ($data, $owner) {
            // Générer le slug
            $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
            $data['slug'] = $this->ensureUniqueSlug($data['slug']);
            $data['owner_id'] = $owner->id;
            
            // Appliquer les limites du plan
            $data = $this->applyPlanLimits($data);
            
            // Créer l'organisation
            $organization = $this->repository->create($data);
            
            // Ajouter le propriétaire comme membre
            $organization->members()->attach($owner->id, [
                'role' => 'owner',
                'accepted_at' => now(),
                'is_active' => true,
            ]);
            
            // Définir comme organisation par défaut si nécessaire
            if (!$owner->default_organization_id) {
                $owner->update(['default_organization_id' => $organization->id]);
            }
            
            return $organization;
        });
    }

    /**
     * Mettre à jour une organisation
     */
    public function update(Organization $organization, array $data): Organization
    {
        if (isset($data['name']) && !isset($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
            if ($data['slug'] !== $organization->slug) {
                $data['slug'] = $this->ensureUniqueSlug($data['slug'], $organization->id);
            }
        }
        
        return $this->repository->update($organization, $data);
    }

    /**
     * Supprimer une organisation
     */
    public function delete(Organization $organization): bool
    {
        return DB::transaction(function () use ($organization) {
            // Vérifier qu'il n'y a plus de magasins
            if ($organization->stores()->count() > 0) {
                throw new Exception("Impossible de supprimer une organisation avec des magasins actifs.");
            }
            
            // Détacher tous les membres
            $organization->members()->detach();
            
            // Supprimer les invitations
            $organization->invitations()->delete();
            
            // Supprimer l'organisation
            return $this->repository->delete($organization);
        });
    }

    /**
     * Inviter un membre
     */
    public function inviteMember(Organization $organization, string $email, string $role, User $invitedBy): void
    {
        if (!$organization->canAddUser()) {
            throw new Exception("Limite d'utilisateurs atteinte pour cette organisation.");
        }

        $user = User::where('email', $email)->first();

        if ($user && $organization->hasMember($user)) {
            throw new Exception("Cet utilisateur est déjà membre de l'organisation.");
        }

        $organization->invitations()->create([
            'email' => $email,
            'role' => $role,
            'token' => Str::random(64),
            'invited_by' => $invitedBy->id,
            'expires_at' => now()->addDays(7),
        ]);

        // TODO: Envoyer notification email
    }

    /**
     * Ajouter un membre existant
     */
    public function addMember(Organization $organization, User $user, string $role = 'member'): void
    {
        if (!$organization->canAddUser()) {
            throw new Exception("Limite d'utilisateurs atteinte.");
        }

        if ($organization->hasMember($user)) {
            throw new Exception("Cet utilisateur est déjà membre.");
        }

        $organization->members()->attach($user->id, [
            'role' => $role,
            'accepted_at' => now(),
            'is_active' => true,
        ]);
    }

    /**
     * Retirer un membre
     */
    public function removeMember(Organization $organization, User $user): void
    {
        if ($organization->isOwner($user)) {
            throw new Exception("Impossible de retirer le propriétaire de l'organisation.");
        }

        $organization->members()->detach($user->id);

        if ($user->default_organization_id === $organization->id) {
            $newDefault = $user->organizations()->first();
            $user->update(['default_organization_id' => $newDefault?->id]);
        }
    }

    /**
     * Mettre à jour le rôle d'un membre
     */
    public function updateMemberRole(Organization $organization, User $user, string $newRole): void
    {
        if ($organization->isOwner($user) && $newRole !== 'owner') {
            throw new Exception("Impossible de modifier le rôle du propriétaire.");
        }

        $organization->members()->updateExistingPivot($user->id, ['role' => $newRole]);
    }

    /**
     * Transférer la propriété
     */
    public function transferOwnership(Organization $organization, User $newOwner): void
    {
        if (!$organization->hasMember($newOwner)) {
            throw new Exception("Le nouvel propriétaire doit être membre de l'organisation.");
        }

        DB::transaction(function () use ($organization, $newOwner) {
            $currentOwner = $organization->owner;

            $organization->update(['owner_id' => $newOwner->id]);

            $organization->members()->updateExistingPivot($currentOwner->id, ['role' => 'admin']);
            $organization->members()->updateExistingPivot($newOwner->id, ['role' => 'owner']);
        });
    }

    /**
     * Appliquer les limites selon le plan
     */
    private function applyPlanLimits(array $data): array
    {
        $planLimits = [
            'free' => ['max_stores' => 1, 'max_users' => 3, 'max_products' => 100],
            'starter' => ['max_stores' => 3, 'max_users' => 10, 'max_products' => 1000],
            'professional' => ['max_stores' => 10, 'max_users' => 50, 'max_products' => 10000],
            'enterprise' => ['max_stores' => 100, 'max_users' => 500, 'max_products' => 100000],
        ];

        $plan = $data['subscription_plan'] ?? 'free';
        $limits = $planLimits[$plan] ?? $planLimits['free'];

        return array_merge($limits, $data);
    }

    /**
     * Assurer l'unicité du slug
     */
    private function ensureUniqueSlug(string $slug, ?int $excludeId = null): string
    {
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = Organization::where('slug', $slug);
            
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            if (!$query->exists()) {
                break;
            }

            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    /**
     * Obtenir les statistiques
     */
    public function getStatistics(Organization $organization): array
    {
        return $this->repository->getStatistics($organization);
    }
}
```

---

### Étape 3.3 - Créer la Policy

```bash
php artisan make:policy OrganizationPolicy --model=Organization
```

**Fichier :** `app/Policies/OrganizationPolicy.php`

```php
<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Organization $organization): bool
    {
        return $organization->hasMember($user);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Organization $organization): bool
    {
        return $organization->isAdmin($user);
    }

    public function delete(User $user, Organization $organization): bool
    {
        return $organization->isOwner($user);
    }

    public function manageMembers(User $user, Organization $organization): bool
    {
        $role = $organization->getUserRole($user);
        return in_array($role, ['owner', 'admin', 'manager']);
    }

    public function removeMember(User $user, Organization $organization): bool
    {
        return $organization->isAdmin($user);
    }

    public function manageSubscription(User $user, Organization $organization): bool
    {
        return $organization->isOwner($user);
    }

    public function transferOwnership(User $user, Organization $organization): bool
    {
        return $organization->isOwner($user);
    }
}
```

**Enregistrer la policy dans `app/Providers/AuthServiceProvider.php` :**

```php
protected $policies = [
    Organization::class => OrganizationPolicy::class,
];
```

---

### Étape 3.4 - Créer le Middleware

**Fichier :** `app/Http/Middleware/EnsureOrganizationAccess.php`

```php
<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizationAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return $next($request);
        }

        // Récupérer l'organization_id depuis la route ou la session
        $organizationId = $request->route('organization') 
                          ?? session('current_organization_id')
                          ?? $user->default_organization_id;

        if (!$organizationId) {
            return $next($request);
        }

        // Vérifier l'accès
        if (!$user->belongsToOrganization($organizationId)) {
            abort(403, 'Accès non autorisé à cette organisation');
        }

        // Charger l'organisation et la mettre dans le contexte
        $organization = Organization::find($organizationId);
        
        if ($organization) {
            app()->instance('current_organization', $organization);
            session(['current_organization_id' => $organization->id]);
        }

        return $next($request);
    }
}
```

**Enregistrer le middleware dans `bootstrap/app.php` ou `app/Http/Kernel.php` :**

```php
// Dans le groupe 'web' ou comme alias
'organization' => \App\Http\Middleware\EnsureOrganizationAccess::class,
```

---

## 🎯 Phase 4 : Interface Livewire (3-4 jours)

### Étape 4.1 - Composant Liste des Organisations

```bash
php artisan make:livewire Organization/OrganizationIndex
```

**Fichier :** `app/Livewire/Organization/OrganizationIndex.php`

```php
<?php

namespace App\Livewire\Organization;

use App\Models\Organization;
use Livewire\Component;
use Livewire\WithPagination;

class OrganizationIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $type = '';

    protected $queryString = ['search', 'type'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function switchTo(Organization $organization)
    {
        $user = auth()->user();
        
        if (!$user->belongsToOrganization($organization->id)) {
            session()->flash('error', 'Accès non autorisé');
            return;
        }

        session(['current_organization_id' => $organization->id]);
        $user->update(['default_organization_id' => $organization->id]);

        return redirect()->route('dashboard');
    }

    public function render()
    {
        $organizations = auth()->user()
            ->organizations()
            ->with(['stores', 'owner'])
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->paginate(10);

        return view('livewire.organization.organization-index', [
            'organizations' => $organizations,
        ]);
    }
}
```

---

### Étape 4.2 - Composant Création Organisation

```bash
php artisan make:livewire Organization/OrganizationCreate
```

**Fichier :** `app/Livewire/Organization/OrganizationCreate.php`

```php
<?php

namespace App\Livewire\Organization;

use App\Services\OrganizationService;
use Livewire\Component;
use Livewire\WithFileUploads;

class OrganizationCreate extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $type = 'company';
    public string $legal_name = '';
    public string $legal_form = '';
    public string $tax_id = '';
    public string $registration_number = '';
    public string $email = '';
    public string $phone = '';
    public string $address = '';
    public string $city = '';
    public string $country = 'CD';
    public $logo;
    public string $website = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:individual,company,franchise,cooperative,group',
            'legal_name' => 'nullable|string|max:255',
            'legal_form' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:100',
            'registration_number' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'required|string|size:2',
            'logo' => 'nullable|image|max:2048',
            'website' => 'nullable|url|max:255',
        ];
    }

    public function save(OrganizationService $service)
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'type' => $this->type,
            'legal_name' => $this->legal_name ?: null,
            'legal_form' => $this->legal_form ?: null,
            'tax_id' => $this->tax_id ?: null,
            'registration_number' => $this->registration_number ?: null,
            'email' => $this->email ?: null,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
            'city' => $this->city ?: null,
            'country' => $this->country,
            'website' => $this->website ?: null,
        ];

        if ($this->logo) {
            $data['logo'] = $this->logo->store('organizations/logos', 'public');
        }

        try {
            $organization = $service->create($data, auth()->user());
            
            session()->flash('success', 'Organisation créée avec succès!');
            return redirect()->route('organizations.show', $organization);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.organization.organization-create');
    }
}
```

---

### Étape 4.3 - Composant Gestion Membres

```bash
php artisan make:livewire Organization/OrganizationMembers
```

**Fichier :** `app/Livewire/Organization/OrganizationMembers.php`

```php
<?php

namespace App\Livewire\Organization;

use App\Models\Organization;
use App\Models\User;
use App\Services\OrganizationService;
use Livewire\Component;
use Livewire\WithPagination;

class OrganizationMembers extends Component
{
    use WithPagination;

    public Organization $organization;
    public string $search = '';
    public bool $showInviteModal = false;
    public string $inviteEmail = '';
    public string $inviteRole = 'member';

    protected $rules = [
        'inviteEmail' => 'required|email',
        'inviteRole' => 'required|in:admin,manager,accountant,member',
    ];

    public function mount(Organization $organization)
    {
        $this->organization = $organization;
    }

    public function openInviteModal()
    {
        $this->reset(['inviteEmail', 'inviteRole']);
        $this->inviteRole = 'member';
        $this->showInviteModal = true;
    }

    public function invite(OrganizationService $service)
    {
        $this->validate();

        try {
            $service->inviteMember(
                $this->organization,
                $this->inviteEmail,
                $this->inviteRole,
                auth()->user()
            );

            session()->flash('success', 'Invitation envoyée!');
            $this->showInviteModal = false;
            $this->reset(['inviteEmail', 'inviteRole']);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function removeMember(int $userId, OrganizationService $service)
    {
        try {
            $user = User::findOrFail($userId);
            $service->removeMember($this->organization, $user);
            session()->flash('success', 'Membre retiré avec succès.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function updateRole(int $userId, string $newRole, OrganizationService $service)
    {
        try {
            $user = User::findOrFail($userId);
            $service->updateMemberRole($this->organization, $user, $newRole);
            session()->flash('success', 'Rôle mis à jour.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        $members = $this->organization
            ->members()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->paginate(10);

        $pendingInvitations = $this->organization
            ->invitations()
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->get();

        return view('livewire.organization.organization-members', [
            'members' => $members,
            'pendingInvitations' => $pendingInvitations,
        ]);
    }
}
```

---

### Étape 4.4 - Composant Switcher (Header)

```bash
php artisan make:livewire Organization/OrganizationSwitcher
```

**Fichier :** `app/Livewire/Organization/OrganizationSwitcher.php`

```php
<?php

namespace App\Livewire\Organization;

use App\Models\Organization;
use Livewire\Component;

class OrganizationSwitcher extends Component
{
    public ?Organization $currentOrganization = null;

    public function mount()
    {
        $this->currentOrganization = app('current_organization');
    }

    public function switchOrganization(int $organizationId)
    {
        $user = auth()->user();
        
        if (!$user->belongsToOrganization($organizationId)) {
            session()->flash('error', 'Accès non autorisé');
            return;
        }

        session(['current_organization_id' => $organizationId]);
        $user->update(['default_organization_id' => $organizationId]);

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.organization.organization-switcher', [
            'organizations' => auth()->user()->organizations()->with('stores')->get(),
        ]);
    }
}
```

---

## 🎯 Phase 5 : Migration des Données Existantes (1 jour)

### Étape 5.1 - Créer une commande de migration

```bash
php artisan make:command MigrateExistingDataToOrganizations
```

**Fichier :** `app/Console/Commands/MigrateExistingDataToOrganizations.php`

```php
<?php

namespace App\Console\Commands;

use App\Models\Organization;
use App\Models\Store;
use App\Models\User;
use App\Services\OrganizationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateExistingDataToOrganizations extends Command
{
    protected $signature = 'organizations:migrate-existing 
                            {--dry-run : Simuler sans appliquer les changements}';

    protected $description = 'Migrate existing stores and users to organizations';

    public function handle(OrganizationService $service): int
    {
        $this->info('🚀 Début de la migration des données...');
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('⚠️ Mode simulation activé - Aucune modification ne sera appliquée');
        }

        // Récupérer les magasins sans organisation
        $orphanStores = Store::whereNull('organization_id')->get();

        if ($orphanStores->isEmpty()) {
            $this->info('✅ Aucun magasin orphelin trouvé.');
            return Command::SUCCESS;
        }

        $this->info("📊 {$orphanStores->count()} magasin(s) sans organisation détecté(s)");

        // Grouper les magasins par manager
        $storesByManager = $orphanStores->groupBy('manager_id');

        $this->newLine();
        $this->info('📋 Plan de migration :');
        
        $table = [];
        foreach ($storesByManager as $managerId => $stores) {
            $manager = User::find($managerId);
            $table[] = [
                'Manager' => $manager?->name ?? 'N/A',
                'Email' => $manager?->email ?? 'N/A',
                'Magasins' => $stores->count(),
                'Noms' => $stores->pluck('name')->implode(', '),
            ];
        }
        $this->table(['Manager', 'Email', 'Magasins', 'Noms'], $table);

        if ($dryRun) {
            $this->warn('⚠️ Fin de la simulation.');
            return Command::SUCCESS;
        }

        if (!$this->confirm('Voulez-vous continuer avec la migration?')) {
            $this->info('Migration annulée.');
            return Command::SUCCESS;
        }

        $this->newLine();
        $progressBar = $this->output->createProgressBar($storesByManager->count());
        $progressBar->start();

        DB::transaction(function () use ($storesByManager, $service, $progressBar) {
            foreach ($storesByManager as $managerId => $stores) {
                $manager = User::find($managerId);
                
                if (!$manager) {
                    // Créer une organisation "default" pour les magasins sans manager
                    $organization = Organization::firstOrCreate(
                        ['slug' => 'default'],
                        [
                            'name' => 'Organisation par défaut',
                            'type' => 'company',
                            'owner_id' => User::first()->id,
                            'subscription_plan' => 'free',
                        ]
                    );
                } else {
                    // Vérifier si le manager a déjà une organisation
                    $existingOrg = $manager->ownedOrganizations()->first();
                    
                    if ($existingOrg) {
                        $organization = $existingOrg;
                    } else {
                        // Créer une organisation pour ce manager
                        $orgName = $stores->first()->name ?? "Organisation de {$manager->name}";
                        
                        $organization = $service->create([
                            'name' => $orgName,
                            'type' => 'individual',
                            'subscription_plan' => 'starter',
                        ], $manager);
                    }
                }

                // Assigner les magasins à l'organisation
                foreach ($stores as $index => $store) {
                    $store->update([
                        'organization_id' => $organization->id,
                        'store_number' => $index + 1,
                    ]);
                }

                $progressBar->advance();
            }
        });

        $progressBar->finish();
        $this->newLine(2);

        $this->info('✅ Migration terminée avec succès!');
        
        // Statistiques finales
        $this->table(['Métrique', 'Valeur'], [
            ['Organisations créées', Organization::count()],
            ['Magasins migrés', Store::whereNotNull('organization_id')->count()],
            ['Utilisateurs assignés', DB::table('organization_user')->count()],
        ]);

        return Command::SUCCESS;
    }
}
```

---

### Étape 5.2 - Commandes à exécuter

```bash
# Simuler d'abord
php artisan organizations:migrate-existing --dry-run

# Puis exécuter réellement
php artisan organizations:migrate-existing
```

---

## 🎯 Phase 6 : Tests (2 jours)

### Étape 6.1 - Tests unitaires

```bash
php artisan make:test OrganizationServiceTest --unit
```

**Fichier :** `tests/Unit/OrganizationServiceTest.php`

```php
<?php

namespace Tests\Unit;

use App\Models\Organization;
use App\Models\User;
use App\Services\OrganizationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationServiceTest extends TestCase
{
    use RefreshDatabase;

    private OrganizationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(OrganizationService::class);
    }

    public function test_can_create_organization(): void
    {
        $owner = User::factory()->create();

        $organization = $this->service->create([
            'name' => 'Test Organization',
            'type' => 'company',
        ], $owner);

        $this->assertDatabaseHas('organizations', [
            'name' => 'Test Organization',
            'owner_id' => $owner->id,
        ]);

        $this->assertTrue($organization->hasMember($owner));
        $this->assertEquals('owner', $organization->getUserRole($owner));
    }

    public function test_owner_cannot_be_removed(): void
    {
        $owner = User::factory()->create();
        $organization = $this->service->create(['name' => 'Test'], $owner);

        $this->expectException(\Exception::class);
        $this->service->removeMember($organization, $owner);
    }

    public function test_can_add_and_remove_member(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        
        $organization = $this->service->create(['name' => 'Test'], $owner);
        
        $this->service->addMember($organization, $member, 'manager');
        $this->assertTrue($organization->hasMember($member));
        
        $this->service->removeMember($organization, $member);
        $this->assertFalse($organization->fresh()->hasMember($member));
    }

    public function test_respects_user_limits(): void
    {
        $owner = User::factory()->create();
        
        $organization = $this->service->create([
            'name' => 'Test',
            'subscription_plan' => 'free', // max 3 users
        ], $owner);

        // Add 2 more members (owner is 1)
        $this->service->addMember($organization, User::factory()->create());
        $this->service->addMember($organization, User::factory()->create());

        // 4th should fail
        $this->expectException(\Exception::class);
        $this->service->addMember($organization, User::factory()->create());
    }
}
```

---

## 📋 Checklist d'Implémentation

### Phase 1 : Base de Données ✅
- [ ] Migration `create_organizations_table`
- [ ] Migration `create_organization_user_table`
- [ ] Migration `add_organization_to_stores_table`
- [ ] Migration `add_default_organization_to_users_table`
- [ ] Migration `create_organization_invitations_table`
- [ ] Exécuter `php artisan migrate`

### Phase 2 : Models ✅
- [ ] Créer `Organization.php`
- [ ] Créer `OrganizationInvitation.php`
- [ ] Modifier `User.php` (ajouter relations)
- [ ] Modifier `Store.php` (ajouter relation)
- [ ] Créer trait `BelongsToOrganization.php`

### Phase 3 : Services ✅
- [ ] Créer `OrganizationRepository.php`
- [ ] Créer `OrganizationService.php`
- [ ] Créer `OrganizationPolicy.php`
- [ ] Créer middleware `EnsureOrganizationAccess.php`
- [ ] Enregistrer la Policy et le Middleware

### Phase 4 : Interface ✅
- [ ] Créer `OrganizationIndex` Livewire
- [ ] Créer `OrganizationCreate` Livewire
- [ ] Créer `OrganizationMembers` Livewire
- [ ] Créer `OrganizationSwitcher` Livewire
- [ ] Créer les vues Blade correspondantes
- [ ] Ajouter les routes

### Phase 5 : Migration Données ✅
- [ ] Créer commande `MigrateExistingDataToOrganizations`
- [ ] Tester avec `--dry-run`
- [ ] Exécuter la migration

### Phase 6 : Tests ✅
- [ ] Tests unitaires OrganizationService
- [ ] Tests de permissions
- [ ] Tests fonctionnels UI

---

## 🛠️ Commandes Utiles

```bash
# Créer toutes les migrations
php artisan make:migration create_organizations_table
php artisan make:migration create_organization_user_table
php artisan make:migration add_organization_to_stores_table
php artisan make:migration add_default_organization_to_users_table
php artisan make:migration create_organization_invitations_table

# Créer les models
php artisan make:model Organization
php artisan make:model OrganizationInvitation

# Créer les composants Livewire
php artisan make:livewire Organization/OrganizationIndex
php artisan make:livewire Organization/OrganizationCreate
php artisan make:livewire Organization/OrganizationMembers
php artisan make:livewire Organization/OrganizationSwitcher

# Créer la policy
php artisan make:policy OrganizationPolicy --model=Organization

# Créer la commande de migration
php artisan make:command MigrateExistingDataToOrganizations

# Exécuter les migrations
php artisan migrate

# Exécuter les tests
php artisan test --filter=Organization
```

---

## 📝 Routes à Ajouter

**Fichier :** `routes/web.php`

```php
use App\Livewire\Organization\OrganizationIndex;
use App\Livewire\Organization\OrganizationCreate;
use App\Livewire\Organization\OrganizationMembers;

Route::middleware(['auth', 'organization'])->group(function () {
    Route::get('/organizations', OrganizationIndex::class)->name('organizations.index');
    Route::get('/organizations/create', OrganizationCreate::class)->name('organizations.create');
    Route::get('/organizations/{organization}', OrganizationShow::class)->name('organizations.show');
    Route::get('/organizations/{organization}/members', OrganizationMembers::class)->name('organizations.members');
    Route::get('/organizations/{organization}/settings', OrganizationSettings::class)->name('organizations.settings');
});
```

---

**Document préparé pour : STK-Back Application**  
**Date : 8 Janvier 2026**
