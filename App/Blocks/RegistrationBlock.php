<?php

namespace App\Blocks;

class RegistrationBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'registration/registration.css',
    ];
    protected $fileRender = 'registration';
    protected $activeLink = 'main';
}
