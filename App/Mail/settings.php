<?php

return [
    \App\ModuleSettingsAggregator::SETTINGS_WEB_ROUTES     => [
        '/mail' => \App\Mail\Controllers\Web\MailController::class,
    ],
    \App\ModuleSettingsAggregator::SETTINGS_CONSOLE_ROUTES => [
        'notification:send' => \App\Mail\Controllers\Console\NotificationSendController::class,
    ],
];