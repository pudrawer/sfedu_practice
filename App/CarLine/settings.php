<?php

return [
    \App\ModuleSettingsAggregator::SETTINGS_API_ROUTES     => [
        '/lines' => \App\CarLine\Controllers\Api\LinesController::class,
    ],
    \App\ModuleSettingsAggregator::SETTINGS_WEB_ROUTES     => [
        '/carLine'       => \App\CarLine\Controllers\Web\CarLineController::class,
        '/carLineDelete' => \App\CarLine\Controllers\Web\CarLineDeleteController::class,
    ],
    \App\ModuleSettingsAggregator::SETTINGS_CONSOLE_ROUTES => [
        'line:export' => \App\CarLine\Controllers\Console\LineExportController::class,
    ],
];