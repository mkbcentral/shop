# 🖨️ QZ Tray - Guide d'utilisation rapide

## ✅ Installation effectuée

Les fichiers suivants ont été créés/modifiés :

1. **`resources/js/qz-thermal-printer.js`** - Classe JavaScript pour gérer l'impression
2. **`public/js/qz-thermal-printer.js`** - Version publique du script
3. **`app/Livewire/Pos/CashRegister.php`** - Modifié pour dispatcher les événements d'impression
4. **`resources/views/livewire/pos/cash-register.blade.php`** - Intégré QZ Tray et les listeners
5. **`INSTALLATION_QZ_TRAY.md`** - Guide complet d'installation

## 🚀 Prochaines étapes

### 1. Installer QZ Tray sur le PC caisse

**Télécharger et installer :**
```
https://qz.io/download/
```

Choisir la version pour votre OS (Windows/Mac/Linux)

### 2. Vérifier que QZ Tray fonctionne

1. Après installation, cherchez l'icône 🖨️ dans la barre système
2. Clic droit sur l'icône → **Advanced** → **Site Manager**
3. Ajouter votre domaine (ou `localhost` pour développement)

### 3. Tester l'impression

1. Ouvrir votre application POS : `http://localhost:8000/pos`
2. Ouvrir la console navigateur (F12)
3. Ajouter des produits au panier
4. Cliquer sur "VALIDER & IMPRIMER"
5. Vérifier dans la console :
   ```
   ✅ Connecté à QZ Tray
   🖨️ Imprimantes trouvées: [...]
   🎯 Imprimante thermique sélectionnée: ...
   📄 Données reçues pour impression: {...}
   ✅ Impression réussie
   ```

### 4. Configuration de l'imprimante

Le script détecte automatiquement les imprimantes thermiques avec ces mots-clés :
- `thermal`
- `pos`
- `receipt`
- `tm-` (Epson TM series)
- `rp-` (Star RP series)
- `xprinter`

**Pour forcer une imprimante spécifique :**

Modifier `public/js/qz-thermal-printer.js` ligne ~50 :
```javascript
// Forcer une imprimante spécifique
this.printerName = "Nom exact de votre imprimante";
```

## 🔧 Fonctionnalités

### Impression automatique après paiement
Quand vous cliquez sur "VALIDER & IMPRIMER", le système :
1. ✅ Crée la vente dans la base de données
2. ✅ Crée la facture
3. ✅ Affiche le modal de confirmation
4. ✅ **Imprime automatiquement sur l'imprimante thermique**

### Réimprimer un ticket
Après avoir validé une vente, vous pouvez réimprimer :
- Cliquer sur "🖨️ Imprimer Ticket" dans le modal

### Format du ticket imprimé
```
        RECU DE CAISSE
        
        Facture: INV-2026-0001
        03/01/2026 14:30:25
        
--------------------------------
Produit A
  x2 x 5,000 CDF        10,000
Produit B
  x1 x 15,000 CDF       15,000
--------------------------------
Sous-total             25,000 CDF
Remise                 -1,000 CDF
================================
TOTAL                  24,000 CDF
================================
Payé                   25,000 CDF
Monnaie                 1,000 CDF

--------------------------------
     Merci de votre visite!
         A bientot!
--------------------------------
```

## 🐛 Dépannage

### "QZ Tray non disponible"
- Vérifier que QZ Tray est installé et lancé (icône dans barre système)
- Redémarrer QZ Tray
- Vérifier que le domaine est autorisé dans Site Manager

### "Aucune imprimante configurée"
- Vérifier que l'imprimante est allumée et connectée
- Tester une impression Windows normale
- Dans la console : `await qz.printers.find()` pour voir les imprimantes détectées

### L'impression ne fonctionne pas
1. Ouvrir la console navigateur (F12)
2. Regarder les messages d'erreur
3. Vérifier que l'imprimante accepte les commandes ESC/POS
4. Tester avec : `await thermalPrinter.initialize()`

### Erreur de certificat (en production)
Voir le guide complet dans `INSTALLATION_QZ_TRAY.md` section "Configuration Production"

## 📱 Test rapide

**Dans la console navigateur (F12) :**

```javascript
// Tester la connexion
await thermalPrinter.initialize();

// Lister les imprimantes
await qz.printers.find();

// Test d'impression
await thermalPrinter.printReceipt({
    invoice_number: "TEST-001",
    date: "03/01/2026 14:30",
    items: [
        { name: "Produit Test", quantity: 1, unit_price: 1000, total: 1000 }
    ],
    subtotal: 1000,
    discount: 0,
    tax: 0,
    total: 1000,
    paid: 1000,
    change: 0
});
```

## 🔐 Production (Hostinger)

### Exigences
1. ✅ Site en **HTTPS** (obligatoire)
2. ✅ Certificats SSL valides
3. ✅ QZ Tray installé sur chaque PC caisse
4. ✅ Domaine autorisé dans QZ Tray Site Manager

### Certificats personnalisés
Pour la production, générez vos propres certificats (voir `INSTALLATION_QZ_TRAY.md`)

Ou utilisez le service de signature QZ Tray Cloud : https://qz.io/pricing/

## 📞 Support

- Documentation complète : `INSTALLATION_QZ_TRAY.md`
- Site officiel : https://qz.io/docs/
- GitHub : https://github.com/qzind/tray

## ⚡ Commandes utiles

```javascript
// Console navigateur (F12)

// Initialiser
await thermalPrinter.initialize();

// Voir les imprimantes
await qz.printers.find();

// Changer d'imprimante
thermalPrinter.printerName = "Nom_Imprimante";

// Déconnecter
await thermalPrinter.disconnect();

// Version QZ Tray
await qz.api.getVersion();
```

## ✨ C'est prêt !

Votre système POS peut maintenant imprimer sur des imprimantes thermiques professionnelles, que ce soit en local ou avec l'application hébergée sur Hostinger !
