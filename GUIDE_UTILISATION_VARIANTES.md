# 🚀 GUIDE RAPIDE - Système de Variantes de Produits

## 📖 Guide d'Utilisation

---

## 1️⃣ Créer un Produit avec Variantes

### Étapes :

1. **Ouvrir le formulaire de création**
   - Cliquer sur "Nouveau Produit"

2. **Remplir les informations de base**
   ```
   Nom: Chaussure Nike Air Max
   Référence: NIKE-AM-001 (auto-généré)
   Prix: 12000 FC
   Catégorie: Chaussures
   ```

3. **Sélectionner le Type de Produit**
   ```
   Type: 👟 Chaussures
   ```
   → Les attributs du type s'affichent automatiquement

4. **Sélectionner les Variantes**
   
   **Pointure** (cocher plusieurs cases) :
   - ☑️ 38
   - ☑️ 39
   - ☑️ 40
   - ☑️ 41
   - ☑️ 42
   
   **Couleur** (cocher plusieurs cases) :
   - ☑️ Noir
   - ☑️ Blanc
   - ☑️ Rouge

5. **Voir l'aperçu**
   ```
   📦 Aperçu des Variantes
   ✅ 15 variantes seront générées automatiquement
   
   Exemples :
   1. Pointure: 38 • Couleur: Noir
   2. Pointure: 39 • Couleur: Noir
   ...
   ```

6. **Créer le produit**
   - Cliquer sur "Créer"
   - ✅ 15 variantes créées avec SKU uniques !

---

## 2️⃣ Vendre un Produit avec Variantes

### Dans le Point de Vente (POS) :

1. **Rechercher le produit**
   - Scanner le code-barres OU
   - Chercher par nom "Nike Air Max"

2. **Sélectionner la variante**
   
   Un modal s'ouvre automatiquement :
   
   ```
   ┌─────────────────────────────────────┐
   │ 🏷️ Choisir une variante            │
   │ Nike Air Max 90                     │
   ├─────────────────────────────────────┤
   │                                     │
   │ Pointure *                          │
   │ [38] [39] [40] [41] [42]           │
   │                                     │
   │ Couleur *                           │
   │ [Noir] [Blanc] [Rouge]             │
   │                                     │
   │ ✅ Stock: 15 unités                 │
   │    Prix: 12 000 FC                  │
   │                                     │
   │ [Annuler] [🛒 Ajouter au panier]   │
   └─────────────────────────────────────┘
   ```

3. **Choisir les options**
   - Cliquer sur "42" pour la pointure
   - Cliquer sur "Noir" pour la couleur

4. **Vérifier**
   - Le système affiche le stock disponible
   - Le prix s'affiche (avec supplément si applicable)

5. **Ajouter au panier**
   - Cliquer sur "Ajouter au panier"
   - ✅ La variante exacte est ajoutée !

---

## 3️⃣ Importer des Variantes en Masse

### Cas d'usage : 
Vous avez 100 sacs de la même marque mais de couleurs différentes

### Étapes :

1. **Créer le produit parent**
   ```
   Nom: Sac à Main Luxe
   Référence: SAC-001
   Type: Sacs
   Prix: 5000 FC
   ```

2. **Télécharger le template CSV**
   ```php
   // Route à créer
   GET /products/{product}/variants/template
   ```
   
   Le fichier téléchargé contient :
   ```csv
   Référence_Produit,Couleur,Stock_Initial,Prix_Supplementaire,Code_Barres
   SAC-001,Exemple,10,0,
   ```

3. **Remplir le CSV**
   ```csv
   Référence_Produit,Couleur,Stock_Initial,Prix_Supplementaire,Code_Barres
   SAC-001,Noir,25,0,
   SAC-001,Blanc,30,500,
   SAC-001,Rouge,15,500,
   SAC-001,Beige,20,0,
   SAC-001,Bleu marine,10,0,
   ```

4. **Importer le fichier**
   ```php
   // Route à créer
   POST /products/{product}/variants/import
   ```
   
   Sélectionner le fichier CSV et uploader

5. **Résultat**
   ```
   ✅ 5 variantes importées avec succès !
   
   Variantes créées :
   - SAC-001-NOI (Stock: 25, Prix: 5000 FC)
   - SAC-001-BLA (Stock: 30, Prix: 5500 FC)
   - SAC-001-ROU (Stock: 15, Prix: 5500 FC)
   - SAC-001-BEI (Stock: 20, Prix: 5000 FC)
   - SAC-001-BLE (Stock: 10, Prix: 5000 FC)
   ```

---

## 4️⃣ Gérer le Stock par Variante

### Consultation du Stock :

```
Produit: Nike Air Max 90
┌────────────────────────────────────────┐
│ Variante          │ Stock │ Statut     │
├───────────────────┼───────┼────────────┤
│ 38 - Noir         │   15  │ ✅ En stock│
│ 39 - Noir         │    3  │ ⚠️  Faible │
│ 40 - Noir         │    0  │ ❌ Rupture │
│ 41 - Blanc        │   20  │ ✅ En stock│
└───────────────────┴───────┴────────────┘
```

### Ajustement du Stock :

Pour chaque variante, vous pouvez :
- Ajouter du stock (réception de commande)
- Retirer du stock (correction)
- Définir des seuils d'alerte personnalisés

---

## 5️⃣ Affichage sur Facture

### Avant (Sans variantes détaillées) :
```
1. Nike Air Max 90           1x    12 000 FC
```

### Après (Avec variantes détaillées) :
```
1. Nike Air Max 90           1x    12 000 FC
   (Pointure: 42, Couleur: Noir)
```

Les détails de la variante sont automatiquement inclus dans les factures et reçus !

---

## 💡 Conseils et Astuces

### ✅ Bonnes Pratiques

1. **Nomenclature des SKU**
   - Utiliser un format cohérent
   - Exemple: `[REFERENCE]-[ATTRIBUT1]-[ATTRIBUT2]`
   - `NIKE-AM-42-NOIR`

2. **Gestion des Prix**
   - Prix de base sur le produit parent
   - Prix additionnel sur les variantes spéciales
   - Exemple: Capacité 256GB = +3000 FC

3. **Stock**
   - Définir des seuils d'alerte réalistes
   - Seuil bas: 10 unités
   - Seuil min: 0 unités

4. **Types de Produits**
   - Créer un type pour chaque catégorie majeure
   - 👕 Vêtements (Taille, Couleur)
   - 👟 Chaussures (Pointure, Couleur)
   - 📱 Électronique (Capacité, Couleur)
   - 🍷 Alimentaire (Volume, Date)

### ⚠️ À Éviter

❌ Ne pas créer trop d'attributs variantes
   - Maximum 3-4 attributs par type
   - Trop d'attributs = trop de combinaisons

❌ Ne pas oublier de définir le stock
   - Variantes sans stock = non vendables
   - Toujours définir le stock initial

❌ Ne pas dupliquer les produits manuellement
   - Utiliser le système de variantes
   - Utiliser l'import CSV pour les volumes importants

---

## 🆘 Résolution de Problèmes

### Problème: "Aucune variante générée"

**Cause:** Aucun attribut variante sélectionné

**Solution:**
1. Vérifier que le type de produit a `has_variants = true`
2. S'assurer que les attributs ont `is_variant_attribute = true`
3. Cocher au moins une option pour chaque attribut variante

---

### Problème: "Trop de variantes générées"

**Cause:** Trop d'options cochées

**Solution:**
1. Décocher les options non nécessaires
2. Créer plusieurs produits parents si besoin
   - Exemple: Un produit pour les baskets, un autre pour les sandales

---

### Problème: "Variante non disponible lors de la vente"

**Cause:** Stock à zéro ou variante inexistante

**Solution:**
1. Vérifier le stock de la variante
2. Ajouter du stock si nécessaire
3. Vérifier que la combinaison existe bien

---

## 📞 Support

Pour toute question ou problème :
- 📧 Email: support@stk.com
- 📚 Documentation complète: [RAPPORT_SYSTEME_VARIANTES_PRODUITS.md](RAPPORT_SYSTEME_VARIANTES_PRODUITS.md)
- 🛠️ Implémentation: [IMPLEMENTATION_VARIANTES_COMPLETE.md](IMPLEMENTATION_VARIANTES_COMPLETE.md)

---

**Guide mis à jour le:** 14 Janvier 2026  
**Version:** 1.0
