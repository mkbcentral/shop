# 👔 Système de Gestion de Boutique d'Habillement

Application Laravel pour la gestion complète d'une boutique de vêtements avec suivi des stocks, ventes et facturations.

## 📋 Fonctionnalités

- ✅ Gestion des produits et catégories
- ✅ Gestion des variations (tailles, couleurs)
- ✅ Suivi des mouvements de stock (entrées/sorties)
- ✅ Gestion des ventes et clients
- ✅ Génération de factures
- ✅ Gestion des fournisseurs et achats
- ✅ Historique complet et traçabilité

## 🗄️ Structure de la Base de Données

### Modèles Principaux

#### 1. Categories
Catégories de vêtements (Chemises, Pantalons, Robes, etc.)
```
- id
- name (string)
- description (text, nullable)
- slug (string)
- timestamps
```

#### 2. Products
Articles en catalogue
```
- id
- category_id (foreign key)
- name (string)
- description (text, nullable)
- reference (string, unique) - Code article
- price (decimal 10,2) - Prix de vente
- cost_price (decimal 10,2) - Prix d'achat
- image (string, nullable)
- status (enum: active, inactive)
- timestamps
- soft_deletes
```

#### 3. ProductVariants
Variations de produits (taille, couleur)
```
- id
- product_id (foreign key)
- size (string, nullable) - S, M, L, XL, etc.
- color (string, nullable)
- sku (string, unique) - Code unique
- stock_quantity (integer, default 0) - Stock actuel
- additional_price (decimal 8,2, default 0)
- timestamps
```

#### 4. StockMovements
Tous les mouvements de stock
```
- id
- product_variant_id (foreign key)
- type (enum: in, out) - Entrée ou Sortie
- movement_type (enum: purchase, sale, adjustment, transfer, return)
- quantity (integer)
- reference (string, nullable)
- reason (text, nullable)
- unit_price (decimal 10,2, nullable)
- total_price (decimal 10,2, nullable)
- date (date)
- user_id (foreign key)
- timestamps
```

#### 5. Clients
Base clients
```
- id
- name (string)
- phone (string, nullable)
- email (string, nullable)
- address (text, nullable)
- timestamps
- soft_deletes
```

#### 6. Sales
Ventes effectuées
```
- id
- client_id (foreign key, nullable)
- sale_number (string, unique)
- sale_date (datetime)
- subtotal (decimal 10,2)
- discount (decimal 10,2, default 0)
- tax (decimal 10,2, default 0)
- total (decimal 10,2)
- payment_method (enum: cash, card, transfer, cheque)
- payment_status (enum: pending, paid, partial, refunded)
- status (enum: pending, completed, cancelled)
- notes (text, nullable)
- user_id (foreign key) - Vendeur
- timestamps
- soft_deletes
```

#### 7. SaleItems
Lignes de vente (détail des articles vendus)
```
- id
- sale_id (foreign key)
- product_variant_id (foreign key)
- quantity (integer)
- unit_price (decimal 10,2)
- discount (decimal 10,2, default 0)
- subtotal (decimal 10,2)
- timestamps
```

#### 8. Invoices
Factures générées
```
- id
- sale_id (foreign key)
- invoice_number (string, unique)
- invoice_date (date)
- due_date (date, nullable)
- subtotal (decimal 10,2)
- tax (decimal 10,2)
- total (decimal 10,2)
- status (enum: draft, sent, paid, cancelled)
- timestamps
```

#### 9. Suppliers (Optionnel)
Fournisseurs
```
- id
- name (string)
- phone (string, nullable)
- email (string, nullable)
- address (text, nullable)
- timestamps
- soft_deletes
```

#### 10. Purchases (Optionnel)
Achats fournisseurs
```
- id
- supplier_id (foreign key)
- purchase_number (string, unique)
- purchase_date (date)
- total (decimal 10,2)
- status (enum: pending, received, cancelled)
- timestamps
```

## 🔗 Relations

```
Category (1) ──────> (N) Product
Product (1) ───────> (N) ProductVariant
ProductVariant (1) ─> (N) StockMovement
ProductVariant (1) ─> (N) SaleItem
Sale (1) ──────────> (N) SaleItem
Sale (1) ──────────> (1) Invoice
Client (1) ────────> (N) Sale
User (1) ──────────> (N) Sale (vendeur)
User (1) ──────────> (N) StockMovement
Supplier (1) ──────> (N) Purchase
```

## 💼 Cas d'Usage

### 1. Ajout d'un nouveau produit
```
1. Créer une Category si nécessaire
2. Créer un Product
3. Créer des ProductVariants (une par combinaison taille/couleur)
```

**Exemple :**
- Produit : Chemise en coton (ref: CH-001)
- Variantes :
  - CH-001-BLUE-M (Bleu, M, stock: 0)
  - CH-001-BLUE-L (Bleu, L, stock: 0)
  - CH-001-RED-M (Rouge, M, stock: 0)

### 2. Réception de stock (Entrée)
```
1. Créer un StockMovement :
   - type: in
   - movement_type: purchase
   - quantity: 50
   - product_variant_id: CH-001-BLUE-M
   
2. Mettre à jour ProductVariant.stock_quantity
   Ancien stock: 0
   Nouveau stock: 0 + 50 = 50
```

### 3. Vente à un client
```
1. Créer un Client (si nouveau)

2. Créer une Sale :
   - client_id: 1
   - sale_number: VT-2024-0001
   - subtotal: 30000
   - total: 30000
   - payment_method: cash
   - payment_status: paid
   
3. Créer des SaleItems :
   - product_variant_id: CH-001-BLUE-M
   - quantity: 2
   - unit_price: 15000
   - subtotal: 30000
   
4. Créer des StockMovements (automatique) :
   - type: out
   - movement_type: sale
   - quantity: 2
   - product_variant_id: CH-001-BLUE-M
   
5. Mettre à jour le stock :
   Ancien stock: 50
   Nouveau stock: 50 - 2 = 48
   
6. Créer une Invoice liée à la Sale
```

### 4. Sortie de stock (autre que vente)
```
Exemple: Transfert vers un autre magasin

1. Créer un StockMovement :
   - type: out
   - movement_type: transfer
   - quantity: 10
   - reason: "Transfert vers succursale centre-ville"
   
2. Mettre à jour le stock :
   Ancien stock: 48
   Nouveau stock: 48 - 10 = 38
```

### 5. Ajustement d'inventaire
```
Cas: Différence entre stock physique et système

1. Créer un StockMovement :
   - type: out (si perte) ou in (si surplus)
   - movement_type: adjustment
   - quantity: 3
   - reason: "Correction inventaire - articles endommagés"
   
2. Ajuster le stock en conséquence
```

### 6. Retour client
```
1. Créer un StockMovement :
   - type: in
   - movement_type: return
   - quantity: 1
   - reason: "Retour client - taille incorrecte"
   
2. Stock réintégré automatiquement

3. Éventuellement mettre à jour la Sale et créer un avoir
```

## 📊 Flux de Travail Global

```
┌─────────────────────────────────────────────────────────┐
│  1. CONFIGURATION INITIALE                              │
│  └─> Créer Categories                                   │
│  └─> Créer Suppliers                                    │
└─────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────┐
│  2. GESTION CATALOGUE                                   │
│  └─> Ajouter Products                                   │
│  └─> Définir ProductVariants (tailles/couleurs)         │
└─────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────┐
│  3. APPROVISIONNEMENT                                   │
│  └─> Créer Purchase (achat fournisseur)                 │
│  └─> StockMovement (type: in, movement_type: purchase)  │
│  └─> Stock mis à jour automatiquement                   │
└─────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────┐
│  4. VENTE                                               │
│  └─> Créer Client (si nécessaire)                       │
│  └─> Créer Sale + SaleItems                            │
│  └─> StockMovement automatique (type: out)              │
│  └─> Générer Invoice                                    │
│  └─> Stock déduit automatiquement                       │
└─────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────┐
│  5. GESTION COURANTE                                    │
│  └─> Ajustements inventaire                             │
│  └─> Retours clients                                    │
│  └─> Transferts entre magasins                          │
│  └─> Tous enregistrés dans StockMovements               │
└─────────────────────────────────────────────────────────┘
```

## 🎯 Avantages de cette Architecture

1. **Traçabilité complète** : Chaque mouvement de stock est enregistré avec date, quantité et responsable
2. **Historique préservé** : Soft deletes conservent les données pour les rapports
3. **Gestion fine** : Variations par taille/couleur avec stock indépendant
4. **Flexibilité** : Multiples types de mouvements (ventes, ajustements, transferts)
5. **Intégrité** : Relations claires entre ventes, articles et mouvements de stock
6. **Reporting** : Facile de générer des rapports de ventes, marges, mouvements

## 📈 Rapports Possibles

- Stock actuel par produit/variante
- Historique des mouvements de stock
- Chiffre d'affaires par période
- Produits les plus vendus
- Marge bénéficiaire (prix vente - coût achat)
- État des paiements
- Factures impayées
- Performance par vendeur

## 🚀 Installation

```bash
# Cloner le repository
git clone <repo-url>

# Installer les dépendances
composer install

# Configurer l'environnement
cp .env.example .env
php artisan key:generate

# Créer la base de données et migrer
php artisan migrate

# Lancer le serveur
php artisan serve
```

## 📝 Licence

Laravel Framework - Open Source

---

**Développé pour la gestion optimale de boutiques d'habillement** 🛍️
