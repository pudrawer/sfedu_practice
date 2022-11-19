<?php

return [
    \App\ModuleSettingsAggregator::SETTINGS_WEB_ROUTES     => [
        '/' => \App\Core\Controllers\Web\HomepageController::class,
    ],
    \App\ModuleSettingsAggregator::SETTINGS_CONSOLE_ROUTES => [
        'env:clean'     => \App\Core\Controllers\Console\EnvCleanController::class,
        'vehicle:parse' => \App\Core\Controllers\Console\VehicleParseController::class,
    ],
];