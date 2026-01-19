# Intégration Flutter - Système de Facturation Mobile (MobileSalesController)

## 📋 Contexte

Ce document décrit l'intégration Flutter avec l'API Laravel de gestion de point de vente (POS). L'API expose un contrôleur `MobileSalesController` qui gère la création de ventes et la facturation mobile.

---

## 🔗 API Endpoints Disponibles

### 1. Validation du Panier
```
POST /api/mobile/checkout/validate
```

**Description**: Valide un panier avant le checkout (vérification du stock, des remises, et calcul des totaux)

**Request Body**:
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

**Response Success (200)**:
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

**Response Error - Stock Insuffisant**:
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
    }
  }
}
```

**Response Error - Remise Excessive**:
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
    }
  }
}
```

---

### 2. Créer une Vente (Checkout)
```
POST /api/mobile/checkout
```

**Description**: Crée une vente complète avec génération automatique de facture et enregistrement des mouvements de stock

**Request Body**:
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

**Paramètres**:
- `items` (array, required) - Liste des produits
  - `variant_id` (int, required) - ID de la variante
  - `quantity` (int, required) - Quantité (min: 1)
  - `price` (float, required) - Prix unitaire
- `client_id` (int, optional) - ID du client
- `payment_method` (string, required) - Méthode: `cash`, `mobile_money`, `card`, `bank_transfer`
- `paid_amount` (float, required) - Montant payé
- `discount` (float, optional) - Remise totale (défaut: 0)
- `tax` (float, optional) - Taxe totale (défaut: 0)
- `notes` (string, optional) - Notes (max 500 caractères)
- `store_id` (int, optional) - ID du magasin (utilise le store actuel si absent)

**Response Success (201)**:
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

**Response Error - Validation (422)**:
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

**Response Error - Stock Insuffisant (400)**:
```json
{
  "success": false,
  "message": "Stock insuffisant pour Produit XYZ...",
  "product": "Produit XYZ",
  "requested": 5,
  "available": 2
}
```

**Response Error - Montant Insuffisant (400)**:
```json
{
  "success": false,
  "message": "Montant payé insuffisant",
  "required": 250.00,
  "provided": 200.00
}
```

**Response Error - Accès Refusé (403)**:
```json
{
  "success": false,
  "message": "Vous n'avez pas accès à ce magasin"
}
```

---

### 3. Historique des Ventes
```
GET /api/mobile/sales
```

**Description**: Récupère l'historique paginé des ventes

**Query Parameters**:
- `per_page` (int, optional) - Résultats par page (min: 10, max: 100, défaut: 20)
- `page` (int, optional) - Numéro de page (défaut: 1)
- `date_from` (date, optional) - Date début (format: YYYY-MM-DD)
- `date_to` (date, optional) - Date fin (format: YYYY-MM-DD)
- `payment_method` (string, optional) - Filtrer par méthode
- `status` (string, optional) - Filtrer par statut
- `store_id` (int, optional) - Filtrer par magasin

**Exemple**:
```
GET /api/mobile/sales?per_page=20&page=1&date_from=2026-01-01&payment_method=cash&store_id=1
```

**Response Success (200)**:
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

### 4. Détail d'une Vente
```
GET /api/mobile/sales/{id}
```

**Description**: Récupère les détails complets d'une vente

**Exemple**:
```
GET /api/mobile/sales/42
```

**Response Success (200)**: Même structure que l'objet sale dans l'historique

**Response Error - Non Trouvé (404)**:
```json
{
  "success": false,
  "message": "Vente non trouvée"
}
```

**Response Error - Accès Refusé (403)**:
```json
{
  "success": false,
  "message": "Accès non autorisé"
}
```

---

## 🎯 Fonctionnalités Clés à Implémenter

### 1. Validation en Temps Réel du Panier
- ✅ Vérifier le stock disponible avant checkout
- ✅ Valider les limites de remise (`max_discount_amount` par produit)
- ✅ Calculer automatiquement les totaux (sous-total, remise, taxe, total)
- ✅ Afficher des messages d'erreur clairs et contextuels

### 2. Création de Ventes
- ✅ Support multi-store via paramètre `store_id`
- ✅ Calcul automatique de la monnaie rendue
- ✅ Génération automatique de factures avec numéro unique
- ✅ Enregistrement des mouvements de stock

### 3. Historique et Consultation
- ✅ Pagination avec infinite scroll
- ✅ Filtres: date, méthode de paiement, statut, magasin
- ✅ Vue détaillée avec liste complète des produits
- ✅ Pull-to-refresh

### 4. Gestion des Erreurs
- **Stock insuffisant**: "Le produit X n'a que Y unités en stock"
- **Remise excessive**: "La remise maximum autorisée est de X CDF"
- **Montant insuffisant**: "Montant payé insuffisant (X CDF requis)"
- **Erreurs réseau**: "Impossible de se connecter au serveur"

---

## 📦 Modèles de Données Flutter

### CartItem

> ⚠️ **IMPORTANT**: L'API attend `variant_id` (ID de la variante), **PAS** `product_id`. Assurez-vous d'utiliser l'ID de la variante du produit, sinon vous recevrez une erreur 422.

```dart
class CartItem {
  final int variantId;  // ⚠️ ATTENTION: Utiliser l'ID de la VARIANTE, pas du produit
  final String productName;
  int quantity;
  double price;
  final double originalPrice;
  final double? maxDiscountAmount;
  
  CartItem({
    required this.variantId,  // ⚠️ ID de la VARIANTE (product_variant_id)
    required this.productName,
    required this.quantity,
    required this.price,
    required this.originalPrice,
    this.maxDiscountAmount,
  });
  
  // Méthodes helper
  double get subtotal => price * quantity;
  double get maxDiscount => maxDiscountAmount ?? originalPrice;
  double get minPrice => originalPrice - maxDiscount;
  
  // Sérialisation JSON
  Map<String, dynamic> toJson() => {
    'variant_id': variantId,  // ⚠️ Clé OBLIGATOIRE: "variant_id"
    'quantity': quantity,
    'price': price,
  };
}
```

**Erreur courante à éviter**:
```dart
// ❌ INCORRECT - Ceci génère une erreur 422
Map<String, dynamic> toJson() => {
  'product_id': productId,  // ❌ FAUX
  'quantity': quantity,
  'price': price,
};

// ✅ CORRECT - Utiliser variant_id
Map<String, dynamic> toJson() => {
  'variant_id': variantId,  // ✅ CORRECT
  'quantity': quantity,
  'price': price,
};
```

### Sale (Vente)
```dart
class Sale {
  final int id;
  final String reference;
  final DateTime saleDate;
  final double total;
  final double discount;
  final double tax;
  final String paymentMethod;
  final String paymentStatus;
  final String status;
  final String? notes;
  final Client? client;
  final Cashier cashier;
  final Store? store;
  final List<SaleItem> items;
  final int itemsCount;
  final Invoice? invoice;
  
  Sale({
    required this.id,
    required this.reference,
    required this.saleDate,
    required this.total,
    required this.discount,
    required this.tax,
    required this.paymentMethod,
    required this.paymentStatus,
    required this.status,
    this.notes,
    this.client,
    required this.cashier,
    this.store,
    required this.items,
    required this.itemsCount,
    this.invoice,
  });
  
  factory Sale.fromJson(Map<String, dynamic> json) {
    return Sale(
      id: json['id'],
      reference: json['reference'],
      saleDate: DateTime.parse(json['sale_date']),
      total: (json['total'] as num).toDouble(),
      discount: (json['discount'] as num?)?.toDouble() ?? 0,
      tax: (json['tax'] as num?)?.toDouble() ?? 0,
      paymentMethod: json['payment_method'],
      paymentStatus: json['payment_status'],
      status: json['status'],
      notes: json['notes'],
      client: json['client'] != null ? Client.fromJson(json['client']) : null,
      cashier: Cashier.fromJson(json['cashier']),
      store: json['store'] != null ? Store.fromJson(json['store']) : null,
      items: (json['items'] as List).map((i) => SaleItem.fromJson(i)).toList(),
      itemsCount: json['items_count'],
      invoice: json['invoice'] != null ? Invoice.fromJson(json['invoice']) : null,
    );
  }
}
```

### ValidationResult
```dart
class ValidationResult {
  final bool isValid;
  final StockValidation stockValidation;
  final DiscountValidation discountValidation;
  final Totals totals;
  
  ValidationResult({
    required this.isValid,
    required this.stockValidation,
    required this.discountValidation,
    required this.totals,
  });
  
  factory ValidationResult.fromJson(Map<String, dynamic> json) {
    return ValidationResult(
      isValid: json['is_valid'],
      stockValidation: StockValidation.fromJson(json['stock_validation']),
      discountValidation: DiscountValidation.fromJson(json['discount_validation']),
      totals: Totals.fromJson(json['totals']),
    );
  }
}

class StockValidation {
  final bool valid;
  final String? productName;
  final int? requested;
  final int? available;
  
  bool get hasError => !valid;
  String get errorMessage => 
    'Le produit $productName n\'a que $available unités en stock (demandé: $requested)';
}

class DiscountValidation {
  final bool valid;
  final String? message;
  final double? maxAllowed;
  final double? requested;
  
  bool get hasError => !valid;
}

class Totals {
  final double subtotal;
  final double discount;
  final double tax;
  final double total;
}
```

---

## 🛠️ Implémentation

### 1. Service API (SalesApiService)

```dart
class SalesApiService {
  final Dio _dio;
  final String baseUrl;
  
  SalesApiService(this._dio, this.baseUrl);
  
  // Valider le panier
  Future<ValidationResult> validateCart({
    required List<CartItem> items,
    double discount = 0,
    double tax = 0,
  }) async {
    try {
      final response = await _dio.post(
        '$baseUrl/api/mobile/checkout/validate',
        data: {
          'items': items.map((item) => item.toJson()).toList(),
          'discount': discount,
          'tax': tax,
        },
      );
      
      return ValidationResult.fromJson(response.data['data']);
    } catch (e) {
      throw _handleError(e);
    }
  }
  
  // Créer une vente
  Future<SaleResult> createSale({
    required List<CartItem> items,
    int? clientId,
    required String paymentMethod,
    required double paidAmount,
    double discount = 0,
    double tax = 0,
    String? notes,
    int? storeId,
  }) async {
    try {
      final response = await _dio.post(
        '$baseUrl/api/mobile/checkout',
        data: {
          'items': items.map((item) => item.toJson()).toList(),
          'client_id': clientId,
          'payment_method': paymentMethod,
          'paid_amount': paidAmount,
          'discount': discount,
          'tax': tax,
          'notes': notes,
          'store_id': storeId,
        },
      );
      
      return SaleResult.fromJson(response.data['data']);
    } catch (e) {
      throw _handleError(e);
    }
  }
  
  // Historique des ventes
  Future<PaginatedSales> getSalesHistory({
    int page = 1,
    int perPage = 20,
    String? dateFrom,
    String? dateTo,
    String? paymentMethod,
    String? status,
    int? storeId,
  }) async {
    try {
      final queryParams = {
        'page': page,
        'per_page': perPage,
        if (dateFrom != null) 'date_from': dateFrom,
        if (dateTo != null) 'date_to': dateTo,
        if (paymentMethod != null) 'payment_method': paymentMethod,
        if (status != null) 'status': status,
        if (storeId != null) 'store_id': storeId,
      };
      
      final response = await _dio.get(
        '$baseUrl/api/mobile/sales',
        queryParameters: queryParams,
      );
      
      return PaginatedSales.fromJson(response.data['data']);
    } catch (e) {
      throw _handleError(e);
    }
  }
  
  // Détail d'une vente
  Future<Sale> getSaleDetail(int saleId) async {
    try {
      final response = await _dio.get('$baseUrl/api/mobile/sales/$saleId');
      return Sale.fromJson(response.data['data']);
    } catch (e) {
      throw _handleError(e);
    }
  }
  
  // Gestion des erreurs
  AppException _handleError(dynamic error) {
    if (error is DioException) {
      if (error.response != null) {
        final data = error.response!.data;
        if (data is Map<String, dynamic>) {
          return AppException(data['message'] ?? 'Erreur inconnue');
        }
      }
      return NetworkException('Erreur de connexion');
    }
    return AppException('Erreur inconnue');
  }
}
```

---

### 2. Provider/State Management (CartProvider)

```dart
class CartProvider extends ChangeNotifier {
  final SalesApiService _apiService;
  
  List<CartItem> _items = [];
  double _discount = 0;
  double _tax = 0;
  ValidationResult? _validationResult;
  bool _isValidating = false;
  bool _isProcessing = false;
  
  // Getters
  List<CartItem> get items => _items;
  double get discount => _discount;
  double get tax => _tax;
  ValidationResult? get validationResult => _validationResult;
  bool get isValidating => _isValidating;
  bool get isProcessing => _isProcessing;
  
  double get subtotal => _items.fold(0, (sum, item) => sum + item.subtotal);
  double get total => subtotal - _discount + _tax;
  double get maxAllowedDiscount {
    double max = 0;
    bool hasLimited = false;
    
    for (var item in _items) {
      if (item.maxDiscountAmount != null && item.maxDiscountAmount! > 0) {
        hasLimited = true;
        max += min(item.maxDiscountAmount!, item.originalPrice) * item.quantity;
      } else {
        max += item.originalPrice * item.quantity;
      }
    }
    
    return hasLimited ? max : double.infinity;
  }
  
  // Actions
  void addItem(CartItem item) {
    _items.add(item);
    notifyListeners();
    _validateCart();
  }
  
  void removeItem(int index) {
    _items.removeAt(index);
    notifyListeners();
    _validateCart();
  }
  
  void updateQuantity(int index, int quantity) {
    _items[index].quantity = quantity;
    notifyListeners();
    _validateCart();
  }
  
  void updatePrice(int index, double price) {
    _items[index].price = price;
    notifyListeners();
    _validateCart();
  }
  
  void setDiscount(double value) {
    _discount = value.clamp(0, maxAllowedDiscount);
    notifyListeners();
    _validateCart();
  }
  
  void setTax(double value) {
    _tax = value;
    notifyListeners();
  }
  
  // Validation automatique
  Future<void> _validateCart() async {
    if (_items.isEmpty) return;
    
    _isValidating = true;
    notifyListeners();
    
    try {
      _validationResult = await _apiService.validateCart(
        items: _items,
        discount: _discount,
        tax: _tax,
      );
    } catch (e) {
      // Gérer l'erreur silencieusement ou afficher un toast
    } finally {
      _isValidating = false;
      notifyListeners();
    }
  }
  
  // Checkout
  Future<SaleResult?> checkout({
    int? clientId,
    required String paymentMethod,
    required double paidAmount,
    String? notes,
    int? storeId,
  }) async {
    if (_items.isEmpty) {
      throw AppException('Le panier est vide');
    }
    
    _isProcessing = true;
    notifyListeners();
    
    try {
      final result = await _apiService.createSale(
        items: _items,
        clientId: clientId,
        paymentMethod: paymentMethod,
        paidAmount: paidAmount,
        discount: _discount,
        tax: _tax,
        notes: notes,
        storeId: storeId,
      );
      
      // Vider le panier après succès
      clear();
      
      return result;
    } finally {
      _isProcessing = false;
      notifyListeners();
    }
  }
  
  void clear() {
    _items.clear();
    _discount = 0;
    _tax = 0;
    _validationResult = null;
    notifyListeners();
  }
}
```

---

### 3. Écran Checkout

```dart
class CheckoutScreen extends StatefulWidget {
  @override
  _CheckoutScreenState createState() => _CheckoutScreenState();
}

class _CheckoutScreenState extends State<CheckoutScreen> {
  final _formKey = GlobalKey<FormState>();
  String _paymentMethod = 'cash';
  double _paidAmount = 0;
  int? _selectedClientId;
  String _notes = '';
  
  @override
  Widget build(BuildContext context) {
    return Consumer<CartProvider>(
      builder: (context, cart, child) {
        return Scaffold(
          appBar: AppBar(
            title: Text('Paiement'),
          ),
          body: Form(
            key: _formKey,
            child: ListView(
              padding: EdgeInsets.all(16),
              children: [
                // Liste des produits
                _buildCartItems(cart),
                
                SizedBox(height: 20),
                
                // Totaux
                _buildTotals(cart),
                
                SizedBox(height: 20),
                
                // Validation errors
                if (cart.validationResult?.hasErrors ?? false)
                  _buildValidationErrors(cart.validationResult!),
                
                SizedBox(height: 20),
                
                // Sélection client
                _buildClientSelector(),
                
                SizedBox(height: 20),
                
                // Méthode de paiement
                _buildPaymentMethodSelector(),
                
                SizedBox(height: 20),
                
                // Montant payé
                _buildPaidAmountField(cart),
                
                SizedBox(height: 20),
                
                // Notes
                _buildNotesField(),
                
                SizedBox(height: 30),
                
                // Bouton de paiement
                _buildCheckoutButton(cart),
              ],
            ),
          ),
        );
      },
    );
  }
  
  Widget _buildCartItems(CartProvider cart) {
    return Card(
      color: Colors.blue.shade50,
      elevation: 2,
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  'Panier (${cart.items.length} articles)',
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: Colors.blue.shade900,
                  ),
                ),
                TextButton.icon(
                  onPressed: cart.items.isEmpty ? null : () {
                    showDialog(
                      context: context,
                      builder: (context) => AlertDialog(
                        title: Text('Vider le panier'),
                        content: Text('Êtes-vous sûr de vouloir vider le panier ?'),
                        actions: [
                          ElevatedButton(
                            onPressed: () {
                              cart.clear();
                              Navigator.pop(context);
                            },
                            style: ElevatedButton.styleFrom(
                              backgroundColor: Colors.blue.shade700,
                              foregroundColor: Colors.white,
                              minimumSize: Size(double.infinity, 48),
                            ),
                            child: Text('Confirmer', style: TextStyle(fontWeight: FontWeight.bold)),
                          ),
                        ],
                      ),
                    );
                  },
                  icon: Icon(Icons.delete_outline, color: Colors.blue.shade700),
                  label: Text(
                    'Vider',
                    style: TextStyle(color: Colors.blue.shade700),
                  ),
                ),
              ],
            ),
            SizedBox(height: 12),
            if (cart.items.isEmpty)
              Center(
                child: Padding(
                  padding: EdgeInsets.all(20),
                  child: Text(
                    'Le panier est vide',
                    style: TextStyle(color: Colors.grey),
                  ),
                ),
              )
            else
              ...cart.items.asMap().entries.map((entry) {
                final index = entry.key;
                final item = entry.value;
                return Card(
                  margin: EdgeInsets.only(bottom: 8),
                  child: ListTile(
                    title: Text(item.productName),
                    subtitle: Text('${item.price.toStringAsFixed(2)} CDF x ${item.quantity}'),
                    trailing: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Text(
                          '${item.subtotal.toStringAsFixed(2)} CDF',
                          style: TextStyle(
                            fontWeight: FontWeight.bold,
                            fontSize: 16,
                          ),
                        ),
                        SizedBox(width: 8),
                        IconButton(
                          icon: Icon(Icons.remove_circle_outline, color: Colors.red),
                          onPressed: () => cart.removeItem(index),
                        ),
                      ],
                    ),
                  ),
                );
              }).toList(),
          ],
        ),
      ),
    );
  }
  
  Widget _buildTotals(CartProvider cart) {
    return Card(
      color: Colors.blue.shade700,
      elevation: 4,
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          children: [
            _buildTotalRow('Sous-total', cart.subtotal, Colors.white70),
            if (cart.discount > 0)
              _buildTotalRow('Remise', -cart.discount, Colors.white70),
            if (cart.tax > 0)
              _buildTotalRow('Taxe', cart.tax, Colors.white70),
            Divider(color: Colors.white54, thickness: 1),
            _buildTotalRow(
              'TOTAL',
              cart.total,
              Colors.white,
              isBold: true,
              fontSize: 20,
            ),
          ],
        ),
      ),
    );
  }
  
  Widget _buildTotalRow(String label, double amount, Color textColor, {bool isBold = false, double fontSize = 16}) {
    return Padding(
      padding: EdgeInsets.symmetric(vertical: 4),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            label,
            style: TextStyle(
              fontSize: fontSize,
              fontWeight: isBold ? FontWeight.bold : FontWeight.normal,
              color: textColor,
            ),
          ),
          Text(
            '${amount.toStringAsFixed(2)} CDF',
            style: TextStyle(
              fontSize: fontSize,
              fontWeight: isBold ? FontWeight.bold : FontWeight.normal,
              color: textColor,
            ),
          ),
        ],
      ),
    );
  }
  
  Widget _buildCheckoutButton(CartProvider cart) {
    final canCheckout = cart.validationResult?.isValid ?? false;
    final change = _paidAmount - cart.total;
    
    return Column(
      children: [
        if (change >= 0)
          Container(
            padding: EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: Colors.green.shade50,
              borderRadius: BorderRadius.circular(8),
            ),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text('Monnaie à rendre:', style: TextStyle(fontSize: 16)),
                Text(
                  '${change.toStringAsFixed(2)} CDF',
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: Colors.green,
                  ),
                ),
              ],
            ),
          ),
        
        SizedBox(height: 16),
        
        ElevatedButton(
          onPressed: canCheckout && !cart.isProcessing
              ? () => _processCheckout(cart)
              : null,
          style: ElevatedButton.styleFrom(
            minimumSize: Size(double.infinity, 56),
            backgroundColor: Colors.blue.shade700,
            foregroundColor: Colors.white,
          ),
          child: cart.isProcessing
              ? CircularProgressIndicator(color: Colors.white)
              : Text(
                  'Confirmer le paiement',
                  style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                ),
        ),
      ],
    );
  }
  
  Future<void> _processCheckout(CartProvider cart) async {
    if (!_formKey.currentState!.validate()) return;
    
    try {
      final result = await cart.checkout(
        clientId: _selectedClientId,
        paymentMethod: _paymentMethod,
        paidAmount: _paidAmount,
        notes: _notes.isNotEmpty ? _notes : null,
      );
      
      if (result != null) {
        // Afficher message de succès
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Vente créée: ${result.sale.reference}'),
            backgroundColor: Colors.green,
          ),
        );
        
        // Naviguer vers écran de succès ou historique
        Navigator.of(context).popUntil((route) => route.isFirst);
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(e.toString()),
          backgroundColor: Colors.red,
        ),
      );
    }
  }
}
```

---

## 📝 Priorités d'Implémentation

### Phase 1 (MVP) - Semaine 1
- [ ] Créer les modèles de données (CartItem, Sale, ValidationResult, etc.)
- [ ] Implémenter SalesApiService avec les 4 endpoints
- [ ] Créer CartProvider pour gérer l'état du panier
- [ ] Écran checkout basique avec liste produits et totaux
- [ ] Implémentation création de vente

### Phase 2 - Semaine 2
- [ ] Validation temps réel du panier
- [ ] Gestion complète des erreurs avec messages user-friendly
- [ ] Écran historique des ventes avec pagination
- [ ] Écran détail d'une vente

### Phase 3 - Semaine 3
- [ ] Filtres avancés (date, méthode, statut)
- [ ] Recherche dans l'historique
- [ ] Mode hors-ligne avec synchronisation
- [ ] Impression/partage de factures

---

## ⚠️ Notes Importantes

1. **Authentification**: Tous les endpoints nécessitent un token Bearer dans le header
2. **Store Filtering**: Toujours passer `store_id` pour le multi-store
3. **Validation Locale**: Valider côté client avant d'appeler l'API pour une meilleure UX
4. **Loading States**: Afficher des indicateurs de chargement pendant les appels API
5. **Error Handling**: Gérer tous les cas d'erreur (réseau, validation, permissions)
6. **Monnaie Rendue**: Calculer automatiquement `change = paidAmount - total`
7. **Limites de Remise**: Respecter `max_discount_amount` défini par produit

---

## 🔧 Configuration Requise

### Dependencies Flutter
```yaml
dependencies:
  dio: ^5.0.0
  provider: ^6.0.0 # ou bloc, riverpod
  intl: ^0.18.0
  cached_network_image: ^3.2.0
  flutter_secure_storage: ^8.0.0
  infinite_scroll_pagination: ^4.0.0
```

### Configuration Dio
```dart
final dio = Dio(BaseOptions(
  baseUrl: 'http://192.168.1.193:8082',
  connectTimeout: Duration(seconds: 30),
  receiveTimeout: Duration(seconds: 30),
));

// Intercepteur pour le token
dio.interceptors.add(InterceptorsWrapper(
  onRequest: (options, handler) async {
    final token = await _storage.read(key: 'auth_token');
    if (token != null) {
      options.headers['Authorization'] = 'Bearer $token';
    }
    return handler.next(options);
  },
));
```

---

## 🐛 Dépannage des Erreurs Courantes

### Erreur 422: "The items.0.variant_id field is required"

**Cause**: Vous envoyez `product_id` au lieu de `variant_id`

**Solution**:
```dart
// ❌ Code incorrect qui cause l'erreur
{"items":[{"product_id":4,"quantity":1,"price":30.0}]}

// ✅ Code correct
{"items":[{"variant_id":4,"quantity":1,"price":30.0}]}
```

Assurez-vous que votre méthode `toJson()` de CartItem utilise bien `variant_id`:
```dart
Map<String, dynamic> toJson() => {
  'variant_id': variantId,  // ✅ Pas product_id
  'quantity': quantity,
  'price': price,
};
```

### Erreur 422: "variant_id is required" avec variant_id: null

**Cause**: Le `variant_id` est envoyé avec la valeur `null` au lieu d'un ID valide

**Exemple du problème**:
```json
{"items":[{"variant_id":null,"quantity":1,"price":30.0}]}
```

**Solution**: Vérifier que vous utilisez bien l'ID de la **variante du produit** et non null:

```dart
// ❌ INCORRECT - Création avec null
final item = CartItem(
  variantId: product.id,  // ❌ Utilise l'ID du produit au lieu de la variante
  productName: product.name,
  quantity: 1,
  price: product.price,
  originalPrice: product.price,
);

// ✅ CORRECT - Utiliser l'ID de la variante
final item = CartItem(
  variantId: product.defaultVariant?.id ?? product.variants.first.id,  // ✅ ID de la variante
  productName: product.name,
  quantity: 1,
  price: product.defaultVariant?.price ?? product.price,
  originalPrice: product.defaultVariant?.price ?? product.price,
);
```

**Vérification avant ajout au panier**:
```dart
void addToCart(Product product) {
  // Vérifier que le produit a une variante
  if (product.variants.isEmpty) {
    showError('Ce produit n\'a pas de variante disponible');
    return;
  }
  
  final variant = product.defaultVariant ?? product.variants.first;
  
  // Vérifier que l'ID de la variante n'est pas null
  if (variant.id == null) {
    showError('ID de variante invalide');
    return;
  }
  
  final item = CartItem(
    variantId: variant.id!,  // ✅ ID valide et non-null
    productName: product.name,
    quantity: 1,
    price: variant.price,
    originalPrice: variant.price,
  );
  
  cart.addItem(item);
}
```

### Erreur 400: "Stock insuffisant"

**Cause**: La quantité demandée dépasse le stock disponible

**Solution**: Implémenter la validation du panier en temps réel avec l'endpoint `/api/mobile/checkout/validate` avant de soumettre la vente.

### Erreur 400: "Montant payé insuffisant"

**Cause**: Le montant payé est inférieur au total de la vente

**Solution**: Ajouter une validation côté client:
```dart
if (_paidAmount < cart.total) {
  showError('Le montant payé doit être au moins ${cart.total.toStringAsFixed(2)} CDF');
  return;
}
```

### Erreur 403: "Vous n'avez pas accès à ce magasin"

**Cause**: L'utilisateur tente d'accéder à un magasin auquel il n'a pas accès

**Solution**: Toujours vérifier les magasins disponibles pour l'utilisateur et ne proposer que ceux-ci dans l'interface.

### Erreur 401: "Unauthenticated"

**Cause**: Token d'authentification manquant ou expiré

**Solution**: 
1. Vérifier que le token est bien ajouté dans le header `Authorization: Bearer {token}`
2. Gérer le refresh du token ou rediriger vers la page de connexion

---

## 📚 Ressources Additionnelles

- [Documentation API complète](MOBILE_CHECKOUT_API.md)
- [Architecture POS](POS_MODULAR_ARCHITECTURE.md)
- [Guide multi-store](MULTI_STORE_README.md)
