<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Étiquettes de Produits</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }

        .page {
            width: 100%;
            padding: 5mm;
        }

        /* Conteneur d'étiquettes */
        .labels-container {
            display: flex;
            flex-wrap: wrap;
            gap: 3mm;
        }

        .labels-container.medium {
            gap: 4mm;
        }

        .labels-container.large {
            gap: 5mm;
        }

        /* Base des étiquettes */
        .label {
            border: 1px solid #ddd;
            page-break-inside: avoid;
            display: inline-block;
            vertical-align: top;
        }

        /* Format Small - hauteur et padding réduits */
        .label.small {
            height: 45mm;
            padding: 2mm;
        }

        /* Format Medium - hauteur et padding moyens */
        .label.medium {
            height: 65mm;
            padding: 3mm;
        }

        /* Format Large - hauteur et padding agrandis */
        .label.large {
            height: 130mm;
            padding: 5mm;
        }

        /* Contenu de l'étiquette */
        .label-content {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .label-header {
            text-align: center;
            border-bottom: 1px solid #eee;
            padding-bottom: 3mm;
            margin-bottom: 4mm;
        }

        .product-name {
            font-size: 8px;
            font-weight: bold;
            margin-bottom: 2mm;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .variant-name {
            font-size: 7px;
            color: #666;
            margin-top: 2mm;
        }

        .product-reference {
            font-size: 6px;
            color: #999;
            margin-top: 1mm;
        }

        .label-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4mm;
            padding: 2mm 0;
        }

        .barcode-container {
            text-align: center;
            margin: 2mm 0;
        }

        .barcode-image {
            max-width: 100%;
            height: auto;
            margin: 2mm 0;
        }

        .barcode-text {
            font-size: 8px;
            color: #333;
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
            margin-top: 1mm;
        }

        .qr-code-container {
            text-align: center;
            margin: 2mm 0;
        }

        .qr-code-image {
            width: 20mm;
            height: 20mm;
        }

        .label.small .qr-code-image {
            width: 15mm;
            height: 15mm;
        }

        .label.large .qr-code-image {
            width: 25mm;
            height: 25mm;
        }

        .label-footer {
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 3mm;
            margin-top: 4mm;
        }

        .product-price {
            font-size: 10px;
            font-weight: bold;
            color: #000;
            margin-bottom: 2mm;
        }

        .label.small .product-price {
            font-size: 9px;
        }

        .label.large .product-price {
            font-size: 11px;
        }

        .product-category {
            display: none;
        }

        /* Page breaks */
        @media print {
            .label {
                page-break-inside: avoid;
            }
        }

        /* Grille dynamique - La largeur est contrôlée uniquement par le nombre de colonnes */
        .labels-container.col-1 .label {
            width: calc(100% - 2mm);
        }

        .labels-container.col-2 .label {
            width: calc(50% - 2mm);
        }

        .labels-container.col-3 .label {
            width: calc(33.33% - 2mm);
        }

        .labels-container.col-4 .label {
            width: calc(25% - 2mm);
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="labels-container {{ $format }} col-{{ $columns }}">
            @foreach($labels as $label)
                <div class="label {{ $format }}">
                    <div class="label-content">
                        <!-- En-tête -->
                        <div class="label-header">
                            <div class="product-name">{{ $label['name'] }}</div>
                            @if(isset($label['variant_name']))
                                <div class="variant-name">{{ $label['variant_name'] }}</div>
                            @endif
                            <div class="product-reference">REF: {{ $label['reference'] }}</div>
                        </div>

                        <!-- Corps -->
                        <div class="label-body">
                            @if($showBarcode && !empty($label['barcode_image']))
                                <div class="barcode-container">
                                    <img src="{{ $label['barcode_image'] }}" alt="Barcode" class="barcode-image">
                                    <div class="barcode-text">{{ $label['barcode'] }}</div>
                                </div>
                            @endif

                            @if($showQrCode && !empty($label['qr_code_image']))
                                <div class="qr-code-container">
                                    <img src="{{ $label['qr_code_image'] }}" alt="QR Code" class="qr-code-image">
                                </div>
                            @endif
                        </div>

                        <!-- Pied de page -->
                        <div class="label-footer">
                            @if($showPrice)
                                <div class="product-price">{{ $label['price_formatted'] }}</div>
                            @endif
                            <div class="product-category">{{ $label['category'] }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
