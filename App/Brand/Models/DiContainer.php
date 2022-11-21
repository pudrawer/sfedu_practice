<?php

namespace App\Brand\Models;

class DiContainer extends \App\Core\Models\AbstractDiContainer
{
    private function assembleServices()
    {
        $this->instanceManager->setParameters(
            'App\Brand\Models\Service\BrandCarService',
            [
                'di' => $this->di,
                'resource' => $this->di->get(\App\Brand\Models\Resource\BrandResource::class),
            ]
        );
    }
}
