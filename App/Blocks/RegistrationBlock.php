<?php

namespace App\Blocks;

class RegistrationBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'registration/registration.css',
    ];
    protected $fileRender = 'registration';

    public function render(): self
    {
        parent::prepareRenderedPage('main');

        return $this;
    }
}