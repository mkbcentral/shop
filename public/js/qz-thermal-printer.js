/**
 * QZ Tray Thermal Printer Integration
 * Gère l'impression sur imprimantes thermiques via QZ Tray
 */

class ThermalPrinter {
    constructor() {
        this.connected = false;
        this.printerName = null;
        this.qz = null;
        // Configuration papier thermique
        // 58mm = 32 caractères, 80mm = 48 caractères
        this.paperWidth = 32; // Par défaut 58mm
        this.detectPaperWidth();
    }

    /**
     * Détecte la largeur du papier selon l'imprimante
     */
    detectPaperWidth() {
        // Détection basée sur le nom de l'imprimante ou configuration
        const savedWidth = localStorage.getItem('thermal_paper_width');
        if (savedWidth) {
            this.paperWidth = parseInt(savedWidth);
        } else {
            // Par défaut 58mm (32 caractères) - le plus courant
            this.paperWidth = 32;
        }
        console.log('📏 Largeur papier:', this.paperWidth, 'caractères');
    }

    /**
     * Configure la largeur du papier
     * @param {number} width - Largeur en caractères (32 pour 58mm, 48 pour 80mm)
     */
    setPaperWidth(width) {
        this.paperWidth = width;
        localStorage.setItem('thermal_paper_width', width);
        console.log('📏 Largeur papier configurée:', width, 'caractères');
    }

    /**
     * Initialise QZ Tray et se connecte
     */
    async initialize() {
        try {
            // Vérifier si qz est défini (CDN chargé)
            if (typeof qz === 'undefined') {
                console.error('❌ QZ Tray library non chargée');
                this.showError('Bibliothèque QZ Tray non chargée. Vérifiez votre connexion internet.');
                return false;
            }

            // Ne PAS définir de certificat ni de signature
            // Cela permettra à QZ Tray d'utiliser son système de mise en liste blanche
            // L'utilisateur devra autoriser une seule fois via l'interface QZ Tray

            // Connexion à QZ Tray
            if (!qz.websocket.isActive()) {
                console.log('🔄 Tentative de connexion à QZ Tray...');
                await qz.websocket.connect();
                console.log('✅ Connecté à QZ Tray');
            } else {
                console.log('✅ Déjà connecté à QZ Tray');
            }

            this.connected = true;

            // Toujours chercher l'imprimante si pas encore définie
            if (!this.printerName) {
                await this.findDefaultPrinter();
            }

            return true;
        } catch (error) {
            console.error('❌ Erreur connexion QZ Tray:', error);
            console.error('❌ Message d\'erreur:', error.message);

            // Message d'erreur plus détaillé
            let errorMsg = 'Impossible de se connecter à QZ Tray.\n\n';

            if (error.message && error.message.includes('WebSocket')) {
                errorMsg += '🔹 QZ Tray n\'est probablement pas démarré.\n';
                errorMsg += '🔹 Vérifiez que QZ Tray est dans la barre des tâches (icône QZ).\n';
            }

            errorMsg += '\n📥 Si QZ Tray n\'est pas installé:\nhttps://qz.io/download/';

            this.showError(errorMsg);
            return false;
        }
    }

    /**
     * Trouve l'imprimante thermique par défaut
     */
    async findDefaultPrinter() {
        try {
            console.log('🔍 Recherche d\'imprimantes...');
            const printers = await qz.printers.find();
            console.log('🖨️ Imprimantes trouvées:', printers);
            console.log('🖨️ Nombre d\'imprimantes:', printers.length);

            if (printers.length === 0) {
                console.warn('⚠️ Aucune imprimante détectée par QZ Tray');
                return;
            }

            // Vérifier d'abord s'il y a une imprimante configurée dans localStorage
            const configuredPrinter = localStorage.getItem('thermal_printer_name');
            console.log('💾 Imprimante en localStorage:', configuredPrinter);

            if (configuredPrinter && printers.includes(configuredPrinter)) {
                this.printerName = configuredPrinter;
                console.log('🎯 Imprimante configurée utilisée:', this.printerName);
                return;
            }

            // Sinon, chercher une imprimante thermique (noms courants)
            // Inclut USB, Bluetooth, et imprimantes réseau
            const thermalPrinter = printers.find(p => {
                const name = p.toLowerCase();
                const isMatch = name.includes('thermal') ||
                    name.includes('pos') ||
                    name.includes('receipt') ||
                    name.includes('epson') || // Epson printers
                    name.includes('tm-') || // Epson TM series
                    name.includes('tm-t') || // Epson TM-T series
                    name.includes('rp-') || // Star RP series
                    name.includes('star') || // Star printers
                    name.includes('xprinter') ||
                    name.includes('bluetooth') ||
                    name.includes('bt-') ||
                    name.includes('80mm') ||
                    name.includes('58mm') ||
                    name.includes('zj-') ||
                    name.includes('goojprt') ||
                    name.includes('pozer') ||
                    name.includes('pp200') ||
                    name.includes('pp-200') ||
                    name.includes('peripage') ||
                    name.includes('prt-') ||
                    name.includes('mini') ||
                    name.includes('portable');

                if (isMatch) {
                    console.log(`✅ Match trouvé: "${p}"`);
                }
                return isMatch;
            });

            if (thermalPrinter) {
                this.printerName = thermalPrinter;
                const connectionType = this.detectConnectionType(thermalPrinter);
                console.log(`🎯 Imprimante thermique sélectionnée: ${this.printerName} (${connectionType})`);
            } else if (printers.length > 0) {
                // Prendre la première imprimante disponible
                this.printerName = printers[0];
                console.log('📝 Aucune imprimante thermique reconnue, utilisation de:', this.printerName);
            }

            console.log('🖨️ this.printerName final:', this.printerName);
        } catch (error) {
            console.error('❌ Erreur recherche imprimante:', error);
        }
    }

    /**
     * Détecte le type de connexion d'une imprimante
     * @param {string} printerName - Nom de l'imprimante
     * @returns {string} Type de connexion (USB, Bluetooth, Réseau)
     */
    detectConnectionType(printerName) {
        const name = printerName.toLowerCase();
        if (name.includes('bluetooth') || name.includes('bt-')) {
            return '📡 Bluetooth';
        } else if (name.includes('network') || name.includes('ip') || name.includes('lan')) {
            return '🌐 Réseau';
        } else {
            return '🔌 USB';
        }
    }

    /**
     * Imprime un reçu de caisse
     * @param {Object} data - Données du reçu
     */
    async printReceipt(data) {
        try {
            console.log('🔵 printReceipt() appelé avec data:', data);
            console.log('🔵 this.connected:', this.connected);
            console.log('🔵 this.printerName:', this.printerName);

            // Initialiser si pas encore fait
            if (!this.connected) {
                console.log('🔵 Connexion à QZ Tray...');
                const initialized = await this.initialize();
                console.log('🔵 Initialisation:', initialized);
                if (!initialized) {
                    console.error('❌ Échec de l\'initialisation');
                    return;
                }
            }

            if (!this.printerName) {
                console.error('❌ Aucune imprimante configurée');
                this.showError('Aucune imprimante configurée');
                return;
            }

            console.log('🔵 Création de la configuration pour:', this.printerName);
            // Créer la configuration d'impression
            const config = qz.configs.create(this.printerName);

            console.log('🔵 Génération des commandes ESC/POS...');
            // Générer les commandes ESC/POS
            const commands = this.generateESCPOSCommands(data);
            console.log('🔵 Commandes générées:', commands.slice(0, 3)); // Afficher les premières commandes

            console.log('🔵 Envoi à l\'imprimante...');
            // Envoyer à l'imprimante
            await qz.print(config, commands);

            console.log('✅ Impression réussie');
            this.showSuccess('Impression envoyée à l\'imprimante');

        } catch (error) {
            console.error('❌ Erreur impression:', error);
            this.showError('Erreur lors de l\'impression: ' + error.message);
            throw error; // Re-throw pour que la promesse soit rejetée
        }
    }

    /**
     * Génère les commandes ESC/POS pour l'impression thermique
     * @param {Object} data - Données du reçu
     * @returns {Array} Commandes ESC/POS
     */
    generateESCPOSCommands(data) {
        const ESC = '\x1B';
        const GS = '\x1D';
        const width = this.paperWidth;
        const separator = '-'.repeat(width);
        const doubleSeparator = '='.repeat(width);

        const commands = [];

        // Initialiser l'imprimante
        commands.push(ESC + '@');

        // Configurer le jeu de caractères pour les accents (Code Page 858 - Multilingual Latin I + Euro)
        commands.push(ESC + 't' + '\x13'); // Code page 858

        // Espace initial
        commands.push('\n');

        // === EN-TETE ENTREPRISE ===
        // Priorité aux données de l'organisation, sinon localStorage, sinon valeurs par défaut
        const companyData = data.company || {};
        const companyName = this.removeAccents(companyData.name || localStorage.getItem('thermal_company_name') || 'VOTRE ENTREPRISE');
        const companyAddress = this.removeAccents(companyData.address || localStorage.getItem('thermal_company_address') || 'Votre Adresse');
        const companyCity = this.removeAccents(companyData.city || '');
        const companyPhone = companyData.phone || localStorage.getItem('thermal_company_phone') || '+243 XXX XXX XXX';
        const companyEmail = companyData.email || localStorage.getItem('thermal_company_email') || 'contact@entreprise.cd';
        const companyWebsite = companyData.website || localStorage.getItem('thermal_company_website') || 'www.votre-site.cd';
        const companyTaxId = companyData.tax_id || '';
        const companyCurrency = companyData.currency || 'CDF';

        commands.push(ESC + 'a' + '\x01'); // Centre
        commands.push(ESC + 'E' + '\x01'); // Gras ON
        commands.push(GS + '!' + '\x21');  // Double largeur
        commands.push(companyName + '\n');
        commands.push(GS + '!' + '\x00');  // Taille normale
        commands.push(ESC + 'E' + '\x00'); // Gras OFF

        // Informations entreprise
        if (companyAddress) {
            commands.push('Adresse: ' + companyAddress + '\n');
        }
        if (companyCity) {
            commands.push('Ville: ' + companyCity + '\n');
        }
        if (companyPhone) {
            commands.push('Tel: ' + companyPhone + '\n');
        }
        if (companyEmail) {
            commands.push('Email: ' + companyEmail + '\n');
        }
        if (companyTaxId) {
            commands.push('N.I.F: ' + companyTaxId + '\n');
        }
        commands.push('\n');

        // Titre du reçu
        commands.push(ESC + 'E' + '\x01'); // Gras ON
        commands.push(GS + '!' + '\x11');  // Double hauteur/largeur
        commands.push('RECU DE CAISSE\n');
        commands.push(GS + '!' + '\x00');  // Taille normale
        commands.push(ESC + 'E' + '\x00'); // Gras OFF
        commands.push(doubleSeparator + '\n');

        // Informations facture
        commands.push(ESC + 'a' + '\x00'); // Alignement gauche
        commands.push('Facture N: ' + data.invoice_number + '\n');
        commands.push('Date: ' + data.date + '\n');
        commands.push('Caissier: ' + (data.cashier || 'N/A') + '\n');
        if (data.client && data.client !== 'Client Comptant') {
            commands.push('Client: ' + data.client + '\n');
        }

        // === SECTION ARTICLES ===
        commands.push(doubleSeparator + '\n');

        // En-tête du tableau en gras
        commands.push(ESC + 'E' + '\x01'); // Gras ON
        const headerLine = this.formatTableRow('ARTICLE', 'QTE', 'P.U', 'TOTAL');
        commands.push(headerLine);
        commands.push(separator + '\n');
        commands.push(ESC + 'E' + '\x00'); // Gras OFF

        // Lignes du tableau - tout sur une seule ligne
        data.items.forEach((item) => {
            const qty = item.quantity.toString();
            const price = this.formatPriceShort(item.unit_price);
            const total = this.formatPriceShort(item.total);

            // Calculer la largeur disponible pour le nom
            const qtyWidth = this.paperWidth <= 32 ? 4 : 6;
            const priceWidth = this.paperWidth <= 32 ? 8 : 11;
            const totalWidth = this.paperWidth <= 32 ? 8 : 11;
            const nameWidth = this.paperWidth - qtyWidth - priceWidth - totalWidth;

            // Tronquer le nom si nécessaire
            const name = this.truncateText(item.name, nameWidth);

            // Formater la ligne complète
            const line = this.formatTableRow(name, qty, price, total);
            commands.push(line);
        });

        // === SECTION TOTAUX ===
        commands.push(doubleSeparator + '\n');

        // Sous-total
        commands.push(this.formatLine('Sous-total:', this.formatPrice(data.subtotal, companyCurrency)));

        // Remise
        if (data.discount > 0) {
            commands.push(this.formatLine('Remise:', '-' + this.formatPrice(data.discount, companyCurrency)));
        }

        // Taxe
        if (data.tax > 0) {
            commands.push(this.formatLine('Taxe:', this.formatPrice(data.tax, companyCurrency)));
        }

        // Ligne de séparation forte
        commands.push(doubleSeparator + '\n');

        // TOTAL en grand et gras
        commands.push(ESC + 'a' + '\x01'); // Centre
        commands.push(ESC + 'E' + '\x01'); // Gras ON
        commands.push(GS + '!' + '\x11');  // Double hauteur/largeur
        commands.push('TOTAL\n');
        commands.push(this.formatPrice(data.total, companyCurrency) + '\n');
        commands.push(GS + '!' + '\x00');  // Taille normale
        commands.push(ESC + 'E' + '\x00'); // Gras OFF
        commands.push(ESC + 'a' + '\x00'); // Alignement gauche

        // === SECTION PAIEMENT ===
        commands.push(doubleSeparator + '\n');

        // Montant payé
        commands.push(this.formatLine('Montant paye:', this.formatPrice(data.paid, companyCurrency)));

        // Monnaie rendue
        if (data.change > 0) {
            commands.push(ESC + 'E' + '\x01'); // Gras ON
            commands.push(this.formatLine('Monnaie rendue:', this.formatPrice(data.change, companyCurrency)));
            commands.push(ESC + 'E' + '\x00'); // Gras OFF
        }

        // === PIED DE PAGE ===
        commands.push('\n');
        commands.push(doubleSeparator + '\n');
        commands.push(ESC + 'a' + '\x01'); // Centre
        commands.push('\n');
        commands.push(ESC + 'E' + '\x01'); // Gras ON
        commands.push('MERCI DE VOTRE VISITE!\n');
        commands.push(ESC + 'E' + '\x00'); // Gras OFF
        commands.push('A bientot!\n');
        commands.push('\n');
        if (companyPhone) {
            commands.push('Service client: ' + companyPhone + '\n');
        }
        if (companyWebsite) {
            commands.push(companyWebsite + '\n');
        }
        commands.push('\n');
        commands.push(doubleSeparator + '\n');

        // Espace suffisant avant la coupe
        commands.push('\n\n\n');
        commands.push(GS + 'V' + '\x41' + '\x00'); // Coupe partielle

        return commands;
    }

    /**
     * Formate une ligne avec label à gauche et valeur à droite
     */
    formatLine(label, value) {
        const cleanLabel = this.removeAccents(label);
        const cleanValue = this.removeAccents(value);
        const spacing = ' '.repeat(Math.max(0, this.paperWidth - cleanLabel.length - cleanValue.length));
        return cleanLabel + spacing + cleanValue + '\n';
    }

    /**
     * Tronque un texte à la longueur maximale
     */
    truncateText(text, maxLength) {
        const cleanText = this.removeAccents(text || '');
        if (cleanText.length <= maxLength) return cleanText;
        return cleanText.substring(0, maxLength - 3) + '...'
    }

    /**
     * Formate une ligne de tableau avec colonnes alignées
     * @param {string} col1 - Article (nom ou vide)
     * @param {string} col2 - Quantité
     * @param {string} col3 - Prix unitaire
     * @param {string} col4 - Total
     */
    formatTableRow(col1, col2, col3, col4) {
        const width = this.paperWidth;

        // Définir les largeurs de colonnes
        // Pour 32 caractères: Article(12), Qte(4), P.U.(8), Total(8)
        // Pour 48 caractères: Article(20), Qte(6), P.U.(11), Total(11)
        let col1Width, col2Width, col3Width, col4Width;

        if (width <= 32) {
            col1Width = 12;
            col2Width = 4;
            col3Width = 8;
            col4Width = 8;
        } else {
            col1Width = 20;
            col2Width = 6;
            col3Width = 11;
            col4Width = 11;
        }

        // Tronquer et aligner les colonnes
        const c1 = this.padText(col1, col1Width, 'left');
        const c2 = this.padText(col2, col2Width, 'right');
        const c3 = this.padText(col3, col3Width, 'right');
        const c4 = this.padText(col4, col4Width, 'right');

        return c1 + c2 + c3 + c4 + '\n';
    }

    /**
     * Ajoute du padding à un texte
     * @param {string} text - Texte à padder
     * @param {number} width - Largeur totale
     * @param {string} align - 'left' ou 'right'
     */
    padText(text, width, align = 'left') {
        const str = this.removeAccents(text.toString());
        if (str.length >= width) {
            return str.substring(0, width);
        }

        const padding = ' '.repeat(width - str.length);
        return align === 'left' ? str + padding : padding + str;
    }

    /**
     * Supprime les accents d'une chaîne de caractères
     * @param {string} str - Chaîne avec accents
     * @returns {string} Chaîne sans accents
     */
    removeAccents(str) {
        if (!str) return '';
        const accentsMap = {
            'à': 'a', 'â': 'a', 'ä': 'a', 'á': 'a', 'ã': 'a',
            'è': 'e', 'ê': 'e', 'ë': 'e', 'é': 'e',
            'ì': 'i', 'î': 'i', 'ï': 'i', 'í': 'i',
            'ò': 'o', 'ô': 'o', 'ö': 'o', 'ó': 'o', 'õ': 'o',
            'ù': 'u', 'û': 'u', 'ü': 'u', 'ú': 'u',
            'ç': 'c', 'ñ': 'n',
            'À': 'A', 'Â': 'A', 'Ä': 'A', 'Á': 'A', 'Ã': 'A',
            'È': 'E', 'Ê': 'E', 'Ë': 'E', 'É': 'E',
            'Ì': 'I', 'Î': 'I', 'Ï': 'I', 'Í': 'I',
            'Ò': 'O', 'Ô': 'O', 'Ö': 'O', 'Ó': 'O', 'Õ': 'O',
            'Ù': 'U', 'Û': 'U', 'Ü': 'U', 'Ú': 'U',
            'Ç': 'C', 'Ñ': 'N',
            '°': 'o', '€': 'EUR', '£': 'GBP', '¥': 'JPY'
        };
        return str.split('').map(char => accentsMap[char] || char).join('');
    }

    /**
     * Formate un prix avec la devise
     * @param {number} amount - Montant à formater
     * @param {string} currency - Code de la devise (défaut: CDF)
     */
    formatPrice(amount, currency = 'CDF') {
        // Convertir en string sans formatage pour éviter les problèmes d'encodage
        const num = parseFloat(amount) || 0;
        const str = Math.floor(num).toString();
        // Utiliser uniquement des caractères ASCII de base
        return str + ' ' + currency;
    }

    /**
     * Formate un prix sans la devise (pour les colonnes étroites)
     * @param {number} amount - Montant à formater
     */
    formatPriceShort(amount) {
        const num = parseFloat(amount) || 0;
        return Math.floor(num).toString();
    }

    /**
     * Déconnexion de QZ Tray
     */
    async disconnect() {
        try {
            if (qz.websocket.isActive()) {
                await qz.websocket.disconnect();
                this.connected = false;
                console.log('✅ Déconnecté de QZ Tray');
            }
        } catch (error) {
            console.error('❌ Erreur déconnexion:', error);
        }
    }

    /**
     * Affiche un message d'erreur
     */
    showError(message) {
        console.error('🔴 Erreur impression:', message);

        // Utiliser l'événement Livewire si disponible
        if (window.Livewire) {
            // Utiliser show-toast pour cohérence avec le reste de l'app
            window.Livewire.dispatch('show-toast', {
                message: message.replace(/\n/g, ' '), // Enlever les retours ligne pour le toast
                type: 'error'
            });
        }

        // Toujours afficher une alerte pour les erreurs critiques de connexion
        if (message.includes('QZ Tray')) {
            alert('⚠️ ' + message);
        }
    }

    /**
     * Affiche un message de succès
     */
    showSuccess(message) {
        console.log('✅', message);
        if (window.Livewire) {
            window.Livewire.dispatch('show-toast', { message, type: 'success' });
        }
    }
}

// Créer une instance globale
window.thermalPrinter = new ThermalPrinter();

// Initialiser au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    console.log('🚀 ThermalPrinter initialisé');
    console.log('📋 Pour configurer une imprimante manuellement:');
    console.log("   localStorage.setItem('thermal_printer_name', 'NOM_DE_VOTRE_IMPRIMANTE');");
    console.log("   localStorage.setItem('thermal_paper_width', '48'); // 32 pour 58mm, 48 pour 80mm");
});
