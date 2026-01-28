# 📋 Guide d'Intégration API Mobile - Gestion des Proformas (Devis)

## 📝 Vue d'ensemble

Ce guide explique comment intégrer la gestion complète des proformas (devis) dans votre application Flutter, avec toutes les fonctionnalités disponibles dans l'interface web Livewire.

### Fonctionnalités couvertes

- ✅ Lister les proformas avec filtres (recherche, statut, période)
- ✅ Créer un nouveau proforma avec items
- ✅ Modifier un proforma existant
- ✅ Voir les détails d'un proforma
- ✅ Supprimer un proforma
- ✅ Changer le statut (brouillon, envoyé, accepté, rejeté)
- ✅ Convertir un proforma accepté en facture/vente
- ✅ Dupliquer un proforma
- ✅ Rechercher des produits pour ajouter aux items
- ✅ Statistiques des proformas

---

## 🎯 Workflow Utilisateur

```
┌─────────────────────────────────────────────────────────────┐
│                    ÉCRAN LISTE PROFORMAS                     │
│  • Recherche par numéro, client, téléphone, email           │
│  • Filtres: statut, période (aujourd'hui, cette semaine...)  │
│  • Tri: date, montant, statut                               │
│  • Actions: Voir, Modifier, Supprimer, Dupliquer            │
└─────────────────────────────────────────────────────────────┘
                              ↓
              ┌───────────────┴────────────────┐
              ↓                                ↓
┌─────────────────────────┐      ┌────────────────────────────┐
│   CRÉER UN PROFORMA     │      │   DÉTAILS DU PROFORMA      │
│ • Info client           │      │ • Informations complètes   │
│ • Date & validité       │      │ • Liste des items          │
│ • Recherche produits    │      │ • Totaux                   │
│ • Quantité/Prix/Remise  │      │ • Actions disponibles      │
│ • Notes & conditions    │      └────────────────────────────┘
└─────────────────────────┘                   ↓
              ↓                    ┌───────────┴────────────┐
┌─────────────────────────┐        ↓                        ↓
│   MODIFIER PROFORMA     │  ┌─────────────┐    ┌──────────────────┐
│ • Même formulaire       │  │  CHANGER    │    │  CONVERTIR EN    │
│ • Éditer items          │  │  STATUT     │    │  FACTURE/VENTE   │
│ • Recalcul totaux       │  └─────────────┘    └──────────────────┘
└─────────────────────────┘
```

---

## 📡 Endpoints API

### Base URL
```
https://your-api.com/api/mobile/proformas
```

### Authentication
Toutes les requêtes nécessitent un token Bearer (Sanctum).

```dart
headers: {
  'Authorization': 'Bearer $token',
  'Accept': 'application/json',
  'Content-Type': 'application/json',
}
```

---

## 🔌 Endpoints Détaillés

### 1. Liste des Proformas (avec filtres et pagination)

**Endpoint:** `GET /api/mobile/proformas`

**Query Parameters:**
- `per_page` (int, optional): Nombre d'éléments par page (10-100, défaut: 20)
- `search` (string, optional): Recherche dans numéro, nom client, téléphone, email
- `status` (string, optional): Filtrer par statut
  - `draft` - Brouillon
  - `sent` - Envoyé
  - `accepted` - Accepté
  - `rejected` - Rejeté
  - `converted` - Converti
  - `expired` - Expiré
- `period` (string, optional): Filtre de période prédéfini
  - `today` - Aujourd'hui
  - `yesterday` - Hier
  - `this_week` - Cette semaine
  - `last_week` - La semaine dernière
  - `this_month` - Ce mois-ci
  - `last_month` - Le mois dernier
  - `last_3_months` - 3 derniers mois
  - `this_year` - Cette année
- `date_from` (date, optional): Date début (YYYY-MM-DD)
- `date_to` (date, optional): Date fin (YYYY-MM-DD)
- `sort_by` (string, optional): Champ de tri (défaut: `proforma_date`)
  - `proforma_date`, `total`, `client_name`, `status`
- `sort_dir` (string, optional): Direction du tri (défaut: `desc`)
  - `asc`, `desc`

**Exemple de Requête:**
```dart
final response = await http.get(
  Uri.parse('$baseUrl/proformas?status=sent&period=this_month&sort_by=total&sort_dir=desc'),
  headers: headers,
);
```

**Réponse Success (200):**
```json
{
  "success": true,
  "data": {
    "proformas": [
      {
        "id": 1,
        "proforma_number": "PRO-202501-0001",
        "client_name": "Entreprise ABC",
        "client_phone": "+225 07 12 34 56 78",
        "client_email": "contact@abc.com",
        "proforma_date": "2025-01-15",
        "valid_until": "2025-02-14",
        "subtotal": 150000,
        "tax_amount": 0,
        "discount": 0,
        "total": 150000,
        "status": "sent",
        "status_label": "Envoyé",
        "is_expired": false,
        "user": {
          "id": 1,
          "name": "John Doe"
        },
        "store": {
          "id": 1,
          "name": "Magasin Principal"
        },
        "items_count": 3,
        "created_at": "2025-01-15T10:30:00Z"
      }
    ],
    "pagination": {
      "current_page": 1,
      "last_page": 5,
      "per_page": 20,
      "total": 95
    }
  }
}
```

---

### 2. Détails d'un Proforma

**Endpoint:** `GET /api/mobile/proformas/{id}`

**Réponse Success (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "proforma_number": "PRO-202501-0001",
    "client_name": "Entreprise ABC",
    "client_phone": "+225 07 12 34 56 78",
    "client_email": "contact@abc.com",
    "client_address": "Rue des Entrepreneurs, Abidjan",
    "proforma_date": "2025-01-15",
    "valid_until": "2025-02-14",
    "subtotal": 150000,
    "tax_amount": 0,
    "discount": 0,
    "total": 150000,
    "status": "sent",
    "status_label": "Envoyé",
    "is_expired": false,
    "notes": "Livraison gratuite pour commande > 100 000 FCFA",
    "terms_conditions": "Paiement sous 30 jours",
    "converted_to_invoice_id": null,
    "converted_at": null,
    "user": {
      "id": 1,
      "name": "John Doe"
    },
    "store": {
      "id": 1,
      "name": "Magasin Principal"
    },
    "items_count": 3,
    "items": [
      {
        "id": 1,
        "product_variant_id": 10,
        "description": "Ordinateur Portable HP - i5 8Go 256SSD",
        "quantity": 2,
        "unit_price": 500000,
        "discount": 50000,
        "total": 950000,
        "product_variant": {
          "id": 10,
          "sku": "HP-LAP-001",
          "name": "Ordinateur Portable HP - i5 8Go 256SSD",
          "stock": 15,
          "product": {
            "id": 5,
            "name": "Ordinateur Portable HP"
          }
        }
      },
      {
        "id": 2,
        "product_variant_id": null,
        "description": "Service d'installation et configuration",
        "quantity": 1,
        "unit_price": 50000,
        "discount": 0,
        "total": 50000,
        "product_variant": null
      }
    ],
    "created_at": "2025-01-15T10:30:00Z",
    "updated_at": "2025-01-15T10:30:00Z"
  }
}
```

---

### 3. Créer un Proforma

**Endpoint:** `POST /api/mobile/proformas`

**Body:**
```json
{
  "client_name": "Entreprise XYZ",
  "client_phone": "+225 07 98 76 54 32",
  "client_email": "contact@xyz.com",
  "client_address": "Boulevard du Commerce, Abidjan",
  "proforma_date": "2025-01-20",
  "valid_until": "2025-02-19",
  "notes": "Remise spéciale client fidèle",
  "terms_conditions": "Paiement à la commande",
  "items": [
    {
      "product_variant_id": 10,
      "description": "Ordinateur Portable HP - i5 8Go 256SSD",
      "quantity": 3,
      "unit_price": 500000,
      "discount": 30000
    },
    {
      "product_variant_id": null,
      "description": "Formation utilisateurs (3h)",
      "quantity": 1,
      "unit_price": 75000,
      "discount": 0
    }
  ]
}
```

**Validation:**
- `client_name`: requis, max 255 caractères
- `client_phone`: optionnel, max 50 caractères
- `client_email`: optionnel, email valide, max 255 caractères
- `client_address`: optionnel, max 500 caractères
- `proforma_date`: requis, format date
- `valid_until`: requis, date >= proforma_date
- `notes`: optionnel, texte
- `terms_conditions`: optionnel, texte
- `items`: requis, array avec minimum 1 item
  - `product_variant_id`: optionnel, doit exister si fourni
  - `description`: requis, texte
  - `quantity`: requis, numérique >= 0.01
  - `unit_price`: requis, numérique >= 0
  - `discount`: optionnel, numérique >= 0

**Réponse Success (201):**
```json
{
  "success": true,
  "message": "Proforma créé avec succès",
  "data": {
    // ... détails complets du proforma créé
  }
}
```

**Réponse Error (422):**
```json
{
  "success": false,
  "message": "Erreur de validation",
  "errors": {
    "client_name": ["Le nom du client est requis"],
    "items": ["Au moins un article est requis"]
  }
}
```

---

### 4. Modifier un Proforma

**Endpoint:** `PUT /api/mobile/proformas/{id}`

**Body:** Mêmes champs que la création (tous optionnels sauf `items` si fourni)

**Règles:**
- Les proformas convertis ou expirés ne peuvent pas être modifiés
- Si `items` est fourni, tous les anciens items sont supprimés et remplacés
- Les totaux sont recalculés automatiquement

**Réponse Success (200):**
```json
{
  "success": true,
  "message": "Proforma modifié avec succès",
  "data": {
    // ... détails complets du proforma modifié
  }
}
```

**Réponse Error (400):**
```json
{
  "success": false,
  "message": "Ce proforma ne peut plus être modifié"
}
```

---

### 5. Supprimer un Proforma

**Endpoint:** `DELETE /api/mobile/proformas/{id}`

**Règles:**
- Les proformas convertis ne peuvent PAS être supprimés
- Les autres statuts peuvent être supprimés

**Réponse Success (200):**
```json
{
  "success": true,
  "message": "Proforma PRO-202501-0001 supprimé avec succès"
}
```

**Réponse Error (400):**
```json
{
  "success": false,
  "message": "Un proforma converti ne peut pas être supprimé"
}
```

---

### 6. Changer le Statut

**Endpoint:** `POST /api/mobile/proformas/{id}/change-status`

**Body:**
```json
{
  "status": "sent"
}
```

**Statuts Valides:**
- `draft` - Brouillon
- `sent` - Envoyé
- `accepted` - Accepté
- `rejected` - Rejeté
- `expired` - Expiré

**Règles:**
- Un proforma converti ne peut plus changer de statut

**Réponse Success (200):**
```json
{
  "success": true,
  "message": "Statut modifié avec succès",
  "data": {
    // ... détails du proforma mis à jour
  }
}
```

---

### 7. Convertir en Facture/Vente

**Endpoint:** `POST /api/mobile/proformas/{id}/convert-to-sale`

**Règles:**
- Seuls les proformas **acceptés** peuvent être convertis
- Crée automatiquement:
  - Une vente (Sale)
  - Une facture (Invoice)
  - Les items associés
- Le proforma passe au statut `converted`

**Réponse Success (200):**
```json
{
  "success": true,
  "message": "Proforma converti en facture avec succès",
  "data": {
    "invoice_id": 42,
    "invoice_number": "INV-202501-0042",
    "sale_id": 128,
    "proforma": {
      // ... détails du proforma avec status=converted
    }
  }
}
```

**Réponse Error (400):**
```json
{
  "success": false,
  "message": "Seuls les proformas acceptés peuvent être convertis"
}
```

---

### 8. Dupliquer un Proforma

**Endpoint:** `POST /api/mobile/proformas/{id}/duplicate`

**Action:**
- Crée une copie exacte du proforma
- Nouveau numéro généré automatiquement
- Statut: `draft`
- Date: aujourd'hui
- Validité: +30 jours
- Copie tous les items

**Réponse Success (200):**
```json
{
  "success": true,
  "message": "Proforma dupliqué avec succès",
  "data": {
    // ... détails complets du nouveau proforma
  }
}
```

---

### 9. Rechercher des Produits

**Endpoint:** `GET /api/mobile/proformas/search-products`

**Query Parameters:**
- `q` (string, required): Terme de recherche (minimum 2 caractères)
- `limit` (int, optional): Nombre de résultats (5-50, défaut: 10)

**Exemple:**
```dart
final response = await http.get(
  Uri.parse('$baseUrl/proformas/search-products?q=ordinateur&limit=20'),
  headers: headers,
);
```

**Réponse Success (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 10,
      "name": "Ordinateur Portable HP - i5 8Go 256SSD",
      "price": 500000,
      "stock": 15,
      "sku": "HP-LAP-001",
      "product": {
        "id": 5,
        "name": "Ordinateur Portable HP",
        "category": "Informatique"
      }
    }
  ]
}
```

---

### 10. Statistiques des Proformas

**Endpoint:** `GET /api/mobile/proformas/statistics`

**Query Parameters:**
- `period` (string, optional): Période prédéfinie (même que liste)
- `date_from` (date, optional): Date début
- `date_to` (date, optional): Date fin

**Réponse Success (200):**
```json
{
  "success": true,
  "data": {
    "total_count": 156,
    "total_amount": 48500000,
    "by_status": {
      "draft": {
        "count": 12,
        "amount": 3200000
      },
      "sent": {
        "count": 45,
        "amount": 15800000
      },
      "accepted": {
        "count": 38,
        "amount": 18500000
      },
      "rejected": {
        "count": 8,
        "amount": 2100000
      },
      "converted": {
        "count": 48,
        "amount": 23400000
      },
      "expired": {
        "count": 5,
        "amount": 1500000
      }
    },
    "conversion_rate": 55.13
  }
}
```

---

## 🎨 Implémentation Flutter

### Structure de Projet Recommandée

```
lib/
├── models/
│   ├── proforma.dart
│   └── proforma_item.dart
├── services/
│   └── proforma_service.dart
├── providers/
│   └── proforma_provider.dart
└── screens/
    ├── proforma_list_screen.dart
    ├── proforma_detail_screen.dart
    └── proforma_form_screen.dart
```

---

### 1. Models

#### `lib/models/proforma.dart`
```dart
class Proforma {
  final int id;
  final String proformaNumber;
  final String clientName;
  final String? clientPhone;
  final String? clientEmail;
  final String? clientAddress;
  final DateTime proformaDate;
  final DateTime validUntil;
  final double subtotal;
  final double taxAmount;
  final double discount;
  final double total;
  final String status;
  final String statusLabel;
  final bool isExpired;
  final User? user;
  final Store? store;
  final int itemsCount;
  final List<ProformaItem>? items;
  final String? notes;
  final String? termsConditions;
  final int? convertedToInvoiceId;
  final DateTime? convertedAt;
  final DateTime createdAt;
  final DateTime? updatedAt;

  Proforma({
    required this.id,
    required this.proformaNumber,
    required this.clientName,
    this.clientPhone,
    this.clientEmail,
    this.clientAddress,
    required this.proformaDate,
    required this.validUntil,
    required this.subtotal,
    required this.taxAmount,
    required this.discount,
    required this.total,
    required this.status,
    required this.statusLabel,
    required this.isExpired,
    this.user,
    this.store,
    required this.itemsCount,
    this.items,
    this.notes,
    this.termsConditions,
    this.convertedToInvoiceId,
    this.convertedAt,
    required this.createdAt,
    this.updatedAt,
  });

  factory Proforma.fromJson(Map<String, dynamic> json) {
    return Proforma(
      id: json['id'],
      proformaNumber: json['proforma_number'],
      clientName: json['client_name'],
      clientPhone: json['client_phone'],
      clientEmail: json['client_email'],
      clientAddress: json['client_address'],
      proformaDate: DateTime.parse(json['proforma_date']),
      validUntil: DateTime.parse(json['valid_until']),
      subtotal: double.parse(json['subtotal'].toString()),
      taxAmount: double.parse(json['tax_amount'].toString()),
      discount: double.parse(json['discount'].toString()),
      total: double.parse(json['total'].toString()),
      status: json['status'],
      statusLabel: json['status_label'],
      isExpired: json['is_expired'],
      user: json['user'] != null ? User.fromJson(json['user']) : null,
      store: json['store'] != null ? Store.fromJson(json['store']) : null,
      itemsCount: json['items_count'],
      items: json['items'] != null
          ? (json['items'] as List)
              .map((item) => ProformaItem.fromJson(item))
              .toList()
          : null,
      notes: json['notes'],
      termsConditions: json['terms_conditions'],
      convertedToInvoiceId: json['converted_to_invoice_id'],
      convertedAt: json['converted_at'] != null
          ? DateTime.parse(json['converted_at'])
          : null,
      createdAt: DateTime.parse(json['created_at']),
      updatedAt: json['updated_at'] != null
          ? DateTime.parse(json['updated_at'])
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'proforma_number': proformaNumber,
      'client_name': clientName,
      'client_phone': clientPhone,
      'client_email': clientEmail,
      'client_address': clientAddress,
      'proforma_date': proformaDate.toIso8601String().split('T')[0],
      'valid_until': validUntil.toIso8601String().split('T')[0],
      'subtotal': subtotal,
      'tax_amount': taxAmount,
      'discount': discount,
      'total': total,
      'status': status,
      'status_label': statusLabel,
      'is_expired': isExpired,
      'items_count': itemsCount,
      'notes': notes,
      'terms_conditions': termsConditions,
    };
  }

  // Helpers
  bool get canBeEdited => status != 'converted' && status != 'expired';
  bool get canBeDeleted => status != 'converted';
  bool get canBeConverted => status == 'accepted';
  
  Color get statusColor {
    switch (status) {
      case 'draft':
        return Colors.grey;
      case 'sent':
        return Colors.blue;
      case 'accepted':
        return Colors.green;
      case 'rejected':
        return Colors.red;
      case 'converted':
        return Colors.purple;
      case 'expired':
        return Colors.orange;
      default:
        return Colors.grey;
    }
  }
}
```

#### `lib/models/proforma_item.dart`
```dart
class ProformaItem {
  final int? id;
  final int? productVariantId;
  final String description;
  final double quantity;
  final double unitPrice;
  final double discount;
  final double total;
  final ProductVariant? productVariant;

  ProformaItem({
    this.id,
    this.productVariantId,
    required this.description,
    required this.quantity,
    required this.unitPrice,
    this.discount = 0,
    required this.total,
    this.productVariant,
  });

  factory ProformaItem.fromJson(Map<String, dynamic> json) {
    return ProformaItem(
      id: json['id'],
      productVariantId: json['product_variant_id'],
      description: json['description'],
      quantity: double.parse(json['quantity'].toString()),
      unitPrice: double.parse(json['unit_price'].toString()),
      discount: double.parse(json['discount']?.toString() ?? '0'),
      total: double.parse(json['total'].toString()),
      productVariant: json['product_variant'] != null
          ? ProductVariant.fromJson(json['product_variant'])
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'product_variant_id': productVariantId,
      'description': description,
      'quantity': quantity,
      'unit_price': unitPrice,
      'discount': discount,
    };
  }

  // Calculer le total d'un item
  static double calculateItemTotal(double quantity, double unitPrice, double discount) {
    return (quantity * unitPrice) - discount;
  }
}
```

---

### 2. Service

#### `lib/services/proforma_service.dart`
```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

class ProformaService {
  final String baseUrl;
  final String token;

  ProformaService({
    required this.baseUrl,
    required this.token,
  });

  Map<String, String> get _headers => {
        'Authorization': 'Bearer $token',
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      };

  /// Liste des proformas avec filtres
  Future<Map<String, dynamic>> getProformas({
    int page = 1,
    int perPage = 20,
    String? search,
    String? status,
    String? period,
    String? dateFrom,
    String? dateTo,
    String sortBy = 'proforma_date',
    String sortDir = 'desc',
  }) async {
    final queryParams = <String, String>{
      'page': page.toString(),
      'per_page': perPage.toString(),
      'sort_by': sortBy,
      'sort_dir': sortDir,
    };

    if (search != null && search.isNotEmpty) queryParams['search'] = search;
    if (status != null && status.isNotEmpty) queryParams['status'] = status;
    if (period != null && period.isNotEmpty) queryParams['period'] = period;
    if (dateFrom != null) queryParams['date_from'] = dateFrom;
    if (dateTo != null) queryParams['date_to'] = dateTo;

    final uri = Uri.parse('$baseUrl/proformas').replace(queryParameters: queryParams);
    final response = await http.get(uri, headers: _headers);

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return {
        'proformas': (data['data']['proformas'] as List)
            .map((p) => Proforma.fromJson(p))
            .toList(),
        'pagination': data['data']['pagination'],
      };
    } else {
      throw Exception('Erreur lors du chargement des proformas');
    }
  }

  /// Détails d'un proforma
  Future<Proforma> getProforma(int id) async {
    final response = await http.get(
      Uri.parse('$baseUrl/proformas/$id'),
      headers: _headers,
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return Proforma.fromJson(data['data']);
    } else {
      throw Exception('Erreur lors du chargement du proforma');
    }
  }

  /// Créer un proforma
  Future<Proforma> createProforma({
    required String clientName,
    String? clientPhone,
    String? clientEmail,
    String? clientAddress,
    required DateTime proformaDate,
    required DateTime validUntil,
    String? notes,
    String? termsConditions,
    required List<ProformaItem> items,
  }) async {
    final body = {
      'client_name': clientName,
      'client_phone': clientPhone,
      'client_email': clientEmail,
      'client_address': clientAddress,
      'proforma_date': proformaDate.toIso8601String().split('T')[0],
      'valid_until': validUntil.toIso8601String().split('T')[0],
      'notes': notes,
      'terms_conditions': termsConditions,
      'items': items.map((item) => item.toJson()).toList(),
    };

    final response = await http.post(
      Uri.parse('$baseUrl/proformas'),
      headers: _headers,
      body: json.encode(body),
    );

    if (response.statusCode == 201) {
      final data = json.decode(response.body);
      return Proforma.fromJson(data['data']);
    } else {
      final errorData = json.decode(response.body);
      throw Exception(errorData['message'] ?? 'Erreur lors de la création');
    }
  }

  /// Modifier un proforma
  Future<Proforma> updateProforma({
    required int id,
    String? clientName,
    String? clientPhone,
    String? clientEmail,
    String? clientAddress,
    DateTime? proformaDate,
    DateTime? validUntil,
    String? notes,
    String? termsConditions,
    List<ProformaItem>? items,
  }) async {
    final body = <String, dynamic>{};
    
    if (clientName != null) body['client_name'] = clientName;
    if (clientPhone != null) body['client_phone'] = clientPhone;
    if (clientEmail != null) body['client_email'] = clientEmail;
    if (clientAddress != null) body['client_address'] = clientAddress;
    if (proformaDate != null) {
      body['proforma_date'] = proformaDate.toIso8601String().split('T')[0];
    }
    if (validUntil != null) {
      body['valid_until'] = validUntil.toIso8601String().split('T')[0];
    }
    if (notes != null) body['notes'] = notes;
    if (termsConditions != null) body['terms_conditions'] = termsConditions;
    if (items != null) {
      body['items'] = items.map((item) => item.toJson()).toList();
    }

    final response = await http.put(
      Uri.parse('$baseUrl/proformas/$id'),
      headers: _headers,
      body: json.encode(body),
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return Proforma.fromJson(data['data']);
    } else {
      final errorData = json.decode(response.body);
      throw Exception(errorData['message'] ?? 'Erreur lors de la modification');
    }
  }

  /// Supprimer un proforma
  Future<void> deleteProforma(int id) async {
    final response = await http.delete(
      Uri.parse('$baseUrl/proformas/$id'),
      headers: _headers,
    );

    if (response.statusCode != 200) {
      final errorData = json.decode(response.body);
      throw Exception(errorData['message'] ?? 'Erreur lors de la suppression');
    }
  }

  /// Changer le statut
  Future<Proforma> changeStatus(int id, String status) async {
    final response = await http.post(
      Uri.parse('$baseUrl/proformas/$id/change-status'),
      headers: _headers,
      body: json.encode({'status': status}),
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return Proforma.fromJson(data['data']);
    } else {
      final errorData = json.decode(response.body);
      throw Exception(errorData['message'] ?? 'Erreur lors du changement de statut');
    }
  }

  /// Convertir en facture/vente
  Future<Map<String, dynamic>> convertToSale(int id) async {
    final response = await http.post(
      Uri.parse('$baseUrl/proformas/$id/convert-to-sale'),
      headers: _headers,
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return data['data'];
    } else {
      final errorData = json.decode(response.body);
      throw Exception(errorData['message'] ?? 'Erreur lors de la conversion');
    }
  }

  /// Dupliquer un proforma
  Future<Proforma> duplicateProforma(int id) async {
    final response = await http.post(
      Uri.parse('$baseUrl/proformas/$id/duplicate'),
      headers: _headers,
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return Proforma.fromJson(data['data']);
    } else {
      final errorData = json.decode(response.body);
      throw Exception(errorData['message'] ?? 'Erreur lors de la duplication');
    }
  }

  /// Rechercher des produits
  Future<List<ProductVariant>> searchProducts(String query, {int limit = 10}) async {
    if (query.length < 2) return [];

    final uri = Uri.parse('$baseUrl/proformas/search-products')
        .replace(queryParameters: {'q': query, 'limit': limit.toString()});

    final response = await http.get(uri, headers: _headers);

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return (data['data'] as List)
          .map((v) => ProductVariant.fromJson(v))
          .toList();
    } else {
      return [];
    }
  }

  /// Statistiques
  Future<Map<String, dynamic>> getStatistics({
    String? period,
    String? dateFrom,
    String? dateTo,
  }) async {
    final queryParams = <String, String>{};
    if (period != null) queryParams['period'] = period;
    if (dateFrom != null) queryParams['date_from'] = dateFrom;
    if (dateTo != null) queryParams['date_to'] = dateTo;

    final uri = Uri.parse('$baseUrl/proformas/statistics')
        .replace(queryParameters: queryParams);

    final response = await http.get(uri, headers: _headers);

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return data['data'];
    } else {
      throw Exception('Erreur lors du chargement des statistiques');
    }
  }
}
```

---

### 3. Provider (avec Riverpod)

#### `lib/providers/proforma_provider.dart`
```dart
import 'package:flutter_riverpod/flutter_riverpod.dart';

// Provider du service
final proformaServiceProvider = Provider<ProformaService>((ref) {
  final token = ref.watch(authTokenProvider);
  return ProformaService(
    baseUrl: 'https://your-api.com/api/mobile',
    token: token,
  );
});

// Provider de la liste des proformas
final proformasProvider = FutureProvider.autoDispose.family<
    Map<String, dynamic>,
    ProformaFilters>((ref, filters) async {
  final service = ref.watch(proformaServiceProvider);
  return service.getProformas(
    page: filters.page,
    perPage: filters.perPage,
    search: filters.search,
    status: filters.status,
    period: filters.period,
    dateFrom: filters.dateFrom,
    dateTo: filters.dateTo,
    sortBy: filters.sortBy,
    sortDir: filters.sortDir,
  );
});

// Provider d'un proforma spécifique
final proformaDetailProvider = FutureProvider.autoDispose.family<Proforma, int>(
  (ref, id) async {
    final service = ref.watch(proformaServiceProvider);
    return service.getProforma(id);
  },
);

// Provider des statistiques
final proformaStatsProvider = FutureProvider.autoDispose.family<
    Map<String, dynamic>,
    StatFilters>((ref, filters) async {
  final service = ref.watch(proformaServiceProvider);
  return service.getStatistics(
    period: filters.period,
    dateFrom: filters.dateFrom,
    dateTo: filters.dateTo,
  );
});

// Classe pour les filtres
class ProformaFilters {
  final int page;
  final int perPage;
  final String? search;
  final String? status;
  final String? period;
  final String? dateFrom;
  final String? dateTo;
  final String sortBy;
  final String sortDir;

  ProformaFilters({
    this.page = 1,
    this.perPage = 20,
    this.search,
    this.status,
    this.period,
    this.dateFrom,
    this.dateTo,
    this.sortBy = 'proforma_date',
    this.sortDir = 'desc',
  });

  ProformaFilters copyWith({
    int? page,
    int? perPage,
    String? search,
    String? status,
    String? period,
    String? dateFrom,
    String? dateTo,
    String? sortBy,
    String? sortDir,
  }) {
    return ProformaFilters(
      page: page ?? this.page,
      perPage: perPage ?? this.perPage,
      search: search ?? this.search,
      status: status ?? this.status,
      period: period ?? this.period,
      dateFrom: dateFrom ?? this.dateFrom,
      dateTo: dateTo ?? this.dateTo,
      sortBy: sortBy ?? this.sortBy,
      sortDir: sortDir ?? this.sortDir,
    );
  }
}

class StatFilters {
  final String? period;
  final String? dateFrom;
  final String? dateTo;

  StatFilters({this.period, this.dateFrom, this.dateTo});
}
```

---

### 4. UI - Liste des Proformas

#### `lib/screens/proforma_list_screen.dart`
```dart
class ProformaListScreen extends ConsumerStatefulWidget {
  @override
  _ProformaListScreenState createState() => _ProformaListScreenState();
}

class _ProformaListScreenState extends ConsumerState<ProformaListScreen> {
  ProformaFilters filters = ProformaFilters();

  @override
  Widget build(BuildContext context) {
    final proformasAsync = ref.watch(proformasProvider(filters));

    return Scaffold(
      appBar: AppBar(
        title: Text('Proformas (Devis)'),
        actions: [
          IconButton(
            icon: Icon(Icons.filter_list),
            onPressed: _showFiltersDialog,
          ),
        ],
      ),
      body: Column(
        children: [
          _buildSearchBar(),
          _buildFilterChips(),
          Expanded(
            child: proformasAsync.when(
              data: (data) => _buildProformasList(data),
              loading: () => Center(child: CircularProgressIndicator()),
              error: (error, stack) => Center(
                child: Text('Erreur: $error'),
              ),
            ),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () => _navigateToCreateProforma(),
        child: Icon(Icons.add),
        tooltip: 'Nouveau proforma',
      ),
    );
  }

  Widget _buildSearchBar() {
    return Padding(
      padding: EdgeInsets.all(8),
      child: TextField(
        decoration: InputDecoration(
          hintText: 'Rechercher par numéro, client...',
          prefixIcon: Icon(Icons.search),
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(12),
          ),
        ),
        onChanged: (value) {
          setState(() {
            filters = filters.copyWith(search: value, page: 1);
          });
        },
      ),
    );
  }

  Widget _buildFilterChips() {
    return SingleChildScrollView(
      scrollDirection: Axis.horizontal,
      padding: EdgeInsets.symmetric(horizontal: 8),
      child: Row(
        children: [
          _buildStatusChip('Tous', null),
          _buildStatusChip('Brouillon', 'draft'),
          _buildStatusChip('Envoyé', 'sent'),
          _buildStatusChip('Accepté', 'accepted'),
          _buildStatusChip('Converti', 'converted'),
        ],
      ),
    );
  }

  Widget _buildStatusChip(String label, String? status) {
    final isSelected = filters.status == status;
    return Padding(
      padding: EdgeInsets.only(right: 8),
      child: FilterChip(
        label: Text(label),
        selected: isSelected,
        onSelected: (selected) {
          setState(() {
            filters = filters.copyWith(status: status, page: 1);
          });
        },
      ),
    );
  }

  Widget _buildProformasList(Map<String, dynamic> data) {
    final proformas = data['proformas'] as List<Proforma>;
    final pagination = data['pagination'];

    if (proformas.isEmpty) {
      return Center(
        child: Text('Aucun proforma trouvé'),
      );
    }

    return ListView.builder(
      itemCount: proformas.length + 1, // +1 for pagination
      itemBuilder: (context, index) {
        if (index == proformas.length) {
          return _buildPaginationControls(pagination);
        }
        return _buildProformaCard(proformas[index]);
      },
    );
  }

  Widget _buildProformaCard(Proforma proforma) {
    return Card(
      margin: EdgeInsets.symmetric(horizontal: 8, vertical: 4),
      child: ListTile(
        title: Text(
          proforma.proformaNumber,
          style: TextStyle(fontWeight: FontWeight.bold),
        ),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(proforma.clientName),
            Text(
              '${NumberFormat.currency(symbol: 'FCFA ').format(proforma.total)}',
              style: TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.w600,
                color: Colors.green,
              ),
            ),
            Text(
              'Validité: ${DateFormat('dd/MM/yyyy').format(proforma.validUntil)}',
              style: TextStyle(fontSize: 12),
            ),
          ],
        ),
        trailing: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Container(
              padding: EdgeInsets.symmetric(horizontal: 8, vertical: 4),
              decoration: BoxDecoration(
                color: proforma.statusColor,
                borderRadius: BorderRadius.circular(12),
              ),
              child: Text(
                proforma.statusLabel,
                style: TextStyle(color: Colors.white, fontSize: 12),
              ),
            ),
            if (proforma.isExpired)
              Icon(Icons.warning, color: Colors.orange, size: 16),
          ],
        ),
        onTap: () => _navigateToProformaDetail(proforma.id),
        onLongPress: () => _showActionsMenu(proforma),
      ),
    );
  }

  Widget _buildPaginationControls(Map<String, dynamic> pagination) {
    final currentPage = pagination['current_page'];
    final lastPage = pagination['last_page'];

    return Padding(
      padding: EdgeInsets.all(16),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          ElevatedButton(
            onPressed: currentPage > 1
                ? () {
                    setState(() {
                      filters = filters.copyWith(page: currentPage - 1);
                    });
                  }
                : null,
            child: Text('Précédent'),
          ),
          Text('Page $currentPage / $lastPage'),
          ElevatedButton(
            onPressed: currentPage < lastPage
                ? () {
                    setState(() {
                      filters = filters.copyWith(page: currentPage + 1);
                    });
                  }
                : null,
            child: Text('Suivant'),
          ),
        ],
      ),
    );
  }

  void _showActionsMenu(Proforma proforma) {
    showModalBottomSheet(
      context: context,
      builder: (context) => Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          ListTile(
            leading: Icon(Icons.visibility),
            title: Text('Voir les détails'),
            onTap: () {
              Navigator.pop(context);
              _navigateToProformaDetail(proforma.id);
            },
          ),
          if (proforma.canBeEdited)
            ListTile(
              leading: Icon(Icons.edit),
              title: Text('Modifier'),
              onTap: () {
                Navigator.pop(context);
                _navigateToEditProforma(proforma.id);
              },
            ),
          ListTile(
            leading: Icon(Icons.copy),
            title: Text('Dupliquer'),
            onTap: () async {
              Navigator.pop(context);
              await _duplicateProforma(proforma.id);
            },
          ),
          if (proforma.canBeConverted)
            ListTile(
              leading: Icon(Icons.receipt_long),
              title: Text('Convertir en facture'),
              onTap: () async {
                Navigator.pop(context);
                await _convertToSale(proforma.id);
              },
            ),
          if (proforma.canBeDeleted)
            ListTile(
              leading: Icon(Icons.delete, color: Colors.red),
              title: Text('Supprimer', style: TextStyle(color: Colors.red)),
              onTap: () async {
                Navigator.pop(context);
                await _deleteProforma(proforma.id);
              },
            ),
        ],
      ),
    );
  }

  Future<void> _duplicateProforma(int id) async {
    try {
      final service = ref.read(proformaServiceProvider);
      await service.duplicateProforma(id);
      
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Proforma dupliqué avec succès')),
      );
      
      // Rafraîchir la liste
      ref.invalidate(proformasProvider);
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Erreur: $e')),
      );
    }
  }

  Future<void> _convertToSale(int id) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: Text('Confirmer la conversion'),
        content: Text(
          'Voulez-vous convertir ce proforma en facture/vente ?'
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: Text('Annuler'),
          ),
          ElevatedButton(
            onPressed: () => Navigator.pop(context, true),
            child: Text('Confirmer'),
          ),
        ],
      ),
    );

    if (confirm != true) return;

    try {
      final service = ref.read(proformaServiceProvider);
      final result = await service.convertToSale(id);
      
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            'Facture ${result['invoice_number']} créée avec succès'
          ),
        ),
      );
      
      ref.invalidate(proformasProvider);
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Erreur: $e')),
      );
    }
  }

  Future<void> _deleteProforma(int id) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: Text('Confirmer la suppression'),
        content: Text('Voulez-vous vraiment supprimer ce proforma ?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: Text('Annuler'),
          ),
          ElevatedButton(
            onPressed: () => Navigator.pop(context, true),
            style: ElevatedButton.styleFrom(backgroundColor: Colors.red),
            child: Text('Supprimer'),
          ),
        ],
      ),
    );

    if (confirm != true) return;

    try {
      final service = ref.read(proformaServiceProvider);
      await service.deleteProforma(id);
      
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Proforma supprimé avec succès')),
      );
      
      ref.invalidate(proformasProvider);
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Erreur: $e')),
      );
    }
  }

  void _showFiltersDialog() {
    // Afficher dialogue avec filtres de période, tri, etc.
  }

  void _navigateToCreateProforma() {
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (context) => ProformaFormScreen(),
      ),
    ).then((_) => ref.invalidate(proformasProvider));
  }

  void _navigateToProformaDetail(int id) {
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (context) => ProformaDetailScreen(proformaId: id),
      ),
    );
  }

  void _navigateToEditProforma(int id) {
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (context) => ProformaFormScreen(proformaId: id),
      ),
    ).then((_) => ref.invalidate(proformasProvider));
  }
}
```

---

## 🎨 Bonnes Pratiques

### 1. **Gestion des Erreurs**
```dart
try {
  final result = await proformaService.convertToSale(id);
  // Success
} on SocketException {
  // Pas de connexion internet
} on TimeoutException {
  // Timeout de la requête
} catch (e) {
  // Autre erreur
  print('Erreur: $e');
}
```

### 2. **Loading States**
```dart
bool isLoading = false;

Future<void> _submitProforma() async {
  setState(() => isLoading = true);
  
  try {
    await proformaService.createProforma(...);
  } finally {
    setState(() => isLoading = false);
  }
}
```

### 3. **Validation des Formulaires**
```dart
final _formKey = GlobalKey<FormState>();

TextFormField(
  validator: (value) {
    if (value == null || value.isEmpty) {
      return 'Le nom du client est requis';
    }
    return null;
  },
)
```

### 4. **Calculs Automatiques**
```dart
double calculateTotal(List<ProformaItem> items) {
  return items.fold(0, (sum, item) => sum + item.total);
}

void _updateItemTotal(int index) {
  final item = items[index];
  final total = (item.quantity * item.unitPrice) - item.discount;
  
  setState(() {
    items[index] = item.copyWith(total: total);
  });
}
```

### 5. **Optimisation des Requêtes**
```dart
// Debounce pour la recherche
Timer? _debounce;

void _onSearchChanged(String query) {
  if (_debounce?.isActive ?? false) _debounce!.cancel();
  
  _debounce = Timer(Duration(milliseconds: 500), () {
    // Exécuter la recherche
  });
}
```

---

## 📊 Statuts des Proformas

| Statut | Code | Description | Actions Disponibles |
|--------|------|-------------|-------------------|
| 🟡 Brouillon | `draft` | Proforma en cours de préparation | Modifier, Supprimer, Changer statut |
| 🔵 Envoyé | `sent` | Proforma envoyé au client | Modifier, Supprimer, Changer statut |
| 🟢 Accepté | `accepted` | Client a accepté | Convertir en facture, Changer statut |
| 🔴 Rejeté | `rejected` | Client a refusé | Supprimer, Dupliquer |
| 🟣 Converti | `converted` | Converti en facture/vente | Voir uniquement (verrouillé) |
| 🟠 Expiré | `expired` | Date de validité dépassée | Supprimer, Dupliquer |

---

## 🔒 Règles de Gestion

### Modification
- ✅ Autorisée pour: `draft`, `sent`, `accepted`, `rejected`
- ❌ Interdite pour: `converted`, `expired`

### Suppression
- ✅ Autorisée pour: tous sauf `converted`
- ❌ Interdite pour: `converted`

### Conversion en Facture
- ✅ Autorisée pour: `accepted` uniquement
- ❌ Interdite pour: tous les autres statuts

---

## ✅ Checklist d'Implémentation

### Phase 1: Setup de Base
- [ ] Créer les models `Proforma` et `ProformaItem`
- [ ] Créer le service `ProformaService`
- [ ] Configurer les providers Riverpod

### Phase 2: Liste des Proformas
- [ ] Écran de liste avec pagination
- [ ] Barre de recherche
- [ ] Filtres par statut
- [ ] Filtres par période
- [ ] Tri (date, montant, statut)

### Phase 3: Formulaire Création/Modification
- [ ] Formulaire client (nom, téléphone, email, adresse)
- [ ] Sélecteur de dates (proforma_date, valid_until)
- [ ] Recherche de produits (autocomplete)
- [ ] Gestion des items (ajouter, supprimer, modifier)
- [ ] Calculs automatiques des totaux
- [ ] Notes et conditions

### Phase 4: Détails et Actions
- [ ] Écran de détails complet
- [ ] Affichage des items avec détails produits
- [ ] Actions: Modifier, Supprimer, Dupliquer
- [ ] Changement de statut avec confirmation
- [ ] Conversion en facture avec confirmation

### Phase 5: Fonctionnalités Avancées
- [ ] Statistiques des proformas
- [ ] Graphiques de conversion
- [ ] Export PDF (si nécessaire)
- [ ] Notifications push pour changements de statut

---

## 🐛 Tests Recommandés

### Tests Unitaires
```dart
test('Calcul du total d\'un item', () {
  final total = ProformaItem.calculateItemTotal(2, 10000, 500);
  expect(total, 19500);
});

test('Validation de la date de validité', () {
  final proformaDate = DateTime(2025, 1, 1);
  final validUntil = DateTime(2025, 2, 1);
  
  expect(validUntil.isAfter(proformaDate), true);
});
```

### Tests d'Intégration
```dart
testWidgets('Création d\'un proforma', (WidgetTester tester) async {
  // Ouvrir l'écran de création
  await tester.pumpWidget(ProformaFormScreen());
  
  // Remplir le formulaire
  await tester.enterText(find.byKey(Key('clientName')), 'Test Client');
  
  // Soumettre
  await tester.tap(find.byKey(Key('submitButton')));
  await tester.pumpAndSettle();
  
  // Vérifier le succès
  expect(find.text('Proforma créé avec succès'), findsOneWidget);
});
```

---

## 📞 Support

Pour toute question sur l'intégration:
- 📧 Email: dev@example.com
- 💬 Slack: #api-mobile
- 📚 Documentation API complète: https://your-api.com/docs

---

**Date de création:** 2025-01-20  
**Version:** 1.0.0  
**Dernière mise à jour:** 2025-01-20
