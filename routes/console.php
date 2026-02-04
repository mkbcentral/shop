<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
*/

// Rapport horaire des ventes (toutes les heures de 8h à 22h)
Schedule::command('sales:notify-hourly')
    ->hourly()
    ->between('8:00', '22:00')
    ->withoutOverlapping();

// Rapport journalier des ventes (tous les jours à 21h)
Schedule::command('sales:notify-daily')
    ->dailyAt('21:00')
    ->withoutOverlapping();

/*
|--------------------------------------------------------------------------
| Subscription Tasks
|--------------------------------------------------------------------------
*/

// Vérifier les abonnements expirants et envoyer des notifications (tous les jours à 9h)
Schedule::command('subscriptions:check-expiring --days=7 --notify')
    ->dailyAt('09:00')
    ->withoutOverlapping()
    ->description('Notifier les organisations dont l\'abonnement expire bientôt');

// Traiter les abonnements expirés (tous les jours à minuit)
Schedule::command('subscriptions:check-expiring --process-expired')
    ->dailyAt('00:00')
    ->withoutOverlapping()
    ->description('Passer les abonnements expirés au plan gratuit');

// Vérifier les limites d'abonnement et envoyer des alertes (tous les jours à 10h)
Schedule::command('subscriptions:check-limits --threshold=80 --notify')
    ->dailyAt('10:00')
    ->withoutOverlapping()
    ->description('Notifier les organisations approchant les limites de leur abonnement');
