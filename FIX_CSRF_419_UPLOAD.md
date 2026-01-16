# Fix: Erreur 419 CSRF avec Upload d'Images

## 🐛 Problème
Lors de l'utilisation de `wire:navigate` avec des pages contenant des uploads d'images, une erreur 419 (CSRF Token Expired) peut survenir.

## 🔍 Cause
- `wire:navigate` utilise la navigation SPA sans recharger complètement la page
- Le token CSRF peut expirer entre le moment où la page est chargée et l'upload
- Les sessions peuvent expirer pendant que l'utilisateur sélectionne les fichiers

## ✅ Solutions Appliquées

### 1. Désactivation de wire:navigate sur les pages avec uploads
**Fichiers modifiés:** `resources/views/livewire/product/product-index.blade.php`

```blade
<!-- Avant -->
<a href="{{ route('products.create') }}" wire:navigate>

<!-- Après -->
<a href="{{ route('products.create') }}">
```

**Impact:** Les pages de création/édition avec uploads se chargent avec un rechargement complet, garantissant un token CSRF frais.

### 2. Rafraîchissement automatique du token CSRF
**Fichier modifié:** `resources/js/app.js`

Ajout d'un listener Livewire qui met à jour automatiquement le token CSRF après chaque navigation :

```javascript
document.addEventListener('livewire:navigated', () => {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (token) {
        // Mise à jour des champs CSRF
        document.querySelectorAll('input[name="_token"]').forEach(input => {
            input.value = token;
        });
        
        // Mise à jour axios
        if (window.axios) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
        }
    }
});
```

### 3. Navigation SPA conservée ailleurs
Les redirections après sauvegarde utilisent toujours `navigate: true` pour une expérience fluide :

```php
return $this->redirectRoute('products.index', navigate: true);
```

## 📊 Résultat

| Scénario | Avant | Après |
|----------|-------|-------|
| Accès page création | Navigation SPA (token peut expirer) | Rechargement complet (token frais) |
| Accès page édition | Navigation SPA (token peut expirer) | Rechargement complet (token frais) |
| Retour vers liste | Rechargement complet | Navigation SPA fluide |
| Token CSRF | Statique | Auto-rafraîchi |

## 🎯 Avantages

- ✅ Plus d'erreur 419 lors des uploads
- ✅ Token CSRF toujours à jour
- ✅ Navigation SPA préservée où approprié
- ✅ Expérience utilisateur améliorée

## 🔧 Configuration Optionnelle

Si les problèmes persistent, vous pouvez augmenter la durée de vie de la session dans `.env` :

```env
# Augmenter de 120 minutes (2h) à 240 minutes (4h)
SESSION_LIFETIME=240
```

## 📝 Note Technique

Cette approche suit les recommandations officielles de Laravel Livewire pour gérer les uploads de fichiers dans une application SPA. Les pages avec interactions sensibles (uploads, paiements) doivent éviter `wire:navigate` pour garantir la fraîcheur des tokens de sécurité.
