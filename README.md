# 👔 Système de Gestion de Boutique d'Habillement

Application Laravel pour la gestion complète d'une boutique de vêtements avec suivi des stocks, ventes et facturations.

## 📋 Fonctionnalités Principales

### 🛍️ Gestion Commerciale
- Gestion des produits avec variations (tailles, couleurs)
- Point de vente (POS) moderne et intuitif
- Gestion des clients et historique d'achats
- Génération automatique de factures et proformas
- Gestion des modes de paiement (espèces, carte, virement, chèque)

### 📦 Gestion des Stocks
- Suivi en temps réel des stocks par variante
- Mouvements de stock traçables (entrées, sorties, ajustements, transferts)
- Alertes de stock minimum
- Gestion multi-magasins avec transferts entre boutiques
- Inventaires et ajustements

### 👥 Gestion des Utilisateurs
- Système de rôles et permissions (SuperAdmin, Manager, Vendeur)
- Gestion multi-organisations
- Authentification sécurisée avec vérification email
- Contrôle d'accès par magasin

### 📊 Reporting et Analyses
- Tableau de bord avec KPIs en temps réel
- Rapports de ventes par période
- Analyse des performances par produit
- Historique complet des transactions
- Suivi des marges bénéficiaires

### 🏪 Fonctionnalités Avancées
- Support multi-magasins et multi-organisations
- Gestion des fournisseurs et achats
- Système d'étiquettes produits personnalisables
- Impression de tickets et factures (compatible QZ Tray)
- Génération de codes QR pour les produits
- API REST pour applications mobiles (Flutter)

## 🎯 Avantages du Système

- **Traçabilité complète** : Chaque opération est enregistrée avec date, heure et utilisateur
- **Gestion fine** : Contrôle précis des stocks par taille et couleur
- **Évolutif** : Architecture modulaire pour ajouter de nouvelles fonctionnalités
- **Multi-boutiques** : Gérez plusieurs magasins depuis une seule interface
- **Sécurisé** : Système de permissions granulaire et authentification robuste
- **Performant** : Optimisé pour gérer des milliers de produits et transactions

## 🚀 Installation

```bash
# Cloner le repository
git clone <repo-url>
cd stk-back

# Installer les dépendances PHP
composer install

# Installer les dépendances JavaScript
npm install

# Configurer l'environnement
cp .env.example .env
php artisan key:generate

# Configurer la base de données dans .env
# DB_DATABASE=votre_base
# DB_USERNAME=votre_utilisateur
# DB_PASSWORD=votre_mot_de_passe

# Créer la base de données et migrer
php artisan migrate --seed

# Compiler les assets
npm run build

# Lancer le serveur de développement
php artisan serve
```

## 🔧 Technologies Utilisées

- **Backend** : Laravel 11.x
- **Frontend** : Blade Templates + Alpine.js + Livewire
- **Base de données** : MySQL / PostgreSQL
- **API** : RESTful API pour applications mobiles
- **Authentification** : Laravel Sanctum
- **UI** : Tailwind CSS + DaisyUI

## 📚 Documentation Technique

Pour plus de détails techniques sur l'architecture et l'implémentation, consultez le dossier `docs-dev/` (non versionné).

## 📝 Licence

Propriétaire - Tous droits réservés

---

**Développé pour la gestion optimale de boutiques d'habillement** 🛍️
