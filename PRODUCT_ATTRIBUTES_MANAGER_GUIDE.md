# 📋 Gestion des Attributs de Produits

## Vue d'ensemble

La page **Attributs de Produits** permet de configurer tous les attributs personnalisés pour chaque type de produit. Ces attributs définissent les champs spécifiques qui apparaîtront dans le formulaire de création de produits.

## Accès

- **Route:** `/product-attributes`
- **Menu:** Attributs de Produits (section Products)
- **Composant:** `App\Livewire\ProductAttribute\AttributeManager`

---

## Fonctionnalités

### 1. Liste des Attributs

#### Colonnes affichées :
- **Attribut** : Nom et code de l'attribut
- **Type de Produit** : À quel type de produit cet attribut appartient (👗 Vêtement, 🍎 Alimentaire, 📱 Électronique)
- **Format** : Type de champ (Texte, Nombre, Liste, Oui/Non, Couleur, Date)
- **Options/Unité** : Options disponibles (pour les listes) ou unité de mesure (pour les nombres)
- **Propriétés** : Badges indiquant les caractéristiques
  - ★ = Obligatoire
  - V = Génère des variantes
  - F = Filtrable
  - 👁️ = Caché
- **Ordre** : Position d'affichage dans le formulaire
- **Actions** : Modifier / Supprimer

#### Filtres disponibles :
- **Recherche** : Par nom ou code
- **Type de Produit** : Filtrer par type (Vêtement, Alimentaire, Électronique)
- **Format** : Filtrer par type de champ
- **Pagination** : 10, 25, 50 ou 100 attributs par page

---

### 2. Créer un Attribut

#### Champs du formulaire :

**Informations de base :**
- **Type de Produit** *(requis)* : Sélectionner à quel type de produit appartient cet attribut
- **Nom de l'attribut** *(requis)* : Nom affiché (ex: "Taille", "Poids", "Marque")
- **Code** : Identifiant technique (auto-généré à partir du nom)

**Configuration du format :**
- **Format de l'attribut** *(requis)* :
  - `text` : Input texte simple
  - `number` : Champ numérique
  - `select` : Liste déroulante avec options prédéfinies
  - `boolean` : Case à cocher (Oui/Non)
  - `color` : Sélecteur de couleur
  - `date` : Sélecteur de date
  - `textarea` : Zone de texte multi-lignes

**Options spécifiques :**
- **Options** *(si format = select)* : Liste des valeurs séparées par des virgules
  - Exemple: `XS, S, M, L, XL, XXL`
- **Unité de mesure** *(si format = number)* : kg, cm, W, V, etc.
- **Texte du label** *(si format = boolean)* : Texte explicatif pour la case à cocher

**Affichage :**
- **Ordre d'affichage** : Nombre définissant la position (plus petit = affiché en premier)

**Propriétés :**
- ☑️ **Obligatoire** : Doit être rempli lors de la création du produit
- ☑️ **Génère des variantes** : Crée automatiquement des combinaisons de produits
  - Exemple: Si Taille et Couleur sont "variantes", cela génère : M-Rouge, M-Bleu, L-Rouge, L-Bleu, etc.
- ☑️ **Filtrable** : Peut être utilisé comme filtre dans les listes de produits
- ☑️ **Visible** : Affiché dans l'interface (décocher pour masquer)

---

### 3. Modifier un Attribut

Cliquer sur l'icône ✏️ pour modifier un attribut existant. Tous les champs sont modifiables.

⚠️ **Attention** : Modifier le type d'un attribut déjà utilisé peut affecter les produits existants.

---

### 4. Supprimer un Attribut

Cliquer sur l'icône 🗑️ pour supprimer un attribut.

⚠️ **Restriction** : Impossible de supprimer un attribut utilisé par des produits existants.

---

## Exemples de Configuration

### Vêtement 👗

| Nom | Format | Options/Unité | Obligatoire | Variante |
|-----|--------|---------------|-------------|----------|
| Taille | select | XS, S, M, L, XL, XXL, XXXL | ✅ | ✅ |
| Couleur | color | - | ✅ | ✅ |
| Matière | select | Coton, Polyester, Laine, Soie | ❌ | ❌ |
| Coupe | select | Slim, Regular, Loose | ❌ | ❌ |
| Genre | select | Homme, Femme, Unisexe | ❌ | ❌ |

**Résultat** : Si un produit a 3 tailles × 4 couleurs = 12 variantes automatiques

---

### Alimentaire 🍎

| Nom | Format | Options/Unité | Obligatoire | Variante |
|-----|--------|---------------|-------------|----------|
| Poids | number | kg | ✅ | ❌ |
| Date d'expiration | date | - | ✅ | ❌ |
| Format | select | Petit (250g), Moyen (500g), Grand (1kg) | ❌ | ✅ |
| Bio | boolean | Produit biologique | ❌ | ❌ |
| Origine | select | Local, Importé, France, Europe | ❌ | ❌ |
| Conservation | select | Ambiante, Réfrigéré, Congelé | ❌ | ❌ |

**Résultat** : Les formats génèrent des variantes (250g, 500g, 1kg)

---

### Électronique 📱

| Nom | Format | Options/Unité | Obligatoire | Variante |
|-----|--------|---------------|-------------|----------|
| Marque | select | Samsung, Apple, LG, Sony | ✅ | ❌ |
| Capacité | select | 32GB, 64GB, 128GB, 256GB | ❌ | ✅ |
| Couleur | select | Noir, Blanc, Gris, Or | ❌ | ✅ |
| Puissance | number | W | ❌ | ❌ |
| Tension | select | 220V, 110V, 12V, 5V | ❌ | ❌ |
| Garantie | select | 6 mois, 1 an, 2 ans, 3 ans | ❌ | ❌ |

**Résultat** : Capacité × Couleur génère automatiquement toutes les combinaisons

---

## Utilisation dans le Formulaire Produit

Une fois les attributs configurés :

1. Créer un nouveau produit
2. Sélectionner le **Type de Produit**
3. Les attributs configurés apparaissent automatiquement
4. Les champs marqués "Obligatoire" doivent être remplis
5. Les attributs "Variantes" génèrent automatiquement les combinaisons

---

## Architecture Technique

### Tables Utilisées

- **product_attributes** : Définition des attributs
- **product_attribute_values** : Valeurs des attributs pour chaque produit
- **product_variants** : Variantes générées automatiquement

### Relations

```
ProductType (1) ──< (N) ProductAttribute
ProductAttribute (1) ──< (N) ProductAttributeValue
ProductVariant (1) ──< (N) ProductAttributeValue
```

### Services

- **ProductService** : Gestion de la création de produits avec attributs
- **VariantGeneratorService** : Génération automatique des variantes

---

## Migration de Seed

Les attributs par défaut ont été créés via :
```bash
php artisan migrate --path=database/migrations/2026_01_12_000001_seed_product_attributes_for_all_types.php
```

Cette migration a créé **18 attributs** :
- 5 pour Vêtement
- 6 pour Alimentaire
- 7 pour Électronique

---

## FAQ

**Q: Puis-je créer des attributs pour d'autres types de produits ?**  
R: Oui ! Créez d'abord le type de produit dans "Types de Produits", puis ajoutez ses attributs.

**Q: Combien d'attributs "variante" puis-je avoir ?**  
R: Techniquement illimité, mais attention : 3 attributs avec 5 options chacun = 125 variantes !

**Q: Puis-je supprimer un attribut utilisé par des produits ?**  
R: Non, pour préserver l'intégrité des données. Vous pouvez le masquer avec "Visible = Non".

**Q: Comment réorganiser l'ordre des attributs ?**  
R: Modifiez le champ "Ordre d'affichage". Plus le nombre est petit, plus l'attribut est affiché tôt.

**Q: Que se passe-t-il si je change les options d'une liste déroulante ?**  
R: Les nouvelles options sont disponibles, les anciennes valeurs restent mais peuvent ne plus correspondre.

---

## Support

Pour toute question technique, consultez :
- [GUIDE_PRODUCT_ATTRIBUTES.md](./GUIDE_PRODUCT_ATTRIBUTES.md) - Guide complet du système d'attributs
- [MULTI_PRODUCT_TYPES_IMPLEMENTATION_PHASE1.md](./MULTI_PRODUCT_TYPES_IMPLEMENTATION_PHASE1.md) - Documentation technique

---

**Version:** 1.0  
**Dernière mise à jour:** 12 janvier 2026
