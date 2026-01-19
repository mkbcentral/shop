# API Mobile - Checkout / Facturation

## Vue d'ensemble

L'API de checkout mobile permet de créer des ventes depuis l'application mobile, avec validation du stock, gestion des remises, et génération automatique de factures.

## Endpoints

### 1. Valider le panier

**Endpoint:** `POST /api/mobile/checkout/validate`

Valide un panier avant de procéder au checkout (vérification du stock, des limites de remise, calcul des totaux).

**Request Body:**
```json
{
  "items": [
    {
      "variant_id": 1,
      "quantity": 2,
      "price": 100.00
    }
  ],
  "discount": 10.00,
  "tax": 5.00
}
```

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "is_valid": true,
    "stock_validation": {
      "valid": true
    },
    "discount_validation": {
      "valid": true
    },
    "totals": {
      "subtotal": 200.00,
      "discount": 10.00,
      "tax": 5.00,
      "total": 195.00
    }
  }
}
```

**Response Error - Stock insuffisant:**
```json
{
  "success": true,
  "data": {
    "is_valid": false,
    "stock_validation": {
      "valid": false,
      "product_name": "Produit XYZ",
      "requested": 5,
      "available": 2
    },
    "discount_validation": {
      "valid": true
    },
    "totals": {
      "subtotal": 500.00,
      "discount": 0,
      "tax": 0,
      "total": 500.00
    }
  }
}
```

**Response Error - Remise trop élevée:**
```json
{
  "success": true,
  "data": {
    "is_valid": false,
    "stock_validation": {
      "valid": true
    },
    "discount_validation": {
      "valid": false,
      "message": "La remise ne peut pas dépasser 50 CDF",
      "max_allowed": 50.00,
      "requested": 100.00
    },
    "totals": {
      "subtotal": 200.00,
      "discount": 100.00,
      "tax": 0,
      "total": 100.00
    }
  }
}
```

---

### 2. Créer une vente (Checkout)

**Endpoint:** `POST /api/mobile/checkout`

Crée une vente complète avec facture.

**Request Body:**
```json
{
  "items": [
    {
      "variant_id": 1,
      "quantity": 2,
      "price": 100.00
    },
    {
      "variant_id": 2,
      "quantity": 1,
      "price": 50.00
    }
  ],
  "client_id": 5,
  "payment_method": "cash",
  "paid_amount": 250.00,
  "discount": 0,
  "tax": 0,
  "notes": "Vente mobile",
  "store_id": 1
}
```

**Paramètres:**
- `items` (array, required) - Liste des produits
  - `variant_id` (int, required) - ID de la variante du produit
  - `quantity` (int, required) - Quantité (minimum 1)
  - `price` (float, required) - Prix unitaire
- `client_id` (int, optional) - ID du client
- `payment_method` (string, required) - Méthode de paiement: `cash`, `mobile_money`, `card`, `bank_transfer`
- `paid_amount` (float, required) - Montant payé
- `discount` (float, optional) - Remise totale (défaut: 0)
- `tax` (float, optional) - Taxe totale (défaut: 0)
- `notes` (string, optional) - Notes sur la vente
- `store_id` (int, optional) - ID du magasin (utilise le store actuel si non fourni)

**Response Success (201):**
```json
{
  "success": true,
  "message": "Vente créée avec succès",
  "data": {
    "sale": {
      "id": 42,
      "reference": "VT-S1-2026-01-0042",
      "total": 250.00,
      "discount": 0,
      "tax": 0,
      "payment_method": "cash",
      "payment_status": "paid",
      "status": "completed",
      "sale_date": "2026-01-19T14:30:00+00:00",
      "items_count": 2
    },
    "invoice": {
      "id": 42,
      "invoice_number": "INV-2026-00042",
      "invoice_date": "2026-01-19T14:30:00+00:00",
      "due_date": null,
      "status": "paid"
    },
    "change": 0,
    "subtotal": 250.00,
    "discount": 0,
    "tax": 0,
    "total": 250.00,
    "paid_amount": 250.00
  }
}
```

**Response Error - Validation (422):**
```json
{
  "success": false,
  "message": "Données invalides",
  "errors": {
    "items": ["Le champ items est obligatoire"],
    "payment_method": ["Le mode de paiement est invalide"]
  }
}
```

**Response Error - Stock insuffisant (400):**
```json
{
  "success": false,
  "message": "Stock insuffisant pour Produit XYZ. Demandé: 5, Disponible: 2",
  "product": "Produit XYZ",
  "requested": 5,
  "available": 2
}
```

**Response Error - Montant insuffisant (400):**
```json
{
  "success": false,
  "message": "Montant payé insuffisant",
  "required": 250.00,
  "provided": 200.00
}
```

**Response Error - Accès refusé (403):**
```json
{
  "success": false,
  "message": "Vous n'avez pas accès à ce magasin"
}
```

---

### 3. Historique des ventes

**Endpoint:** `GET /api/mobile/sales`

Récupère l'historique des ventes avec pagination.

**Query Parameters:**
- `per_page` (int, optional) - Nombre de résultats par page (min: 10, max: 100, défaut: 20)
- `page` (int, optional) - Numéro de page (défaut: 1)
- `date_from` (date, optional) - Date de début (format: YYYY-MM-DD)
- `date_to` (date, optional) - Date de fin (format: YYYY-MM-DD)
- `payment_method` (string, optional) - Filtrer par méthode de paiement
- `status` (string, optional) - Filtrer par statut
- `store_id` (int, optional) - Filtrer par magasin

**Exemple:**
```
GET /api/mobile/sales?per_page=20&page=1&date_from=2026-01-01&payment_method=cash&store_id=1
```

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "sales": [
      {
        "id": 42,
        "reference": "VT-S1-2026-01-0042",
        "sale_date": "2026-01-19T14:30:00+00:00",
        "total": 250.00,
        "discount": 0,
        "tax": 0,
        "payment_method": "cash",
        "payment_status": "paid",
        "status": "completed",
        "notes": "Vente mobile",
        "client": {
          "id": 5,
          "name": "Client Test",
          "phone": "+243999999999"
        },
        "cashier": {
          "id": 8,
          "name": "John Doe"
        },
        "store": {
          "id": 1,
          "name": "Magasin Principal",
          "code": "MAIN-1"
        },
        "items": [
          {
            "id": 1,
            "product_name": "Produit A",
            "variant": {
              "size": "M",
              "color": "Bleu"
            },
            "quantity": 2,
            "price": 100.00,
            "subtotal": 200.00
          }
        ],
        "items_count": 2,
        "invoice": {
          "id": 42,
          "invoice_number": "INV-2026-00042",
          "status": "paid"
        }
      }
    ],
    "pagination": {
      "current_page": 1,
      "last_page": 5,
      "per_page": 20,
      "total": 98
    }
  }
}
```

---

### 4. Détail d'une vente

**Endpoint:** `GET /api/mobile/sales/{id}`

Récupère les détails complets d'une vente spécifique.

**Exemple:**
```
GET /api/mobile/sales/42
```

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "id": 42,
    "reference": "VT-S1-2026-01-0042",
    "sale_date": "2026-01-19T14:30:00+00:00",
    "total": 250.00,
    "discount": 0,
    "tax": 0,
    "payment_method": "cash",
    "payment_status": "paid",
    "status": "completed",
    "notes": "Vente mobile",
    "client": {
      "id": 5,
      "name": "Client Test",
      "phone": "+243999999999"
    },
    "cashier": {
      "id": 8,
      "name": "John Doe"
    },
    "store": {
      "id": 1,
      "name": "Magasin Principal",
      "code": "MAIN-1"
    },
    "items": [
      {
        "id": 1,
        "product_name": "Produit A",
        "variant": {
          "size": "M",
          "color": "Bleu"
        },
        "quantity": 2,
        "price": 100.00,
        "subtotal": 200.00
      }
    ],
    "items_count": 2,
    "invoice": {
      "id": 42,
      "invoice_number": "INV-2026-00042",
      "status": "paid"
    }
  }
}
```

**Response Error - Non trouvé (404):**
```json
{
  "success": false,
  "message": "Vente non trouvée"
}
```

**Response Error - Accès refusé (403):**
```json
{
  "success": false,
  "message": "Accès non autorisé"
}
```

---

## Fonctionnalités

### Validation du stock
Le système vérifie automatiquement la disponibilité du stock avant de créer une vente. Si le stock est insuffisant, la vente est rejetée.

### Validation des remises
Si un produit a une `max_discount_amount` définie, le système s'assure que la remise totale ne dépasse pas la limite configurée.

### Support multi-store
Vous pouvez spécifier le `store_id` dans la requête pour créer une vente dans un magasin spécifique. Le système valide que l'utilisateur a accès au magasin demandé.

### Génération automatique de factures
Chaque vente génère automatiquement une facture avec un numéro unique.

### Mouvements de stock
Les mouvements de stock sont enregistrés automatiquement lors de la création d'une vente.

---

## Cas d'usage Flutter

### 1. Valider le panier avant checkout

```dart
Future<Map<String, dynamic>> validateCart(List<CartItem> items, double discount, double tax) async {
  final response = await http.post(
    Uri.parse('$baseUrl/api/mobile/checkout/validate'),
    headers: {
      'Authorization': 'Bearer $token',
      'Content-Type': 'application/json',
    },
    body: jsonEncode({
      'items': items.map((item) => {
        'variant_id': item.variantId,
        'quantity': item.quantity,
        'price': item.price,
      }).toList(),
      'discount': discount,
      'tax': tax,
    }),
  );

  return jsonDecode(response.body);
}
```

### 2. Créer une vente

```dart
Future<Map<String, dynamic>> createSale({
  required List<CartItem> items,
  required int clientId,
  required String paymentMethod,
  required double paidAmount,
  double discount = 0,
  double tax = 0,
  String? notes,
  int? storeId,
}) async {
  final response = await http.post(
    Uri.parse('$baseUrl/api/mobile/checkout'),
    headers: {
      'Authorization': 'Bearer $token',
      'Content-Type': 'application/json',
    },
    body: jsonEncode({
      'items': items.map((item) => {
        'variant_id': item.variantId,
        'quantity': item.quantity,
        'price': item.price,
      }).toList(),
      'client_id': clientId,
      'payment_method': paymentMethod,
      'paid_amount': paidAmount,
      'discount': discount,
      'tax': tax,
      'notes': notes,
      'store_id': storeId,
    }),
  );

  if (response.statusCode == 201) {
    return jsonDecode(response.body);
  } else {
    throw Exception('Erreur lors de la création de la vente');
  }
}
```

### 3. Récupérer l'historique

```dart
Future<Map<String, dynamic>> getSalesHistory({
  int page = 1,
  int perPage = 20,
  String? dateFrom,
  String? dateTo,
  String? paymentMethod,
  int? storeId,
}) async {
  final queryParams = {
    'page': page.toString(),
    'per_page': perPage.toString(),
    if (dateFrom != null) 'date_from': dateFrom,
    if (dateTo != null) 'date_to': dateTo,
    if (paymentMethod != null) 'payment_method': paymentMethod,
    if (storeId != null) 'store_id': storeId.toString(),
  };

  final uri = Uri.parse('$baseUrl/api/mobile/sales').replace(queryParameters: queryParams);
  
  final response = await http.get(
    uri,
    headers: {
      'Authorization': 'Bearer $token',
    },
  );

  return jsonDecode(response.body);
}
```

---

## Notes importantes

1. **Authentification requise** : Tous les endpoints nécessitent un token Bearer valide
2. **Gestion des erreurs** : Toujours vérifier le champ `success` dans la réponse
3. **Validation côté client** : Validez le panier avant de soumettre pour une meilleure UX
4. **Store filtering** : Le `store_id` dans la requête permet de basculer entre magasins
5. **Limites de remise** : Le système respecte les `max_discount_amount` configurés sur les produits
