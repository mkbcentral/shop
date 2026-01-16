<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$movements = DB::table('stock_movements')
    ->select('store_id', DB::raw('COUNT(*) as count'))
    ->groupBy('store_id')
    ->get();

echo "Répartition des mouvements de stock par store:\n";
echo "==============================================\n";
foreach ($movements as $m) {
    echo "Store {$m->store_id}: {$m->count} mouvements\n";
}

echo "\nTotal mouvements: " . DB::table('stock_movements')->count() . "\n";
echo "Mouvements sans store_id: " . DB::table('stock_movements')->whereNull('store_id')->count() . "\n";
