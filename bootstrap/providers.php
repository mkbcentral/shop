<?php

return [
    App\Providers\AppServiceProvider::class,
    // App\Providers\VoltServiceProvider::class, // Désactivé - utilisation de Livewire classique
    App\Providers\FortifyServiceProvider::class,
    App\Providers\RepositoryServiceProvider::class,
    App\Providers\BusinessServiceProvider::class,
    App\Providers\ActionServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\PosEventServiceProvider::class,
];
