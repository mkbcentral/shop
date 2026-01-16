# 📋 RAPPORT DE PROPOSITION
## Architecture Multi-Entités (Organisation/Entreprise → Magasins)

**Date:** 8 Janvier 2026  
**Version:** 1.0  
**Statut:** Proposition

---

## 📑 Table des Matières

1. [Contexte et Besoin](#1--contexte-et-besoin)
2. [Analyse de l'Architecture Actuelle](#2--analyse-de-larchitecture-actuelle)
3. [Proposition d'Architecture](#3--proposition-darchitecture)
4. [Modèle de Données](#4--modèle-de-données)
5. [Cas d'Utilisation](#5--cas-dutilisation)
6. [Impact sur le Code Existant](#6--impact-sur-le-code-existant)
7. [Nouveaux Fichiers à Créer](#7--nouveaux-fichiers-à-créer)
8. [Interface Utilisateur](#8--interface-utilisateur)
9. [Plan d'Implémentation](#9--plan-dimplémentation)
10. [Estimation et Priorisation](#10--estimation-et-priorisation)

---

## 1. 🎯 Contexte et Besoin

### 1.1 Problématique Actuelle

Actuellement, l'application gère :
- ✅ **Utilisateurs** (`User`) qui créent et gèrent des magasins
- ✅ **Magasins** (`Store`) avec leurs stocks, ventes, achats
- ✅ **Multi-magasins** - Un utilisateur peut accéder à plusieurs magasins
- ❌ **MANQUE** : Pas de notion d'**entité propriétaire** des magasins

### 1.2 Besoin Identifié

> *"Les users créent des magasins, on a besoin de savoir à quelle entité ces magasins appartiennent"*

**Cas d'usage réels :**
- Une **entreprise** possède plusieurs magasins dans différentes villes
- Un **groupe** commercial gère plusieurs enseignes
- Un **franchiseur** supervise des magasins franchisés
- Une **coopérative** regroupe des commerces indépendants

### 1.3 Objectifs

| Objectif | Description |
|----------|-------------|
| **Traçabilité** | Savoir qui possède quel magasin |
| **Reporting consolidé** | Rapports au niveau entité (tous les magasins) |
| **Gestion centralisée** | Un admin d'entité gère tous ses magasins |
| **Isolation des données** | Chaque entité ne voit que ses propres magasins |
| **Facturation** | Facturer par entité, pas par magasin |
| **Multi-tenant** | Support pour SaaS multi-entreprises |

---

## 2. 📊 Analyse de l'Architecture Actuelle

### 2.1 Schéma Actuel

```
┌─────────────────────────────────────────────────────────────────┐
│                         ARCHITECTURE ACTUELLE                   │
└─────────────────────────────────────────────────────────────────┘

    ┌──────────┐         ┌──────────────┐
    │   User   │─────────│  store_user  │──────────┐
    │──────────│         │  (pivot)     │          │
    │ id       │         │──────────────│          ▼
    │ name     │         │ user_id      │     ┌──────────┐
    │ email    │         │ store_id     │     │  Store   │
    │ role     │         │ role         │     │──────────│
    │ current_ │         │ is_default   │     │ id       │
    │ store_id │─────────┴──────────────┘     │ name     │
    └──────────┘                              │ code     │
         │                                    │ manager_ │
         │                                    │   id     │
         │                                    │ is_main  │
         ▼                                    └──────────┘
    ┌──────────────┐                               │
    │  role_user   │                               │
    │  (pivot)     │                               ▼
    │──────────────│                    ┌─────────────────────┐
    │ user_id      │                    │ Products, Sales,    │
    │ role_id      │                    │ Stock, Purchases... │
    └──────────────┘                    └─────────────────────┘
```

### 2.2 Problèmes Identifiés

| Problème | Impact |
|----------|--------|
| Pas de propriétaire de magasin | Impossible de savoir qui "possède" un magasin |
| Pas de regroupement | Impossible de faire des rapports consolidés |
| Pas d'isolation | Tous les magasins sont "globaux" |
| Pas de hiérarchie | Pas de notion d'organisation |

### 2.3 Tables Existantes Concernées

```
stores              → Ajouter organization_id
users               → Ajouter organization_id (optionnel)
products            → Filtrer par organization via store
sales               → Filtrer par organization via store
purchases           → Filtrer par organization via store
```

---

## 3. 🏗️ Proposition d'Architecture

### 3.1 Concept : Entité "Organization"

Introduire une entité **Organization** (ou Entreprise/Company) qui :
- Regroupe plusieurs **Stores** (magasins)
- A des **Users** membres avec différents rôles
- Possède une **Subscription** (abonnement) pour le modèle SaaS
- Permet un **reporting consolidé**

### 3.2 Schéma Proposé

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         ARCHITECTURE PROPOSÉE                               │
└─────────────────────────────────────────────────────────────────────────────┘

                         ┌──────────────────┐
                         │   Organization   │
                         │──────────────────│
                         │ id               │
                         │ name             │
                         │ slug             │
                         │ type             │◄─── Entreprise, Franchise, 
                         │ legal_name       │     Coopérative, Individuel
                         │ tax_id           │
                         │ owner_id (FK)    │◄─── Créateur/Propriétaire
                         │ logo             │
                         │ settings (JSON)  │
                         │ subscription_    │
                         │   plan           │
                         │ subscription_    │
                         │   ends_at        │
                         │ is_active        │
                         └────────┬─────────┘
                                  │
              ┌───────────────────┼───────────────────┐
              │                   │                   │
              ▼                   ▼                   ▼
    ┌──────────────────┐  ┌──────────────┐  ┌─────────────────┐
    │ organization_user│  │    Store     │  │   Subscription  │
    │    (pivot)       │  │──────────────│  │   (optionnel)   │
    │──────────────────│  │ id           │  │─────────────────│
    │ organization_id  │  │ organization_│◄─│ organization_id │
    │ user_id          │  │   id (FK)    │  │ plan            │
    │ role             │  │ name         │  │ features        │
    │ is_owner         │  │ code         │  │ started_at      │
    │ invited_at       │  │ ...          │  │ ends_at         │
    │ accepted_at      │  └──────────────┘  │ status          │
    └──────────────────┘         │          └─────────────────┘
              │                  │
              │                  │
              ▼                  ▼
    ┌──────────────┐    ┌─────────────────────┐
    │     User     │    │ Products, Sales,    │
    │──────────────│    │ Stock, Purchases... │
    │ id           │    │    (via Store)      │
    │ name         │    └─────────────────────┘
    │ email        │
    │ default_     │
    │ organization_│
    │   id         │
    └──────────────┘
```

### 3.3 Hiérarchie des Entités

```
Organization (Entreprise/Groupe)
    │
    ├── Store 1 (Magasin Paris)
    │       ├── Products
    │       ├── Stock
    │       ├── Sales
    │       └── Users (staff du magasin)
    │
    ├── Store 2 (Magasin Lyon)
    │       ├── Products
    │       ├── Stock
    │       ├── Sales
    │       └── Users (staff du magasin)
    │
    └── Store 3 (Magasin Marseille)
            └── ...
```

---

## 4. 📐 Modèle de Données

### 4.1 Nouvelle Table `organizations`

```php
Schema::create('organizations', function (Blueprint $table) {
    $table->id();
    
    // Informations de base
    $table->string('name');                              // Nom commercial
    $table->string('slug')->unique();                    // URL-friendly
    $table->string('legal_name')->nullable();            // Raison sociale
    $table->enum('type', [
        'individual',    // Entrepreneur individuel
        'company',       // Entreprise/Société
        'franchise',     // Franchise
        'cooperative',   // Coopérative
        'group'          // Groupe commercial
    ])->default('company');
    
    // Informations légales
    $table->string('tax_id')->nullable();                // NIF/RCCM
    $table->string('registration_number')->nullable();   // Numéro d'immatriculation
    $table->string('legal_form')->nullable();            // SARL, SA, etc.
    
    // Contact
    $table->string('email')->nullable();
    $table->string('phone')->nullable();
    $table->text('address')->nullable();
    $table->string('city')->nullable();
    $table->string('country')->default('CD');            // Code pays
    
    // Branding
    $table->string('logo')->nullable();
    $table->string('website')->nullable();
    
    // Propriétaire (créateur)
    $table->foreignId('owner_id')->constrained('users');
    
    // Abonnement (pour SaaS)
    $table->enum('subscription_plan', [
        'free',          // Gratuit (limité)
        'starter',       // Démarrage
        'professional',  // Professionnel
        'enterprise'     // Entreprise
    ])->default('free');
    $table->timestamp('subscription_starts_at')->nullable();
    $table->timestamp('subscription_ends_at')->nullable();
    $table->boolean('is_trial')->default(true);
    
    // Limites selon abonnement
    $table->integer('max_stores')->default(1);           // Nombre max de magasins
    $table->integer('max_users')->default(3);            // Nombre max d'utilisateurs
    $table->integer('max_products')->default(100);       // Nombre max de produits
    
    // Configuration
    $table->json('settings')->nullable();                // Paramètres personnalisés
    $table->string('currency')->default('CDF');          // Devise par défaut
    $table->string('timezone')->default('Africa/Kinshasa');
    
    // Statut
    $table->boolean('is_active')->default(true);
    $table->boolean('is_verified')->default(false);      // Vérifié par admin
    $table->timestamp('verified_at')->nullable();
    
    $table->timestamps();
    $table->softDeletes();
    
    // Index
    $table->index('owner_id');
    $table->index('subscription_plan');
    $table->index('is_active');
    $table->index(['type', 'is_active']);
});
```

### 4.2 Table Pivot `organization_user`

```php
Schema::create('organization_user', function (Blueprint $table) {
    $table->id();
    $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    
    // Rôle dans l'organisation
    $table->enum('role', [
        'owner',         // Propriétaire (tous les droits)
        'admin',         // Administrateur
        'manager',       // Manager (gère les magasins)
        'accountant',    // Comptable (accès rapports)
        'member'         // Membre simple
    ])->default('member');
    
    // Invitation
    $table->timestamp('invited_at')->nullable();
    $table->timestamp('accepted_at')->nullable();
    $table->foreignId('invited_by')->nullable()->constrained('users');
    
    // Statut
    $table->boolean('is_active')->default(true);
    
    $table->timestamps();
    
    $table->unique(['organization_id', 'user_id']);
    $table->index('role');
});
```

### 4.3 Modification Table `stores`

```php
// Migration: add_organization_to_stores_table.php
Schema::table('stores', function (Blueprint $table) {
    $table->foreignId('organization_id')
          ->nullable()
          ->after('id')
          ->constrained()
          ->cascadeOnDelete();
    
    // Numéro de magasin dans l'organisation
    $table->integer('store_number')->nullable()->after('code');
    
    $table->index('organization_id');
});
```

### 4.4 Modification Table `users`

```php
// Migration: add_default_organization_to_users_table.php
Schema::table('users', function (Blueprint $table) {
    // Organisation par défaut (pour login)
    $table->foreignId('default_organization_id')
          ->nullable()
          ->after('current_store_id')
          ->constrained('organizations')
          ->nullOnDelete();
    
    $table->index('default_organization_id');
});
```

### 4.5 Table `organization_invitations` (Optionnelle)

```php
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
```

---

## 5. 📋 Cas d'Utilisation

### 5.1 Création d'Organisation

```
┌─────────────────────────────────────────────────────────────────┐
│  FLUX : Nouvel Utilisateur → Organisation → Magasin            │
└─────────────────────────────────────────────────────────────────┘

1. Utilisateur s'inscrit
   │
   ▼
2. Création automatique d'une Organisation
   (type: individual, plan: free/trial)
   │
   ▼
3. Utilisateur devient "owner" de l'organisation
   │
   ▼
4. Création du premier magasin (rattaché à l'organisation)
   │
   ▼
5. Utilisateur peut inviter d'autres membres
```

### 5.2 Scénarios Utilisateurs

#### A. Entrepreneur Individuel
```
Organisation: "Boutique Marie"
Type: individual
Plan: starter
│
└── Store: "Boutique Marie - Centre"
        └── 1 utilisateur (propriétaire)
```

#### B. Entreprise Multi-Magasins
```
Organisation: "Fashion Group SARL"
Type: company
Plan: professional
│
├── Store: "Fashion Gombe"
│       └── 5 utilisateurs
│
├── Store: "Fashion Limete"
│       └── 3 utilisateurs
│
└── Store: "Fashion Ngaliema"
        └── 4 utilisateurs

Total: 12 utilisateurs, 3 magasins
```

#### C. Franchise
```
Organisation: "QuickMart Franchise"
Type: franchise
Plan: enterprise
│
├── Store: "QuickMart #001 - Kinshasa"
├── Store: "QuickMart #002 - Lubumbashi"
├── Store: "QuickMart #003 - Goma"
└── Store: "QuickMart #004 - Matadi"

Chaque magasin peut avoir des "sous-franchisés"
```

### 5.3 Matrice des Permissions

| Permission | Owner | Admin | Manager | Accountant | Member |
|------------|:-----:|:-----:|:-------:|:----------:|:------:|
| Voir organisation | ✅ | ✅ | ✅ | ✅ | ✅ |
| Modifier organisation | ✅ | ✅ | ❌ | ❌ | ❌ |
| Supprimer organisation | ✅ | ❌ | ❌ | ❌ | ❌ |
| Créer magasin | ✅ | ✅ | ❌ | ❌ | ❌ |
| Supprimer magasin | ✅ | ✅ | ❌ | ❌ | ❌ |
| Inviter membres | ✅ | ✅ | ✅ | ❌ | ❌ |
| Supprimer membres | ✅ | ✅ | ❌ | ❌ | ❌ |
| Voir rapports globaux | ✅ | ✅ | ✅ | ✅ | ❌ |
| Gérer abonnement | ✅ | ❌ | ❌ | ❌ | ❌ |
| Accéder aux magasins | ✅ | ✅ | ✅* | ✅* | ✅* |

*\* Selon assignation au magasin*

---

## 6. 🔄 Impact sur le Code Existant

### 6.1 Models à Modifier

| Model | Modifications |
|-------|---------------|
| `User` | Ajouter relation `organizations()`, `defaultOrganization()`, `ownedOrganizations()` |
| `Store` | Ajouter relation `organization()`, scope `forOrganization()` |
| `Product` | Hérite du filtrage via Store→Organization |
| `Sale` | Hérite du filtrage via Store→Organization |
| `Purchase` | Hérite du filtrage via Store→Organization |

### 6.2 Services à Modifier

```php
// StoreService.php - Ajouter vérification organization
public function createStore(array $data): Store
{
    // Vérifier que l'utilisateur peut créer dans cette organisation
    $this->verifyOrganizationAccess($data['organization_id']);
    
    // Vérifier les limites de l'abonnement
    $this->checkOrganizationLimits($data['organization_id'], 'stores');
    
    // Créer le magasin...
}
```

### 6.3 Middlewares à Créer/Modifier

```php
// EnsureUserBelongsToOrganization.php
public function handle($request, Closure $next)
{
    $organizationId = $request->route('organization') 
                      ?? $request->user()->default_organization_id;
    
    if (!$request->user()->belongsToOrganization($organizationId)) {
        abort(403, 'Accès non autorisé à cette organisation');
    }
    
    // Mettre l'organisation dans le contexte
    app()->instance('current_organization', Organization::find($organizationId));
    
    return $next($request);
}
```

### 6.4 Trait pour Filtrage Automatique

```php
// app/Traits/BelongsToOrganization.php
trait BelongsToOrganization
{
    protected static function bootBelongsToOrganization()
    {
        static::addGlobalScope('organization', function ($query) {
            if ($organization = app('current_organization')) {
                $query->where('organization_id', $organization->id);
            }
        });
        
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
}
```

---

## 7. 📁 Nouveaux Fichiers à Créer

### 7.1 Structure des Fichiers

```
app/
├── Models/
│   ├── Organization.php                    # NOUVEAU
│   └── OrganizationInvitation.php          # NOUVEAU (optionnel)
│
├── Services/
│   ├── OrganizationService.php             # NOUVEAU
│   └── SubscriptionService.php             # NOUVEAU (optionnel)
│
├── Repositories/
│   └── OrganizationRepository.php          # NOUVEAU
│
├── Actions/
│   └── Organization/
│       ├── CreateOrganizationAction.php    # NOUVEAU
│       ├── UpdateOrganizationAction.php    # NOUVEAU
│       ├── DeleteOrganizationAction.php    # NOUVEAU
│       ├── InviteMemberAction.php          # NOUVEAU
│       ├── RemoveMemberAction.php          # NOUVEAU
│       ├── AcceptInvitationAction.php      # NOUVEAU
│       └── SwitchOrganizationAction.php    # NOUVEAU
│
├── Http/
│   ├── Controllers/
│   │   └── OrganizationController.php      # NOUVEAU
│   │
│   └── Middleware/
│       └── EnsureOrganizationAccess.php    # NOUVEAU
│
├── Traits/
│   └── BelongsToOrganization.php           # NOUVEAU
│
├── Policies/
│   └── OrganizationPolicy.php              # NOUVEAU
│
├── Events/
│   └── Organization/
│       ├── OrganizationCreated.php         # NOUVEAU
│       ├── MemberInvited.php               # NOUVEAU
│       └── MemberRemoved.php               # NOUVEAU
│
├── Notifications/
│   └── OrganizationInvitation.php          # NOUVEAU
│
└── Livewire/
    └── Organization/
        ├── OrganizationIndex.php           # NOUVEAU
        ├── OrganizationCreate.php          # NOUVEAU
        ├── OrganizationEdit.php            # NOUVEAU
        ├── OrganizationSettings.php        # NOUVEAU
        ├── OrganizationMembers.php         # NOUVEAU
        └── OrganizationSwitcher.php        # NOUVEAU

database/
└── migrations/
    ├── 2026_01_08_000001_create_organizations_table.php
    ├── 2026_01_08_000002_create_organization_user_table.php
    ├── 2026_01_08_000003_add_organization_to_stores_table.php
    ├── 2026_01_08_000004_add_default_organization_to_users_table.php
    └── 2026_01_08_000005_create_organization_invitations_table.php

resources/views/livewire/organization/
    ├── index.blade.php
    ├── create.blade.php
    ├── edit.blade.php
    ├── settings.blade.php
    ├── members.blade.php
    └── switcher.blade.php
```

### 7.2 Model Organization

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
            ->withPivot('role', 'invited_at', 'accepted_at', 'is_active')
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
     * Get remaining days of subscription
     */
    public function getRemainingDaysAttribute(): ?int
    {
        if (!$this->subscription_ends_at) {
            return null;
        }

        return max(0, now()->diffInDays($this->subscription_ends_at, false));
    }

    /**
     * Check if user is owner
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
}
```

### 7.3 OrganizationService

```php
<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\User;
use App\Repositories\OrganizationRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrganizationService
{
    public function __construct(
        private OrganizationRepository $organizationRepository
    ) {}

    /**
     * Create a new organization
     */
    public function createOrganization(array $data, User $owner): Organization
    {
        return DB::transaction(function () use ($data, $owner) {
            // Generate slug if not provided
            $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
            $data['owner_id'] = $owner->id;
            
            // Set default limits based on plan
            $data = $this->applyPlanLimits($data);
            
            // Create organization
            $organization = $this->organizationRepository->create($data);
            
            // Add owner as member with 'owner' role
            $organization->members()->attach($owner->id, [
                'role' => 'owner',
                'accepted_at' => now(),
                'is_active' => true,
            ]);
            
            // Set as user's default organization if they don't have one
            if (!$owner->default_organization_id) {
                $owner->update(['default_organization_id' => $organization->id]);
            }
            
            return $organization;
        });
    }

    /**
     * Apply plan limits to organization data
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

        return array_merge($data, $limits);
    }

    /**
     * Invite a user to organization
     */
    public function inviteMember(Organization $organization, string $email, string $role, User $invitedBy): void
    {
        // Check limits
        if (!$organization->canAddUser()) {
            throw new \Exception("Limite d'utilisateurs atteinte pour cette organisation.");
        }

        // Check if user already exists
        $user = User::where('email', $email)->first();

        if ($user && $organization->members()->where('user_id', $user->id)->exists()) {
            throw new \Exception("Cet utilisateur est déjà membre de l'organisation.");
        }

        // Create invitation
        $invitation = $organization->invitations()->create([
            'email' => $email,
            'role' => $role,
            'token' => Str::random(64),
            'invited_by' => $invitedBy->id,
            'expires_at' => now()->addDays(7),
        ]);

        // Send notification
        // Notification::route('mail', $email)->notify(new OrganizationInvitation($invitation));
    }

    /**
     * Add existing user to organization
     */
    public function addMember(Organization $organization, User $user, string $role = 'member'): void
    {
        if (!$organization->canAddUser()) {
            throw new \Exception("Limite d'utilisateurs atteinte.");
        }

        $organization->members()->attach($user->id, [
            'role' => $role,
            'accepted_at' => now(),
            'is_active' => true,
        ]);
    }

    /**
     * Remove member from organization
     */
    public function removeMember(Organization $organization, User $user): void
    {
        if ($organization->isOwner($user)) {
            throw new \Exception("Impossible de retirer le propriétaire de l'organisation.");
        }

        $organization->members()->detach($user->id);

        // If this was user's default organization, clear it
        if ($user->default_organization_id === $organization->id) {
            $newDefault = $user->organizations()->first();
            $user->update(['default_organization_id' => $newDefault?->id]);
        }
    }

    /**
     * Update member role
     */
    public function updateMemberRole(Organization $organization, User $user, string $newRole): void
    {
        if ($organization->isOwner($user) && $newRole !== 'owner') {
            throw new \Exception("Impossible de modifier le rôle du propriétaire.");
        }

        $organization->members()->updateExistingPivot($user->id, ['role' => $newRole]);
    }

    /**
     * Transfer ownership
     */
    public function transferOwnership(Organization $organization, User $newOwner): void
    {
        DB::transaction(function () use ($organization, $newOwner) {
            $currentOwner = $organization->owner;

            // Update organization owner
            $organization->update(['owner_id' => $newOwner->id]);

            // Update roles in pivot
            $organization->members()->updateExistingPivot($currentOwner->id, ['role' => 'admin']);
            $organization->members()->updateExistingPivot($newOwner->id, ['role' => 'owner']);
        });
    }

    /**
     * Get organization statistics
     */
    public function getStatistics(Organization $organization): array
    {
        return [
            'stores_count' => $organization->stores()->count(),
            'active_stores' => $organization->activeStores()->count(),
            'members_count' => $organization->members()->count(),
            'products_count' => $organization->stores()
                ->withCount('products')
                ->get()
                ->sum('products_count'),
            'total_sales' => $organization->stores()
                ->with('sales')
                ->get()
                ->flatMap->sales
                ->sum('total_amount'),
            'limits' => [
                'max_stores' => $organization->max_stores,
                'max_users' => $organization->max_users,
                'max_products' => $organization->max_products,
            ],
            'subscription' => [
                'plan' => $organization->subscription_plan,
                'is_trial' => $organization->is_trial,
                'ends_at' => $organization->subscription_ends_at,
                'remaining_days' => $organization->remaining_days,
            ],
        ];
    }
}
```

---

## 8. 🖥️ Interface Utilisateur

### 8.1 Sélecteur d'Organisation (Header)

```
┌─────────────────────────────────────────────────────────────────┐
│  ┌─────────────────────────────┐                                │
│  │ 🏢 Fashion Group SARL    ▼ │  ← Dropdown pour changer      │
│  │    └─ 🏪 Magasin Gombe     │     d'organisation/magasin     │
│  └─────────────────────────────┘                                │
│                                                                 │
│  Au clic sur le dropdown:                                       │
│  ┌─────────────────────────────┐                                │
│  │ 📌 Organisations           │                                │
│  │ ─────────────────────────  │                                │
│  │ ● Fashion Group SARL       │  ← Organisation actuelle      │
│  │   ├─ 🏪 Magasin Gombe     │                                │
│  │   ├─ 🏪 Magasin Limete     │                                │
│  │   └─ 🏪 Magasin Ngaliema   │                                │
│  │                            │                                │
│  │ ○ Ma Boutique Perso        │  ← Autre organisation         │
│  │   └─ 🏪 Boutique Centre    │                                │
│  │                            │                                │
│  │ ─────────────────────────  │                                │
│  │ ➕ Créer une organisation  │                                │
│  └─────────────────────────────┘                                │
└─────────────────────────────────────────────────────────────────┘
```

### 8.2 Page Liste des Organisations

```
┌─────────────────────────────────────────────────────────────────┐
│  Mes Organisations                          [+ Nouvelle Org.]   │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌───────────────────────────────────────────────────────────┐ │
│  │ 🏢 Fashion Group SARL                              [OWNER] │ │
│  │    Type: Entreprise | Plan: Professional                  │ │
│  │    3 magasins | 12 membres                                │ │
│  │    Créé le 15/12/2025                                     │ │
│  │                                                           │ │
│  │    [📊 Dashboard] [⚙️ Paramètres] [👥 Membres]            │ │
│  └───────────────────────────────────────────────────────────┘ │
│                                                                 │
│  ┌───────────────────────────────────────────────────────────┐ │
│  │ 🏢 Ma Boutique Perso                              [ADMIN] │ │
│  │    Type: Individuel | Plan: Starter                       │ │
│  │    1 magasin | 3 membres                                  │ │
│  │    Créé le 01/01/2026                                     │ │
│  │                                                           │ │
│  │    [📊 Dashboard] [⚙️ Paramètres]                         │ │
│  └───────────────────────────────────────────────────────────┘ │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 8.3 Création d'Organisation

```
┌─────────────────────────────────────────────────────────────────┐
│  Créer une Organisation                                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ═══ Informations Générales ═══                                │
│                                                                 │
│  Nom de l'organisation *                                       │
│  [_______________________________________]                      │
│                                                                 │
│  Type d'organisation *                                         │
│  ○ Entrepreneur individuel                                     │
│  ○ Entreprise / Société                                        │
│  ○ Franchise                                                   │
│  ○ Coopérative                                                 │
│  ○ Groupe commercial                                           │
│                                                                 │
│  ═══ Informations Légales (optionnel) ═══                      │
│                                                                 │
│  Raison sociale          Forme juridique                       │
│  [___________________]   [SARL ▼]                              │
│                                                                 │
│  NIF / RCCM              N° Immatriculation                    │
│  [___________________]   [___________________]                  │
│                                                                 │
│  ═══ Contact ═══                                               │
│                                                                 │
│  Email                   Téléphone                             │
│  [___________________]   [___________________]                  │
│                                                                 │
│  Adresse                                                       │
│  [_______________________________________]                      │
│                                                                 │
│  Ville                   Pays                                  │
│  [___________________]   [RD Congo ▼]                          │
│                                                                 │
│  ═══ Branding (optionnel) ═══                                  │
│                                                                 │
│  Logo                    Site web                              │
│  [📷 Uploader]           [___________________]                  │
│                                                                 │
│                                                                 │
│                          [Annuler]  [💾 Créer l'organisation]  │
└─────────────────────────────────────────────────────────────────┘
```

### 8.4 Gestion des Membres

```
┌─────────────────────────────────────────────────────────────────┐
│  👥 Membres - Fashion Group SARL               [+ Inviter]     │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  🔍 [Rechercher un membre...]                                  │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ 👤 Jean Dupont                                    OWNER │   │
│  │    jean@fashion-group.com                               │   │
│  │    Membre depuis: 15/12/2025                            │   │
│  │    Magasins: Tous (3)                                   │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ 👤 Marie Martin                                   ADMIN │   │
│  │    marie@fashion-group.com                              │   │
│  │    Membre depuis: 20/12/2025                            │   │
│  │    Magasins: Gombe, Limete                              │   │
│  │                                                         │   │
│  │    [Modifier rôle ▼]  [Gérer accès magasins]  [🗑️]      │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ 👤 Paul Kabila                                 MANAGER │   │
│  │    paul@fashion-group.com                               │   │
│  │    Membre depuis: 05/01/2026                            │   │
│  │    Magasins: Ngaliema                                   │   │
│  │                                                         │   │
│  │    [Modifier rôle ▼]  [Gérer accès magasins]  [🗑️]      │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                 │
│  ─────────────────────────────────────────────────────────     │
│  📩 Invitations en attente (2)                                 │
│                                                                 │
│  │ alice@example.com - Manager - Expire dans 5 jours [Annuler]│ │
│  │ bob@example.com - Member - Expire dans 3 jours [Annuler]   │ │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 8.5 Dashboard Organisation (Consolidé)

```
┌─────────────────────────────────────────────────────────────────┐
│  📊 Dashboard - Fashion Group SARL                              │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐ ┌─────────┐│
│  │   3          │ │   12         │ │  1,234       │ │ $45,678 ││
│  │   Magasins   │ │   Membres    │ │  Produits    │ │ Ventes  ││
│  │   actifs     │ │   actifs     │ │  total       │ │ ce mois ││
│  └──────────────┘ └──────────────┘ └──────────────┘ └─────────┘│
│                                                                 │
│  ═══ Ventes par Magasin (ce mois) ═══                          │
│                                                                 │
│  Gombe      ████████████████████████████  $25,000 (55%)        │
│  Limete     ██████████████                $12,000 (26%)        │
│  Ngaliema   █████████                      $8,678 (19%)        │
│                                                                 │
│  ═══ Performance des Magasins ═══                              │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ Magasin        │ Ventes    │ Produits │ Stock   │ Trend │   │
│  │────────────────┼───────────┼──────────┼─────────┼───────│   │
│  │ 🏪 Gombe       │ $25,000   │ 456      │ 1,234   │  ↑12% │   │
│  │ 🏪 Limete      │ $12,000   │ 389      │ 987     │  ↑5%  │   │
│  │ 🏪 Ngaliema    │ $8,678    │ 289      │ 654     │  ↓2%  │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                 │
│  ═══ Abonnement ═══                                            │
│                                                                 │
│  Plan: Professional                                            │
│  Expire le: 15/02/2026 (38 jours restants)                     │
│  Utilisation: 3/10 magasins | 12/50 utilisateurs               │
│                                                                 │
│  [📈 Voir rapports détaillés]  [⬆️ Upgrader le plan]           │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

## 9. 📈 Plan d'Implémentation

### Phase 1 : Base de Données (2 jours)

| Étape | Fichier | Description |
|-------|---------|-------------|
| 1.1 | `create_organizations_table.php` | Table principale organisations |
| 1.2 | `create_organization_user_table.php` | Pivot membres |
| 1.3 | `add_organization_to_stores_table.php` | FK sur stores |
| 1.4 | `add_default_organization_to_users.php` | FK sur users |
| 1.5 | `create_organization_invitations.php` | Invitations (optionnel) |

### Phase 2 : Models et Relations (1-2 jours)

| Étape | Fichier | Description |
|-------|---------|-------------|
| 2.1 | `Organization.php` | Model avec relations |
| 2.2 | `OrganizationInvitation.php` | Model invitations |
| 2.3 | `User.php` (modifier) | Ajouter relations organizations |
| 2.4 | `Store.php` (modifier) | Ajouter relation organization |
| 2.5 | `BelongsToOrganization.php` | Trait pour scoping |

### Phase 3 : Services et Actions (2-3 jours)

| Étape | Fichier | Description |
|-------|---------|-------------|
| 3.1 | `OrganizationRepository.php` | Requêtes BD |
| 3.2 | `OrganizationService.php` | Logique métier |
| 3.3 | `CreateOrganizationAction.php` | Création avec owner |
| 3.4 | `InviteMemberAction.php` | Gestion invitations |
| 3.5 | `OrganizationPolicy.php` | Autorisations |

### Phase 4 : Interface (3-4 jours)

| Étape | Fichier | Description |
|-------|---------|-------------|
| 4.1 | `OrganizationIndex.php` | Liste organisations |
| 4.2 | `OrganizationCreate.php` | Formulaire création |
| 4.3 | `OrganizationSettings.php` | Paramètres |
| 4.4 | `OrganizationMembers.php` | Gestion membres |
| 4.5 | `OrganizationSwitcher.php` | Composant header |
| 4.6 | Vues Blade | Templates UI |

### Phase 5 : Migration des Données (1 jour)

| Étape | Action |
|-------|--------|
| 5.1 | Créer organisation "Default" pour magasins existants |
| 5.2 | Assigner tous les magasins à cette organisation |
| 5.3 | Assigner les utilisateurs existants à l'organisation |
| 5.4 | Définir l'organisation par défaut pour chaque user |

### Phase 6 : Tests et Finitions (2 jours)

| Étape | Action |
|-------|--------|
| 6.1 | Tests unitaires OrganizationService |
| 6.2 | Tests fonctionnels création/invitation |
| 6.3 | Tests de permissions |
| 6.4 | Documentation mise à jour |

---

## 10. 📊 Estimation et Priorisation

### Résumé des Efforts

| Phase | Description | Durée | Priorité |
|-------|-------------|-------|----------|
| 1 | Base de données | 2 jours | 🔴 Haute |
| 2 | Models et relations | 1-2 jours | 🔴 Haute |
| 3 | Services et actions | 2-3 jours | 🔴 Haute |
| 4 | Interface utilisateur | 3-4 jours | 🟡 Moyenne |
| 5 | Migration données | 1 jour | 🔴 Haute |
| 6 | Tests et finitions | 2 jours | 🟡 Moyenne |
| **TOTAL** | | **11-14 jours** | |

### Fonctionnalités par Priorité

#### 🔴 MVP (Minimum Viable Product) - 7-8 jours
- [x] Tables organizations + organization_user
- [x] Model Organization avec relations
- [x] OrganizationService (CRUD basique)
- [x] Assignation stores à organization
- [x] Switcher d'organisation basique
- [x] Migration données existantes

#### 🟡 Phase 2 - 3-4 jours
- [ ] Gestion des membres (invite/remove)
- [ ] Interface complète de paramètres
- [ ] Dashboard consolidé organisation
- [ ] Notifications email invitations

#### 🟢 Phase 3 (Optionnel) - 2-3 jours
- [ ] Système d'abonnement complet
- [ ] Limites par plan
- [ ] Facturation par organisation
- [ ] API pour intégrations

### Dépendances

```
Phase 1 (BD) 
    │
    ▼
Phase 2 (Models) ──► Phase 3 (Services)
    │                     │
    ▼                     ▼
Phase 5 (Migration) ◄─── Phase 4 (UI)
                              │
                              ▼
                         Phase 6 (Tests)
```

---

## 11. 🎯 Avantages de cette Architecture

| Avantage | Description |
|----------|-------------|
| **📊 Reporting consolidé** | Voir les stats de tous les magasins d'un coup |
| **🔐 Isolation des données** | Chaque organisation ne voit que ses données |
| **👥 Gestion centralisée** | Un admin gère tous les magasins de son organisation |
| **💰 Modèle SaaS ready** | Facturation par organisation possible |
| **🔄 Scalabilité** | Supporte des centaines d'organisations |
| **🎭 Multi-rôles** | Rôles différents par organisation |
| **📧 Invitations** | Ajouter facilement des collaborateurs |
| **⬆️ Évolutif** | Facile d'ajouter des fonctionnalités (plans, limites...) |

---

## 12. ⚠️ Points d'Attention

| Point | Risque | Mitigation |
|-------|--------|------------|
| **Migration données** | Données orphelines | Script de migration robuste |
| **Performance** | Jointures supplémentaires | Index appropriés |
| **UX** | Complexité ajoutée | Interface intuitive |
| **Permissions** | Failles de sécurité | Policies Laravel strictes |
| **Multi-tenant** | Isolation des données | Global scopes Eloquent |

---

## 13. 📎 Conclusion

Cette architecture ajoute une couche **Organization** au-dessus des **Stores** existants, permettant :

1. ✅ De savoir **qui possède** chaque magasin
2. ✅ D'avoir un **reporting consolidé** par entreprise
3. ✅ De gérer les **membres et permissions** au niveau organisation
4. ✅ De préparer l'application pour un modèle **SaaS multi-tenant**
5. ✅ De **facturer par organisation** plutôt que par magasin

Le tout en **conservant** l'architecture existante et en étant **rétrocompatible** avec les données actuelles.

---

**Document préparé pour : STK-Back Application**  
**Auteur : GitHub Copilot**  
**Date : 8 Janvier 2026**
