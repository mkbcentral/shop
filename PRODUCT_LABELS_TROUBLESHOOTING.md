# 🔧 Dépannage - Boutons d'Étiquettes

## ✅ Problème Résolu

Les boutons pour générer les QR codes et codes-barres ont été corrigés. Si vous rencontrez toujours des problèmes, suivez ce guide.

---

## 🚀 Solution Rapide

### 1️⃣ Vider tous les caches (OBLIGATOIRE)

```bash
php artisan view:clear
php artisan config:clear
php artisan cache:clear
php artisan package:discover
```

### 2️⃣ Rafraîchir la page

Dans votre navigateur :
- **Windows/Linux** : `Ctrl + F5`
- **Mac** : `Cmd + Shift + R`

Cela vide le cache du navigateur et recharge complètement la page.

---

## 🔍 Qu'est-ce qui a été Corrigé ?

### Problème identifié

Le modal Livewire ne recevait pas correctement les IDs de produits à cause d'une incompatibilité dans la façon de passer les paramètres.

### Fichiers corrigés

1. **app/Livewire/Product/LabelModal.php**
   - La méthode `open()` accepte maintenant différents formats de paramètres
   
2. **resources/views/components/product/table-view.blade.php**
   - Bouton vert dans la vue tableau corrigé
   
3. **resources/views/components/product-card.blade.php**
   - Bouton vert dans la vue grille corrigé
   
4. **app/Livewire/Product/ProductIndex.php**
   - Action groupée "Générer Étiquettes" corrigée

---

## ✅ Vérification

Après avoir vidé les caches et rafraîchi la page, testez :

### Test 1 : Bouton individuel (vue tableau)

```
1. Aller sur la page Produits
2. Repérer un produit dans le tableau
3. Cliquer sur le bouton VERT 🏷️ (entre Modifier et Supprimer)
4. Le modal devrait s'ouvrir avec le produit sélectionné
5. Configurer les options et cliquer sur "Générer"
6. Le PDF devrait se télécharger
```

**Résultat attendu** :
- ✅ Modal s'ouvre
- ✅ Affiche "1 produit(s) sélectionné(s)"
- ✅ PDF se télécharge avec l'étiquette du produit

### Test 2 : Bouton individuel (vue grille)

```
1. Passer en mode grille (icône en haut à droite)
2. Repérer une carte de produit
3. Cliquer sur le bouton VERT 🏷️ (au bas de la carte)
4. Le modal devrait s'ouvrir
5. Générer le PDF
```

**Résultat attendu** :
- ✅ Modal s'ouvre
- ✅ PDF se télécharge

### Test 3 : Action groupée

```
1. Cocher plusieurs produits (2-3 produits)
2. Sélectionner "Générer Étiquettes" dans Actions groupées
3. Cliquer sur "Appliquer"
4. Le modal devrait s'ouvrir
5. Générer le PDF
```

**Résultat attendu** :
- ✅ Modal s'ouvre
- ✅ Affiche "X produit(s) sélectionné(s)" (nombre correct)
- ✅ PDF se télécharge avec toutes les étiquettes

---

## 🐛 Problèmes Persistants ?

Si le bouton ne fonctionne toujours pas après avoir vidé les caches :

### 1. Vérifier la console JavaScript

1. Appuyez sur `F12` dans votre navigateur
2. Allez dans l'onglet "Console"
3. Cliquez sur le bouton vert 🏷️
4. Vérifiez s'il y a des erreurs en rouge

**Erreurs communes** :

#### Erreur : "Livewire component not initialized"
```bash
# Solution :
php artisan view:clear
php artisan optimize:clear
```

#### Erreur : "Method [open] not found"
```bash
# Solution : Vérifier que LabelModal est bien enregistré
php artisan package:discover
```

#### Erreur : "undefined is not a function"
```bash
# Solution : Rafraîchir avec Ctrl+F5 pour recharger le JavaScript
```

### 2. Vérifier que le modal est inclus

Ouvrir le fichier : `resources/views/livewire/product/product-index.blade.php`

Vérifier que cette ligne est présente :
```blade
<livewire:product.label-modal />
```

Elle devrait être juste après :
```blade
<livewire:product.product-modal />
```

### 3. Vérifier les logs Laravel

```bash
tail -f storage/logs/laravel.log
```

Puis cliquer sur le bouton vert et voir si des erreurs apparaissent.

### 4. Mode Debug

Activer le mode debug dans `.env` :
```
APP_DEBUG=true
```

Puis recharger la page et cliquer sur le bouton. Les erreurs seront affichées à l'écran.

---

## 📋 Tests Backend Validés

Un script de test a été créé pour valider le backend :

```bash
php test-label-modal.php
```

**Résultat des tests** :
```
✅ Service de génération fonctionne
✅ Données du produit correctes
✅ Formatage de devise OK
✅ Modal accepte les IDs correctement
✅ Barcode et QR code générés
```

---

## 🎯 Exemple de Flux Complet

### Scénario : Générer l'étiquette d'un produit

```
Utilisateur clique sur bouton vert 🏷️
        ↓
Livewire dispatch('openLabelModal', [123])
        ↓
LabelModal::open([123])
        ↓
$this->productIds = [123]
        ↓
$this->showModal = true
        ↓
Modal s'affiche à l'écran
        ↓
Utilisateur configure les options
        ↓
Utilisateur clique sur "Générer"
        ↓
LabelModal::generate()
        ↓
ProductLabelService::generateLabelsFromIds([123])
        ↓
PDF créé dans storage/app/temp/
        ↓
dispatch('downloadPdf', url)
        ↓
JavaScript télécharge le PDF
        ↓
Fichier temp supprimé automatiquement
```

---

## 📊 Fichier PDF Généré

Chaque étiquette contient :
- ✅ Nom du produit
- ✅ Code-barres (Code 128)
- ✅ QR code avec données JSON du produit
- ✅ Prix formaté avec la devise de l'organisation
- ✅ Référence du produit
- ✅ Catégorie

**Formats disponibles** :
- Petite : 80×50mm
- Moyenne : 100×70mm (par défaut)
- Grande : A4

---

## 🆘 Support Supplémentaire

Si le problème persiste après tous ces tests :

1. **Vérifier la version de Livewire**
   ```bash
   composer show livewire/livewire
   ```
   Version minimale requise : 3.x

2. **Réinstaller Livewire**
   ```bash
   composer update livewire/livewire
   ```

3. **Vérifier les permissions**
   ```bash
   chmod -R 775 storage
   chmod -R 775 bootstrap/cache
   ```

4. **Créer un ticket avec ces informations** :
   - Navigateur utilisé (Chrome, Firefox, etc.)
   - Version du navigateur
   - Erreurs de la console JavaScript (F12)
   - Erreurs dans `storage/logs/laravel.log`
   - Résultat de `php test-label-modal.php`

---

## ✨ Fonctionnalités Validées

Après correction, vous devriez pouvoir :

✅ Cliquer sur le bouton vert dans le tableau
✅ Cliquer sur le bouton vert dans la grille
✅ Utiliser l'action groupée pour plusieurs produits
✅ Voir le modal s'ouvrir instantanément
✅ Configurer le format (petit/moyen/grand)
✅ Configurer le nombre de colonnes (1-4)
✅ Activer/désactiver prix, barcode, QR code
✅ Télécharger le PDF généré
✅ Voir le prix formaté avec la devise correcte

---

## 📝 Checklist de Vérification

Cochez au fur et à mesure :

- [ ] J'ai vidé tous les caches (`php artisan view:clear` etc.)
- [ ] J'ai rafraîchi la page avec Ctrl+F5
- [ ] J'ai vérifié la console JavaScript (F12)
- [ ] Le modal s'ouvre quand je clique sur le bouton vert
- [ ] Le modal affiche le bon nombre de produits
- [ ] Je peux configurer les options dans le modal
- [ ] Le PDF se télécharge quand je clique sur "Générer"
- [ ] Le PDF contient le bon produit
- [ ] Le prix est formaté correctement (pas "CDF" en dur)
- [ ] Le code-barres et QR code sont visibles

---

**Date de correction** : 29 janvier 2026
**Fichiers de test** : `test-label-modal.php`
**Status** : ✅ Corrigé et testé
