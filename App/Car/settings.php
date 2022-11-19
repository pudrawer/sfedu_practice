<?php

return [
    \App\ModuleSettingsAggregator::SETTINGS_WEB_ROUTES => [
        '/country' => \App\Car\Controllers\Web\CountryController::class,
        '/factory' => \App\Car\Controllers\Web\FactoryController::class,
    ],
];