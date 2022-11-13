<?php

namespace App\Models;

use Laminas\Di\Di;

class DiContainer
{
    private $di;
    private $instanceManager;

    public function __construct(Di $di = null)
    {
        if (!$di) {
            $di = new Di();
        }

        $this->di = $di;
        $this->instanceManager = $di->instanceManager();
    }

    public function assemble()
    {
        $reflection = new \ReflectionClass($this);

        foreach ($reflection->getMethods(\ReflectionMethod::IS_PRIVATE) as $method) {
            if (strpos($method->getName(), 'assemble') === 0) {
                $method->setAccessible(true);
                $method->invoke($this);
            }
        }
    }

    private function assembleRouters()
    {
        $this->instanceManager->setParameters(
            \App\Router\RouterPool::class,
            [
                'routers' => [
                    $this->di->get(
                        \App\Router\WebRouter::class,
                        ['di' => $this->di],
                    ),
                    $this->di->get(
                        \App\Router\ApiRouter::class,
                        ['di' => $this->di],
                    ),
                ]
            ]
        );
    }

    private function assembleLogger()
    {
        $this->instanceManager->setParameters(
            \App\Models\Logger::class,
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
            'App\Models\Cache\PredisCache',
            [
                'predis' => new \Predis\Client(),
            ]
        );
    }

    private function assembleCache()
    {
        $this->instanceManager->addTypePreference(
            'App\Models\Cache\AbstractCache',
            'App\Models\Cache\FileCache'
        );
    }

    private function assembleServices()
    {
        $this->instanceManager->setParameters(
            'App\Models\Service\BrandCarService',
            [
                'di'       => $this->di,
                'resource' => $this->di->get(\App\Models\Resource\BrandResource::class),
            ]
        );

        $this->instanceManager->setParameters(
            'App\Models\Service\LineCarService',
            [
                'di'       => $this->di,
                'resource' => $this->di->get(\App\Models\Resource\LineResource::class),
            ]
        );

        $this->instanceManager->setParameters(
            'App\Models\Service\MailService',
            [
                'di' => $this->di,
            ]
        );

        $this->instanceManager->setParameters(
            'App\Models\Service\VehicleApiService',
            [
                'di' => $this->di,
            ]
        );
    }

    private function assembleResources()
    {
        $this->instanceManager->setParameters(
            'App\Models\Resource\AbstractResource',
            [
                'di' => $this->di,
            ]
        );
    }
}
