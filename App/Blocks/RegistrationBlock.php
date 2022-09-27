<?php

namespace App\Blocks;

class RegistrationBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'registration.css',
    ];
    protected $fileRender = 'registration';
}
