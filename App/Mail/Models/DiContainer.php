<?php

namespace App\Mail\Models;

class DiContainer extends \App\Core\Models\AbstractDiContainer
{
    private function assembleServices()
    {
        $this->instanceManager->setParameters(
            'App\Mail\Models\Service\MailService',
            [
                'di' => $this->di,
            ]
        );
    }
}
