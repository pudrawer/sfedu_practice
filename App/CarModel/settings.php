<?php

return [
    \App\ModuleSettingsAggregator::SETTINGS_API_ROUTES => [
        '/models' => \App\CarModel\Controllers\Api\ModelsController::class,
    ],
    \App\ModuleSettingsAggregator::SETTINGS_WEB_ROUTES => [
        '/carModel'       => \App\CarModel\Controllers\Web\CarModelController::class,
        '/carModelDelete' => \App\CarModel\Controllers\Web\CarModelDeleteController::class,
    ],
];