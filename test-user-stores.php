<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Store;

echo '=== Utilisateurs et leurs magasins ===' . PHP_EOL;

$users = User::with(['stores', 'currentStore', 'roles'])->get();

foreach ($users as $user) {
    echo PHP_EOL . "User: {$user->name} (ID: {$user->id})" . PHP_EOL;
    echo "  - Email: {$user->email}" . PHP_EOL;
    echo "  - current_store_id: " . ($user->current_store_id ?? 'NULL') . PHP_EOL;
    echo "  - currentStore: " . ($user->currentStore ? $user->currentStore->name : 'NULL') . PHP_EOL;
    echo "  - Stores assignés: ";
    if ($user->stores->count() > 0) {
        $storeNames = $user->stores->pluck('name')->implode(', ');
        echo $storeNames . PHP_EOL;
    } else {
        echo "Aucun" . PHP_EOL;
    }
    
    // Vérifier les rôles
    echo "  - Roles: " . $user->roles->pluck('name')->implode(', ') . PHP_EOL;
    echo "  - isAdmin(): " . ($user->isAdmin() ? 'true' : 'false') . PHP_EOL;
}

echo PHP_EOL . '=== Pivot table user_store ===' . PHP_EOL;
$pivots = \DB::table('user_store')->get();
foreach ($pivots as $pivot) {
    $user = User::find($pivot->user_id);
    $store = Store::find($pivot->store_id);
    echo "User {$pivot->user_id} ({$user->name}) - Store {$pivot->store_id} ({$store->name})" . PHP_EOL;
}
