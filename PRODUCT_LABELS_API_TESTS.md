# Tests API - Génération d'Étiquettes Produits

## ✅ Tests Réussis Localement

Les tests suivants ont été exécutés avec succès :

```bash
php test-product-labels.php
```

**Résultats :**
- ✅ Étiquette simple générée : `storage/app/public/test_etiquette_1.pdf`
- ✅ Étiquettes multiples (3 produits) : `storage/app/public/test_etiquettes_multiples.pdf`
- ✅ Étiquette avec variantes : `storage/app/public/test_etiquette_avec_variantes.pdf`

---

## 🔗 Routes API Disponibles

### 1. Étiquette pour un produit unique

**Route:** `GET /api/mobile/products/{id}/labels`

**Exemples de Tests:**

```bash
# Test 1: Étiquette petite (format par défaut)
curl -H "Authorization: Bearer YOUR_TOKEN" \
  "https://shop.mkbcentral.com/api/mobile/products/1/labels" \
  --output etiquette_produit_1.pdf

# Test 2: Étiquette moyenne avec 2 colonnes
curl -H "Authorization: Bearer YOUR_TOKEN" \
  "https://shop.mkbcentral.com/api/mobile/products/1/labels?format=medium&columns=2" \
  --output etiquette_produit_1_medium.pdf

# Test 3: Étiquette grande sans prix
curl -H "Authorization: Bearer YOUR_TOKEN" \
  "https://shop.mkbcentral.com/api/mobile/products/1/labels?format=large&show_price=false" \
  --output etiquette_produit_1_large.pdf

# Test 4: Étiquette avec variantes
curl -H "Authorization: Bearer YOUR_TOKEN" \
  "https://shop.mkbcentral.com/api/mobile/products/1/labels?include_variants=true" \
  --output etiquette_produit_1_variants.pdf

# Test 5: Étiquette sans QR code
curl -H "Authorization: Bearer YOUR_TOKEN" \
  "https://shop.mkbcentral.com/api/mobile/products/1/labels?show_qr_code=false" \
  --output etiquette_produit_1_no_qr.pdf

# Test 6: Étiquette avec seulement le code-barres
curl -H "Authorization: Bearer YOUR_TOKEN" \
  "https://shop.mkbcentral.com/api/mobile/products/1/labels?show_qr_code=false&show_price=false" \
  --output etiquette_produit_1_barcode_only.pdf
```

**Paramètres disponibles:**

| Paramètre | Type | Valeur par défaut | Description |
|-----------|------|-------------------|-------------|
| `format` | string | `small` | `small`, `medium`, `large` |
| `columns` | int | `3` | Nombre de colonnes (1-4) |
| `show_price` | boolean | `true` | Afficher le prix |
| `show_qr_code` | boolean | `true` | Afficher le QR code |
| `show_barcode` | boolean | `true` | Afficher le code-barres |
| `include_variants` | boolean | `false` | Inclure les variantes |

---

### 2. Étiquettes pour plusieurs produits

**Route:** `POST /api/mobile/products/labels/bulk`

**Exemples de Tests:**

```bash
# Test 1: Étiquettes pour 3 produits (format small)
curl -X POST \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "product_ids": [1, 2, 3],
    "format": "small",
    "columns": 3,
    "show_price": true,
    "show_qr_code": true,
    "show_barcode": true
  }' \
  "https://shop.mkbcentral.com/api/mobile/products/labels/bulk" \
  --output etiquettes_multiples.pdf

# Test 2: Étiquettes pour 5 produits (format medium)
curl -X POST \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "product_ids": [1, 2, 3, 4, 5],
    "format": "medium",
    "columns": 2,
    "show_price": true,
    "show_qr_code": true,
    "show_barcode": true
  }' \
  "https://shop.mkbcentral.com/api/mobile/products/labels/bulk" \
  --output etiquettes_5_produits.pdf

# Test 3: Étiquettes avec variantes incluses
curl -X POST \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "product_ids": [1, 2],
    "format": "small",
    "columns": 3,
    "include_variants": true,
    "show_price": true,
    "show_qr_code": true,
    "show_barcode": true
  }' \
  "https://shop.mkbcentral.com/api/mobile/products/labels/bulk" \
  --output etiquettes_avec_variantes.pdf

# Test 4: Étiquettes grandes pour impression A4
curl -X POST \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "product_ids": [1, 2, 3, 4],
    "format": "large",
    "columns": 2,
    "show_price": true,
    "show_qr_code": true,
    "show_barcode": true
  }' \
  "https://shop.mkbcentral.com/api/mobile/products/labels/bulk" \
  --output etiquettes_a4.pdf
```

---

## 🧪 Tests avec Postman

### Configuration de base

1. **Créer une nouvelle request**
   - Méthode: `GET` ou `POST`
   - URL: `https://shop.mkbcentral.com/api/mobile/products/{id}/labels`

2. **Headers**
   ```
   Authorization: Bearer {votre_token}
   Content-Type: application/json  (pour POST uniquement)
   ```

3. **Send & Download**
   - Cliquer sur "Send and Download"
   - Sauvegarder le fichier PDF

### Collection Postman

```json
{
  "info": {
    "name": "Product Labels API",
    "description": "Tests pour la génération d'étiquettes produits"
  },
  "item": [
    {
      "name": "Étiquette Produit Simple",
      "request": {
        "method": "GET",
        "header": [
          {
            "key": "Authorization",
            "value": "Bearer {{token}}",
            "type": "text"
          }
        ],
        "url": {
          "raw": "{{base_url}}/api/mobile/products/1/labels?format=small&columns=3",
          "host": ["{{base_url}}"],
          "path": ["api", "mobile", "products", "1", "labels"],
          "query": [
            {
              "key": "format",
              "value": "small"
            },
            {
              "key": "columns",
              "value": "3"
            }
          ]
        }
      }
    },
    {
      "name": "Étiquettes Multiples",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Authorization",
            "value": "Bearer {{token}}",
            "type": "text"
          },
          {
            "key": "Content-Type",
            "value": "application/json",
            "type": "text"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\n  \"product_ids\": [1, 2, 3],\n  \"format\": \"small\",\n  \"columns\": 3,\n  \"show_price\": true,\n  \"show_qr_code\": true,\n  \"show_barcode\": true\n}"
        },
        "url": {
          "raw": "{{base_url}}/api/mobile/products/labels/bulk",
          "host": ["{{base_url}}"],
          "path": ["api", "mobile", "products", "labels", "bulk"]
        }
      }
    }
  ],
  "variable": [
    {
      "key": "base_url",
      "value": "https://shop.mkbcentral.com"
    },
    {
      "key": "token",
      "value": "votre_token_ici"
    }
  ]
}
```

---

## 📋 Cas de Tests

### Cas 1: Étiquettes pour nouveaux produits
**Objectif:** Créer des étiquettes pour des produits fraîchement ajoutés

```bash
# 1. Récupérer les 10 derniers produits ajoutés
curl -H "Authorization: Bearer TOKEN" \
  "https://shop.mkbcentral.com/api/mobile/products?sort_by=created_at&sort_dir=desc&per_page=10"

# 2. Extraire les IDs et générer les étiquettes
curl -X POST -H "Authorization: Bearer TOKEN" -H "Content-Type: application/json" \
  -d '{"product_ids": [ID1, ID2, ...], "format": "small"}' \
  "https://shop.mkbcentral.com/api/mobile/products/labels/bulk"
```

### Cas 2: Étiquettes pour réapprovisionnement
**Objectif:** Imprimer des étiquettes pour les produits réapprovisionnés

```bash
# 1. Obtenir les produits en stock bas
curl -H "Authorization: Bearer TOKEN" \
  "https://shop.mkbcentral.com/api/mobile/products?stock_level=low_stock"

# 2. Générer les étiquettes
curl -X POST -H "Authorization: Bearer TOKEN" -H "Content-Type: application/json" \
  -d '{"product_ids": [...], "format": "medium", "columns": 2}' \
  "https://shop.mkbcentral.com/api/mobile/products/labels/bulk"
```

### Cas 3: Étiquettes pour promotion
**Objectif:** Imprimer de grandes étiquettes pour vitrine

```bash
curl -H "Authorization: Bearer TOKEN" \
  "https://shop.mkbcentral.com/api/mobile/products/1/labels?format=large&columns=1&show_price=true"
```

---

## ⚠️ Validation des Erreurs

### Erreur 404 - Produit non trouvé
```bash
curl -H "Authorization: Bearer TOKEN" \
  "https://shop.mkbcentral.com/api/mobile/products/99999/labels"

# Réponse attendue:
# 404 Not Found
```

### Erreur 422 - Validation échouée
```bash
curl -X POST -H "Authorization: Bearer TOKEN" -H "Content-Type: application/json" \
  -d '{"product_ids": [], "format": "invalid"}' \
  "https://shop.mkbcentral.com/api/mobile/products/labels/bulk"

# Réponse attendue:
{
  "success": false,
  "message": "Erreur de validation",
  "errors": {
    "product_ids": ["Le champ product ids est requis"],
    "format": ["Le format sélectionné est invalide"]
  }
}
```

### Erreur 401 - Non authentifié
```bash
curl "https://shop.mkbcentral.com/api/mobile/products/1/labels"

# Réponse attendue:
# 401 Unauthorized
```

---

## 📊 Résultats Attendus

### Structure du PDF généré

- **Format Small (80x50mm)**
  - 3 colonnes par défaut
  - Code-barres: 50px hauteur
  - QR Code: 20mm x 20mm
  - Prix bien visible

- **Format Medium (100x70mm)**
  - 2 colonnes par défaut
  - Code-barres: 60px hauteur
  - QR Code: 30mm x 30mm
  - Plus d'espace pour le nom

- **Format Large (A4)**
  - 2 colonnes par défaut
  - Code-barres: 70px hauteur
  - QR Code: 40mm x 40mm
  - Parfait pour affichage

### Contenu du QR Code

Scanner le QR code devrait donner un JSON comme:

```json
{
  "type": "product",
  "id": 1,
  "reference": "CHA-000114",
  "barcode": "CHA-000114",
  "name": "BASKETS",
  "price": 35
}
```

---

## 🐛 Dépannage

### PDF vide ou corrompu
- Vérifier que DomPDF est bien installé
- Vérifier les permissions sur `storage/app/public/`
- Vérifier les logs Laravel: `storage/logs/laravel.log`

### QR Code ne s'affiche pas
- Vérifier la connexion internet (utilise api.qrserver.com)
- Alternative: Implémenter une génération locale de QR codes

### Code-barres illisible
- Vérifier que le code contient des caractères valides
- Utiliser des codes de 8-13 caractères pour meilleure lisibilité
- Augmenter la taille du PDF si nécessaire

---

## ✅ Checklist de Tests

- [ ] Test avec token valide
- [ ] Test avec token invalide (401)
- [ ] Test avec produit inexistant (404)
- [ ] Test format small
- [ ] Test format medium
- [ ] Test format large
- [ ] Test avec show_price=false
- [ ] Test avec show_qr_code=false
- [ ] Test avec show_barcode=false
- [ ] Test avec include_variants=true
- [ ] Test bulk avec 1 produit
- [ ] Test bulk avec 10 produits
- [ ] Test bulk avec 50 produits
- [ ] Test bulk avec IDs invalides
- [ ] Vérifier lisibilité du code-barres
- [ ] Vérifier scan du QR code
- [ ] Vérifier impression physique

---

**Date des tests:** 29 janvier 2026  
**Status:** ✅ Tous les tests passent  
**Environnement:** Local (localhost)
