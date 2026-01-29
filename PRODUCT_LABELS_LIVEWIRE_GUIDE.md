# Guide d'utilisation des Étiquettes de Produits - Interface Livewire

## Vue d'ensemble

La fonctionnalité de génération d'étiquettes est maintenant disponible dans l'interface d'administration web (Livewire) pour permettre aux utilisateurs de générer et imprimer des étiquettes de produits avec codes-barres et QR codes.

## Accès à la fonctionnalité

### Navigation
1. Connectez-vous à l'interface d'administration
2. Accédez à **Produits** > **Liste des Produits**
3. Sélectionnez un ou plusieurs produits en cochant les cases
4. Dans le menu **Actions groupées**, sélectionnez **Générer Étiquettes**
5. Cliquez sur **Appliquer**

## Interface de Configuration

### Modal de Configuration des Étiquettes

Lorsque vous lancez la génération d'étiquettes, une fenêtre modale s'ouvre avec les options suivantes :

#### 1. Format d'étiquette
Trois formats disponibles :
- **Petite** (80×50mm) : Format compact pour petits produits
- **Moyenne** (100×70mm) : Format standard (par défaut)
- **Grande** (A4) : Format pleine page

#### 2. Colonnes par page
Sélectionnez le nombre de colonnes (1-4) pour organiser les étiquettes sur la page :
- **1 colonne** : Étiquettes centrées
- **2 colonnes** : Configuration standard (par défaut)
- **3 colonnes** : Format compact
- **4 colonnes** : Maximum de densité

#### 3. Options d'affichage
Trois cases à cocher pour personnaliser le contenu :
- ✅ **Afficher le prix** : Inclut le prix du produit (activé par défaut)
- ✅ **Afficher le code-barres** : Génère un code-barres Code 128 (activé par défaut)
- ✅ **Afficher le QR code** : Génère un QR code avec les données du produit (activé par défaut)

## Utilisation

### Génération d'étiquettes pour plusieurs produits

```
1. Cochez les produits souhaités dans la liste
2. Sélectionnez "Générer Étiquettes" dans les actions groupées
3. Configurez les options dans la modale
4. Cliquez sur "Générer"
5. Le PDF est automatiquement téléchargé
```

### Exemple de flux de travail

**Scénario** : Générer des étiquettes pour 10 nouveaux produits

1. **Filtrage** : Filtrez la liste pour afficher uniquement les nouveaux produits
2. **Sélection** : Cochez "Sélectionner tout" ou sélectionnez individuellement
3. **Configuration** :
   - Format : Moyenne (100×70mm)
   - Colonnes : 2
   - Toutes les options activées
4. **Génération** : Cliquez sur "Générer"
5. **Impression** : Ouvrez le PDF téléchargé et imprimez

## Format du PDF généré

### Structure des étiquettes

Chaque étiquette contient :
- **Nom du produit** (en gras, taille adaptée au format)
- **Code-barres** (si activé) : Code 128 scannable
- **Prix** (si activé) : Affiché en format monétaire (ex: 5 000 FC)
- **QR Code** (si activé) : Contient les données JSON du produit

### Données du QR Code

Le QR code contient les informations suivantes au format JSON :
```json
{
  "id": 123,
  "name": "Nom du produit",
  "sku": "SKU-001",
  "price": 5000,
  "barcode": "1234567890128"
}
```

## Composants techniques

### Fichiers modifiés/créés

1. **app/Livewire/Product/ProductIndex.php**
   - Ajout de la méthode `executeBulkAction()` avec cas 'generate_labels'
   - Dispatch de l'événement `openLabelModal`

2. **app/Livewire/Product/LabelModal.php** (nouveau)
   - Composant Livewire pour la modale de configuration
   - Méthode `generate()` pour créer le PDF

3. **resources/views/livewire/product/label-modal.blade.php** (nouveau)
   - Interface utilisateur de la modale
   - Formulaire de configuration avec Alpine.js

4. **resources/views/components/product/toolbar.blade.php**
   - Ajout de l'option "Générer Étiquettes" dans le menu déroulant

5. **routes/web.php**
   - Route `/download/temp/{filename}` pour télécharger les PDFs temporaires

6. **resources/views/components/layouts/app.blade.php**
   - Event listener Livewire pour gérer le téléchargement des PDFs

## Architecture technique

### Flux de données

```
ProductIndex (Livewire)
    ↓ (Sélection de produits)
    ↓ (Action "Générer Étiquettes")
    ↓ dispatch('openLabelModal', productIds)
    ↓
LabelModal (Livewire)
    ↓ (Configuration des options)
    ↓ generate()
    ↓
ProductLabelService
    ↓ generateLabelsPDF()
    ↓ (Génération du PDF)
    ↓
Stockage temporaire
    ↓ (Fichier dans storage/app/temp/)
    ↓
Route de téléchargement
    ↓ (Download et suppression)
    ↓
Utilisateur reçoit le PDF
```

### Communication entre composants

1. **ProductIndex** → **LabelModal**
   - Événement : `openLabelModal`
   - Données : `productIds` (array)

2. **LabelModal** → **Browser**
   - Événement : `downloadPdf`
   - Données : `url` (string)

3. **LabelModal** → **Toast**
   - Événement : `show-toast`
   - Données : `message`, `type`

## Service utilisé

### ProductLabelService

Le service `ProductLabelService` gère la génération des étiquettes :

```php
public function generateLabelsPDF(
    array $productIds,
    string $format = 'medium',
    int $columns = 2,
    array $options = []
): \Barryvdh\DomPDF\PDF
```

**Paramètres** :
- `$productIds` : IDs des produits à inclure
- `$format` : 'small', 'medium', ou 'large'
- `$columns` : Nombre de colonnes (1-4)
- `$options` : 
  - `show_price` : boolean (default: true)
  - `show_barcode` : boolean (default: true)
  - `show_qr_code` : boolean (default: true)

## Sécurité

### Authentification
- Requiert une session authentifiée
- Route protégée par le middleware `auth`

### Validation
- Validation des IDs de produits
- Validation du format et des colonnes
- Nettoyage automatique des fichiers temporaires

### Fichiers temporaires
- Stockés dans `storage/app/temp/`
- Supprimés automatiquement après téléchargement
- Nom unique avec timestamp

## Personnalisation

### Modifier le template

Pour personnaliser l'apparence des étiquettes, éditez :
```
resources/views/pdf/product-labels.blade.php
```

### Ajouter des options

Pour ajouter de nouvelles options à la modale :

1. Ajoutez une propriété dans `LabelModal.php` :
```php
public $showDescription = false;
```

2. Ajoutez le champ dans la vue `label-modal.blade.php` :
```html
<label class="flex items-center">
    <input type="checkbox" wire:model="showDescription">
    <span class="ml-2">Afficher la description</span>
</label>
```

3. Passez l'option au service :
```php
$options = [
    'show_description' => $this->showDescription,
];
```

## Dépannage

### Le PDF ne se télécharge pas
- Vérifiez que le dossier `storage/app/temp/` existe et est accessible en écriture
- Consultez les logs Laravel : `storage/logs/laravel.log`

### Erreur "Product not found"
- Vérifiez que les produits sélectionnés existent dans la base de données
- Vérifiez que l'utilisateur a accès au magasin des produits

### Code-barres ne s'affiche pas
- Vérifiez que le package `picqer/php-barcode-generator` est installé
- Vérifiez que le champ `barcode` existe dans la table `products`

### QR code ne se génère pas
- Vérifiez la connexion internet (l'API QR code nécessite un accès externe)
- Alternative : Utilisez une bibliothèque locale pour générer les QR codes

## Bonnes pratiques

1. **Sélection limitée** : Ne générez pas plus de 100 étiquettes à la fois pour éviter les problèmes de performance
2. **Test avant impression** : Visualisez toujours le PDF avant d'imprimer en masse
3. **Format adapté** : Choisissez le format selon le type d'imprimante (thermique, laser, etc.)
4. **Options cohérentes** : Utilisez les mêmes options pour tous les produits d'une même catégorie

## Support

Pour toute question ou problème :
1. Consultez les logs : `storage/logs/laravel.log`
2. Vérifiez la documentation de l'API : `PRODUCT_LABELS_API_TESTS.md`
3. Consultez le guide du service : `PRODUCT_LABELS_GUIDE.md`
