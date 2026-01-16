# 🔍 Guide des Commandes d'Audit par Magasin

## 📋 Commandes Disponibles

### 1. `store:audit` - Auditer les Données par Magasin

Permet de vérifier la répartition des produits, ventes et mouvements de stock par magasin.

#### Utilisation de Base

```bash
# Afficher un audit complet de tous les magasins
php artisan store:audit

# Afficher tous les détails (mode verbose)
php artisan store:audit -v
```

#### Options Disponibles

```bash
# Auditer uniquement les produits
php artisan store:audit --products

# Auditer uniquement les ventes
php artisan store:audit --sales

# Auditer uniquement les mouvements de stock
php artisan store:audit --stock

# Auditer tout (par défaut)
php artisan store:audit --all

# Filtrer par un magasin spécifique
php artisan store:audit --store=1

# Combiner les options
php artisan store:audit --products --sales --store=1 -v
```

#### Exemple de Sortie

```
🔍 AUDIT DES DONNÉES PAR MAGASIN

📍 MAGASINS DANS LE SYSTÈME:
+----+------------------+----------+-----------+-------+
| ID | Nom              | Code     | Principal | Actif |
+----+------------------+----------+-----------+-------+
| 1  | Magasin Central  | MAG-001  | ✓         | ✓     |
| 2  | Boutique Gombe   | MAG-002  |           | ✓     |
+----+------------------+----------+-----------+-------+

📦 AUDIT DES PRODUITS PAR MAGASIN:
+----------+------------------+----------------+--------+----------+
| Store ID | Magasin          | Total Produits | Actifs | Inactifs |
+----------+------------------+----------------+--------+----------+
| 1        | Magasin Central  | 45             | 42     | 3        |
| 2        | Boutique Gombe   | 23             | 23     | 0        |
+----------+------------------+----------------+--------+----------+

Total produits dans tous les magasins: 68

💰 AUDIT DES VENTES PAR MAGASIN:
+----------+------------------+------------------+----------------+
| Store ID | Magasin          | Nombre de Ventes | Montant Total  |
+----------+------------------+------------------+----------------+
| 1        | Magasin Central  | 120              | 450,000.00 FC  |
| 2        | Boutique Gombe   | 85               | 320,500.00 FC  |
+----------+------------------+------------------+----------------+

Total ventes: 205
Montant total: 770,500.00 FC

✅ Toutes les données sont correctement assignées à un magasin
```

---

### 2. `store:fix-orphans` - Corriger les Données Sans Magasin

Permet d'assigner un magasin aux données orphelines (produits, ventes, mouvements sans `store_id`).

#### Utilisation de Base

```bash
# Aperçu des corrections (sans modifier)
php artisan store:fix-orphans --dry-run

# Corriger en assignant au magasin principal
php artisan store:fix-orphans

# Corriger en assignant à un magasin spécifique
php artisan store:fix-orphans --store=2

# Mode verbose pour voir les détails
php artisan store:fix-orphans -v
```

#### Workflow Recommandé

```bash
# 1. D'abord, auditer pour voir s'il y a des problèmes
php artisan store:audit

# 2. Si des données orphelines sont détectées, voir ce qui serait corrigé
php artisan store:fix-orphans --dry-run

# 3. Appliquer les corrections
php artisan store:fix-orphans
```

#### Exemple de Sortie

```
🔧 CORRECTION DES DONNÉES SANS MAGASIN

🏪 Magasin cible: Magasin Central (ID: 1)

+----------------------+----------------------+
| Type de Données      | Nombre sans Magasin  |
+----------------------+----------------------+
| Produits             | 12                   |
| Ventes               | 3                    |
| Mouvements de Stock  | 25                   |
+----------------------+----------------------+

Voulez-vous assigner ces données au magasin 'Magasin Central' ? (yes/no) [no]:
> yes

📦 Correction de 12 produit(s)...
 12/12 [============================] 100%

💰 Correction de 3 vente(s)...
 3/3 [============================] 100%

📊 Correction de 25 mouvement(s) de stock...
 25/25 [============================] 100%

✅ Toutes les corrections ont été appliquées avec succès !
```

---

## 🎯 Cas d'Utilisation

### Scénario 1 : Vérifier la Répartition des Produits

```bash
# Voir combien de produits sont dans chaque magasin
php artisan store:audit --products

# Voir les détails des produits
php artisan store:audit --products -v
```

**Utilité :** Vérifier que les produits sont bien répartis entre les magasins.

---

### Scénario 2 : Analyser les Ventes par Magasin

```bash
# Voir les statistiques de ventes par magasin
php artisan store:audit --sales

# Filtrer pour un magasin spécifique
php artisan store:audit --sales --store=1
```

**Utilité :** Comparer les performances de vente entre magasins.

---

### Scénario 3 : Migration de Données

Après une migration ou import de données :

```bash
# 1. Vérifier s'il y a des données sans magasin
php artisan store:audit

# 2. Voir ce qui serait corrigé
php artisan store:fix-orphans --dry-run

# 3. Appliquer les corrections
php artisan store:fix-orphans

# 4. Vérifier que tout est OK
php artisan store:audit
```

---

### Scénario 4 : Debugging du Filtrage

Si un utilisateur ne voit pas ses produits :

```bash
# Vérifier que les produits sont assignés au bon magasin
php artisan store:audit --products --store=1 -v

# Vérifier s'il y a des produits orphelins
php artisan store:audit | grep "sans magasin"
```

---

## 📊 Interprétation des Résultats

### ✅ Tout est OK

```
⚠️  VÉRIFICATION DES DONNÉES SANS MAGASIN:
  ✅ Toutes les données sont correctement assignées à un magasin
```

Tous les produits, ventes et mouvements ont un `store_id`.

### ⚠️ Données Orphelines Détectées

```
⚠️  VÉRIFICATION DES DONNÉES SANS MAGASIN:
  ❌ 12 produit(s) sans magasin assigné
  ❌ 3 vente(s) sans magasin assigné
  
  💡 Pour corriger, vous pouvez assigner ces données au magasin principal:
     php artisan store:fix-orphans
```

Action requise : Utiliser `store:fix-orphans` pour corriger.

### 📊 Répartition Déséquilibrée

```
📦 AUDIT DES PRODUITS PAR MAGASIN:
| 1  | Magasin Central  | 150  |
| 2  | Boutique Gombe   | 2    |
```

Si un magasin a très peu de produits, vérifier :
- Les utilisateurs créent-ils dans le bon magasin ?
- Les imports de données fonctionnent-ils correctement ?

---

## 🔧 Automatisation

### Cron Job Quotidien

Ajoutez dans `app/Console/Kernel.php` :

```php
protected function schedule(Schedule $schedule)
{
    // Audit quotidien à minuit
    $schedule->command('store:audit --all')
             ->dailyAt('00:00')
             ->sendOutputTo(storage_path('logs/store-audit.log'));
}
```

### Script de Vérification Post-Migration

```bash
#!/bin/bash

echo "🔍 Vérification après migration..."

# Audit complet
php artisan store:audit > audit-report.txt

# Compter les orphelins
ORPHANS=$(grep "sans magasin" audit-report.txt | wc -l)

if [ $ORPHANS -gt 0 ]; then
    echo "⚠️  $ORPHANS type(s) de données orphelines détectés"
    echo "Correction automatique..."
    php artisan store:fix-orphans
else
    echo "✅ Aucune donnée orpheline"
fi

echo "✅ Vérification terminée"
```

---

## 🎓 Exemples Avancés

### Audit Complet avec Export

```bash
# Générer un rapport complet
php artisan store:audit --all -v > rapport-$(date +%Y%m%d).txt

# Envoyer par email (si configuré)
php artisan store:audit | mail -s "Rapport Audit Magasins" admin@example.com
```

### Corriger Uniquement un Type de Données

Il faudrait créer une commande personnalisée, ou utiliser tinker :

```bash
php artisan tinker
```

```php
// Corriger uniquement les produits
$mainStore = App\Models\Store::where('is_main', true)->first();
App\Models\Product::whereNull('store_id')->update(['store_id' => $mainStore->id]);
```

---

## 📝 Checklist de Maintenance

Utiliser ces commandes régulièrement :

- [ ] **Hebdomadaire** : `php artisan store:audit` pour surveiller la répartition
- [ ] **Après import** : `php artisan store:audit` + `store:fix-orphans` si nécessaire
- [ ] **Avant déploiement** : Vérifier qu'il n'y a pas de données orphelines
- [ ] **Après migration** : Audit complet et correction

---

## 🐛 Dépannage

### Erreur : "Aucun magasin trouvé"

```bash
# Vérifier qu'il y a des magasins
php artisan tinker
App\Models\Store::count();

# Créer le magasin principal si nécessaire
php artisan db:seed --class=StoreSeeder
```

### Commande Lente

Si l'audit est lent avec beaucoup de données :

```bash
# Auditer uniquement un magasin
php artisan store:audit --store=1

# Auditer uniquement un type
php artisan store:audit --products
```

---

## 🎉 Résumé

| Commande | Usage | Quand l'utiliser |
|----------|-------|------------------|
| `store:audit` | Voir la répartition des données | Régulièrement, pour surveiller |
| `store:fix-orphans --dry-run` | Aperçu des corrections | Avant de corriger |
| `store:fix-orphans` | Corriger les données orphelines | Après import/migration |

**Ces commandes garantissent que toutes les données sont correctement assignées aux magasins ! 🚀**

---

**Version:** 1.0.0  
**Date:** 7 janvier 2026
