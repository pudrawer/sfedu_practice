<?php

namespace App\Core\Models;

use Laminas\Di\Di;

abstract class AbstractDiContainer
{
    protected $di;
    protected $instanceManager;

    public function __construct(Di $di)
    {
        $this->di = $di;
        $this->instanceManager = $this->di->instanceManager();
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
}
