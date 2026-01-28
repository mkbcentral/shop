# 🚀 Prompt Flutter - Intégration des nouvelles API Mobile

Ce document contient les instructions pour implémenter les nouvelles fonctionnalités API côté Flutter.

---

## CONTEXTE API

- **Base URL:** `/api/mobile/`
- **Authentification:** Bearer Token (Sanctum)

---

## NOUVEAUX ENDPOINTS À INTÉGRER

### 1. Statistiques des Ventes

**Endpoint:** `GET /api/mobile/sales/statistics`

**Paramètres optionnels:**
| Paramètre | Type | Valeurs |
|-----------|------|---------|
| `period` | string | `today`, `yesterday`, `this_week`, `last_week`, `this_month`, `last_month`, `last_3_months`, `this_year`, `all` |
| `date_from` | string | Format: `YYYY-MM-DD` |
| `date_to` | string | Format: `YYYY-MM-DD` |

**Réponse:**
```json
{
  "success": true,
  "data": {
    "completed": {
      "count": 45,
      "amount": 125000,
      "amount_formatted": "125 000,00"
    },
    "pending": {
      "count": 3,
      "amount": 15000,
      "amount_formatted": "15 000,00"
    },
    "cancelled": {
      "count": 2,
      "amount": 5000
    },
    "totals": {
      "total_sales": 45,
      "total_amount": 125000,
      "pending_sales": 3,
      "pending_amount": 15000,
      "average_ticket": 2777.78
    },
    "payment_methods": [
      {
        "method": "cash",
        "label": "Espèces",
        "count": 30,
        "amount": 80000
      },
      {
        "method": "mobile_money",
        "label": "Mobile Money",
        "count": 15,
        "amount": 45000
      }
    ]
  }
}
```

---

### 2. Historique des Ventes (mis à jour)

**Endpoint:** `GET /api/mobile/sales`

**Paramètres:**
| Paramètre | Type | Description |
|-----------|------|-------------|
| `per_page` | int | Nombre d'éléments par page (10-100) |
| `period` | string | Période prédéfinie (voir ci-dessus) |
| `date_from` | string | Date de début |
| `date_to` | string | Date de fin |
| `client_id` | int | **NOUVEAU** - Filtrer par client |
| `status` | string | `completed`, `pending`, `cancelled` |
| `payment_status` | string | **NOUVEAU** - `paid`, `partial`, `unpaid` |
| `payment_method` | string | `cash`, `mobile_money`, `card`, `bank_transfer` |

---

### 3. Mouvements de Stock Groupés

**Endpoint:** `GET /api/mobile/stock/movements/grouped`

**Paramètres:**
| Paramètre | Type | Description |
|-----------|------|-------------|
| `per_page` | int | Nombre d'éléments par page (10-100) |
| `type` | string | `in` ou `out` |
| `movement_type` | string | `purchase`, `sale`, `adjustment`, `transfer`, `return` |
| `date_from` | string | Date de début |
| `date_to` | string | Date de fin |

**Réponse:**
```json
{
  "success": true,
  "data": {
    "grouped_movements": [
      {
        "product_variant_id": 1,
        "product_variant": {
          "id": 1,
          "sku": "PROD-001",
          "name": "Produit A - Taille M",
          "product_name": "Produit A",
          "current_stock": 50
        },
        "total_in": 100,
        "total_out": 50,
        "net_change": 50,
        "movement_count": 15,
        "last_date": "2026-01-28"
      }
    ],
    "summary": {
      "total_products": 25,
      "total_movements": 150,
      "total_in": 500,
      "total_out": 350
    },
    "pagination": {
      "current_page": 1,
      "last_page": 3,
      "per_page": 20,
      "total": 25
    }
  }
}
```

---

### 4. Produits avec filtre de stock

**Endpoint:** `GET /api/mobile/products`

**Nouveau paramètre:**
| Paramètre | Type | Valeurs |
|-----------|------|---------|
| `stock_level` | string | `in_stock`, `low_stock`, `out_of_stock` |

---

## TÂCHES À RÉALISER

### 1. Services/Repositories

Mettre à jour les services API pour supporter les nouveaux endpoints et paramètres:

```dart
// SalesService
Future<SalesStatistics> getStatistics({String? period, DateTime? dateFrom, DateTime? dateTo});
Future<PaginatedResponse<Sale>> getSales({
  int page = 1,
  String? period,
  int? clientId,        // NOUVEAU
  String? paymentStatus, // NOUVEAU
  // ... autres paramètres existants
});

// StockService
Future<GroupedMovementsResponse> getGroupedMovements({
  int page = 1,
  String? type,
  String? movementType,
  DateTime? dateFrom,
  DateTime? dateTo,
});

// ProductService
Future<PaginatedResponse<Product>> getProducts({
  // ... paramètres existants
  String? stockLevel, // NOUVEAU: in_stock, low_stock, out_of_stock
});
```

---

### 2. Models/DTOs

Créer ou mettre à jour les modèles:

```dart
// sales_statistics.dart
class SalesStatistics {
  final SalesCount completed;
  final SalesCount pending;
  final SalesCount cancelled;
  final SalesTotals totals;
  final List<PaymentMethodStats> paymentMethods;
}

class SalesCount {
  final int count;
  final double amount;
  final String amountFormatted;
}

class SalesTotals {
  final int totalSales;
  final double totalAmount;
  final int pendingSales;
  final double pendingAmount;
  final double averageTicket;
}

class PaymentMethodStats {
  final String method;
  final String label;
  final int count;
  final double amount;
}

// grouped_movement.dart
class GroupedMovement {
  final int productVariantId;
  final ProductVariantInfo productVariant;
  final int totalIn;
  final int totalOut;
  final int netChange;
  final int movementCount;
  final DateTime lastDate;
}

class MovementSummary {
  final int totalProducts;
  final int totalMovements;
  final int totalIn;
  final int totalOut;
}
```

---

### 3. State Management (Riverpod/Bloc/Provider)

Ajouter les providers/blocs pour:

```dart
// Avec Riverpod
final salesStatisticsProvider = FutureProvider.family<SalesStatistics, String?>((ref, period) async {
  final service = ref.read(salesServiceProvider);
  return service.getStatistics(period: period);
});

final groupedMovementsProvider = StateNotifierProvider<GroupedMovementsNotifier, AsyncValue<GroupedMovementsState>>((ref) {
  return GroupedMovementsNotifier(ref.read(stockServiceProvider));
});

// Mettre à jour salesProvider avec nouveaux filtres
final salesFiltersProvider = StateProvider<SalesFilters>((ref) => SalesFilters());
```

---

### 4. UI/Screens

#### a) Écran Statistiques Ventes (`SalesStatsScreen`)

```
┌─────────────────────────────────────┐
│  📊 Statistiques des Ventes         │
├─────────────────────────────────────┤
│  [Aujourd'hui ▼] <- PeriodSelector  │
├─────────────────────────────────────┤
│  ┌─────────┐  ┌─────────┐           │
│  │   45    │  │ 125 000 │           │
│  │ Ventes  │  │ Montant │           │
│  └─────────┘  └─────────┘           │
│  ┌─────────┐  ┌─────────┐           │
│  │    3    │  │ 2 778   │           │
│  │En attente│ │ Panier  │           │
│  └─────────┘  └─────────┘           │
├─────────────────────────────────────┤
│  Répartition par paiement           │
│  ┌─────────────────────┐            │
│  │     [PieChart]      │            │
│  │  Cash: 60%          │            │
│  │  Mobile: 40%        │            │
│  └─────────────────────┘            │
└─────────────────────────────────────┘
```

#### b) Écran Historique Ventes (`SalesHistoryScreen`)

Ajouter les filtres:
- Dropdown période (today, this_week, this_month, etc.)
- Recherche/sélection client
- Chips statut paiement (Tous, Payé, Partiel, Impayé)

#### c) Écran Mouvements Stock (`StockMovementsScreen`)

```
┌─────────────────────────────────────┐
│  📦 Mouvements de Stock             │
├─────────────────────────────────────┤
│  [Détaillée] [Groupée] <- Toggle    │
├─────────────────────────────────────┤
│  VUE GROUPÉE:                       │
│  ┌─────────────────────────────────┐│
│  │ Produit A           [15 mvts]  ││
│  │ Stock: 50                      ││
│  │ ↑ +100  ↓ -50  = +50          ││
│  │ Dernier: 28/01/2026           ││
│  └─────────────────────────────────┘│
│  ┌─────────────────────────────────┐│
│  │ Produit B           [8 mvts]   ││
│  │ Stock: 25                      ││
│  │ ↑ +30   ↓ -20  = +10          ││
│  └─────────────────────────────────┘│
└─────────────────────────────────────┘
```

#### d) Écran Produits (`ProductsScreen`)

Ajouter filtre chips de niveau de stock:

```
[Tous] [En stock] [Stock bas] [Rupture]
```

---

### 5. Widgets réutilisables

```dart
// period_selector.dart
class PeriodSelector extends StatelessWidget {
  final String? selectedPeriod;
  final ValueChanged<String?> onChanged;
  
  static const periods = [
    ('today', 'Aujourd\'hui'),
    ('yesterday', 'Hier'),
    ('this_week', 'Cette semaine'),
    ('last_week', 'Semaine dernière'),
    ('this_month', 'Ce mois'),
    ('last_month', 'Mois dernier'),
    ('last_3_months', '3 derniers mois'),
    ('this_year', 'Cette année'),
    ('all', 'Tout'),
  ];
}

// stats_card.dart
class StatsCard extends StatelessWidget {
  final String label;
  final String value;
  final IconData? icon;
  final Color? color;
}

// movement_summary_card.dart
class MovementSummaryCard extends StatelessWidget {
  final GroupedMovement movement;
  final VoidCallback? onTap;
}

// stock_level_badge.dart
class StockLevelBadge extends StatelessWidget {
  final String level; // in_stock, low_stock, out_of_stock
  
  Color get color => switch(level) {
    'in_stock' => Colors.green,
    'low_stock' => Colors.orange,
    'out_of_stock' => Colors.red,
    _ => Colors.grey,
  };
}
```

---

## STRUCTURE SUGGÉRÉE

```
lib/
├── models/
│   ├── sales_statistics.dart      # NOUVEAU
│   ├── grouped_movement.dart      # NOUVEAU
│   └── movement_summary.dart      # NOUVEAU
├── services/
│   ├── sales_service.dart         # Mise à jour
│   └── stock_service.dart         # Mise à jour
├── providers/ (ou blocs/)
│   ├── sales_stats_provider.dart  # NOUVEAU
│   └── grouped_movements_provider.dart  # NOUVEAU
├── screens/
│   ├── sales/
│   │   ├── sales_stats_screen.dart     # NOUVEAU
│   │   └── sales_history_screen.dart   # Mise à jour
│   └── stock/
│       └── stock_movements_screen.dart # Mise à jour
└── widgets/
    ├── period_selector.dart       # NOUVEAU
    ├── stats_card.dart            # NOUVEAU
    ├── movement_summary_card.dart # NOUVEAU
    └── stock_level_badge.dart     # NOUVEAU
```

---

## PRIORITÉS

| Priorité | Tâche | Justification |
|----------|-------|---------------|
| 🔴 1 | Modèles et Services | Foundation technique |
| 🔴 2 | Statistiques des ventes | Haute valeur UX |
| 🟡 3 | Vue groupée mouvements | Cohérence avec web |
| 🟢 4 | Filtres additionnels | Amélioration UX |

---

## NOTES TECHNIQUES

- ✅ Utiliser `freezed` pour les modèles si disponible dans le projet
- ✅ Gérer le cache des statistiques (5 minutes)
- ✅ Implémenter pull-to-refresh sur tous les écrans de liste
- ✅ Gérer les états `loading` / `error` / `empty`
- ✅ Supporter le mode hors-ligne si applicable
- ✅ Ajouter des tests unitaires pour les nouveaux services
- ✅ Documenter les nouveaux widgets avec des exemples

---

## EXEMPLE D'UTILISATION

### Appel API avec Dio

```dart
// Statistiques des ventes
final response = await dio.get('/api/mobile/sales/statistics', queryParameters: {
  'period': 'this_month',
});
final stats = SalesStatistics.fromJson(response.data['data']);

// Mouvements groupés
final response = await dio.get('/api/mobile/stock/movements/grouped', queryParameters: {
  'per_page': 20,
  'date_from': '2026-01-01',
  'date_to': '2026-01-28',
});
final grouped = GroupedMovementsResponse.fromJson(response.data['data']);

// Produits avec filtre stock
final response = await dio.get('/api/mobile/products', queryParameters: {
  'stock_level': 'low_stock',
  'per_page': 20,
});
```

---

*Document généré le 28 janvier 2026*
