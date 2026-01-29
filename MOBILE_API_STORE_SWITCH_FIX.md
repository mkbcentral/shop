# Fix: Rafraîchissement des données après changement de store (Mobile API)

## Problème identifié

Lorsqu'on change de store via `/api/mobile/switch-store/{storeId}`, les endpoints de stock et ventes ne rafraîchissaient pas correctement les données.

### Causes racines

1. **Cache incomplet** : La méthode `invalidateCache()` ne supprimait que 4 clés de cache sur ~15 clés utilisées
2. **Paramètre `store_id` dans les requêtes** : Si le client mobile envoie `?store_id=X` dans les requêtes, cela remplace le `current_store_id` de l'utilisateur

## Solutions appliquées

### 1. Extension de `invalidateCache()` ✅

**Fichier** : `app/Services/Mobile/MobileReportService.php`

Maintenant invalide TOUTES les clés de cache :
- `dashboard`, `sales_summary`, `stock_alerts`, `stock_summary`
- `low_stock`, `out_of_stock` 
- `sales_daily`, `sales_weekly`, `sales_monthly`
- `chart_week`, `chart_month`, `chart_quarter`, `chart_year`
- `top_products_{limit}_{days}` (combinaisons courantes)

### 2. Instructions pour le client Flutter ⚠️

**IMPORTANT** : Le client mobile ne doit **JAMAIS** envoyer le paramètre `?store_id=X` dans les requêtes API.

#### ❌ INCORRECT - Ne pas faire

```dart
// ❌ N'envoyez PAS store_id dans les paramètres
final response = await dio.get(
  '/api/mobile/stock/alerts',
  queryParameters: {'store_id': currentStoreId}, // ❌ MAUVAIS
);
```

#### ✅ CORRECT - Faire ceci

```dart
// ✅ Le backend utilise automatiquement le current_store_id de l'utilisateur
final response = await dio.get('/api/mobile/stock/alerts');
```

## Fonctionnement du système de store

### Priorité des sources de `store_id`

La fonction `effective_store_id()` utilise cet ordre de priorité :

1. **Paramètre de requête** `?store_id=X` (priorité haute)
2. **User's `current_store_id`** (priorité basse)

C'est pourquoi il est **crucial** de ne pas envoyer `?store_id=X` dans les requêtes.

### Workflow correct de switch store

#### Étape 1 : Switch de store

```dart
// Changer le store actif de l'utilisateur
final response = await dio.post('/api/mobile/switch-store/$newStoreId');

if (response.data['success']) {
  // Le current_store_id de l'utilisateur est mis à jour
  // Le cache est automatiquement invalidé
  
  // Mettre à jour le contexte local (optionnel)
  final context = response.data['data']['context'];
  await saveUserContext(context);
}
```

#### Étape 2 : Rafraîchir les données

```dart
// ✅ CORRECT : Pas de paramètre store_id
await fetchDashboard();      // /api/mobile/dashboard
await fetchStockAlerts();    // /api/mobile/stock/alerts
await fetchSalesSummary();   // /api/mobile/sales/summary
```

Le backend va :
1. Lire le `current_store_id` de l'utilisateur (mis à jour à l'étape 1)
2. Utiliser ce store_id pour filtrer les données
3. Générer de nouvelles clés de cache basées sur le nouveau store_id

### Exemple complet Flutter

```dart
class StoreService {
  final Dio _dio;
  
  // Changer de store
  Future<void> switchStore(int? storeId) async {
    try {
      // storeId peut être null pour voir tous les magasins (admin)
      final endpoint = storeId != null 
        ? '/api/mobile/switch-store/$storeId'
        : '/api/mobile/switch-store/null';
      
      final response = await _dio.post(endpoint);
      
      if (response.data['success']) {
        print('Store changé : ${response.data['message']}');
        
        // ⚠️ NE PAS sauvegarder store_id localement pour l'envoyer dans les requêtes
        // ✅ Sauvegarder seulement pour l'UI
        final context = response.data['data']['context'];
        await _saveContextForUI(context);
        
        // Rafraîchir toutes les données
        await refreshAllData();
      }
    } catch (e) {
      print('Erreur switch store: $e');
      rethrow;
    }
  }
  
  // Rafraîchir toutes les données après switch
  Future<void> refreshAllData() async {
    // ✅ Aucun paramètre store_id envoyé
    await Future.wait([
      fetchDashboard(),
      fetchStockAlerts(),
      fetchSalesSummary(),
      fetchStockMovements(),
    ]);
  }
  
  // ✅ CORRECT : Pas de paramètre store_id
  Future<Map<String, dynamic>> fetchDashboard() async {
    final response = await _dio.get('/api/mobile/dashboard');
    return response.data['data'];
  }
  
  // ✅ CORRECT : Pas de paramètre store_id
  Future<List<dynamic>> fetchStockAlerts() async {
    final response = await _dio.get('/api/mobile/stock/alerts');
    return response.data['data']['alerts'];
  }
}
```

## Endpoints affectés

Tous ces endpoints utilisent automatiquement le `current_store_id` :

### Stock
- `GET /api/mobile/stock/alerts`
- `GET /api/mobile/stock/alerts/list`
- `GET /api/mobile/stock/summary`
- `GET /api/mobile/stock/dashboard`
- `GET /api/mobile/stock/low-stock`
- `GET /api/mobile/stock/out-of-stock`
- `GET /api/mobile/stock/movements`
- `GET /api/mobile/stock/movements/grouped`

### Ventes
- `GET /api/mobile/sales/summary`
- `GET /api/mobile/sales/daily`
- `GET /api/mobile/sales/weekly`
- `GET /api/mobile/sales/monthly`
- `GET /api/mobile/sales/chart/{period}`

### Produits
- `GET /api/mobile/products`
- `GET /api/mobile/products/search`

## Vérification

Pour vérifier que le switch fonctionne :

1. **Appeler** `/api/mobile/switch-store/2`
2. **Vérifier** que `current_store_id` est mis à jour :
   ```bash
   # SQL
   SELECT id, name, current_store_id FROM users WHERE id = X;
   ```
3. **Appeler** `/api/mobile/stock/alerts` (SANS paramètre store_id)
4. **Vérifier** que les données correspondent au store 2

## Cache

Le système de cache utilise ce pattern de clé :
```
mobile_report_{organization_id}_{store_id}_{endpoint}
```

Exemples :
- `mobile_report_1_2_stock_alerts` (org 1, store 2)
- `mobile_report_1_all_stock_alerts` (org 1, tous les stores)

Quand on switch de store 2 → 3 :
- Les anciennes clés `mobile_report_1_2_*` restent en cache (pas grave, expireront)
- De nouvelles clés `mobile_report_1_3_*` sont créées
- Durée de vie : 300-600 secondes selon l'endpoint

## Commit

```bash
git add app/Services/Mobile/MobileReportService.php
git commit -m "fix: Extension de invalidateCache pour tous les endpoints de stock/ventes

- Ajout de low_stock, out_of_stock dans invalidateCache()
- Ajout de sales_daily/weekly/monthly et chart_*
- Ajout de top_products avec combinaisons courantes
- Résout le problème de données non rafraîchies après switch store"
```

## Notes pour le développeur Flutter

⚠️ **Point critique** : Ne JAMAIS stocker/envoyer `store_id` en paramètre de requête

Le `current_store_id` de l'utilisateur est la **source unique de vérité**. Quand vous changez de store via l'API, ce champ est mis à jour côté serveur et tous les endpoints suivants l'utiliseront automatiquement.

Si vous avez besoin de savoir quel store est actif pour l'UI, utilisez le champ `current_store` dans le contexte utilisateur :

```dart
final context = await dio.get('/api/mobile/context');
final currentStore = context.data['data']['current_store'];

// Pour l'UI
print('Store actif : ${currentStore['name']}'); // ✅ OK

// Pour les requêtes API  
// ❌ N'envoyez PAS currentStore['id'] dans les paramètres
```
