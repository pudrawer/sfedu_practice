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
        $this->commonRender('main');

        return $this;
    }

    public function setData(array $data): self
    {
        return $this;
    }
}