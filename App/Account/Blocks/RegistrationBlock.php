<?php

namespace App\Account\Blocks;

use App\Core\Blocks\AbstractBlock;

class RegistrationBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'registration.css',
    ];
    protected $fileRender = 'registration';
}
