<?php

namespace App\Core\Models;

class DiContainer extends AbstractDiContainer
{
    private function assembleRouters()
    {
        $this->instanceManager->setParameters(
            \App\Core\Router\RouterPool::class,
            [
                'routers' => [
                    $this->di->get(
                        \App\Core\Router\WebRouter::class,
                        ['di' => $this->di],
                    ),
                    $this->di->get(
                        \App\Core\Router\ApiRouter::class,
                        ['di' => $this->di],
                    ),
                ]
            ]
        );
    }

    private function assembleLogger()
    {
        $this->instanceManager->setParameters(
            \App\Core\Models\Logger::class,
            [
                'logger'         => $this->di->get(
                    \Monolog\Logger::class,
                    [
                        'name'   => 'general'
                    ],
                ),
                'warningHandler' => $this->di->get(
                    \Monolog\Handler\StreamHandler::class,
                    [
                        'stream' => APP_ROOT . '/var/log/warning.log',
                        'level'  => \Monolog\Logger::WARNING,
                    ]
                ),
                'errorHandler'   => $this->di->get(
                    \Monolog\Handler\StreamHandler::class,
                    [
                        'stream' => APP_ROOT . '/var/log/error.log',
                        'level'  => \Monolog\Logger::ERROR,
                    ]
                ),
            ]
        );
    }

    private function assemblePredisCache()
    {
        $this->instanceManager->setParameters(
            'App\Core\Models\Cache\PredisCache',
            [
                'predis' => new \Predis\Client(),
            ]
        );
    }

    private function assembleCache()
    {
        $this->instanceManager->addTypePreference(
            'App\Core\Models\Cache\AbstractCache',
            'App\Core\Models\Cache\FileCache'
        );
    }

    private function assembleServices()
    {
        $this->instanceManager->setParameters(
            'App\Core\Models\Service\VehicleApiService',
            [
                'di' => $this->di,
            ]
        );
    }

    private function assembleResources()
    {
        $this->instanceManager->setParameters(
            'App\Core\Models\Resource\AbstractResource',
            [
                'di' => $this->di,
            ]
        );
    }
}
