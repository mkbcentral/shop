# Menu Gestion des Utilisateurs - Guide d'accès

## ✅ Menu ajouté avec succès !

### 📍 Où trouver le menu ?

Le menu **"Gestion des Utilisateurs"** a été ajouté dans la barre latérale (sidebar) sous une nouvelle section **"Administration"**.

### 📂 Structure du menu

```
Administration
├── Utilisateurs
│   └── Liste des utilisateurs
└── Rôles
    └── Liste des rôles (à venir)
```

### 🔗 Routes disponibles

1. **Liste des utilisateurs**
   - URL: `/users`
   - Route: `users.index`
   - Composant: `App\Livewire\User\Index`

2. **Liste des rôles** (placeholder)
   - URL: `/roles`
   - Route: `roles.index`
   - Redirige temporairement vers users.index

### 🎨 Fonctionnalités de la page Utilisateurs

#### Filtres disponibles
- **Recherche** : Par nom ou email
- **Filtre par rôle** : Super Admin, Admin, Manager, Cashier, Staff
- **Filtre par magasin** : Liste déroulante des magasins

#### Colonnes affichées
1. **Utilisateur** : Avatar, nom et email
2. **Rôles** : Badges colorés des rôles assignés
3. **Magasins** : Nombre de magasins affectés
4. **Dernière connexion** : Date relative (ex: "il y a 2 heures")
5. **Statut** : Actif / En attente (selon email_verified_at)
6. **Actions** : Bouton Supprimer (sauf pour Super Admin)

#### Tri
- Cliquez sur l'en-tête "Utilisateur" pour trier par nom (asc/desc)

#### Pagination
- 10 utilisateurs par page par défaut
- Navigation en bas de la liste

### 🛡️ Permissions requises

Pour l'instant, seuls les utilisateurs authentifiés et vérifiés peuvent accéder à cette page.

**À implémenter** : Vérification de la permission `users.view`

### 🎯 Prochaines étapes

#### Court terme
1. ✅ Menu ajouté
2. ✅ Page liste des utilisateurs
3. ⏳ Formulaire création utilisateur
4. ⏳ Formulaire édition utilisateur
5. ⏳ Page détails utilisateur

#### Moyen terme
1. Assignation de rôles depuis l'interface
2. Assignation de magasins depuis l'interface
3. Gestion des permissions
4. Page de gestion des rôles

#### Long terme
1. Middleware de permissions
2. Blade directives @role et @permission
3. Audit log des changements
4. Notifications par email

### 🚀 Accès rapide

Pour accéder à la page de gestion des utilisateurs :

1. **Via le menu** : 
   - Cliquez sur "Administration" dans la sidebar
   - Puis "Utilisateurs" → "Liste des utilisateurs"

2. **Via URL directe** :
   - Allez sur `http://127.0.0.1:8000/users`

### 💡 Utilisation

#### Rechercher un utilisateur
```
1. Tapez le nom ou l'email dans la barre de recherche
2. Les résultats se filtrent automatiquement
```

#### Filtrer par rôle
```
1. Sélectionnez un rôle dans la liste déroulante "Rôle"
2. La liste se met à jour automatiquement
```

#### Filtrer par magasin
```
1. Sélectionnez un magasin dans la liste déroulante "Magasin"
2. Seuls les utilisateurs affectés à ce magasin s'affichent
```

#### Supprimer un utilisateur
```
1. Cliquez sur l'icône de corbeille
2. Confirmez la suppression dans la modal
3. L'utilisateur est supprimé (sauf Super Admin)
```

### 🔧 Fichiers modifiés

- ✅ `resources/views/components/navigation.blade.php` - Menu ajouté
- ✅ `routes/web.php` - Routes users et roles
- ✅ `app/Livewire/User/Index.php` - Composant Livewire
- ✅ `resources/views/livewire/user/index.blade.php` - Vue

### ✅ Tests effectués

- ✅ Route créée : `php artisan route:list | grep users`
- ✅ Cache nettoyé : `php artisan route:clear && php artisan view:clear`
- ✅ Menu visible dans la sidebar
- ✅ Page accessible via `/users`

### 📝 Notes importantes

1. **Super Admin** : Ne peut pas être supprimé via l'interface
2. **Permissions** : Le système vérifie si l'utilisateur a le rôle 'super-admin'
3. **Pagination** : Utilise Livewire WithPagination trait
4. **Temps réel** : Les filtres utilisent `wire:model.live` pour mise à jour instantanée

### 🎨 Aperçu visuel

Le menu "Administration" apparaît après la section "Multi-Magasins" avec :
- 👥 Icône utilisateurs pour la section Utilisateurs
- 🛡️ Icône bouclier pour la section Rôles

Les badges de rôles utilisent les couleurs :
- **Indigo** : Pour les rôles (indigo-100/indigo-800)
- **Vert** : Pour le statut "Actif" (green-100/green-800)
- **Jaune** : Pour le statut "En attente" (yellow-100/yellow-800)

---

**Le menu est maintenant accessible et fonctionnel !** 🎉
