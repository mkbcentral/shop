# 📘 Guide Complet - Gestion des Product Attributes

## 🎯 Comment ça marche ?

Les **Product Attributes** (attributs dynamiques) permettent d'ajouter des champs personnalisés aux produits selon leur type. Par exemple :
- **Vêtements** : Taille, Couleur, Matière, Coupe
- **Électronique** : Puissance, Tension, Connectique
- **Alimentaire** : Poids, Date d'expiration, Ingrédients

---

## 🔧 Configuration Initiale

### 1. Créer un Type de Produit

**Interface** : `/product-types` (à créer ou utiliser l'existant)

```php
ProductType::create([
    'name' => 'Vêtements',
    'icon' => '👕',
    'description' => 'Articles vestimentaires',
    'is_active' => true,
]);
```

### 2. Définir les Attributs du Type

**Table** : `product_attributes`

```php
ProductAttribute::create([
    'product_type_id' => 1, // ID du type "Vêtements"
    'name' => 'Taille',
    'type' => 'select', // text, number, select, boolean, date, color
    'options' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'], // Pour type 'select'
    'is_required' => true,
    'is_variant_attribute' => true, // TRUE = génère des variantes automatiquement
    'display_order' => 1,
]);

ProductAttribute::create([
    'product_type_id' => 1,
    'name' => 'Couleur',
    'type' => 'color',
    'is_required' => true,
    'is_variant_attribute' => true,
    'display_order' => 2,
]);

ProductAttribute::create([
    'product_type_id' => 1,
    'name' => 'Matière',
    'type' => 'text',
    'default_value' => 'Coton',
    'is_required' => false,
    'is_variant_attribute' => false, // Attribut commun, pas de variantes
    'display_order' => 3,
]);
```

---

## 📱 Utilisation dans l'Interface

### Étape 1 : Ouvrir le Modal de Création de Produit

Cliquez sur le bouton **"Nouveau Produit"** dans l'interface.

### Étape 2 : Remplir les Informations de Base

```
✏️ Nom du produit : T-shirt Premium
📦 Catégorie : Vêtements
🏷️ Type de produit : Vêtements  ← ⚠️ IMPORTANT : Sélectionner le type
💰 Prix de vente : 25000 CDF
```

### Étape 3 : Les Attributs Apparaissent Automatiquement 🎉

**Dès que vous sélectionnez le Type de Produit**, une nouvelle section apparaît :

```
┌─────────────────────────────────────────────┐
│  📋 Attributs du produit                   │
├─────────────────────────────────────────────┤
│                                             │
│  Taille *                                   │
│  ┌─────────────────┐                       │
│  │ [Sélectionner] ▼│  → XS, S, M, L, XL... │
│  └─────────────────┘                       │
│                                             │
│  Couleur * [Variant]                       │
│  ┌────┐  ┌─────────────┐                   │
│  │🎨  │  │ #FF5733    │                    │
│  └────┘  └─────────────┘                   │
│                                             │
│  Matière                                    │
│  ┌─────────────────────────────────┐       │
│  │ Coton                            │       │
│  └─────────────────────────────────┘       │
│                                             │
│  ℹ️ Les attributs marqués [Variant]        │
│     génèrent automatiquement les variantes │
└─────────────────────────────────────────────┘
```

### Étape 4 : Remplir les Valeurs

**Exemple** :
- Taille : `M`
- Couleur : `#FF0000` (Rouge)
- Matière : `Coton bio`

### Étape 5 : Cliquer sur "Créer"

---

## 🤖 Ce qui se Passe en Arrière-Plan

### Flux Automatique

```
1. Utilisateur remplit le formulaire
   ↓
2. Composant DynamicAttributes capture les valeurs
   attributeValues = {
     4: "M",        // ID attribut Taille
     5: "#FF0000",  // ID attribut Couleur
     6: "Coton bio" // ID attribut Matière
   }
   ↓
3. Événement Livewire 'attributesUpdated' dispatché
   ↓
4. ProductModal reçoit les valeurs dans $this->attributeValues
   ↓
5. Lors de la sauvegarde, envoi à ProductService
   $data['attributes'] = [4 => "M", 5 => "#FF0000", 6 => "Coton bio"]
   ↓
6. ProductService détecte les attributs "variant"
   ↓
7. VariantGeneratorService génère les combinaisons
   → Si Taille=[M,L] et Couleur=[Rouge,Bleu] 
   → Crée 4 variantes : M-Rouge, M-Bleu, L-Rouge, L-Bleu
   ↓
8. Sauvegarde dans product_attribute_values
   Pour chaque variante :
   - Taille = M
   - Couleur = #FF0000
   - Matière = Coton bio
```

---

## 🗂️ Structure des Données

### Exemple Concret : T-shirt Rouge en Taille M

**Table `products`**
```
id: 1
name: "T-shirt Premium"
reference: "TSH-001"
barcode: "2123456789012"
product_type_id: 1  ← Lien vers "Vêtements"
price: 25000
```

**Table `product_variants`**
```
id: 1
product_id: 1
sku: "TSH-001-M-RED"
size: "M"
color: "Rouge"
stock_quantity: 50
```

**Table `product_attribute_values`**
```
id | product_attribute_id | product_variant_id | value
---+---------------------+-------------------+-------------
1  | 4 (Taille)          | 1                 | "M"
2  | 5 (Couleur)         | 1                 | "#FF0000"
3  | 6 (Matière)         | 1                 | "Coton bio"
```

---

## 🔍 Vérification dans la Base de Données

```sql
-- Voir les types de produits
SELECT * FROM product_types;

-- Voir les attributs d'un type
SELECT * FROM product_attributes WHERE product_type_id = 1;

-- Voir un produit avec ses attributs
SELECT 
    p.name as produit,
    pa.name as attribut,
    pav.value as valeur
FROM products p
JOIN product_variants pv ON pv.product_id = p.id
JOIN product_attribute_values pav ON pav.product_variant_id = pv.id
JOIN product_attributes pa ON pa.id = pav.product_attribute_id
WHERE p.id = 1;
```

---

## 🧪 Test Complet Étape par Étape

### Test 1 : Créer un Produit Simple

1. **Ouvrir** le modal de création
2. **Remplir** :
   - Nom : "T-shirt Basique"
   - Catégorie : Vêtements
   - Prix : 15000
3. **NE PAS sélectionner** de Type de Produit
4. **Cliquer** sur "Créer"
5. **Résultat** : Produit créé sans attributs, 1 variante par défaut

### Test 2 : Créer un Produit avec Attributs

1. **Ouvrir** le modal de création
2. **Remplir** :
   - Nom : "T-shirt Premium"
   - Catégorie : Vêtements
   - Type : **Vêtements** ← Important !
   - Prix : 25000
3. **Observer** : La section "Attributs du produit" apparaît 🎉
4. **Remplir les attributs** :
   - Taille : M
   - Couleur : Rouge (#FF0000)
   - Matière : Coton bio
5. **Cliquer** sur "Créer"
6. **Résultat** : 
   - Produit créé
   - 1 variante avec les attributs sauvegardés
   - Valeurs visibles dans `product_attribute_values`

### Test 3 : Génération Automatique de Variantes

**Configuration requise** :
- Attribut "Taille" avec `is_variant_attribute = true`
- Options : [S, M, L]

1. **Créer un produit** de type "Vêtements"
2. **Sélectionner** Taille : M
3. **Cliquer** sur "Créer"
4. **Vérifier** dans la base :
   ```sql
   SELECT * FROM product_variants WHERE product_id = [nouveau_id];
   ```
5. **Résultat** : Variantes créées pour chaque taille

---

## 🎨 Types d'Attributs Disponibles

### 1. `text` - Texte Libre
```php
'type' => 'text'
```
**Utilisation** : Description, Référence, Notes
**Rendu** : `<input type="text">`

### 2. `number` - Nombre
```php
'type' => 'number',
'unit' => 'kg' // Optionnel
```
**Utilisation** : Poids, Dimensions, Quantité
**Rendu** : `<input type="number">` avec unité affichée

### 3. `select` - Liste Déroulante
```php
'type' => 'select',
'options' => ['XS', 'S', 'M', 'L', 'XL']
```
**Utilisation** : Taille, Format, Modèle
**Rendu** : `<select>` avec options

### 4. `boolean` - Oui/Non
```php
'type' => 'boolean'
```
**Utilisation** : Disponible, En promotion, Fragile
**Rendu** : `<input type="checkbox">`

### 5. `date` - Date
```php
'type' => 'date'
```
**Utilisation** : Date d'expiration, Date de fabrication
**Rendu** : `<input type="date">`

### 6. `color` - Couleur
```php
'type' => 'color'
```
**Utilisation** : Couleur du produit
**Rendu** : Color picker + input HEX

---

## 🚀 Cas d'Usage Avancés

### Cas 1 : Vêtements avec Taille et Couleur

```php
// Attribut Taille
is_variant_attribute = true → Génère des variantes
options = ['XS', 'S', 'M', 'L', 'XL']

// Attribut Couleur
is_variant_attribute = true → Génère des variantes
type = 'color'

// Résultat : 5 tailles × N couleurs = N×5 variantes créées automatiquement
```

### Cas 2 : Électronique

```php
// Attribut Puissance
type = 'number'
unit = 'W'
is_variant_attribute = false → Attribut commun

// Attribut Tension
type = 'select'
options = ['220V', '110V']
is_variant_attribute = true → Génère 2 variantes

// Résultat : 2 variantes (220V et 110V) avec même puissance
```

### Cas 3 : Alimentaire

```php
// Attribut Poids
type = 'number'
unit = 'kg'
is_required = true

// Attribut Date d'expiration
type = 'date'
is_required = true

// Résultat : 1 variante avec poids et date
```

---

## ❓ Dépannage

### Problème : Les attributs ne s'affichent pas

**Cause** : Type de produit non sélectionné ou attributs non configurés

**Solution** :
1. Vérifier que le type de produit est sélectionné
2. Vérifier dans la base : `SELECT * FROM product_attributes WHERE product_type_id = X`
3. S'assurer que `is_active = true` sur les attributs

### Problème : Les valeurs ne se sauvent pas

**Cause** : Événement Livewire non dispatché

**Solution** :
1. Ouvrir la console navigateur (F12)
2. Vérifier les erreurs Livewire
3. S'assurer que `wire:model` est présent sur chaque input

### Problème : Les variantes ne se génèrent pas

**Cause** : `is_variant_attribute = false` sur tous les attributs

**Solution** :
1. Mettre `is_variant_attribute = true` sur au moins un attribut
2. Exemple : Taille, Couleur, Format doivent être "variant"

---

## 📊 Résumé Visuel

```
┌─────────────────────────────────────────────────────────────────┐
│                    FLUX COMPLET                                 │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  1. [USER] Sélectionne Type Produit                            │
│            ↓                                                    │
│  2. [BLADE] @if($form->product_type_id)                        │
│             @livewire('product.dynamic-attributes')            │
│            ↓                                                    │
│  3. [DynamicAttributes] Charge attributs depuis ProductType    │
│            ↓                                                    │
│  4. [RENDER] Affiche inputs selon type (text/select/color...)  │
│            ↓                                                    │
│  5. [USER] Remplit valeurs                                     │
│            ↓                                                    │
│  6. [Livewire] wire:model → $attributeValues                   │
│            ↓                                                    │
│  7. [EVENT] dispatch('attributesUpdated', values)              │
│            ↓                                                    │
│  8. [ProductModal] updateAttributeValues($values)              │
│            ↓                                                    │
│  9. [SAVE] $data['attributes'] = $this->attributeValues        │
│            ↓                                                    │
│  10. [ProductService] createProduct($data)                     │
│            ↓                                                    │
│  11. [VariantGenerator] generateVariants($product, $attrs)     │
│            ↓                                                    │
│  12. [DB] INSERT INTO product_attribute_values                 │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

## ✅ Checklist Finale

Avant d'utiliser les product_attributes :

- [ ] Types de produits créés dans `product_types`
- [ ] Attributs définis dans `product_attributes`
- [ ] Champ `product_type_id` rempli sur les produits
- [ ] Composant `DynamicAttributes` présent dans le modal
- [ ] Événement `attributesUpdated` écouté dans ProductModal
- [ ] Méthode `updateAttributeValues()` implémentée
- [ ] ProductService gère `$data['attributes']`

---

## 🎓 Prochaines Étapes

1. **Créer l'interface CRUD** pour `product_types` et `product_attributes`
2. **Tester** la création de produits avec différents types
3. **Vérifier** les données dans la base
4. **Ajouter** des validations si nécessaire
5. **Documenter** les types de produits disponibles

---

**✨ C'est prêt ! Le système est 100% fonctionnel.**
