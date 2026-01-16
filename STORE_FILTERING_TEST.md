# 🧪 Test du Filtrage par Magasin - Guide Rapide

## 🚀 Étape 1 : Créer les Utilisateurs de Test

```bash
php artisan db:seed --class=TestUsersSeeder
```

Cela créera automatiquement :
- ✅ `admin@stk.com` - Admin (voit TOUS les magasins)
- ✅ `manager@stk.com` - Manager (voit tout son magasin)
- ✅ `cashier1@stk.com` - Caissier (FILTRÉ - ne voit que son magasin)
- ✅ `staff1@stk.com` - Employé (FILTRÉ - ne voit que son magasin)
- ✅ `cashier2@stk.com` - Caissier magasin 2 (si existe)

**Mot de passe pour tous :** `password`

---

## 🧪 Étape 2 : Tester le Filtrage

### Test 1 : Se Connecter en tant que Cashier

1. **Déconnectez-vous** si vous êtes connecté
2. **Connectez-vous** avec :
   - Email : `cashier1@stk.com`
   - Mot de passe : `password`

3. **Vérifiez** :
   - ✅ Dashboard : Affiche uniquement les stats du Magasin 1
   - ✅ Produits : Liste uniquement les produits du Magasin 1
   - ✅ Ventes : Liste uniquement les ventes du Magasin 1
   - ✅ Stock : Mouvements uniquement du Magasin 1
   - ❌ Pas de sélecteur de magasin (ou grisé)

### Test 2 : Se Connecter en tant que Staff

1. **Déconnectez-vous**
2. **Connectez-vous** avec :
   - Email : `staff1@stk.com`
   - Mot de passe : `password`

3. **Vérifiez** : Même comportement que le cashier

### Test 3 : Se Connecter en tant que Manager

1. **Déconnectez-vous**
2. **Connectez-vous** avec :
   - Email : `manager@stk.com`
   - Mot de passe : `password`

3. **Vérifiez** :
   - ✅ Voit toutes les données de son magasin
   - ✅ Peut gérer le magasin
   - ❌ Ne voit pas les autres magasins

### Test 4 : Se Connecter en tant qu'Admin

1. **Déconnectez-vous**
2. **Connectez-vous** avec :
   - Email : `admin@stk.com`
   - Mot de passe : `password`

3. **Vérifiez** :
   - ✅ Dashboard : Stats de TOUS les magasins
   - ✅ Produits : Produits de TOUS les magasins
   - ✅ Ventes : Ventes de TOUS les magasins
   - ✅ Sélecteur de magasin : Disponible et fonctionnel

---

## 📊 Résultats Attendus

### Dashboard

| Utilisateur | Ventes Affichées | Produits Affichés | Stock Affiché |
|-------------|------------------|-------------------|---------------|
| **cashier1** | Magasin 1 UNIQUEMENT | Magasin 1 UNIQUEMENT | Magasin 1 UNIQUEMENT |
| **staff1** | Magasin 1 UNIQUEMENT | Magasin 1 UNIQUEMENT | Magasin 1 UNIQUEMENT |
| **manager** | Magasin 1 UNIQUEMENT | Magasin 1 UNIQUEMENT | Magasin 1 UNIQUEMENT |
| **admin** | TOUS LES MAGASINS | TOUS LES MAGASINS | TOUS LES MAGASINS |

### Liste des Produits

```sql
-- Ce que voit cashier1@stk.com
SELECT * FROM products WHERE store_id = 1;

-- Ce que voit admin@stk.com
SELECT * FROM products; -- Tous les magasins
```

---

## 🔍 Vérification en Base de Données

### Vérifier l'Assignation des Utilisateurs

```bash
php artisan tinker
```

```php
// Voir les magasins d'un utilisateur
$user = User::where('email', 'cashier1@stk.com')->first();
echo "Magasin actuel: " . $user->currentStore->name;
echo "Rôle: " . $user->getRoleInStore($user->current_store_id);

// Vérifier le filtrage
echo user_can_access_all_stores() ? 'Accès global' : 'Accès filtré';
echo user_is_cashier_or_staff() ? 'Cashier/Staff' : 'Manager/Admin';
```

### Compter les Produits par Magasin

```php
use App\Models\Product;

// Compter les produits par magasin
foreach (Store::all() as $store) {
    $count = Product::where('store_id', $store->id)->count();
    echo "{$store->name}: {$count} produits\n";
}
```

---

## 🎯 Scénarios de Test

### Scénario 1 : Cashier Crée une Vente

1. Connectez-vous en tant que `cashier1@stk.com`
2. Allez sur **POS** ou **Ventes → Nouvelle vente**
3. Créez une vente
4. **Vérifiez** : La vente doit avoir `store_id = 1` (magasin du cashier)

```php
$sale = Sale::latest()->first();
echo "Store ID: " . $sale->store_id; // Devrait être 1
```

### Scénario 2 : Cashier ne Voit pas les Produits d'un Autre Magasin

1. Connectez-vous en tant que `cashier1@stk.com` (Magasin 1)
2. Allez sur **Produits**
3. **Vérifiez** : Seuls les produits du Magasin 1 sont listés

```php
// Créer un produit pour le magasin 2
Product::create([
    'name' => 'Produit Magasin 2',
    'reference' => 'PROD-MAG2-001',
    'store_id' => 2,
    'category_id' => 1,
]);

// Se connecter en tant que cashier1@stk.com
// Le produit ne devrait PAS apparaître
```

### Scénario 3 : Admin Voit Tous les Produits

1. Connectez-vous en tant que `admin@stk.com`
2. Allez sur **Produits**
3. **Vérifiez** : Tous les produits de tous les magasins sont listés

### Scénario 4 : Changement de Magasin (Admin)

1. Connectez-vous en tant que `admin@stk.com`
2. Cliquez sur le **sélecteur de magasin** dans la navbar
3. Sélectionnez **Magasin 2**
4. **Vérifiez** : La page se recharge avec les données du Magasin 2

---

## ⚠️ Points de Vérification

### ✅ Ce qui DOIT fonctionner

- [x] Cashier voit uniquement les produits de son magasin
- [x] Staff voit uniquement les ventes de son magasin
- [x] Manager voit toutes les données de son magasin
- [x] Admin voit les données de tous les magasins
- [x] Création de vente : `store_id` est automatiquement assigné
- [x] Dashboard : Stats filtrées par magasin

### ❌ Ce qui NE DOIT PAS fonctionner

- [ ] Cashier accède aux produits d'un autre magasin
- [ ] Staff voit les ventes d'un autre magasin
- [ ] Manager change de magasin (sauf s'il gère plusieurs magasins)
- [ ] Utilisateur sans magasin assigné (middleware le bloque)

---

## 🐛 Debugging

### Problème : Cashier voit tous les produits

**Solution :**
```php
// Vérifier que le helper fonctionne
php artisan tinker

auth()->loginUsingId(3); // ID du cashier
echo user_can_access_all_stores() ? 'ERREUR' : 'OK';
echo current_store_id(); // Devrait retourner l'ID du magasin
```

### Problème : Admin ne voit pas tous les magasins

**Solution :**
```php
// Vérifier le rôle
$admin = User::where('email', 'admin@stk.com')->first();
echo $admin->role; // Devrait être 'admin'
echo $admin->isAdmin() ? 'OK' : 'ERREUR';
```

### Voir les Requêtes SQL

Dans `config/app.php`, activez le debug :
```php
'debug' => true,
```

Ou utilisez Debugbar :
```bash
composer require barryvdh/laravel-debugbar --dev
```

---

## 📝 Checklist de Test

- [ ] Seeder exécuté (`TestUsersSeeder`)
- [ ] Connexion cashier → voit uniquement son magasin
- [ ] Connexion staff → voit uniquement son magasin
- [ ] Connexion manager → voit son magasin
- [ ] Connexion admin → voit tous les magasins
- [ ] Création vente → `store_id` correct
- [ ] Dashboard → stats filtrées
- [ ] Produits → liste filtrée
- [ ] Stock → mouvements filtrés
- [ ] Sélecteur de magasin → fonctionne pour admin

---

## 🎉 Succès !

Si tous les tests passent, votre système de filtrage par magasin fonctionne parfaitement ! 🚀

**Les utilisateurs cashier et staff ne voient maintenant QUE les données de leur magasin assigné.**

---

**Version:** 1.0.0  
**Date:** 7 janvier 2026
