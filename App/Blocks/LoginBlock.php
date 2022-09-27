<?php

namespace App\Blocks;

class LoginBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'registration.css'
    ];
    protected $fileRender = 'login';
}
