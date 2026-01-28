# 🎯 Flutter - Création de Produit avec Workflow Type → Catégories

## 📋 Concept

Lors de la création d'un produit, l'utilisateur doit **d'abord choisir un type de produit**, puis les **catégories sont automatiquement filtrées** en fonction du type sélectionné.

Ce workflow est identique à celui utilisé dans l'interface web (ProductModal).

---

## 🔄 Workflow Visuel

```
┌─────────────────────────────────────┐
│  📦 Nouveau Produit                 │
├─────────────────────────────────────┤
│                                     │
│  ① CHOISIR LE TYPE                  │
│  ┌─────────────────────────────────┐│
│  │ [🔘] Vêtements                  ││
│  │      → Gestion des variantes    ││
│  │                                 ││
│  │ [ ] Services                    ││
│  │      → Pas de stock             ││
│  │                                 ││
│  │ [ ] Produits Digitaux           ││
│  │      → Téléchargements          ││
│  └─────────────────────────────────┘│
│                                     │
├─────────────────────────────────────┤
│  ② CATÉGORIE (filtrée par type)     │
│  ┌─────────────────────────────────┐│
│  │ [Sélectionner une catégorie ▼] ││
│  │                                 ││
│  │ Options disponibles :           ││
│  │  • T-shirts                     ││
│  │  • Pantalons                    ││
│  │  • Robes                        ││
│  │  • Accessoires                  ││
│  └─────────────────────────────────┘│
│                                     │
├─────────────────────────────────────┤
│  ③ INFORMATIONS GÉNÉRALES           │
│  ┌─────────────────────────────────┐│
│  │ Nom: [_____________________]    ││
│  │                                 ││
│  │ Référence: [VET-000123] 🔄      ││
│  │            (auto-généré)        ││
│  │                                 ││
│  │ Prix d'achat: [_______] FCFA    ││
│  │ Prix de vente: [_______] FCFA   ││
│  └─────────────────────────────────┘│
│                                     │
├─────────────────────────────────────┤
│  ④ ATTRIBUTS DYNAMIQUES             │
│  (Affichés si le type a des attributs)│
│  ┌─────────────────────────────────┐│
│  │ Taille: *                       ││
│  │ [S] [M] [L] [XL] [XXL]          ││
│  │                                 ││
│  │ Couleur: *                      ││
│  │ [🔴 Rouge] [🔵 Bleu] [⚫ Noir]  ││
│  │                                 ││
│  │ Matière:                        ││
│  │ [Coton ▼]                       ││
│  └─────────────────────────────────┘│
│                                     │
│         [Annuler]  [Créer]          │
└─────────────────────────────────────┘

LÉGENDE:
* = Champ requis pour les variantes
🔄 = Généré automatiquement
▼ = Dropdown
```

---

## 🚀 API Endpoints

### Base URL
```
/api/mobile/products
```

### 1. Récupérer les données du formulaire (Recommandé)

**Endpoint tout-en-un pour initialiser le formulaire**

```http
GET /api/mobile/products/create-form-data
```

**Sans type sélectionné:**
```bash
GET /api/mobile/products/create-form-data
```

**Avec type pré-sélectionné:**
```bash
GET /api/mobile/products/create-form-data?product_type_id=1
```

**Réponse:**
```json
{
  "success": true,
  "data": {
    "product_types": [
      {
        "id": 1,
        "name": "Vêtements",
        "slug": "vetements",
        "description": "Vêtements et accessoires",
        "has_variants": true,
        "has_stock_management": true,
        "icon": "shirt",
        "attributes": [
          {
            "id": 1,
            "name": "Taille",
            "type": "select",
            "is_variant": true,
            "is_required": true,
            "options": ["XS", "S", "M", "L", "XL", "XXL"]
          },
          {
            "id": 2,
            "name": "Couleur",
            "type": "color",
            "is_variant": true,
            "is_required": true,
            "options": ["Rouge", "Bleu", "Noir", "Blanc"]
          }
        ]
      },
      {
        "id": 2,
        "name": "Services",
        "slug": "services",
        "description": "Prestations de services",
        "has_variants": false,
        "has_stock_management": false,
        "icon": "briefcase",
        "attributes": []
      }
    ],
    "categories": [
      {
        "id": 1,
        "name": "T-shirts",
        "slug": "t-shirts",
        "product_type_id": 1
      },
      {
        "id": 2,
        "name": "Pantalons",
        "slug": "pantalons",
        "product_type_id": 1
      }
    ],
    "selected_product_type_id": 1
  }
}
```

---

### 2. Alternative : Endpoints séparés

Si vous préférez des appels séparés pour plus de contrôle :

#### a) Liste des types de produits

```http
GET /api/mobile/products/product-types?with_attributes=true
```

**Paramètres:**
- `with_attributes` (bool, optionnel) : Inclure les attributs dans la réponse

#### b) Catégories filtrées par type

```http
GET /api/mobile/products/categories?product_type_id=1
```

**Paramètres:**
- `product_type_id` (int, optionnel) : Filtre par type de produit

**Sans filtre** → Toutes les catégories
**Avec filtre** → Uniquement les catégories du type choisi

#### c) Détails d'un type spécifique

```http
GET /api/mobile/products/product-types/1
```

**Réponse:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Vêtements",
    "slug": "vetements",
    "has_variants": true,
    "attributes": [...],
    "categories": [
      {"id": 1, "name": "T-shirts"},
      {"id": 2, "name": "Pantalons"}
    ]
  }
}
```

#### d) Génération de la référence

```http
GET /api/mobile/products/generate-reference?category_id=1
```

**Réponse:**
```json
{
  "success": true,
  "data": {
    "reference": "VET-000123"
  }
}
```

---

## 💻 Implémentation Flutter

### Étape 1: Service API

```dart
// lib/services/product_service.dart

class ProductService {
  final Dio _dio;
  
  ProductService(this._dio);

  /// Récupère toutes les données pour le formulaire de création
  /// 
  /// Si [productTypeId] est fourni, les catégories seront déjà filtrées
  Future<ProductFormData> getCreateFormData({int? productTypeId}) async {
    final response = await _dio.get(
      '/api/mobile/products/create-form-data',
      queryParameters: {
        if (productTypeId != null) 'product_type_id': productTypeId,
      },
    );
    
    return ProductFormData.fromJson(response.data['data']);
  }

  /// Récupère les catégories filtrées par type de produit
  Future<List<Category>> getCategoriesByType(int productTypeId) async {
    final response = await _dio.get(
      '/api/mobile/products/categories',
      queryParameters: {'product_type_id': productTypeId},
    );
    
    return (response.data['data'] as List)
        .map((json) => Category.fromJson(json))
        .toList();
  }

  /// Génère une référence unique pour un produit
  Future<String> generateReference(int categoryId) async {
    final response = await _dio.get(
      '/api/mobile/products/generate-reference',
      queryParameters: {'category_id': categoryId},
    );
    
    return response.data['data']['reference'];
  }

  /// Crée un nouveau produit
  Future<Product> createProduct(Map<String, dynamic> data) async {
    final response = await _dio.post('/api/mobile/products', data: data);
    return Product.fromJson(response.data['data']);
  }
}
```

---

### Étape 2: Modèles de données

```dart
// lib/models/product_form_data.dart

class ProductFormData {
  final List<ProductType> productTypes;
  final List<Category> categories;
  final int? selectedProductTypeId;

  ProductFormData({
    required this.productTypes,
    required this.categories,
    this.selectedProductTypeId,
  });

  factory ProductFormData.fromJson(Map<String, dynamic> json) {
    return ProductFormData(
      productTypes: (json['product_types'] as List)
          .map((e) => ProductType.fromJson(e))
          .toList(),
      categories: (json['categories'] as List)
          .map((e) => Category.fromJson(e))
          .toList(),
      selectedProductTypeId: json['selected_product_type_id'],
    );
  }
}

// lib/models/product_type.dart

class ProductType {
  final int id;
  final String name;
  final String slug;
  final String? description;
  final bool hasVariants;
  final bool hasStockManagement;
  final String? icon;
  final List<ProductAttribute>? attributes;

  ProductType({
    required this.id,
    required this.name,
    required this.slug,
    this.description,
    required this.hasVariants,
    required this.hasStockManagement,
    this.icon,
    this.attributes,
  });

  factory ProductType.fromJson(Map<String, dynamic> json) {
    return ProductType(
      id: json['id'],
      name: json['name'],
      slug: json['slug'],
      description: json['description'],
      hasVariants: json['has_variants'] ?? false,
      hasStockManagement: json['has_stock_management'] ?? true,
      icon: json['icon'],
      attributes: json['attributes'] != null
          ? (json['attributes'] as List)
              .map((e) => ProductAttribute.fromJson(e))
              .toList()
          : null,
    );
  }
}

// lib/models/product_attribute.dart

class ProductAttribute {
  final int id;
  final String name;
  final String type; // text, number, select, color, textarea
  final bool isVariant;
  final bool isRequired;
  final List<String>? options;
  final String? defaultValue;

  ProductAttribute({
    required this.id,
    required this.name,
    required this.type,
    required this.isVariant,
    required this.isRequired,
    this.options,
    this.defaultValue,
  });

  factory ProductAttribute.fromJson(Map<String, dynamic> json) {
    return ProductAttribute(
      id: json['id'],
      name: json['name'],
      type: json['type'],
      isVariant: json['is_variant'] ?? false,
      isRequired: json['is_required'] ?? false,
      options: json['options'] != null 
          ? List<String>.from(json['options'])
          : null,
      defaultValue: json['default_value'],
    );
  }
}

// lib/models/category.dart

class Category {
  final int id;
  final String name;
  final String slug;
  final int? parentId;
  final int? productTypeId;

  Category({
    required this.id,
    required this.name,
    required this.slug,
    this.parentId,
    this.productTypeId,
  });

  factory Category.fromJson(Map<String, dynamic> json) {
    return Category(
      id: json['id'],
      name: json['name'],
      slug: json['slug'],
      parentId: json['parent_id'],
      productTypeId: json['product_type_id'],
    );
  }
}
```

---

### Étape 3: State Management (Riverpod)

```dart
// lib/providers/product_form_provider.dart

import 'package:flutter_riverpod/flutter_riverpod.dart';

/// Provider pour les données du formulaire
final productFormDataProvider = FutureProvider.family<ProductFormData, int?>(
  (ref, productTypeId) async {
    final service = ref.read(productServiceProvider);
    return service.getCreateFormData(productTypeId: productTypeId);
  },
);

/// Provider pour le type de produit sélectionné
final selectedProductTypeProvider = StateProvider<ProductType?>((ref) => null);

/// Provider pour les catégories filtrées
final filteredCategoriesProvider = Provider<List<Category>>((ref) {
  final selectedType = ref.watch(selectedProductTypeProvider);
  final formDataAsync = ref.watch(productFormDataProvider(selectedType?.id));
  
  return formDataAsync.when(
    data: (formData) => formData.categories,
    loading: () => [],
    error: (_, __) => [],
  );
});

/// Provider pour la catégorie sélectionnée
final selectedCategoryProvider = StateProvider<Category?>((ref) => null);

/// Provider pour la référence auto-générée
final productReferenceProvider = FutureProvider.family<String, int>(
  (ref, categoryId) async {
    final service = ref.read(productServiceProvider);
    return service.generateReference(categoryId);
  },
);
```

---

### Étape 4: Écran de création

```dart
// lib/screens/products/create_product_screen.dart

class CreateProductScreen extends ConsumerStatefulWidget {
  const CreateProductScreen({Key? key}) : super(key: key);

  @override
  ConsumerState<CreateProductScreen> createState() => _CreateProductScreenState();
}

class _CreateProductScreenState extends ConsumerState<CreateProductScreen> {
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _costPriceController = TextEditingController();
  final _priceController = TextEditingController();
  
  String? _generatedReference;
  
  @override
  void dispose() {
    _nameController.dispose();
    _costPriceController.dispose();
    _priceController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final selectedType = ref.watch(selectedProductTypeProvider);
    final formDataAsync = ref.watch(productFormDataProvider(selectedType?.id));
    final filteredCategories = ref.watch(filteredCategoriesProvider);
    final selectedCategory = ref.watch(selectedCategoryProvider);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Nouveau Produit'),
        actions: [
          IconButton(
            icon: const Icon(Icons.save),
            onPressed: _saveProduct,
          ),
        ],
      ),
      body: formDataAsync.when(
        loading: () => const Center(child: CircularProgressIndicator()),
        error: (error, stack) => Center(
          child: Text('Erreur: $error'),
        ),
        data: (formData) => Form(
          key: _formKey,
          child: ListView(
            padding: const EdgeInsets.all(16),
            children: [
              // ÉTAPE 1: Type de produit
              _buildSection(
                title: '① Type de produit',
                icon: Icons.category,
                child: _buildProductTypeSelector(formData.productTypes),
              ),
              
              const SizedBox(height: 24),
              
              // ÉTAPE 2: Catégorie (affichée seulement si type sélectionné)
              if (selectedType != null) ...[
                _buildSection(
                  title: '② Catégorie',
                  icon: Icons.folder,
                  child: _buildCategoryDropdown(filteredCategories),
                ),
                const SizedBox(height: 24),
              ],
              
              // ÉTAPE 3: Informations générales
              if (selectedCategory != null) ...[
                _buildSection(
                  title: '③ Informations générales',
                  icon: Icons.info_outline,
                  child: Column(
                    children: [
                      TextFormField(
                        controller: _nameController,
                        decoration: const InputDecoration(
                          labelText: 'Nom du produit *',
                          border: OutlineInputBorder(),
                        ),
                        validator: (value) {
                          if (value == null || value.isEmpty) {
                            return 'Le nom est requis';
                          }
                          return null;
                        },
                      ),
                      const SizedBox(height: 16),
                      
                      // Référence auto-générée
                      _buildReferenceField(selectedCategory!.id),
                      
                      const SizedBox(height: 16),
                      
                      Row(
                        children: [
                          Expanded(
                            child: TextFormField(
                              controller: _costPriceController,
                              decoration: const InputDecoration(
                                labelText: 'Prix d\'achat *',
                                border: OutlineInputBorder(),
                                suffix: Text('FCFA'),
                              ),
                              keyboardType: TextInputType.number,
                              validator: (value) {
                                if (value == null || value.isEmpty) {
                                  return 'Requis';
                                }
                                return null;
                              },
                            ),
                          ),
                          const SizedBox(width: 16),
                          Expanded(
                            child: TextFormField(
                              controller: _priceController,
                              decoration: const InputDecoration(
                                labelText: 'Prix de vente *',
                                border: OutlineInputBorder(),
                                suffix: Text('FCFA'),
                              ),
                              keyboardType: TextInputType.number,
                              validator: (value) {
                                if (value == null || value.isEmpty) {
                                  return 'Requis';
                                }
                                return null;
                              },
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 24),
              ],
              
              // ÉTAPE 4: Attributs dynamiques
              if (selectedType != null && 
                  selectedType.hasVariants && 
                  selectedType.attributes != null) ...[
                _buildSection(
                  title: '④ Attributs du produit',
                  icon: Icons.tune,
                  child: _buildDynamicAttributes(selectedType.attributes!),
                ),
              ],
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildSection({
    required String title,
    required IconData icon,
    required Widget child,
  }) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Icon(icon, color: Theme.of(context).primaryColor),
                const SizedBox(width: 8),
                Text(
                  title,
                  style: Theme.of(context).textTheme.titleMedium?.copyWith(
                        fontWeight: FontWeight.bold,
                      ),
                ),
              ],
            ),
            const Divider(height: 24),
            child,
          ],
        ),
      ),
    );
  }

  Widget _buildProductTypeSelector(List<ProductType> types) {
    final selectedType = ref.watch(selectedProductTypeProvider);
    
    return Wrap(
      spacing: 8,
      runSpacing: 8,
      children: types.map((type) {
        final isSelected = selectedType?.id == type.id;
        
        return ChoiceChip(
          label: Row(
            mainAxisSize: MainAxisSize.min,
            children: [
              if (type.icon != null) ...[
                Icon(
                  _getIconData(type.icon!),
                  size: 18,
                  color: isSelected ? Colors.white : null,
                ),
                const SizedBox(width: 8),
              ],
              Text(type.name),
            ],
          ),
          selected: isSelected,
          onSelected: (selected) {
            if (selected) {
              ref.read(selectedProductTypeProvider.notifier).state = type;
              // Réinitialiser la catégorie sélectionnée
              ref.read(selectedCategoryProvider.notifier).state = null;
              _generatedReference = null;
            }
          },
        );
      }).toList(),
    );
  }

  Widget _buildCategoryDropdown(List<Category> categories) {
    final selectedCategory = ref.watch(selectedCategoryProvider);
    
    if (categories.isEmpty) {
      return const Text(
        'Aucune catégorie disponible pour ce type de produit.',
        style: TextStyle(fontStyle: FontStyle.italic),
      );
    }
    
    return DropdownButtonFormField<Category>(
      value: selectedCategory,
      decoration: const InputDecoration(
        labelText: 'Sélectionner une catégorie *',
        border: OutlineInputBorder(),
      ),
      items: categories.map((category) {
        return DropdownMenuItem(
          value: category,
          child: Text(category.name),
        );
      }).toList(),
      onChanged: (category) {
        ref.read(selectedCategoryProvider.notifier).state = category;
        
        // Générer automatiquement la référence
        if (category != null) {
          _generateReference(category.id);
        }
      },
      validator: (value) {
        if (value == null) {
          return 'La catégorie est requise';
        }
        return null;
      },
    );
  }

  Widget _buildReferenceField(int categoryId) {
    if (_generatedReference == null) {
      return const LinearProgressIndicator();
    }
    
    return TextFormField(
      initialValue: _generatedReference,
      decoration: InputDecoration(
        labelText: 'Référence',
        border: const OutlineInputBorder(),
        suffixIcon: IconButton(
          icon: const Icon(Icons.refresh),
          onPressed: () => _generateReference(categoryId),
          tooltip: 'Régénérer',
        ),
      ),
      readOnly: true,
    );
  }

  Widget _buildDynamicAttributes(List<ProductAttribute> attributes) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: attributes.map((attr) {
        return Padding(
          padding: const EdgeInsets.only(bottom: 16),
          child: _buildAttributeField(attr),
        );
      }).toList(),
    );
  }

  Widget _buildAttributeField(ProductAttribute attribute) {
    switch (attribute.type) {
      case 'select':
        return _buildSelectAttribute(attribute);
      case 'color':
        return _buildColorAttribute(attribute);
      case 'text':
        return _buildTextAttribute(attribute);
      default:
        return _buildTextAttribute(attribute);
    }
  }

  Widget _buildSelectAttribute(ProductAttribute attribute) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          '${attribute.name}${attribute.isRequired ? ' *' : ''}',
          style: const TextStyle(fontWeight: FontWeight.w500),
        ),
        const SizedBox(height: 8),
        Wrap(
          spacing: 8,
          runSpacing: 8,
          children: attribute.options!.map((option) {
            return FilterChip(
              label: Text(option),
              selected: false, // TODO: Gérer la sélection
              onSelected: (selected) {
                // TODO: Sauvegarder la valeur
              },
            );
          }).toList(),
        ),
      ],
    );
  }

  Widget _buildColorAttribute(ProductAttribute attribute) {
    // Implémentation similaire avec des chips colorés
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          '${attribute.name}${attribute.isRequired ? ' *' : ''}',
          style: const TextStyle(fontWeight: FontWeight.w500),
        ),
        const SizedBox(height: 8),
        // Chips de couleurs
        Wrap(
          spacing: 8,
          children: attribute.options!.map((color) {
            return ActionChip(
              label: Text(color),
              onPressed: () {
                // TODO: Sauvegarder la couleur
              },
            );
          }).toList(),
        ),
      ],
    );
  }

  Widget _buildTextAttribute(ProductAttribute attribute) {
    return TextFormField(
      decoration: InputDecoration(
        labelText: '${attribute.name}${attribute.isRequired ? ' *' : ''}',
        border: const OutlineInputBorder(),
      ),
      validator: attribute.isRequired
          ? (value) {
              if (value == null || value.isEmpty) {
                return 'Ce champ est requis';
              }
              return null;
            }
          : null,
    );
  }

  Future<void> _generateReference(int categoryId) async {
    final service = ref.read(productServiceProvider);
    
    try {
      final reference = await service.generateReference(categoryId);
      setState(() {
        _generatedReference = reference;
      });
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Erreur: $e')),
      );
    }
  }

  Future<void> _saveProduct() async {
    if (!_formKey.currentState!.validate()) {
      return;
    }
    
    final selectedType = ref.read(selectedProductTypeProvider);
    final selectedCategory = ref.read(selectedCategoryProvider);
    
    if (selectedType == null || selectedCategory == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Veuillez sélectionner un type et une catégorie')),
      );
      return;
    }
    
    // TODO: Construire les données et appeler l'API
    final data = {
      'name': _nameController.text,
      'reference': _generatedReference,
      'category_id': selectedCategory.id,
      'product_type_id': selectedType.id,
      'cost_price': double.parse(_costPriceController.text),
      'price': double.parse(_priceController.text),
      'status': 'active',
      // TODO: Ajouter les attributs dynamiques
    };
    
    try {
      final service = ref.read(productServiceProvider);
      await service.createProduct(data);
      
      if (mounted) {
        Navigator.pop(context);
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Produit créé avec succès')),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Erreur: $e')),
      );
    }
  }

  IconData _getIconData(String iconName) {
    // Mapper les noms d'icônes
    switch (iconName) {
      case 'shirt':
        return Icons.checkroom;
      case 'briefcase':
        return Icons.work;
      default:
        return Icons.category;
    }
  }
}
```

---

## 📊 Diagramme de Séquence

```
User          Flutter App         API Backend         Database
  │                │                    │                  │
  │  Ouvrir        │                    │                  │
  │  formulaire    │                    │                  │
  │───────────────>│                    │                  │
  │                │  GET /create-      │                  │
  │                │  form-data         │                  │
  │                │───────────────────>│                  │
  │                │                    │  SELECT types    │
  │                │                    │  + categories    │
  │                │                    │─────────────────>│
  │                │                    │<─────────────────│
  │                │  Types +           │                  │
  │                │  Categories        │                  │
  │                │<───────────────────│                  │
  │  Afficher      │                    │                  │
  │  types         │                    │                  │
  │<───────────────│                    │                  │
  │                │                    │                  │
  │  Sélectionner  │                    │                  │
  │  "Vêtements"   │                    │                  │
  │───────────────>│                    │                  │
  │                │  Filtrer           │                  │
  │                │  catégories        │                  │
  │                │  localement        │                  │
  │  Afficher      │                    │                  │
  │  catégories    │                    │                  │
  │  filtrées      │                    │                  │
  │<───────────────│                    │                  │
  │                │                    │                  │
  │  Sélectionner  │                    │                  │
  │  "T-shirts"    │                    │                  │
  │───────────────>│                    │                  │
  │                │  GET /generate-    │                  │
  │                │  reference         │                  │
  │                │  ?category_id=1    │                  │
  │                │───────────────────>│                  │
  │                │                    │  Générer ref     │
  │                │                    │─────────────────>│
  │                │                    │<─────────────────│
  │                │  "VET-000123"      │                  │
  │                │<───────────────────│                  │
  │  Afficher      │                    │                  │
  │  référence     │                    │                  │
  │<───────────────│                    │                  │
  │                │                    │                  │
  │  Remplir       │                    │                  │
  │  formulaire    │                    │                  │
  │───────────────>│                    │                  │
  │                │                    │                  │
  │  Cliquer       │                    │                  │
  │  "Créer"       │                    │                  │
  │───────────────>│                    │                  │
  │                │  POST /products    │                  │
  │                │───────────────────>│                  │
  │                │                    │  INSERT product  │
  │                │                    │─────────────────>│
  │                │                    │<─────────────────│
  │                │  Produit créé      │                  │
  │                │<───────────────────│                  │
  │  Confirmation  │                    │                  │
  │<───────────────│                    │                  │
```

---

## ✅ Checklist d'implémentation

### Phase 1 : Setup de base
- [ ] Créer les modèles (`ProductType`, `ProductAttribute`, `Category`, `ProductFormData`)
- [ ] Implémenter `ProductService` avec les méthodes API
- [ ] Créer les providers Riverpod

### Phase 2 : UI de base
- [ ] Créer `CreateProductScreen`
- [ ] Implémenter le sélecteur de type de produit (étape 1)
- [ ] Implémenter le dropdown de catégories filtrées (étape 2)
- [ ] Implémenter les champs d'informations générales (étape 3)

### Phase 3 : Fonctionnalités avancées
- [ ] Génération automatique de la référence
- [ ] Affichage des attributs dynamiques (étape 4)
- [ ] Gestion des attributs de type `select`
- [ ] Gestion des attributs de type `color`
- [ ] Gestion des attributs de type `text`

### Phase 4 : Validation & Soumission
- [ ] Validation du formulaire
- [ ] Gestion des champs requis pour les variantes
- [ ] Appel API pour créer le produit
- [ ] Gestion des erreurs
- [ ] Feedback utilisateur (SnackBar, Dialog)

### Phase 5 : Tests
- [ ] Tests unitaires des modèles
- [ ] Tests unitaires du service
- [ ] Tests d'intégration du formulaire
- [ ] Tests de bout en bout

---

## 🎨 Design Recommandations

### Ordre des étapes
1. **Type** → Toujours visible en premier
2. **Catégorie** → Apparaît après sélection du type
3. **Infos générales** → Apparaît après sélection de la catégorie
4. **Attributs** → Apparaît en dernier (si le type a des variantes)

### UI/UX
- ✅ Utiliser des **ChoiceChip** pour les types de produits
- ✅ Utiliser un **DropdownButton** pour les catégories
- ✅ Afficher la référence avec un bouton de **régénération**
- ✅ Grouper les attributs dans une section dédiée
- ✅ Marquer clairement les champs **requis** (*)
- ✅ Afficher des **messages d'aide** contextuels

### Validation
- Type de produit : **Requis**
- Catégorie : **Requis**
- Nom : **Requis**
- Référence : **Auto-généré** (lecture seule)
- Prix : **Requis**
- Attributs variant : **Requis si `is_variant = true`**

---

## 📝 Notes importantes

1. **Filtrage local vs API**
   - Si `create-form-data` est utilisé, le filtrage des catégories peut se faire **côté client**
   - Sinon, appeler `/categories?product_type_id=X` à chaque changement de type

2. **Génération de référence**
   - Automatique lors de la sélection de la catégorie
   - Bouton de régénération disponible si l'utilisateur veut une autre référence

3. **Attributs dynamiques**
   - Affichés uniquement si le type a `has_variants = true`
   - Les attributs avec `is_variant = true` créeront des variantes multiples
   - Les attributs avec `is_required = true` sont obligatoires

4. **Performance**
   - Mettre en cache les données de `create-form-data`
   - Éviter les appels API répétés pour les mêmes données

---

## 🐛 Gestion des erreurs courantes

| Erreur | Cause | Solution |
|--------|-------|----------|
| Catégories vides | Aucune catégorie associée au type | Afficher un message + lien pour créer des catégories |
| Référence en double | Conflit lors de la génération | Régénérer automatiquement (côté backend) |
| Validation échouée | Champs requis manquants | Afficher les erreurs sous chaque champ |
| API timeout | Connexion lente | Afficher un loader + possibilité de réessayer |

---

**Document créé le 28 janvier 2026**
**API Version:** Mobile v1
**Compatible avec:** Flutter 3.x + Riverpod 2.x
