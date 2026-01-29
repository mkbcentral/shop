# Génération d'Étiquettes Produits avec Codes-Barres et QR Codes

## 📋 Vue d'ensemble

Cette fonctionnalité permet de générer des étiquettes PDF imprimables pour les produits avec :
- **Codes-barres** (Code 128)
- **QR Codes** (contenant les informations du produit)
- **Prix** formaté
- **Nom du produit** et référence
- **Catégorie**

Les étiquettes peuvent être imprimées et collées directement sur les produits.

---

## 🔗 Endpoints API

### 1. Générer des étiquettes pour un produit

```http
GET /api/mobile/products/{id}/labels
```

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**

| Paramètre | Type | Défaut | Description |
|-----------|------|--------|-------------|
| `format` | string | `small` | Format d'étiquette: `small`, `medium`, `large` |
| `columns` | integer | `3` | Nombre de colonnes (1-4) |
| `show_price` | boolean | `true` | Afficher le prix |
| `show_qr_code` | boolean | `true` | Afficher le QR code |
| `show_barcode` | boolean | `true` | Afficher le code-barres |
| `include_variants` | boolean | `false` | Inclure les variantes du produit |

**Exemple de requête:**
```bash
# Étiquettes petites (80x50mm) avec tous les éléments
GET /api/mobile/products/1/labels?format=small&columns=3&show_price=true&show_qr_code=true&show_barcode=true

# Étiquettes moyennes (100x70mm) avec variantes
GET /api/mobile/products/1/labels?format=medium&include_variants=true

# Étiquettes grandes (A4) sans prix
GET /api/mobile/products/1/labels?format=large&show_price=false
```

**Réponse:**
- Type: `application/pdf`
- Fichier PDF téléchargé directement

---

### 2. Générer des étiquettes pour plusieurs produits

```http
POST /api/mobile/products/labels/bulk
```

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body (JSON):**
```json
{
  "product_ids": [1, 2, 3, 4, 5],
  "format": "small",
  "columns": 3,
  "show_price": true,
  "show_qr_code": true,
  "show_barcode": true,
  "include_variants": false
}
```

**Paramètres:**

| Paramètre | Type | Requis | Description |
|-----------|------|--------|-------------|
| `product_ids` | array | ✅ | Liste des IDs de produits |
| `format` | string | ❌ | Format: `small`, `medium`, `large` |
| `columns` | integer | ❌ | Nombre de colonnes (1-4) |
| `show_price` | boolean | ❌ | Afficher le prix |
| `show_qr_code` | boolean | ❌ | Afficher le QR code |
| `show_barcode` | boolean | ❌ | Afficher le code-barres |
| `include_variants` | boolean | ❌ | Inclure les variantes |

**Exemple de requête:**
```bash
curl -X POST https://shop.mkbcentral.com/api/mobile/products/labels/bulk \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "product_ids": [1, 2, 3],
    "format": "small",
    "columns": 3,
    "show_price": true,
    "show_qr_code": true,
    "show_barcode": true
  }'
```

**Réponse:**
- Type: `application/pdf`
- Fichier: `etiquettes_produits_YYYYMMDDHHMMSS.pdf`

---

## 📏 Formats d'Étiquettes

### Format Small (80mm x 50mm)
- **Usage:** Petits produits, étiquettes de prix
- **Colonnes recommandées:** 3
- **Taille QR Code:** 20mm x 20mm
- **Éléments affichés:** Nom, Code-barres, QR Code (optionnel), Prix

### Format Medium (100mm x 70mm)
- **Usage:** Produits moyens, emballages standards
- **Colonnes recommandées:** 2
- **Taille QR Code:** 30mm x 30mm
- **Éléments affichés:** Nom, Variante, Code-barres, QR Code, Prix, Catégorie

### Format Large (A4)
- **Usage:** Grands produits, affiches promotionnelles
- **Colonnes recommandées:** 2
- **Taille QR Code:** 40mm x 40mm
- **Éléments affichés:** Tous les éléments avec plus d'espace

---

## 📊 Structure du QR Code

Le QR Code contient un JSON avec les informations suivantes :

### Pour un produit simple:
```json
{
  "type": "product",
  "id": 1,
  "reference": "PRD-001",
  "barcode": "1234567890",
  "name": "iPhone 15 Pro",
  "price": 1500000
}
```

### Pour une variante:
```json
{
  "type": "variant",
  "id": 5,
  "sku": "IPH-15-PRO-256-BLK",
  "product_id": 1,
  "name": "iPhone 15 Pro",
  "variant": "256GB - Noir",
  "price": 1500000
}
```

---

## 🎨 Personnalisation

### Options disponibles:

```php
$options = [
    'format' => 'small',          // small, medium, large
    'columns' => 3,                // 1-4 colonnes
    'show_price' => true,          // Afficher le prix
    'show_qr_code' => true,        // Afficher le QR code
    'show_barcode' => true,        // Afficher le code-barres
    'include_variants' => false,   // Inclure les variantes
];
```

---

## 📱 Intégration Flutter

### 1. Modèle de données

```dart
class LabelOptions {
  final String format;
  final int columns;
  final bool showPrice;
  final bool showQrCode;
  final bool showBarcode;
  final bool includeVariants;

  LabelOptions({
    this.format = 'small',
    this.columns = 3,
    this.showPrice = true,
    this.showQrCode = true,
    this.showBarcode = true,
    this.includeVariants = false,
  });

  Map<String, dynamic> toJson() => {
    'format': format,
    'columns': columns,
    'show_price': showPrice,
    'show_qr_code': showQrCode,
    'show_barcode': showBarcode,
    'include_variants': includeVariants,
  };
}
```

### 2. Service API

```dart
class ProductLabelService {
  final Dio _dio;

  ProductLabelService(this._dio);

  /// Générer des étiquettes pour un produit
  Future<void> generateProductLabels({
    required int productId,
    required LabelOptions options,
  }) async {
    try {
      final response = await _dio.get(
        '/api/mobile/products/$productId/labels',
        queryParameters: options.toJson(),
        options: Options(
          responseType: ResponseType.bytes,
          headers: {'Accept': 'application/pdf'},
        ),
      );

      // Sauvegarder le PDF
      await _savePdf(response.data, 'etiquette_produit_$productId.pdf');
    } catch (e) {
      throw Exception('Erreur lors de la génération des étiquettes: $e');
    }
  }

  /// Générer des étiquettes pour plusieurs produits
  Future<void> generateBulkLabels({
    required List<int> productIds,
    required LabelOptions options,
  }) async {
    try {
      final response = await _dio.post(
        '/api/mobile/products/labels/bulk',
        data: {
          'product_ids': productIds,
          ...options.toJson(),
        },
        options: Options(
          responseType: ResponseType.bytes,
          headers: {'Accept': 'application/pdf'},
        ),
      );

      // Sauvegarder le PDF
      await _savePdf(response.data, 'etiquettes_produits_${DateTime.now().millisecondsSinceEpoch}.pdf');
    } catch (e) {
      throw Exception('Erreur lors de la génération des étiquettes: $e');
    }
  }

  /// Sauvegarder le PDF
  Future<void> _savePdf(List<int> bytes, String filename) async {
    final directory = await getApplicationDocumentsDirectory();
    final file = File('${directory.path}/$filename');
    await file.writeAsBytes(bytes);
    
    // Ouvrir le PDF avec l'application par défaut
    await OpenFile.open(file.path);
  }
}
```

### 3. Widget de sélection d'options

```dart
class LabelOptionsDialog extends StatefulWidget {
  final Function(LabelOptions) onGenerate;

  const LabelOptionsDialog({Key? key, required this.onGenerate}) : super(key: key);

  @override
  _LabelOptionsDialogState createState() => _LabelOptionsDialogState();
}

class _LabelOptionsDialogState extends State<LabelOptionsDialog> {
  String _format = 'small';
  int _columns = 3;
  bool _showPrice = true;
  bool _showQrCode = true;
  bool _showBarcode = true;
  bool _includeVariants = false;

  @override
  Widget build(BuildContext context) {
    return AlertDialog(
      title: Text('Options d\'étiquettes'),
      content: SingleChildScrollView(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            // Format
            DropdownButtonFormField<String>(
              value: _format,
              decoration: InputDecoration(labelText: 'Format'),
              items: [
                DropdownMenuItem(value: 'small', child: Text('Petit (80x50mm)')),
                DropdownMenuItem(value: 'medium', child: Text('Moyen (100x70mm)')),
                DropdownMenuItem(value: 'large', child: Text('Grand (A4)')),
              ],
              onChanged: (value) => setState(() => _format = value!),
            ),
            
            SizedBox(height: 16),
            
            // Colonnes
            Row(
              children: [
                Text('Colonnes: '),
                Expanded(
                  child: Slider(
                    value: _columns.toDouble(),
                    min: 1,
                    max: 4,
                    divisions: 3,
                    label: _columns.toString(),
                    onChanged: (value) => setState(() => _columns = value.toInt()),
                  ),
                ),
                Text(_columns.toString()),
              ],
            ),
            
            // Options d'affichage
            SwitchListTile(
              title: Text('Afficher le prix'),
              value: _showPrice,
              onChanged: (value) => setState(() => _showPrice = value),
            ),
            SwitchListTile(
              title: Text('Afficher le QR code'),
              value: _showQrCode,
              onChanged: (value) => setState(() => _showQrCode = value),
            ),
            SwitchListTile(
              title: Text('Afficher le code-barres'),
              value: _showBarcode,
              onChanged: (value) => setState(() => _showBarcode = value),
            ),
            SwitchListTile(
              title: Text('Inclure les variantes'),
              value: _includeVariants,
              onChanged: (value) => setState(() => _includeVariants = value),
            ),
          ],
        ),
      ),
      actions: [
        TextButton(
          onPressed: () => Navigator.pop(context),
          child: Text('Annuler'),
        ),
        ElevatedButton(
          onPressed: () {
            final options = LabelOptions(
              format: _format,
              columns: _columns,
              showPrice: _showPrice,
              showQrCode: _showQrCode,
              showBarcode: _showBarcode,
              includeVariants: _includeVariants,
            );
            widget.onGenerate(options);
            Navigator.pop(context);
          },
          child: Text('Générer'),
        ),
      ],
    );
  }
}
```

### 4. Utilisation dans l'écran produit

```dart
// Dans ProductDetailsScreen
IconButton(
  icon: Icon(Icons.qr_code),
  onPressed: () async {
    await showDialog(
      context: context,
      builder: (context) => LabelOptionsDialog(
        onGenerate: (options) async {
          try {
            await context.read<ProductLabelService>().generateProductLabels(
              productId: widget.productId,
              options: options,
            );
            
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text('Étiquettes générées avec succès')),
            );
          } catch (e) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text('Erreur: $e'), backgroundColor: Colors.red),
            );
          }
        },
      ),
    );
  },
)

// Dans ProductListScreen - Action sur plusieurs produits
FloatingActionButton(
  onPressed: () {
    final selectedIds = _selectedProducts.map((p) => p.id).toList();
    
    if (selectedIds.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Sélectionnez au moins un produit')),
      );
      return;
    }
    
    showDialog(
      context: context,
      builder: (context) => LabelOptionsDialog(
        onGenerate: (options) async {
          try {
            await context.read<ProductLabelService>().generateBulkLabels(
              productIds: selectedIds,
              options: options,
            );
            
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text('${selectedIds.length} étiquettes générées')),
            );
          } catch (e) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text('Erreur: $e'), backgroundColor: Colors.red),
            );
          }
        },
      ),
    );
  },
  child: Icon(Icons.print),
)
```

---

## 🖨️ Impression

### Paramètres d'impression recommandés:

1. **Format Small (80x50mm)**
   - Imprimante: Étiqueteuse thermique
   - Papier: Étiquettes adhésives 80x50mm
   - Résolution: 203 DPI minimum

2. **Format Medium (100x70mm)**
   - Imprimante: Étiqueteuse ou imprimante laser
   - Papier: Étiquettes adhésives 100x70mm
   - Résolution: 300 DPI

3. **Format Large (A4)**
   - Imprimante: Laser ou jet d'encre
   - Papier: A4 standard ou étiquettes A4
   - Résolution: 600 DPI recommandé

---

## 📦 Dependencies

### Backend (Laravel)
```json
{
  "barryvdh/laravel-dompdf": "^3.1",
  "picqer/php-barcode-generator": "^3.2"
}
```

### Frontend (Flutter)
```yaml
dependencies:
  dio: ^5.0.0
  path_provider: ^2.0.0
  open_file: ^3.3.0
```

---

## ⚠️ Limitations et Notes

1. **QR Codes**: Utilise une API externe (api.qrserver.com) pour générer les QR codes. En production, considérez une solution locale.

2. **Codes-barres**: Supporte uniquement le format Code 128 (standard retail)

3. **Performance**: La génération de nombreuses étiquettes (>100) peut prendre du temps. Considérez une file d'attente pour les grandes quantités.

4. **Taille fichier**: Les PDFs avec QR codes peuvent être volumineux (2-5MB pour 50 étiquettes)

5. **Variantes**: Si `include_variants=true`, chaque variante aura sa propre étiquette

---

## 🔧 Personnalisation Avancée

### Modifier le style des étiquettes:

Éditez le fichier `resources/views/pdf/product-labels.blade.php` pour personnaliser:
- Couleurs
- Polices
- Disposition
- Taille des éléments

### Ajouter un logo d'entreprise:

```php
// Dans ProductLabelService.php
$pdf = Pdf::loadView('pdf.product-labels', [
    'labels' => $labelData,
    'company_logo' => asset('images/logo.png'),
    // ...
]);
```

---

## 🚀 Améliorations Futures

- [ ] Génération offline des QR codes
- [ ] Support de formats supplémentaires (EAN-13, UPC, QR Code 2D)
- [ ] Templates d'étiquettes personnalisables
- [ ] Impression directe sans téléchargement
- [ ] Aperçu avant impression
- [ ] Support multi-langues
- [ ] Intégration avec imprimantes Bluetooth

---

**Date de mise en œuvre**: 29 janvier 2026  
**Version**: 1.0.0  
**Status**: ✅ Implémenté et fonctionnel
