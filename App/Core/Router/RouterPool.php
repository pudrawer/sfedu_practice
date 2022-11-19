<?php

namespace App\Core\Router;

use App\Core\Blocks\NotFoundBlock;
use App\Core\Controllers\ControllerInterface;
use App\Core\Controllers\Web\NotFoundController;
use Laminas\Di\Di;

class RouterPool
{
    private $routers;
    private $di;

    /**
     * @param AbstractRouter[] $routers
     */
    public function __construct(Di $di, array $routers = [])
    {
        $this->routers = $routers;
        $this->di = $di;
    }

    /**
     * @param string $path
     * @return NotFoundController|ControllerInterface
     */
    public function chooseRouter(string $path)
    {
        foreach ($this->routers as $router) {
            $controller = $router->chooseController($path);

            if ($controller) {
                return $controller;
            }
        }

        return $this->di->get(
            NotFoundController::class,
            [
                'block' => $this->di->get(NotFoundBlock::class),
            ]
        );
    }
}
