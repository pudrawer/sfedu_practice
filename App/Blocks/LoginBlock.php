<?php

namespace App\Blocks;

class LoginBlock extends AbstractBlock
{
    protected $activeLink = 'main';
    protected $childStylesheetList = [
        'registration/registration.css'
    ];
    protected $fileRender = 'login';
}
