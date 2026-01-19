# Intégration Flutter - Système de Rapports et Statistiques (ReportController)

## 📋 Contexte

Ce document décrit l'intégration Flutter avec l'API Laravel pour les rapports et statistiques de vente. L'API expose un contrôleur `ReportController` qui fournit des KPIs, graphiques d'évolution, statistiques détaillées et top produits.

---

## 🔗 API Endpoint

### GET /api/mobile/reports

**Description**: Récupère les données complètes de rapports pour une période donnée avec KPIs, graphiques, statistiques et top produits

**Query Parameters**:
- `period` (string, **required**) - Type de période: `week`, `month`, `year`, `custom`
- `store_id` (int, optional) - Filtrer par magasin (utilise le store actuel si absent)
- `start_date` (string, required si `period=custom`) - Date début (format: YYYY-MM-DD)
- `end_date` (string, required si `period=custom`) - Date fin (format: YYYY-MM-DD)
- `year` (int, optional) - Année spécifique (2020-2100)
- `month_number` (int, optional) - Numéro du mois (1-12)

---

## 📊 Exemples de Requêtes

```bash
# 1. Semaine en cours
GET /api/mobile/reports?period=week&store_id=6

# 2. Mois en cours
GET /api/mobile/reports?period=month&store_id=6

# 3. Année en cours
GET /api/mobile/reports?period=year&store_id=6

# 4. Mois spécifique (Février 2026)
GET /api/mobile/reports?period=month&year=2026&month_number=2&store_id=6

# 5. Période personnalisée
GET /api/mobile/reports?period=custom&start_date=2026-01-01&end_date=2026-01-15&store_id=6

# 6. Sans store_id (utilise le magasin actuel de l'utilisateur)
GET /api/mobile/reports?period=month
```

---

## 📤 Réponse JSON Complète

```json
{
  "success": true,
  "data": {
    "period": {
      "type": "week",
      "start_date": "2026-01-13",
      "end_date": "2026-01-19",
      "label": "13 - 19 Jan 2026"
    },
    
    "kpis": {
      "revenue": {
        "value": 125450.00,
        "formatted": "125.5K CDF",
        "change": 12.5
      },
      "sales_count": {
        "value": 342,
        "formatted": "342",
        "change": 8.2
      },
      "average_basket": {
        "value": 366.81,
        "formatted": "366.81 CDF",
        "change": 4.1
      },
      "gross_margin": {
        "value": 28.5,
        "formatted": "28.5%",
        "change": -2.3
      }
    },
    
    "chart_data": {
      "labels": ["Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim"],
      "values": [15200.0, 18500.0, 12300.0, 22100.0, 19800.0, 25400.0, 12150.0]
    },
    
    "detailed_stats": {
      "transactions_count": 342,
      "new_customers": 28,
      "products_sold": 1247,
      "returns_count": 12
    },
    
    "top_products": [
      {
        "id": 1,
        "name": "iPhone 15 Pro",
        "quantity_sold": 45,
        "revenue": 54000.00
      },
      {
        "id": 2,
        "name": "AirPods Pro",
        "quantity_sold": 82,
        "revenue": 20500.00
      },
      {
        "id": 3,
        "name": "MacBook Air M2",
        "quantity_sold": 12,
        "revenue": 15600.00
      },
      {
        "id": 4,
        "name": "iPad Air",
        "quantity_sold": 23,
        "revenue": 13800.00
      },
      {
        "id": 5,
        "name": "Apple Watch",
        "quantity_sold": 34,
        "revenue": 11900.00
      }
    ]
  }
}
```

**Codes d'erreur**:
- `422` - Validation échouée (paramètres invalides)
- `500` - Erreur serveur

---

## 📦 Modèles de Données Flutter

### 1. ReportData (Modèle principal)

```dart
class ReportData {
  final PeriodInfo period;
  final KPIs kpis;
  final ChartData chartData;
  final DetailedStats detailedStats;
  final List<TopProduct> topProducts;

  ReportData({
    required this.period,
    required this.kpis,
    required this.chartData,
    required this.detailedStats,
    required this.topProducts,
  });

  factory ReportData.fromJson(Map<String, dynamic> json) {
    return ReportData(
      period: PeriodInfo.fromJson(json['period']),
      kpis: KPIs.fromJson(json['kpis']),
      chartData: ChartData.fromJson(json['chart_data']),
      detailedStats: DetailedStats.fromJson(json['detailed_stats']),
      topProducts: (json['top_products'] as List)
          .map((p) => TopProduct.fromJson(p))
          .toList(),
    );
  }
}
```

### 2. PeriodInfo

```dart
class PeriodInfo {
  final String type;
  final String startDate;
  final String endDate;
  final String label;

  PeriodInfo({
    required this.type,
    required this.startDate,
    required this.endDate,
    required this.label,
  });

  factory PeriodInfo.fromJson(Map<String, dynamic> json) {
    return PeriodInfo(
      type: json['type'],
      startDate: json['start_date'],
      endDate: json['end_date'],
      label: json['label'],
    );
  }
}
```

### 3. KPIs

```dart
class KPIs {
  final KPIValue revenue;
  final KPIValue salesCount;
  final KPIValue averageBasket;
  final KPIValue grossMargin;

  KPIs({
    required this.revenue,
    required this.salesCount,
    required this.averageBasket,
    required this.grossMargin,
  });

  factory KPIs.fromJson(Map<String, dynamic> json) {
    return KPIs(
      revenue: KPIValue.fromJson(json['revenue']),
      salesCount: KPIValue.fromJson(json['sales_count']),
      averageBasket: KPIValue.fromJson(json['average_basket']),
      grossMargin: KPIValue.fromJson(json['gross_margin']),
    );
  }
}

class KPIValue {
  final double value;
  final String formatted;
  final double change;

  KPIValue({
    required this.value,
    required this.formatted,
    required this.change,
  });

  factory KPIValue.fromJson(Map<String, dynamic> json) {
    return KPIValue(
      value: (json['value'] as num).toDouble(),
      formatted: json['formatted'],
      change: (json['change'] as num).toDouble(),
    );
  }

  // Helper pour déterminer la couleur selon le changement
  Color get changeColor {
    if (change > 0) return Colors.green;
    if (change < 0) return Colors.red;
    return Colors.grey;
  }

  // Helper pour l'icône
  IconData get changeIcon {
    if (change > 0) return Icons.trending_up;
    if (change < 0) return Icons.trending_down;
    return Icons.trending_flat;
  }
}
```

### 4. ChartData

```dart
class ChartData {
  final List<String> labels;
  final List<double> values;

  ChartData({
    required this.labels,
    required this.values,
  });

  factory ChartData.fromJson(Map<String, dynamic> json) {
    return ChartData(
      labels: List<String>.from(json['labels']),
      values: (json['values'] as List).map((v) => (v as num).toDouble()).toList(),
    );
  }

  // Helper pour obtenir la valeur maximale (utile pour le graphique)
  double get maxValue => values.isEmpty ? 0 : values.reduce((a, b) => a > b ? a : b);
  
  // Helper pour obtenir la valeur minimale
  double get minValue => values.isEmpty ? 0 : values.reduce((a, b) => a < b ? a : b);
  
  // Helper pour la somme totale
  double get totalValue => values.fold(0, (sum, value) => sum + value);
}
```

### 5. DetailedStats

```dart
class DetailedStats {
  final int transactionsCount;
  final int newCustomers;
  final int productsSold;
  final int returnsCount;

  DetailedStats({
    required this.transactionsCount,
    required this.newCustomers,
    required this.productsSold,
    required this.returnsCount,
  });

  factory DetailedStats.fromJson(Map<String, dynamic> json) {
    return DetailedStats(
      transactionsCount: json['transactions_count'],
      newCustomers: json['new_customers'],
      productsSold: json['products_sold'],
      returnsCount: json['returns_count'],
    );
  }
}
```

### 6. TopProduct

```dart
class TopProduct {
  final int id;
  final String name;
  final int quantitySold;
  final double revenue;

  TopProduct({
    required this.id,
    required this.name,
    required this.quantitySold,
    required this.revenue,
  });

  factory TopProduct.fromJson(Map<String, dynamic> json) {
    return TopProduct(
      id: json['id'],
      name: json['name'],
      quantitySold: json['quantity_sold'],
      revenue: (json['revenue'] as num).toDouble(),
    );
  }
}
```

---

## 🛠️ Implémentation

### 1. Service API (ReportsApiService)

```dart
class ReportsApiService {
  final Dio _dio;
  final String baseUrl;

  ReportsApiService(this._dio, this.baseUrl);

  /// Récupérer les rapports
  Future<ReportData> getReports({
    required String period,
    int? storeId,
    String? startDate,
    String? endDate,
    int? year,
    int? monthNumber,
  }) async {
    try {
      final queryParams = {
        'period': period,
        if (storeId != null) 'store_id': storeId,
        if (startDate != null) 'start_date': startDate,
        if (endDate != null) 'end_date': endDate,
        if (year != null) 'year': year,
        if (monthNumber != null) 'month_number': monthNumber,
      };

      final response = await _dio.get(
        '$baseUrl/api/mobile/reports',
        queryParameters: queryParams,
      );

      return ReportData.fromJson(response.data['data']);
    } catch (e) {
      throw _handleError(e);
    }
  }

  /// Gestion des erreurs
  AppException _handleError(dynamic error) {
    if (error is DioException) {
      if (error.response != null) {
        final data = error.response!.data;
        if (data is Map<String, dynamic>) {
          return AppException(data['message'] ?? 'Erreur inconnue');
        }
      }
      return NetworkException('Erreur de connexion');
    }
    return AppException('Erreur inconnue');
  }
}
```

---

### 2. Provider/State Management (ReportsProvider)

```dart
class ReportsProvider extends ChangeNotifier {
  final ReportsApiService _apiService;

  ReportsProvider(this._apiService);

  // État
  ReportData? _reportData;
  bool _isLoading = false;
  String? _error;
  
  // Période sélectionnée
  String _selectedPeriod = 'week';
  int? _selectedYear;
  int? _selectedMonth;
  DateTime? _customStartDate;
  DateTime? _customEndDate;
  int? _selectedStoreId;

  // Getters
  ReportData? get reportData => _reportData;
  bool get isLoading => _isLoading;
  String? get error => _error;
  String get selectedPeriod => _selectedPeriod;
  int? get selectedYear => _selectedYear;
  int? get selectedMonth => _selectedMonth;

  /// Charger les rapports
  Future<void> loadReports() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      _reportData = await _apiService.getReports(
        period: _selectedPeriod,
        storeId: _selectedStoreId,
        startDate: _customStartDate?.toIso8601String().split('T')[0],
        endDate: _customEndDate?.toIso8601String().split('T')[0],
        year: _selectedYear,
        monthNumber: _selectedMonth,
      );
    } catch (e) {
      _error = e.toString();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  /// Changer la période
  void setPeriod(String period) {
    _selectedPeriod = period;
    _selectedYear = null;
    _selectedMonth = null;
    _customStartDate = null;
    _customEndDate = null;
    loadReports();
  }

  /// Sélectionner un mois spécifique
  void setSpecificMonth(int year, int month) {
    _selectedPeriod = 'month';
    _selectedYear = year;
    _selectedMonth = month;
    _customStartDate = null;
    _customEndDate = null;
    loadReports();
  }

  /// Sélectionner une période personnalisée
  void setCustomPeriod(DateTime startDate, DateTime endDate) {
    _selectedPeriod = 'custom';
    _customStartDate = startDate;
    _customEndDate = endDate;
    _selectedYear = null;
    _selectedMonth = null;
    loadReports();
  }

  /// Changer le magasin
  void setStore(int? storeId) {
    _selectedStoreId = storeId;
    loadReports();
  }

  /// Rafraîchir
  Future<void> refresh() => loadReports();
}
```

---

### 3. Écran Rapports (ReportsScreen)

```dart
class ReportsScreen extends StatefulWidget {
  @override
  _ReportsScreenState createState() => _ReportsScreenState();
}

class _ReportsScreenState extends State<ReportsScreen> {
  @override
  void initState() {
    super.initState();
    // Charger les rapports au démarrage
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<ReportsProvider>().loadReports();
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Rapports'),
        actions: [
          IconButton(
            icon: Icon(Icons.filter_list),
            onPressed: _showPeriodSelector,
          ),
        ],
      ),
      body: Consumer<ReportsProvider>(
        builder: (context, provider, child) {
          if (provider.isLoading && provider.reportData == null) {
            return Center(child: CircularProgressIndicator());
          }

          if (provider.error != null) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(Icons.error_outline, size: 48, color: Colors.red),
                  SizedBox(height: 16),
                  Text(provider.error!),
                  SizedBox(height: 16),
                  ElevatedButton(
                    onPressed: provider.refresh,
                    child: Text('Réessayer'),
                  ),
                ],
              ),
            );
          }

          if (provider.reportData == null) {
            return Center(child: Text('Aucune donnée disponible'));
          }

          final data = provider.reportData!;

          return RefreshIndicator(
            onRefresh: provider.refresh,
            child: ListView(
              padding: EdgeInsets.all(16),
              children: [
                // Sélecteur de période
                _buildPeriodSelector(provider),
                
                SizedBox(height: 16),
                
                // 4 Cartes KPI
                _buildKPICards(data.kpis),
                
                SizedBox(height: 24),
                
                // Graphique d'évolution
                _buildRevenueChart(data.chartData, data.period.label),
                
                SizedBox(height: 24),
                
                // Statistiques détaillées
                _buildDetailedStats(data.detailedStats),
                
                SizedBox(height: 24),
                
                // Top 5 Produits
                _buildTopProducts(data.topProducts),
              ],
            ),
          );
        },
      ),
    );
  }

  /// Sélecteur de période
  Widget _buildPeriodSelector(ReportsProvider provider) {
    return Card(
      child: Padding(
        padding: EdgeInsets.all(12),
        child: Row(
          children: [
            Icon(Icons.calendar_today, size: 20),
            SizedBox(width: 8),
            Expanded(
              child: Text(
                provider.reportData!.period.label,
                style: TextStyle(fontSize: 16, fontWeight: FontWeight.w500),
              ),
            ),
            TextButton(
              onPressed: _showPeriodSelector,
              child: Text('Changer'),
            ),
          ],
        ),
      ),
    );
  }

  /// Cartes KPI
  Widget _buildKPICards(KPIs kpis) {
    return Column(
      children: [
        Row(
          children: [
            Expanded(child: _buildKPICard('Chiffre d\'affaires', kpis.revenue, Icons.attach_money)),
            SizedBox(width: 12),
            Expanded(child: _buildKPICard('Ventes', kpis.salesCount, Icons.shopping_cart)),
          ],
        ),
        SizedBox(height: 12),
        Row(
          children: [
            Expanded(child: _buildKPICard('Panier moyen', kpis.averageBasket, Icons.shopping_basket)),
            SizedBox(width: 12),
            Expanded(child: _buildKPICard('Marge brute', kpis.grossMargin, Icons.percent)),
          ],
        ),
      ],
    );
  }

  Widget _buildKPICard(String title, KPIValue kpi, IconData icon) {
    return Card(
      elevation: 2,
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Icon(icon, size: 20, color: Colors.grey[600]),
                SizedBox(width: 8),
                Expanded(
                  child: Text(
                    title,
                    style: TextStyle(fontSize: 12, color: Colors.grey[600]),
                    overflow: TextOverflow.ellipsis,
                  ),
                ),
              ],
            ),
            SizedBox(height: 8),
            Text(
              kpi.formatted,
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
            SizedBox(height: 4),
            Row(
              children: [
                Icon(kpi.changeIcon, size: 16, color: kpi.changeColor),
                SizedBox(width: 4),
                Text(
                  '${kpi.change > 0 ? '+' : ''}${kpi.change.toStringAsFixed(1)}%',
                  style: TextStyle(
                    fontSize: 12,
                    color: kpi.changeColor,
                    fontWeight: FontWeight.w500,
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  /// Graphique d'évolution du CA
  Widget _buildRevenueChart(ChartData chartData, String periodLabel) {
    return Card(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Évolution du chiffre d\'affaires',
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
            ),
            Text(
              periodLabel,
              style: TextStyle(fontSize: 12, color: Colors.grey[600]),
            ),
            SizedBox(height: 16),
            SizedBox(
              height: 200,
              child: LineChart(
                LineChartData(
                  gridData: FlGridData(show: true),
                  titlesData: FlTitlesData(
                    leftTitles: AxisTitles(
                      sideTitles: SideTitles(showTitles: true, reservedSize: 40),
                    ),
                    bottomTitles: AxisTitles(
                      sideTitles: SideTitles(
                        showTitles: true,
                        getTitlesWidget: (value, meta) {
                          if (value.toInt() >= 0 && value.toInt() < chartData.labels.length) {
                            return Text(chartData.labels[value.toInt()], style: TextStyle(fontSize: 10));
                          }
                          return Text('');
                        },
                      ),
                    ),
                    topTitles: AxisTitles(sideTitles: SideTitles(showTitles: false)),
                    rightTitles: AxisTitles(sideTitles: SideTitles(showTitles: false)),
                  ),
                  borderData: FlBorderData(show: true),
                  lineBarsData: [
                    LineChartBarData(
                      spots: chartData.values
                          .asMap()
                          .entries
                          .map((e) => FlSpot(e.key.toDouble(), e.value))
                          .toList(),
                      isCurved: true,
                      color: Colors.blue,
                      barWidth: 3,
                      dotData: FlDotData(show: true),
                      belowBarData: BarAreaData(
                        show: true,
                        color: Colors.blue.withOpacity(0.1),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  /// Statistiques détaillées
  Widget _buildDetailedStats(DetailedStats stats) {
    return Card(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Statistiques détaillées',
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
            ),
            SizedBox(height: 16),
            _buildStatRow('Transactions', stats.transactionsCount.toString(), Icons.receipt),
            _buildStatRow('Nouveaux clients', stats.newCustomers.toString(), Icons.person_add),
            _buildStatRow('Produits vendus', stats.productsSold.toString(), Icons.inventory),
            _buildStatRow('Retours', stats.returnsCount.toString(), Icons.assignment_return),
          ],
        ),
      ),
    );
  }

  Widget _buildStatRow(String label, String value, IconData icon) {
    return Padding(
      padding: EdgeInsets.symmetric(vertical: 8),
      child: Row(
        children: [
          Icon(icon, size: 20, color: Colors.grey[600]),
          SizedBox(width: 12),
          Expanded(child: Text(label)),
          Text(
            value,
            style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
          ),
        ],
      ),
    );
  }

  /// Top 5 Produits
  Widget _buildTopProducts(List<TopProduct> products) {
    return Card(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Top 5 Produits',
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
            ),
            SizedBox(height: 16),
            ...products.asMap().entries.map((entry) {
              final index = entry.key;
              final product = entry.value;
              return _buildTopProductRow(index + 1, product);
            }).toList(),
          ],
        ),
      ),
    );
  }

  Widget _buildTopProductRow(int rank, TopProduct product) {
    return Padding(
      padding: EdgeInsets.symmetric(vertical: 8),
      child: Row(
        children: [
          Container(
            width: 24,
            height: 24,
            decoration: BoxDecoration(
              color: rank <= 3 ? Colors.amber : Colors.grey[300],
              shape: BoxShape.circle,
            ),
            child: Center(
              child: Text(
                '$rank',
                style: TextStyle(
                  fontSize: 12,
                  fontWeight: FontWeight.bold,
                  color: rank <= 3 ? Colors.white : Colors.grey[700],
                ),
              ),
            ),
          ),
          SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  product.name,
                  style: TextStyle(fontWeight: FontWeight.w500),
                  overflow: TextOverflow.ellipsis,
                ),
                Text(
                  '${product.quantitySold} vendus',
                  style: TextStyle(fontSize: 12, color: Colors.grey[600]),
                ),
              ],
            ),
          ),
          Text(
            '${product.revenue.toStringAsFixed(0)} CDF',
            style: TextStyle(fontWeight: FontWeight.bold),
          ),
        ],
      ),
    );
  }

  /// Dialogue de sélection de période
  void _showPeriodSelector() {
    showModalBottomSheet(
      context: context,
      builder: (context) {
        return Container(
          padding: EdgeInsets.all(16),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              ListTile(
                leading: Icon(Icons.calendar_view_week),
                title: Text('Cette semaine'),
                onTap: () {
                  context.read<ReportsProvider>().setPeriod('week');
                  Navigator.pop(context);
                },
              ),
              ListTile(
                leading: Icon(Icons.calendar_today),
                title: Text('Ce mois'),
                onTap: () {
                  context.read<ReportsProvider>().setPeriod('month');
                  Navigator.pop(context);
                },
              ),
              ListTile(
                leading: Icon(Icons.calendar_month),
                title: Text('Mois spécifique'),
                onTap: () {
                  Navigator.pop(context);
                  _showMonthPicker();
                },
              ),
              ListTile(
                leading: Icon(Icons.date_range),
                title: Text('Cette année'),
                onTap: () {
                  context.read<ReportsProvider>().setPeriod('year');
                  Navigator.pop(context);
                },
              ),
              ListTile(
                leading: Icon(Icons.event),
                title: Text('Période personnalisée'),
                onTap: () {
                  Navigator.pop(context);
                  _showCustomDatePicker();
                },
              ),
            ],
          ),
        );
      },
    );
  }

  /// Sélecteur de mois spécifique
  void _showMonthPicker() async {
    final now = DateTime.now();
    int selectedYear = now.year;
    int selectedMonth = now.month;

    await showDialog(
      context: context,
      builder: (context) {
        return StatefulBuilder(
          builder: (context, setState) {
            return AlertDialog(
              title: Text('Sélectionner un mois'),
              content: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  // Année
                  DropdownButton<int>(
                    value: selectedYear,
                    items: List.generate(5, (i) => now.year - i)
                        .map((year) => DropdownMenuItem(value: year, child: Text('$year')))
                        .toList(),
                    onChanged: (year) {
                      setState(() => selectedYear = year!);
                    },
                  ),
                  SizedBox(height: 16),
                  // Mois
                  Wrap(
                    spacing: 8,
                    runSpacing: 8,
                    children: List.generate(12, (i) {
                      final month = i + 1;
                      final isSelected = month == selectedMonth;
                      return ChoiceChip(
                        label: Text(_getMonthName(month)),
                        selected: isSelected,
                        onSelected: (selected) {
                          setState(() => selectedMonth = month);
                        },
                      );
                    }),
                  ),
                ],
              ),
              actions: [
                TextButton(
                  onPressed: () => Navigator.pop(context),
                  child: Text('Annuler'),
                ),
                ElevatedButton(
                  onPressed: () {
                    context.read<ReportsProvider>().setSpecificMonth(selectedYear, selectedMonth);
                    Navigator.pop(context);
                  },
                  child: Text('Valider'),
                ),
              ],
            );
          },
        );
      },
    );
  }

  /// Sélecteur de période personnalisée
  void _showCustomDatePicker() async {
    final now = DateTime.now();
    DateTimeRange? picked = await showDateRangePicker(
      context: context,
      firstDate: DateTime(2020),
      lastDate: now,
      initialDateRange: DateTimeRange(
        start: now.subtract(Duration(days: 7)),
        end: now,
      ),
    );

    if (picked != null) {
      context.read<ReportsProvider>().setCustomPeriod(picked.start, picked.end);
    }
  }

  String _getMonthName(int month) {
    const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 
                    'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
    return months[month - 1];
  }
}
```

---

## 📝 Priorités d'Implémentation

### Phase 1 (MVP) - Semaine 1
- [ ] Créer les modèles de données (ReportData, KPIs, ChartData, etc.)
- [ ] Implémenter ReportsApiService
- [ ] Créer ReportsProvider pour la gestion d'état
- [ ] Écran basique avec les 4 cartes KPI
- [ ] Sélecteur de période (semaine, mois, année)

### Phase 2 - Semaine 2
- [ ] Implémenter le graphique d'évolution avec fl_chart
- [ ] Afficher les statistiques détaillées
- [ ] Afficher le top 5 des produits
- [ ] Sélecteur de mois spécifique
- [ ] Pull-to-refresh

### Phase 3 - Semaine 3
- [ ] Période personnalisée avec date range picker
- [ ] Filtrage par magasin
- [ ] Animations et transitions
- [ ] Export PDF des rapports
- [ ] Mode hors-ligne avec cache

---

## 🔧 Configuration Requise

### Dependencies Flutter

```yaml
dependencies:
  dio: ^5.0.0
  provider: ^6.0.0
  intl: ^0.18.0
  fl_chart: ^0.66.0  # Pour les graphiques
  flutter_secure_storage: ^8.0.0
```

### Configuration Dio

```dart
final dio = Dio(BaseOptions(
  baseUrl: 'http://192.168.1.193:8082',
  connectTimeout: Duration(seconds: 30),
  receiveTimeout: Duration(seconds: 30),
));

// Intercepteur pour le token
dio.interceptors.add(InterceptorsWrapper(
  onRequest: (options, handler) async {
    final token = await _storage.read(key: 'auth_token');
    if (token != null) {
      options.headers['Authorization'] = 'Bearer $token';
    }
    return handler.next(options);
  },
));
```

---

## 📊 Types de Périodes Disponibles

| Type | Description | Paramètres requis |
|------|-------------|-------------------|
| `week` | Semaine en cours (Lun → Dim) | `period=week` |
| `month` | Mois en cours | `period=month` |
| `year` | Année en cours | `period=year` |
| `month` + `year` + `month_number` | Mois spécifique | `period=month&year=2026&month_number=2` |
| `custom` | Période personnalisée | `period=custom&start_date=2026-01-01&end_date=2026-01-31` |

---

## ⚠️ Notes Importantes

1. **Authentification**: L'endpoint nécessite un token Bearer
2. **Store Filtering**: Le paramètre `store_id` est optionnel (utilise le store actuel si absent)
3. **Période Actuelle**: Par défaut, les périodes `week`, `month`, `year` correspondent à la période en cours
4. **Comparaison**: Les pourcentages de changement comparent avec la période précédente de même durée
5. **Graphiques**: Labels adaptés automatiquement selon la période (Lun-Dim pour semaine, 01-31 pour mois, Jan-Déc pour année)
6. **Top Produits**: Limité aux 5 meilleurs produits par revenu
7. **Devise**: Tous les montants sont en CDF (Franc Congolais)

---

## 🎨 Personnalisations Recommandées

### Couleurs des KPIs
```dart
// Revenus: Vert
Color.fromRGBO(76, 175, 80, 1)

// Ventes: Bleu
Color.fromRGBO(33, 150, 243, 1)

// Panier moyen: Orange
Color.fromRGBO(255, 152, 0, 1)

// Marge: Violet
Color.fromRGBO(156, 39, 176, 1)
```

### Animations
```dart
// Transition lors du changement de période
AnimatedSwitcher(
  duration: Duration(milliseconds: 300),
  child: _buildKPICards(data.kpis),
)
```

---

## 📚 Ressources Additionnelles

- [Documentation API Rapports](ReportController.php)
- [Flutter fl_chart Documentation](https://pub.dev/packages/fl_chart)
- [Guide multi-store](MULTI_STORE_README.md)
