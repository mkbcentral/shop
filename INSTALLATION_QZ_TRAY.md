# Installation et Configuration QZ Tray

## 📋 Prérequis

- **Java Runtime Environment (JRE)** 8 ou supérieur
- Imprimante thermique USB, Bluetooth, ou réseau
- Navigateur moderne (Chrome, Firefox, Edge, Safari)

## 🔧 Installation

### 1. Télécharger QZ Tray

Visitez: **https://qz.io/download/**

Téléchargez la version pour votre système:
- Windows: `qz-tray-x.x.x.exe`
- macOS: `qz-tray-x.x.x.pkg`
- Linux: `qz-tray-x.x.x.run`

### 2. Installer QZ Tray

**Windows:**
```bash
# Double-cliquer sur le fichier .exe
# Suivre l'assistant d'installation
# QZ Tray démarrera automatiquement
```

**macOS:**
```bash
# Double-cliquer sur le fichier .pkg
sudo installer -pkg qz-tray-x.x.x.pkg -target /
# Autoriser dans Préférences Système > Sécurité
```

**Linux:**
```bash
chmod +x qz-tray-x.x.x.run
./qz-tray-x.x.x.run
```

### 3. Vérifier l'installation

1. Cherchez l'icône QZ Tray dans la barre système (🖨️)
2. Clic droit → **Advanced** → **Site Manager**
3. Ajouter votre domaine/localhost à la liste blanche

### 4. Connecter l'imprimante thermique

**USB:**
- Brancher l'imprimante
- Installer les drivers du fabricant
- L'imprimante apparaîtra dans la liste QZ Tray

**Bluetooth:**
- Appairer l'imprimante dans les paramètres Bluetooth
- Installer les drivers nécessaires
- Sélectionner dans QZ Tray

**Réseau:**
- Configurer l'IP de l'imprimante
- Ajouter comme imprimante réseau Windows/macOS/Linux
- Disponible dans QZ Tray

## 🔐 Configuration Production (Certificats)

Pour la production, vous devez générer vos propres certificats:

### 1. Générer le certificat

```bash
# Créer une clé privée
openssl genrsa -out private-key.pem 2048

# Créer une demande de certificat
openssl req -new -key private-key.pem -out cert-request.csr

# Auto-signer le certificat (valide 365 jours)
openssl x509 -req -days 365 -in cert-request.csr \
  -signkey private-key.pem -out digital-certificate.crt
```

### 2. Configurer dans l'application

Modifiez `resources/js/qz-thermal-printer.js`:

```javascript
qz.security.setCertificatePromise((resolve) => {
    // Remplacer par votre certificat
    fetch('/certificates/digital-certificate.crt')
        .then(response => response.text())
        .then(cert => resolve(cert));
});

qz.security.setSignaturePromise((toSign) => {
    return (resolve, reject) => {
        // Signature via votre serveur
        fetch('/api/sign-request', {
            method: 'POST',
            body: JSON.stringify({ request: toSign }),
            headers: { 'Content-Type': 'application/json' }
        })
        .then(response => response.text())
        .then(signature => resolve(signature))
        .catch(error => reject(error));
    };
});
```

### 3. Créer endpoint de signature (Laravel)

```php
// routes/web.php
Route::post('/api/sign-request', function (Request $request) {
    $privateKey = file_get_contents(storage_path('certificates/private-key.pem'));
    $toSign = $request->input('request');
    
    openssl_sign($toSign, $signature, $privateKey, OPENSSL_ALGO_SHA512);
    
    return base64_encode($signature);
});
```

## 📦 Inclure les bibliothèques

### Option 1: CDN (Développement)

Ajoutez dans votre `cash-register.blade.php`:

```html
<script src="https://cdn.jsdelivr.net/npm/qz-tray@2.2/qz-tray.min.js"></script>
```

### Option 2: NPM (Production recommandée)

```bash
npm install qz-tray
```

Dans `resources/js/app.js`:
```javascript
import qz from 'qz-tray';
window.qz = qz;
```

## 🧪 Tester l'installation

1. Ouvrir votre application POS
2. Ouvrir la console du navigateur (F12)
3. Taper:
```javascript
await thermalPrinter.initialize();
```
4. Si vous voyez "✅ Connecté à QZ Tray" → Succès!

## 🖨️ Imprimantes compatibles

**Testées et approuvées:**
- Epson TM-T20, TM-T88, TM-m30
- Star TSP100, TSP650, TSP700
- Citizen CT-S310, CT-S801
- Bixolon SRP-350, SRP-275
- XPrinter XP-80, XP-58

**Protocoles supportés:**
- ESC/POS (Standard)
- Star Line Mode
- SBPL (Citizen)

## 🔧 Dépannage

### QZ Tray ne démarre pas
```bash
# Windows: Vérifier les services
services.msc → QZ Tray → Démarrer

# macOS: Vérifier les autorisations
System Preferences → Security & Privacy → Autoriser QZ Tray

# Linux: Vérifier les logs
tail -f ~/.qz/qz-tray.log
```

### Imprimante non détectée
1. Vérifier drivers installés
2. Tester impression Windows/macOS normale
3. Redémarrer QZ Tray
4. Vérifier dans QZ Tray → Liste des imprimantes

### Erreurs de certificat
1. Vérifier que les certificats sont valides
2. Ajouter le domaine dans Site Manager
3. Autoriser HTTPS seulement en production

### Impression ne fonctionne pas
1. Ouvrir console navigateur (F12)
2. Vérifier les erreurs JavaScript
3. Tester avec: `qz.printers.find()` dans la console
4. Vérifier que l'imprimante est allumée et connectée

## 📞 Support

- **Documentation officielle:** https://qz.io/docs/
- **Forum communautaire:** https://qz.io/support/
- **GitHub Issues:** https://github.com/qzind/tray/issues

## 🚀 Commandes utiles

```javascript
// Lister les imprimantes
await qz.printers.find();

// Imprimante par défaut
await qz.printers.getDefault();

// Tester la connexion
qz.websocket.isActive();

// Statut QZ Tray
await qz.api.getVersion();
```

## ⚙️ Configuration avancée

### Démarrage automatique
QZ Tray se lance automatiquement au démarrage du système.

### Port personnalisé
Par défaut: `8181` et `8282` (WebSocket)

Pour changer: QZ Tray → Advanced → Port Configuration

### Logs
- Windows: `%APPDATA%\qz\qz-tray.log`
- macOS: `~/Library/Application Support/qz/qz-tray.log`
- Linux: `~/.qz/qz-tray.log`
