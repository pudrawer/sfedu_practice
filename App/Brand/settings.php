<?php

return [
    \App\ModuleSettingsAggregator::SETTINGS_WEB_ROUTES => [
        '/carBrand'       => \App\Brand\Controllers\Web\CarBrandController::class,
        '/carBrandDelete' => \App\Brand\Controllers\Web\CarBrandDeleteController::class,
        '/carBrandList'   => \App\Brand\Controllers\Web\CarBrandListController::class,
    ],
    \App\ModuleSettingsAggregator::SETTINGS_API_ROUTES => [
        '/brands' => \App\Brand\Controllers\Api\BrandsController::class,
    ]
];