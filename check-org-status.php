<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$org = App\Models\Organization::find(1);

echo "Organisation: " . $org->name . PHP_EOL;
echo "Plan: " . $org->subscription_plan->value . PHP_EOL;
echo "Payment Status: " . $org->payment_status->value . PHP_EOL;
echo "isAccessible: " . ($org->isAccessible() ? 'true' : 'false') . PHP_EOL;
