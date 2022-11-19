<?php

namespace App\Account\Blocks;

use App\Core\Blocks\AbstractBlock;

class LoginBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'registration.css'
    ];
    protected $fileRender = 'login';
}
