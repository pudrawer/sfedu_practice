<?php

namespace App\Blocks;

use App\Models\User;

class ProfileInfoBlock extends AbstractBlock
{
    protected $fileRender = 'profile';
    protected $childStylesheetList = [
        'profile.css',
        'profile-nav.css',
    ];

    protected $childModels = [];

    public function setChildModels(User $userModel): self
    {
        $this->childModels[(string) $userModel] = $userModel;

        return $this;
    }

    public function getChildModels(): array
    {
        return $this->childModels;
    }
}
