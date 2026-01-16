# Guide de Test - Module Organisations

## 📋 Préparation

### 1. Lancer les migrations
```bash
php artisan migrate
```

### 2. Vider le cache
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 🧪 Scénarios de Test

### Test 1: Navigation et Accès
1. ✅ Connectez-vous à l'application
2. ✅ Vérifiez que le menu "Organisations" apparaît dans le sidebar (section Multi-Magasins)
3. ✅ Vérifiez que le sélecteur d'organisation apparaît dans le header (à gauche du sélecteur de magasin)
4. ✅ Cliquez sur "Mes organisations" dans le sidebar
5. ✅ Vérifiez que vous arrivez sur `/organizations`

### Test 2: Création d'Organisation
1. ✅ Cliquez sur "Nouvelle Organisation" dans le sidebar OU sur le bouton dans la page index
2. ✅ Remplissez le formulaire:
   - Nom: "Mon Entreprise SARL"
   - Type: Entreprise
   - Forme juridique: SARL
   - Email: test@entreprise.com
   - Devise: USD
   - Uploadez un logo (optionnel)
3. ✅ Cliquez sur "Créer l'organisation"
4. ✅ Vérifiez la redirection vers la liste avec message de succès
5. ✅ Vérifiez que vous êtes marqué comme "Propriétaire"

### Test 3: Visualisation d'Organisation
1. ✅ Depuis la liste, cliquez sur "Voir" pour une organisation
2. ✅ Vérifiez que vous voyez:
   - Logo de l'organisation
   - Statistiques (Magasins, Membres, etc.)
   - Informations détaillées
   - Liste des magasins
   - Carte du propriétaire
   - Carte d'abonnement
3. ✅ Vérifiez les actions rapides dans le sidebar

### Test 4: Modification d'Organisation
1. ✅ Depuis la vue détaillée, cliquez sur "Modifier"
2. ✅ Modifiez quelques informations
3. ✅ Changez le logo
4. ✅ Cliquez sur "Enregistrer les modifications"
5. ✅ Vérifiez que les changements sont sauvegardés

### Test 5: Gestion des Membres
1. ✅ Depuis la vue détaillée, cliquez sur "Gérer les membres"
2. ✅ Cliquez sur "Inviter un membre"
3. ✅ Entrez un email et sélectionnez un rôle (Admin, Manager, Comptable, Membre)
4. ✅ Envoyez l'invitation
5. ✅ Vérifiez que l'invitation apparaît dans "Invitations en attente"
6. ✅ Testez le bouton "Renvoyer" et "Annuler"

### Test 6: Changement de Rôle
1. ✅ Ajoutez manuellement un membre à l'organisation (via la base de données pour ce test)
2. ✅ Dans la gestion des membres, changez son rôle via le dropdown
3. ✅ Vérifiez que le rôle est mis à jour
4. ✅ Vérifiez que vous ne pouvez pas changer le rôle du propriétaire

### Test 7: Retrait de Membre
1. ✅ Cliquez sur "Retirer" à côté d'un membre
2. ✅ Confirmez dans le modal
3. ✅ Vérifiez que le membre est retiré de la liste

### Test 8: Basculement d'Organisation
1. ✅ Créez une deuxième organisation
2. ✅ Cliquez sur le sélecteur d'organisation dans le header
3. ✅ Vérifiez que les deux organisations apparaissent
4. ✅ Cliquez sur la deuxième organisation
5. ✅ Vérifiez que l'organisation active change (badge "Active" et icône ✓)
6. ✅ Vérifiez que le nom dans le header est mis à jour

### Test 9: Recherche et Filtres
1. ✅ Dans la liste des organisations, utilisez le champ de recherche
2. ✅ Vérifiez que la recherche fonctionne en temps réel
3. ✅ Filtrez par type d'organisation
4. ✅ Vérifiez que les filtres s'appliquent correctement

### Test 10: Permissions et Autorisation
1. ✅ Connectez-vous avec un utilisateur "Member" (créez-le si nécessaire)
2. ✅ Vérifiez qu'il peut voir l'organisation
3. ✅ Vérifiez qu'il ne peut PAS modifier l'organisation
4. ✅ Vérifiez qu'il ne peut PAS gérer les membres
5. ✅ Vérifiez qu'il ne peut PAS supprimer l'organisation

### Test 11: Association Magasin-Organisation
1. ✅ Créez un nouveau magasin
2. ✅ Vérifiez que le magasin est automatiquement associé à l'organisation active
3. ✅ Dans la vue de l'organisation, vérifiez que le magasin apparaît dans la liste
4. ✅ Vérifiez que le compteur de magasins est mis à jour

### Test 12: Limites d'Abonnement
1. ✅ Créez une organisation avec plan "free" (par défaut)
2. ✅ Essayez de créer plus de 3 magasins (limite free)
3. ✅ Vérifiez que vous recevez un message d'erreur
4. ✅ Vérifiez que la carte d'abonnement affiche correctement l'utilisation (X/3 magasins)

## 🔍 Points à Vérifier

### Interface Utilisateur
- [ ] Les icônes s'affichent correctement
- [ ] Les couleurs sont cohérentes avec le reste de l'application
- [ ] Les badges (rôles, plans) ont les bonnes couleurs
- [ ] Les modals s'ouvrent et se ferment correctement
- [ ] Les formulaires sont réactifs et bien alignés
- [ ] Le logo uploadé s'affiche correctement

### Fonctionnalités
- [ ] Toutes les routes fonctionnent
- [ ] Les redirections sont correctes
- [ ] Les messages de succès/erreur s'affichent
- [ ] La validation des formulaires fonctionne
- [ ] Les recherches et filtres sont réactifs
- [ ] Le changement d'organisation persiste après rechargement

### Base de Données
- [ ] Les organisations sont créées correctement
- [ ] Les relations (owner, members, stores) fonctionnent
- [ ] Les invitations sont créées avec un token unique
- [ ] Les soft deletes fonctionnent
- [ ] Les timestamps sont mis à jour

### Permissions
- [ ] Les policies sont correctement appliquées
- [ ] Le middleware fonctionne
- [ ] Les utilisateurs non autorisés reçoivent une erreur 403
- [ ] Le propriétaire a tous les droits
- [ ] Les autres rôles ont les bonnes restrictions

## 🐛 Bugs Potentiels à Surveiller

1. **Logo Upload**: Vérifiez que le dossier storage/app/public/logos existe
2. **Email Invitations**: Les emails ne seront pas envoyés sans configuration SMTP
3. **Session**: Le changement d'organisation doit persister
4. **Middleware**: Vérifiez que le middleware ne bloque pas les routes publiques
5. **Scoping**: Les produits/ventes doivent être filtrés par organisation (après application du trait)

## 📝 Commandes SQL Utiles pour Test

```sql
-- Voir toutes les organisations
SELECT * FROM organizations;

-- Voir les membres d'une organisation
SELECT u.name, u.email, ou.role 
FROM users u 
JOIN organization_user ou ON u.id = ou.user_id 
WHERE ou.organization_id = 1;

-- Voir les invitations en attente
SELECT * FROM organization_invitations WHERE accepted_at IS NULL;

-- Voir les magasins par organisation
SELECT * FROM stores WHERE organization_id = 1;
```

## ✅ Checklist Finale

- [ ] Toutes les pages sont accessibles
- [ ] Les formulaires fonctionnent
- [ ] La gestion des membres fonctionne
- [ ] Le basculement d'organisation fonctionne
- [ ] Les permissions sont correctes
- [ ] Les limites d'abonnement sont respectées
- [ ] L'interface est cohérente et responsive
- [ ] Pas d'erreurs dans la console navigateur
- [ ] Pas d'erreurs dans les logs Laravel

## 🚀 Prochaines Étapes

1. Créer la commande de migration des données existantes
2. Configurer l'envoi d'emails pour les invitations
3. Implémenter la page d'acceptation d'invitation
4. Ajouter le trait `BelongsToOrganization` aux autres modèles
5. Créer l'interface de gestion des abonnements
6. Ajouter les tests automatisés

---

**Bon testing! 🎉**
