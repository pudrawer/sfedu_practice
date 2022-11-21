<?php

namespace App\CarLine\Models;

class DiContainer extends \App\Core\Models\AbstractDiContainer
{
    private function assembleServices()
    {
        $this->instanceManager->setParameters(
            'App\CarLine\Models\Service\LineCarService',
            [
                'di' => $this->di,
                'resource' => $this->di->get(\App\CarLine\Models\Resource\LineResource::class),
            ]
        );
    }
}
