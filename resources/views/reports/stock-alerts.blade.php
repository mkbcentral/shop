@extends('reports.layouts.pdf')

@section('content')
    <!-- Summary -->
    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td style="width: 20%; text-align: center; background: #f9fafb; padding: 15px; border: 1px solid #e5e7eb;">
                <div class="summary-value">{{ $totals['total_alerts'] }}</div>
                <div class="summary-label">Total Alertes</div>
            </td>
            <td style="width: 20%; text-align: center; background: #fee2e2; padding: 15px; border: 1px solid #fecaca;">
                <div class="summary-value" style="color: #dc2626;">{{ $totals['out_of_stock_count'] }}</div>
                <div class="summary-label">Ruptures</div>
            </td>
            <td style="width: 20%; text-align: center; background: #fef3c7; padding: 15px; border: 1px solid #fde68a;">
                <div class="summary-value" style="color: #f59e0b;">{{ $totals['low_stock_count'] }}</div>
                <div class="summary-label">Stock Faible</div>
            </td>
            <td style="width: 20%; text-align: center; background: #fce7f3; padding: 15px; border: 1px solid #f9a8d4;">
                <div class="summary-value" style="color: #be185d;">{{ $totals['expired_count'] }}</div>
                <div class="summary-label">Expirés</div>
            </td>
            <td style="width: 20%; text-align: center; background: #fed7aa; padding: 15px; border: 1px solid #fdba74;">
                <div class="summary-value" style="color: #ea580c;">{{ $totals['expiring_soon_count'] }}</div>
                <div class="summary-label">Expire Bientôt</div>
            </td>
        </tr>
    </table>

    <!-- EXPIRED PRODUCTS -->
    @if(isset($expired) && $expired->count() > 0)
    <div class="alert alert-danger">
        <strong>🚨 PRODUITS EXPIRÉS</strong> - {{ $expired->count() }} produit(s) ont dépassé leur date d'expiration et doivent être retirés.
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 25%;">Produit</th>
                <th style="width: 15%;">SKU</th>
                <th style="width: 15%;">Catégorie</th>
                <th style="width: 12%;" class="text-center">Stock</th>
                <th style="width: 13%;" class="text-center">Date Expiration</th>
                <th style="width: 15%;" class="text-center">Jours Expirés</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expired as $index => $variant)
            @php
                $expiryDate = \Carbon\Carbon::parse($variant->product->expiry_date);
                $daysExpired = (int) abs(now()->diffInDays($expiryDate, false));
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $variant->product->name }}</strong>
                    @if($variant->size || $variant->color)
                    <br><small style="color: #666;">{{ $variant->size }} {{ $variant->color }}</small>
                    @endif
                </td>
                <td>{{ $variant->sku }}</td>
                <td>{{ $variant->product->category?->name ?? '—' }}</td>
                <td class="text-center">
                    <span class="badge badge-danger">{{ $variant->stock_quantity }}</span>
                </td>
                <td class="text-center">{{ $expiryDate->format('d/m/Y') }}</td>
                <td class="text-center" style="font-weight: bold; color: #be185d;">
                    {{ $daysExpired }} jour(s)
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- EXPIRING SOON -->
    @if(isset($expiringSoon) && $expiringSoon->count() > 0)
    <div class="alert alert-warning" style="margin-top: 20px;">
        <strong>⏰ EXPIRE BIENTÔT</strong> - {{ $expiringSoon->count() }} produit(s) vont expirer dans les 30 prochains jours.
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 25%;">Produit</th>
                <th style="width: 15%;">SKU</th>
                <th style="width: 15%;">Catégorie</th>
                <th style="width: 12%;" class="text-center">Stock</th>
                <th style="width: 13%;" class="text-center">Date Expiration</th>
                <th style="width: 15%;" class="text-center">Jours Restants</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expiringSoon as $index => $variant)
            @php
                $expiryDate = \Carbon\Carbon::parse($variant->product->expiry_date);
                $daysRemaining = (int) now()->diffInDays($expiryDate, false);
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $variant->product->name }}</strong>
                    @if($variant->size || $variant->color)
                    <br><small style="color: #666;">{{ $variant->size }} {{ $variant->color }}</small>
                    @endif
                </td>
                <td>{{ $variant->sku }}</td>
                <td>{{ $variant->product->category?->name ?? '—' }}</td>
                <td class="text-center">{{ $variant->stock_quantity }}</td>
                <td class="text-center">{{ $expiryDate->format('d/m/Y') }}</td>
                <td class="text-center" style="font-weight: bold; color: #ea580c;">
                    {{ $daysRemaining }} jour(s)
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Out of Stock -->
    @if($outOfStock->count() > 0)
    <div class="alert alert-danger">
        <strong>⚠ RUPTURES DE STOCK</strong> - {{ $outOfStock->count() }} produit(s) en rupture nécessitent un réapprovisionnement urgent.
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 30%;">Produit</th>
                <th style="width: 15%;">SKU</th>
                <th style="width: 15%;">Catégorie</th>
                <th style="width: 10%;" class="text-center">Stock</th>
                <th style="width: 10%;" class="text-center">Seuil</th>
                <th style="width: 15%;" class="text-center">À commander</th>
            </tr>
        </thead>
        <tbody>
            @foreach($outOfStock as $index => $variant)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $variant->product->name }}</strong>
                    @if($variant->size || $variant->color)
                    <br><small style="color: #666;">{{ $variant->size }} {{ $variant->color }}</small>
                    @endif
                </td>
                <td>{{ $variant->sku }}</td>
                <td>{{ $variant->product->category?->name ?? '—' }}</td>
                <td class="text-center">
                    <span class="badge badge-danger">{{ $variant->stock_quantity }}</span>
                </td>
                <td class="text-center">{{ $variant->low_stock_threshold }}</td>
                <td class="text-center" style="font-weight: bold; color: #dc2626;">
                    {{ max(0, $variant->low_stock_threshold - $variant->stock_quantity + 10) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Low Stock -->
    @if($lowStock->count() > 0)
    <div class="alert alert-warning" style="margin-top: 20px;">
        <strong>⚡ STOCK FAIBLE</strong> - {{ $lowStock->count() }} produit(s) ont un stock inférieur au seuil d'alerte.
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 30%;">Produit</th>
                <th style="width: 15%;">SKU</th>
                <th style="width: 15%;">Catégorie</th>
                <th style="width: 10%;" class="text-center">Stock</th>
                <th style="width: 10%;" class="text-center">Seuil</th>
                <th style="width: 15%;" class="text-center">À commander</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lowStock as $index => $variant)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $variant->product->name }}</strong>
                    @if($variant->size || $variant->color)
                    <br><small style="color: #666;">{{ $variant->size }} {{ $variant->color }}</small>
                    @endif
                </td>
                <td>{{ $variant->sku }}</td>
                <td>{{ $variant->product->category?->name ?? '—' }}</td>
                <td class="text-center">
                    <span class="badge badge-warning">{{ $variant->stock_quantity }}</span>
                </td>
                <td class="text-center">{{ $variant->low_stock_threshold }}</td>
                <td class="text-center" style="font-weight: bold; color: #f59e0b;">
                    {{ max(0, $variant->low_stock_threshold - $variant->stock_quantity + 10) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($outOfStock->count() === 0 && $lowStock->count() === 0 && (!isset($expired) || $expired->count() === 0) && (!isset($expiringSoon) || $expiringSoon->count() === 0))
    <div style="text-align: center; padding: 50px; color: #059669;">
        <div style="font-size: 48px;">✓</div>
        <div style="font-size: 18px; font-weight: bold; margin-top: 10px;">Aucune alerte de stock</div>
        <div style="color: #666; margin-top: 5px;">Tous les produits ont un niveau de stock suffisant et ne sont pas expirés.</div>
    </div>
    @endif
@endsection
