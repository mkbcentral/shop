# 🏗️ Architecture Backend - Système de Gestion de Boutique

## 📋 Structure en couches

```
Controllers → Actions → Services → Repositories → Models → Database
```

## ✅ **RÈGLE D'ARCHITECTURE**

### Actions
- ✅ **Utilisent SERVICES** pour toute logique métier
- ✅ **Utilisent REPOSITORIES** uniquement pour lectures simples (find, search)
- ✅ Gèrent les validations de données entrantes
- ✅ Orchestrent les cas d'usage complexes

### Services
- ✅ **Utilisent REPOSITORIES** pour accès aux données
- ✅ Contiennent toute la logique métier
- ✅ Gèrent les transactions (DB::transaction)
- ✅ Effectuent les validations complexes
- ✅ Calculent et transforment les données

### Repositories
- ✅ **Utilisent MODELS** pour requêtes Eloquent
- ✅ Encapsulent les requêtes de base de données
- ✅ Fournissent des méthodes de recherche et filtrage
- ✅ Pas de logique métier

## 📊 Récapitulatif des fichiers

### **9 Repositories**
- CategoryRepository
- ProductRepository
- ProductVariantRepository
- ClientRepository
- SaleRepository
- StockMovementRepository
- InvoiceRepository
- SupplierRepository
- PurchaseRepository

### **8 Services**
- CategoryService ✅
- ProductService ✅
- ClientService ✅
- SaleService ✅
- StockService ✅
- InvoiceService ✅
- SupplierService ✅
- PurchaseService ✅

### **34 Actions**

#### **Catégories (3)**
- CreateCategoryAction
- UpdateCategoryAction
- DeleteCategoryAction

#### **Produits (6)**
- CreateProductAction
- UpdateProductAction
- DeleteProductAction
- CreateVariantAction
- UpdateVariantAction
- DeleteVariantAction

#### **Clients (3)**
- CreateClientAction
- UpdateClientAction
- DeleteClientAction

#### **Fournisseurs (3)**
- CreateSupplierAction
- UpdateSupplierAction
- DeleteSupplierAction

#### **Achats (3)**
- CreatePurchaseAction
- UpdatePurchaseAction
- DeletePurchaseAction

#### **Ventes (5)**
- CreateSaleAction
- UpdateSaleAction
- DeleteSaleAction
- ProcessSaleAction (vente complète + facture)
- RefundSaleAction (remboursement + restauration stock)

#### **Stock (6)**
- AddStockAction (entrée)
- RemoveStockAction (sortie)
- AdjustStockAction (ajustement)
- BulkStockUpdateAction (masse)
- PerformInventoryAction (inventaire)
- (+ TransferStock et ReturnStock dans StockService)

#### **Factures (3)**
- CreateInvoiceAction
- UpdateInvoiceAction
- DeleteInvoiceAction

#### **Rapports (2)**
- GenerateSalesReportAction
- GenerateStockReportAction

#### **Import (1)**
- ImportProductsAction

## 🎯 Exemples d'utilisation

### ✅ **BON - Action utilise Service**
```php
class DeleteProductAction
{
    public function __construct(
        private ProductService $productService
    ) {}

    public function execute(int $productId): bool
    {
        // Le service gère tout : validation, vérifications, suppression
        return $this->productService->deleteProduct($productId);
    }
}
```

### ❌ **MAUVAIS - Action mélange Service et Repository**
```php
class DeleteProductAction
{
    public function __construct(
        private ProductService $productService,
        private ProductRepository $productRepository  // ❌ Inutile
    ) {}

    public function execute(int $productId): bool
    {
        $product = $this->productRepository->find($productId);  // ❌
        // validation manuelle...
        return $this->productService->deleteProduct($productId);
    }
}
```

### ✅ **BON - Service utilise Repository**
```php
class ProductService
{
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    public function deleteProduct(int $productId): bool
    {
        $product = $this->productRepository->find($productId);
        
        if (!$product) {
            throw new \Exception("Product not found");
        }

        // Vérifications métier
        if ($product->variants()->whereHas('saleItems')->exists()) {
            throw new \Exception("Cannot delete product with sales");
        }

        return $this->productRepository->delete($product);
    }
}
```

## 🔄 Flux typique

### Création d'une vente complète :

```
Controller (API)
    ↓
ProcessSaleAction
    ↓
SaleService::createSale()
    ↓ (validation stock)
    ↓ (création vente + items)
    ↓ (mouvements stock auto)
    ↓
InvoiceService::createFromSale()
    ↓
Retourne : Sale + Invoice
```

### Ajustement de stock :

```
Controller (API)
    ↓
AdjustStockAction
    ↓ (validation quantité)
    ↓
StockService::adjustStock()
    ↓ (calcul différence)
    ↓ (création mouvement)
    ↓ (mise à jour stock auto)
    ↓
Retourne : StockMovement
```

## 📝 Avantages de cette architecture

✅ **Séparation des responsabilités**
- Actions = Cas d'usage
- Services = Logique métier
- Repositories = Accès données

✅ **Testabilité**
- Chaque couche testable indépendamment
- Mock facile avec les interfaces

✅ **Maintenabilité**
- Logique centralisée dans les services
- Facile à modifier sans impacter le reste

✅ **Réutilisabilité**
- Services utilisables depuis n'importe où
- Actions composables

✅ **Transactions gérées**
- Tout dans DB::transaction quand nécessaire
- Rollback automatique en cas d'erreur

## 🚀 Prochaines étapes

1. ✅ Models
2. ✅ Migrations
3. ✅ Repositories
4. ✅ Services
5. ✅ Actions
6. ⏳ Controllers (API REST)
7. ⏳ Requests (validation)
8. ⏳ Resources (transformation JSON)
9. ⏳ Routes
10. ⏳ Tests
