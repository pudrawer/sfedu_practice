<?php

return [
    \App\ModuleSettingsAggregator::SETTINGS_WEB_ROUTES => [
        '/login'        => \App\Account\Controllers\Web\LoginController::class,
        '/profileInfo'  => \App\Account\Controllers\Web\ProfileInfoController::class,
        '/registration' => \App\Account\Controllers\Web\RegistrationController::class,
        '/userCars'     => \App\Account\Controllers\Web\UserCarsController::class,
        '/userList'     => \App\Account\Controllers\Web\UserListController::class,
    ],
];
